<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans): JsonResponse
    {
        $cfg = (array) config('services.midtrans', []);
        $serverKey = (string) ($cfg['server_key'] ?? '');

        if ($serverKey === '') {
            Log::error('midtrans.notification.server_key_missing');
            return response()->json(['message' => 'Server key not configured'], 500);
        }

        $payload = (array) $request->all();

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '') {
            Log::warning('midtrans.notification.invalid_payload', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
            ]);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        if (!$midtrans->verifyNotificationSignature($payload)) {
            Log::warning('midtrans.notification.invalid_signature', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
            ]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        // Extract base order code (remove suffix like -abc123)
        $orderCode = $orderId;
        if (preg_match('/^(TF-[A-Z0-9]+)-[a-z0-9]+$/', $orderId, $matches)) {
            $orderCode = $matches[1];
        }

        $order = Order::query()->where('code', $orderCode)->first();
        if (!$order) {
            Log::info('midtrans.notification.order_not_found', [
                'order_id' => $orderId,
                'order_code' => $orderCode,
            ]);
            return response()->json(['message' => 'Order not found'], 200);
        }

        $previousStatus = $order->status;
        $updates = $midtrans->mapNotificationToOrderUpdates($order, $payload);
        $order->update($updates);

        Log::info('midtrans.notification.order_updated', [
            'order_id' => $orderId,
            'payment_status' => (string) ($updates['payment_status'] ?? ''),
            'status' => (string) ($updates['status'] ?? ''),
        ]);

        // Send email confirmation after successful payment (status changed to Dikemas)
        $newStatus = (string) ($updates['status'] ?? '');
        if ($newStatus === 'Dikemas' && $previousStatus === 'Menunggu Pembayaran') {
            try {
                $order->loadMissing(['user', 'items']);
                if ($order->user && $order->user->email) {
                    Mail::to($order->user->email)->send(new OrderConfirmation($order));
                    Log::info('midtrans.notification.email_sent', [
                        'order_id' => $orderId,
                        'email' => $order->user->email,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('midtrans.notification.email_failed', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['message' => 'ok']);
    }
}

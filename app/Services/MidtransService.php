<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MidtransService
{
    public function snapJsUrl(bool $isProduction): string
    {
        return $isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function statusApiBaseUrl(bool $isProduction): string
    {
        return $isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    public function createSnapToken(Order $order): string
    {
        $cfg = (array) config('services.midtrans', []);
        $serverKey = (string) ($cfg['server_key'] ?? '');
        $isProduction = (bool) ($cfg['is_production'] ?? false);
        $verifySsl = (bool) ($cfg['verify_ssl'] ?? true);

        if ($serverKey === '') {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diatur.');
        }

        $baseUrl = $isProduction
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';

        $order->loadMissing(['user', 'items']);

        $itemDetails = $order->items->map(function (OrderItem $it) {
            return [
                'id' => (string) $it->product_id,
                'price' => (int) $it->price,
                'quantity' => (int) $it->qty,
                'name' => (string) $it->product_title,
            ];
        })->values()->all();

        if (count($itemDetails) === 0) {
            $itemDetails = [
                [
                    'id' => (string) $order->code,
                    'price' => (int) $order->grand_total,
                    'quantity' => 1,
                    'name' => 'Pesanan '.$order->code,
                ],
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id' => (string) $order->code,
                'gross_amount' => (int) $order->grand_total,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => (string) ($order->user?->name ?? 'Pelanggan'),
                'email' => (string) ($order->user?->email ?? ''),
            ],
        ];

        Log::info('midtrans.snap.create.request', [
            'order_code' => (string) $order->code,
            'gross_amount' => (int) $order->grand_total,
            'items_count' => count($itemDetails),
            'is_production' => $isProduction,
            'verify_ssl' => $verifySsl,
        ]);

        $http = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.base64_encode($serverKey.':'),
        ]);

        if (!$verifySsl && !$isProduction) {
            Log::warning('midtrans.snap.create.ssl_verification_disabled', [
                'order_code' => (string) $order->code,
            ]);
            $http = $http->withoutVerifying();
        }

        try {
            $res = $http->post($baseUrl.'/snap/v1/transactions', $payload);
        } catch (\Throwable $e) {
            Log::error('midtrans.snap.create.exception', [
                'order_code' => (string) $order->code,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        $httpStatus = (int) $res->status();
        if ($httpStatus < 200 || $httpStatus >= 300) {
            Log::warning('midtrans.snap.create.http_error', [
                'order_code' => (string) $order->code,
                'http_status' => $httpStatus,
            ]);
            throw new \RuntimeException('Gagal membuat transaksi Midtrans: HTTP '.$httpStatus);
        }

        $json = (array) $res->json();
        $token = (string) ($json['token'] ?? '');

        if ($token === '') {
            Log::warning('midtrans.snap.create.missing_token', [
                'order_code' => (string) $order->code,
            ]);
            throw new \RuntimeException('Midtrans tidak mengembalikan snap token.');
        }

        Log::info('midtrans.snap.create.success', [
            'order_code' => (string) $order->code,
            'http_status' => $httpStatus,
        ]);

        return $token;
    }

    public function fetchTransactionStatus(string $orderId): array
    {
        $cfg = (array) config('services.midtrans', []);
        $serverKey = (string) ($cfg['server_key'] ?? '');
        $isProduction = (bool) ($cfg['is_production'] ?? false);
        $verifySsl = (bool) ($cfg['verify_ssl'] ?? true);

        if ($serverKey === '') {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diatur.');
        }

        $baseUrl = $this->statusApiBaseUrl($isProduction);

        $http = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Basic '.base64_encode($serverKey.':'),
        ]);

        if (!$verifySsl && !$isProduction) {
            $http = $http->withoutVerifying();
        }

        Log::info('midtrans.status.fetch.request', [
            'order_id' => (string) $orderId,
            'is_production' => $isProduction,
            'verify_ssl' => $verifySsl,
        ]);

        $res = $http->get($baseUrl.'/v2/'.urlencode((string) $orderId).'/status');

        $httpStatus = (int) $res->status();
        if ($httpStatus < 200 || $httpStatus >= 300) {
            Log::warning('midtrans.status.fetch.http_error', [
                'order_id' => (string) $orderId,
                'http_status' => $httpStatus,
            ]);
            throw new \RuntimeException('Gagal cek status Midtrans: HTTP '.$httpStatus);
        }

        $json = (array) $res->json();
        Log::info('midtrans.status.fetch.success', [
            'order_id' => (string) $orderId,
            'http_status' => $httpStatus,
            'transaction_status' => (string) ($json['transaction_status'] ?? ''),
        ]);

        return $json;
    }

    public function syncOrderFromTransactionStatus(Order $order): array
    {
        $status = $this->fetchTransactionStatus((string) $order->code);

        $payload = [
            'transaction_status' => (string) ($status['transaction_status'] ?? ''),
            'fraud_status' => (string) ($status['fraud_status'] ?? ''),
            'transaction_id' => (string) ($status['transaction_id'] ?? ''),
        ];

        $updates = $this->mapNotificationToOrderUpdates($order, $payload);
        $order->update($updates);

        Log::info('midtrans.status.sync.order_updated', [
            'order_id' => (string) $order->code,
            'payment_status' => (string) ($updates['payment_status'] ?? ''),
            'status' => (string) ($updates['status'] ?? ''),
        ]);

        return $updates;
    }

    public function verifyNotificationSignature(array $payload): bool
    {
        $cfg = (array) config('services.midtrans', []);
        $serverKey = (string) ($cfg['server_key'] ?? '');

        if ($serverKey === '') {
            return false;
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '') {
            return false;
        }

        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        return hash_equals($expectedSignature, $signatureKey);
    }

    public function mapNotificationToOrderUpdates(Order $order, array $payload): array
    {
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $transactionId = (string) ($payload['transaction_id'] ?? '');

        $paymentStatus = 'pending';
        $nextOrderStatus = (string) $order->status;
        $paidAt = null;

        $shouldKeepProgressStatus = in_array((string) $order->status, ['Dikirim', 'Selesai', 'Dibatalkan'], true);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $paymentStatus = 'pending';
                $nextOrderStatus = 'Menunggu Pembayaran';
            } else {
                $paymentStatus = 'paid';
                $nextOrderStatus = $shouldKeepProgressStatus ? (string) $order->status : 'Dikemas';
                $paidAt = now();
            }
        } elseif ($transactionStatus === 'settlement') {
            $paymentStatus = 'paid';
            $nextOrderStatus = $shouldKeepProgressStatus ? (string) $order->status : 'Dikemas';
            $paidAt = now();
        } elseif ($transactionStatus === 'pending') {
            $paymentStatus = 'pending';
            $nextOrderStatus = 'Menunggu Pembayaran';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $paymentStatus = 'failed';
            $nextOrderStatus = 'Dibatalkan';
        } elseif (in_array($transactionStatus, ['refund', 'partial_refund'], true)) {
            $paymentStatus = 'refund';
            $nextOrderStatus = 'Dibatalkan';
        }

        $updates = [
            'payment_provider' => 'midtrans',
            'payment_status' => $paymentStatus,
            'midtrans_transaction_id' => ($transactionId !== '' ? $transactionId : $order->midtrans_transaction_id),
            'status' => $nextOrderStatus,
        ];

        if ($paidAt) {
            $updates['paid_at'] = $paidAt;
        }

        return $updates;
    }
}

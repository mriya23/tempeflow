<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     */
    public Order $order;

    /**
     * The previous status.
     */
    public string $previousStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $previousStatus = '')
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
        $this->order->loadMissing(['user', 'items']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = $this->getStatusText($this->order->status);

        return new Envelope(
            subject: 'Pesanan #' . $this->order->code . ' ' . $statusText . ' - Tempe Jaya Mandiri',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-status-updated',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->user?->name ?? $this->order->recipient_name ?? 'Pelanggan',
                'orderCode' => $this->order->code,
                'orderDate' => $this->order->created_at?->format('d M Y, H:i'),
                'currentStatus' => $this->order->status,
                'previousStatus' => $this->previousStatus,
                'statusMessage' => $this->getStatusMessage($this->order->status),
                'statusColor' => $this->getStatusColor($this->order->status),
                'items' => $this->order->items,
                'grandTotal' => $this->formatRupiah((int) $this->order->grand_total),
                'recipientName' => $this->order->recipient_name,
                'recipientPhone' => $this->order->recipient_phone,
                'shippingAddress' => $this->order->shipping_address,
                'shippingCity' => $this->order->shipping_city,
                'trackUrl' => route('storefront.track', ['order_code' => $this->order->code]),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Format number to Rupiah.
     */
    private function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Get status text for email subject.
     */
    private function getStatusText(string $status): string
    {
        return match ($status) {
            'Dikemas' => 'Sedang Dikemas',
            'Dikirim' => 'Sedang Dikirim',
            'Selesai' => 'Telah Selesai',
            'Dibatalkan' => 'Dibatalkan',
            default => 'Update Status',
        };
    }

    /**
     * Get status message for email body.
     */
    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            'Dikemas' => 'Pesanan Anda sedang dikemas dengan penuh perhatian. Tim kami memastikan produk tempe Anda dalam kondisi terbaik sebelum dikirim.',
            'Dikirim' => 'Kabar baik! Pesanan Anda sudah dalam perjalanan menuju alamat tujuan. Mohon pastikan ada yang menerima paket.',
            'Selesai' => 'Pesanan Anda telah selesai dan diterima. Terima kasih telah berbelanja di Tempe Jaya Mandiri! Kami berharap Anda menikmati tempe berkualitas kami.',
            'Dibatalkan' => 'Pesanan Anda telah dibatalkan. Jika Anda tidak membatalkan pesanan ini atau memiliki pertanyaan, silakan hubungi kami.',
            default => 'Status pesanan Anda telah diperbarui.',
        };
    }

    /**
     * Get status color for email styling.
     */
    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'Menunggu Pembayaran' => '#7C3AED',
            'Dikemas' => '#D97706',
            'Dikirim' => '#2563EB',
            'Selesai' => '#059669',
            'Dibatalkan' => '#DC2626',
            default => '#6B7280',
        };
    }
}

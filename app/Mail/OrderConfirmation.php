<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     */
    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->order->loadMissing(['user', 'items']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Berhasil! Pesanan #' . $this->order->code . ' - Tempe Jaya Mandiri',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $shippingCost = (int) $this->order->shipping_cost;
        
        return new Content(
            markdown: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->user?->name ?? $this->order->recipient_name ?? 'Pelanggan',
                'orderCode' => $this->order->code,
                'orderDate' => $this->order->created_at?->format('d M Y, H:i'),
                'items' => $this->order->items,
                'subtotal' => $this->formatRupiah((int) $this->order->subtotal),
                'shippingCost' => $shippingCost,
                'shippingCostFormatted' => $shippingCost > 0 ? $this->formatRupiah($shippingCost) : 'GRATIS',
                'grandTotal' => $this->formatRupiah((int) $this->order->grand_total),
                'recipientName' => $this->order->recipient_name,
                'recipientPhone' => $this->order->recipient_phone,
                'shippingAddress' => $this->order->shipping_address,
                'shippingCity' => $this->order->shipping_city,
                'shippingPostalCode' => $this->order->shipping_postal_code,
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
}

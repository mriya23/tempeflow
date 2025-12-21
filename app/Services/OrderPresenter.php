<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class OrderPresenter
{
    public function formatRupiah(int $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    public function computeCartTotals(array $cart, array $catalogById): array
    {
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $p = $catalogById[(int) $productId] ?? null;
            if (!$p) {
                continue;
            }

            $qty = max(1, (int) $qty);
            $price = (int) ($p['price'] ?? 0);
            $total += ($price * $qty);
        }

        return [
            'items_count' => count($cart),
            'cart_count' => array_sum($cart),
            'total' => $total,
            'total_formatted' => $this->formatRupiah($total),
            'grand_total' => $total,
            'grand_total_formatted' => $this->formatRupiah($total),
        ];
    }

    public function orderToPayload(Order $order): array
    {
        $order->loadMissing('items');

        $items = $order->items->map(fn (OrderItem $it) => [
            'id' => (int) $it->product_id,
            'title' => (string) $it->product_title,
            'tag' => (string) ($it->product_tag ?? ''),
            'qty' => (int) $it->qty,
            'price' => (int) $it->price,
            'subtotal' => (int) $it->subtotal,
        ])->values()->all();

        return [
            'code' => (string) $order->code,
            'status' => (string) $order->status,
            'created_at' => $order->created_at ? $order->created_at->toDateTimeString() : null,
            'recipient_name' => (string) ($order->recipient_name ?? ''),
            'recipient_phone' => (string) ($order->recipient_phone ?? ''),
            'shipping_address' => (string) ($order->shipping_address ?? ''),
            'shipping_city' => (string) ($order->shipping_city ?? ''),
            'shipping_postal_code' => (string) ($order->shipping_postal_code ?? ''),
            'shipping_note' => (string) ($order->shipping_note ?? ''),
            'items' => $items,
            'totals' => [
                'total' => (int) $order->subtotal,
                'total_formatted' => $this->formatRupiah((int) $order->subtotal),
                'grand_total' => (int) $order->grand_total,
                'grand_total_formatted' => $this->formatRupiah((int) $order->grand_total),
            ],
        ];
    }
}

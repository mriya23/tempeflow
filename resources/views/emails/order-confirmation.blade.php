<x-mail::message>
# Pembayaran Berhasil! 🎉

Halo **{{ $customerName }}**,

Terima kasih! Pembayaran untuk pesanan Anda telah berhasil dikonfirmasi. Pesanan Anda sekarang sedang diproses dan akan segera dikemas.

---

## Detail Pesanan

**Nomor Pesanan:** {{ $orderCode }}
**Tanggal:** {{ $orderDate }}
**Status:** ✅ Pembayaran Berhasil - Sedang Dikemas

---

## Produk yang Dipesan

<x-mail::table>
| Produk | Qty | Harga | Subtotal |
|:-------|:---:|------:|---------:|
@foreach ($items as $item)
| {{ $item->product_title }} | {{ $item->qty }} | Rp {{ number_format($item->price, 0, ',', '.') }} | Rp {{ number_format($item->subtotal, 0, ',', '.') }} |
@endforeach
</x-mail::table>

<x-mail::panel>
**Subtotal:** {{ $subtotal }}
**Ongkos Kirim:** {{ $shippingCostFormatted }}
**Total Dibayar:** **{{ $grandTotal }}**
</x-mail::panel>

---

## Alamat Pengiriman

**{{ $recipientName }}**
{{ $recipientPhone }}
{{ $shippingAddress }}
{{ $shippingCity }} {{ $shippingPostalCode }}

---

## Apa Selanjutnya?

1. 📦 **Pesanan Anda sedang dikemas** dengan penuh perhatian
2. 🚚 **Kami akan mengirim** pesanan secepatnya
3. 📧 **Anda akan menerima email** saat pesanan dikirim

<x-mail::button :url="route('storefront.track', ['order_code' => $orderCode])" color="success">
Lacak Pesanan
</x-mail::button>

---

Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami di **085712149529** atau balas email ini.

Salam hangat,
**Tim Tempe Jaya Mandiri** 🌿

<x-mail::subcopy>
Pesanan ini dibuat pada {{ $orderDate }}. Terima kasih telah berbelanja di Tempe Jaya Mandiri!
</x-mail::subcopy>
</x-mail::message>

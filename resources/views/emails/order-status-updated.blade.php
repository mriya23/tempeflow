<x-mail::message>
# Status Pesanan Diperbarui 📦

Halo **{{ $customerName }}**,

{{ $statusMessage }}

---

## Detail Pesanan

**Nomor Pesanan:** {{ $orderCode }}
**Tanggal Pemesanan:** {{ $orderDate }}

<x-mail::panel>
@if ($previousStatus)
**Status Sebelumnya:** {{ $previousStatus }}
@endif
**Status Saat Ini:** <span style="color: {{ $statusColor }}; font-weight: bold;">{{ $currentStatus }}</span>
</x-mail::panel>

---

## Produk yang Dipesan

<x-mail::table>
| Produk | Qty | Subtotal |
|:-------|:---:|--------:|
@foreach ($items as $item)
| {{ $item->product_title }} | {{ $item->qty }} | Rp {{ number_format($item->subtotal, 0, ',', '.') }} |
@endforeach
</x-mail::table>

**Total Pembayaran:** **{{ $grandTotal }}**

---

## Alamat Pengiriman

**{{ $recipientName }}**
{{ $recipientPhone }}
{{ $shippingAddress }}, {{ $shippingCity }}

---

@if ($currentStatus === 'Dikirim')
## Tips Penerimaan Paket

- Pastikan ada yang dapat menerima paket di alamat tujuan
- Periksa kondisi paket saat menerima
- Hubungi kami jika ada kendala pengiriman

@elseif ($currentStatus === 'Selesai')
## Bagaimana Pengalaman Anda?

Kami sangat menghargai feedback Anda! Jika Anda puas dengan produk dan layanan kami, mohon bagikan pengalaman Anda kepada teman dan keluarga.

@elseif ($currentStatus === 'Dibatalkan')
## Pesanan Dibatalkan

Jika pembatalan ini bukan atas permintaan Anda, silakan hubungi kami segera untuk informasi lebih lanjut.

@endif

<x-mail::button :url="route('storefront.track', ['order_code' => $orderCode])" color="success">
Lacak Pesanan
</x-mail::button>

---

Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami di **085712149529** atau balas email ini.

Salam hangat,
**Tim Tempe Jaya Mandiri** 🌿

<x-mail::subcopy>
Email ini dikirim secara otomatis oleh sistem Tempe Jaya Mandiri. Jika Anda memiliki pertanyaan tentang pesanan ini, silakan hubungi customer service kami.
</x-mail::subcopy>
</x-mail::message>

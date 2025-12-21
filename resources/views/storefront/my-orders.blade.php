@extends('layouts.storefront')

@section('content')
    @php
        $formatRupiah = fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
        $tz = (string) config('app.timezone', 'Asia/Jakarta');
        $statusMap = [
            'Menunggu Pembayaran' => 'bg-violet-100 text-violet-800',
            'Dikemas' => 'bg-amber-100 text-amber-800',
            'Dikirim' => 'bg-blue-100 text-blue-800',
            'Selesai' => 'bg-emerald-100 text-emerald-800',
            'Dibatalkan' => 'bg-rose-100 text-rose-800',
        ];

        $pendingOrder = $orders->getCollection()->first(function ($o) {
            $status = trim((string) ($o->status ?? ''));
            $paymentStatus = trim((string) ($o->payment_status ?? ''));

            // Only show pending banner if status is exactly "Menunggu Pembayaran" and not cancelled
            if ($status === 'Dibatalkan') {
                return false;
            }

            return $status === 'Menunggu Pembayaran' && $paymentStatus === 'pending';
        });
    @endphp

    <section class="max-w-6xl mx-auto px-4 pt-10 pb-16">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pesanan Saya</h1>
                <p class="mt-1 text-sm text-slate-500 leading-relaxed">Semua pesanan yang pernah kamu buat tersimpan di sini. Kamu bisa lacak kapan saja tanpa perlu mengingat kode.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('storefront.products') }}" class="h-10 px-4 rounded-full bg-[#556B55] text-white font-semibold text-sm grid place-items-center hover:bg-[#4B5F4B]">Belanja Lagi</a>
                <a href="{{ route('storefront.track') }}" class="h-10 px-4 rounded-full border border-[#E8DDCF] bg-white font-semibold text-sm grid place-items-center hover:bg-[#FBF7F0]">Lacak Manual</a>
            </div>
        </div>

        @if ($pendingOrder)
            <div class="mt-5 rounded-3xl border border-[#E8DDCF] bg-[#FBF7F0] px-5 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-sm font-bold text-slate-900">Kamu punya pesanan yang belum dibayar</div>
                    <div class="mt-1 text-xs text-slate-600">Klik tombol di samping untuk melanjutkan pembayaran tanpa membuat pesanan baru. ({{ $pendingOrder->code }})</div>
                </div>
                <a href="{{ route('storefront.pay', ['code' => $pendingOrder->code]) }}" class="inline-flex items-center justify-center h-10 px-5 rounded-full bg-[#556B55] text-white font-semibold text-sm hover:bg-[#4B5F4B]">Bayar Pesanan Terbaru</a>
            </div>
        @endif

        <div class="mt-6 rounded-3xl border border-[#E8DDCF] bg-white shadow-sm overflow-hidden">
            <div class="px-6 border-b border-[#E8DDCF] bg-[#FBF7F0]" style="padding-top: 22px; padding-bottom: 22px;">
                <div class="text-sm font-bold text-slate-900">Riwayat Pesanan</div>
                <div class="mt-2 text-xs text-slate-500 leading-relaxed">Klik "Detail" untuk melihat item. Gunakan tombol "Copy Kode" untuk menyalin kode pesanan.</div>
            </div>

            <div class="divide-y divide-[#E8DDCF]">
                @forelse ($orders as $o)
                    @php
                        $itemsCount = (int) $o->items->sum('qty');
                    @endphp

                    <div class="p-5" x-data="{ open: false }">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="text-sm font-bold text-slate-900">{{ $o->code }}</div>
                                    <span class="inline-flex items-center h-6 px-3 rounded-full text-[11px] font-semibold {{ $statusMap[$o->status] ?? 'bg-slate-100 text-slate-700' }}">{{ $o->status }}</span>
                                </div>
                                <div class="mt-1 text-xs text-slate-500">{{ $o->created_at ? $o->created_at->timezone($tz)->format('d M Y, H:i') : '-' }}</div>
                                <div class="mt-2 text-sm text-slate-700">{{ $itemsCount }} pcs • Total: <span class="font-bold text-slate-900">{{ $formatRupiah((int) $o->grand_total) }}</span></div>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('storefront.track', ['order_code' => $o->code]) }}" class="h-10 px-4 rounded-full bg-[#556B55] text-white font-semibold text-sm grid place-items-center hover:bg-[#4B5F4B]">Lacak</a>
                                @php
                                    $rowStatus = trim((string) ($o->status ?? ''));
                                    $rowPaymentStatus = trim((string) ($o->payment_status ?? ''));
                                    $rowCanPay = ($rowStatus === 'Menunggu Pembayaran' || $rowPaymentStatus === 'pending');
                                    $rowCanCancel = ($rowStatus === 'Menunggu Pembayaran');
                                @endphp

                                @if ($rowCanPay)
                                    <a href="{{ route('storefront.pay', ['code' => $o->code]) }}" class="h-10 px-4 rounded-full bg-[#556B55] text-white font-semibold text-sm grid place-items-center hover:bg-[#4B5F4B]">Bayar</a>
                                @endif
                                <button type="button" class="h-10 px-4 rounded-full border border-[#E8DDCF] bg-white font-semibold text-sm hover:bg-[#FBF7F0]" onclick="tfCopy('{{ $o->code }}')">Copy Kode</button>
                                <button
                                    type="button"
                                    class="h-10 px-4 rounded-full border border-[#E8DDCF] bg-white font-semibold text-sm hover:bg-[#FBF7F0]"
                                    @click="open = !open"
                                    :aria-expanded="open.toString()"
                                >
                                    Detail
                                </button>
                                @if ($rowCanCancel)
                                    <form method="POST" action="{{ route('storefront.order.cancel', ['order' => $o->id]) }}" style="margin: 0;" onsubmit="return confirm('Yakin ingin membatalkan pesanan {{ $o->code }}?');">
                                        @csrf
                                        <button type="submit" class="h-10 px-4 rounded-full border border-red-200 bg-white text-red-600 font-semibold text-sm hover:bg-red-50 hover:border-red-300 transition">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div x-show="open" x-transition class="mt-4 rounded-3xl border border-[#E8DDCF] bg-[#FBF7F0] p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-sm font-bold text-slate-900">Detail Item</div>
                                    <div class="mt-0.5 text-xs text-slate-500">{{ $o->items->count() }} produk</div>
                                </div>
                                <button type="button" class="h-9 px-4 rounded-full border border-[#E8DDCF] bg-white font-semibold text-xs hover:bg-white" @click="open = false">Tutup</button>
                            </div>

                            <div class="mt-3 rounded-2xl border border-[#E8DDCF] bg-white px-3 py-3">
                                <div class="text-xs font-semibold text-slate-900">Alamat Pengiriman</div>
                                <div class="mt-2 text-xs text-slate-600">
                                    <div><span class="font-semibold text-slate-900">Penerima:</span> {{ $o->recipient_name ?? '-' }} ({{ $o->recipient_phone ?? '-' }})</div>
                                    <div class="mt-1"><span class="font-semibold text-slate-900">Alamat:</span> {{ $o->shipping_address ?? '-' }}</div>
                                    <div class="mt-1"><span class="font-semibold text-slate-900">Kota:</span> {{ $o->shipping_city ?? '-' }}{{ !empty($o->shipping_postal_code) ? ', '.$o->shipping_postal_code : '' }}</div>
                                    @if (!empty($o->shipping_note))
                                        <div class="mt-2 rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-3 py-2 text-[11px] text-slate-600">
                                            <span class="font-semibold text-slate-900">Catatan:</span> {{ $o->shipping_note }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 space-y-2">
                                @foreach ($o->items as $it)
                                    <div class="flex items-start justify-between gap-4 rounded-2xl bg-white border border-[#E8DDCF] px-3 py-2">
                                        <div class="min-w-0">
                                            <div class="text-xs font-semibold text-slate-900 truncate">{{ $it->product_title }}</div>
                                            <div class="mt-0.5 text-[11px] text-slate-500">{{ $formatRupiah((int) $it->price) }} × {{ $it->qty }}</div>
                                        </div>
                                        <div class="text-xs font-bold text-slate-900">{{ $formatRupiah((int) $it->subtotal) }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 rounded-2xl border border-[#E8DDCF] bg-white px-3 py-2">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="text-slate-500">Grand Total</div>
                                    <div class="font-bold text-slate-900">{{ $formatRupiah((int) $o->grand_total) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="text-sm font-semibold text-slate-900">Belum ada pesanan</div>
                        <div class="mt-2 text-sm text-slate-500 leading-relaxed">Mulai belanja dulu, nanti pesanan kamu akan muncul di halaman ini.</div>
                        <div class="mt-6">
                            <a href="{{ route('storefront.products') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#556B55] text-white font-semibold">Lihat Produk</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->onEachSide(1)->links() }}
        </div>
    </section>

    <script>
        function tfCopy(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text);
                return;
            }

            const el = document.createElement('textarea');
            el.value = text;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        }
    </script>
@endsection

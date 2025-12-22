@extends('layouts.storefront')

@section('content')
    @php
        $order = $order ?? null;
        $totals = (array) ($order['totals'] ?? []);
        $items = (array) ($order['items'] ?? []);
        $formatRupiah = fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
        $isWaitingPayment = (bool) (($order['status'] ?? '') === 'Menunggu Pembayaran');
    @endphp

    <section class="max-w-6xl mx-auto px-4 pt-10 pb-16">
        <div class="rounded-[28px] border border-[#E8DDCF] bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-6 border-b border-[#E8DDCF] bg-[#FBF7F0]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-700">Status Pesanan</div>
                        <div class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                            {{ $isWaitingPayment ? 'Menunggu pembayaran' : 'Pesanan kamu sedang diproses' }}
                        </div>
                        <div class="mt-2 text-sm text-slate-500">
                            {{ $isWaitingPayment ? 'Silakan selesaikan pembayaran untuk melanjutkan proses pesanan.' : 'Kamu bisa cek status kapan pun lewat menu Pesanan Saya.' }}
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-[#D7E6D0] text-[#556B55] grid place-items-center font-bold">✓</div>
                </div>
            </div>

            <div class="p-6">
                @if (!$order)
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                        Pesanan tidak ditemukan.
                    </div>
                @else
                    <div class="grid lg:grid-cols-12 gap-6">
                        <div class="lg:col-span-7">
                            <div class="rounded-2xl border border-[#E8DDCF] bg-white">
                                <div class="px-5 py-6 border-b border-[#E8DDCF]">
                                    <div class="text-sm font-semibold text-slate-900">Detail Pesanan</div>
                                </div>
                                <div class="p-5 space-y-6">
                                    <div class="grid sm:grid-cols-2 gap-3 text-sm">
                                        <div class="rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-4 py-3">
                                            <div class="text-xs text-slate-500">Nomor Pesanan</div>
                                            <div class="mt-1 font-bold text-slate-900">{{ $order['code'] ?? '-' }}</div>
                                        </div>
                                        <div class="rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-4 py-3">
                                            <div class="text-xs text-slate-500">Status</div>
                                            <div class="mt-1 font-bold text-slate-900">{{ $order['status'] ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-[#E8DDCF] overflow-hidden">
                                        <div class="px-4 py-4 border-b border-[#E8DDCF] bg-white">
                                            <div class="text-sm font-semibold text-slate-900">Alamat Pengiriman</div>
                                        </div>
                                        <div class="px-4 py-4 text-sm">
                                            <div class="text-xs text-slate-500">Penerima</div>
                                            <div class="mt-1 font-semibold text-slate-900">{{ $order['recipient_name'] ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $order['recipient_phone'] ?? '' }}</div>

                                            <div class="mt-3 text-xs text-slate-500">Alamat</div>
                                            <div class="mt-1 font-semibold text-slate-900">{{ $order['shipping_address'] ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $order['shipping_city'] ?? '' }}{{ !empty($order['shipping_postal_code']) ? ', '.$order['shipping_postal_code'] : '' }}</div>

                                            @if (!empty($order['shipping_note']))
                                                <div class="mt-3 rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-3 py-2 text-xs text-slate-600">
                                                    <span class="font-semibold text-slate-900">Catatan:</span> {{ $order['shipping_note'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-[#E8DDCF] overflow-hidden">
                                        <div class="px-6 py-4 border-b border-[#E8DDCF] bg-white">
                                            <div class="text-sm font-semibold text-slate-900">Item</div>
                                        </div>
                                        <div class="divide-y divide-[#E8DDCF]">
                                            @foreach ($items as $it)
                                                @php
                                                    $qty = (int) ($it['qty'] ?? 1);
                                                    $subtotal = (int) ($it['subtotal'] ?? 0);
                                                @endphp
                                                <div class="px-6 py-4 flex items-start justify-between gap-6">
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-semibold text-slate-900 truncate">{{ $it['title'] ?? '' }}</div>
                                                        <div class="mt-2 text-xs text-slate-500">Qty: {{ $qty }}</div>
                                                    </div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $formatRupiah($subtotal) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="rounded-2xl border border-[#E8DDCF] bg-white overflow-hidden">
                                <div class="px-5 py-4 border-b border-[#E8DDCF] bg-white">
                                    <div class="text-sm font-semibold text-slate-900">Ringkasan</div>
                                </div>
                                <div class="p-5 space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Subtotal</span>
                                        <span class="font-semibold text-slate-900">{{ $totals['total_formatted'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Ongkir</span>
                                        @if (($order['shipping_cost'] ?? 0) > 0)
                                            <span class="font-semibold text-slate-900">Rp {{ number_format($order['shipping_cost'], 0, ',', '.') }}</span>
                                        @else
                                            <span class="font-semibold text-emerald-700">GRATIS</span>
                                        @endif
                                    </div>

                                    <div class="pt-4 mt-4 border-t border-[#E8DDCF] flex items-end justify-between">
                                        <span class="text-sm font-semibold text-slate-700">Total</span>
                                        <span class="text-xl font-bold text-[#29412E]">{{ $totals['grand_total_formatted'] ?? '-' }}</span>
                                    </div>

                                    {{-- Payment Info --}}
                                    @if (($order['payment_status'] ?? '') === 'paid')
                                        @php
                                            $method = $order['payment_method'] ?? '';
                                            if (str_starts_with($method, 'bank_transfer_')) {
                                                $methodDisplay = 'VA ' . strtoupper(str_replace('bank_transfer_', '', $method));
                                            } elseif ($method === 'qris') {
                                                $methodDisplay = 'QRIS';
                                            } else {
                                                $methodDisplay = $method ?: '-';
                                            }
                                        @endphp
                                        <div class="mt-5 rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
                                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#ffffff" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <span style="font-weight: 700; color: #047857;">Telah Dibayar</span>
                                            </div>
                                            <div class="space-y-3 text-sm">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-slate-500">Metode</span>
                                                    <span class="font-semibold text-slate-900">{{ $methodDisplay }}</span>
                                                </div>
                                                @if ($order['paid_at'] ?? null)
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-slate-500">Waktu</span>
                                                        <span class="font-semibold text-slate-900">{{ $order['paid_at'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="pt-5 flex flex-col sm:flex-row gap-3">
                                        @if ($isWaitingPayment)
                                            <a href="{{ route('storefront.pay', ['code' => $order['code'] ?? '' ]) }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#556B55] text-white font-semibold hover:bg-[#4B5F4B]">Bayar Sekarang</a>
                                        @endif
                                        <a href="{{ route('storefront.track', ['order_code' => $order['code'] ?? '' ]) }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#556B55] text-white font-semibold">Lacak Pesanan</a>
                                        <a href="{{ route('storefront.my-orders') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl border border-[#E8DDCF] bg-white text-slate-800 font-semibold">Pesanan Saya</a>
                                        <a href="{{ route('storefront.products') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] text-slate-800 font-semibold">Lanjut Belanja</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if ($order && $isWaitingPayment)
        <script>
            (function () {
                const orderCode = @json($order['code'] ?? '');
                if (!orderCode) return;

                const url = @json(route('storefront.checkout.status', ['code' => $order['code'] ?? '' ]));

                let tries = 0;
                const maxTries = 20;

                function poll() {
                    tries++;
                    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                        .then(r => r.ok ? r.json() : null)
                        .then(data => {
                            if (!data) return;
                            if ((data.payment_status && data.payment_status === 'paid') || (data.status && data.status !== 'Menunggu Pembayaran')) {
                                window.location.reload();
                                return;
                            }
                            if (tries < maxTries) {
                                setTimeout(poll, 3000);
                            }
                        })
                        .catch(() => {
                            if (tries < maxTries) setTimeout(poll, 5000);
                        });
                }

                setTimeout(poll, 2500);
            })();
        </script>
    @endif
@endsection

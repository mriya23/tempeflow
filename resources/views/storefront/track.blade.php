@extends('layouts.storefront')

@section('content')
    @php
        $order_code = (string) ($order_code ?? '');
        $order = $order ?? null;
        $order_not_found = (bool) ($order_not_found ?? false);
        $can_pay = (bool) ($can_pay ?? false);
        $is_owner = (bool) ($is_owner ?? false);
    @endphp

    <section class="max-w-6xl mx-auto px-4 pt-14 pb-20">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Lacak Pesanan Anda</h1>
            <p class="mt-2 text-sm text-slate-500">Masukkan nomor pesanan untuk melihat status pengiriman tempe Anda</p>
        </div>

        <div class="mt-8 max-w-xl mx-auto">
            <div class="rounded-3xl border border-[#E8DDCF] bg-white shadow-sm p-4">
                <form method="GET" action="{{ route('storefront.track') }}" class="flex items-center gap-3">
                    <div class="flex-1 relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔎</span>
                        <input
                            type="text"
                            name="order_code"
                            value="{{ $order_code }}"
                            placeholder="Masukkan nomor pesanan (contoh: TF-12548765)"
                            class="w-full h-11 rounded-2xl border border-[#E8DDCF] bg-white px-10 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        />
                    </div>
                    <button type="submit" class="h-11 px-5 rounded-2xl bg-[#556B55] text-white font-semibold text-sm">Lacak Pesanan</button>
                </form>
                <div class="mt-3 text-xs text-slate-500">
                    Nomor pesanan dapat ditemukan di email/nota konfirmasi atau dalam riwayat pembelian.
                </div>
            </div>
        </div>

        @if ($order_not_found)
            <div class="mt-6 max-w-xl mx-auto">
                <div class="rounded-3xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">
                    Nomor pesanan <span class="font-semibold">{{ $order_code }}</span> tidak ditemukan.
                </div>
            </div>
        @endif

        @if ($order)
            <div class="mt-8 max-w-3xl mx-auto">
                <div class="rounded-3xl border border-[#E8DDCF] bg-white shadow-sm overflow-hidden">
                    <div class="px-6 pt-6 pb-5 border-b border-[#E8DDCF] bg-[#FBF7F0]">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-xs text-slate-500">Nomor Pesanan</div>
                                <div class="mt-1 text-xl font-bold text-slate-900">{{ $order['code'] ?? $order_code }}</div>
                                <div class="mt-2 text-sm text-slate-600">Status: <span class="font-semibold text-slate-900">{{ $order['status'] ?? '-' }}</span></div>
                            </div>
                            <div class="h-12 w-12 rounded-2xl bg-[#D7E6D0] text-[#556B55] grid place-items-center font-bold">✓</div>
                        </div>
                    </div>

                    <div class="p-6 grid lg:grid-cols-12 gap-6">
                        <div class="lg:col-span-7">
                            <div class="rounded-2xl border border-[#E8DDCF] overflow-hidden">
                                <div class="px-4 py-3 border-b border-[#E8DDCF] bg-white">
                                    <div class="text-sm font-semibold text-slate-900">Item Pesanan</div>
                                </div>
                                <div class="divide-y divide-[#E8DDCF]">
                                    @foreach ((array) ($order['items'] ?? []) as $it)
                                        <div class="px-4 py-3 flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-slate-900 truncate">{{ $it['title'] ?? '' }}</div>
                                                <div class="mt-1 text-xs text-slate-500">Qty: {{ (int) ($it['qty'] ?? 1) }}</div>
                                            </div>
                                            <div class="text-sm font-bold text-slate-900">
                                                Rp {{ number_format((int) ($it['subtotal'] ?? 0), 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
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
                                        <span class="font-semibold text-slate-900">{{ $order['totals']['total_formatted'] ?? '-' }}</span>
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
                                        <span class="text-xl font-bold text-[#29412E]">{{ $order['totals']['grand_total_formatted'] ?? '-' }}</span>
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

                                    @if (($order['status'] ?? '') === 'Menunggu Pembayaran')
                                        <div class="pt-5">
                                            @if ($can_pay)
                                                <a href="{{ route('storefront.pay', ['code' => $order['code'] ?? '' ]) }}" class="w-full inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#556B55] text-white font-semibold hover:bg-[#4B5F4B]">Bayar Sekarang</a>
                                            @elseif (auth()->check())
                                                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-900">
                                                    Pembayaran hanya bisa dilakukan oleh pemilik pesanan. Buka <a href="{{ route('storefront.my-orders') }}" class="font-semibold underline">Pesanan Saya</a> untuk melanjutkan pembayaran pesanan kamu.
                                                </div>
                                            @elseif (Route::has('login'))
                                                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-900">
                                                    Jika ini pesanan kamu, silakan <a href="{{ route('login') }}" class="font-semibold underline">login</a> lalu buka menu <span class="font-semibold">Pesanan Saya</span> untuk melanjutkan pembayaran.
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($is_owner)
                                <div class="mt-4 rounded-2xl border border-[#E8DDCF] bg-white overflow-hidden">
                                    <div class="px-5 py-4 border-b border-[#E8DDCF] bg-white">
                                        <div class="text-sm font-semibold text-slate-900">Alamat Pengiriman</div>
                                    </div>
                                    <div class="p-5 text-sm space-y-2">
                                        <div>
                                            <div class="text-xs text-slate-500">Penerima</div>
                                            <div class="font-semibold text-slate-900">{{ $order['recipient_name'] ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $order['recipient_phone'] ?? '' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-500">Alamat</div>
                                            <div class="text-sm font-semibold text-slate-900">{{ $order['shipping_address'] ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $order['shipping_city'] ?? '' }}{{ !empty($order['shipping_postal_code']) ? ', '.$order['shipping_postal_code'] : '' }}</div>
                                        </div>
                                        @if (!empty($order['shipping_note']))
                                            <div class="rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-4 py-3 text-xs text-slate-600">
                                                <span class="font-semibold text-slate-900">Catatan:</span> {{ $order['shipping_note'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-10 max-w-2xl mx-auto">
            <div class="rounded-3xl border border-[#E8DDCF] bg-white shadow-sm p-6">
                <div class="text-center">
                    <div class="text-sm font-bold text-slate-900">Cara Melacak Pesanan</div>
                </div>

                <div class="mt-6 grid sm:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="h-10 w-10 rounded-2xl bg-[#D7E6D0] text-[#556B55] grid place-items-center mx-auto font-bold">1</div>
                        <div class="mt-3 text-sm font-semibold">Masukkan Nomor</div>
                        <div class="mt-1 text-xs text-slate-500">Input nomor pesanan dari konfirmasi</div>
                    </div>
                    <div class="text-center">
                        <div class="h-10 w-10 rounded-2xl bg-[#D7E6D0] text-[#556B55] grid place-items-center mx-auto font-bold">2</div>
                        <div class="mt-3 text-sm font-semibold">Lihat Status</div>
                        <div class="mt-1 text-xs text-slate-500">Cek progress pesanan secara real-time</div>
                    </div>
                    <div class="text-center">
                        <div class="h-10 w-10 rounded-2xl bg-[#D7E6D0] text-[#556B55] grid place-items-center mx-auto font-bold">3</div>
                        <div class="mt-3 text-sm font-semibold">Terima Paket</div>
                        <div class="mt-1 text-xs text-slate-500">Tunggu paket tiba di alamat Anda</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

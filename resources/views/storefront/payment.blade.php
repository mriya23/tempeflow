@extends('layouts.storefront')

@section('content')
    @php
        $order = $order ?? null;
        $snap_token = $snap_token ?? null;
        $snap_js_url = $snap_js_url ?? null;
        $client_key = $client_key ?? null;
        $success_url = $success_url ?? null;
        $payment_error = $payment_error ?? null;
        $formatRupiah = $format_rupiah ?? fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
    @endphp

    <section class="max-w-6xl mx-auto px-4 pt-10 pb-16">
        <div class="rounded-[28px] border border-[#E8DDCF] bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-6 border-b border-[#E8DDCF] bg-[#FBF7F0]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-700">Pembayaran</div>
                        <div class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Selesaikan pembayaran pesanan</div>
                        <div class="mt-2 text-sm text-slate-500">Gunakan metode pembayaran favoritmu. Setelah pembayaran berhasil, status pesanan akan otomatis diperbarui.</div>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-[#D7E6D0] text-[#556B55] grid place-items-center font-bold">Rp</div>
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
                            <div class="rounded-2xl border border-[#E8DDCF] bg-white overflow-hidden">
                                <div class="px-5 py-4 border-b border-[#E8DDCF]">
                                    <div class="text-sm font-semibold text-slate-900">Ringkasan Pesanan</div>
                                </div>
                                <div class="p-5 space-y-6">
                                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                                        <div class="rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-4 py-3">
                                            <div class="text-xs text-slate-500">Nomor Pesanan</div>
                                            <div class="mt-1 font-bold text-slate-900">{{ $order->code }}</div>
                                        </div>
                                        <div class="rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-4 py-3">
                                            <div class="text-xs text-slate-500">Total Pembayaran</div>
                                            <div class="mt-1 font-bold text-slate-900">{{ $formatRupiah((int) $order->grand_total) }}</div>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-[#E8DDCF] overflow-hidden">
                                        <div class="px-6 py-4 border-b border-[#E8DDCF] bg-white">
                                            <div class="text-sm font-semibold text-slate-900">Item</div>
                                        </div>
                                        <div class="divide-y divide-[#E8DDCF]">
                                            @foreach ($order->items as $it)
                                                <div class="px-6 py-4 flex items-start justify-between gap-6">
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-semibold text-slate-900 truncate">{{ $it->product_title }}</div>
                                                        <div class="mt-2 text-xs text-slate-500">{{ $formatRupiah((int) $it->price) }} × {{ (int) $it->qty }}</div>
                                                    </div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $formatRupiah((int) $it->subtotal) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] px-4 py-3 text-xs text-slate-600">
                                        Jika kamu menutup popup pembayaran, kamu bisa klik tombol <span class="font-semibold text-slate-900">Bayar Sekarang</span> lagi kapan saja.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-5 space-y-6">
                            @if ($payment_error)
                                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                    {{ $payment_error }}
                                </div>
                            @endif

                            <div class="rounded-2xl border border-[#E8DDCF] bg-white overflow-hidden">
                                <div class="px-5 py-4 border-b border-[#E8DDCF] bg-white">
                                    <div class="text-sm font-semibold text-slate-900">Pembayaran</div>
                                </div>
                                <div class="p-5 space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Subtotal</span>
                                        <span class="font-semibold text-slate-900">{{ $formatRupiah((int) $order->subtotal) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Ongkir</span>
                                        <span class="font-semibold text-emerald-700">GRATIS</span>
                                    </div>

                                    <div class="pt-4 mt-4 border-t border-[#E8DDCF] flex items-end justify-between">
                                        <span class="text-sm font-semibold text-slate-700">Total</span>
                                        <span class="text-xl font-bold text-[#29412E]">{{ $formatRupiah((int) $order->grand_total) }}</span>
                                    </div>

                                    <div class="pt-5 flex flex-col gap-3">
                                        <button
                                            type="button"
                                            data-tf-pay-btn
                                            class="w-full inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#556B55] text-white font-semibold"
                                            {{ (!$snap_token || !$snap_js_url || !$client_key) ? 'disabled' : '' }}
                                        >
                                            Bayar Sekarang
                                        </button>

                                        <a href="{{ route('storefront.my-orders') }}" class="w-full inline-flex items-center justify-center h-11 px-6 rounded-2xl border border-[#E8DDCF] bg-white text-slate-800 font-semibold">
                                            Lihat Pesanan Saya
                                        </a>
                                    </div>

                                    <div class="mt-4 text-[11px] text-slate-500 flex items-center gap-2">
                                        <span class="h-5 w-5 rounded-full bg-[#F3EEE6] border border-[#E8DDCF] grid place-items-center text-[10px]">✓</span>
                                        <span>Pembayaran diproses aman melalui Midtrans</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if ($order && $snap_token && $snap_js_url && $client_key)
        <script src="{{ $snap_js_url }}" data-client-key="{{ $client_key }}"></script>
        <script>
            (function () {
                const snapToken = @json($snap_token);
                const successUrl = @json($success_url);

                function openSnap() {
                    if (!window.snap || !snapToken) return;

                    window.snap.pay(snapToken, {
                        onSuccess: function () {
                            if (successUrl) window.location.href = successUrl;
                        },
                        onPending: function () {
                            if (successUrl) window.location.href = successUrl;
                        },
                        onError: function () {
                            // stay on page so user can retry
                        },
                        onClose: function () {
                            // user closed without finishing
                        }
                    });
                }

                const btn = document.querySelector('[data-tf-pay-btn]');
                if (btn) btn.addEventListener('click', openSnap);
            })();
        </script>
    @endif
@endsection

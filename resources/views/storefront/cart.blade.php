@extends('layouts.storefront')

@section('content')
    @php
        $items = $items ?? [];
        $total_formatted = $total_formatted ?? null;
        $total = (int) ($total ?? 0);
        $formatRupiah = fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
    @endphp

    @if (count($items) === 0)
        <section class="max-w-6xl mx-auto px-4 pt-10 pb-16">
            <div class="rounded-[28px] border border-[#E8DDCF] bg-white/70 backdrop-blur p-6 sm:p-10">
                <div class="max-w-xl mx-auto text-center">
                    <div class="h-16 w-16 rounded-[28px] bg-[#F3EEE6] border border-[#E8DDCF] grid place-items-center mx-auto">
                        <span class="text-2xl">🛒</span>
                    </div>
                    <h1 class="mt-5 text-2xl font-bold tracking-tight text-slate-900">Keranjang kamu masih kosong</h1>
                    <p class="mt-2 text-sm text-slate-600">Mulai belanja dulu, nanti produk pilihanmu akan muncul di sini.</p>
                    
                    @if ($pending_order ?? null)
                        <div class="mt-6 mx-auto max-w-sm rounded-2xl border border-amber-200 bg-amber-50 p-4 text-left">
                            <div class="text-xs font-semibold text-amber-900">Pesanan Belum Dibayar</div>
                            <div class="mt-1 text-amber-800 text-sm">
                                Pesananmu <strong>{{ $pending_order->code }}</strong> sedang menunggu pembayaran.
                            </div>
                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('storefront.pay', ['code' => $pending_order->code]) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-[#556B55] text-white text-xs font-bold hover:bg-[#4B5F4B]">
                                    Bayar Sekarang
                                </a>
                                <a href="{{ route('storefront.my-orders') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-amber-200 bg-white text-amber-800 text-xs font-semibold hover:bg-amber-100">
                                    Lihat Pesanan
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('storefront.products') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#556B55] text-white font-semibold">Lihat Produk</a>
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl border border-[#E8DDCF] bg-white text-slate-800 font-semibold">Kembali ke Home</a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @else
        <section
            class="max-w-6xl mx-auto px-4 pt-10 pb-16"
            x-data="{
                sendQtyUpdate(formEl, delta) {
                    const root = formEl.closest('[data-tf-cart-item]');
                    const qtyEl = root ? root.querySelector('[data-tf-item-qty]') : null;
                    const currentQty = parseInt(qtyEl ? qtyEl.textContent : '1', 10) || 1;
                    const nextQty = Math.max(1, currentQty + (parseInt(delta, 10) || 0));

                    const fd = new FormData(formEl);
                    fd.set('qty', String(nextQty));

                    return fetch(formEl.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: fd,
                    })
                        .then(r => {
                            if (r.status === 401) {
                                loginToast = true;
                                setTimeout(() => loginToast = false, 3500);
                                return null;
                            }

                            if (!r.ok) {
                                window.location.reload();
                                return null;
                            }

                            return r.json();
                        })
                        .then(data => {
                            if (!data) return;
                            this.updateItem(data.updated_id, data.qty, data.item_subtotal_formatted);
                            this.setCartBadge(parseInt(data.cart_count || 0, 10) || 0);
                            this.applyTotals(data);
                        });
                },
                setCartBadge(count) {
                    document.querySelectorAll('[data-tf-cart-button]').forEach(cartBtn => {
                        let badge = cartBtn.querySelector('[data-tf-cart-badge]');
                        if (count > 0) {
                            if (!badge) {
                                badge = document.createElement('span');
                                badge.setAttribute('data-tf-cart-badge', '');
                                badge.className = 'tf-cart-badge';
                                cartBtn.appendChild(badge);
                            }
                            badge.textContent = String(count);
                        } else {
                            if (badge) badge.remove();
                        }
                    });
                },
                applyTotals(data) {
                    const itemsCount = document.querySelector('[data-tf-cart-items-count]');
                    if (itemsCount && typeof data.items_count !== 'undefined') itemsCount.textContent = String(data.items_count);

                    const summaryItemsCount = document.querySelector('[data-tf-summary-items-count]');
                    if (summaryItemsCount && typeof data.items_count !== 'undefined') summaryItemsCount.textContent = String(data.items_count);

                    const subtotal = document.querySelector('[data-tf-summary-subtotal]');
                    if (subtotal && data.total_formatted) subtotal.textContent = String(data.total_formatted);

                    const grand = document.querySelector('[data-tf-summary-grand]');
                    if (grand && data.grand_total_formatted) grand.textContent = String(data.grand_total_formatted);

                    const shipping = document.querySelector('[data-tf-summary-shipping]');
                    if (shipping && typeof data.shipping_cost !== 'undefined') {
                        if (data.shipping_cost === 0) {
                            shipping.textContent = 'GRATIS';
                            shipping.className = 'text-emerald-700 font-semibold';
                        } else {
                            shipping.textContent = String(data.shipping_cost_formatted);
                            shipping.className = 'font-semibold text-slate-900';
                        }
                    }
                },
                updateItem(id, qty, subtotalFormatted) {
                    const root = document.querySelector('[data-tf-cart-item=\'' + String(id) + '\']');
                    if (!root) return;
                    const qtyEl = root.querySelector('[data-tf-item-qty]');
                    if (qtyEl) qtyEl.textContent = String(qty);

                    const qtyMult = root.querySelector('[data-tf-item-qty-mult]');
                    if (qtyMult) qtyMult.textContent = String(qty);

                    const subtotalEl = root.querySelector('[data-tf-item-subtotal]');
                    if (subtotalEl && subtotalFormatted) subtotalEl.textContent = String(subtotalFormatted);

                    const minusBtn = root.querySelector('[data-tf-qty-minus]');
                    if (minusBtn) minusBtn.disabled = (parseInt(qty, 10) || 1) <= 1;
                },
                removeItem(id) {
                    const root = document.querySelector('[data-tf-cart-item=\'' + String(id) + '\']');
                    if (root) root.remove();
                }
            }"
        >
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Keranjang Belanja</h1>
                <p class="mt-2 text-sm text-slate-600"><span data-tf-cart-items-count>{{ count($items) }}</span> produk di keranjang Anda</p>
            </div>

            <div class="mt-8 grid lg:grid-cols-12 gap-6 items-start">
                <div class="lg:col-span-8 space-y-4">
                    @foreach ($items as $item)
                        @php
                            $qty = (int) ($item['qty'] ?? 1);
                            $qtyMinus = max(1, $qty - 1);
                            $qtyPlus = $qty + 1;
                        @endphp

                        <div data-tf-cart-item="{{ $item['id'] }}" class="relative rounded-[24px] border border-[#E8DDCF] bg-white shadow-sm">
                            <form
                                method="POST"
                                action="{{ route('storefront.cart.remove', [], false) }}"
                                class="absolute right-4 top-4"
                                x-on:submit.prevent="fetch($el.action, { method: 'POST', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: new FormData($el) }).then(r => { if (r.status === 401) { loginToast = true; setTimeout(() => loginToast = false, 3500); return null } return r.json() }).then(data => { if (!data) return; removeItem(data.removed_id); setCartBadge(parseInt(data.cart_count || 0, 10) || 0); applyTotals(data); if ((parseInt(data.items_count || 0, 10) || 0) === 0) { window.location.reload(); } })"
                            >
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}" />
                                <button type="submit" class="h-9 w-9 rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] grid place-items-center text-slate-500 hover:bg-white transition" aria-label="Hapus">
                                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4h8v2" />
                                        <path d="M19 6l-1 14H6L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                </button>
                            </form>

                            <div class="p-4 sm:p-5 flex gap-4">
                                <img class="h-16 w-16 rounded-2xl object-cover border border-[#E8DDCF] bg-[#FBF7F0]" src="{{ $item['img'] }}" alt="{{ $item['title'] }}" />

                                <div class="flex-1 min-w-0 pr-10">
                                    <div class="text-[11px] text-slate-500">{{ $item['tag'] }}</div>
                                    <div class="mt-0.5 font-semibold text-slate-900 leading-snug truncate">{{ $item['title'] }}</div>

                                    <div class="mt-3 flex items-center justify-between gap-4">
                                        <div class="inline-flex items-center rounded-full border border-[#E8DDCF] bg-[#FBF7F0] overflow-hidden">
                                            <form
                                                method="POST"
                                                action="{{ route('storefront.cart.update', [], false) }}"
                                                x-on:submit.prevent="sendQtyUpdate($el, -1)"
                                            >
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}" />
                                                <input type="hidden" name="qty" value="{{ $qtyMinus }}" />
                                                <button data-tf-qty-minus type="submit" class="h-7 w-8 grid place-items-center text-slate-700 hover:bg-white transition" {{ $qty <= 1 ? 'disabled' : '' }}>−</button>
                                            </form>
                                            <div data-tf-item-qty class="h-7 px-3 grid place-items-center text-xs font-semibold text-slate-900">{{ $qty }}</div>
                                            <form
                                                method="POST"
                                                action="{{ route('storefront.cart.update', [], false) }}"
                                                x-on:submit.prevent="sendQtyUpdate($el, 1)"
                                            >
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}" />
                                                <input type="hidden" name="qty" value="{{ $qtyPlus }}" />
                                                <button type="submit" class="h-7 w-8 grid place-items-center text-slate-700 hover:bg-white transition">+</button>
                                            </form>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-[10px] text-slate-500">{{ $item['price_formatted'] }} × <span data-tf-item-qty-mult>{{ $qty }}</span></div>
                                            <div data-tf-item-subtotal class="text-sm font-bold text-slate-900">{{ $item['subtotal_formatted'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <a href="{{ route('storefront.products') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 transition">
                        <span>←</span>
                        <span class="font-semibold">Lanjut Belanja</span>
                    </a>
                </div>

                <div class="lg:col-span-4 lg:sticky lg:top-24">
                    <div class="rounded-[24px] border border-[#E8DDCF] bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-[#E8DDCF] bg-[#FBF7F0]">
                            <div class="text-sm font-semibold text-slate-900">Ringkasan Pesanan</div>
                        </div>

                        <div class="p-5">
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600">Subtotal (<span data-tf-summary-items-count>{{ count($items) }}</span> item)</span>
                                    <span data-tf-summary-subtotal class="font-semibold text-slate-900">{{ $formatRupiah($total) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600">Estimasi Ongkir</span>
                                    <span data-tf-summary-shipping class="{{ ($shipping_cost ?? 0) === 0 ? 'text-emerald-700 font-semibold' : 'font-semibold text-slate-900' }}">
                                        {{ ($shipping_cost ?? 0) === 0 ? 'GRATIS' : ($shipping_cost_formatted ?? 'Rp 15.000') }}
                                    </span>
                                </div>
                                @if (($shipping_cost ?? 0) > 0)
                                    <div class="text-[10px] text-slate-500 text-right mt-[-8px]">
                                        Gratis ongkir min. Rp {{ number_format($free_shipping_threshold ?? 150000, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-5 pt-4 border-t border-[#E8DDCF] flex items-end justify-between">
                                <div class="text-sm font-semibold text-slate-700">Total</div>
                                <div data-tf-summary-grand class="text-xl font-bold text-[#29412E]">{{ $grand_total_formatted ?? $formatRupiah($total) }}</div>
                            </div>

                            <div class="mt-5">
                                <form
                                    method="POST"
                                    action="{{ route('storefront.checkout', [], false) }}"
                                >
                                    @csrf
                                    <div class="rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] p-4">
                                        <div class="text-sm font-semibold text-slate-900">Alamat Pengiriman</div>

                                        <div class="mt-3 space-y-3">
                                            <div>
                                                <div class="text-xs font-semibold text-slate-600">Nama Penerima</div>
                                                <input name="recipient_name" value="{{ old('recipient_name') }}" class="mt-1 w-full h-11 rounded-2xl border border-[#E8DDCF] bg-white px-4 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                                @error('recipient_name')
                                                    <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold text-slate-600">No. HP / WhatsApp</div>
                                                <input name="recipient_phone" value="{{ old('recipient_phone') }}" class="mt-1 w-full h-11 rounded-2xl border border-[#E8DDCF] bg-white px-4 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                                @error('recipient_phone')
                                                    <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold text-slate-600">Kota</div>
                                                <input name="shipping_city" value="{{ old('shipping_city') }}" class="mt-1 w-full h-11 rounded-2xl border border-[#E8DDCF] bg-white px-4 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                                @error('shipping_city')
                                                    <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold text-slate-600">Kode Pos (opsional)</div>
                                                <input name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" class="mt-1 w-full h-11 rounded-2xl border border-[#E8DDCF] bg-white px-4 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                                                @error('shipping_postal_code')
                                                    <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold text-slate-600">Alamat Lengkap</div>
                                                <textarea name="shipping_address" rows="3" class="mt-1 w-full rounded-2xl border border-[#E8DDCF] bg-white px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('shipping_address') }}</textarea>
                                                @error('shipping_address')
                                                    <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div>
                                                <div class="text-xs font-semibold text-slate-600">Catatan (opsional)</div>
                                                <textarea name="shipping_note" rows="2" class="mt-1 w-full rounded-2xl border border-[#E8DDCF] bg-white px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('shipping_note') }}</textarea>
                                                @error('shipping_note')
                                                    <div class="mt-1 text-xs text-rose-600">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-4 w-full inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#556B55] text-white font-semibold">Lanjut ke Checkout</button>
                                </form>
                            </div>

                            <div class="mt-4 text-[11px] text-slate-500 flex items-center gap-2">
                                <span class="h-5 w-5 rounded-full bg-[#F3EEE6] border border-[#E8DDCF] grid place-items-center text-[10px]">✓</span>
                                <span>Transaksi aman dan data terlindungi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

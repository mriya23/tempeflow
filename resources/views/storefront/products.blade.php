@extends('layouts.storefront')

@section('content')
    <section
        class="max-w-6xl mx-auto px-4 pt-10 pb-16"
    >
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Katalog Produk Tempe</h1>
            <p class="mt-2 text-sm text-slate-500">Temukan tempe berkualitas premium dengan berbagai pilihan rasa</p>
        </div>

        <div class="mt-8 rounded-3xl border border-[#E8DDCF] bg-white/70 backdrop-blur px-4 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-slate-600">Kategori:</span>
                    <a
                        href="{{ route('storefront.products', ['kategori' => 'semua', 'sort' => $sort ?? 'populer']) }}"
                        class="inline-flex items-center h-8 px-3 rounded-full border border-[#E8DDCF] text-xs font-semibold transition {{ ($kategori ?? 'semua') === 'semua' ? 'bg-[#556B55] text-white border-transparent' : 'bg-white text-slate-700 hover:bg-white' }}"
                    >Semua Produk</a>
                    <a
                        href="{{ route('storefront.products', ['kategori' => 'plastik', 'sort' => $sort ?? 'populer']) }}"
                        class="inline-flex items-center h-8 px-3 rounded-full border border-[#E8DDCF] text-xs font-semibold transition {{ ($kategori ?? 'semua') === 'plastik' ? 'bg-[#556B55] text-white border-transparent' : 'bg-white text-slate-700 hover:bg-white' }}"
                    >Tempe Plastik</a>
                    <a
                        href="{{ route('storefront.products', ['kategori' => 'daun', 'sort' => $sort ?? 'populer']) }}"
                        class="inline-flex items-center h-8 px-3 rounded-full border border-[#E8DDCF] text-xs font-semibold transition {{ ($kategori ?? 'semua') === 'daun' ? 'bg-[#556B55] text-white border-transparent' : 'bg-white text-slate-700 hover:bg-white' }}"
                    >Tempe Daun Pisang</a>
                </div>

                <form method="GET" action="{{ route('storefront.products') }}" class="lg:ml-auto flex items-center gap-2">
                    <input type="hidden" name="kategori" value="{{ $kategori ?? 'semua' }}" />
                    <span class="text-xs font-semibold text-slate-600">Urutkan:</span>
                    <select name="sort" onchange="this.form.submit()" class="h-8 rounded-full border border-[#E8DDCF] bg-white text-xs px-3 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="populer" {{ ($sort ?? 'populer') === 'populer' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="terbaru" {{ ($sort ?? 'populer') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="harga_terendah" {{ ($sort ?? 'populer') === 'harga_terendah' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_tertinggi" {{ ($sort ?? 'populer') === 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>

                </form>
            </div>
        </div>

        @php
            $formatRupiah = fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
        @endphp

        <div class="mt-6 text-xs text-slate-500">Menampilkan {{ count($products) }} produk</div>

        <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="rounded-3xl border border-[#E8DDCF] bg-white shadow-sm overflow-hidden">

                    <div class="relative overflow-hidden">
                        <img class="w-full h-44 object-cover" src="{{ $product['img'] }}" alt="{{ $product['title'] }}" />
                        <div class="absolute left-3 top-3 flex items-center gap-2">
                            <span class="inline-flex items-center h-6 px-3 rounded-full bg-black/40 text-white text-[11px]">{{ $product['tag'] }}</span>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="font-semibold text-sm text-slate-900 leading-snug">{{ $product['title'] }}</div>
                        <div class="mt-1 text-xs text-slate-500 leading-relaxed">{{ $product['desc'] }}</div>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <div class="text-xs text-slate-500">Harga</div>
                                <div class="text-sm font-bold text-slate-900">{{ $formatRupiah((int) ($product['price'] ?? 0)) }}</div>
                            </div>
                            @auth
                                <form
                                    method="POST"
                                    action="{{ route('storefront.cart.add', [], false) }}"
                                    x-on:submit.prevent="fetch($el.action, { method: 'POST', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: new FormData($el) }).then(r => { if (r.status === 401) { loginToast = true; setTimeout(() => loginToast = false, 3500); return null } return r.json() }).then(data => { if (!data) return; const nextCount = (typeof data.cart_count !== 'undefined') ? data.cart_count : 1; document.querySelectorAll('[data-tf-cart-button]').forEach(cartBtn => { let badge = cartBtn.querySelector('[data-tf-cart-badge]'); if (nextCount > 0) { if (!badge) { badge = document.createElement('span'); badge.setAttribute('data-tf-cart-badge', ''); badge.className = 'tf-cart-badge'; cartBtn.appendChild(badge); } badge.textContent = String(nextCount); } else { if (badge) badge.remove(); } }); })"
                                >
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product['id'] }}" />
                                    <button type="submit" class="inline-flex items-center justify-center h-9 px-3 rounded-2xl bg-[#556B55] text-white text-xs font-semibold">+ Keranjang</button>
                                </form>
                            @else
                                <button type="button" @click="loginToast = true; setTimeout(() => loginToast = false, 3500)" class="inline-flex items-center justify-center h-9 px-3 rounded-2xl bg-[#556B55] text-white text-xs font-semibold">+ Keranjang</button>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Full Width CTA Banner -->
    <div class="bg-[#556B55]" style="background-color: #556B55;">
        <div class="max-w-6xl mx-auto px-4 py-14 text-center text-white">
            <h3 class="text-2xl font-bold">Butuh Pesanan dalam Jumlah Besar?</h3>
            <p class="mt-2 text-sm text-white/80">Kami siap bantu kebutuhan grosir dan pengiriman luar kota</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                @php
                    $waNumber = '6285712149529';
                    $waText = rawurlencode('Halo Tempe Jaya Mandiri, saya ingin pesan tempe dalam jumlah besar.');
                @endphp
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#D7E6D0] text-[#29412E] font-semibold">Hubungi Kami</a>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.storefront')

@section('content')
    <section class="max-w-6xl mx-auto px-4 pt-10 pb-14">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="tf-animate-fade-up">


                <h1 class="mt-5 text-4xl sm:text-5xl font-bold tracking-tight text-slate-900 tf-animate-fade-up tf-delay-200">
                    Tempe Berkualitas Premium
                    <span class="block text-[#556B55]">untuk Pasar, Reseller &amp; Grosir</span>
                </h1>

                <p class="mt-4 text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl tf-animate-fade-up tf-delay-300">
                    Platform e-commerce untuk produsen UMKM tempe Indonesia. Hubungkan langsung dengan pembeli di pasar tradisional, reseller, dan pengiriman luar kota dengan harga terbaik.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ route('storefront.products') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-2xl bg-[#556B55] text-white font-semibold tf-hover-lift tf-animate-fade-up tf-delay-400">Lihat Produk</a>
                    <a href="{{ route('storefront.track') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-2xl border border-[#E8DDCF] bg-white/70 text-slate-800 font-semibold tf-hover-lift tf-animate-fade-up tf-delay-400">Lacak Pesanan</a>
                </div>

                <div class="mt-8 pt-6 border-t border-[#E8DDCF] grid grid-cols-3 gap-6 max-w-xl">
                    <div>
                        <div class="text-sm font-bold text-slate-900">500+</div>
                        <div class="text-xs text-slate-500">Mitra Pembeli</div>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">1000+</div>
                        <div class="text-xs text-slate-500">Transaksi/Bulan</div>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">98%</div>
                        <div class="text-xs text-slate-500">Kepuasan Pelanggan</div>
                    </div>
                </div>
            </div>

            <div class="relative tf-animate-fade-up tf-delay-200">
                <div class="rounded-[28px] overflow-hidden shadow-[0_20px_60px_rgba(17,24,39,0.12)] border border-[#E8DDCF] bg-white tf-hover-lift">
                    <img class="w-full h-[360px] sm:h-[420px] object-cover" src="{{ asset('images/tempe_hero.png') }}" alt="Hero image" />
                </div>

                <div class="absolute -bottom-6 left-8 bg-white/90 backdrop-blur border border-[#E8DDCF] rounded-2xl shadow-lg px-4 py-3 flex items-center gap-3 tf-animate-float">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center">
                        @if (file_exists(public_path('images/vector_tempe.png')))
                            <img src="{{ asset('images/vector_tempe.png') }}" alt="Tempe icon" class="h-10 w-10 object-contain" />
                        @else
                            <span class="text-emerald-900 font-bold">T</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-800">100% Lokal</div>
                        <div class="text-xs text-slate-600">Produsen UMKM Indonesia</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="max-w-6xl mx-auto px-4 py-16">
            <div class="text-center">
                <h2 class="text-2xl font-bold">Mengapa Memilih Tempe Jaya Mandiri?</h2>
                <p class="mt-2 text-sm text-slate-500">Platform terpercaya untuk memenuhi kebutuhan tempe berkualitas tinggi dengan sistem yang mudah dan efisien</p>
            </div>

            <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $benefits = [
                        ['Produk Segar', 'Tempe langsung dari produsen UMKM lokal, diproduksi setiap hari'],
                        ['Pengiriman Cepat', 'Layanan pengiriman ke seluruh Indonesia dengan packaging terjamin'],
                        ['Higienis & Aman', 'Proses produksi bersertifikat dan memenuhi standar kesehatan'],
                        ['Kualitas Terjamin', 'Hanya tempe berkualitas premium dengan cita rasa terbaik'],
                    ];
                @endphp

                @foreach ($benefits as $benefit)
                    <div class="rounded-3xl border border-[#E8DDCF] bg-[#FBF7F0] p-6 tf-hover-lift">
                        <div class="h-10 w-10 rounded-2xl bg-[#D7E6D0] grid place-items-center text-[#556B55]">
                            <span class="text-sm font-bold">✓</span>
                        </div>
                        <div class="mt-4 font-semibold">{{ $benefit[0] }}</div>
                        <div class="mt-2 text-sm text-slate-600">{{ $benefit[1] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center">
            <h2 class="text-2xl font-bold">Kategori Produk</h2>
            <p class="mt-2 text-sm text-slate-500">Dua pilihan tempe premium dengan karakteristik unik</p>
        </div>

        <div class="mt-10 grid lg:grid-cols-2 gap-8">
            <a href="{{ route('storefront.products') }}" class="relative rounded-[28px] overflow-hidden border border-[#E8DDCF] bg-white shadow-lg tf-hover-lift">
                <img class="w-full h-[280px] object-cover" src="{{ asset('images/tempeplastik_produk.png') }}" alt="Tempe Plastik" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent"></div>
                <div class="absolute left-6 bottom-6 text-white">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-black/30 text-xs">12 Produk</div>
                    <div class="mt-3 text-2xl font-bold">Tempe Plastik</div>
                    <div class="mt-1 text-sm text-white/85">Kemasan plastik food-grade, higienis &amp; tahan lama</div>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold">Lihat Produk <span>→</span></div>
                </div>
            </a>

            <a href="{{ route('storefront.products') }}" class="relative rounded-[28px] overflow-hidden border border-[#E8DDCF] bg-white shadow-lg tf-hover-lift">
                <img class="w-full h-[280px] object-cover" src="{{ asset('images/tempedaunpisang_produk.png') }}" alt="Tempe Daun Pisang" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-transparent"></div>
                <div class="absolute left-6 bottom-6 text-white">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-black/30 text-xs">8 Produk</div>
                    <div class="mt-3 text-2xl font-bold">Tempe Daun Pisang</div>
                    <div class="mt-1 text-sm text-white/85">Aroma khas daun pisang, rasa autentik tradisional</div>
                    <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold">Lihat Produk <span>→</span></div>
                </div>
            </a>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div class="rounded-[28px] overflow-hidden">
                <img class="w-full h-auto object-contain" src="{{ asset('images/vector_mitra.png') }}" alt="Keuntungan Bermitra" />
            </div>

            <div>
                <h2 class="text-2xl font-bold">Keuntungan Bermitra dengan Tempe Jaya Mandiri</h2>
                <p class="mt-2 text-sm text-slate-500">Solusi terbaik untuk pasar tradisional, reseller, dan pembeli grosir yang mencari tempe berkualitas dengan harga kompetitif.</p>

                @php
                    $items = [
                        'Harga kompetitif langsung dari produsen',
                        'Minimum order fleksibel untuk reseller',
                        'Kualitas konsisten dan terpercaya',
                        'Dukungan untuk pengiriman luar kota',
                        'Program kemitraan untuk pasar tradisional',
                        'Konsultasi gratis untuk bulk order',
                    ];
                @endphp

                <div class="mt-6 space-y-3">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-3 rounded-2xl bg-white/70 border border-[#E8DDCF] px-4 py-3">
                            <span class="h-6 w-6 rounded-full bg-[#D7E6D0] text-[#556B55] grid place-items-center text-xs font-bold">✓</span>
                            <span class="text-sm text-slate-700">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#556B55]" style="background-color: #556B55;">
        <div class="max-w-6xl mx-auto px-4 py-14 text-center text-white">
            <h3 class="text-2xl font-bold">Siap Menjadi Mitra Tempe Jaya Mandiri?</h3>
            <p class="mt-2 text-sm text-white/80">Bergabung dengan ratusan mitra yang telah mempercayai Tempe Jaya Mandiri untuk kebutuhan tempe berkualitas mereka.</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('storefront.products') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-[#D7E6D0] text-[#29412E] font-semibold">Mulai Belanja</a>
                <a href="https://wa.me/6285712149529" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center h-11 px-6 rounded-2xl border border-white/30 bg-white/10 font-semibold">Hubungi Kami</a>
            </div>
        </div>
    </section>
@endsection

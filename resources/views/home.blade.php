<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tempe Jaya Mandiri') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-b from-amber-50 via-white to-emerald-50 text-slate-900">
    <div class="min-h-screen">
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-emerald-600 shadow-sm"></div>
                        <div class="leading-tight">
                            <div class="font-semibold tracking-tight">Tempe Jaya Mandiri</div>
                            <div class="text-xs text-slate-500">Tempeh untuk pasar & reseller</div>
                        </div>
                    </a>

                    <nav class="flex items-center gap-2">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold bg-slate-900 text-white shadow-sm hover:bg-slate-800 transition">Register</a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </header>

        <main>
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 text-emerald-800 px-4 py-2 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                            Pameran Kampus: Demo E-commerce Tempe Jaya Mandiri
                        </div>

                        <h1 class="mt-5 text-4xl sm:text-5xl font-bold tracking-tight">
                            Tempeh segar, siap kirim.
                            <span class="text-emerald-700">Pesan mudah</span> untuk pasar, reseller, dan luar kota.
                        </h1>

                        <p class="mt-5 text-lg text-slate-600">
                            Tempe Jaya Mandiri membantu pelanggan memilih produk tempeh, membuat pesanan, memantau status produksi dan pengiriman,
                            tanpa payment gateway (COD / transfer manual).
                        </p>

                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('login') }}" class="inline-flex justify-center items-center rounded-2xl px-6 py-3 text-sm font-semibold bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 transition">Mulai Pesan</a>
                            <a href="#produk" class="inline-flex justify-center items-center rounded-2xl px-6 py-3 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">Lihat Produk</a>
                        </div>

                        <dl class="mt-10 grid grid-cols-2 gap-6">
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-5 shadow-sm">
                                <dt class="text-sm font-semibold">Alur order jelas</dt>
                                <dd class="mt-1 text-sm text-slate-600">Pending → Processing → Shipped → Completed</dd>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-5 shadow-sm">
                                <dt class="text-sm font-semibold">Tracking pengiriman</dt>
                                <dd class="mt-1 text-sm text-slate-600">Update status oleh tim distribusi</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="relative">
                        <div class="absolute -inset-4 bg-emerald-200/40 blur-3xl rounded-full"></div>
                        <div class="relative rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-xl">
                            <div class="aspect-[4/3]">
                                <img
                                    class="h-full w-full object-cover"
                                    src="https://images.unsplash.com/photo-1546554137-f86b9593a222?auto=format&fit=crop&w=1600&q=60"
                                    alt="Tempeh placeholder (replace with real tempeh image)"
                                />
                            </div>
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-sm font-semibold">Paket Tempeh Premium</div>
                                        <div class="mt-1 text-sm text-slate-600">Potongan rapi, cocok untuk reseller</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-slate-500">Mulai dari</div>
                                        <div class="text-lg font-bold text-emerald-700">Rp 12.000</div>
                                    </div>
                                </div>
                                <div class="mt-4 grid grid-cols-3 gap-3 text-xs">
                                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-3 text-center">
                                        <div class="font-semibold">Fresh</div>
                                        <div class="text-slate-500 mt-1">Harian</div>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-3 text-center">
                                        <div class="font-semibold">Higienis</div>
                                        <div class="text-slate-500 mt-1">Standar</div>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-3 text-center">
                                        <div class="font-semibold">Packing</div>
                                        <div class="text-slate-500 mt-1">Aman</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="produk" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
                <div class="flex items-end justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight">Produk unggulan</h2>
                        <p class="mt-2 text-slate-600">Contoh katalog untuk demo. Nanti akan tersambung ke database produk.</p>
                    </div>
                </div>

                <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                        <div class="aspect-[4/3] bg-slate-100">
                            <img class="h-full w-full object-cover" src="https://placehold.co/800x600/png?text=Tempeh+Image+1" alt="Product image placeholder (replace with real tempeh image)" />
                        </div>
                        <div class="p-6">
                            <div class="font-semibold">Tempeh Original 250g</div>
                            <div class="mt-1 text-sm text-slate-600">Cocok untuk rumah tangga & warung</div>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-lg font-bold">Rp 8.000</div>
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Pesan</a>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                        <div class="aspect-[4/3] bg-slate-100">
                            <img class="h-full w-full object-cover" src="https://placehold.co/800x600/png?text=Tempeh+Image+2" alt="Product image placeholder (replace with real tempeh image)" />
                        </div>
                        <div class="p-6">
                            <div class="font-semibold">Tempeh Premium 500g</div>
                            <div class="mt-1 text-sm text-slate-600">Untuk reseller & pesanan partai</div>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-lg font-bold">Rp 14.000</div>
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Pesan</a>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                        <div class="aspect-[4/3] bg-slate-100">
                            <img class="h-full w-full object-cover" src="https://placehold.co/800x600/png?text=Tempeh+Image+3" alt="Product image placeholder (replace with real tempeh image)" />
                        </div>
                        <div class="p-6">
                            <div class="font-semibold">Tempeh Sliced (Siap Goreng)</div>
                            <div class="mt-1 text-sm text-slate-600">Praktis untuk katering & UMKM</div>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-lg font-bold">Rp 16.000</div>
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="font-semibold">Tempe Jaya Mandiri</div>
                        <div class="text-sm text-slate-600">Demo e-commerce tempeh untuk pameran kampus.</div>
                    </div>
                    <div class="text-sm text-slate-500">© {{ date('Y') }} Tempe Jaya Mandiri</div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

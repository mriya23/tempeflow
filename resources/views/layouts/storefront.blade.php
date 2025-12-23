<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tempe Jaya Mandiri') }}</title>
        <link rel="icon" href="{{ asset('images/favicon_tjm.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .tf-cart-btn{position:relative;width:40px;height:40px;border:2px solid #2e7d32;border-radius:9999px;display:flex;align-items:center;justify-content:center;color:#2e7d32;background:#fff}
            .tf-cart-btn svg{stroke:#2e7d32;stroke-width:2.5}
            .tf-cart-badge{position:absolute;top:-6px;right:-6px;background:#ff3b30;color:#fff;font-size:12px;min-width:20px;height:20px;padding:0 5px;border-radius:9999px;display:flex;align-items:center;justify-content:center;font-weight:700}
            [x-cloak]{display:none !important}
        </style>
    </head>
    @php($tfToast = session()->pull('tf_toast'))
    <body
        x-data="{ loginToast: {{ $tfToast === 'login-required' ? 'true' : 'false' }}, cancelledToast: {{ $tfToast === 'order-cancelled' ? 'true' : 'false' }} }"
        x-init="if (loginToast) setTimeout(() => loginToast = false, 3500); if (cancelledToast) setTimeout(() => cancelledToast = false, 3500)"
        class="font-sans antialiased text-slate-900 bg-[#FBF7F0]"
    >
        <div class="min-h-screen flex flex-col">
            <header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 bg-[#FBF7F0]/90 backdrop-blur border-b border-[#E8DDCF]">
                <div class="max-w-6xl mx-auto px-4">
                    <div class="h-16 flex items-center justify-between">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <span class="text-xl font-bold tracking-tight">Tempe Jaya Mandiri</span>
                        </a>

                        <nav class="ml-auto flex items-center gap-4 text-sm">
                            <div class="hidden sm:flex items-center gap-3">
                                @if (Route::has('login'))
                                    @auth
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = ! open" type="button" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white border border-[#E8DDCF] shadow-sm text-slate-700 hover:bg-white transition">
                                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                                    <path d="M20 21a8 8 0 1 0-16 0" />
                                                    <circle cx="12" cy="8" r="3" />
                                                </svg>
                                            </button>

                                            <div
                                                x-show="open"
                                                x-cloak
                                                @click.outside="open = false"
                                                x-transition
                                                class="absolute right-0 mt-2 w-56 rounded-2xl border border-[#E8DDCF] bg-white shadow-lg overflow-hidden"
                                            >
                                                @if (Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')
                                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-sm text-slate-700 hover:bg-[#FBF7F0]">Admin Dashboard</a>
                                                @endif
                                                <a href="{{ route('storefront.my-orders') }}" class="block px-4 py-3 text-sm text-slate-700 hover:bg-[#FBF7F0]">Pesanan Saya</a>
                                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-slate-700 hover:bg-[#FBF7F0]">Profile</a>
                                                <form method="POST" action="{{ route('logout') }}" class="border-t border-[#E8DDCF]">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-[#FBF7F0]">Log Out</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center h-9 px-4 rounded-2xl bg-white/70 border border-[#E8DDCF] text-slate-700 font-semibold hover:bg-white transition">Masuk</a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center h-9 px-4 rounded-2xl bg-[#556B55] text-white font-semibold hover:opacity-95 transition">Daftar</a>
                                        @endif
                                    @endauth
                                @endif

                                @auth
                                    @php($cartCount = array_sum((array) session('cart', [])))
                                    <a href="{{ route('storefront.my-orders') }}" class="inline-flex items-center justify-center h-9 px-4 rounded-2xl bg-white/70 border border-[#E8DDCF] text-slate-700 font-semibold hover:bg-white transition">Pesanan Saya</a>
                                    <a data-tf-cart-button href="{{ route('storefront.cart') }}" class="tf-cart-btn">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                            <path d="M6 6h15l-1.5 9h-13z" />
                                            <path d="M6 6 5 3H2" />
                                            <circle cx="9" cy="21" r="1" />
                                            <circle cx="18" cy="21" r="1" />
                                        </svg>
                                        @if ($cartCount > 0)
                                            <span data-tf-cart-badge class="tf-cart-badge">{{ $cartCount }}</span>
                                        @endif
                                    </a>
                                @else
                                    <button type="button" @click="loginToast = true; setTimeout(() => loginToast = false, 3500)" class="relative inline-flex items-center justify-center h-9 w-9 rounded-full bg-white/70 border border-[#E8DDCF]">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-slate-700" stroke="currentColor" stroke-width="2">
                                            <path d="M6 6h15l-1.5 9h-13z" />
                                            <path d="M6 6 5 3H2" />
                                            <circle cx="9" cy="21" r="1" />
                                            <circle cx="18" cy="21" r="1" />
                                        </svg>
                                    </button>
                                @endauth
                            </div>

                            <div class="sm:hidden flex items-center gap-2">
                                @auth
                                    @php($cartCount = array_sum((array) session('cart', [])))
                                    <a data-tf-cart-button href="{{ route('storefront.cart') }}" class="tf-cart-btn">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                            <path d="M6 6h15l-1.5 9h-13z" />
                                            <path d="M6 6 5 3H2" />
                                            <circle cx="9" cy="21" r="1" />
                                            <circle cx="18" cy="21" r="1" />
                                        </svg>
                                        @if ($cartCount > 0)
                                            <span data-tf-cart-badge class="tf-cart-badge">{{ $cartCount }}</span>
                                        @endif
                                    </a>
                                @else
                                    <button type="button" @click="loginToast = true; setTimeout(() => loginToast = false, 3500)" class="relative inline-flex items-center justify-center h-10 w-10 rounded-full bg-white/70 border border-[#E8DDCF]">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-slate-700" stroke="currentColor" stroke-width="2">
                                            <path d="M6 6h15l-1.5 9h-13z" />
                                            <path d="M6 6 5 3H2" />
                                            <circle cx="9" cy="21" r="1" />
                                            <circle cx="18" cy="21" r="1" />
                                        </svg>
                                    </button>
                                @endauth

                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white border border-[#E8DDCF] shadow-sm text-slate-700"
                                    @click="mobileOpen = !mobileOpen"
                                    :aria-expanded="mobileOpen.toString()"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                        <path d="M4 6h16" />
                                        <path d="M4 12h16" />
                                        <path d="M4 18h16" />
                                    </svg>
                                </button>
                            </div>
                        </nav>
                    </div>

                    <div x-show="mobileOpen" x-cloak x-transition @click.outside="mobileOpen = false" class="sm:hidden pb-4">
                        <div class="rounded-3xl border border-[#E8DDCF] bg-white shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-[#E8DDCF] bg-[#FBF7F0] flex items-center justify-between">
                                <div class="text-sm font-bold text-slate-900">Menu</div>
                                <button type="button" class="h-9 px-4 rounded-full border border-[#E8DDCF] bg-white font-semibold text-xs" @click="mobileOpen = false">Tutup</button>
                            </div>

                            <div class="p-2">
                                <a @click="mobileOpen = false" href="{{ route('home') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Home</a>
                                <a @click="mobileOpen = false" href="{{ route('storefront.products') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Produk</a>
                                <a @click="mobileOpen = false" href="{{ route('storefront.track') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Lacak Pesanan</a>

                                <div class="my-2 h-px bg-[#E8DDCF]"></div>

                                @auth
                                    <a @click="mobileOpen = false" href="{{ route('storefront.my-orders') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Pesanan Saya</a>
                                    <a @click="mobileOpen = false" href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Profile</a>
                                    @if (Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')
                                        <a @click="mobileOpen = false" href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Admin Dashboard</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="px-2 pt-2">
                                        @csrf
                                        <button type="submit" class="w-full h-11 rounded-2xl bg-[#556B55] text-white font-semibold">Log Out</button>
                                    </form>
                                @else
                                    @if (Route::has('login'))
                                        <a @click="mobileOpen = false" href="{{ route('login') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Masuk</a>
                                    @endif
                                    @if (Route::has('register'))
                                        <a @click="mobileOpen = false" href="{{ route('register') }}" class="block px-4 py-3 rounded-2xl text-sm font-semibold text-slate-800 hover:bg-[#FBF7F0]">Daftar</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-cloak
                    x-transition
                    x-init="setTimeout(() => show = false, 3500)"
                    class="fixed top-5 right-5 z-[9999]"
                >
                    <div class="w-[340px] max-w-[calc(100vw-2.5rem)] rounded-3xl border border-[#E8DDCF] bg-white shadow-lg overflow-hidden">
                        <div class="p-4 flex items-start gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-[#D7E6D0] text-[#556B55] grid place-items-center flex-shrink-0">
                                <span class="text-sm font-bold">✓</span>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-slate-900">Password berhasil diperbarui</div>
                                <div class="mt-1 text-xs text-slate-500">Keamanan akun kamu sudah diperbarui.</div>
                            </div>
                            <button type="button" @click="show = false" class="h-8 w-8 rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] text-slate-600 hover:bg-white transition">×</button>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-[#556B55] to-[#6F8A6F]"></div>
                    </div>
                </div>
            @endif

            <div x-show="loginToast" x-cloak x-transition class="fixed top-5 right-5 z-[9999]">
                <div class="w-[360px] max-w-[calc(100vw-2.5rem)] rounded-3xl border border-[#E8DDCF] bg-white shadow-lg overflow-hidden">
                    <div class="p-4 flex items-start gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-[#FBF7F0] border border-[#E8DDCF] text-slate-700 grid place-items-center flex-shrink-0">
                            <span class="text-sm font-bold">!</span>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-slate-900">Login diperlukan</div>
                            <div class="mt-1 text-xs text-slate-500">Untuk menambahkan produk ke keranjang, silakan masuk dulu.</div>
                            <div class="mt-3 flex items-center gap-2">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center h-9 px-4 rounded-2xl bg-[#556B55] text-white text-xs font-semibold">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center h-9 px-4 rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] text-slate-800 text-xs font-semibold">Daftar</a>
                                @endif
                            </div>
                        </div>
                        <button type="button" @click="loginToast = false" class="h-8 w-8 rounded-2xl border border-[#E8DDCF] bg-[#FBF7F0] text-slate-600 hover:bg-white transition">×</button>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-[#556B55] to-[#6F8A6F]"></div>
                </div>
            </div>

            <div x-show="cancelledToast" x-cloak x-transition class="fixed top-5 right-5 z-[9999]">
                <div class="w-[360px] max-w-[calc(100vw-2.5rem)] rounded-3xl border border-red-200 bg-white shadow-lg overflow-hidden">
                    <div class="p-4 flex items-start gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-red-50 border border-red-200 text-red-600 grid place-items-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-slate-900">Pesanan Dibatalkan</div>
                            <div class="mt-1 text-xs text-slate-500">Pesanan kamu berhasil dibatalkan.</div>
                        </div>
                        <button type="button" @click="cancelledToast = false" class="h-8 w-8 rounded-2xl border border-red-200 bg-red-50 text-red-600 hover:bg-white transition">×</button>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-red-500 to-red-400"></div>
                </div>
            </div>

            <main class="flex-1">
                @yield('content')
            </main>

            <!-- Section Divider -->
            <div class="h-16 bg-[#FBF7F0]"></div>

            <footer class="mt-auto bg-[#1F3527] text-white" style="background-color: #1F3527;">
                <div class="max-w-6xl mx-auto px-4 py-12">
                    <div class="grid gap-8 lg:grid-cols-[420px_200px_320px] lg:justify-center">
                        <div>
                            <div>
                                <div class="font-bold tracking-tight text-base">Tempe Jaya Mandiri</div>
                                <div class="text-xs text-white/70">Platform e-commerce tempe berkualitas</div>
                            </div>

                            <p class="mt-4 text-sm text-white/70 leading-relaxed max-w-sm">Tempe Jaya Mandiri menghubungkan pembeli pasar, reseller, dan grosir dengan produsen UMKM tempe Indonesia.</p>

                            <div class="mt-5 flex items-center gap-3">
                                <a href="#" aria-label="Facebook" class="h-9 w-9 rounded-2xl bg-white/10 border border-white/10 grid place-items-center hover:bg-white/20 transition">
                                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2">
                                        <path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v3H8v3h3v6h3v-6h3l1-3h-4V9c0-.6.4-1 1-1Z" />
                                    </svg>
                                </a>
                                <a href="#" aria-label="Instagram" class="h-9 w-9 rounded-2xl bg-white/10 border border-white/10 grid place-items-center hover:bg-white/20 transition">
                                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2">
                                        <rect x="7" y="7" width="10" height="10" rx="3" />
                                        <path d="M16.5 7.5h.01" />
                                        <path d="M12 14.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-semibold">Menu</div>
                            <div class="mt-4 space-y-2 text-sm text-white/70">
                                <a class="block hover:text-white transition" href="{{ route('home') }}">Home</a>
                                <a class="block hover:text-white transition" href="{{ route('storefront.cart') }}">Keranjang</a>

                                @auth
                                    <a class="block hover:text-white transition" href="{{ route('profile.edit') }}">Profil</a>
                                @else
                                    @if (Route::has('login'))
                                        <a class="block hover:text-white transition" href="{{ route('login') }}">Masuk</a>
                                    @endif
                                    @if (Route::has('register'))
                                        <a class="block hover:text-white transition" href="{{ route('register') }}">Daftar</a>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-semibold">Kontak</div>
                            <div class="mt-4 space-y-3 text-sm text-white/70">
                                <div>Pliken,rt2 rw 5, Dusun IV, Pliken, Kec. Kembaran, Kabupaten Banyumas, Jawa Tengah 53182</div>
                                <div>
                                    <a class="hover:text-white transition" href="mailto:info@tempejayamandiri.id">info@tempejayamandiri.id</a>
                                </div>
                                <div>
                                    <a class="hover:text-white transition" href="tel:+6281234567890">0857-1214-9529</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-start sm:items-center sm:justify-center sm:gap-6 gap-2 text-xs text-white/60">
                        <div>© {{ date('Y') }} Tempe Jaya Mandiri. Hak Cipta Dilindungi.</div>
                        <div>Dibuat dengan ♥ untuk UMKM Indonesia</div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Lenis Smooth Scroll -->
        <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
        <script>
            const lenis = new Lenis();

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);
        </script>
    </body>
</html>

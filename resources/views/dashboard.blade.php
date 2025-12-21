@extends('layouts.storefront')

@section('content')
    <section class="max-w-6xl mx-auto px-4 pt-10 pb-16">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Halo, {{ Auth::user()->name }}. Selamat datang kembali di Tempe Jaya Mandiri.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center h-10 px-4 rounded-2xl bg-white/70 border border-[#E8DDCF] text-slate-700 font-semibold hover:bg-white transition">Profile</a>
                <a href="{{ route('storefront.products') }}" class="inline-flex items-center justify-center h-10 px-4 rounded-2xl bg-[#556B55] text-white font-semibold hover:opacity-95 transition">Belanja</a>
            </div>
        </div>

        <div class="mt-6 grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 rounded-3xl border border-[#E8DDCF] bg-white shadow-sm p-6">
                <div class="text-sm font-bold text-slate-900">Aktivitas Cepat</div>
                <div class="mt-4 grid sm:grid-cols-2 gap-4">
                    <a href="{{ route('storefront.products') }}" class="rounded-3xl border border-[#E8DDCF] bg-[#FBF7F0] p-5 hover:bg-white transition">
                        <div class="text-sm font-bold">Lihat Katalog Produk</div>
                        <div class="mt-1 text-xs text-slate-500">Pilih tempe plastik / daun pisang / grosir</div>
                        <div class="mt-4 text-sm font-semibold text-[#556B55]">Buka Katalog →</div>
                    </a>
                    <a href="{{ route('storefront.track') }}" class="rounded-3xl border border-[#E8DDCF] bg-[#FBF7F0] p-5 hover:bg-white transition">
                        <div class="text-sm font-bold">Lacak Pesanan</div>
                        <div class="mt-1 text-xs text-slate-500">Cek status pesanan dan pengiriman</div>
                        <div class="mt-4 text-sm font-semibold text-[#556B55]">Mulai Lacak →</div>
                    </a>
                </div>

                <div class="mt-6 rounded-[28px] overflow-hidden border border-[#E8DDCF] bg-gradient-to-r from-[#556B55] to-[#6F8A6F]">
                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-white">
                        <div>
                            <div class="text-sm font-bold">Butuh Pesanan Grosir?</div>
                            <div class="mt-1 text-xs text-white/80">Hubungi kami untuk kebutuhan besar & pengiriman luar kota</div>
                        </div>
                        <a href="{{ route('storefront.track') }}" class="inline-flex items-center justify-center h-10 px-5 rounded-2xl bg-[#D7E6D0] text-[#29412E] font-semibold text-sm">Hubungi Kami</a>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-[#E8DDCF] bg-white shadow-sm p-6">
                <div class="text-sm font-bold text-slate-900">Akun</div>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="rounded-2xl bg-[#FBF7F0] border border-[#E8DDCF] px-4 py-3">
                        <div class="text-xs text-slate-500">Email</div>
                        <div class="font-semibold text-slate-900">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="rounded-2xl bg-[#FBF7F0] border border-[#E8DDCF] px-4 py-3">
                        <div class="text-xs text-slate-500">Keranjang</div>
                        <a href="{{ route('storefront.cart') }}" class="font-semibold text-[#556B55]">Lihat Keranjang →</a>
                    </div>

                    @if (Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')
                        <div class="rounded-2xl bg-[#FBF7F0] border border-[#E8DDCF] px-4 py-3">
                            <div class="text-xs text-slate-500">Admin</div>
                            <a href="{{ route('admin.dashboard') }}" class="font-semibold text-[#556B55]">Masuk Admin Dashboard →</a>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center h-10 rounded-2xl border border-[#E8DDCF] bg-white text-slate-700 font-semibold hover:bg-[#FBF7F0] transition">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

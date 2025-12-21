@extends('layouts.storefront')

@section('content')
    <section class="max-w-6xl mx-auto px-4 pt-10 pb-16">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Profile') }}</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola informasi akun dan keamanan</p>
            </div>
            <a href="{{ route('home') }}" class="text-sm font-semibold text-[#556B55] hover:underline">Kembali ke Beranda</a>
        </div>

        <div class="mt-6 space-y-6">
            <div class="p-6 bg-white border border-[#E8DDCF] shadow-sm rounded-3xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 bg-white border border-[#E8DDCF] shadow-sm rounded-3xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 bg-white border border-[#E8DDCF] shadow-sm rounded-3xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tempe Jaya Mandiri') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon_v3.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #556B55 0%, #3d4f3d 100%); min-height: 100vh;">
        <div style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 24px;">

            <!-- Logo Text Only -->
            <a href="{{ route('home') }}" style="display: block; margin-bottom: 32px; text-decoration: none;">
                <div style="font-size: 28px; font-weight: 700; color: white; letter-spacing: -0.5px;">Tempe Jaya Mandiri</div>
            </a>

            <!-- Card -->
            <div style="width: 100%; max-width: 420px; background: white; border-radius: 24px; padding: 40px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div style="margin-top: 32px; text-align: center; color: rgba(255,255,255,0.7); font-size: 14px;">
                &copy; {{ date('Y') }} Tempe Jaya Mandiri. Semua hak dilindungi.
            </div>
        </div>
    </body>
</html>

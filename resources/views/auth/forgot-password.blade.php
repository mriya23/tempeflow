<x-guest-layout>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">Lupa Kata Sandi</h1>
        <p style="margin-top: 8px; color: #64748b; font-size: 14px;">Masukkan email untuk menerima link reset password</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div style="margin-bottom: 16px; padding: 12px 16px; background: #d1fae5; border-radius: 12px; color: #065f46; font-size: 14px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Alamat Email
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="contoh@email.com"
                style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                onfocus="this.style.borderColor='#556B55'"
                onblur="this.style.borderColor='#e2e8f0'"
            />
            @error('email')
                <p style="margin-top: 8px; color: #dc2626; font-size: 13px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            style="width: 100%; padding: 16px; background: #556B55; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
            onmouseover="this.style.background='#4a5d4a'"
            onmouseout="this.style.background='#556B55'"
        >
            Kirim Link Reset Password
        </button>

        <!-- Divider -->
        <div style="margin: 24px 0; display: flex; align-items: center; gap: 16px;">
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            <span style="color: #94a3b8; font-size: 13px;">atau</span>
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
        </div>

        <!-- Back to Login -->
        <a
            href="{{ route('login') }}"
            style="display: block; width: 100%; padding: 14px; background: white; color: #374151; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; box-sizing: border-box; transition: border-color 0.2s;"
            onmouseover="this.style.borderColor='#556B55'"
            onmouseout="this.style.borderColor='#e2e8f0'"
        >
            Kembali ke Halaman Login
        </a>
    </form>
</x-guest-layout>

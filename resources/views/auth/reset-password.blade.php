<x-guest-layout>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">Reset Kata Sandi</h1>
        <p style="margin-top: 8px; color: #64748b; font-size: 14px;">Buat kata sandi baru untuk akun Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Alamat Email
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                placeholder="contoh@email.com"
                style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color 0.2s; box-sizing: border-box; background: #f8fafc;"
                onfocus="this.style.borderColor='#556B55'"
                onblur="this.style.borderColor='#e2e8f0'"
                readonly
            />
            @error('email')
                <p style="margin-top: 8px; color: #dc2626; font-size: 13px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div style="margin-bottom: 20px;">
            <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Kata Sandi Baru
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                onfocus="this.style.borderColor='#556B55'"
                onblur="this.style.borderColor='#e2e8f0'"
            />
            @error('password')
                <p style="margin-top: 8px; color: #dc2626; font-size: 13px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div style="margin-bottom: 24px;">
            <label for="password_confirmation" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Konfirmasi Kata Sandi Baru
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi kata sandi baru"
                style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                onfocus="this.style.borderColor='#556B55'"
                onblur="this.style.borderColor='#e2e8f0'"
            />
            @error('password_confirmation')
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
            Reset Kata Sandi
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

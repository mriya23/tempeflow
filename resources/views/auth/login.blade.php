<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">Selamat Datang Kembali</h1>
        <p style="margin-top: 8px; color: #64748b; font-size: 14px;">Masuk untuk melanjutkan belanja tempe berkualitas</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
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
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label for="password" style="font-size: 14px; font-weight: 500; color: #374151;">
                    Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 13px; color: #556B55; text-decoration: none; font-weight: 500;">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="••••••••"
                style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                onfocus="this.style.borderColor='#556B55'"
                onblur="this.style.borderColor='#e2e8f0'"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div style="margin-bottom: 24px;">
            <label for="remember_me" style="display: flex; align-items: center; cursor: pointer;">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    value="1"
                    style="width: 18px; height: 18px; border-radius: 4px; border: 2px solid #cbd5e1; margin-right: 10px; accent-color: #556B55; cursor: pointer;"
                >
                <span style="font-size: 14px; color: #64748b;">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            style="width: 100%; padding: 16px; background: #556B55; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
            onmouseover="this.style.background='#4a5d4a'"
            onmouseout="this.style.background='#556B55'"
        >
            Masuk ke Akun
        </button>

        <!-- Divider -->
        <div style="display: flex; align-items: center; margin: 28px 0;">
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            <span style="padding: 0 16px; color: #94a3b8; font-size: 13px;">atau</span>
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div style="text-align: center;">
                <span style="color: #64748b; font-size: 14px;">Belum punya akun?</span>
                <a href="{{ route('register') }}" style="color: #556B55; text-decoration: none; font-weight: 600; margin-left: 4px; font-size: 14px;">
                    Daftar Sekarang
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>

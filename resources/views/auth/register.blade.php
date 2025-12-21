<x-guest-layout>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">Buat Akun Baru</h1>
        <p style="margin-top: 8px; color: #64748b; font-size: 14px;">Bergabunglah dengan mitra Tempe Jaya Mandiri</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Nama Lengkap
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama lengkap"
                style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                onfocus="this.style.borderColor='#556B55'"
                onblur="this.style.borderColor='#e2e8f0'"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
            <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Kata Sandi
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
                oninput="checkPasswordStrength(this.value)"
            />
            <!-- Password Strength Indicator -->
            <div id="strength-container" style="margin-top: 8px; display: none;">
                <div style="display: flex; gap: 4px; margin-bottom: 4px;">
                    <div id="bar-1" style="flex: 1; height: 4px; border-radius: 2px; background: #e2e8f0;"></div>
                    <div id="bar-2" style="flex: 1; height: 4px; border-radius: 2px; background: #e2e8f0;"></div>
                    <div id="bar-3" style="flex: 1; height: 4px; border-radius: 2px; background: #e2e8f0;"></div>
                    <div id="bar-4" style="flex: 1; height: 4px; border-radius: 2px; background: #e2e8f0;"></div>
                </div>
                <div id="strength-text" style="font-size: 12px; color: #64748b;"></div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div style="margin-bottom: 24px;">
            <label for="password_confirmation" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                Konfirmasi Kata Sandi
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi kata sandi"
                style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                onfocus="this.style.borderColor='#556B55'"
                onblur="this.style.borderColor='#e2e8f0'"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            style="width: 100%; padding: 16px; background: #556B55; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
            onmouseover="this.style.background='#4a5d4a'"
            onmouseout="this.style.background='#556B55'"
        >
            Buat Akun
        </button>

        <!-- Divider -->
        <div style="display: flex; align-items: center; margin: 28px 0;">
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            <span style="padding: 0 16px; color: #94a3b8; font-size: 13px;">sudah punya akun?</span>
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
        </div>

        <!-- Login Link -->
        <a
            href="{{ route('login') }}"
            style="display: block; width: 100%; padding: 14px; background: white; color: #374151; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; box-sizing: border-box; transition: border-color 0.2s;"
            onmouseover="this.style.borderColor='#556B55'"
            onmouseout="this.style.borderColor='#e2e8f0'"
        >
            Masuk ke Akun
        </a>
    </form>

    <script>
        function checkPasswordStrength(password) {
            const container = document.getElementById('strength-container');
            const bar1 = document.getElementById('bar-1');
            const bar2 = document.getElementById('bar-2');
            const bar3 = document.getElementById('bar-3');
            const bar4 = document.getElementById('bar-4');
            const text = document.getElementById('strength-text');

            if (password.length === 0) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'block';

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
            const labels = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];
            const textColors = ['#ef4444', '#f97316', '#ca8a04', '#16a34a'];

            bar1.style.background = strength >= 1 ? colors[strength - 1] : '#e2e8f0';
            bar2.style.background = strength >= 2 ? colors[strength - 1] : '#e2e8f0';
            bar3.style.background = strength >= 3 ? colors[strength - 1] : '#e2e8f0';
            bar4.style.background = strength >= 4 ? colors[strength - 1] : '#e2e8f0';

            text.textContent = strength > 0 ? labels[strength - 1] : 'Sangat Lemah';
            text.style.color = strength > 0 ? textColors[strength - 1] : '#ef4444';
        }
    </script>
</x-guest-layout>

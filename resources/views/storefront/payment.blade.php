@extends('layouts.storefront')

@section('content')
    @php
        $order = $order ?? null;
        $success_url = $success_url ?? null;
        $payment_error = $payment_error ?? null;
        $formatRupiah = $format_rupiah ?? fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
    @endphp

    <style>
        .payment-container { max-width: 900px; margin: 0 auto; padding: 48px 16px; }
        .payment-header { text-align: center; margin-bottom: 40px; }
        .payment-header h1 { font-size: 28px; font-weight: 700; color: #1e293b; margin: 0; }
        .payment-header p { font-size: 15px; color: #64748b; margin-top: 8px; }
        .payment-header .order-code { font-weight: 600; color: #334155; }
        
        .payment-grid { display: grid; grid-template-columns: 1fr; gap: 32px; align-items: start; }
        @media (min-width: 768px) { .payment-grid { grid-template-columns: 320px 1fr; } }
        
        .payment-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; height: fit-content; }
        .payment-card-header { padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .payment-card-header h2 { font-size: 16px; font-weight: 600; color: #1e293b; margin: 0; }
        .payment-card-body { padding: 24px; }
        
        .order-item { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; padding: 12px 0; }
        .order-item:not(:last-child) { border-bottom: 1px solid #f1f5f9; }
        .order-item-name { font-size: 14px; font-weight: 500; color: #1e293b; }
        .order-item-detail { font-size: 13px; color: #64748b; margin-top: 2px; }
        .order-item-price { font-size: 14px; font-weight: 600; color: #1e293b; white-space: nowrap; }
        
        .order-summary { margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0; }
        .order-summary-row { display: flex; justify-content: space-between; font-size: 14px; padding: 6px 0; }
        .order-summary-label { color: #64748b; }
        .order-summary-value { color: #334155; }
        .order-summary-value.free { color: #059669; font-weight: 500; }
        
        .order-total { margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .order-total-label { font-size: 15px; font-weight: 600; color: #1e293b; }
        .order-total-value { font-size: 24px; font-weight: 700; color: #1e293b; }
        
        .method-section { margin-bottom: 24px; }
        .method-section:last-of-type { margin-bottom: 0; }
        .method-section-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
        
        .method-option { display: flex; align-items: center; gap: 16px; padding: 16px; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all 0.15s; margin-bottom: 10px; background: #fff; }
        .method-option:hover { border-color: #cbd5e1; background: #f8fafc; }
        .method-option.selected { border-color: #10b981; background: #ecfdf5; }
        
        .method-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0; }
        .method-icon.qris { background: linear-gradient(135deg, #8b5cf6, #6366f1); color: #fff; }
        .method-icon.bca { background: #1e40af; color: #fff; }
        .method-icon.bni { background: #f97316; color: #fff; }
        .method-icon.bri { background: #1e3a8a; color: #fff; }
        .method-icon.mandiri { background: #fbbf24; color: #1e293b; }
        .method-icon.permata { background: #0d9488; color: #fff; }
        
        .method-info { flex: 1; }
        .method-name { font-size: 15px; font-weight: 600; color: #1e293b; }
        .method-desc { font-size: 13px; color: #64748b; margin-top: 2px; }
        
        .method-check { width: 24px; height: 24px; border-radius: 50%; background: #10b981; color: #fff; display: none; align-items: center; justify-content: center; flex-shrink: 0; }
        .method-option.selected .method-check { display: flex; }
        
        .pay-button { width: 100%; height: 52px; border: none; border-radius: 12px; background: #1e293b; color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.15s; margin-top: 24px; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .pay-button:hover:not(:disabled) { background: #0f172a; }
        .pay-button:disabled { opacity: 0.4; cursor: not-allowed; }
        
        .qr-display { text-align: center; padding: 32px 24px; }
        .qr-image { background: #fff; padding: 16px; border: 2px solid #e2e8f0; border-radius: 16px; display: inline-block; margin-bottom: 20px; }
        .qr-image img { width: 200px; height: 200px; }
        .qr-amount { font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .qr-hint { font-size: 14px; color: #64748b; margin-bottom: 20px; }
        .qr-wallets { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; }
        .qr-wallet { padding: 6px 12px; background: #f1f5f9; border-radius: 6px; font-size: 12px; color: #475569; }
        
        .va-display { text-align: center; padding: 32px 24px; }
        .va-bank { font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .va-bank-name { font-size: 20px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-bottom: 24px; }
        .va-box { background: #f8fafc; border-radius: 12px; padding: 24px; margin-bottom: 20px; }
        .va-label { font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .va-number { font-size: 28px; font-weight: 700; font-family: monospace; color: #1e293b; letter-spacing: 2px; margin-bottom: 16px; }
        .va-copy { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; font-size: 14px; color: #475569; cursor: pointer; }
        .va-copy:hover { background: #f8fafc; }
        .va-amount { font-size: 24px; font-weight: 700; color: #1e293b; }
        
        .status-indicator { display: flex; align-items: center; justify-content: center; gap: 8px; padding-top: 24px; margin-top: 24px; border-top: 1px solid #e2e8f0; font-size: 14px; }
        .status-indicator.waiting { color: #64748b; }
        .status-indicator.success { color: #059669; }
        
        .back-link { display: block; text-align: center; margin-top: 24px; font-size: 14px; color: #64748b; text-decoration: none; }
        .back-link:hover { color: #475569; }
        
        /* Clean Professional Modal */
        .notification-overlay { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .notification-backdrop { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.4); }
        .notification-card { position: relative; background: #fff; border-radius: 12px; padding: 32px 28px; max-width: 340px; width: 100%; text-align: center; box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12); }
        
        .notification-icon { width: 52px; height: 52px; margin: 0 auto 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .notification-icon.success { background: #ecfdf5; }
        .notification-icon.failed { background: #fef2f2; }
        .notification-icon svg { width: 24px; height: 24px; }
        
        .notification-title { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 6px; }
        .notification-amount { font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 4px; letter-spacing: -0.5px; }
        .notification-desc { font-size: 14px; color: #6b7280; }
        .notification-footer { font-size: 13px; color: #9ca3af; margin-top: 20px; }
        
        .notification-btn { display: block; width: 100%; height: 44px; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 20px; transition: background 0.15s; }
        .notification-btn.primary { background: #111827; color: #fff; }
        .notification-btn.primary:hover { background: #1f2937; }
        
        .spinner { animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .error-alert { background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px 20px; color: #991b1b; font-size: 14px; margin-bottom: 24px; }
    </style>

    <section class="payment-container" x-data="paymentApp()">
        @if (!$order)
            <div class="error-alert">Pesanan tidak ditemukan.</div>
        @else
            <div class="payment-header">
                <h1>Pembayaran</h1>
                <p>Selesaikan pembayaran untuk pesanan <span class="order-code">{{ $order->code }}</span></p>
            </div>

            <div class="payment-grid">
                {{-- Order Summary --}}
                <div class="payment-card">
                    <div class="payment-card-header">
                        <h2>Ringkasan Pesanan</h2>
                    </div>
                    <div class="payment-card-body">
                        @foreach ($order->items as $it)
                            <div class="order-item">
                                <div>
                                    <div class="order-item-name">{{ $it->product_title }}</div>
                                    <div class="order-item-detail">{{ $formatRupiah((int) $it->price) }} × {{ (int) $it->qty }}</div>
                                </div>
                                <div class="order-item-price">{{ $formatRupiah((int) $it->subtotal) }}</div>
                            </div>
                        @endforeach

                        <div class="order-summary">
                            <div class="order-summary-row">
                                <span class="order-summary-label">Subtotal</span>
                                <span class="order-summary-value">{{ $formatRupiah((int) $order->subtotal) }}</span>
                            </div>
                            <div class="order-summary-row">
                                <span class="order-summary-label">Ongkir</span>
                                @if ((int) $order->shipping_cost > 0)
                                    <span class="order-summary-value">{{ $formatRupiah((int) $order->shipping_cost) }}</span>
                                @else
                                    <span class="order-summary-value free">Gratis</span>
                                @endif
                            </div>
                        </div>

                        <div class="order-total">
                            <span class="order-total-label">Total</span>
                            <span class="order-total-value">{{ $formatRupiah((int) $order->grand_total) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Payment Methods --}}
                <div>
                    <div x-show="errorMessage" x-cloak class="error-alert" x-text="errorMessage"></div>

                    {{-- Selection Step --}}
                    <div x-show="step === 'select'" class="payment-card">
                        <div class="payment-card-header">
                            <h2>Pilih Metode Pembayaran</h2>
                        </div>
                        <div class="payment-card-body">
                            {{-- QRIS --}}
                            <div class="method-section">
                                <div class="method-section-label">E-Wallet / QRIS</div>
                                <div class="method-option" :class="{ selected: selectedMethod === 'qris' }" @click="selectMethod('qris')">
                                    <div class="method-icon qris">QRIS</div>
                                    <div class="method-info">
                                        <div class="method-name">Scan QRIS</div>
                                        <div class="method-desc">GoPay, OVO, DANA, ShopeePay, LinkAja</div>
                                    </div>
                                    <div class="method-check">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Virtual Account --}}
                            <div class="method-section">
                                <div class="method-section-label">Virtual Account</div>
                                
                                <div class="method-option" :class="{ selected: selectedMethod === 'bank_transfer' && selectedBank === 'bca' }" @click="selectMethod('bank_transfer', 'bca')">
                                    <div class="method-icon bca">BCA</div>
                                    <div class="method-info">
                                        <div class="method-name">Bank BCA</div>
                                        <div class="method-desc">Transfer ke Virtual Account</div>
                                    </div>
                                    <div class="method-check">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>

                                <div class="method-option" :class="{ selected: selectedMethod === 'bank_transfer' && selectedBank === 'bni' }" @click="selectMethod('bank_transfer', 'bni')">
                                    <div class="method-icon bni">BNI</div>
                                    <div class="method-info">
                                        <div class="method-name">Bank BNI</div>
                                        <div class="method-desc">Transfer ke Virtual Account</div>
                                    </div>
                                    <div class="method-check">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>

                                <div class="method-option" :class="{ selected: selectedMethod === 'bank_transfer' && selectedBank === 'bri' }" @click="selectMethod('bank_transfer', 'bri')">
                                    <div class="method-icon bri">BRI</div>
                                    <div class="method-info">
                                        <div class="method-name">Bank BRI</div>
                                        <div class="method-desc">Transfer ke Virtual Account</div>
                                    </div>
                                    <div class="method-check">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>

                                <div class="method-option" :class="{ selected: selectedMethod === 'bank_transfer' && selectedBank === 'mandiri' }" @click="selectMethod('bank_transfer', 'mandiri')">
                                    <div class="method-icon mandiri">MDR</div>
                                    <div class="method-info">
                                        <div class="method-name">Bank Mandiri</div>
                                        <div class="method-desc">Transfer ke Virtual Account</div>
                                    </div>
                                    <div class="method-check">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>

                                <div class="method-option" :class="{ selected: selectedMethod === 'bank_transfer' && selectedBank === 'permata' }" @click="selectMethod('bank_transfer', 'permata')">
                                    <div class="method-icon permata">PMT</div>
                                    <div class="method-info">
                                        <div class="method-name">Bank Permata</div>
                                        <div class="method-desc">Transfer ke Virtual Account</div>
                                    </div>
                                    <div class="method-check">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>

                            <button class="pay-button" :disabled="!selectedMethod || isLoading" @click="chargePayment()">
                                <template x-if="isLoading">
                                    <svg class="spinner" width="20" height="20" fill="none" viewBox="0 0 24 24">
                                        <circle opacity="0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path opacity="0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </template>
                                <span x-text="isLoading ? 'Memproses...' : 'Bayar Sekarang'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- QRIS Step --}}
                    <div x-show="step === 'qris'" x-cloak class="payment-card">
                        <div class="payment-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h2>Scan QR Code</h2>
                            <span style="font-size: 12px; color: #64748b;">Berlaku hingga <span x-text="formatExpiry(expiryTime)"></span></span>
                        </div>
                        <div class="qr-display">
                            <div class="qr-image">
                                <img :src="qrUrl" alt="QRIS" />
                            </div>
                            <div class="qr-amount">{{ $formatRupiah((int) $order->grand_total) }}</div>
                            <div class="qr-hint">Scan dengan aplikasi e-wallet favoritmu</div>
                            <div class="qr-wallets">
                                <span class="qr-wallet">GoPay</span>
                                <span class="qr-wallet">OVO</span>
                                <span class="qr-wallet">DANA</span>
                                <span class="qr-wallet">ShopeePay</span>
                            </div>
                            <div class="status-indicator" :class="isPaid ? 'success' : 'waiting'">
                                <template x-if="!isPaid">
                                    <svg class="spinner" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                        <circle opacity="0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path opacity="0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </template>
                                <template x-if="isPaid">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <span x-text="isPaid ? 'Pembayaran berhasil!' : 'Menunggu pembayaran...'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- VA Step --}}
                    <div x-show="step === 'va'" x-cloak class="payment-card">
                        <div class="payment-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h2>Transfer Virtual Account</h2>
                            <span style="font-size: 12px; color: #64748b;">Berlaku hingga <span x-text="formatExpiry(expiryTime)"></span></span>
                        </div>
                        <div class="va-display">
                            <div class="va-bank">Bank</div>
                            <div class="va-bank-name" x-text="selectedBank"></div>
                            
                            <div class="va-box">
                                <div class="va-label">Nomor Virtual Account</div>
                                <div class="va-number" x-text="vaNumber"></div>
                                <button class="va-copy" @click="copyVA()">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span x-text="copied ? 'Tersalin!' : 'Salin Nomor'"></span>
                                </button>
                            </div>

                            <div class="va-box">
                                <div class="va-label">Total Pembayaran</div>
                                <div class="va-amount">{{ $formatRupiah((int) $order->grand_total) }}</div>
                            </div>

                            <div class="status-indicator" :class="isPaid ? 'success' : 'waiting'">
                                <template x-if="!isPaid">
                                    <svg class="spinner" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                        <circle opacity="0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path opacity="0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </template>
                                <template x-if="isPaid">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <span x-text="isPaid ? 'Pembayaran berhasil!' : 'Menunggu pembayaran...'"></span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('storefront.my-orders') }}" class="back-link">← Kembali ke Pesanan Saya</a>
                </div>
            </div>

            {{-- Success Notification --}}
            <div x-show="showSuccessModal" x-cloak class="notification-overlay">
                <div class="notification-backdrop"></div>
                <div class="notification-card">
                    <div class="notification-icon success">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="notification-title">Pembayaran Berhasil</div>
                    <div class="notification-amount">{{ $formatRupiah((int) $order->grand_total) }}</div>
                    <div class="notification-desc">Pesanan sedang diproses</div>
                    <div class="notification-footer">Mengalihkan...</div>
                </div>
            </div>

            {{-- Failed Notification --}}
            <div x-show="showFailedModal" x-cloak class="notification-overlay">
                <div class="notification-backdrop"></div>
                <div class="notification-card">
                    <div class="notification-icon failed">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="notification-title">Pembayaran Gagal</div>
                    <div class="notification-desc">Waktu pembayaran habis atau dibatalkan</div>
                    <button @click="retryPayment()" class="notification-btn primary">Coba Lagi</button>
                </div>
            </div>
        @endif
    </section>

    @if ($order)
        <script>
            function paymentApp() {
                return {
                    step: 'select',
                    selectedMethod: null,
                    selectedBank: null,
                    isLoading: false,
                    isPaid: false,
                    isFailed: false,
                    showSuccessModal: false,
                    showFailedModal: false,
                    errorMessage: '',
                    qrUrl: '',
                    vaNumber: '',
                    expiryTime: '',
                    copied: false,
                    pollingInterval: null,

                    chargeUrl: @json(route('storefront.checkout.charge', ['code' => $order->code])),
                    statusUrl: @json(route('storefront.checkout.status', ['code' => $order->code])),
                    successUrl: @json($success_url),
                    csrfToken: @json(csrf_token()),

                    selectMethod(method, bank = null) {
                        this.selectedMethod = method;
                        this.selectedBank = bank;
                        this.errorMessage = '';
                    },

                    async chargePayment() {
                        if (!this.selectedMethod) return;
                        this.isLoading = true;
                        this.errorMessage = '';

                        try {
                            const response = await fetch(this.chargeUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                },
                                body: JSON.stringify({
                                    payment_method: this.selectedMethod,
                                    bank: this.selectedBank || '',
                                }),
                            });

                            const data = await response.json();
                            if (!response.ok) throw new Error(data.error || 'Terjadi kesalahan');

                            if (data.payment_method === 'qris') {
                                this.qrUrl = data.qr_url;
                                this.expiryTime = data.expiry_time;
                                this.step = 'qris';
                            } else if (data.payment_method === 'bank_transfer') {
                                this.vaNumber = data.va_number;
                                this.expiryTime = data.expiry_time;
                                this.step = 'va';
                            }
                            this.startPolling();
                        } catch (error) {
                            this.errorMessage = error.message;
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    startPolling() {
                        if (this.pollingInterval) return;
                        this.pollingInterval = setInterval(async () => {
                            if (this.isPaid || this.isFailed) return;
                            try {
                                const response = await fetch(this.statusUrl, { headers: { 'Accept': 'application/json' } });
                                if (response.ok) {
                                    const data = await response.json();
                                    
                                    // Success
                                    if (data.payment_status === 'paid' || data.status === 'Dikemas') {
                                        this.isPaid = true;
                                        this.stopPolling();
                                        this.showSuccessModal = true;
                                        setTimeout(() => { window.location.href = this.successUrl; }, 3500);
                                    }
                                    
                                    // Failed/Expired/Cancelled
                                    if (data.payment_status === 'expired' || data.payment_status === 'cancel' || data.payment_status === 'deny' || data.status === 'Dibatalkan') {
                                        this.isFailed = true;
                                        this.stopPolling();
                                        this.showFailedModal = true;
                                    }
                                }
                            } catch (e) {}
                        }, 500);
                    },

                    stopPolling() {
                        if (this.pollingInterval) { clearInterval(this.pollingInterval); this.pollingInterval = null; }
                    },

                    formatExpiry(datetime) {
                        if (!datetime) return '-';
                        const d = new Date(datetime);
                        return d.toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
                    },

                    copyVA() {
                        navigator.clipboard.writeText(this.vaNumber);
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    },

                    retryPayment() {
                        this.showFailedModal = false;
                        this.isFailed = false;
                        this.step = 'select';
                        this.selectedMethod = null;
                        this.selectedBank = null;
                        this.qrUrl = '';
                        this.vaNumber = '';
                        this.expiryTime = '';
                    },

                    init() { window.addEventListener('beforeunload', () => this.stopPolling()); }
                };
            }
        </script>
    @endif
@endsection

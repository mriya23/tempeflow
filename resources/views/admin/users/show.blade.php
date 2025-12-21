@extends('layouts.storefront')

@section('content')
<style>
    .admin-page {
        --color-primary: #2D5A3D;
        --color-primary-ink: #234A31;
        --color-surface: #FFFFFF;
        --color-background: #F8F9FA;
        --color-border: #E5E7EB;
        --color-text-primary: #111827;
        --color-text-secondary: #6B7280;
        --color-text-muted: #9CA3AF;
        --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
        --radius: 14px;
        --radius-sm: 10px;
    }

    .admin-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
    }

    .admin-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 40px;
        padding: 0 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        line-height: 1;
        transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        user-select: none;
        white-space: nowrap;
        text-decoration: none;
    }

    .admin-btn-primary {
        background: var(--color-primary);
        color: #fff;
        border: 1px solid transparent;
    }
    .admin-btn-primary:hover { background: var(--color-primary-ink); }

    .admin-btn-secondary {
        background: #fff;
        color: var(--color-text-primary);
        border: 1px solid var(--color-border);
    }
    .admin-btn-secondary:hover {
        background: var(--color-background);
        border-color: #D1D5DB;
    }

    .user-avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2D5A3D 0%, #4A7C4E 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 28px;
        flex-shrink: 0;
    }

    .stat-card {
        padding: 16px;
        border-radius: 12px;
        background: #fff;
        border: 1px solid var(--color-border);
    }
    .stat-card .value {
        font-size: 24px;
        font-weight: 700;
        color: var(--color-text-primary);
    }
    .stat-card .label {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: 4px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        height: 24px;
        padding: 0 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .status-pending { background: #F3E8FF; color: #7C3AED; }
    .status-packed { background: #FEF3C7; color: #D97706; }
    .status-shipped { background: #DBEAFE; color: #2563EB; }
    .status-done { background: #D1FAE5; color: #059669; }
    .status-canceled { background: #FEE2E2; color: #DC2626; }
</style>

<section class="admin-page max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-10">
    @php
        $formatRupiah = fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
        $tz = (string) config('app.timezone', 'Asia/Jakarta');
        $statusClasses = [
            'Menunggu Pembayaran' => 'status-pending',
            'Dikemas' => 'status-packed',
            'Dikirim' => 'status-shipped',
            'Selesai' => 'status-done',
            'Dibatalkan' => 'status-canceled',
        ];
    @endphp

    <!-- Header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="min-w-0">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Pelanggan
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Detail Pelanggan</h1>
        </div>
    </div>

    <!-- Customer Profile Card -->
    <div class="admin-card mb-6">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <div class="user-avatar-lg">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500 mt-1">{{ $user->email }}</p>
                    <div class="mt-3 flex flex-wrap gap-3 text-sm text-gray-600">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Bergabung {{ $user->created_at?->format('d M Y') }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Terakhir aktif {{ $user->updated_at?->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="value text-blue-600">{{ $stats['total_orders'] ?? 0 }}</div>
                    <div class="label">Total Pesanan</div>
                </div>
                <div class="stat-card">
                    <div class="value text-emerald-600">{{ $stats['completed_orders'] ?? 0 }}</div>
                    <div class="label">Selesai</div>
                </div>
                <div class="stat-card">
                    <div class="value text-rose-600">{{ $stats['cancelled_orders'] ?? 0 }}</div>
                    <div class="label">Dibatalkan</div>
                </div>
                <div class="stat-card">
                    <div class="value text-gray-900" style="font-size: 18px;">{{ $formatRupiah($stats['total_spending'] ?? 0) }}</div>
                    <div class="label">Total Belanja</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Favorite Products -->
        <div class="admin-card lg:col-span-1">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">🛒 Produk Favorit</h3>
                <p class="text-xs text-gray-500 mt-0.5">Produk yang paling sering dibeli</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($favorite_products ?? [] as $index => $fp)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-{{ $index === 0 ? 'amber' : 'gray' }}-100 flex items-center justify-center text-xs font-bold text-{{ $index === 0 ? 'amber' : 'gray' }}-600">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $fp->product_title }}</div>
                        </div>
                        <div class="text-sm font-semibold text-gray-600">
                            {{ $fp->total_qty }}x
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500">
                        Belum ada pembelian
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Order History -->
        <div class="admin-card lg:col-span-2">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">📦 Riwayat Pesanan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Semua pesanan dari pelanggan ini</p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($orders as $o)
                    @php
                        $itemsCount = (int) $o->items->sum('qty');
                    @endphp
                    <div class="p-4" x-data="{ open: false }">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-gray-900">{{ $o->code }}</span>
                                    <span class="{{ $statusClasses[$o->status] ?? 'status-pending' }} status-badge">{{ $o->status }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $o->created_at?->timezone($tz)->format('d M Y, H:i') }} • {{ $itemsCount }} item
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-gray-900">{{ $formatRupiah((int) $o->grand_total) }}</div>
                                <button type="button" @click="open = !open" class="mt-1 text-xs text-emerald-600 hover:underline">
                                    <span x-text="open ? 'Tutup' : 'Lihat Item'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Order Items (expandable) -->
                        <div x-show="open" x-collapse class="mt-3 p-3 rounded-xl bg-gray-50 border border-gray-200">
                            <div class="space-y-2">
                                @foreach ($o->items as $it)
                                    <div class="flex items-center justify-between text-sm">
                                        <div>
                                            <span class="text-gray-900">{{ $it->product_title }}</span>
                                            <span class="text-gray-500">× {{ $it->qty }}</span>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $formatRupiah((int) $it->subtotal) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($o->shipping_address)
                                <div class="mt-3 pt-3 border-t border-gray-200 text-xs text-gray-600">
                                    <div class="font-medium text-gray-700">Alamat Pengiriman:</div>
                                    <div>{{ $o->recipient_name }} ({{ $o->recipient_phone }})</div>
                                    <div>{{ $o->shipping_address }}, {{ $o->shipping_city }} {{ $o->shipping_postal_code }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p>Belum ada pesanan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

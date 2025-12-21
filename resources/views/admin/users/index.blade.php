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

    .kpi-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }
    @media (min-width: 640px) { .kpi-grid { grid-template-columns: repeat(3, 1fr); } }

    .kpi {
        padding: 18px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid var(--color-border);
        box-shadow: var(--shadow-sm);
    }
    .kpi strong {
        display: block;
        margin-top: 6px;
        font-size: 24px;
        line-height: 1.1;
        color: var(--color-text-primary);
    }
    .kpi .label {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 600;
    }
    .kpi .icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        border: 1px solid var(--color-border);
        background: #F9FAFB;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2D5A3D 0%, #4A7C4E 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
</style>

<section class="admin-page max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-10">
    @php
        $formatRupiah = fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
    @endphp

    <!-- Header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="min-w-0">
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Pelanggan</h1>
            <p class="mt-1 text-sm text-gray-500">Lihat daftar pelanggan dan riwayat pesanan mereka</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Stats -->
    <div class="kpi-grid mb-6">
        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="label">Total Pelanggan</div>
                    <strong>{{ number_format($stats['total_customers'] ?? 0) }}</strong>
                </div>
                <div class="icon">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="label">Pernah Belanja</div>
                    <strong>{{ number_format($stats['customers_with_orders'] ?? 0) }}</strong>
                </div>
                <div class="icon">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="label">Baru Bulan Ini</div>
                    <strong>{{ number_format($stats['new_this_month'] ?? 0) }}</strong>
                </div>
                <div class="icon">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="admin-card">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Daftar Pelanggan</h2>
                    <p class="mt-0.5 text-xs text-gray-500">Klik nama pelanggan untuk melihat detail</p>
                </div>
            </div>

            <!-- Search & Filter -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="mt-4 flex flex-wrap gap-3">
                <div style="flex: 1 1 200px; min-width: 200px; position: relative;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #9CA3AF; pointer-events: none; z-index: 1;">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Cari nama atau email..."
                        style="width: 100%; height: 44px; padding: 0 16px 0 44px; border: 1px solid #E5E7EB; border-radius: 12px; font-size: 14px; background: #fff; color: #111827; outline: none; box-sizing: border-box;"
                    />
                </div>
                <select name="sort" class="h-11 px-4 border border-gray-200 rounded-xl text-sm bg-white">
                    <option value="latest" {{ ($filters['sort'] ?? '') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ ($filters['sort'] ?? '') === 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="orders" {{ ($filters['sort'] ?? '') === 'orders' ? 'selected' : '' }}>Pesanan Terbanyak</option>
                    <option value="spending" {{ ($filters['sort'] ?? '') === 'spending' ? 'selected' : '' }}>Belanja Terbanyak</option>
                </select>
                <button type="submit" class="admin-btn admin-btn-secondary">Cari</button>
                @if (($filters['q'] ?? '') !== '' || ($filters['sort'] ?? 'latest') !== 'latest')
                    <a href="{{ route('admin.users.index') }}" class="admin-btn" style="border: 1px solid #FECACA; color: #DC2626;">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table (Desktop) -->
        <div class="overflow-x-auto" style="display: none;" id="desktop-table">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wide">Bergabung</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wide">Total Pesanan</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wide">Total Belanja</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $u)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $u->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $u->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center h-7 px-3 rounded-full bg-blue-50 text-blue-700 font-semibold text-xs">
                                    {{ $u->orders_count ?? 0 }} pesanan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ $formatRupiah((int) ($u->total_spending ?? 0)) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.users.show', $u) }}" class="admin-btn admin-btn-secondary" style="height: 32px; font-size: 12px;">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 48px 24px; text-align: center; color: #6B7280;">
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <svg style="width: 48px; height: 48px; color: #D1D5DB; margin-bottom: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p style="font-size: 14px; margin: 0;">Tidak ada pelanggan ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Cards (Mobile) -->
        <div class="divide-y divide-gray-100" id="mobile-cards">
            @forelse ($users as $u)
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="user-avatar">
                            {{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-gray-900">{{ $u->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $u->email }}</div>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                                    {{ $u->orders_count ?? 0 }} pesanan
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 font-medium">
                                    {{ $formatRupiah((int) ($u->total_spending ?? 0)) }}
                                </span>
                            </div>
                            <div class="mt-2 text-xs text-gray-400">
                                Bergabung {{ $u->created_at?->format('d M Y') }}
                            </div>
                        </div>
                        <a href="{{ route('admin.users.show', $u) }}" class="admin-btn admin-btn-secondary" style="height: 32px; padding: 0 10px; font-size: 11px;">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div style="padding: 48px 24px; text-align: center; color: #6B7280;">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <svg style="width: 48px; height: 48px; color: #D1D5DB; margin-bottom: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p style="font-size: 14px; margin: 0;">Tidak ada pelanggan ditemukan</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</section>

<style>
    /* Desktop: show table, hide cards */
    @media (min-width: 640px) {
        #desktop-table {
            display: block !important;
        }
        #mobile-cards {
            display: none !important;
        }
    }
    /* Mobile: hide table, show cards */
    @media (max-width: 639px) {
        #desktop-table {
            display: none !important;
        }
        #mobile-cards {
            display: block !important;
        }
    }
</style>
@endsection

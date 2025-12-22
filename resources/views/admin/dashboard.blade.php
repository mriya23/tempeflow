@extends('layouts.storefront')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Minimal Modern Admin UI (clean, consistent) */
    .admin-dashboard {
        --color-primary: #2D5A3D;
        --color-primary-ink: #234A31;
        --color-primary-soft: rgba(45, 90, 61, 0.10);
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
        height: 44px;
        padding: 0 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        line-height: 1;
        transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        user-select: none;
        white-space: nowrap;
    }

    /* Search input focus state */
    .admin-dashboard input[type="text"]:focus {
        border-color: #2D5A3D !important;
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1) !important;
    }

    .admin-dashboard select:focus {
        border-color: #2D5A3D !important;
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1) !important;
        outline: none !important;
    }

    .admin-dashboard button[type="submit"]:hover {
        background: #F8F9FA !important;
        border-color: #D1D5DB !important;
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

    .admin-btn-danger {
        background: #fff;
        color: #DC2626;
        border: 1px solid #FECACA;
    }
    .admin-btn-danger:hover {
        background: #FEF2F2;
        border-color: #F87171;
    }

    .admin-input {
        height: 44px;
        border: 1px solid var(--color-border);
        border-radius: 10px;
        padding: 0 12px;
        font-size: 14px;
        background: #fff;
        color: var(--color-text-primary);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        width: 100%;
    }

    .admin-input.pl-10 { padding-left: 2.5rem; }

    .admin-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px var(--color-primary-soft);
    }

    .admin-table {
        width: 100%;
        font-size: 13px;
    }

    .admin-table thead {
        background: #FAFBFC;
    }

    .admin-table th {
        padding: 12px 18px;
        text-align: left;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border);
        white-space: nowrap;
    }

    .admin-table td {
        padding: 14px 18px;
        border-bottom: 1px solid var(--color-border);
        vertical-align: middle;
        color: var(--color-text-primary);
    }

    .admin-table tbody tr:hover {
        background: #FAFBFC;
    }

    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        height: 24px;
        padding: 0 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .status-pending { background: #F3E8FF; color: #7C3AED; border-color: #E9D5FF; }
    .status-packed { background: #FEF3C7; color: #D97706; border-color: #FDE68A; }
    .status-shipped { background: #DBEAFE; color: #2563EB; border-color: #BFDBFE; }
    .status-done { background: #D1FAE5; color: #059669; border-color: #A7F3D0; }
    .status-canceled { background: #FEE2E2; color: #DC2626; border-color: #FECACA; }

    /* Summary cards (clean) */
    .kpi-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }
    @media (min-width: 640px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .kpi-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 1280px) { .kpi-grid { grid-template-columns: repeat(6, 1fr); } }

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
        font-size: 20px;
        line-height: 1.1;
        color: var(--color-text-primary);
        letter-spacing: -0.01em;
    }
    .kpi .label {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 700;
    }
    .kpi .meta {
        margin-top: 10px;
        font-size: 12px;
        color: var(--color-text-muted);
    }
    .kpi .icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        border: 1px solid var(--color-border);
        background: #F9FAFB;
        flex: none;
    }

    .kpi.kpi-primary {
        background: var(--color-primary);
        color: #fff;
        border-color: transparent;
    }
    .kpi.kpi-primary .label { color: rgba(255,255,255,0.80); }
    .kpi.kpi-primary strong { color: #fff; }
    .kpi.kpi-primary .meta { color: rgba(255,255,255,0.70); }
    .kpi.kpi-primary .icon {
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.18);
    }

    /* Filter chips keep behavior, adjust radius/weight */
    .filter-chip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        background: #fff;
        font-size: 13px;
        transition: background 0.15s ease, border-color 0.15s ease;
        text-decoration: none;
        color: inherit;
    }

    .filter-chip:hover {
        border-color: #D1D5DB;
        background: var(--color-background);
    }

    .filter-chip.active {
        border-color: var(--color-primary);
        background: rgba(45, 90, 61, 0.06);
    }

    /* Layout Helpers to bypass Tailwind Build - Responsive */
    /* Mobile (Default) */
    .gap-lg { gap: 24px !important; }
    .gap-md { gap: 16px !important; }
    .mb-lg { margin-bottom: 24px !important; }
    .space-y-lg > * + * { margin-top: 24px !important; }
    .p-lg { padding: 16px !important; }

    .admin-only-mobile { display: block; }
    .admin-only-desktop { display: none; }

    @media (min-width: 640px) {
        .admin-only-mobile { display: none; }
        .admin-only-desktop { display: block; }
    }

    /* Desktop (md and up) */
    @media (min-width: 768px) {
        .gap-lg { gap: 32px !important; }
        .gap-md { gap: 24px !important; }
        .mb-lg { margin-bottom: 40px !important; }
        .space-y-lg > * + * { margin-top: 32px !important; }
        .p-lg { padding: 24px !important; }
    }
</style>

<section class="admin-dashboard max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-10">
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
        $filters = $filters ?? ['q' => '', 'status' => ''];
        $statusCounts = $status_counts ?? [];
    @endphp

    <!-- Header (clean) -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="min-w-0">
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola pesanan dan pantau penjualan</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pelanggan
            </a>
            <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-secondary">
                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Produk
            </a>
            <a href="{{ route('admin.reports.pdf', ['date_from' => now()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="admin-btn admin-btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Laporan PDF
            </a>
        </div>
    </div>

    @if (session('admin_notice'))
        <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('admin_notice') }}
        </div>
    @endif

    <!-- Summary Cards (cleaner) -->
    <div class="kpi-grid mt-6 mb-lg">
        <div class="kpi kpi-primary">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="label">Total Penjualan</div>
                    <strong>{{ $formatRupiah((int) ($stats['total_revenue'] ?? 0)) }}</strong>
                    <div class="meta">Hari ini: <span class="font-semibold text-white">{{ $formatRupiah((int) ($stats['today_revenue'] ?? 0)) }}</span></div>
                </div>
                <div class="icon" aria-hidden="true">
                    <svg class="w-5 h-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="label">Total Pesanan</div>
                    <strong>{{ (int) ($stats['total_orders'] ?? 0) }}</strong>
                    <div class="meta">Hari ini: <span class="font-semibold text-gray-900">{{ (int) ($stats['today_orders'] ?? 0) }}</span></div>
                </div>
                <div class="icon" aria-hidden="true">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="label">Menunggu Bayar</div>
                    <strong style="color:#7C3AED;">{{ (int) ($stats['orders_pending'] ?? 0) }}</strong>
                    <div class="meta">Status: <span class="status-badge status-pending">Pending</span></div>
                </div>
                <div class="icon" aria-hidden="true">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="label">Perlu Dikemas</div>
                    <strong style="color:#B45309;">{{ (int) ($stats['orders_packed'] ?? 0) }}</strong>
                    <div class="meta">Status: <span class="status-badge status-packed">Dikemas</span></div>
                </div>
                <div class="icon" aria-hidden="true">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="label">Sedang Dikirim</div>
                    <strong style="color:#2563EB;">{{ (int) ($stats['orders_shipped'] ?? 0) }}</strong>
                    <div class="meta">Status: <span class="status-badge status-shipped">Dikirim</span></div>
                </div>
                <div class="icon" aria-hidden="true">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="kpi">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="label">Selesai</div>
                    <strong style="color:#059669;">{{ (int) ($stats['orders_done'] ?? 0) }}</strong>
                    <div class="meta">Status: <span class="status-badge status-done">Selesai</span></div>
                </div>
                <div class="icon" aria-hidden="true">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Chart Section -->
    <div class="admin-card mt-6 mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Grafik Penjualan</h2>
                    <p class="mt-0.5 text-xs text-gray-500">Analisis penjualan harian, mingguan, dan bulanan</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="showChart('daily')" id="btn-daily" class="admin-btn admin-btn-primary" style="height: 36px; padding: 0 14px; font-size: 12px;">Harian</button>
                    <button type="button" onclick="showChart('weekly')" id="btn-weekly" class="admin-btn admin-btn-secondary" style="height: 36px; padding: 0 14px; font-size: 12px;">Mingguan</button>
                    <button type="button" onclick="showChart('monthly')" id="btn-monthly" class="admin-btn admin-btn-secondary" style="height: 36px; padding: 0 14px; font-size: 12px;">Bulanan</button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div style="position: relative; height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products & Recent Orders Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Products -->
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-900">🏆 Produk Terlaris</h2>
                <p class="mt-0.5 text-xs text-gray-500">Top 5 produk berdasarkan jumlah terjual</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($top_products ?? [] as $index => $tp)
                    <div class="p-4 flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-{{ $index === 0 ? 'amber' : ($index === 1 ? 'gray' : ($index === 2 ? 'orange' : 'slate')) }}-100 flex items-center justify-center text-sm font-bold text-{{ $index === 0 ? 'amber' : ($index === 1 ? 'gray' : ($index === 2 ? 'orange' : 'slate')) }}-600">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 text-sm truncate">{{ $tp->product_title }}</div>
                            <div class="text-xs text-gray-500">{{ $formatRupiah((int) $tp->total_revenue) }} revenue</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-gray-900 text-sm">{{ number_format($tp->total_sold) }}</div>
                            <div class="text-xs text-gray-500">terjual</div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500">Belum ada data penjualan</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="admin-card">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-900">🕐 Pesanan Terbaru</h2>
                <p class="mt-0.5 text-xs text-gray-500">5 pesanan terakhir yang masuk</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($recent_orders ?? [] as $ro)
                    <div class="p-4 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-lg">
                            📦
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 text-sm">{{ $ro->code }}</div>
                            <div class="text-xs text-gray-500">{{ $ro->user?->name ?? 'Guest' }} • {{ $ro->created_at?->timezone($tz)->diffForHumans() }}</div>
                        </div>
                        <div class="text-right">
                            <span class="status-badge {{ $statusClasses[$ro->status] ?? 'bg-gray-100 text-gray-700' }}" style="font-size: 10px; height: 20px; padding: 0 8px;">{{ $ro->status }}</span>
                            <div class="text-xs font-semibold text-gray-900 mt-1">{{ $formatRupiah((int) $ro->grand_total) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500">Belum ada pesanan</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 gap-lg">
        <!-- Orders Table (Full Width) -->
        <div class="admin-card">
            <div class="p-6 border-b border-gray-100">
                <div id="dashboard" class="w-full flex flex-col gap-4">
                    <div class="w-full flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div class="min-w-0">
                            <h2 class="text-base font-semibold text-gray-900">Manajemen Pesanan</h2>
                            <p class="mt-0.5 text-xs text-gray-500">Cari dan kelola status pesanan</p>
                        </div>
                        <a href="{{ route('admin.orders.export', ['status' => $filters['status'] ?? '', 'date_from' => $filters['date_from'] ?? '', 'date_to' => $filters['date_to'] ?? '']) }}" class="admin-btn admin-btn-secondary" style="height: 36px; padding: 0 14px; font-size: 12px;">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export CSV
                        </a>
                    </div>
                </div>

                <!-- Search & Filter (responsive: stack on mobile, row on desktop) -->
                <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; flex-wrap: wrap; gap: 12px; width: 100%; margin-top: 16px;">
                    <!-- 1) Search bar (dominant) -->
                    <div style="flex: 1 1 220px; min-width: 0; position: relative;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #9CA3AF; pointer-events: none; z-index: 1;">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            name="q"
                            value="{{ $filters['q'] ?? '' }}"
                            placeholder="Cari kode, nama, email..."
                            autocomplete="off"
                            inputmode="search"
                            aria-label="Cari pesanan"
                            style="display: block; width: 100%; height: 44px; padding: 0 14px 0 44px; border: 1px solid #E5E7EB; border-radius: 10px; font-size: 14px; background: #fff; color: #111827; outline: none; box-sizing: border-box;"
                        />
                    </div>

                    <!-- 2) Filter status -->
                    <div style="flex: 0 0 auto;">
                        <select name="status" style="display: block; width: 150px; height: 44px; padding: 0 12px; border: 1px solid #E5E7EB; border-radius: 10px; font-size: 13px; background: #fff; color: #111827; cursor: pointer; box-sizing: border-box;">
                            <option value="">Semua Status</option>
                            @foreach (['Menunggu Pembayaran','Dikemas','Dikirim','Selesai','Dibatalkan'] as $st)
                                <option value="{{ $st }}" {{ (($filters['status'] ?? '') === $st) ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3) Date From -->
                    <div style="flex: 0 0 auto;">
                        <input
                            type="date"
                            name="date_from"
                            value="{{ $filters['date_from'] ?? '' }}"
                            placeholder="Dari tanggal"
                            style="display: block; width: 150px; height: 44px; padding: 0 12px; border: 1px solid #E5E7EB; border-radius: 10px; font-size: 13px; background: #fff; color: #111827; box-sizing: border-box;"
                        />
                    </div>

                    <!-- 4) Date To -->
                    <div style="flex: 0 0 auto;">
                        <input
                            type="date"
                            name="date_to"
                            value="{{ $filters['date_to'] ?? '' }}"
                            placeholder="Sampai tanggal"
                            style="display: block; width: 150px; height: 44px; padding: 0 12px; border: 1px solid #E5E7EB; border-radius: 10px; font-size: 13px; background: #fff; color: #111827; box-sizing: border-box;"
                        />
                    </div>

                    <!-- 5) Search action -->
                    <div style="flex: 0 0 auto;">
                        <button type="submit" style="display: block; width: 80px; height: 44px; border: 1px solid #E5E7EB; border-radius: 10px; font-size: 14px; font-weight: 600; background: #fff; color: #111827; cursor: pointer; box-sizing: border-box;">Cari</button>
                    </div>

                    <!-- 6) Reset -->
                    @if (($filters['q'] ?? '') !== '' || ($filters['status'] ?? '') !== '' || ($filters['date_from'] ?? '') !== '' || ($filters['date_to'] ?? '') !== '')
                        <div style="flex: 0 0 auto;">
                            <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; justify-content: center; width: 80px; height: 44px; border: 1px solid #FECACA; border-radius: 10px; font-size: 14px; font-weight: 600; background: #fff; color: #DC2626; text-decoration: none; box-sizing: border-box;">Reset</a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Table -->
            @php
                    $ordersItems = $orders;
                    if ($orders instanceof \Illuminate\Pagination\AbstractPaginator) {
                        $ordersItems = $orders->getCollection();
                    } elseif ($orders instanceof \Illuminate\Support\LazyCollection) {
                        $ordersItems = $orders->collect();
                    }
                @endphp
                <div x-data="{ openId: null }">
                    <div class="admin-only-mobile p-4 space-y-3">
                        @forelse ($ordersItems as $o)
                            @php
                                $itemsCount = (int) $o->items->sum('qty');
                                $itemsLines = (int) $o->items->count();
                            @endphp

                            <div class="p-4 rounded-xl border border-gray-200 bg-white">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $o->code }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $o->created_at?->timezone($tz)->format('d M Y, H:i') }}</div>
                                    </div>
                                    <span class="status-badge {{ $statusClasses[$o->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $o->status }}</span>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    <div style="min-width: 0; overflow: hidden;">
                                        <div class="text-xs text-gray-500">Pelanggan</div>
                                        <div class="text-sm font-medium text-gray-900" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $o->user?->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $o->user?->email ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Item</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $itemsCount }} pcs</div>
                                        <div class="text-xs text-gray-500">{{ $itemsLines }} produk</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Total</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $formatRupiah((int) $o->grand_total) }}</div>
                                    </div>
                                    <div class="flex items-end justify-end">
                                        <button type="button" class="admin-btn admin-btn-secondary h-9 px-4" @click="openId = (openId === {{ $o->id }} ? null : {{ $o->id }})">Detail</button>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <form method="POST" action="{{ route('admin.orders.updateStatus', ['order' => $o->id]) }}">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="admin-input w-full">
                                            @if ($o->status === 'Menunggu Pembayaran')
                                                <option value="Menunggu Pembayaran" selected disabled>Menunggu</option>
                                            @endif
                                            @foreach (['Dikemas','Dikirim','Selesai','Dibatalkan'] as $st)
                                                <option value="{{ $st }}" {{ $o->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>

                                <div class="mt-3" x-show="openId === {{ $o->id }}" x-cloak>
                                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-semibold text-gray-900">Detail Pesanan {{ $o->code }}</h4>
                                            <button type="button" @click="openId = null" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Item Pesanan</p>
                                                @foreach ($o->items as $it)
                                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                                                        <div>
                                                            <p class="font-medium text-gray-900 text-sm">{{ $it->product_title }}</p>
                                                            <p class="text-xs text-gray-500">{{ $formatRupiah((int) $it->price) }} × {{ $it->qty }}</p>
                                                        </div>
                                                        <p class="font-semibold text-gray-900 text-sm">{{ $formatRupiah((int) $it->subtotal) }}</p>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="space-y-4">
                                                <div>
                                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Alamat Pengiriman</p>
                                                    <div class="p-3 bg-white rounded-lg border border-gray-200 text-sm">
                                                        <p class="font-medium text-gray-900">{{ $o->recipient_name ?? '-' }}</p>
                                                        <p class="text-gray-600">{{ $o->recipient_phone ?? '-' }}</p>
                                                        <p class="text-gray-600 mt-1">{{ $o->shipping_address ?? '-' }}</p>
                                                        <p class="text-gray-600">{{ $o->shipping_city ?? '-' }} {{ $o->shipping_postal_code ?? '' }}</p>
                                                    </div>
                                                </div>

                                                <div class="p-3 bg-white rounded-lg border border-gray-200">
                                                    <div class="flex justify-between text-sm mb-2">
                                                        <span class="text-gray-500">Subtotal</span>
                                                        <span class="font-medium">{{ $formatRupiah((int) $o->subtotal) }}</span>
                                                    </div>
                                                    <div class="flex justify-between text-sm pt-2 border-t border-gray-200">
                                                        <span class="font-medium text-gray-900">Grand Total</span>
                                                        <span class="font-bold text-gray-900">{{ $formatRupiah((int) $o->grand_total) }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-4 bg-white rounded-lg border border-gray-200 mt-4">
                                                <h5 class="text-sm font-semibold text-gray-900 mb-3">Update Status Pesanan</h5>
                                                <form method="POST" action="{{ route('admin.orders.updateStatus', ['order' => $o->id]) }}" class="space-y-3">
                                                    @csrf
                                                    <div>
                                                        <label class="text-xs text-gray-500">Status</label>
                                                        <select name="status" class="admin-input w-full h-10 text-sm">
                                                            @foreach (['Menunggu Pembayaran','Dikemas','Dikirim','Selesai','Dibatalkan'] as $st)
                                                                <option value="{{ $st }}" {{ $o->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="admin-btn admin-btn-primary w-full h-10">Simpan Perubahan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="font-medium text-gray-900">Tidak ada pesanan</p>
                                    <p class="text-sm text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="admin-only-desktop" style="overflow-x: auto; width: 100%;">
                        <table class="w-full text-sm" style="min-width: 900px; table-layout: fixed;">
                            <thead class="bg-[#FBF7F0] text-slate-600">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold tracking-wide whitespace-nowrap" style="width: 140px;">Pesanan</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold tracking-wide whitespace-nowrap" style="width: 200px;">Pelanggan</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold tracking-wide whitespace-nowrap" style="width: 80px;">Item</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold tracking-wide whitespace-nowrap" style="width: 110px;">Total</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold tracking-wide whitespace-nowrap" style="width: 160px;">Status</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold tracking-wide whitespace-nowrap" style="width: 280px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ordersItems as $o)
                                    @php
                                        $itemsCount = (int) $o->items->sum('qty');
                                        $itemsLines = (int) $o->items->count();
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-4 align-top" style="width: 140px;">
                                            <div class="font-medium text-gray-900">{{ $o->code }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $o->created_at?->timezone($tz)->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td class="px-4 py-4 align-top" style="width: 200px;">
                                            <div class="font-medium text-gray-900">{{ $o->user?->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5 truncate" style="max-width: 180px;">{{ $o->user?->email ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4 align-top" style="width: 80px;">
                                            <div class="font-medium text-gray-900">{{ $itemsCount }} pcs</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $itemsLines }} produk</div>
                                        </td>
                                        <td class="px-4 py-4 align-top" style="width: 110px;">
                                            <div class="font-semibold text-gray-900">{{ $formatRupiah((int) $o->grand_total) }}</div>
                                        </td>
                                        <td class="px-4 py-4 align-top" style="width: 160px;">
                                            <span class="status-badge {{ $statusClasses[$o->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $o->status }}</span>
                                        </td>
                                        <td class="px-4 py-4 align-top" style="width: 280px;">
                                            <div class="flex items-center gap-2">
                                                {{-- Detail Button --}}
                                                <button 
                                                    type="button" 
                                                    @click="openId = (openId === {{ $o->id }} ? null : {{ $o->id }})"
                                                    class="inline-flex items-center h-8 px-3 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:border-slate-300 transition-all duration-150"
                                                >
                                                    <svg class="w-3.5 h-3.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span>Detail</span>
                                                </button>

                                                {{-- Invoice Button --}}
                                                <a 
                                                    href="{{ route('admin.orders.invoice', $o) }}" 
                                                    class="inline-flex items-center justify-center h-8 w-8 text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:border-slate-300 hover:text-slate-700 transition-all duration-150"
                                                    title="Download Invoice"
                                                >
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>

                                                @if ($o->status !== 'Dibatalkan' && $o->status !== 'Selesai')
                                                    {{-- Status Dropdown with Visual Stepper --}}
                                                    <div x-data="{ statusOpen: false }" class="relative">
                                                        <button 
                                                            type="button"
                                                            @click="statusOpen = !statusOpen"
                                                            @click.away="statusOpen = false"
                                                            class="inline-flex items-center gap-2 h-8 px-3 text-xs font-semibold rounded-lg transition-all duration-150
                                                                {{ $o->status === 'Menunggu Pembayaran' ? 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100' : '' }}
                                                                {{ $o->status === 'Dikemas' ? 'bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100' : '' }}
                                                                {{ $o->status === 'Dikirim' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100' : '' }}
                                                            "
                                                        >
                                                            @if ($o->status === 'Menunggu Pembayaran')
                                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                            @elseif ($o->status === 'Dikemas')
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                            @elseif ($o->status === 'Dikirim')
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                                                            @endif
                                                            <span>{{ $o->status === 'Menunggu Pembayaran' ? 'Menunggu' : $o->status }}</span>
                                                            <svg class="w-3 h-3 transition-transform duration-150" :class="statusOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7" /></svg>
                                                        </button>

                                                        {{-- Dropdown Menu --}}
                                                        <div 
                                                            x-show="statusOpen" 
                                                            x-transition:enter="transition ease-out duration-100"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-75"
                                                            x-transition:leave-start="opacity-100 scale-100"
                                                            x-transition:leave-end="opacity-0 scale-95"
                                                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-200 z-50 overflow-hidden"
                                                            x-cloak
                                                        >
                                                            <div class="px-3 py-2 bg-slate-50 border-b border-slate-100">
                                                                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Ubah Status Pesanan</p>
                                                            </div>
                                                            <div class="p-1.5">
                                                                {{-- Dikemas --}}
                                                                <form method="POST" action="{{ route('admin.orders.updateStatus', ['order' => $o->id]) }}" style="margin: 0;">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Dikemas">
                                                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors {{ $o->status === 'Dikemas' ? 'bg-blue-50 text-blue-700' : 'hover:bg-slate-50 text-slate-700' }}" {{ $o->status === 'Dikemas' ? 'disabled' : '' }}>
                                                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                                        <span class="text-xs font-medium">Dikemas {{ $o->status === 'Dikemas' ? '(Saat ini)' : '' }}</span>
                                                                    </button>
                                                                </form>

                                                                {{-- Dikirim --}}
                                                                <form method="POST" action="{{ route('admin.orders.updateStatus', ['order' => $o->id]) }}" style="margin: 0;">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Dikirim">
                                                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors {{ $o->status === 'Dikirim' ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-slate-50 text-slate-700' }}" {{ $o->status === 'Dikirim' ? 'disabled' : '' }}>
                                                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                                                                        <span class="text-xs font-medium">Dikirim {{ $o->status === 'Dikirim' ? '(Saat ini)' : '' }}</span>
                                                                    </button>
                                                                </form>

                                                                {{-- Selesai --}}
                                                                <form method="POST" action="{{ route('admin.orders.updateStatus', ['order' => $o->id]) }}" style="margin: 0;">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="Selesai">
                                                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors hover:bg-emerald-50 text-slate-700 hover:text-emerald-700">
                                                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                        <span class="text-xs font-medium">Selesai</span>
                                                                    </button>
                                                                </form>

                                                                <div class="border-t border-slate-100 mt-1 pt-1">
                                                                    {{-- Batalkan --}}
                                                                    <form method="POST" action="{{ route('admin.orders.updateStatus', ['order' => $o->id]) }}" style="margin: 0;" onsubmit="return confirm('Yakin ingin membatalkan pesanan {{ $o->code }}?');">
                                                                        @csrf
                                                                        <input type="hidden" name="status" value="Dibatalkan">
                                                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors hover:bg-red-50 text-red-600">
                                                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                                            <span class="text-xs font-medium">Batalkan</span>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- Completed/Cancelled Status Badge --}}
                                                    <span class="inline-flex items-center gap-1.5 h-8 px-3 text-xs font-semibold rounded-lg
                                                        {{ $o->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}
                                                    ">
                                                        @if ($o->status === 'Selesai')
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" /></svg>
                                                        @else
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                        @endif
                                                        {{ $o->status }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <tr x-show="openId === {{ $o->id }}" x-cloak>
                                        <td colspan="6" style="padding: 0; border: none;">
                                            <div style="margin: 16px; padding: 24px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                                                {{-- Header --}}
                                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
                                                    <h4 style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0;">Detail Pesanan {{ $o->code }}</h4>
                                                    <button type="button" @click="openId = null" style="padding: 8px; border-radius: 8px; background: transparent; border: none; cursor: pointer; color: #94a3b8;">
                                                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                {{-- Content Grid --}}
                                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                                                    
                                                    {{-- Column 1: Item Pesanan --}}
                                                    <div style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px;">
                                                        <p style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 16px 0;">Item Pesanan</p>
                                                        <div style="display: flex; flex-direction: column; gap: 12px;">
                                                            @foreach ($o->items as $it)
                                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 12px; background: #f8fafc; border-radius: 8px;">
                                                                    <div style="flex: 1; min-width: 0;">
                                                                        <p style="font-size: 14px; font-weight: 500; color: #1e293b; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $it->product_title }}</p>
                                                                        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">{{ $formatRupiah((int) $it->price) }} × {{ $it->qty }}</p>
                                                                    </div>
                                                                    <p style="font-size: 14px; font-weight: 600; color: #1e293b; margin: 0; margin-left: 12px; white-space: nowrap;">{{ $formatRupiah((int) $it->subtotal) }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    {{-- Column 2: Alamat Pengiriman --}}
                                                    <div style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px;">
                                                        <p style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 16px 0;">Alamat Pengiriman</p>
                                                        <div>
                                                            <p style="font-size: 15px; font-weight: 600; color: #1e293b; margin: 0;">{{ $o->recipient_name ?? '-' }}</p>
                                                            <p style="font-size: 14px; color: #475569; margin: 6px 0 0 0;">{{ $o->recipient_phone ?? '-' }}</p>
                                                        </div>
                                                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                                                            <p style="font-size: 14px; color: #475569; margin: 0; line-height: 1.5;">{{ $o->shipping_address ?? '-' }}</p>
                                                            <p style="font-size: 14px; color: #475569; margin: 4px 0 0 0;">{{ $o->shipping_city ?? '-' }} {{ $o->shipping_postal_code ?? '' }}</p>
                                                        </div>
                                                    </div>

                                                    {{-- Column 3: Ringkasan & Status --}}
                                                    <div style="display: flex; flex-direction: column; gap: 16px;">
                                                        {{-- Ringkasan Pembayaran --}}
                                                        <div style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px;">
                                                            <p style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 16px 0;">Ringkasan</p>
                                                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                                                <span style="font-size: 14px; color: #64748b;">Subtotal</span>
                                                                <span style="font-size: 14px; font-weight: 500; color: #334155;">{{ $formatRupiah((int) $o->subtotal) }}</span>
                                                            </div>
                                                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                                                <span style="font-size: 14px; color: #64748b;">Ongkir</span>
                                                                <span style="font-size: 14px; font-weight: 500; color: #334155;">{{ $o->shipping_cost > 0 ? $formatRupiah((int) $o->shipping_cost) : 'Gratis' }}</span>
                                                            </div>
                                                            <div style="display: flex; justify-content: space-between; padding-top: 12px; margin-top: 12px; border-top: 1px solid #e2e8f0;">
                                                                <span style="font-size: 15px; font-weight: 600; color: #1e293b;">Grand Total</span>
                                                                <span style="font-size: 15px; font-weight: 700; color: #1e293b;">{{ $formatRupiah((int) $o->grand_total) }}</span>
                                                            </div>
                                                        </div>

                                                        {{-- Status --}}
                                                        <div style="background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px;">
                                                            <p style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 10px 0;">Status Pesanan</p>
                                                            @php
                                                                $statusStyles = [
                                                                    'Menunggu Pembayaran' => 'background: #fef3c7; color: #b45309;',
                                                                    'Dikemas' => 'background: #dbeafe; color: #1d4ed8;',
                                                                    'Dikirim' => 'background: #e0e7ff; color: #4338ca;',
                                                                    'Selesai' => 'background: #d1fae5; color: #047857;',
                                                                    'Dibatalkan' => 'background: #fee2e2; color: #dc2626;',
                                                                ];
                                                            @endphp
                                                            <span style="display: inline-block; padding: 6px 14px; font-size: 13px; font-weight: 600; border-radius: 20px; {{ $statusStyles[$o->status] ?? 'background: #f1f5f9; color: #475569;' }}">
                                                                {{ $o->status }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="!py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                </div>
                                                <p class="font-medium text-gray-900">Tidak ada pesanan</p>
                                                <p class="text-sm text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @if (method_exists($orders, 'links'))
                <div class="p-4 border-t border-gray-100">
                    {{ $orders->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

    </div>
</section>
<script>
    // Chart Data from PHP
    const chartData = {
        daily: {
            labels: @json($chart_data['daily']['labels'] ?? []),
            revenue: @json($chart_data['daily']['revenue'] ?? []),
            orders: @json($chart_data['daily']['orders'] ?? [])
        },
        weekly: {
            labels: @json($chart_data['weekly']['labels'] ?? []),
            revenue: @json($chart_data['weekly']['revenue'] ?? []),
            orders: @json($chart_data['weekly']['orders'] ?? [])
        },
        monthly: {
            labels: @json($chart_data['monthly']['labels'] ?? []),
            revenue: @json($chart_data['monthly']['revenue'] ?? []),
            orders: @json($chart_data['monthly']['orders'] ?? [])
        }
    };

    let salesChart = null;
    let currentChartType = 'daily';

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function createChart(type) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const data = chartData[type];

        if (salesChart) {
            salesChart.destroy();
        }

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Revenue (Rp)',
                        data: data.revenue,
                        borderColor: '#2D5A3D',
                        backgroundColor: 'rgba(45, 90, 61, 0.1)',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Jumlah Pesanan',
                        data: data.orders,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: false,
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return 'Revenue: ' + formatRupiah(context.raw);
                                }
                                return 'Pesanan: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'jt';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'rb';
                                }
                                return value;
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Jumlah Pesanan'
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });
    }

    function showChart(type) {
        currentChartType = type;
        createChart(type);

        // Update button styles
        document.getElementById('btn-daily').className = type === 'daily' ? 'admin-btn admin-btn-primary' : 'admin-btn admin-btn-secondary';
        document.getElementById('btn-weekly').className = type === 'weekly' ? 'admin-btn admin-btn-primary' : 'admin-btn admin-btn-secondary';
        document.getElementById('btn-monthly').className = type === 'monthly' ? 'admin-btn admin-btn-primary' : 'admin-btn admin-btn-secondary';
    }

    // Initialize chart on page load
    document.addEventListener('DOMContentLoaded', function() {
        showChart('daily');
    });
</script>
@endsection

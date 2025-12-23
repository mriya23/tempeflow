@extends('layouts.storefront')

@section('content')
<style>
    /* Minimal Modern Admin UI (clean, low-flair, consistent) */
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
        transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        user-select: none;
        white-space: nowrap;
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

    .admin-btn-ghost {
        background: transparent;
        color: var(--color-text-secondary);
        border: 1px solid transparent;
    }
    .admin-btn-ghost:hover {
        background: var(--color-background);
        color: var(--color-text-primary);
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
        height: 40px;
        border: 1px solid var(--color-border);
        border-radius: 10px;
        padding: 0 12px;
        font-size: 13px;
        background: #fff;
        color: var(--color-text-primary);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        width: 100%;
    }

    .admin-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.10);
    }

    .admin-select {
        height: 40px;
        border: 1px solid var(--color-border);
        border-radius: 10px;
        padding: 0 12px;
        font-size: 13px;
        background: #fff;
        color: var(--color-text-primary);
        width: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
    }

    .admin-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.10);
    }

    .admin-breadcrumb {
        font-size: 12px;
        color: var(--color-text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .admin-breadcrumb a {
        color: var(--color-text-secondary);
        text-decoration: none;
    }
    .admin-breadcrumb a:hover {
        color: var(--color-text-primary);
        text-decoration: underline;
    }

    .admin-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--color-text-primary);
        letter-spacing: -0.01em;
    }

    .admin-subtitle {
        margin-top: 6px;
        font-size: 13px;
        color: var(--color-text-secondary);
    }

    .toolbar {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    @media (min-width: 640px) {
        .toolbar {
            grid-template-columns: 1fr auto auto;
            align-items: center;
        }
    }

    .search-wrap {
        position: relative;
        min-width: 0;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: #9CA3AF;
        pointer-events: none;
    }

    .search-input {
        padding-left: 40px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }

    .admin-table thead th {
        position: sticky;
        top: 0;
        background: #FAFBFC;
        color: var(--color-text-secondary);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 800;
        text-align: left;
        padding: 12px 18px;
        border-bottom: 1px solid var(--color-border);
        white-space: nowrap;
    }

    .admin-table tbody td {
        padding: 14px 18px;
        border-bottom: 1px solid var(--color-border);
        vertical-align: middle;
        color: var(--color-text-primary);
    }

    .admin-table tbody tr:hover {
        background: #FAFBFC;
    }

    .thumb {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        background: #F3F4F6;
        overflow: hidden;
        display: grid;
        place-items: center;
    }

    .badge {
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

    .badge-active {
        background: #ECFDF5;
        color: #047857;
        border-color: #A7F3D0;
    }

    .badge-inactive {
        background: #F3F4F6;
        color: #374151;
        border-color: #E5E7EB;
    }

    .cell-title {
        font-weight: 700;
        color: var(--color-text-primary);
        line-height: 1.2;
    }

    .cell-meta {
        margin-top: 4px;
        font-size: 12px;
        color: var(--color-text-secondary);
    }

    .cell-meta .muted { color: var(--color-text-muted); }

    .empty {
        text-align: center;
        padding: 56px 18px;
        color: var(--color-text-secondary);
    }

    /* Mobile: convert rows to cards for readability */
    @media (max-width: 768px) {
        .admin-table thead { display: none; }
        .admin-table, .admin-table tbody, .admin-table tr, .admin-table td { display: block; width: 100%; }

        .admin-table tr {
            border: 1px solid var(--color-border);
            border-radius: 14px;
            background: #fff;
            box-shadow: var(--shadow-sm);
            margin: 0 0 12px 0;
            overflow: hidden;
        }

        .admin-table tbody td {
            border-bottom: 1px solid #F1F5F9;
            padding: 12px 14px;
        }

        .admin-table tbody td:last-child { border-bottom: none; }

        .row-top {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .row-actions {
            display: flex;
            justify-content: flex-end;
        }
    }
</style>

<section class="admin-page max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-10">
    <!-- Header (Navigation separated) -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="min-w-0">

            <h1 class="admin-title mt-2">Manajemen Produk</h1>
            <p class="admin-subtitle">Kelola katalog produk tokomu</p>
        </div>

        <div class="flex-shrink-0">
            <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk
            </a>
        </div>


    </div>

    @if (session('success'))
        <div class="mb-4 admin-card" style="padding: 12px 14px; border-color: #BBF7D0; background: #F0FDF4; color: #166534;">
            <div class="flex items-start gap-10 justify-between">
                <div class="flex items-start gap-10">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm font-semibold">{{ session('success') }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="admin-card">
        <!-- Toolbar: 1) Search bar 2) Filter status 3) Actions (no navigation) -->
        <div class="p-4 sm:p-6 border-b" style="border-color: var(--color-border);">
            <form method="GET" action="{{ route('admin.products.index') }}" class="toolbar">
                <!-- 1) Search bar -->
                <div class="search-wrap">
                    <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        name="q"
                        value="{{ request()->input('q') }}"
                        placeholder="Cari kode, nama, atau email..."
                        class="admin-input search-input"
                        autocomplete="off"
                        inputmode="search"
                        aria-label="Cari produk"
                    />
                </div>

                <!-- 2) Filter status -->
                <div>
                    <select name="status" class="admin-select" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit" class="admin-btn admin-btn-secondary w-full sm:w-auto">Cari</button>
                    @if(request()->filled('q') || request()->filled('status'))
                        <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-ghost w-full sm:w-auto">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Gambar</th>
                        <th>Info Produk</th>
                        <th style="width: 170px;">Harga</th>
                        <th style="width: 140px;">Status</th>
                        <th style="width: 140px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="thumb">
                                    @if($product->img_path)
                                        <img src="{{ asset($product->img_path) }}" class="w-full h-full object-cover" alt="Gambar produk">
                                    @else
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="row-top">
                                    <div class="min-w-0">
                                        <div class="cell-title">
                                            <span class="truncate block">{{ $product->title }}</span>
                                        </div>
                                        <div class="cell-meta">
                                            <span class="muted">{{ $product->tag ? Str::upper($product->tag) : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div style="font-weight: 800; color: var(--color-text-primary);">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </td>

                            <td>
                                @if($product->is_active)
                                    <span class="badge badge-active">Aktif</span>
                                @else
                                    <span class="badge badge-inactive">Non-Aktif</span>
                                @endif
                            </td>

                            <td style="text-align: center;">
                                <div class="row-actions flex justify-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-btn admin-btn-secondary" style="height: 36px; padding: 0 12px;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger" style="height: 36px; padding: 0 12px;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty">
                                    <div class="font-semibold" style="color: var(--color-text-primary);">Belum ada produk</div>
                                    <div class="mt-1 text-sm">Tambah produk pertama untuk mulai mengelola katalog.</div>

                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="p-4 border-t" style="border-color: var(--color-border);">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

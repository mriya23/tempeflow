<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Penjualan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .container {
            padding: 25px;
        }
        .header {
            border-bottom: 3px solid #2D5A3D;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2D5A3D;
        }
        .report-title {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            text-align: right;
            margin-top: -40px;
        }
        .report-period {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2D5A3D;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #E5E7EB;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-box {
            display: table-cell;
            width: 20%;
            padding: 12px;
            text-align: center;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
        }
        .stat-box:first-child {
            border-radius: 8px 0 0 8px;
        }
        .stat-box:last-child {
            border-radius: 0 8px 8px 0;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #2D5A3D;
        }
        .stat-value.blue { color: #2563EB; }
        .stat-value.green { color: #059669; }
        .stat-value.red { color: #DC2626; }
        .stat-value.orange { color: #D97706; }
        .stat-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        .revenue-box {
            background: #2D5A3D;
            padding: 15px 20px;
            border-radius: 8px;
            color: #fff;
            margin-bottom: 20px;
        }
        .revenue-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        .revenue-value {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background: #2D5A3D;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table th:last-child {
            text-align: right;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 10px;
        }
        table td:last-child {
            text-align: right;
        }
        table tr:nth-child(even) {
            background: #F9FAFB;
        }
        .two-column {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }
        .column:first-child {
            padding-right: 15px;
        }
        .column:last-child {
            padding-left: 15px;
        }
        .rank-badge {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 50%;
            font-size: 9px;
            font-weight: bold;
            margin-right: 8px;
        }
        .rank-1 { background: #FEF3C7; color: #D97706; }
        .rank-2 { background: #E5E7EB; color: #4B5563; }
        .rank-3 { background: #FFEDD5; color: #C2410C; }
        .rank-default { background: #F3F4F6; color: #6B7280; }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-pending { background: #F3E8FF; color: #7C3AED; }
        .status-packed { background: #FEF3C7; color: #D97706; }
        .status-shipped { background: #DBEAFE; color: #2563EB; }
        .status-done { background: #D1FAE5; color: #059669; }
        .status-canceled { background: #FEE2E2; color: #DC2626; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            font-size: 9px;
            color: #666;
            text-align: center;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    @php
        $formatRupiah = fn (int $amount) => 'Rp '.number_format($amount, 0, ',', '.');
    @endphp

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company['name'] ?? 'Tempe Jaya Mandiri' }}</div>
            <div class="report-title">LAPORAN PENJUALAN</div>
            <div class="report-period">
                Periode: {{ $period['from'] ?? '' }} - {{ $period['to'] ?? '' }}
            </div>
        </div>

        <!-- Revenue Highlight -->
        <div class="revenue-box">
            <div class="revenue-label">Total Pendapatan (Pesanan Selesai)</div>
            <div class="revenue-value">{{ $formatRupiah($stats['total_revenue'] ?? 0) }}</div>
        </div>

        <!-- Stats Overview -->
        <div class="section">
            <div class="section-title">Ringkasan Pesanan</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value blue">{{ $stats['total_orders'] ?? 0 }}</div>
                    <div class="stat-label">Total Pesanan</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value green">{{ $stats['completed_orders'] ?? 0 }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value orange">{{ $stats['pending_orders'] ?? 0 }}</div>
                    <div class="stat-label">Menunggu Bayar</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value red">{{ $stats['cancelled_orders'] ?? 0 }}</div>
                    <div class="stat-label">Dibatalkan</div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="two-column">
            <!-- Top Products -->
            <div class="column">
                <div class="section">
                    <div class="section-title">🏆 Produk Terlaris</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 30px;">#</th>
                                <th>Produk</th>
                                <th style="width: 50px;">Qty</th>
                                <th style="width: 90px;">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($top_products ?? [] as $index => $tp)
                                <tr>
                                    <td>
                                        <span class="rank-badge {{ $index === 0 ? 'rank-1' : ($index === 1 ? 'rank-2' : ($index === 2 ? 'rank-3' : 'rank-default')) }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($tp->product_title, 25) }}</td>
                                    <td>{{ number_format($tp->total_sold) }}</td>
                                    <td>{{ $formatRupiah((int) $tp->total_revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #999;">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Daily Breakdown -->
            <div class="column">
                <div class="section">
                    <div class="section-title">📊 Penjualan Harian</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th style="width: 50px;">Pesanan</th>
                                <th style="width: 100px;">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daily_data ?? [] as $dd)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($dd->date)->format('d M Y') }}</td>
                                    <td>{{ $dd->orders_count }}</td>
                                    <td>{{ $formatRupiah((int) $dd->revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #999;">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        @if (($orders ?? collect())->count() > 0)
            <div class="section page-break">
                <div class="section-title">📦 Daftar Pesanan</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 100px;">Kode</th>
                            <th style="width: 80px;">Tanggal</th>
                            <th>Pelanggan</th>
                            <th style="width: 50px;">Item</th>
                            <th style="width: 70px;">Status</th>
                            <th style="width: 100px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $o)
                            @php
                                $statusClass = match($o->status) {
                                    'Menunggu Pembayaran' => 'status-pending',
                                    'Dikemas' => 'status-packed',
                                    'Dikirim' => 'status-shipped',
                                    'Selesai' => 'status-done',
                                    'Dibatalkan' => 'status-canceled',
                                    default => 'status-pending'
                                };
                                $itemsCount = $o->items->sum('qty');
                            @endphp
                            <tr>
                                <td><strong>{{ $o->code }}</strong></td>
                                <td>{{ $o->created_at?->format('d/m/Y') }}</td>
                                <td>{{ Str::limit($o->user?->name ?? '-', 20) }}</td>
                                <td>{{ $itemsCount }} pcs</td>
                                <td><span class="status-badge {{ $statusClass }}">{{ Str::limit($o->status, 10) }}</span></td>
                                <td>{{ $formatRupiah((int) $o->grand_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini dibuat secara otomatis oleh sistem {{ $company['name'] ?? 'Tempe Jaya Mandiri' }}</p>
            <p>Dicetak pada: {{ $generated_at ?? now()->format('d M Y H:i') }}</p>
        </div>
    </div>
</body>
</html>

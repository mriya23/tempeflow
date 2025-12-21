<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $order->code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .container {
            padding: 30px;
        }
        .header {
            border-bottom: 3px solid #2D5A3D;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2D5A3D;
        }
        .company-tagline {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }
        .company-info {
            font-size: 10px;
            color: #666;
            margin-top: 8px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2D5A3D;
            text-align: right;
            margin-top: -60px;
        }
        .invoice-details {
            text-align: right;
            margin-top: 10px;
            font-size: 11px;
        }
        .invoice-details strong {
            color: #333;
        }
        .section {
            margin-bottom: 25px;
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
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 12px;
            color: #333;
            margin-top: 2px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.items th {
            background: #2D5A3D;
            color: #fff;
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.items th:last-child {
            text-align: right;
        }
        table.items td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 11px;
        }
        table.items td:last-child {
            text-align: right;
        }
        table.items tr:nth-child(even) {
            background: #F9FAFB;
        }
        .totals {
            margin-top: 20px;
            margin-left: auto;
            width: 280px;
        }
        .totals-row {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .totals-row:last-child {
            border-bottom: none;
        }
        .totals-label {
            display: table-cell;
            text-align: left;
            color: #666;
        }
        .totals-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }
        .grand-total {
            background: #2D5A3D;
            color: #fff;
            padding: 12px;
            margin-top: 10px;
        }
        .grand-total .totals-label,
        .grand-total .totals-value {
            color: #fff;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #F3E8FF; color: #7C3AED; }
        .status-packed { background: #FEF3C7; color: #D97706; }
        .status-shipped { background: #DBEAFE; color: #2563EB; }
        .status-done { background: #D1FAE5; color: #059669; }
        .status-canceled { background: #FEE2E2; color: #DC2626; }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
        }
        .footer-thanks {
            font-size: 14px;
            color: #2D5A3D;
            font-weight: bold;
        }
        .footer-note {
            font-size: 10px;
            color: #666;
            margin-top: 8px;
        }
        .watermark {
            position: fixed;
            bottom: 30px;
            right: 30px;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company['name'] ?? 'Tempe Jaya Mandiri' }}</div>
            <div class="company-tagline">Platform e-commerce tempe berkualitas</div>
            <div class="company-info">
                {{ $company['address'] ?? '' }}<br>
                Telp: {{ $company['phone'] ?? '' }} | Email: {{ $company['email'] ?? '' }}
            </div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-details">
                <strong>No. Invoice:</strong> {{ $order->code }}<br>
                <strong>Tanggal:</strong> {{ $order->created_at?->format('d M Y') }}<br>
                <strong>Status:</strong>
                @php
                    $statusClass = match($order->status) {
                        'Menunggu Pembayaran' => 'status-pending',
                        'Dikemas' => 'status-packed',
                        'Dikirim' => 'status-shipped',
                        'Selesai' => 'status-done',
                        'Dibatalkan' => 'status-canceled',
                        default => 'status-pending'
                    };
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $order->status }}</span>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="section">
            <div class="section-title">Informasi Pelanggan</div>
            <div class="info-grid">
                <div class="info-col">
                    <div class="info-label">Nama Pelanggan</div>
                    <div class="info-value">{{ $order->user?->name ?? '-' }}</div>
                    <div style="margin-top: 10px;">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $order->user?->email ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-col">
                    <div class="info-label">Alamat Pengiriman</div>
                    <div class="info-value">
                        {{ $order->recipient_name ?? '-' }}<br>
                        {{ $order->recipient_phone ?? '-' }}<br>
                        {{ $order->shipping_address ?? '-' }}<br>
                        {{ $order->shipping_city ?? '' }} {{ $order->shipping_postal_code ?? '' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="section">
            <div class="section-title">Detail Pesanan</div>
            <table class="items">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Produk</th>
                        <th style="width: 60px;">Qty</th>
                        <th style="width: 120px;">Harga</th>
                        <th style="width: 120px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product_title }}</strong>
                                @if ($item->product_tag)
                                    <br><span style="color: #666; font-size: 10px;">{{ $item->product_tag }}</span>
                                @endif
                            </td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span class="totals-label">Subtotal</span>
                <span class="totals-value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="totals-row">
                <span class="totals-label">Ongkos Kirim</span>
                <span class="totals-value" style="color: #059669;">GRATIS</span>
            </div>
            <div class="totals-row grand-total">
                <span class="totals-label">TOTAL</span>
                <span class="totals-value">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-thanks">Terima kasih atas pesanan Anda! 🙏</div>
            <div class="footer-note">
                Jika ada pertanyaan, silakan hubungi kami di {{ $company['phone'] ?? '085712149529' }}<br>
                atau email ke {{ $company['email'] ?? 'info@tempejayamandiri.id' }}
            </div>
        </div>

        <div class="watermark">
            Dicetak pada: {{ now()->format('d M Y H:i') }}
        </div>
    </div>
</body>
</html>

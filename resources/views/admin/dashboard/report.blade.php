<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $settings['store_name'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; padding: 30px; max-width: 900px; margin: 0 auto; font-size: 13px; color: #333; }
        .header { text-align: center; border-bottom: 3px solid #1e3932; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { font-size: 24px; color: #1e3932; }
        .header p { color: #666; margin-top: 4px; }
        .period { background: #f0f0f0; padding: 10px 16px; border-radius: 8px; text-align: center; margin-bottom: 20px; font-weight: 600; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
        .stat-box { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 14px; text-align: center; }
        .stat-box .value { font-size: 20px; font-weight: 700; color: #1e3932; }
        .stat-box .label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .section-title { font-size: 15px; font-weight: 700; color: #1e3932; margin: 20px 0 10px; padding-bottom: 6px; border-bottom: 2px solid #e9ecef; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #1e3932; color: white; padding: 8px 10px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 12px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .total-row { font-weight: 700; background: #e8f5e9 !important; }
        .footer { text-align: center; margin-top: 30px; padding-top: 16px; border-top: 2px solid #e9ecef; color: #888; font-size: 11px; }
        .btn-print { display: block; margin: 20px auto; padding: 10px 30px; background: #1e3932; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-print:hover { background: #15302a; }
        @media print { .btn-print { display: none; } body { padding: 15px; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>☕ {{ $settings['store_name'] }}</h1>
        <p>{{ $settings['store_address'] }}</p>
        <p>Telp: {{ $settings['store_phone'] }}</p>
    </div>

    <div class="period">
        📊 Laporan Penjualan: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="value">{{ $stats['total_orders'] }}</div>
            <div class="label">Total Pesanan</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="label">Total Pendapatan</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $stats['total_items_sold'] }}</div>
            <div class="label">Item Terjual</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $stats['cash_payments'] }}</div>
            <div class="label">Tunai</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $stats['qris_payments'] }}</div>
            <div class="label">QRIS</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $stats['dine_in'] }} / {{ $stats['takeaway'] }}</div>
            <div class="label">Dine In / Take Away</div>
        </div>
    </div>

    @if($dailyRevenue->count())
    <div class="section-title">📅 Rekap Per Hari</div>
    <table>
        <thead>
            <tr><th>Tanggal</th><th style="text-align:right">Pesanan</th><th style="text-align:right">Item</th><th style="text-align:right">Pendapatan</th></tr>
        </thead>
        <tbody>
            @foreach($dailyRevenue as $day)
            <tr>
                <td>{{ $day['date'] }}</td>
                <td style="text-align:right">{{ $day['orders'] }}</td>
                <td style="text-align:right">{{ $day['items'] }}</td>
                <td style="text-align:right;font-weight:600;">Rp {{ number_format($day['revenue'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL</td>
                <td style="text-align:right">{{ $stats['total_orders'] }}</td>
                <td style="text-align:right">{{ $stats['total_items_sold'] }}</td>
                <td style="text-align:right">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    @if($productStats->count())
    <div class="section-title">🏆 Produk Terlaris</div>
    <table>
        <thead>
            <tr><th>Produk</th><th style="text-align:right">Qty Terjual</th><th style="text-align:right">Pendapatan</th></tr>
        </thead>
        <tbody>
            @foreach($productStats as $p)
            <tr>
                <td>{{ $p['name'] }}</td>
                <td style="text-align:right">{{ $p['qty'] }}</td>
                <td style="text-align:right;font-weight:600;">Rp {{ number_format($p['revenue'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($orders->count())
    <div class="section-title">📋 Detail Pesanan</div>
    <table>
        <thead>
            <tr><th>No.</th><th>Kasir</th><th>Tipe</th><th>Pembayaran</th><th style="text-align:right">Total</th><th>Waktu</th></tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->orderNumber() }}</td>
                <td>{{ $order->user?->name ?? '-' }}</td>
                <td>{{ $order->order_type === 'dine_in' ? '🍽️ Dine In' : '📦 Take Away' }}</td>
                <td>{{ strtoupper($order->payment_method) }}</td>
                <td style="text-align:right;font-weight:600;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }} WIB</p>
        <p>© {{ date('Y') }} {{ $settings['store_name'] }} — Laporan Penjualan</p>
    </div>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak Laporan</button>
</body>
</html>

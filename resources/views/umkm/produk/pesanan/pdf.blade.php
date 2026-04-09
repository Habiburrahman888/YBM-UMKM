<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan - {{ $umkm->nama_usaha }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #111111;
            background: #ffffff;
        }

        .header {
            background: #111111;
            color: #ffffff;
            padding: 22px 28px 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #cccccc;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
        }

        .header-left h1 {
            font-size: 17px;
            font-weight: 800;
        }

        .header-left p {
            font-size: 10px;
            color: #aaaaaa;
        }

        .header-right .date {
            font-size: 13px;
            font-weight: 700;
        }

        .header-right .time {
            font-size: 10px;
            color: #aaaaaa;
        }

        .filter-box {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            background: #f7f7f7;
            border-left: 4px solid #111111;
            padding: 10px;
            margin-bottom: 16px;
        }

        .filter-item {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .stats {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }

        .stat-card {
            flex: 1;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .stat-card .label {
            font-size: 8px;
        }

        .stat-card .value {
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #111;
            color: #fff;
        }

        th,
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .currency {
            font-weight: bold;
        }

        .badge {
            padding: 2px 6px;
            font-size: 8px;
            border: 1px solid #000;
        }

        .total-row td {
            background: #111;
            color: #fff;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-flex">
            <div class="header-left">
                <h1>Laporan Pesanan Masuk</h1>
                <p>{{ $umkm->nama_usaha }} - {{ $umkm->nama_pemilik }}</p>
            </div>
            <div class="header-right">
                <div class="date">{{ now()->format('d M Y') }}</div>
                <div class="time">{{ now()->format('H:i') }} WIB</div>
            </div>
        </div>
    </div>

    <div class="filter-box">
        <span class="filter-item">Status: <strong>{{ $filters['status'] ?? 'SEMUA' }}</strong></span>
        <span class="filter-item">Total: <strong>{{ $pesanans->count() }}</strong></span>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="label">Total</div>
            <div class="value">{{ $pesanans->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Selesai</div>
            <div class="value">{{ $pesanans->where('status', 'selesai')->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pembeli</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesanans as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->nama_pembeli }}</td>
                    <td class="currency">Rp {{ number_format($p->total_harga) }}</td>
                    <td>{{ $p->status }}</td>
                </tr>
            @endforeach
        </tbody>

        <tr class="total-row">
            <td colspan="2">TOTAL</td>
            <td>Rp {{ number_format($totalPendapatan) }}</td>
            <td></td>
        </tr>
    </table>

    <div class="footer">
        Generated otomatis sistem UMKM
    </div>

</body>

</html>

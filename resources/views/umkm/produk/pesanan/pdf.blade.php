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
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            padding: 20px 28px;
            border-radius: 0 0 12px 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.85;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-right {
            text-align: right;
            font-size: 10px;
        }

        .header-right .date {
            font-size: 12px;
            font-weight: 700;
        }

        .filter-box {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 16px;
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .filter-box span {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-box strong {
            color: #1d4ed8;
            font-weight: 800;
        }

        .stats {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }

        .stat-card {
            flex: 1;
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
        }

        .stat-card.blue {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .stat-card.green {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .stat-card.amber {
            background: #fffbeb;
            border-color: #fde68a;
        }

        .stat-card.red {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .stat-card .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .stat-card .value {
            font-size: 20px;
            font-weight: 900;
        }

        .stat-card.blue .value {
            color: #1d4ed8;
        }

        .stat-card.green .value {
            color: #16a34a;
        }

        .stat-card.amber .value {
            color: #d97706;
        }

        .stat-card.red .value {
            color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #1e293b;
            color: white;
        }

        thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #eff6ff;
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-diproses {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-selesai {
            background: #dcfce7;
            color: #166534;
        }

        .badge-dikirim {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-batal {
            background: #fee2e2;
            color: #991b1b;
        }

        .total-row td {
            font-weight: 900;
            background: #1e293b;
            color: white;
            padding: 10px 12px;
            border-radius: 0 0 8px 8px;
        }

        .currency {
            font-weight: 800;
            color: #16a34a;
        }

        .no {
            color: #64748b;
            font-size: 10px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-flex">
            <div>
                <h1>Laporan Pesanan Masuk</h1>
                <p>{{ $umkm->nama_usaha }} &mdash; {{ $umkm->nama_pemilik }}</p>
            </div>
            <div class="header-right">
                <div class="date">{{ now()->format('d M Y') }}</div>
                <div>Dicetak: {{ now()->format('H:i') }} WIB</div>
            </div>
        </div>
    </div>

    <!-- Filter Info -->
    <div class="filter-box">
        <span>Filter:&nbsp;</span>
        <span>Status:
            <strong>{{ isset($filters['status']) && $filters['status'] ? strtoupper($filters['status']) : 'SEMUA' }}</strong></span>
        <span>Dari:
            <strong>{{ isset($filters['dari']) && $filters['dari'] ? \Carbon\Carbon::parse($filters['dari'])->format('d M Y') : 'Awal' }}</strong></span>
        <span>Sampai:
            <strong>{{ isset($filters['sampai']) && $filters['sampai'] ? \Carbon\Carbon::parse($filters['sampai'])->format('d M Y') : 'Sekarang' }}</strong></span>
        <span>Total Data: <strong>{{ $pesanans->count() }} pesanan</strong></span>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card blue">
            <div class="label">Total Pesanan</div>
            <div class="value">{{ $pesanans->count() }}</div>
        </div>
        <div class="stat-card green">
            <div class="label">Selesai</div>
            <div class="value">{{ $pesanans->where('status', 'selesai')->count() }}</div>
        </div>
        <div class="stat-card amber">
            <div class="label">Belum Selesai (Pending/Proses/Kirim)</div>
            <div class="value">{{ $pesanans->whereIn('status', ['pending', 'diproses', 'dikirim'])->count() }}</div>
        </div>
        <div class="stat-card green" style="background:#f0fdf4;">
            <div class="label">Total Pendapatan (Selesai)</div>
            <div class="value" style="font-size:14px;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>No. WA</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $i => $p)
                <tr>
                    <td class="no">{{ $i + 1 }}</td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                    <td><strong>{{ $p->nama_pembeli }}</strong></td>
                    <td>{{ $p->telepon_pembeli }}</td>
                    <td>
                        @if ($p->items->count() > 0)
                            @foreach ($p->items as $item)
                                • {{ $item->produk->nama_produk }} (x{{ $item->jumlah }})<br>
                            @endforeach
                        @else
                            {{ $p->produk->nama_produk ?? '-' }}
                        @endif
                    </td>
                    <td style="text-align:center;font-weight:700;">
                        @if ($p->items->count() > 0)
                            {{ $p->items->sum('jumlah') }}
                        @else
                            {{ $p->jumlah }}
                        @endif
                    </td>
                    <td class="currency">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($p->status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($p->status === 'diproses')
                            <span class="badge badge-diproses">Diproses</span>
                        @elseif($p->status === 'dikirim')
                            <span class="badge badge-dikirim">Dikirim</span>
                        @elseif($p->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-batal">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:20px; color:#94a3b8;">Tidak ada data pesanan
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($pesanans->count() > 0)
            <tr class="total-row">
                <td colspan="6" style="text-align:right;">TOTAL PENDAPATAN (SELESAI)</td>
                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        @endif
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh Sistem UMKM &mdash; {{ now()->format('d M Y H:i') }} WIB
    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        /* Konfigurasi Halaman Cetak */
        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        /* Header Usaha */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #3182ce;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .brand h1 {
            font-size: 18pt;
            margin: 0;
            color: #2c5282;
            text-transform: uppercase;
        }

        .brand p {
            margin: 5px 0 0 0;
            color: #718096;
            font-size: 9pt;
        }

        .meta {
            text-align: right;
        }

        .meta .title {
            font-weight: bold;
            color: #3182ce;
            font-size: 11pt;
            margin-bottom: 5px;
        }

        /* Chips / Filter Info */
        .info-bar {
            background: #f7fafc;
            border: 1px solid #edf2f7;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 9pt;
        }

        .info-bar span {
            margin-right: 15px;
        }

        /* Ringkasan Stats */
        .stats-grid {
            width: 100%;
            margin-bottom: 25px;
            border-spacing: 10px 0;
            display: table;
        }

        .stat-card {
            display: table-cell;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            width: 33.33%;
        }

        .stat-card .label {
            font-size: 8pt;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }

        .stat-card .value {
            font-size: 14pt;
            font-weight: bold;
            color: #2d3748;
        }

        /* Styling Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #3182ce;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 9pt;
            text-transform: uppercase;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Badge Status Dinamis */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-selesai {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge-proses {
            background: #feebc8;
            color: #744210;
        }

        .badge-batal {
            background: #fed7d7;
            color: #822727;
        }

        /* Row Total */
        .total-row td {
            background: #edf2f7;
            font-weight: bold;
            border-top: 2px solid #cbd5e0;
            font-size: 11pt;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="brand">
            <h1>{{ $umkm->nama_usaha }}</h1>
            <p>Pemilik: {{ $umkm->nama_pemilik }}</p>
        </div>
        <div class="meta">
            <div class="title">LAPORAN PESANAN</div>
            <div>{{ now()->format('d F Y') }}</div>
            <div style="font-size: 8pt; color: #718096;">Waktu Cetak: {{ now()->format('H:i') }} WIB</div>
        </div>
    </div>

    <div class="info-bar">
        <span>Status: <strong>{{ strtoupper($filters['status'] ?? 'SEMUA') }}</strong></span>
        @if (isset($filters['tanggal_mulai']))
            <span>Periode: <strong>{{ $filters['tanggal_mulai'] }} - {{ $filters['tanggal_akhir'] }}</strong></span>
        @endif
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="label">Total Pesanan</span>
            <span class="value">{{ $pesanans->count() }}</span>
        </div>
        <div class="stat-card">
            <span class="label">Pesanan Selesai</span>
            <span class="value">{{ $pesanans->where('status', 'selesai')->count() }}</span>
        </div>
        <div class="stat-card">
            <span class="label">Total Pendapatan</span>
            <span class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="20%">Nama Pembeli</th>
                <th width="30%">Produk</th>
                <th class="text-center" width="10%">Qty</th>
                <th class="text-center" width="15%">Status</th>
                <th class="text-right" width="20%">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanans as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $p->nama_pembeli ?? 'Pembeli Umum' }}</div>
                        <div style="font-size: 8pt; color: #718096;">{{ $p->telepon_pembeli }}</div>
                    </td>
                    <td>
                        @if ($p->items->count() > 0)
                            @foreach ($p->items as $item)
                                <div style="margin-bottom: 2px;">
                                    {{ $item->produk->nama_produk ?? 'Produk Terhapus' }}
                                </div>
                            @endforeach
                        @elseif($p->produk)
                            {{ $p->produk->nama_produk }}
                        @else
                            <span style="color: #a0aec0;">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($p->items->count() > 0)
                            @foreach ($p->items as $item)
                                <div style="margin-bottom: 2px;">{{ $item->jumlah }}</div>
                            @endforeach
                        @else
                            {{ $p->jumlah ?? 0 }}
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ strtolower($p->status) }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td class="text-right">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; color: #a0aec0;">
                        Data pesanan tidak ditemukan untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($pesanans->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-right">TOTAL KESELURUHAN</td>
                    <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Laporan ini digenerate secara otomatis oleh Sistem Manajemen UMKM - {{ date('Y') }}
    </div>

</body>

</html>

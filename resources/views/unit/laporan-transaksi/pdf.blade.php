<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - {{ $unit->nama_unit }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8pt;
            line-height: 1.4;
            color: #334155;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            padding: 0.4in 0.5in;
        }

        /* ── Header ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 0.5px solid #e2e8f0;
        }

        .header-left {
            display: table-cell;
            vertical-align: bottom;
        }

        .header-right {
            display: table-cell;
            vertical-align: bottom;
            text-align: right;
            color: #94a3b8;
            font-size: 8pt;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: 300;
            color: #0f172a;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 7.5pt;
            color: #64748b;
        }

        /* ── Filter Box ── */
        .filter-box {
            background: #f8fafc;
            border: 0.5px solid #e2e8f0;
            border-radius: 6px;
            padding: 7px 12px;
            margin-bottom: 16px;
            font-size: 7.5pt;
            color: #64748b;
        }

        .filter-box span {
            margin-right: 16px;
        }

        .filter-box strong {
            color: #0f172a;
            font-weight: 700;
        }

        /* ── Summary Stat ── */
        .summary-stat {
            display: table;
            width: 100%;
            background: #f8fafc;
            border: 0.5px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .stat-item {
            display: table-cell;
            width: 50%;
            text-align: center;
            border-right: 0.5px solid #e2e8f0;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-label {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 11pt;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-value.pendapatan {
            color: #0f172a;
            font-size: 9pt;
        }

        /* ── Table ── */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.main-table th {
            background: #f8fafc;
            text-align: left;
            font-size: 7.5pt;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e1;
        }

        table.main-table td {
            padding: 6px 10px;
            border-bottom: 0.5px solid #f1f5f9;
            font-size: 7.5pt;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background: #f1f5f9;
            font-weight: 700;
            color: #0f172a;
        }

        .currency {
            font-weight: 700;
            color: #0f172a;
        }

        .empty-row td {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-style: italic;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 0.5px solid #e2e8f0;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        {{-- ── Header ── --}}
        <div class="header">
            <div class="header-left">
                <h1>Laporan Transaksi UMKM Binaan</h1>
                <p>Unit: {{ $unit->nama_unit }} &nbsp;&middot;&nbsp; Penanggung Jawab: {{ $unit->nama_pj ?? '-' }}</p>
            </div>
            <div class="header-right">
                Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }}
            </div>
        </div>

        {{-- ── Filter Info ── --}}
        <div class="filter-box">
            <span>UMKM:
                <strong>{{ isset($filters['umkm_id']) && $filters['umkm_id'] ? $umkmList[$filters['umkm_id']]->nama_usaha ?? '-' : 'SEMUA' }}</strong></span>
            <span>Status: <strong>SELESAI</strong></span>
            <span>Dari:
                <strong>{{ isset($filters['dari']) && $filters['dari'] ? \Carbon\Carbon::parse($filters['dari'])->translatedFormat('d F Y') : 'Awal' }}</strong></span>
            <span>Sampai:
                <strong>{{ isset($filters['sampai']) && $filters['sampai'] ? \Carbon\Carbon::parse($filters['sampai'])->translatedFormat('d F Y') : 'Sekarang' }}</strong></span>
            <span>Total UMKM: <strong>{{ $pesanans->count() }}</strong></span>
        </div>

        {{-- ── Summary Stats ── --}}
        <div class="summary-stat">
            <div class="stat-item">
                <div class="stat-label">Total UMKM</div>
                <div class="stat-value">{{ $pesanans->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Pendapatan (Selesai)</div>
                <div class="stat-value pendapatan">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- ── Tabel Data ── --}}
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 25px;" class="text-center">No</th>
                    <th>Nama UMKM</th>
                    <th>Nama Pemilik</th>
                    <th style="width: 160px;" class="text-right">Total Penjualan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $i => $p)
                    <tr>
                        <td class="text-center" style="color: #94a3b8;">{{ $i + 1 }}</td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $p->nama_usaha }}</td>
                        <td>{{ $p->nama_pemilik ?? '-' }}</td>
                        <td class="text-right currency">{{ number_format($p->total_penjualan ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="4">Tidak ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ── Total ── --}}
        @if ($pesanans->count() > 0)
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <tr class="total-row">
                    <td
                        style="padding: 10px; text-align: right; border: 0.5px solid #cbd5e1; background: #f1f5f9; font-weight: bold;">
                        TOTAL PENDAPATAN (SELESAI)
                    </td>
                    <td
                        style="padding: 10px; width: 160px; text-align: right; border: 0.5px solid #cbd5e1; background: #f1f5f9; font-weight: bold;">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        @endif

        {{-- ── Footer ── --}}
        <div class="footer">
            Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi YBM PLN UMKM.
        </div>

    </div>
</body>

</html>

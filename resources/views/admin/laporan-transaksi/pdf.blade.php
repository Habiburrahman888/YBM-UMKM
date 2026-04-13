<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Lintas Unit - YBM UMKM</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8pt;
            line-height: 1.45;
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
            margin-bottom: 18px;
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
            font-size: 7.5pt;
        }

        .header h1 {
            font-size: 13pt;
            font-weight: 300;
            color: #0f172a;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }

        .header p {
            font-size: 7.5pt;
            color: #64748b;
            margin: 0;
        }

        /* ── Filter Box ── */
        .filter-box {
            background: #f8fafc;
            border: 0.5px solid #e2e8f0;
            border-radius: 5px;
            padding: 6px 12px;
            margin-bottom: 14px;
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
            border-radius: 5px;
            margin-bottom: 18px;
        }

        .stat-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 9px 6px;
            border-right: 0.5px solid #e2e8f0;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-label {
            font-size: 6.5pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 3px;
        }

        .stat-value {
            font-size: 10.5pt;
            font-weight: 700;
            color: #0f172a;
        }

        /* ── Unit Group Header ── */
        .unit-header {
            background: #1e40af;
            color: #fff;
            page-break-inside: avoid;
        }

        .unit-header td {
            padding: 7px 10px;
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .unit-sub {
            font-size: 7pt;
            font-weight: 400;
            color: #bfdbfe;
            margin-top: 1px;
        }

        /* ── Table ── */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table.main-table th {
            background: #f1f5f9;
            text-align: left;
            font-size: 7pt;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 7px 10px;
            border-bottom: 0.5px solid #cbd5e1;
        }

        table.main-table td {
            padding: 5.5px 10px;
            border-bottom: 0.5px solid #f1f5f9;
            font-size: 7.5pt;
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .currency {
            font-weight: 700;
            color: #1e3a8a;
        }

        .subtotal-row td {
            background: #eff6ff;
            border-top: 0.5px solid #bfdbfe;
            border-bottom: 1px solid #93c5fd;
            font-weight: 700;
            font-size: 7.5pt;
            padding: 6px 10px;
            color: #1e40af;
        }

        .grand-total-row td {
            background: #1e40af;
            color: #fff;
            font-weight: 700;
            font-size: 9pt;
            padding: 9px 10px;
            border: none;
        }

        .empty-row td {
            text-align: center;
            padding: 12px;
            color: #94a3b8;
            font-style: italic;
            font-size: 7.5pt;
        }

        /* ── Badge ── */
        .badge-unit {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 3px;
            padding: 1px 5px;
            font-size: 7pt;
            font-weight: 600;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 28px;
            padding-top: 12px;
            border-top: 0.5px solid #e2e8f0;
            font-size: 7pt;
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
                <h1>Laporan Transaksi Lintas Unit</h1>
                <p>Sistem Informasi YBM PLN UMKM &nbsp;&middot;&nbsp; Rekap Transaksi Selesai Per UMKM</p>
            </div>
            <div class="header-right">
                Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }}
            </div>
        </div>

        {{-- ── Filter Info ── --}}
        <div class="filter-box">
            <span>Unit:
                <strong>{{ $unitRef?->nama_unit ?? 'SEMUA UNIT' }}</strong>
            </span>
            <span>Dari:
                <strong>{{ isset($filters['dari']) && $filters['dari'] ? \Carbon\Carbon::parse($filters['dari'])->translatedFormat('d F Y') : 'Awal' }}</strong>
            </span>
            <span>Sampai:
                <strong>{{ isset($filters['sampai']) && $filters['sampai'] ? \Carbon\Carbon::parse($filters['sampai'])->translatedFormat('d F Y') : 'Sekarang' }}</strong>
            </span>
            <span>Status: <strong>SELESAI</strong></span>
        </div>

        {{-- ── Summary Stats ── --}}
        <div class="summary-stat">
            <div class="stat-item">
                <div class="stat-label">Total Unit</div>
                <div class="stat-value">{{ $unitList->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ number_format($totalTransaksiGlobal, 0, ',', '.') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" style="font-size: 8.5pt;">Rp {{ number_format($totalPendapatanGlobal, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- ── Table per Unit ── --}}
        @foreach($unitList as $unit)
            @php
                $unitPendapatan  = $unit->umkm->sum('total_penjualan');
                $unitTransaksi   = $unit->umkm->sum('jumlah_transaksi');
                $umkmWithTrx     = $unit->umkm->filter(fn($u) => ($u->jumlah_transaksi ?? 0) > 0);
            @endphp

            <table class="main-table">
                {{-- Unit Group Header --}}
                <tbody>
                    <tr class="unit-header">
                        <td colspan="5">
                            {{ $unit->nama_unit }}
                            <div class="unit-sub">
                                {{ $unit->kota_nama ?? '' }}{{ $unit->provinsi_nama ? ' · ' . $unit->provinsi_nama : '' }}
                                &nbsp;|&nbsp; {{ $umkmWithTrx->count() }} UMKM &nbsp;|&nbsp;
                                {{ number_format($unitTransaksi, 0, ',', '.') }} Transaksi
                            </div>
                        </td>
                    </tr>

                    {{-- Table Header --}}
                    <tr>
                        <th style="width: 22px;" class="text-center">No</th>
                        <th>Nama UMKM</th>
                        <th>Pemilik</th>
                        <th class="text-right" style="width: 70px;">Transaksi</th>
                        <th class="text-right" style="width: 140px;">Total Penjualan (Rp)</th>
                    </tr>

                    @forelse($umkmWithTrx->values() as $idx => $umkm)
                        <tr>
                            <td class="text-center" style="color: #94a3b8;">{{ $idx + 1 }}</td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $umkm->nama_usaha }}</td>
                            <td style="color: #64748b;">{{ $umkm->nama_pemilik ?? '-' }}</td>
                            <td class="text-right" style="color: #475569;">{{ number_format($umkm->jumlah_transaksi ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right currency">{{ number_format($umkm->total_penjualan ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="5">Tidak ada transaksi selesai pada unit ini.</td>
                        </tr>
                    @endforelse

                    {{-- Subtotal unit --}}
                    @if($umkmWithTrx->isNotEmpty())
                        <tr class="subtotal-row">
                            <td colspan="3" class="text-right">Subtotal {{ $unit->nama_unit }}</td>
                            <td class="text-right">{{ number_format($unitTransaksi, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($unitPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach

        {{-- ── Grand Total ── --}}
        @if($totalPendapatanGlobal > 0)
            <table style="width:100%; border-collapse:collapse; margin-top: 10px;">
                <tr class="grand-total-row">
                    <td style="width:100%;" colspan="3" class="text-right">TOTAL PENDAPATAN KESELURUHAN</td>
                    <td style="width:80px;" class="text-right">{{ number_format($totalTransaksiGlobal, 0, ',', '.') }}</td>
                    <td style="width:160px;" class="text-right">Rp {{ number_format($totalPendapatanGlobal, 0, ',', '.') }}</td>
                </tr>
            </table>
        @endif

        {{-- ── Footer ── --}}
        <div class="footer">
            Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi YBM PLN UMKM &middot;
            Hanya menampilkan transaksi dengan status <strong>Selesai</strong>.
        </div>

    </div>
</body>

</html>

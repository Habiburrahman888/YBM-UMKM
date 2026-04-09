<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan - {{ $umkm->nama_usaha }}</title>
    <style>
        @page {
            margin: 0.7in 0.5in;
        }

        @page :first {
            margin-top: 0;
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

        /* ── Summary Stats ── */
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
            width: 25%;
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

        .stat-value.blue  { color: #1d4ed8; }
        .stat-value.green { color: #16a34a; }
        .stat-value.amber { color: #d97706; }
        .stat-value.pendapatan { color: #0f172a; font-size: 9pt; }

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

        table.main-table tfoot td {
            padding: 10px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 0.5px solid #cbd5e1;
            background: #f1f5f9;
            font-size: 7.5pt;
            font-weight: 700;
            color: #0f172a;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .currency {
            font-weight: 700;
            color: #16a34a;
        }

        .total-row {
            background: #f1f5f9;
            font-weight: 700;
            color: #0f172a;
        }

        .empty-row td {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-style: italic;
        }

        /* ── Badge Status ── */
        .badge {
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-dikirim  { background: #fef9c3; color: #854d0e; }
        .badge-selesai  { background: #dcfce7; color: #166534; }
        .badge-batal    { background: #fee2e2; color: #991b1b; }

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
                <h1>Laporan Pesanan Masuk</h1>
                <p>{{ $umkm->nama_usaha }} &nbsp;&middot;&nbsp; {{ $umkm->nama_pemilik }}</p>
            </div>
            <div class="header-right">
                Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>

        {{-- ── Filter Info ── --}}
        <div class="filter-box">
            <span>Status:
                <strong>{{ isset($filters['status']) && $filters['status'] ? strtoupper($filters['status']) : 'SEMUA' }}</strong></span>
            <span>Dari:
                <strong>{{ isset($filters['dari']) && $filters['dari'] ? \Carbon\Carbon::parse($filters['dari'])->translatedFormat('d F Y') : 'Awal' }}</strong></span>
            <span>Sampai:
                <strong>{{ isset($filters['sampai']) && $filters['sampai'] ? \Carbon\Carbon::parse($filters['sampai'])->translatedFormat('d F Y') : 'Sekarang' }}</strong></span>
            <span>Total Data: <strong>{{ $pesanans->count() }} pesanan</strong></span>
        </div>

        {{-- ── Summary Stats ── --}}
        <div class="summary-stat">
            <div class="stat-item">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value blue">{{ $pesanans->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Selesai</div>
                <div class="stat-value green">{{ $pesanans->where('status', 'selesai')->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Pending / Proses / Kirim</div>
                <div class="stat-value amber">{{ $pesanans->whereIn('status', ['pending', 'diproses', 'dikirim'])->count() }}</div>
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
                    <th style="width: 60px;">Tanggal</th>
                    <th>Pembeli</th>
                    <th style="width: 80px;">No. WA</th>
                    <th>Produk</th>
                    <th style="width: 30px;" class="text-center">Qty</th>
                    <th style="width: 90px;" class="text-right">Total (Rp)</th>
                    <th style="width: 65px;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $i => $p)
                    <tr>
                        <td class="text-center" style="color: #94a3b8;">{{ $i + 1 }}</td>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $p->nama_pembeli }}</td>
                        <td>{{ $p->telepon_pembeli }}</td>
                        <td>
                            @if ($p->items->count() > 0)
                                @foreach ($p->items as $item)
                                    &bull; {{ $item->produk->nama_produk }} (x{{ $item->jumlah }})<br>
                                @endforeach
                            @else
                                {{ $p->produk->nama_produk ?? '-' }}
                            @endif
                        </td>
                        <td class="text-center" style="font-weight: 700;">
                            @if ($p->items->count() > 0)
                                {{ $p->items->sum('jumlah') }}
                            @else
                                {{ $p->jumlah }}
                            @endif
                        </td>
                        <td class="text-right currency">{{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($p->status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif ($p->status === 'diproses')
                                <span class="badge badge-diproses">Diproses</span>
                            @elseif ($p->status === 'dikirim')
                                <span class="badge badge-dikirim">Dikirim</span>
                            @elseif ($p->status === 'selesai')
                                <span class="badge badge-selesai">Selesai</span>
                            @else
                                <span class="badge badge-batal">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="8">Tidak ada data pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($pesanans->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">TOTAL PENDAPATAN (SELESAI)</td>
                        <td class="text-right" style="color: #16a34a;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>

        {{-- ── Footer ── --}}
        <div class="footer">
            Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi YBM PLN UMKM.
        </div>

    </div>
</body>

</html>
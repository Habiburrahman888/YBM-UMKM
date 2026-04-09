<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.7in 0.5in; }
        @page :first { margin-top: 0; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8pt; line-height: 1.4;
            color: #334155; background: #fff; margin: 0; padding: 0;
        }
        .page-wrapper { padding: 0.4in 0.5in; }

        .header {
            display: table; width: 100%;
            margin-bottom: 20px; padding-bottom: 12px;
            border-bottom: 0.5px solid #e2e8f0;
        }
        .header-left  { display: table-cell; vertical-align: bottom; }
        .header-right {
            display: table-cell; vertical-align: bottom;
            text-align: right; color: #94a3b8; font-size: 8pt;
        }
        .header h1 {
            font-size: 14pt; font-weight: 300; color: #0f172a;
            letter-spacing: -0.01em; text-transform: uppercase; margin-bottom: 2px;
        }
        .header p { font-size: 7.5pt; color: #64748b; margin: 0; }

        .summary-stat {
            display: table; width: 100%;
            background: #f8fafc; border: 0.5px solid #e2e8f0;
            border-radius: 6px; margin-bottom: 20px; padding: 10px;
        }
        .stat-item {
            display: table-cell; width: 25%;
            text-align: center; border-right: 0.5px solid #e2e8f0;
        }
        .stat-item:last-child { border-right: none; }
        .stat-label {
            font-size: 7pt; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;
        }
        .stat-value { font-size: 11pt; font-weight: 700; color: #0f172a; }
        .stat-value.pendapatan { font-size: 9pt; }

        .unit-section { margin-bottom: 35px; page-break-inside: avoid; }
        .unit-header {
            background: #0f172a; color: #fff;
            padding: 8px 12px; font-size: 8pt; font-weight: 700;
            border-radius: 4px; display: table; width: 100%; margin-bottom: 10px;
        }
        .unit-title { display: table-cell; text-transform: uppercase; }
        .unit-total {
            display: table-cell; text-align: right;
            font-size: 7.5pt; font-weight: 400;
        }

        table.main-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.main-table th {
            background: #f8fafc; text-align: left;
            font-size: 7.5pt; font-weight: 600; color: #475569;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 8px 10px; border-bottom: 1px solid #cbd5e1;
        }
        table.main-table td {
            padding: 6px 10px; border-bottom: 0.5px solid #f1f5f9;
            font-size: 7.5pt; vertical-align: top;
        }
        table.main-table tfoot td {
            padding: 10px; border-top: 1px solid #cbd5e1;
            border-bottom: 0.5px solid #cbd5e1;
            background: #f1f5f9; font-size: 7.5pt;
            font-weight: 700; color: #0f172a;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .no    { width: 20px; color: #94a3b8; text-align: center; }
        .price { font-weight: 700; text-align: right; width: 90px; color: #0f172a; }
        .status { text-align: center; width: 60px; }

        .umkm-info { font-weight: 700; color: #0f172a; }
        .owner { font-size: 7pt; color: #64748b; }

        .modals {
            font-size: 7pt; color: #475569;
            margin: 0; padding-left: 12px; list-style-type: square;
        }
        .modals li { margin-bottom: 2px; }

        .status-pill {
            display: inline-block; padding: 2px 7px;
            border-radius: 4px; font-weight: 700;
            font-size: 7pt; text-transform: uppercase;
        }
        .pill-aktif    { background: #0f172a; color: #fff; }
        .pill-nonaktif { background: #f1f5f9; color: #94a3b8; }

        .empty-row td {
            text-align: center; padding: 20px;
            color: #94a3b8; font-style: italic;
        }

        .footer {
            margin-top: 30px; padding-top: 15px;
            border-top: 0.5px solid #e2e8f0;
            font-size: 7.5pt; color: #94a3b8; text-align: center;
        }
    </style>
</head>
<body>
    @php
        $unitCount       = $umkmList->count();
        $isSingleUnit    = $unitCount === 1;
        $totalUmkm       = $umkmList->flatten()->count();
        $totalNilaiModal = $umkmList->flatten()->sum(fn($u) => $u->modalUmkm->sum('nilai_modal'));
        $singleUnit      = $isSingleUnit ? $umkmList->first()->first()->unit : null;
        $title           = $isSingleUnit
            ? 'Laporan UMKM Unit ' . ($singleUnit ? $singleUnit->nama_unit : 'Pusat')
            : 'Laporan Unit & UMKM Binaan';
    @endphp

    <div class="page-wrapper">

        <div class="header">
            <div class="header-left">
                <h1>{{ $title }}</h1>
                <p>{{ $isSingleUnit ? 'Data UMKM binaan pada unit ' . ($singleUnit ? $singleUnit->nama_unit : 'pusat') : 'Rekapitulasi data unit dan pendampingan UMKM YBM.' }}</p>
            </div>
            <div class="header-right">
                Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>

        <div class="summary-stat">
            @if(!$isSingleUnit)
                <div class="stat-item">
                    <div class="stat-label">Total Unit</div>
                    <div class="stat-value">{{ $unitCount }}</div>
                </div>
            @endif
            <div class="stat-item">
                <div class="stat-label">Total UMKM Binaan</div>
                <div class="stat-value">{{ $totalUmkm }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Terverifikasi Aktif</div>
                <div class="stat-value">{{ $umkmList->flatten()->where('status', 'aktif')->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Akumulasi Modal</div>
                <div class="stat-value pendapatan">Rp {{ number_format($totalNilaiModal, 0, ',', '.') }}</div>
            </div>
        </div>

        @foreach($unitList as $unit)
            @php
                $unitId = $unit->id;
                $items  = $umkmList->get($unitId, collect());
            @endphp
            <div class="unit-section">
                <div class="unit-header">
                    <div class="unit-title">UNIT: {{ strtoupper($unit?->nama_unit ?? 'PUSAT / TANPA UNIT') }}</div>
                    <div class="unit-total">Total Modal Unit: Rp
                        {{ number_format($items->sum(fn($u) => $u->modalUmkm->sum('nilai_modal')), 0, ',', '.') }}
                    </div>
                </div>

                <table class="main-table">
                    <thead>
                        <tr>
                            <th class="no text-center">No</th>
                            <th>Informasi UMKM</th>
                            <th>Item Modal Terbantu</th>
                            <th class="status text-center">Status</th>
                            <th class="price text-right">Nilai Modal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td class="no">{{ $index + 1 }}</td>
                                <td>
                                    <div class="umkm-info">{{ $item->nama_usaha }}</div>
                                    <div class="owner">{{ $item->nama_pemilik }}</div>
                                    <div class="owner">{{ $item->kategori->nama ?? '-' }} | Berdiri {{ $item->tahun_berdiri ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($item->modalUmkm->isNotEmpty())
                                        <ul class="modals">
                                            @foreach($item->modalUmkm as $modal)
                                                <li>{{ $modal->nama_item }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span style="color:#cbd5e1; font-style:italic; font-size:7pt;">- belum ada data -</span>
                                    @endif
                                </td>
                                <td class="status">
                                    <span class="status-pill pill-{{ $item->status }}">{{ $item->status }}</span>
                                </td>
                                <td class="price">
                                    {{ number_format($item->modalUmkm->sum('nilai_modal'), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="5">- Belum ada data UMKM untuk unit ini -</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endforeach

        @if($totalNilaiModal > 0)
            <table class="main-table" style="margin-top: 10px;">
                <tfoot>
                    <tr>
                        <td class="text-right">
                            {{ $isSingleUnit ? 'TOTAL MODAL UNIT' : 'TOTAL KESELURUHAN MODAL SEMUA UNIT' }}
                        </td>
                        <td style="width:120px; text-align:right;">
                            Rp {{ number_format($totalNilaiModal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endif

        <div class="footer">
            Dokumen ini dibuat otomatis oleh Sistem Informasi YBM UMKM sebagai laporan resmi rekapitulasi unit binaan.
        </div>

    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.5in; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 8pt; color: #1e293b; background: #fff; margin: 0; padding: 0; }
        .header { display: table; width: 100%; border-bottom: 1.5px solid #0f172a; padding-bottom: 20px; margin-bottom: 20px; }
        .header-left { display: table-cell; vertical-align: bottom; }
        .header-right { display: table-cell; vertical-align: bottom; text-align: right; font-size: 7.5pt; color: #64748b; }
        .header h1 { font-size: 16pt; font-weight: bold; color: #0f172a; margin: 0; margin-bottom: 5px; text-transform: uppercase; letter-spacing: -0.01em; }
        .header p { margin: 0; font-size: 8pt; color: #64748b; }
        
        .summary { display: table; width: 100%; margin-bottom: 30px; background: #f8fafc; border: 0.5px solid #e2e8f0; border-collapse: separate; border-spacing: 15px; border-radius: 8px; }
        .stat-item { display: table-cell; text-align: center; }
        .stat-label { font-size: 6.5pt; color: #64748b; text-transform: uppercase; font-weight: bold; margin-bottom: 3px; letter-spacing: 0.05em; }
        .stat-value { font-size: 11pt; font-weight: bold; color: #0f172a; }
        .stat-value.primary { color: #2563eb; }
        
        .unit-section { margin-bottom: 35px; page-break-inside: avoid; }
        .unit-header { background: #1e293b; color: #fff; padding: 8px 12px; font-weight: bold; font-size: 9pt; border-radius: 4px; display: table; width: 100%; margin-bottom: 10px; }
        .unit-title { display: table-cell; }
        .unit-total { display: table-cell; text-align: right; font-size: 7.5pt; font-weight: normal; }

        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background: #f1f5f9; text-align: left; font-size: 7pt; font-weight: bold; color: #475569; padding: 8px 10px; border-bottom: 1px solid #cbd5e1; text-transform: uppercase; letter-spacing: 0.03em; }
        td { padding: 8px 10px; border-bottom: 0.5px solid #f1f5f9; vertical-align: top; line-height: 1.3; }
        .no { width: 20px; color: #94a3b8; text-align: center; }
        .umkm-info { font-weight: bold; color: #0f172a; }
        .owner { font-size: 7pt; color: #64748b; }
        .modals { font-size: 7pt; color: #475569; margin: 0; padding-left: 12px; list-style-type: square; }
        .modals li { margin-bottom: 2px; }
        .price { font-weight: bold; text-align: right; width: 90px; color: #0f172a; }
        .status { text-align: center; width: 60px; }
        .status-pill { display: inline-block; padding: 2px 5px; border-radius: 3px; font-weight: bold; font-size: 6pt; text-transform: uppercase; }
        .pill-aktif { background: #f0fdf4; color: #166534; }
        .pill-nonaktif { background: #fef2f2; color: #991b1b; }

        .footer { margin-top: 40px; border-top: 0.5px solid #e2e8f0; padding-top: 15px; font-size: 7pt; color: #94a3b8; text-align: center; font-style: italic; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    @php
        $unitCount = $umkmList->count();
        $isSingleUnit = $unitCount === 1;
        $totalUmkm = $umkmList->flatten()->count();
        $totalNilaiModal = $umkmList->flatten()->sum(fn($u) => $u->modalUmkm->sum('nilai_modal'));
        
        $singleUnit = $isSingleUnit ? $umkmList->first()->first()->unit : null;
        $title = $isSingleUnit 
            ? 'Laporan UMKM Unit ' . ($singleUnit ? $singleUnit->nama_unit : 'Pusat')
            : 'Laporan Unit & UMKM Binaan';
    @endphp

    <div class="header">
        <div class="header-left">
            <h1>{{ $title }}</h1>
            <p>{{ $isSingleUnit ? 'Data UMKM binaan pada unit ' . ($singleUnit ? $singleUnit->nama_unit : 'pusat') : 'Rekapitulasi data unit dan pendampingan UMKM YBM.' }}</p>
        </div>
        <div class="header-right">
            Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}<br>
            Halaman <span class="page-number"></span>
        </div>
    </div>

    <div class="summary">
        @if (!$isSingleUnit)
        <div class="stat-item" style="border-right: 0.5px solid #e2e8f0;">
            <div class="stat-label">Total Unit</div>
            <div class="stat-value">{{ $unitCount }}</div>
        </div>
        @endif
        <div class="stat-item" style="border-right: 0.5px solid #e2e8f0;">
            <div class="stat-label">Total UMKM Binaan</div>
            <div class="stat-value">{{ $totalUmkm }}</div>
        </div>
        <div class="stat-item" style="border-right: 0.5px solid #e2e8f0;">
            <div class="stat-label">Terverifikasi Aktif</div>
            <div class="stat-value primary">{{ $umkmList->flatten()->where('status', 'aktif')->count() }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Akumulasi Modal</div>
            <div class="stat-value">Rp {{ number_format($totalNilaiModal, 0, ',', '.') }}</div>
        </div>
    </div>

    @foreach ($umkmList as $unitId => $items)
        @php $unit = $items->first()->unit; @endphp
        <div class="unit-section">
            <div class="unit-header">
                <div class="unit-title text-uppercase">UNIT: {{ $unit ? strtoupper($unit->nama_unit) : 'PUSAT / TANPA UNIT' }}</div>
                <div class="unit-total">Total Modal Unit: Rp {{ number_format($items->sum(fn($u) => $u->modalUmkm->sum('nilai_modal')), 0, ',', '.') }}</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="no">No</th>
                        <th>Informasi UMKM</th>
                        <th>Item Modal Terbantu</th>
                        <th class="status">Status</th>
                        <th class="price">Nilai Modal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td class="no">{{ $index + 1 }}</td>
                            <td>
                                <div class="umkm-info">{{ $item->nama_usaha }}</div>
                                <div class="owner">{{ $item->nama_pemilik }}</div>
                                <div class="owner">{{ $item->kategori->nama ?? '-' }} | Berdiri {{ $item->tahun_berdiri ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($item->modalUmkm->isNotEmpty())
                                    <ul class="modals">
                                        @foreach ($item->modalUmkm as $modal)
                                            <li>{{ $modal->nama_item }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span style="color: #cbd5e1; font-style: italic; font-size: 7pt;">- belum ada data -</span>
                                @endif
                            </td>
                            <td class="status">
                                <span class="status-pill pill-{{ $item->status }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="price">
                                {{ number_format($item->modalUmkm->sum('nilai_modal'), 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <table style="margin-top: 10px; background: #0f172a; color: #fff; border: 0;">
        <tr>
            <td style="padding: 12px; font-weight: bold; font-size: 10pt; text-align: right;">
                {{ $isSingleUnit ? 'TOTAL MODAL UNIT' : 'TOTAL KESELURUHAN MODAL SEMUA UNIT' }}
            </td>
            <td style="padding: 12px; width: 120px; font-weight: bold; font-size: 10pt; text-align: right; border-left: 1px solid #334155;">
                Rp {{ number_format($totalNilaiModal, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem Informasi YBM UMKM sebagai laporan resmi rekapitulasi unit binaan.
    </div>
</body>
</html>

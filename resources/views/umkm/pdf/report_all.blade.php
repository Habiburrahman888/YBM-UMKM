<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
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

        .stat-value.aktif {
            color: #166534;
        }

        .stat-value.nonaktif {
            color: #991b1b;
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

        .code {
            font-family: 'Courier', monospace;
            color: #64748b;
            font-size: 7.5pt;
        }

        .status-pill {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 6.5pt;
            font-weight: 600;
            text-transform: uppercase;
        }

        .pill-aktif {
            background: #f0fdf4;
            color: #166534;
        }

        .pill-nonaktif {
            background: #fef2f2;
            color: #991b1b;
        }

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

        <div class="header">
            <div class="header-left">
                <h1>{{ auth()->user()->role === 'admin' ? 'Laporan Unit & UMKM' : 'Rekapitulasi Data UMKM' }}</h1>
            </div>
            <div class="header-right">
                Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }}
            </div>
        </div>

        @php
            $totalUmkm = auth()->user()->role === 'admin' ? $umkmList->flatten()->count() : $umkmList->count();
            $totalAktif =
                auth()->user()->role === 'admin'
                    ? $umkmList->flatten()->where('status', 'aktif')->count()
                    : $umkmList->where('status', 'aktif')->count();
            $totalNonaktif =
                auth()->user()->role === 'admin'
                    ? $umkmList->flatten()->where('status', 'nonaktif')->count()
                    : $umkmList->where('status', 'nonaktif')->count();
            $totalNilaiModal =
                auth()->user()->role === 'admin'
                    ? $umkmList->flatten()->sum(fn($u) => $u->modalUmkm->sum('nilai_modal'))
                    : $umkmList->sum(fn($u) => $u->modalUmkm->sum('nilai_modal'));
        @endphp

        <div class="summary-stat">
            <div class="stat-item">
                <div class="stat-label">Total UMKM</div>
                <div class="stat-value">{{ $totalUmkm }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Terverifikasi</div>
                <div class="stat-value aktif">{{ $totalAktif }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Non-Aktif</div>
                <div class="stat-value nonaktif">{{ $totalNonaktif }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Akumulasi Modal</div>
                <div class="stat-value">Rp {{ number_format($totalNilaiModal, 0, ',', '.') }}</div>
            </div>
        </div>

        @if (auth()->user()->role === 'admin')
            @foreach ($umkmList as $unitId => $umkms)
                @php $unit = $umkms->first()->unit; @endphp
                <div
                    style="background: #f1f5f9; padding: 5px 10px; font-weight: bold; border: 0.5px solid #cbd5e1; margin-top: 15px;">
                    Unit: {{ $unit->nama ?? 'Tanpa Unit' }}
                </div>
                <table class="main-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th style="width: 25px;" class="text-center">No</th>
                            <th style="width: 70px;">Kode</th>
                            <th style="width: 140px;">Nama Usaha / Pemilik</th>
                            <th style="width: 120px;">Item Modal</th>
                            <th>Kategori</th>
                            <th class="text-center">Tahun</th>
                            <th>Wilayah</th>
                            <th class="text-right">Modal (Rp)</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($umkms as $i => $umkm)
                            <tr>
                                <td class="text-center" style="color: #94a3b8;">{{ $i + 1 }}</td>
                                <td><span class="code">{{ $umkm->kode_umkm }}</span></td>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a;">{{ $umkm->nama_usaha }}</div>
                                    <div style="font-size: 7pt; color: #64748b;">{{ $umkm->nama_pemilik }}</div>
                                </td>
                                <td>
                                    @if ($umkm->modalUmkm->isNotEmpty())
                                        <ul style="margin: 0; padding-left: 10px; list-style-type: none;">
                                            @foreach ($umkm->modalUmkm as $modal)
                                                <li style="font-size: 7pt; color: #475569;">• {{ $modal->nama_item }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </td>
                                <td>{{ $umkm->kategori->nama ?? '-' }}</td>
                                <td class="text-center">{{ $umkm->tahun_berdiri ?? '-' }}</td>
                                <td>{{ $umkm->city->name ?? '-' }}</td>
                                <td class="text-right" style="font-weight: 700;">
                                    {{ number_format($umkm->modalUmkm->sum('nilai_modal'), 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="status-pill pill-{{ $umkm->status }}">
                                        {{ $umkm->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <table class="main-table">
                <thead>
                    <tr>
                        <th style="width: 25px;" class="text-center">No</th>
                        <th style="width: 70px;">Kode</th>
                        <th style="width: 140px;">Nama Usaha / Pemilik</th>
                        <th style="width: 120px;">Item Modal</th>
                        <th>Kategori</th>
                        <th class="text-center">Tahun</th>
                        <th>Wilayah</th>
                        <th class="text-right">Modal (Rp)</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($umkmList as $i => $umkm)
                        <tr>
                            <td class="text-center" style="color: #94a3b8;">{{ $i + 1 }}</td>
                            <td><span class="code">{{ $umkm->kode_umkm }}</span></td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">{{ $umkm->nama_usaha }}</div>
                                <div style="font-size: 7pt; color: #64748b;">{{ $umkm->nama_pemilik }}</div>
                            </td>
                            <td>
                                @if ($umkm->modalUmkm->isNotEmpty())
                                    <ul style="margin: 0; padding-left: 10px; list-style-type: none;">
                                        @foreach ($umkm->modalUmkm as $modal)
                                            <li style="font-size: 7pt; color: #475569;">• {{ $modal->nama_item }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                            <td>{{ $umkm->kategori->nama ?? '-' }}</td>
                            <td class="text-center">{{ $umkm->tahun_berdiri ?? '-' }}</td>
                            <td>{{ $umkm->city->name ?? '-' }}</td>
                            <td class="text-right" style="font-weight: 700;">
                                {{ number_format($umkm->modalUmkm->sum('nilai_modal'), 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="status-pill pill-{{ $umkm->status }}">
                                    {{ $umkm->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <tr class="total-row">
                <td
                    style="padding: 10px; text-align: right; border: 0.5px solid #cbd5e1; background: #f1f5f9; font-weight: bold;">
                    TOTAL KESELURUHAN MODAL</td>
                <td
                    style="padding: 10px; width: 100px; text-align: right; border: 0.5px solid #cbd5e1; background: #f1f5f9; font-weight: bold;">
                    Rp {{ number_format($totalNilaiModal, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <div class="footer">
            Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi YBM UMKM.
        </div>
    </div>
</body>

</html>

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
            font-size: 9pt;
            line-height: 1.5;
            color: #334155;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            padding: 0.5in 0.7in;
        }

        /* ── Header ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            padding-bottom: 10px;
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
            font-size: 16pt;
            font-weight: 300;
            color: #0f172a;
            letter-spacing: -0.025em;
            text-transform: uppercase;
        }

        /* ── Hero ── */
        .hero {
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 22pt;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .hero-meta {
            font-size: 9pt;
            color: #64748b;
        }

        .status-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-aktif {
            background: #f0fdf4;
            color: #166534;
            border: 0.5px solid #bbf7d0;
        }


        .status-nonaktif {
            background: #fef2f2;
            color: #991b1b;
            border: 0.5px solid #fecaca;
        }

        /* ── Section ── */
        .section {
            margin-bottom: 20px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 4px;
        }

        .section-title {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #0f172a;
        }

        /* ── Grid ── */
        .grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .grid-row {
            display: table-row;
        }

        .grid-cell {
            display: table-cell;
            padding: 4px 0;
            vertical-align: top;
            border-bottom: 0.5px solid #f1f5f9;
        }

        .label {
            width: 30%;
            color: #64748b;
            font-size: 8.5pt;
        }

        .value {
            width: 70%;
            color: #1e293b;
            font-weight: 500;
        }

        /* ── Table ── */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th {
            text-align: left;
            font-size: 8pt;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        table.data-table td {
            padding: 6px 12px;
            border-bottom: 0.5px solid #f1f5f9;
            font-size: 8.5pt;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background: #f8fafc;
            font-weight: 700;
            color: #0f172a;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
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
                <h1>Profil UMKM</h1>
            </div>
            <div class="header-right">
                Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }}
            </div>
        </div>

        <div class="hero">
            <div class="hero-title">{{ $umkm->nama_usaha }}</div>
            <div class="hero-meta">
                {{ $umkm->kode_umkm }} &bull;
                @if ($umkm->kategori)
                    {{ $umkm->kategori->nama }} &bull;
                @endif
                Berdiri {{ $umkm->tahun_berdiri ?? '-' }}
            </div>
            <div class="status-badge status-{{ $umkm->status }}">
                {{ ucfirst($umkm->status) }}
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <span class="section-title">Informasi Utama</span>
            </div>
            <div class="grid">
                <div class="grid-row">
                    <div class="grid-cell label">Pemilik</div>
                    <div class="grid-cell value">{{ $umkm->nama_pemilik }}</div>
                </div>
                <div class="grid-row">
                    <div class="grid-cell label">Kontak</div>
                    <div class="grid-cell value">{{ $umkm->telepon }} / {{ $umkm->email }}</div>
                </div>
                <div class="grid-row">
                    <div class="grid-cell label">Alamat</div>
                    <div class="grid-cell value">
                        {{ $umkm->alamat }}<br>
                        <span style="color: #64748b; font-weight: 400; font-size: 8pt;">
                            @php
                                $village = $umkm->village ? 'Kel. ' . $umkm->village->name : null;
                                $district = $umkm->district ? 'Kec. ' . $umkm->district->name : null;
                                echo collect([
                                    $village,
                                    $district,
                                    $umkm->city?->name,
                                    $umkm->province?->name,
                                    $umkm->kode_pos,
                                ])
                                    ->filter()
                                    ->implode(', ');
                            @endphp
                        </span>
                    </div>
                </div>
                <div class="grid-row">
                    <div class="grid-cell label">Bergabung Sejak</div>
                    <div class="grid-cell value">{{ $umkm->tanggal_bergabung?->translatedFormat('d F Y') ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <span class="section-title">Aset & Modal Usaha</span>
            </div>
            @if ($umkm->modalUmkm && $umkm->modalUmkm->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 30px;" class="text-center">NO</th>
                            <th>Nama Item</th>
                            <th>Kategori</th>
                            <th class="text-center">Kondisi</th>
                            <th class="text-right">Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($umkm->modalUmkm as $i => $modal)
                            <tr>
                                <td class="text-center" style="color: #94a3b8;">{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $modal->nama_item }}</strong>
                                    @if ($modal->keterangan)
                                        <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">
                                            {{ $modal->keterangan }}</div>
                                    @endif
                                </td>
                                <td style="text-transform: capitalize;">{{ $modal->kategori_modal }}</td>
                                <td class="text-center">{{ ucfirst($modal->kondisi) }}</td>
                                <td class="text-right">
                                    <strong>{{ number_format($modal->nilai_modal, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" class="text-right">Total Akumulasi Modal</td>
                            <td class="text-right">Rp
                                {{ number_format($umkm->modalUmkm->sum('nilai_modal'), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p style="color: #94a3b8; font-style: italic; padding: 10px 0;">Belum ada data modal terdaftar.</p>
            @endif
        </div>

        <div class="section">
            <div class="section-header">
                <span class="section-title">Produk & Layanan</span>
            </div>
            @if ($umkm->produkUmkm && $umkm->produkUmkm->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 30px;" class="text-center">NO</th>
                            <th>Nama Produk</th>
                            <th class="text-right">Harga (Rp)</th>
                            <th style="width: 40%;">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($umkm->produkUmkm as $i => $produk)
                            <tr>
                                <td class="text-center" style="color: #94a3b8;">{{ $i + 1 }}</td>
                                <td><strong>{{ $produk->nama_produk }}</strong></td>
                                <td class="text-right">
                                    <strong>{{ number_format($produk->harga, 0, ',', '.') }}</strong>
                                </td>
                                <td style="color: #64748b; font-size: 8pt;">
                                    {{ Str::limit($produk->deskripsi_produk, 150) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #94a3b8; font-style: italic; padding: 10px 0;">Belum ada produk terdaftar.</p>
            @endif
        </div>

        <div class="footer">
            Dokumen ini dibuat secara otomatis melalui Sistem Informasi YBM UMKM.
        </div>
    </div>
</body>

</html>

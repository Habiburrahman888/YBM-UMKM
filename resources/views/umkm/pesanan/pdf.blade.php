<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan - {{ $umkm->nama_usaha }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'DejaVu Sans', sans-serif;
            font-size: 11px;
            background: #eef2f5;
            color: #1f3a47;
            padding: 20px;
        }

        /* kontainer utama menyatu, tidak terlalu lebar */
        .laporan-card {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        /* padding internal proporsional, tidak mepet */
        .laporan-body {
            padding: 20px 24px 24px 24px;
        }

        /* header sederhana menyatu */
        .header-simple {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #ecf3f8;
        }

        .umkm-title h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1e4a6b;
            letter-spacing: -0.2px;
        }

        .umkm-title p {
            font-size: 9.5px;
            color: #6f95ab;
            margin-top: 2px;
        }

        .tanggal-box {
            text-align: right;
        }

        .tanggal-box .date {
            font-weight: 600;
            font-size: 12px;
            color: #1e4a6b;
        }

        .tanggal-box .time {
            font-size: 9px;
            color: #8aaec2;
        }

        /* filter dalam satu baris rapi */
        .filter-chip {
            background: #f9fbfd;
            border-radius: 40px;
            padding: 8px 18px;
            margin-bottom: 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            border: 1px solid #eaf0f5;
            font-size: 9.5px;
        }

        .filter-chip span {
            color: #2c5f7a;
            font-weight: 500;
        }

        .filter-chip strong {
            color: #1a445b;
            font-weight: 700;
        }

        /* 3 stat kecil menyatu, tidak besar */
        .stats-mini {
            display: flex;
            gap: 14px;
            margin-bottom: 22px;
        }

        .stat-mini-card {
            flex: 1;
            background: #fafcfd;
            border-radius: 18px;
            padding: 10px 12px;
            border: 1px solid #eef3f9;
            text-align: center;
        }

        .stat-mini-card .label {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6d8ea3;
        }

        .stat-mini-card .value {
            font-size: 22px;
            font-weight: 700;
            color: #236b8e;
            line-height: 1.2;
        }

        /* tabel rapi, tidak mepet */
        .table-responsive {
            width: 100%;
            border-radius: 16px;
            border: 1px solid #eef2f7;
            overflow: hidden;
            margin: 10px 0 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f7fc;
            padding: 10px 12px;
            font-size: 9.5px;
            font-weight: 600;
            color: #1a5a78;
            text-align: left;
            border-bottom: 1px solid #e2eaf1;
        }

        td {
            padding: 10px 12px;
            font-size: 10px;
            border-bottom: 1px solid #f0f5fa;
            color: #2b5d78;
        }

        .currency {
            font-weight: 600;
            color: #2e7a6e;
        }

        .badge-soft {
            background: #eef4f0;
            padding: 3px 12px;
            border-radius: 40px;
            font-size: 8.5px;
            font-weight: 500;
            display: inline-block;
            color: #2e6b5c;
        }

        /* total row menyatu dengan tabel */
        .total-garis td {
            background: #f5fafd;
            font-weight: 700;
            color: #1f5a77;
            border-bottom: none;
            padding: 10px 12px;
        }

        /* footer kecil */
        .footer-meta {
            text-align: center;
            margin-top: 20px;
            font-size: 8.5px;
            color: #8bafc2;
            padding-top: 12px;
            border-top: 1px solid #ecf3f9;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .laporan-body {
                padding: 16px;
            }

            .stats-mini {
                flex-direction: column;
                gap: 8px;
            }

            .header-simple {
                flex-direction: column;
                align-items: flex-start;
            }

            .tanggal-box {
                text-align: left;
            }

            .filter-chip {
                gap: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="laporan-card">
        <div class="laporan-body">
            <!-- header ringkas -->
            <div class="header-simple">
                <div class="umkm-title">
                    <h2>Laporan Pesanan</h2>
                    <p>{{ $umkm->nama_usaha }} · {{ $umkm->nama_pemilik }}</p>
                </div>
                <div class="tanggal-box">
                    <div class="date">{{ now()->format('d M Y') }}</div>
                    <div class="time">{{ now()->format('H:i') }} WIB</div>
                </div>
            </div>

            <!-- filter -->
            <div class="filter-chip">
                <span>Status: <strong>{{ $filters['status'] ?? 'SEMUA' }}</strong></span>
                <span>Total order: <strong>{{ $pesanans->count() }}</strong></span>
                @if (isset($filters['tanggal_mulai']) && $filters['tanggal_mulai'])
                    <span>Periode: <strong>{{ $filters['tanggal_mulai'] }} →
                            {{ $filters['tanggal_akhir'] }}</strong></span>
                @endif
            </div>

            <!-- stats ringkas tidak besar -->
            <div class="stats-mini">
                <div class="stat-mini-card">
                    <div class="label">Pesanan</div>
                    <div class="value">{{ $pesanans->count() }}</div>
                </div>
                <div class="stat-mini-card">
                    <div class="label">Selesai</div>
                    <div class="value">{{ $pesanans->where('status', 'selesai')->count() }}</div>
                </div>
                <div class="stat-mini-card">
                    <div class="label">Pendapatan</div>
                    <div class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- tabel -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pembeli</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanans as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $p->nama_pembeli ?? 'Pembeli' }}</td>
                                <td class="currency">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td><span class="badge-soft">{{ $p->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding: 28px;">Tidak ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($pesanans->count() > 0)
                        <tr class="total-garis">
                            <td colspan="2"><strong>Total Keseluruhan</strong></td>
                            <td class="currency"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                            </td>
                            <td></td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="footer-meta">
                Dicetak otomatis · Sistem UMKM
            </div>
        </div>
    </div>
</body>

</html>

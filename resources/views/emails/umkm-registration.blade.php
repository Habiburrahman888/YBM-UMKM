@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi UMKM Berhasil - {{ $nama_expo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
            line-height: 1.6;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            padding: 32px 24px 36px 24px;
            text-align: center;
        }

        .header-logo {
            max-width: 200px;
            max-height: 100px;
            object-fit: contain;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .header-text-logo {
            font-size: 32px;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .header p {
            color: #e0f2fe;
            font-size: 15px;
        }

        .content {
            padding: 40px 32px;
        }

        .greeting {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 24px;
            font-weight: 600;
        }

        .message {
            color: #475569;
            font-size: 15px;
            margin-bottom: 16px;
            line-height: 1.7;
        }

        .success-box {
            background: #f0f9ff;
            padding: 24px;
            border-radius: 8px;
            margin: 24px 0 32px 0;
            text-align: center;
            border: 1px solid #bae6fd;
        }

        .success-title {
            font-size: 20px;
            font-weight: 700;
            color: #0e7490;
            margin-bottom: 8px;
        }

        .success-message {
            font-size: 14px;
            color: #155e75;
            line-height: 1.6;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin: 32px 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .info-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .business-name {
            font-size: 18px;
            font-weight: 700;
            color: #0891b2;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            width: 120px;
            min-width: 120px;
        }

        .info-value {
            font-size: 14px;
            color: #0f172a;
            font-weight: 600;
            flex: 1;
            word-break: break-word;
        }

        .info-value-light {
            font-weight: 400;
            color: #475569;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background: #0891b2;
            color: white;
        }

        .badge-purple {
            background: #8b5cf6;
            color: white;
        }

        .badge-warning {
            background: #f59e0b;
            color: white;
        }

        .badge-success {
            background: #10b981;
            color: white;
        }

        .highlight-box {
            background: #ffffff;
            border: 1px solid #0891b2;
            border-radius: 6px;
            padding: 6px 12px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 13px;
            color: #0891b2;
            display: inline-block;
        }

        .security-alert {
            margin: 28px 0;
            padding: 20px 24px;
            background: #fef2f2;
            border-radius: 8px;
            border: 1px solid #fecaca;
        }

        .security-alert p {
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.8;
            color: #991b1b;
        }

        .security-alert p:last-child {
            margin-bottom: 0;
        }

        .security-alert strong {
            color: #7f1d1d;
            font-weight: 700;
        }

        .info-box {
            margin: 28px 0;
            padding: 20px 24px;
            background: #f0f9ff;
            border-radius: 8px;
            border: 1px solid #bae6fd;
        }

        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #155e75;
            line-height: 1.7;
        }

        .info-box strong {
            color: #0e7490;
            font-weight: 700;
        }

        .button-container {
            text-align: center;
            margin: 36px 0 20px;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            color: white;
            text-decoration: none;
            padding: 16px 48px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(8, 145, 178, 0.3);
            letter-spacing: 0.3px;
        }

        .button-note {
            margin-top: 12px;
            font-size: 13px;
            color: #64748b;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 32px 0;
        }

        .footer {
            background: #f8fafc;
            padding: 32px 32px 28px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            color: #64748b;
            font-size: 13px;
            margin: 6px 0;
            line-height: 1.6;
        }

        .footer .brand {
            color: #0f172a;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .footer .copyright {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 12px;
        }

        @media (max-width: 500px) {
            .header-logo {
                max-width: 160px;
                max-height: 80px;
            }

            .header-text-logo {
                font-size: 26px;
            }

            .info-row {
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .header {
                padding: 28px 20px 32px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 32px 24px;
            }

            .footer {
                padding: 28px 24px 24px 24px;
            }

            .action-button {
                padding: 14px 36px;
                font-size: 15px;
            }

            .info-card,
            .success-box,
            .security-alert,
            .info-box {
                padding: 16px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="header">
            @if ($logo_expo)
                <img src="{{ $message->embed($logo_expo) }}" alt="Logo {{ $nama_expo }}" class="header-logo">
            @else
                <div class="header-text-logo">{{ $nama_expo }}</div>
            @endif

            <h1>Registrasi UMKM Berhasil</h1>
            <p>Selamat bergabung di {{ $nama_expo }}</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $umkm->nama_pemilik }}!</div>

            <div class="message">
                Selamat! Akun UMKM Anda telah berhasil didaftarkan di {{ $nama_expo }}. Berikut adalah informasi
                lengkap mengenai pendaftaran Anda:
            </div>

            <div class="success-box">
                <div class="success-title">Registrasi Berhasil</div>
                <div class="success-message">UMKM Anda kini terdaftar dan siap untuk mengikuti program pembinaan</div>
            </div>

            <div class="section-title">Informasi Usaha</div>
            <div class="info-card">
                <div class="business-name">{{ $umkm->nama_usaha }}</div>

                <div class="info-row">
                    <div class="info-label">Kode UMKM</div>
                    <div class="info-value"><span class="highlight-box">{{ $umkm->kode_umkm }}</span></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Kategori Usaha</div>
                    <div class="info-value">
                        <span class="badge badge-purple">{{ $umkm->kategori?->nama ?? '-' }}</span>
                    </div>
                </div>

                @if ($umkm->tentang)
                    <div class="info-row">
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value info-value-light">{{ $umkm->tentang }}</div>
                    </div>
                @endif
            </div>

            <div class="section-title">Informasi Pemilik</div>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $umkm->nama_pemilik }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $umkm->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $umkm->telepon }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-value info-value-light">{{ $umkm->alamat_lengkap }}</div>
                </div>
            </div>

            <div class="section-title">Informasi Akun Login</div>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Username</div>
                    <div class="info-value"><span class="highlight-box">{{ $username }}</span></div>
                </div>

                @if ($password)
                    <div class="info-row">
                        <div class="info-label">Password</div>
                        <div class="info-value"><span class="highlight-box">{{ $password }}</span></div>
                    </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Peran</div>
                    <div class="info-value"><span class="badge badge-primary">Pelaku UMKM</span></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if ($umkm->status === 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-warning">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-title">Informasi Unit</div>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Nama Unit</div>
                    <div class="info-value">{{ $umkm->unit->nama_unit ?? 'Unit' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kode Unit</div>
                    <div class="info-value">{{ $umkm->unit->kode_unit ?? '-' }}</div>
                </div>
                @if ($umkm->unit->telepon)
                    <div class="info-row">
                        <div class="info-label">Telepon Unit</div>
                        <div class="info-value">{{ $umkm->unit->telepon }}</div>
                    </div>
                @endif
                @if ($umkm->unit->email)
                    <div class="info-row">
                        <div class="info-label">Email Unit</div>
                        <div class="info-value">{{ $umkm->unit->email }}</div>
                    </div>
                @endif
            </div>

            @if ($password)
                <div class="security-alert">
                    <p>
                        <strong>Penting!</strong><br>
                        Email ini berisi password akun Anda. Demi keamanan, segera ubah password Anda setelah login
                        pertama kali dan jangan bagikan kredensial ini kepada siapapun.
                    </p>
                </div>
            @endif

            @if ($umkm->status === 'aktif')
                <div class="info-box">
                    <p>
                        <strong>Akun Anda Sudah Aktif!</strong><br>
                        UMKM Anda telah terdaftar dan akun sudah aktif. Silakan login menggunakan kredensial di atas.
                    </p>
                </div>
            @endif

            <div class="divider"></div>

            <div class="button-container">
                <a href="{{ url('/login') }}" class="action-button">Login ke Dashboard</a>
                <div class="button-note">Gunakan username dan password di atas untuk mengakses sistem</div>
            </div>

            <div class="message" style="margin-top: 24px; font-size: 14px; color: #64748b;">
                Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi tim support
                {{ $nama_expo }} kami.
            </div>
        </div>

        <div class="footer">
            <p class="brand">{{ $nama_expo }}</p>
            <p style="font-size: 12px;">Email otomatis - Mohon tidak membalas email ini</p>
            <p class="copyright">© {{ date('Y') }} {{ $nama_expo }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>

</html>

@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil - {{ $nama_expo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            padding: 20px;
            line-height: 1.6;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            padding: 32px 24px 36px;
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
        }

        .header h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
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
            margin: 24px 0 32px;
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
        }

        .info-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .info-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #0891b2;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
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

        .highlight-box {
            background: #fff;
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
            font-size: 14px;
            color: #991b1b;
            line-height: 1.8;
        }

        .security-alert strong {
            color: #7f1d1d;
        }

        .button-container {
            text-align: center;
            margin: 36px 0 20px;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 48px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 10px;
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
            padding: 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            color: #64748b;
            font-size: 13px;
            margin: 6px 0;
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
            <h1>Registrasi Berhasil!</h1>
            <p>Selamat bergabung di {{ $nama_expo }}</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $nama }}!</div>

            <div class="message">
                Selamat! Akun Anda telah berhasil dibuat dan diverifikasi di {{ $nama_expo }}.
            </div>

            <div class="success-box">
                <div class="success-title">✓ Registrasi Berhasil</div>
                <div class="success-message">Akun Anda kini aktif dan siap digunakan</div>
            </div>

            {{-- Info Akun --}}
            <div class="info-card">
                <div class="info-card-title">Informasi Akun</div>

                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $nama }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $email }}</div>
                </div>
                @if (!$isGoogleUser)
                    <div class="info-row">
                        <div class="info-label">Username</div>
                        <div class="info-value"><span class="highlight-box">{{ $username }}</span></div>
                    </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Unit</div>
                    <div class="info-value">{{ $nama_unit }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kode Unit</div>
                    <div class="info-value"><span class="highlight-box">{{ $kode_unit }}</span></div>
                </div>
            </div>

            @if (!$isGoogleUser && $password)
                <div class="security-alert">
                    <p>
                        <strong>Penting!</strong><br>
                        Simpan kredensial login Anda: Username <strong>{{ $username }}</strong> dengan password
                        yang Anda buat saat registrasi.
                        Jangan bagikan kepada siapapun.
                    </p>
                </div>
            @endif

            @if ($isGoogleUser)
                <div class="info-card">
                    <div class="info-card-title">Login dengan Google</div>
                    <div class="message" style="margin:0;">
                        Anda dapat login menggunakan akun Google Anda. Username otomatis: <span
                            class="highlight-box">{{ $username }}</span>
                    </div>
                </div>
            @endif

            <div class="divider"></div>

            <div class="button-container">
                <a href="{{ url('/login') }}" class="action-button">Login ke Dashboard</a>
                <div class="button-note">Klik tombol di atas untuk mulai menggunakan aplikasi</div>
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

@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Registrasi UMKM - {{ $nama_expo }}</title>
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

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 28px 0 32px 0;
        }

        .info-box h3 {
            color: #0f172a;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .info-item {
            margin: 12px 0;
            color: #475569;
            font-size: 15px;
            display: flex;
            align-items: flex-start;
        }

        .info-item strong {
            color: #0f172a;
            font-weight: 600;
            min-width: 140px;
            display: inline-block;
        }

        .info-value {
            color: #1e293b;
            font-weight: 500;
            word-break: break-word;
        }

        .button-container {
            text-align: center;
            margin: 36px 0 28px 0;
        }

        .button {
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

        .credentials-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 28px 0;
        }

        .credentials-box h3 {
            color: #0f172a;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .credential-row {
            display: flex;
            align-items: center;
            margin: 12px 0;
            flex-wrap: wrap;
            gap: 10px;
        }

        .credential-label {
            color: #0f172a;
            font-weight: 600;
            min-width: 120px;
            font-size: 15px;
        }

        .credential-value {
            background: #ffffff;
            padding: 10px 16px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #0891b2;
            font-weight: 600;
            word-break: break-all;
        }

        .warning-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 28px 0;
        }

        .warning-box p {
            color: #9a3412;
            font-size: 14px;
            line-height: 1.7;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .warning-box strong {
            color: #7c2d12;
            font-weight: 700;
        }

        .important-note {
            margin: 28px 0;
            padding: 20px 24px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .important-note p {
            margin-bottom: 14px;
            font-size: 14px;
            line-height: 1.8;
            color: #475569;
        }

        .important-note p:last-child {
            margin-bottom: 0;
        }

        .important-note strong {
            color: #0f172a;
            font-weight: 700;
        }

        .important-note ul {
            margin: 10px 0 0 20px;
            color: #475569;
            font-size: 14px;
            line-height: 1.8;
        }

        .important-note li {
            margin-bottom: 8px;
        }

        .important-note li:last-child {
            margin-bottom: 0;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 32px 0;
        }

        .fallback-link {
            margin: 28px 0;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .fallback-link p {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .fallback-link a {
            color: #0891b2;
            font-size: 12px;
            word-break: break-all;
            line-height: 1.6;
            text-decoration: none;
        }

        .fallback-link a:hover {
            text-decoration: underline;
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
            .info-item {
                flex-direction: column;
            }

            .info-item strong {
                min-width: 100%;
                margin-bottom: 4px;
            }

            .credential-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .credential-label {
                min-width: 100%;
            }

            .credential-value {
                width: 100%;
            }

            .header-logo {
                max-width: 160px;
                max-height: 80px;
            }

            .header-text-logo {
                font-size: 26px;
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

            .button {
                padding: 14px 36px;
                font-size: 15px;
            }

            .info-box,
            .credentials-box,
            .warning-box,
            .important-note,
            .fallback-link {
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

            <h1>Undangan Registrasi UMKM</h1>
            <p>Anda telah diundang untuk bergabung sebagai UMKM</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $nama_lengkap }}!</div>

            <div class="message">
                Anda telah diundang untuk bergabung sebagai UMKM di platform {{ $nama_expo }} oleh
                <strong>{{ $unit_nama }}</strong>.
            </div>

            <div class="info-box">
                <h3>Detail Undangan</h3>
                <div class="info-item">
                    <strong>Nama UMKM:</strong>
                    <span class="info-value">{{ $nama_umkm }}</span>
                </div>
                <div class="info-item">
                    <strong>Unit:</strong>
                    <span class="info-value">{{ $unit_nama }}</span>
                </div>
                <div class="info-item">
                    <strong>Email Terdaftar:</strong>
                    <span class="info-value">{{ $email }}</span>
                </div>
            </div>

            <div class="message">
                Untuk menyelesaikan registrasi dan mengaktifkan akun Anda, silakan klik tombol di bawah ini:
            </div>

            <div class="button-container">
                <a href="{{ $activation_url }}" class="button">Aktivasi Akun Sekarang</a>
            </div>

            <div class="divider"></div>

            <div class="credentials-box">
                <h3>Kredensial Login Anda</h3>
                <div class="credential-row">
                    <span class="credential-label">Username:</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Password Sementara:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="warning-box">
                <p><strong>Penting:</strong> Segera ubah password Anda setelah login pertama kali untuk keamanan akun
                    Anda.</p>
            </div>

            <div class="important-note">
                <p><strong>Catatan Penting:</strong></p>
                <ul>
                    <li>Link aktivasi ini akan kadaluarsa pada: <strong>{{ $expires_at->format('d F Y, H:i') }}
                            WIB</strong></li>
                    <li>Jika link sudah kadaluarsa, hubungi {{ $unit_nama }} untuk mengirim ulang undangan</li>
                    <li>Jika Anda tidak merasa mendaftar, abaikan email ini</li>
                </ul>
            </div>

            <div class="divider"></div>

            <div class="fallback-link">
                <p>Jika tombol di atas tidak berfungsi, klik tautan berikut:</p>
                <a href="{{ $activation_url }}">{{ $activation_url }}</a>
            </div>

            <div class="message" style="margin-top: 24px; font-size: 14px; color: #64748b; text-align: center;">
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

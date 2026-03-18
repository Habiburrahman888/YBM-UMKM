@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode OTP - {{ $nama_expo }}</title>
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
            font-weight: 400;
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

        .otp-container {
            margin: 32px 0 36px 0;
        }

        .otp-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            text-align: center;
        }

        .otp-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 3px dashed #0891b2;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
        }

        .otp-value {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15);
            font-size: 48px;
            font-weight: 800;
            color: #155e75;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin-bottom: 12px;
        }

        .otp-expiry {
            font-size: 13px;
            color: #0e7490;
            font-weight: 500;
        }

        .otp-expiry strong {
            color: #155e75;
            font-weight: 700;
        }

        .instructions-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
            border: 1px solid #e2e8f0;
        }

        .instructions-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .instructions-list {
            margin: 0;
            padding-left: 24px;
            color: #475569;
        }

        .instructions-list li {
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.6;
        }

        .instructions-list li:last-child {
            margin-bottom: 0;
        }

        .warning-box {
            background: #fff0f0;
            padding: 20px 24px;
            border-radius: 8px;
            margin: 32px 0;
        }

        .warning-box p {
            margin: 0;
            font-size: 14px;
            color: #7f1d1d;
            line-height: 1.7;
        }

        .warning-box strong {
            color: #991b1b;
            font-weight: 700;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 32px 0;
        }

        .help-text {
            margin-top: 24px;
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
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

        .email-highlight {
            font-weight: 600;
            color: #0f172a;
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 14px;
        }

        @media (max-width: 500px) {
            .header-logo {
                max-width: 160px;
                max-height: 80px;
            }

            .header-text-logo {
                font-size: 26px;
            }

            .otp-value {
                font-size: 36px;
                letter-spacing: 6px;
                padding: 16px;
            }

            .otp-box {
                padding: 20px;
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

            .header p {
                font-size: 14px;
            }

            .content {
                padding: 32px 24px;
            }

            .footer {
                padding: 28px 24px 24px 24px;
            }

            .instructions-container {
                padding: 20px;
            }

            .warning-box {
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

            <h1>Verifikasi Email Anda</h1>
            <p>Kode OTP untuk aktivasi akun</p>
        </div>

        <div class="content">
            <div class="greeting">Halo,</div>

            <div class="message">
                Terima kasih telah mendaftar! Untuk melanjutkan proses verifikasi email
                <span class="email-highlight">{{ $email }}</span>, silakan gunakan kode OTP berikut:
            </div>

            <div class="otp-container">
                <div class="otp-label">KODE VERIFIKASI OTP</div>
                <div class="otp-box">
                    <div class="otp-value">{{ $otp }}</div>
                    <div class="otp-expiry">
                        Kode ini berlaku selama <strong>{{ $expiresInMinutes }} menit</strong>
                    </div>
                </div>
            </div>

            <div class="instructions-container">
                <div class="instructions-title">Cara Menggunakan Kode OTP:</div>
                <ol class="instructions-list">
                    <li>Salin kode OTP di atas</li>
                    <li>Kembali ke halaman verifikasi</li>
                    <li>Masukkan kode 6 digit tersebut</li>
                    <li>Klik tombol "Verifikasi OTP"</li>
                </ol>
            </div>

            <div class="warning-box">
                <p>
                    <strong>Perhatian:</strong><br>
                    Jangan bagikan kode OTP ini kepada siapapun, termasuk staff kami. Kami tidak akan pernah meminta
                    kode OTP Anda melalui email, telepon, atau media sosial.
                </p>
            </div>

            <div class="divider"></div>

            <div class="help-text">
                Jika Anda tidak melakukan pendaftaran, abaikan email ini atau hubungi tim support kami
                jika Anda memiliki pertanyaan.
            </div>
        </div>

        <div class="footer">
            <p class="brand">{{ $nama_expo }}</p>
            <p style="font-size: 12px;">Email otomatis - Mohon tidak membalas email ini</p>
            <p class="copyright">
                © {{ date('Y') }} {{ $nama_expo }}. Semua hak dilindungi.
            </p>
        </div>
    </div>
</body>

</html>

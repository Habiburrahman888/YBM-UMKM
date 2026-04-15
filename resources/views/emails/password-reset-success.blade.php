@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diubah - {{ $nama_expo }}</title>
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
            margin-bottom: 24px;
            line-height: 1.7;
        }

        .details-container {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0891b2;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }

        .detail-row {
            display: flex;
            flex-wrap: wrap;
            padding: 8px 0;
            border-bottom: 1px solid rgba(8, 145, 178, 0.2);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 14px;
            color: #0e7490;
            font-weight: 600;
            width: 100px;
            min-width: 100px;
        }

        .detail-value {
            font-size: 14px;
            color: #155e75;
            flex: 1;
        }

        .status-badge {
            display: inline-block;
            background: #0891b2;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .info-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
            border: 1px solid #e2e8f0;
        }

        .info-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .info-list {
            margin: 0;
            padding-left: 24px;
            color: #475569;
        }

        .info-list li {
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.6;
        }

        .info-list li:last-child {
            margin-bottom: 0;
        }

        .security-alert {
            background: #fef2f2;
            padding: 20px 24px;
            border-radius: 8px;
            margin: 32px 0;
        }

        .security-alert p {
            margin: 0;
            font-size: 14px;
            color: #991b1b;
            line-height: 1.7;
        }

        .security-alert strong {
            color: #7f1d1d;
            font-weight: 700;
        }

        .button-container {
            text-align: center;
            margin: 36px 0 28px 0;
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

        .security-tips {
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
            border: 1px solid #e2e8f0;
        }

        .security-tips h4 {
            margin: 0 0 16px 0;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .security-tips ul {
            margin: 0;
            padding-left: 20px;
            color: #475569;
            font-size: 13px;
            line-height: 1.7;
        }

        .security-tips li {
            margin-bottom: 10px;
        }

        .security-tips li:last-child {
            margin-bottom: 0;
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

        @media (max-width: 500px) {
            .header-logo {
                max-width: 160px;
                max-height: 80px;
            }

            .header-text-logo {
                font-size: 26px;
            }

            .detail-row {
                flex-direction: column;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 4px;
            }

            .detail-value {
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

            .header p {
                font-size: 14px;
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

            .details-container,
            .info-container,
            .security-alert,
            .security-tips {
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

            <h1>Password Berhasil Diubah!</h1>
            <p>Akun Anda telah berhasil diamankan</p>

        </div>

        <div class="content">
            <div class="greeting">Halo, <strong>{{ $nama }}</strong>!</div>

            <div class="message">
                Kami ingin mengonfirmasi bahwa password untuk akun Anda telah berhasil diubah.
                Perubahan ini dilakukan pada:
            </div>

            <div class="details-container">
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu:</span>
                    <span class="detail-value">{{ $tanggal }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value"><span class="status-badge">Berhasil Diubah</span></span>
                </div>
            </div>

            <div class="info-container">
                <div class="info-title">Langkah Selanjutnya:</div>
                <ul class="info-list">
                    <li>Gunakan password baru Anda untuk login</li>
                    <li>Jangan bagikan password kepada siapapun</li>
                    <li>Gunakan password yang kuat dan unik</li>
                    <li>Aktifkan autentikasi dua faktor jika tersedia</li>
                </ul>
            </div>

            <div class="security-alert">
                <p>
                    <strong>Perhatian Keamanan:</strong><br>
                    Jika Anda <strong>TIDAK</strong> melakukan perubahan password ini, segera hubungi tim support kami
                    dan amankan akun Anda. Kemungkinan ada yang mencoba mengakses akun Anda tanpa izin.
                </p>
            </div>

            <div class="button-container">
                <a href="{{ config('app.url') }}/login" class="action-button"
                    style="color: #ffffff !important; text-decoration: none !important;">Login ke Akun Saya</a>
            </div>

            <div class="security-tips">
                <h4>Tips Keamanan Password:</h4>
                <ul>
                    <li>Gunakan kombinasi huruf besar, kecil, angka, dan simbol</li>
                    <li>Minimal 8 karakter atau lebih</li>
                    <li>Jangan gunakan informasi pribadi (nama, tanggal lahir, dll)</li>
                    <li>Gunakan password yang berbeda untuk setiap akun</li>
                </ul>
            </div>

            <div class="divider"></div>

            <div class="help-text">
                Jika Anda memiliki pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi tim support kami.
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

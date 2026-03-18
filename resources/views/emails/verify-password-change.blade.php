@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Perubahan Password - {{ $nama_expo }}</title>
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

        .verify-button-container {
            text-align: center;
            margin: 36px 0 28px 0;
        }

        .verify-button {
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

        .security-note {
            margin: 28px 0;
            padding: 20px 24px;
            background: #f0f9ff;
            border-radius: 8px;
            border: 1px solid #bae6fd;
        }

        .security-note p {
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 15px;
        }

        .security-note ul {
            list-style: none;
            padding: 0;
        }

        .security-note li {
            color: #475569;
            font-size: 14px;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
            line-height: 1.6;
        }

        .security-note li:before {
            content: "•";
            color: #0891b2;
            font-weight: bold;
            font-size: 18px;
            position: absolute;
            left: 0;
        }

        .security-note li:last-child {
            margin-bottom: 0;
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
        }

        .fallback-link p {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .link-text {
            color: #0891b2;
            font-size: 12px;
            word-break: break-all;
            font-family: 'Courier New', monospace;
            line-height: 1.6;
            background: #ffffff;
            padding: 14px;
            border-radius: 6px;
            border: 1px dashed #cbd5e1;
        }

        .request-time {
            font-size: 13px;
            color: #64748b;
            margin-top: 20px;
            padding: 16px;
            background: #fefce8;
            border-radius: 6px;
            border: 1px solid #fde047;
            font-style: italic;
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

        .footer a {
            color: #0891b2;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
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

            .verify-button {
                padding: 14px 36px;
                font-size: 15px;
            }

            .security-note,
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

            <h1>Verifikasi Perubahan Password</h1>
            <p>Konfirmasi permintaan perubahan password Anda</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $user->username }}!</div>

            <div class="message">
                Kami menerima permintaan untuk mengubah password akun {{ $nama_expo }} Anda. Untuk keamanan akun,
                kami perlu memverifikasi bahwa ini adalah permintaan yang sah dari Anda.
            </div>

            <div class="message">
                Untuk menyelesaikan proses perubahan password, silakan klik tombol verifikasi di bawah ini:
            </div>

            <div class="verify-button-container">
                <a href="{{ $verifyUrl }}" class="verify-button">Verifikasi Perubahan Password</a>
            </div>

            <div class="security-note">
                <p>Penting untuk keamanan Anda:</p>
                <ul>
                    <li>Link verifikasi ini akan kadaluarsa dalam <strong>{{ $expiresInMinutes }} menit</strong></li>
                    <li>Setelah verifikasi berhasil, password Anda akan langsung diperbarui</li>
                    <li>Anda akan diminta login ulang menggunakan password baru</li>
                </ul>
            </div>

            <div class="important-note">
                <p><strong>Bukan Anda yang mengajukan?</strong> Jika Anda tidak merasa melakukan permintaan perubahan
                    password, segera amankan akun Anda dengan:</p>
                <p><strong>1.</strong> Segera hubungi tim support {{ $nama_expo }} untuk melaporkan aktivitas
                    mencurigakan</p>
                <p><strong>2.</strong> Ganti password Anda segera jika masih bisa mengakses akun</p>
                <p><strong>3.</strong> Periksa aktivitas login terakhir di pengaturan akun Anda</p>
            </div>

            <div class="divider"></div>

            <div class="fallback-link">
                <p>Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</p>
                <div class="link-text">{{ $verifyUrl }}</div>
            </div>

            <div class="request-time">
                <strong>Perhatian:</strong> Permintaan ini dilakukan pada
                {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i:s') }} WIB.
                Jika Anda tidak mengenali aktivitas ini, segera hubungi tim support kami.
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

@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ $nama_expo }}</title>
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
            /* Warna putih untuk judul Reset Password */
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .header p {
            color: #ffffff;
            /* Warna putih untuk teks deskripsi */
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

        .info-row {
            margin: 28px 0 32px 0;
        }

        .info-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            word-break: break-all;
            background: #f8fafc;
            padding: 14px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .button-container {
            text-align: center;
            margin: 36px 0 28px 0;
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
            box-shadow: 0 4px 14px rgba(8, 145, 178, 0.3);
            letter-spacing: 0.3px;
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

        .security-alert ul {
            margin: 8px 0 0 20px;
            color: #991b1b;
            font-size: 13px;
            line-height: 1.7;
        }

        .security-alert li {
            margin-bottom: 6px;
        }

        .security-alert li:last-child {
            margin-bottom: 0;
        }

        .instructions-container {
            margin: 32px 0 24px 0;
        }

        .instructions-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .instructions-list {
            margin: 0;
            padding-left: 24px;
            color: #475569;
            font-size: 14px;
            line-height: 1.7;
        }

        .instructions-list li {
            margin-bottom: 10px;
        }

        .instructions-list li:last-child {
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

        .help-text {
            margin-top: 24px;
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            font-style: italic;
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

            .important-note,
            .security-alert,
            .fallback-link {
                padding: 16px 20px;
            }

            .instructions-list {
                padding-left: 20px;
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

            <h1>Reset Password</h1>
            <p>Permintaan pengaturan ulang kata sandi Anda</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $nama }}!</div>

            <div class="message">
                Kami menerima permintaan untuk mereset password akun Anda yang terdaftar dengan email:
            </div>

            <div class="info-row">
                <div class="info-label">Email Terdaftar</div>
                <div class="info-value">{{ $email }}</div>
            </div>

            <div class="message">
                Jika Anda yang melakukan permintaan ini, silakan klik tombol di bawah untuk membuat password baru:
            </div>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="action-button">Reset Password Sekarang</a>
            </div>

            <div class="important-note">
                <p><strong>Penting:</strong> Link reset password ini akan kadaluarsa dalam
                    <strong>{{ $expiresInMinutes }} menit</strong>. Setelah waktu tersebut, Anda perlu meminta link
                    reset password yang baru.
                </p>
                <p><strong>Catatan:</strong> Setelah berhasil mereset password, Anda dapat login menggunakan password
                    baru Anda.</p>
            </div>

            <div class="divider"></div>

            <div class="instructions-container">
                <div class="instructions-title">Langkah-langkah Reset Password:</div>
                <ol class="instructions-list">
                    <li>Klik tombol "Reset Password Sekarang" di atas</li>
                    <li>Anda akan diarahkan ke halaman reset password</li>
                    <li>Masukkan password baru Anda (minimal 8 karakter)</li>
                    <li>Konfirmasi password baru Anda</li>
                    <li>Klik tombol reset untuk menyelesaikan proses</li>
                </ol>
            </div>

            <div class="security-alert">
                <p><strong>Perhatian Keamanan:</strong></p>
                <p>Jika Anda <strong>TIDAK</strong> mengajukan permintaan reset password ini:</p>
                <ul>
                    <li>Abaikan email ini - password Anda akan tetap aman dan tidak berubah</li>
                    <li>Pastikan Anda masih dapat mengakses akun Anda</li>
                    <li>Jika merasa akun Anda tidak aman, segera hubungi tim support {{ $nama_expo }}</li>
                </ul>
            </div>

            <div class="fallback-link">
                <p>Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</p>
                <div class="link-text">{{ $resetUrl }}</div>
            </div>

            <div class="help-text">
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

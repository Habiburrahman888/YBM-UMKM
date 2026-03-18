@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - {{ $nama_expo }}</title>
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

        .info-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 24px;
            margin: 24px 0;
            border: 1px solid #e2e8f0;
        }

        .info-card-title {
            font-size: 16px;
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

        .login-info {
            margin: 28px 0;
            padding: 20px 24px;
            background: #fef3c7;
            border-radius: 8px;
            border: 1px solid #fde68a;
        }

        .login-info p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
            line-height: 1.7;
        }

        .login-info strong {
            color: #78350f;
            font-weight: 700;
        }

        .login-info ul {
            margin: 8px 0 0 20px;
            color: #92400e;
            font-size: 13px;
            line-height: 1.7;
        }

        .login-info li {
            margin-bottom: 4px;
        }

        .google-info {
            margin: 28px 0;
            padding: 20px 24px;
            background: #eff6ff;
            border-radius: 8px;
            border: 1px solid #bfdbfe;
        }

        .google-info p {
            margin: 0;
            font-size: 14px;
            color: #1e40af;
            line-height: 1.7;
        }

        .google-info strong {
            color: #1e3a8a;
            font-weight: 700;
        }

        .steps-container {
            background: #f8fafc;
            border-radius: 8px;
            padding: 24px;
            margin: 32px 0;
            border: 1px solid #e2e8f0;
        }

        .steps-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .steps-list {
            margin: 0;
            padding-left: 24px;
            color: #475569;
            font-size: 14px;
            line-height: 1.7;
        }

        .steps-list li {
            margin-bottom: 10px;
        }

        .steps-list li:last-child {
            margin-bottom: 0;
        }

        .features-grid {
            margin: 32px 0;
        }

        .feature-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            display: flex;
            gap: 12px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            flex-shrink: 0;
        }

        .feature-content {
            flex: 1;
        }

        .feature-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .feature-description {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 32px 0;
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

            .feature-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
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

            .info-card,
            .success-box,
            .security-alert,
            .login-info,
            .google-info {
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

            <h1>Selamat Datang! 🎉</h1>
            <p>Registrasi Anda berhasil dilakukan</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $nama }}!</div>

            <div class="message">
                Selamat! Akun Anda telah berhasil dibuat dan diverifikasi. Kami sangat senang Anda bergabung dengan
                {{ $nama_expo }}.
            </div>

            <div class="success-box">
                <div class="success-title">✓ Registrasi Berhasil</div>
                <div class="success-message">Akun Anda kini aktif dan siap digunakan</div>
            </div>

            <div class="info-card">
                <div class="info-card-title">📋 Informasi Akun Anda</div>

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
                        <div class="info-value">
                            <span class="highlight-box">{{ $username }}</span>
                        </div>
                    </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Unit</div>
                    <div class="info-value">{{ $nama_unit }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Kode Unit</div>
                    <div class="info-value">
                        <span class="highlight-box">{{ $kode_unit }}</span>
                    </div>
                </div>
            </div>

            @if (!$isGoogleUser)
                <div class="login-info">
                    <p>
                        <strong>Informasi Login:</strong><br><br>
                        Simpan kredensial login Anda dengan aman. Anda dapat login menggunakan:
                    </p>
                    <ul>
                        <li><strong>Username:</strong> {{ $username }}</li>
                        <li><strong>Email:</strong> {{ $email }}</li>
                        <li><strong>Password:</strong> (yang Anda buat saat registrasi)</li>
                    </ul>
                </div>
            @endif

            @if ($isGoogleUser)
                <div class="google-info">
                    <p>
                        <strong>Login dengan Google:</strong><br><br>
                        Anda dapat login menggunakan akun Google Anda tanpa perlu mengingat password tambahan.
                        Username Anda telah dibuat secara otomatis: <strong>{{ $username }}</strong>
                    </p>
                </div>
            @endif

            <div class="button-container">
                <a href="{{ url('/login') }}" class="action-button">Mulai Sekarang</a>
                <div class="button-note">Klik tombol di atas untuk mengakses dashboard Anda</div>
            </div>

            <div class="steps-container">
                <div class="steps-title">Langkah Selanjutnya:</div>
                <ol class="steps-list">
                    <li>Login ke akun Anda menggunakan kredensial yang telah dibuat</li>
                    <li>Lengkapi profil Anda untuk pengalaman yang lebih personal</li>
                    <li>Jelajahi fitur-fitur yang tersedia di dashboard</li>
                    <li>Hubungi support jika Anda memerlukan bantuan</li>
                </ol>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <div class="feature-content">
                        <div class="feature-title">Dashboard Lengkap</div>
                        <div class="feature-description">Kelola semua data Anda dalam satu tempat</div>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📁</div>
                    <div class="feature-content">
                        <div class="feature-title">Manajemen Data</div>
                        <div class="feature-description">Atur dan kelola informasi dengan mudah</div>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <div class="feature-content">
                        <div class="feature-title">Keamanan Terjamin</div>
                        <div class="feature-description">Data Anda dilindungi dengan enkripsi modern</div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="security-alert">
                <p><strong>Pengingat Keamanan:</strong></p>
                <ul>
                    <li>Jangan bagikan password Anda kepada siapapun</li>
                    <li>Gunakan password yang kuat dan unik</li>
                    <li>Logout setelah selesai menggunakan aplikasi</li>
                    <li>Laporkan aktivitas mencurigakan kepada tim support {{ $nama_expo }}</li>
                </ul>
            </div>

            <div class="message" style="margin-top: 32px; text-align: center;">
                Terima kasih telah bergabung dengan {{ $nama_expo }}! Kami berkomitmen untuk memberikan pengalaman
                terbaik untuk Anda.
            </div>

            <div class="message" style="margin-top: 24px; text-align: center; font-weight: 500;">
                Salam hangat,<br>
                <strong>Tim {{ $nama_expo }}</strong>
            </div>
        </div>

        <div class="footer">
            <p class="brand">{{ $nama_expo }}</p>
            <p style="font-size: 12px;">Butuh bantuan? Hubungi tim support kami</p>
            <p class="copyright">© {{ date('Y') }} {{ $nama_expo }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>

</html>

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
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #e0f2fe;
            padding: 24px;
            line-height: 1.6;
        }

        .wrapper {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #bae6fd;
            overflow: hidden;
        }

        /* ── Header ── */
        .header {
            background: #0891b2;
            padding: 28px 32px 24px;
            text-align: center;
        }

        .header-logo-wrap {
            display: inline-block;
            background: #ffffff;
            border-radius: 10px;
            padding: 10px 18px;
            margin-bottom: 16px;
        }

        .header-logo {
            max-width: 200px;
            max-height: 80px;
            object-fit: contain;
            display: block;
        }

        .header-brand {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .header p {
            color: #cffafe;
            font-size: 13px;
        }

        /* ── Body ── */
        .body {
            padding: 28px 32px 24px;
        }

        .greeting {
            font-size: 15px;
            font-weight: 500;
            color: #0c4a6e;
            margin-bottom: 8px;
        }

        .msg {
            font-size: 14px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        /* Perbaikan: Teks email hitam dan bold */
        .email-bold {
            font-weight: 700 !important;
            color: #000000 !important;
            font-size: 14px;
            text-decoration: none !important;
        }

        .email-bold a {
            color: #000000 !important;
            text-decoration: none !important;
        }

        /* ── OTP ── */
        .otp-label {
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .otp-area {
            text-align: center;
            margin-bottom: 24px;
        }

        .otp-code {
            font-size: 50px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #0f172a;
            font-family: 'Courier New', monospace;
            margin-bottom: 10px;
        }

        .otp-expiry {
            font-size: 13px;
            color: #64748b;
        }

        .otp-expiry b {
            color: #0f172a;
        }

        /* ── Divider ── */
        .divider-light {
            border: none;
            border-top: 1px solid #e0f2fe;
            margin: 0 0 16px;
        }

        .divider {
            border: none;
            border-top: 0.5px solid #e0f2fe;
            margin: 0 0 14px;
        }

        /* Perbaikan: Card diperpendek (padding dan margin dikurangi) */
        .info-card {
            background: #f0f9ff;
            border: 0.5px solid #bae6fd;
            border-radius: 10px;
            padding: 8px 12px; /* padding diperkecil */
            margin-bottom: 10px;
        }

        .info-title {
            font-size: 13px;
            font-weight: 600;
            color: #0c4a6e;
            margin-bottom: 4px; /* margin diperkecil */
        }

        .info-list {
            padding-left: 18px;
            color: #334155;
            margin: 0; /* tambahan untuk memastikan tidak ada margin berlebih */
        }

        .info-list li {
            font-size: 12px; /* ukuran font sedikit diperkecil */
            line-height: 1.5; /* line-height diperkecil */
            margin-bottom: 2px;
        }

        .info-list li:last-child { margin-bottom: 0; }

        /* Perbaikan: Card peringatan juga diperpendek */
        .warn-card {
            background: #fff7ed;
            border: 0.5px solid #fed7aa;
            border-radius: 10px;
            padding: 8px 12px; /* padding diperkecil */
            margin-bottom: 18px;
        }

        .warn-card p {
            font-size: 12px; /* ukuran font diperkecil */
            color: #7c2d12;
            line-height: 1.5; /* line-height diperkecil */
            margin: 0; /* hilangkan margin default paragraf */
        }

        .warn-card b { color: #9a3412; }

        /* ── Help ── */
        .help {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }

        /* ── Footer ── */
        .footer {
            background: #ffffff;
            border-top: 1px solid #e0f2fe;
            padding: 16px 32px;
            text-align: center;
        }

        .footer .brand {
            font-size: 14px;
            font-weight: 600;
            color: #0c4a6e;
            margin-bottom: 2px;
        }

        .footer .sub {
            font-size: 12px;
            color: #94a3b8;
            margin: 3px 0;
        }

        .footer .copy {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 0.5px solid #e0f2fe;
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            body { padding: 12px; }

            .header { padding: 28px 20px 28px; }
            .header h1 { font-size: 20px; }

            .body { padding: 28px 20px 24px; }

            .otp-code {
                font-size: 38px;
                letter-spacing: 8px;
            }

            .footer { padding: 16px 20px; }

            .header-logo-wrap { padding: 8px 14px; }
            .header-logo { max-width: 160px; max-height: 64px; }
        }
    </style>
</head>

<body>
    <div class="wrapper">

        {{-- ── Header ── --}}
        <div class="header">
            @if ($logo_expo)
                <div class="header-logo-wrap">
                    <img src="{{ $message->embed($logo_expo) }}"
                         alt="Logo {{ $nama_expo }}"
                         class="header-logo">
                </div>
            @else
                <div class="header-brand">{{ $nama_expo }}</div>
            @endif

            <h1>Verifikasi Email Anda</h1>
            <p>Kode OTP untuk aktivasi akun</p>
        </div>

        {{-- ── Body ── --}}
        <div class="body">
            <div class="greeting">Halo,</div>

            <div class="msg">
                Terima kasih telah mendaftar! Untuk melanjutkan proses verifikasi email
                <span class="email-bold" style="color: #000000 !important; text-decoration: none !important;">{{ $email }}</span>,
                silakan gunakan kode OTP berikut:
            </div>

            {{-- OTP --}}
            <div class="otp-label">Kode Verifikasi OTP</div>
            <div class="otp-area">
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">
                    Kode ini berlaku selama <b>{{ $expiresInMinutes }} menit</b>
                </div>
            </div>

            <hr class="divider-light">

            {{-- Instruksi (Card lebih pendek) --}}
            <div class="info-card">
                <div class="info-title">Cara Menggunakan Kode OTP:</div>
                <ol class="info-list">
                    <li>Salin kode OTP di atas</li>
                    <li>Kembali ke halaman verifikasi</li>
                    <li>Masukkan kode 6 digit tersebut</li>
                    <li>Klik tombol "Verifikasi OTP"</li>
                </ol>
            </div>

            {{-- Peringatan (Card lebih pendek) --}}
            <div class="warn-card">
                <p>
                    <b>Perhatian:</b> Jangan bagikan kode OTP ini kepada siapapun, termasuk staff kami.
                    Kami tidak akan pernah meminta kode OTP Anda melalui email, telepon, atau media sosial.
                </p>
            </div>

            <hr class="divider">

            <div class="help">
                Jika Anda tidak melakukan pendaftaran, abaikan email ini atau
                hubungi tim support kami jika Anda memiliki pertanyaan.
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="footer">
            <div class="brand">{{ $nama_expo }}</div>
            <div class="sub">Email otomatis — Mohon tidak membalas email ini</div>
            <div class="copy">© {{ date('Y') }} {{ $nama_expo }}. Semua hak dilindungi.</div>
            {{-- Mencegah Gmail clipping/hiding dengan ID unik dinamis --}}
            <div style="display:none; visibility:hidden; mso-hide:all; font-size:1px; color:#ffffff; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden;">
                {{ md5(time()) }}
            </div>
        </div>

    </div>
</body>

</html>
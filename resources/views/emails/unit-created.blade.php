@php
    $nama_expo = $nama_expo ?? config('app.name', 'YBM UMKM');
    $logo_expo = $logo_expo ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Berhasil Dibuat - {{ $nama_expo }}</title>
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

        /* ── HEADER ── */
        .header {
            background: #111827;
            padding: 32px 24px 36px 24px;
            text-align: center;
        }

        .header-logo {
            max-width: 200px;
            max-height: 80px;
            object-fit: contain;
            margin: 0 auto 20px auto;
            display: block;
        }

        .header-text-logo {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            color: #9ca3af;
            font-size: 14px;
        }

        /* ── CONTENT ── */
        .content {
            padding: 40px 32px;
        }

        .greeting {
            color: #111827;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .message {
            color: #4b5563;
            font-size: 14px;
            margin-bottom: 16px;
            line-height: 1.7;
        }

        /* Success box */
        .success-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #111827;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 24px 0 32px 0;
        }

        .success-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .success-message {
            font-size: 13px;
            color: #6b7280;
        }

        /* Section title */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin: 32px 0 12px 0;
        }

        /* Info card */
        .info-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            padding: 11px 16px;
            border-bottom: 1px solid #f3f4f6;
            background: #ffffff;
        }

        .info-row:nth-child(even) {
            background: #f9fafb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 13px;
            color: #6b7280;
            width: 150px;
            min-width: 150px;
        }

        .info-value {
            font-size: 13px;
            color: #111827;
            font-weight: 600;
            flex: 1;
            word-break: break-word;
        }

        .info-value-light {
            font-weight: 400;
            color: #374151;
        }

        /* Credential rows — bold & monospace */
        .cred-value {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            display: inline-block;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.7;
        }

        .alert-warning {
            background: #fafafa;
            border: 1px solid #d1d5db;
            border-left: 4px solid #111827;
            color: #374151;
        }

        .alert-info {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .alert strong {
            color: #111827;
            font-weight: 700;
        }

        /* Note */
        .note-box {
            margin: 20px 0;
            padding: 14px 18px;
            background: #f9fafb;
            border-left: 3px solid #9ca3af;
            border-radius: 0 6px 6px 0;
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
        }

        /* Button */
        .button-container {
            text-align: center;
            margin: 32px 0 20px;
        }

        .action-button {
            display: inline-block;
            background: #111827;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 44px;
            font-weight: 700;
            font-size: 15px;
            border-radius: 8px;
            letter-spacing: 0.3px;
        }

        .button-note {
            margin-top: 10px;
            font-size: 12px;
            color: #9ca3af;
        }

        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 32px 0;
        }

        /* Footer */
        .footer {
            background: #f9fafb;
            padding: 28px 32px 24px 32px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            color: #9ca3af;
            font-size: 12px;
            margin: 4px 0;
            line-height: 1.6;
        }

        .footer .brand {
            color: #111827;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .footer .copyright {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            color: #d1d5db;
            font-size: 11px;
        }

        @media (max-width: 500px) {
            .info-row {
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                width: 100%;
            }

            .header-logo {
                max-width: 160px;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .header {
                padding: 24px 20px 28px 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            .content {
                padding: 28px 20px;
            }

            .footer {
                padding: 24px 20px;
            }

            .action-button {
                padding: 12px 32px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">

        {{-- ── HEADER ── --}}
        <div class="header">
            @if ($logo_expo)
                <img src="{{ $message->embed($logo_expo) }}" alt="Logo {{ $nama_expo }}" class="header-logo">
            @else
                <div class="header-text-logo">{{ $nama_expo }}</div>
            @endif
            <h1>Unit Berhasil Dibuat</h1>
            <p>Notifikasi resmi dari {{ $nama_expo }}</p>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="content">

            <div class="greeting">
                Halo, {{ $unit->user?->username ?? ($unit->user?->email ?? 'Pengguna') }}!
            </div>

            <div class="message">
                Admin telah membuat unit baru atas akun Anda di <strong>{{ $nama_expo }}</strong>.
                Berikut adalah informasi lengkap unit dan kredensial login Anda.
            </div>

            <div class="success-box">
                <div class="success-title">✓ Unit Berhasil Dibuat</div>
                <div class="success-message">Unit Anda kini terdaftar dan siap digunakan dalam sistem.</div>
            </div>

            {{-- Detail Unit --}}
            <div class="section-title">Detail Unit</div>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Nama Unit</div>
                    <div class="info-value"><strong>{{ $unit->nama_unit }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kode Unit</div>
                    <div class="info-value">
                        <span class="cred-value">{{ $unit->kode_unit }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <strong>{{ $unit->is_active ? 'Aktif' : 'Non-Aktif' }}</strong>
                    </div>
                </div>
                @if ($unit->kota_nama || $unit->provinsi_nama)
                    <div class="info-row">
                        <div class="info-label">Wilayah</div>
                        <div class="info-value info-value-light">
                            {{ collect([$unit->kota_nama, $unit->provinsi_nama])->filter()->implode(', ') }}
                        </div>
                    </div>
                @endif
                @if ($unit->telepon)
                    <div class="info-row">
                        <div class="info-label">Telepon Unit</div>
                        <div class="info-value info-value-light">{{ $unit->telepon }}</div>
                    </div>
                @endif
                @if ($unit->unit_email)
                    <div class="info-row">
                        <div class="info-label">Email Unit</div>
                        <div class="info-value info-value-light">{{ $unit->unit_email }}</div>
                    </div>
                @endif
                @if ($unit->admin_nama)
                    <div class="info-row">
                        <div class="info-label">Admin Unit</div>
                        <div class="info-value info-value-light">{{ $unit->admin_nama }}</div>
                    </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Tanggal Dibuat</div>
                    <div class="info-value info-value-light">
                        {{ $unit->created_at->locale('id')->isoFormat('dddd, D MMMM Y') }},
                        pukul {{ $unit->created_at->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Kredensial Login --}}
            <div class="section-title">Kredensial Login</div>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Email / Username</div>
                    <div class="info-value">
                        <span class="cred-value">{{ $loginEmail }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Password Default</div>
                    <div class="info-value">
                        <span class="cred-value">{{ $defaultPassword }}</span>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning">
                <strong>Penting!</strong> Segera ubah password Anda setelah login pertama kali.
                Jangan bagikan informasi login ini kepada siapapun demi keamanan akun Anda.
            </div>

            <div class="note-box">
                Jika Anda merasa tidak mendaftarkan unit ini atau ada informasi yang tidak sesuai,
                segera hubungi administrator kami.
            </div>

            <div class="divider"></div>

            <div class="button-container">
                <a href="{{ url('/login') }}" class="action-button">Login ke Dashboard</a>
                <div class="button-note">Gunakan email/username dan password di atas untuk mengakses sistem</div>
            </div>

            <div class="message" style="margin-top: 24px; color: #9ca3af; font-size: 13px;">
                Jika Anda mengalami kesulitan, jangan ragu untuk menghubungi tim support
                <strong>{{ $nama_expo }}</strong>.
            </div>

        </div>

        {{-- ── FOOTER ── --}}
        <div class="footer">
            <p class="brand">{{ $nama_expo }}</p>
            <p>Email otomatis — Mohon tidak membalas email ini</p>
            <p class="copyright">© {{ date('Y') }} {{ $nama_expo }}. Semua hak dilindungi.</p>
        </div>

    </div>
</body>

</html>

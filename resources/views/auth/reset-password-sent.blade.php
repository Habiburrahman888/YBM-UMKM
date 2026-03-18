@extends('layouts.auth')

@section('title', 'Link Reset Password Terkirim')

@section('content')
    <div class="auth-header">
        <h1>Periksa Email Anda</h1>
        <p>Kami telah mengirimkan link reset password ke <strong>{{ $maskedEmail }}</strong></p>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="alert alert-success">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Email Sent Illustration --}}
    <div class="email-sent-illustration">
        <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="60" cy="60" r="60" fill="#EFF6FF" />
            <path
                d="M40 45C40 42.2386 42.2386 40 45 40H75C77.7614 40 80 42.2386 80 45V75C80 77.7614 77.7614 80 75 80H45C42.2386 80 40 77.7614 40 75V45Z"
                fill="#3B82F6" />
            <path d="M40 45L60 60L80 45" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="85" cy="35" r="8" fill="#10B981" />
            <path d="M82 35L84 37L88 33" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    {{-- Instructions --}}
    <div class="instructions-box">
        <h3>Langkah Selanjutnya:</h3>
        <ol>
            <li>Buka inbox email Anda ({{ $maskedEmail }})</li>
            <li>Cari email dengan subjek "Reset Password"</li>
            <li>Klik link yang tersedia dalam email</li>
            <li>Link akan kedaluwarsa dalam <strong><span id="countdown">{{ $countdownSeconds }}</span> detik</strong></li>
        </ol>

        <div class="help-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span>Tidak menerima email? Periksa folder spam atau gunakan tombol kirim ulang di bawah.</span>
        </div>
    </div>

    {{-- Resend Link --}}
    <div class="resend-section">
        <p>Tidak menerima email?</p>
        <button type="button" class="btn-secondary" id="resendBtn" onclick="resendResetLink()" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            <span id="resendText">Kirim Ulang (<span id="resendTimer">{{ $canResendIn }}</span>)</span>
        </button>
    </div>

    {{-- Back to Login --}}
    <div class="auth-footer">
        <p>Ingat password Anda? <a href="{{ route('login') }}">Kembali ke Login</a></p>
    </div>

    {{-- Hidden input for AJAX --}}
    <input type="hidden" id="email" value="{{ $email }}">
    <input type="hidden" id="recaptcha_token">

    {{-- reCAPTCHA Notice --}}
    @if ($recaptchaSiteKey)
        <div class="recaptcha-notice">
            This site is protected by reCAPTCHA and the Google
            <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and
            <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.
        </div>
    @endif
@endsection

@push('styles')
    <style>
        /* Prevent body scroll */
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        /* Card scroll internal */
        .auth-card {
            max-height: 95vh;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .auth-card::-webkit-scrollbar {
            display: none;
        }

        /* Compact layout */
        .auth-header {
            margin-bottom: 12px;
        }

        .auth-header h1 {
            font-size: 22px;
        }

        .auth-header p {
            font-size: 13px;
        }

        /* Illustration lebih kecil */
        .email-sent-illustration {
            margin: 16px 0;
        }

        .email-sent-illustration svg {
            width: 80px;
            height: 80px;
        }

        /* Instructions compact */
        .instructions-box {
            padding: 16px;
            margin-bottom: 16px;
        }

        .instructions-box h3 {
            font-size: 14px;
            margin: 0 0 10px 0;
        }

        .instructions-box ol li {
            margin-bottom: 8px;
            font-size: 13px;
        }

        .help-text {
            margin-top: 12px;
            padding-top: 12px;
            font-size: 12px;
        }

        /* Resend section compact */
        .resend-section {
            margin-bottom: 16px;
        }

        .resend-section p {
            margin-bottom: 8px;
            font-size: 13px;
        }

        .btn-secondary {
            padding: 10px 20px;
            font-size: 14px;
        }

        .auth-footer {
            margin-top: 8px;
            font-size: 13px;
        }

        .recaptcha-notice {
            margin-top: 8px;
            font-size: 11px;
        }

        /* Email Sent Illustration */
        .email-sent-illustration {
            display: flex;
            justify-content: center;
            margin: 32px 0;
        }

        .email-sent-illustration svg {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Instructions Box */
        .instructions-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .instructions-box h3 {
            color: #065f46;
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 16px 0;
        }

        .instructions-box ol {
            margin: 0;
            padding-left: 24px;
            color: #047857;
        }

        .instructions-box ol li {
            margin-bottom: 12px;
            line-height: 1.6;
            font-size: 14px;
        }

        .instructions-box ol li:last-child {
            margin-bottom: 0;
        }

        .instructions-box ol li strong {
            color: #065f46;
        }

        .help-text {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #bbf7d0;
            color: #047857;
            font-size: 13px;
            line-height: 1.5;
        }

        .help-text svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Resend Section */
        .resend-section {
            text-align: center;
            margin-bottom: 24px;
        }

        .resend-section p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .btn-secondary {
            padding: 12px 28px;
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover:not(:disabled) {
            background: #e5e7eb;
            border-color: #d1d5db;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-secondary svg {
            transition: transform 0.3s ease;
        }

        .btn-secondary:hover:not(:disabled) svg {
            transform: rotate(180deg);
        }

        /* Countdown highlight */
        #countdown {
            color: #dc2626;
            font-weight: 700;
            font-size: 16px;
            padding: 2px 6px;
            background: #fee2e2;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .instructions-box {
                padding: 20px;
            }

            .instructions-box h3 {
                font-size: 15px;
            }

            .instructions-box ol {
                padding-left: 20px;
            }

            .instructions-box ol li {
                font-size: 13px;
            }
        }
    </style>
@endpush

@push('scripts')
    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
        <script>
            const RECAPTCHA_SITE_KEY = '{{ $recaptchaSiteKey }}';
        </script>
    @endif

    <script>
        let linkExpirySeconds = {{ $countdownSeconds }};
        let resendCooldownSeconds = {{ $canResendIn }};
        let countdownInterval;
        let resendInterval;

        // Countdown untuk expiry link
        function startLinkCountdown() {
            const countdownElement = document.getElementById('countdown');

            countdownInterval = setInterval(function() {
                linkExpirySeconds--;

                if (linkExpirySeconds <= 0) {
                    clearInterval(countdownInterval);
                    countdownElement.textContent = '0';
                    countdownElement.style.background = '#fca5a5';

                    // Show warning
                    const instructionsBox = document.querySelector('.instructions-box');
                    const expiredWarning = document.createElement('div');
                    expiredWarning.className = 'alert alert-danger';
                    expiredWarning.style.marginTop = '16px';
                    expiredWarning.innerHTML = `
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Link reset password telah kedaluwarsa. Silakan kirim ulang.</span>
                `;
                    instructionsBox.appendChild(expiredWarning);
                } else {
                    countdownElement.textContent = linkExpirySeconds;

                    // Change color when less than 60 seconds
                    if (linkExpirySeconds <= 60) {
                        countdownElement.style.background = '#fecaca';
                    }
                }
            }, 1000);
        }

        // Countdown untuk resend cooldown
        function startResendCountdown() {
            const resendBtn = document.getElementById('resendBtn');
            const resendTimer = document.getElementById('resendTimer');

            resendInterval = setInterval(function() {
                resendCooldownSeconds--;

                if (resendCooldownSeconds <= 0) {
                    clearInterval(resendInterval);
                    resendBtn.disabled = false;
                    document.getElementById('resendText').innerHTML = `
                    <span>Kirim Ulang Link</span>
                `;
                } else {
                    resendTimer.textContent = resendCooldownSeconds;
                }
            }, 1000);
        }

        // Resend reset link
        async function resendResetLink() {
            const resendBtn = document.getElementById('resendBtn');
            const originalContent = resendBtn.innerHTML;
            const email = document.getElementById('email').value;

            // Disable button
            resendBtn.disabled = true;
            resendBtn.innerHTML = `
            <div class="spinner"></div>
            <span>Mengirim...</span>
        `;

            try {
                @if ($recaptchaSiteKey)
                    // Get reCAPTCHA token
                    const token = await new Promise((resolve, reject) => {
                        grecaptcha.ready(function() {
                            grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                                    action: 'resend_reset_link'
                                })
                                .then(resolve)
                                .catch(reject);
                        });
                    });
                @else
                    const token = null;
                @endif

                // Send AJAX request
                const response = await fetch('{{ route('password.resend') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        recaptcha_token: token
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Show success alert
                    showAlert('success', data.message);

                    // Reset timers
                    linkExpirySeconds = 480; // 8 minutes in seconds (15 * 60 - buffer)
                    resendCooldownSeconds = data.canResendIn || 60;

                    // Restart countdown
                    clearInterval(countdownInterval);
                    clearInterval(resendInterval);
                    startLinkCountdown();
                    startResendCountdown();

                    // Update button
                    resendBtn.innerHTML = originalContent;
                    document.getElementById('resendTimer').textContent = resendCooldownSeconds;
                } else {
                    showAlert('danger', data.message);
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = originalContent;
                }
            } catch (error) {
                console.error('Resend error:', error);
                showAlert('danger', 'Terjadi kesalahan. Silakan coba lagi.');
                resendBtn.disabled = false;
                resendBtn.innerHTML = originalContent;
            }
        }

        // Show alert helper
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                }
            </svg>
            <span>${message}</span>
        `;

            const authHeader = document.querySelector('.auth-header');
            authHeader.parentNode.insertBefore(alertDiv, authHeader.nextSibling);

            // Auto-hide after 5 seconds
            setTimeout(function() {
                alertDiv.style.opacity = '0';
                alertDiv.style.transition = 'opacity 0.5s ease';
                setTimeout(function() {
                    alertDiv.remove();
                }, 500);
            }, 5000);
        }

        // Auto-hide existing alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });

            // Start countdowns
            startLinkCountdown();

            if (resendCooldownSeconds > 0) {
                startResendCountdown();
            } else {
                document.getElementById('resendBtn').disabled = false;
                document.getElementById('resendText').innerHTML = '<span>Kirim Ulang Link</span>';
            }
        });
    </script>
@endpush

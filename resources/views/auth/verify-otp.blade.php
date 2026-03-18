@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('content')
    <div class="auth-header">
        <h1>Verifikasi Email Anda</h1>
        <p>Kode OTP telah dikirim ke <strong>{{ $maskedEmail }}</strong></p>
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

    @if (session('warning'))
        <div class="alert alert-warning">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    {{-- OTP Illustration --}}
    <div class="otp-illustration">
        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="50" fill="#EFF6FF" />
            <path
                d="M30 40C30 37.2386 32.2386 35 35 35H65C67.7614 35 70 37.2386 70 40V60C70 62.7614 67.7614 65 65 65H35C32.2386 65 30 62.7614 30 60V40Z"
                fill="#3B82F6" />
            <rect x="38" y="47" width="6" height="8" rx="1" fill="white" />
            <rect x="47" y="47" width="6" height="8" rx="1" fill="white" />
            <rect x="56" y="47" width="6" height="8" rx="1" fill="white" />
            <path d="M30 40L50 52L70 40" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    {{-- OTP Form --}}
    <form method="POST" action="{{ route('verify-otp') }}" id="otpForm">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label for="otp" class="form-label">Kode OTP (6 Digit)</label>
            <div class="otp-input-wrapper">
                <input type="text" class="otp-input @error('otp') is-invalid @enderror" id="otp-1" maxlength="1"
                    pattern="[0-9]" required autofocus>
                <input type="text" class="otp-input" id="otp-2" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" id="otp-3" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" id="otp-4" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" id="otp-5" maxlength="1" pattern="[0-9]" required>
                <input type="text" class="otp-input" id="otp-6" maxlength="1" pattern="[0-9]" required>
            </div>
            <input type="hidden" name="otp" id="otp-hidden">
            @error('otp')
                <span class="invalid-feedback" style="display: block; text-align: center;">{{ $message }}</span>
            @enderror
        </div>

        {{-- OTP Timer --}}
        <div class="otp-timer">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>Kode OTP berlaku <strong id="timer"></strong></span>
        </div>

        {{-- reCAPTCHA Token (Hidden) --}}
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">

        {{-- Submit Button --}}
        <button type="submit" class="btn-primary" id="submitBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>Verifikasi OTP</span>
        </button>

        {{-- Resend Button (Shown when expired) --}}
        <button type="button" class="btn-primary" id="resendBtn" onclick="resendOTP()"
            style="display: none; background: #475569;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            <span id="resendText">Kirim Ulang Kode</span>
        </button>
    </form>

    {{-- Help Text --}}
    <div class="help-box">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <span>Pastikan Anda memeriksa folder spam jika tidak menemukan email di inbox.</span>
    </div>

    {{-- Back to Register --}}
    <div class="auth-footer">
        <p>Salah email? <a href="{{ route('register') }}">Daftar ulang</a></p>
    </div>

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
        /* Compact layout */
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        .auth-card {
            max-height: 95vh;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .auth-card::-webkit-scrollbar {
            display: none;
        }

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
        .otp-illustration {
            margin: 12px 0;
        }

        .otp-illustration svg {
            width: 72px;
            height: 72px;
        }

        /* OTP input compact */
        .otp-input-wrapper {
            gap: 8px;
            margin-bottom: 6px;
        }

        .otp-input {
            width: 46px;
            height: 54px;
            font-size: 20px;
            border-radius: 10px;
        }

        /* Timer compact */
        .otp-timer {
            padding: 10px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        /* Button compact */
        .btn-primary {
            padding: 10px;
            font-size: 14px;
        }

        /* Help box compact */
        .help-box {
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 12px;
        }

        .auth-footer {
            margin-top: 8px;
            font-size: 13px;
        }

        .recaptcha-notice {
            margin-top: 8px;
            font-size: 11px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        /* OTP Illustration */
        .otp-illustration {
            display: flex;
            justify-content: center;
            margin: 24px 0;
        }

        .otp-illustration svg {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* OTP Input */
        .otp-input-wrapper {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 8px;
        }

        .otp-input {
            width: 52px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            color: #1f2937;
            transition: all 0.3s ease;
            caret-color: #3b82f6;
        }

        .otp-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .otp-input.filled {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-color: #3b82f6;
        }

        .otp-input.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
            animation: shake 0.5s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* OTP Timer */
        .otp-timer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border: 2px solid #f97316;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #9a3412;
        }

        .otp-timer svg {
            color: #f97316;
            flex-shrink: 0;
        }

        .otp-timer strong {
            color: #c2410c;
            font-weight: 700;
        }

        .otp-timer.expired {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-color: #ef4444;
        }

        .otp-timer.expired strong {
            color: #dc2626;
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

        /* Help Box */
        .help-box {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 12px 16px;
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #92400e;
            line-height: 1.5;
        }

        .help-box svg {
            flex-shrink: 0;
            margin-top: 2px;
            color: #f59e0b;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .otp-input-wrapper {
                gap: 8px;
            }

            .otp-input {
                width: 44px;
                height: 52px;
                font-size: 20px;
            }

            .otp-timer {
                font-size: 13px;
                padding: 10px;
            }

            .help-box {
                font-size: 12px;
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
        // OTP Input Handler
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otp-hidden');

        otpInputs.forEach((input, index) => {
            // Auto-focus next input
            input.addEventListener('input', function(e) {
                const value = this.value;

                // Only allow numbers
                if (!/^[0-9]$/.test(value)) {
                    this.value = '';
                    return;
                }

                // Add filled class
                this.classList.add('filled');
                this.classList.remove('is-invalid');

                // Update hidden input
                updateHiddenOTP();

                // Move to next input
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }

                // Auto-submit if all filled
                if (index === otpInputs.length - 1 && value) {
                    const allFilled = Array.from(otpInputs).every(input => input.value);
                    if (allFilled) {
                        setTimeout(() => {
                            document.getElementById('submitBtn').click();
                        }, 300);
                    }
                }
            });

            // Handle backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                    updateHiddenOTP();
                }
            });

            // Handle paste
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').trim();

                if (/^\d{6}$/.test(pastedData)) {
                    pastedData.split('').forEach((char, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = char;
                            otpInputs[i].classList.add('filled');
                        }
                    });
                    updateHiddenOTP();
                    otpInputs[5].focus();

                    // Auto-submit
                    setTimeout(() => {
                        document.getElementById('submitBtn').click();
                    }, 300);
                }
            });

            // Prevent non-numeric input
            input.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });

        function updateHiddenOTP() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpHidden.value = otp;
        }

        // PERBAIKAN: OTP Timer dengan parsing ISO 8601 yang benar
        const expiresAtString = '{{ $expiresAt }}'; // ISO 8601 format dari backend
        let expiryTime = new Date(expiresAtString).getTime();
        let timerInterval;

        console.log('OTP Expires At (from backend):', expiresAtString);
        console.log('OTP Expires At (parsed):', new Date(expiryTime));
        console.log('Current time:', new Date());

        function startTimer() {
            const timerElement = document.getElementById('timer');
            const timerBox = document.querySelector('.otp-timer');
            const resendBtn = document.getElementById('resendBtn');
            const resendText = document.getElementById('resendText');

            timerInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = expiryTime - now;

                if (distance < 0) {
                    clearInterval(timerInterval);
                    timerElement.innerHTML = '<span style="color: #dc2626;">KEDALUWARSA</span>';
                    timerBox.classList.add('expired');

                    // Show alert
                    showAlert('danger', 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.');

                    // Switch buttons: hide submit, show resend
                    document.getElementById('submitBtn').style.display = 'none';
                    resendBtn.style.display = '';
                    resendBtn.disabled = false;
                    resendText.innerHTML = 'Kirim Ulang Kode';
                } else {
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                    timerElement.textContent = timeString;

                    document.getElementById('submitBtn').style.display = '';
                    resendBtn.style.display = 'none';
                }
            }, 1000);
        }

        // Resend logic is now handled in startTimer()

        // PERBAIKAN: Resend OTP Function dengan update timer
        async function resendOTP() {
            const resendBtn = document.getElementById('resendBtn');
            const originalContent = resendBtn.innerHTML;
            const email = '{{ $email }}';

            resendBtn.disabled = true;
            resendBtn.innerHTML = `
                <div class="spinner"></div>
                <span>Mengirim...</span>
            `;

            try {
                @if ($recaptchaSiteKey)
                    const token = await new Promise((resolve, reject) => {
                        grecaptcha.ready(function() {
                            grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                                    action: 'resend_otp'
                                })
                                .then(resolve)
                                .catch(reject);
                        });
                    });
                @else
                    const token = null;
                @endif

                const response = await fetch('{{ route('resend-otp') }}', {
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
                    showAlert('success', data.message);

                    // PERBAIKAN: Update timer dengan expires_at dari response
                    if (data.expires_at) {
                        expiryTime = new Date(data.expires_at).getTime();
                        console.log('New OTP Expires At:', new Date(expiryTime));
                    } else {
                        // Fallback: 15 menit dari sekarang
                        expiryTime = new Date().getTime() + (15 * 60 * 1000);
                    }

                    clearInterval(timerInterval);
                    startTimer();

                    // Re-enable submit button, hide resend button
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = false;
                    submitBtn.style.display = '';
                    resendBtn.style.display = 'none';
                    document.querySelector('.otp-timer').classList.remove('expired');

                    // Restore otp inputs class
                    document.querySelectorAll('.otp-input').forEach(input => {
                        input.value = '';
                        input.classList.remove('filled');
                        input.classList.remove('is-invalid');
                    });
                    document.getElementById('otp-hidden').value = '';
                    document.getElementById('otp-1').focus();
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

            setTimeout(function() {
                alertDiv.style.opacity = '0';
                alertDiv.style.transition = 'opacity 0.5s ease';
                setTimeout(function() {
                    alertDiv.remove();
                }, 500);
            }, 5000);
        }

        // Form Submission with reCAPTCHA
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            // Validate OTP is complete
            const otp = otpHidden.value;
            if (otp.length !== 6) {
                e.preventDefault();
                otpInputs.forEach(input => input.classList.add('is-invalid'));
                alert('Silakan masukkan kode OTP 6 digit');
                otpInputs[0].focus();
                return;
            }

            @if ($recaptchaSiteKey)
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const originalContent = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <div class="spinner"></div>
                    <span>Memverifikasi...</span>
                `;

                grecaptcha.ready(function() {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'verify_otp'
                        })
                        .then(function(token) {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('otpForm').submit();
                        })
                        .catch(function(error) {
                            console.error('reCAPTCHA error:', error);
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalContent;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
            @endif
        });

        // Auto-hide alerts and start timers
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

            // PERBAIKAN: Start timer dengan logging
            console.log('Starting OTP timer...');
            console.log('Expiry time:', new Date(expiryTime));
            console.log('Current time:', new Date());
            console.log('Time remaining (ms):', expiryTime - new Date().getTime());

            startTimer();
        });
    </script>
@endpush

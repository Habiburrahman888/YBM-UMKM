@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div class="auth-header">
        <h1>Lupa Password?</h1>
        <p>Masukkan email Anda untuk menerima link reset password</p>
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

    {{-- Forgot Password Form --}}
    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <div class="form-input-wrapper">
                <span class="form-input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </span>
                <input type="email" class="form-input @error('email') is-invalid @enderror" id="email" name="email"
                    placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Info Box --}}
        <div class="info-box">
            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <div class="info-content">
                <strong>Catatan:</strong>
                <p>Link reset password akan dikirim ke email Anda dan berlaku selama 15 menit.</p>
            </div>
        </div>

        {{-- reCAPTCHA Token (Hidden) --}}
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">

        {{-- Submit Button --}}
        <button type="submit" class="btn-primary" id="submitBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>Kirim Link Reset Password</span>
        </button>
    </form>

    {{-- Back to Login --}}
    <div class="auth-footer">
        <p>Ingat password Anda? <a href="{{ route('login') }}">Kembali ke Login</a></p>
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

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            font-size: 13px;
            margin-bottom: 4px;
        }

        .form-input {
            padding: 8px 12px 8px 40px;
            font-size: 13px;
            height: 40px;
        }

        .info-box {
            padding: 12px;
            margin-bottom: 16px;
        }

        .info-content strong {
            font-size: 13px;
        }

        .info-content p {
            font-size: 12px;
        }

        .btn-primary {
            padding: 10px;
            font-size: 14px;
        }

        .auth-footer {
            margin-top: 12px;
            font-size: 13px;
        }

        .recaptcha-notice {
            margin-top: 8px;
            font-size: 11px;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .info-icon {
            flex-shrink: 0;
            color: #3b82f6;
            margin-top: 2px;
        }

        .info-content {
            flex: 1;
        }

        .info-content strong {
            display: block;
            color: #1e40af;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-content p {
            color: #1e3a8a;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .info-box {
                flex-direction: column;
                gap: 8px;
            }

            .info-icon {
                margin-top: 0;
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
        // Form Submission with reCAPTCHA
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            @if ($recaptchaSiteKey)
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const originalContent = submitBtn.innerHTML;

                // Validate email format
                const emailInput = document.getElementById('email');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(emailInput.value.trim())) {
                    alert('Format email tidak valid');
                    return;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
            <div class="spinner"></div>
            <span>Mengirim...</span>
        `;

                grecaptcha.ready(function() {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'forgot_password'
                        })
                        .then(function(token) {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('forgotPasswordForm').submit();
                        })
                        .catch(function(error) {
                            console.error('reCAPTCHA error:', error);
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalContent;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
            @else
                // If no reCAPTCHA, validate email format
                const emailInput = document.getElementById('email');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(emailInput.value.trim())) {
                    e.preventDefault();
                    alert('Format email tidak valid');
                    return;
                }
            @endif
        });

        // Auto-hide alerts after 5 seconds
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
        });

        // Real-time email validation (visual feedback)
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('input', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.length > 0) {
                if (emailRegex.test(email)) {
                    this.classList.remove('is-invalid');
                    this.classList.add('valid');
                } else {
                    this.classList.add('is-invalid');
                    this.classList.remove('valid');
                }
            } else {
                this.classList.remove('is-invalid', 'valid');
            }
        });
    </script>
@endpush

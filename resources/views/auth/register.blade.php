@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-header">
        <h1>Daftar Akun Baru</h1>
        <p>Buat akun Anda untuk memulai</p>
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

    {{-- Register Form --}}
    <form method="POST" action="{{ route('register') }}" id="registerForm">
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
                <span class="validation-icon" id="emailValidation"></span>
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <span class="invalid-feedback" id="emailError" style="display: none;"></span>
        </div>

        {{-- reCAPTCHA Token (Hidden) --}}
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">

        {{-- Submit Button --}}
        <button type="submit" class="btn-primary" id="submitBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            <span>Daftar Sekarang</span>
        </button>
    </form>

    {{-- Divider --}}
    <div class="divider">
        <span>Atau daftar dengan</span>
    </div>

    {{-- Google Register --}}
    <a href="{{ route('google.redirect', ['action' => 'register']) }}" class="btn-google">
        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4="
            alt="Google">
        <span>Daftar dengan Google</span>
    </a>

    {{-- Login Link --}}
    <div class="auth-footer">
        <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
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

        .btn-primary {
            padding: 10px;
            font-size: 14px;
        }

        .btn-google {
            padding: 10px;
            font-size: 13px;
        }

        .divider {
            margin: 14px 0;
            font-size: 12px;
        }

        .auth-footer {
            margin-top: 12px;
            font-size: 13px;
        }

        .recaptcha-notice {
            margin-top: 8px;
            font-size: 11px;
        }

        .validation-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            display: none;
        }

        .validation-icon.checking {
            display: block;
            color: #6b7280;
        }

        .validation-icon.valid {
            display: block;
            color: #10b981;
        }

        .validation-icon.invalid {
            display: block;
            color: #ef4444;
        }

        .form-input.checking {
            border-color: #6b7280;
        }

        .form-input.valid {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .form-input.valid:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
        let emailCheckTimeout;
        const emailInput = document.getElementById('email');
        const emailValidation = document.getElementById('emailValidation');
        const emailError = document.getElementById('emailError');

        // Real-time Email Validation
        emailInput.addEventListener('input', function() {
            clearTimeout(emailCheckTimeout);

            const email = this.value.trim();

            // Reset validation
            emailInput.classList.remove('checking', 'valid', 'is-invalid');
            emailValidation.className = 'validation-icon';
            emailValidation.innerHTML = '';
            emailError.style.display = 'none';

            // Basic email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.length === 0) {
                return;
            }

            if (!emailRegex.test(email)) {
                emailInput.classList.add('is-invalid');
                emailValidation.className = 'validation-icon invalid';
                emailValidation.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            `;
                emailError.textContent = 'Format email tidak valid';
                emailError.style.display = 'block';
                return;
            }

            // Show checking state
            emailInput.classList.add('checking');
            emailValidation.className = 'validation-icon checking';
            emailValidation.innerHTML = `
            <svg class="spinner" width="20" height="20" viewBox="0 0 20 20">
                <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="50.265" stroke-dashoffset="0">
                    <animateTransform attributeName="transform" type="rotate" from="0 10 10" to="360 10 10" dur="1s" repeatCount="indefinite"/>
                </circle>
            </svg>
        `;

            // Debounce check email availability
            emailCheckTimeout = setTimeout(function() {
                checkEmailAvailability(email);
            }, 500);
        });

        function checkEmailAvailability(email) {
            fetch(`{{ route('check-email') }}?email=${encodeURIComponent(email)}`)
                .then(response => response.json())
                .then(data => {
                    emailInput.classList.remove('checking');
                    emailValidation.className = 'validation-icon';

                    if (data.available) {
                        emailInput.classList.add('valid');
                        emailValidation.className = 'validation-icon valid';
                        emailValidation.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    `;
                        emailError.style.display = 'none';
                    } else {
                        emailInput.classList.add('is-invalid');
                        emailValidation.className = 'validation-icon invalid';
                        emailValidation.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    `;
                        emailError.textContent = data.message || 'Email sudah terdaftar';
                        emailError.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error checking email:', error);
                    emailInput.classList.remove('checking');
                    emailValidation.className = 'validation-icon';
                    emailValidation.innerHTML = '';
                });
        }

        // Form Submission with reCAPTCHA
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            @if ($recaptchaSiteKey)
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const originalContent = submitBtn.innerHTML;

                // Check if email is valid
                if (emailInput.classList.contains('is-invalid')) {
                    alert('Silakan gunakan email yang valid dan belum terdaftar.');
                    return;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
            <div class="spinner"></div>
            <span>Memproses...</span>
        `;

                grecaptcha.ready(function() {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'register'
                        })
                        .then(function(token) {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('registerForm').submit();
                        })
                        .catch(function(error) {
                            console.error('reCAPTCHA error:', error);
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalContent;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
            @else
                // If no reCAPTCHA, check email validation
                if (emailInput.classList.contains('is-invalid')) {
                    e.preventDefault();
                    alert('Silakan gunakan email yang valid dan belum terdaftar.');
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
    </script>
@endpush

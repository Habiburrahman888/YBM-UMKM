@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    {{-- Left Panel: Lottie Animation --}}
    <div class="left-panel">
        <div id="lottie-logo"></div>
        <div class="left-panel-text">
            <h2>Selamat Datang!</h2>
            <p>Portal resmi Yayasan Baitul Maal UMKM Indonesia</p>
        </div>
    </div>

    {{-- Right Panel: Login Form --}}
    <div class="right-panel">
        <div class="auth-header">
            <h1>Hai, Selamat Datang!</h1>
            <p>Silakan masuk dengan akun anda.</p>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Email or Username --}}
            <div class="form-group">
                <label for="login" class="form-label">Email atau Username <span class="required">*</span></label>
                <div class="form-input-wrapper">
                    <span class="form-input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    <input type="text" class="form-input @error('login') is-invalid @enderror" id="login" name="login"
                        placeholder="nama@gmail.com atau username" value="{{ old('login') }}" required autofocus>
                </div>
                @error('login')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">Password <span class="required">*</span></label>
                <div class="form-input-wrapper">
                    <span class="form-input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password" class="form-input @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Masukkan password anda" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="form-row">
                <div class="form-checkbox">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat saya</label>
                </div>
                <a href="{{ route('password.request') }}" class="form-link">Lupa Password?</a>
            </div>

            {{-- reCAPTCHA Token (Hidden) --}}
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            {{-- Submit Button --}}
            <button type="submit" class="btn-primary" id="submitBtn">
                <span>Masuk</span>
            </button>
        </form>

        {{-- Divider --}}
        <div class="divider">
            <span>Atau masuk dengan</span>
        </div>

        {{-- Google Login --}}
        <a href="{{ route('google.redirect', ['action' => 'login']) }}" class="btn-google">
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4="
                alt="Google">
            <span>Masuk dengan Google</span>
        </a>

        {{-- Register Link --}}
        <div class="auth-footer">
            <p>Belum memiliki akun? <a href="{{ route('register') }}">Daftar disini</a></p>
        </div>

        {{-- reCAPTCHA Notice --}}
        @if (!empty($recaptchaSiteKey))
            <div class="recaptcha-notice">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.
            </div>
        @else
            <div class="recaptcha-notice" style="color: #ef4444;">
                ⚠️ Warning: reCAPTCHA is not configured. Please set RECAPTCHA_SITE_KEY in your .env file.
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    html, body {
        height: 100%;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    /* Override auth-card default to allow two-column layout */
    .auth-card {
        max-width: 1200px !important;
        width: 96vw !important;
        min-width: 860px !important;
        padding: 0 !important;
        border-radius: 16px !important;
        overflow: hidden !important;
        display: flex !important;
        max-height: 88vh;
        min-height: 540px;
    }

    .login-wrapper {
        display: flex;
        width: 100%;
        min-height: 0;
        flex: 1;
    }

    /* ── Left Panel ── */
    .left-panel {
        width: 42%;
        flex-shrink: 0;
        background: linear-gradient(160deg, #1a3a5c 0%, #2563a8 60%, #3b82c4 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 36px;
        position: relative;
        overflow: hidden;
    }

    .left-panel::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        top: -80px;
        left: -80px;
    }

    .left-panel::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        bottom: -60px;
        right: -60px;
    }

    #lottie-logo {
        width: 260px;
        height: 260px;
        position: relative;
        z-index: 1;
    }

    .left-panel-text {
        text-align: center;
        color: white;
        position: relative;
        z-index: 1;
        margin-top: 12px;
    }

    .left-panel-text h2 {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .left-panel-text p {
        font-size: 14px;
        opacity: 0.85;
        margin: 0;
        line-height: 1.6;
    }

    /* ── Right Panel ── */
    .right-panel {
        flex: 1;
        padding: 40px 52px;
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .right-panel::-webkit-scrollbar {
        display: none;
    }

    .auth-header {
        margin-bottom: 20px;
    }

    .auth-header h1 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #1e293b;
    }

    .auth-header p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .required {
        color: #ef4444;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 5px;
    }

    .form-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-input-icon {
        position: absolute;
        left: 12px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        pointer-events: none;
    }

    .form-input {
        width: 100%;
        padding: 9px 12px 9px 40px;
        font-size: 13px;
        height: 42px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
        outline: none;
    }

    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        background: #fff;
    }

    .form-input.is-invalid {
        border-color: #ef4444;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        padding: 0;
        display: flex;
        align-items: center;
    }

    .invalid-feedback {
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
        display: block;
    }

    .form-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        font-size: 13px;
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #374151;
    }

    .form-link {
        color: #2563eb;
        text-decoration: none;
        font-size: 13px;
    }

    .form-link:hover {
        text-decoration: underline;
    }

    .btn-primary {
        width: 100%;
        padding: 11px;
        font-size: 14px;
        font-weight: 600;
        background: #1e3a5f;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: #2563a8;
    }

    .btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 14px 0;
        font-size: 12px;
        color: #94a3b8;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .btn-google {
        width: 100%;
        padding: 10px;
        font-size: 13px;
        font-weight: 500;
        background: #fff;
        color: #374151;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-google:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-google img {
        width: 18px;
        height: 18px;
    }

    .auth-footer {
        margin-top: 14px;
        text-align: center;
        font-size: 13px;
        color: #64748b;
    }

    .auth-footer a {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    .recaptcha-notice {
        margin-top: 10px;
        font-size: 11px;
        color: #94a3b8;
        text-align: center;
    }

    .recaptcha-notice a {
        color: #64748b;
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 14px;
    }

    .alert .icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .alert-danger  { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .alert-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .auth-card {
            flex-direction: column !important;
            max-height: 100vh;
            min-width: unset !important;
            width: 100vw !important;
            border-radius: 0 !important;
        }

        .left-panel {
            width: 100%;
            padding: 28px 20px;
        }

        #lottie-logo {
            width: 160px;
            height: 160px;
        }

        .left-panel-text h2 { font-size: 20px; }

        .right-panel {
            padding: 28px 24px;
        }
    }
</style>
@endpush

@push('scripts')
    {{-- Lottie Web Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

    @if (!empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
        <script>
            const RECAPTCHA_SITE_KEY = '{{ $recaptchaSiteKey }}';
        </script>
    @endif

    <script>
        // Init Lottie Animation
        lottie.loadAnimation({
            container: document.getElementById('lottie-logo'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '{{ asset("Auth/Pin code Password Protection, Secure Login animation.json") }}'
        });

        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            }
        }

        // Form Submission with reCAPTCHA v3
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            @if (!empty($recaptchaSiteKey))
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const originalContent = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <div class="spinner"></div>
                    <span>Memproses...</span>
                `;

                grecaptcha.ready(function() {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, { action: 'login' })
                        .then(function(token) {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('loginForm').submit();
                        })
                        .catch(function(error) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalContent;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
            @endif
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(function() { alert.remove(); }, 500);
                }, 5000);
            });
        });
    </script>
@endpush
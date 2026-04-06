@extends('layouts.auth')

@section('title', 'Register')

@section('content')

    <div class="auth-left">
        <div id="lottie-logo" style="width: 280px; height: 280px;" class="relative z-10 mx-auto mb-4"></div>
        <div class="auth-left-text">
            <h2>Portal YBM UMKM</h2>
            <p>Yayasan Baitul Maal UMKM Indonesia</p>
        </div>
    </div>

    {{-- ── Right Panel ── --}}
    <div class="auth-right">

        {{-- Heading --}}
        <div class="text-center mb-5">
            <h1 class="text-[22px] font-bold text-neutral-800 mb-1">Daftar Akun Baru</h1>
            <p class="text-[13px] text-neutral-500">Buat akun Anda untuk memulai.</p>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                    Email <span class="text-red-500">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    <input type="email" class="form-input pl-10 pr-3 @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                @enderror
                <span class="hidden mt-1 text-[12px] font-medium" id="emailError"></span>
            </div>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            {{-- Submit --}}
            <button type="submit" id="submitBtn" class="btn-primary">
                <span>Daftar Sekarang</span>
            </button>
        </form>

        {{-- Divider --}}
        <div class="auth-divider my-3.5">
            <span>Atau daftar dengan</span>
        </div>

        {{-- Google --}}
        <a href="{{ route('google.redirect', ['action' => 'register']) }}" class="btn-google">
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4="
                alt="Google" class="w-[18px] h-[18px] shrink-0">
            <span>Daftar dengan Google</span>
        </a>

        {{-- Login Link --}}
        <div class="mt-3.5 text-center text-[13px] text-neutral-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary-600 font-semibold hover:underline">Masuk sekarang</a>
        </div>

        {{-- reCAPTCHA Notice --}}
        @if (!empty($recaptchaSiteKey))
            <div class="mt-2.5 text-[11px] text-neutral-400 text-center leading-relaxed">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" target="_blank"
                    class="text-neutral-500 hover:underline">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank" class="text-neutral-500 hover:underline">Terms
                    of Service</a> apply.
            </div>
        @else
            <div class="mt-2.5 text-[11px] text-red-500 text-center">
                ⚠️ Warning: reCAPTCHA is not configured. Please set RECAPTCHA_SITE_KEY in your .env file.
            </div>
        @endif

    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

    @if (!empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
        <script>
            const RECAPTCHA_SITE_KEY = '{{ $recaptchaSiteKey }}';
        </script>
    @endif

    <script>
        // Lottie
        const lottieContainer = document.getElementById('lottie-logo');
        if (lottieContainer) {
            lottie.loadAnimation({
                container: lottieContainer,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: '{{ asset('Auth/auth-logo.json') }}'
            });
        }

        // Real-time email validation
        let emailCheckTimeout;
        const emailInput = document.getElementById('email');
        const emailFeedback = document.getElementById('emailError');

        emailInput.addEventListener('input', function() {
            clearTimeout(emailCheckTimeout);
            const email = this.value.trim();

            emailInput.classList.remove('is-invalid');
            emailFeedback.className = 'hidden mt-1 text-[12px] font-medium';
            emailFeedback.textContent = '';

            if (!email) return;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailInput.classList.add('is-invalid');
                emailFeedback.className = 'block mt-1 text-[12px] font-medium text-red-500';
                emailFeedback.textContent = 'Format email tidak valid';
                return;
            }

            emailFeedback.className = 'block mt-1 text-[12px] font-medium text-neutral-400';
            emailFeedback.textContent = 'Memeriksa email...';

            emailCheckTimeout = setTimeout(() => checkEmailAvailability(email), 500);
        });

        function checkEmailAvailability(email) {
            fetch(`{{ route('check-email') }}?email=${encodeURIComponent(email)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.available) {
                        emailInput.classList.remove('is-invalid');
                        emailFeedback.className = 'block mt-1 text-[12px] font-medium text-green-500';
                        emailFeedback.textContent = 'Email tersedia';
                    } else {
                        emailInput.classList.add('is-invalid');
                        emailFeedback.className = 'block mt-1 text-[12px] font-medium text-red-500';
                        emailFeedback.textContent = data.message || 'Email sudah terdaftar';
                    }
                })
                .catch(() => {
                    emailFeedback.className = 'hidden mt-1 text-[12px] font-medium';
                });
        }

        // Form submit + reCAPTCHA
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            @if (!empty($recaptchaSiteKey))
                e.preventDefault();
                if (emailInput.classList.contains('is-invalid')) {
                    alert('Silakan gunakan email yang valid dan belum terdaftar.');
                    return;
                }
                const btn = document.getElementById('submitBtn');
                const orig = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = `<div class="spinner"></div><span>Memproses...</span>`;
                grecaptcha.ready(() => {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'register'
                        })
                        .then(token => {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('registerForm').submit();
                        })
                        .catch(() => {
                            btn.disabled = false;
                            btn.innerHTML = orig;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
            @else
                if (emailInput.classList.contains('is-invalid')) {
                    e.preventDefault();
                    alert('Silakan gunakan email yang valid dan belum terdaftar.');
                }
            @endif
        });

        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.alert').forEach(el => {
                setTimeout(() => {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 5000);
            });
        });
    </script>
@endpush

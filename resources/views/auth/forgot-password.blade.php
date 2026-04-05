@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')

    {{-- ── Left Panel ── --}}
    <div class="auth-left">
        <div id="lottie-logo" class="w-[220px] h-[220px] relative z-10"></div>
        <div class="auth-left-text">
            <h2>Portal YBM UMKM</h2>
            <p>Yayasan Baitul Maal UMKM Indonesia</p>
        </div>
    </div>

    {{-- ── Right Panel ── --}}
    <div class="auth-right">

        {{-- Heading --}}
        <div class="text-center mb-5">
            <h1 class="text-[22px] font-bold text-neutral-800 mb-1">Lupa Password?</h1>
            <p class="text-[13px] text-neutral-500">Masukkan email Anda untuk menerima link reset password.</p>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
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

        {{-- Cooldown Banner --}}
        @if (!empty($cooldownSeconds) && $cooldownSeconds > 0)
            <div class="alert alert-warning" id="cooldown-banner">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    Mohon tunggu <strong id="cooldown-timer">{{ $cooldownSeconds }}</strong> detik sebelum meminta link
                    reset password lagi.
                </span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
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
                        name="email" placeholder="nama@email.com" value="{{ old('email', $prefillEmail ?? '') }}" required
                        autofocus>
                </div>
                @error('email')
                    <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Info Box --}}
            <div
                class="flex items-start gap-2.5 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg px-3.5 py-3 mb-4">
                <svg class="w-[18px] h-[18px] shrink-0 text-blue-600 mt-px" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <div>
                    <strong class="block text-[12px] font-semibold text-blue-800 mb-0.5">Catatan:</strong>
                    <p class="text-[12px] text-blue-900 leading-relaxed m-0">Link reset password akan dikirim ke email Anda
                        dan berlaku selama 15 menit.</p>
                </div>
            </div>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            {{-- Submit --}}
            <button type="submit" class="btn-primary" id="submitBtn"
                {{ !empty($cooldownSeconds) && $cooldownSeconds > 0 ? 'disabled' : '' }}>
                <span id="submitBtnText">
                    @if (!empty($cooldownSeconds) && $cooldownSeconds > 0)
                        Tunggu <span id="btn-timer">{{ $cooldownSeconds }}</span> detik...
                    @else
                        Kirim Link Reset Password
                    @endif
                </span>
            </button>
        </form>

        {{-- Back to Login --}}
        <div class="mt-3.5 text-center text-[13px] text-neutral-500">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="text-primary-600 font-semibold hover:underline">Kembali ke Login</a>
        </div>

        {{-- reCAPTCHA Notice --}}
        @if (!empty($recaptchaSiteKey))
            <div class="mt-2.5 text-[11px] text-neutral-400 text-center leading-relaxed">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" target="_blank"
                    class="text-neutral-500 hover:underline">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank"
                    class="text-neutral-500 hover:underline">Terms of Service</a> apply.
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
        // ── Lottie ──
        lottie.loadAnimation({
            container: document.getElementById('lottie-logo'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '{{ asset('Auth/Pin code Password Protection, Secure Login animation.json') }}'
        });

        // ── Cooldown Countdown ──
        const INITIAL_COOLDOWN = {{ !empty($cooldownSeconds) && $cooldownSeconds > 0 ? (int) $cooldownSeconds : 0 }};
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const timerEl = document.getElementById('cooldown-timer');
        const btnTimerEl = document.getElementById('btn-timer');
        const cooldownBanner = document.getElementById('cooldown-banner');

        function enableForm() {
            submitBtn.disabled = false;
            submitBtnText.textContent = 'Kirim Link Reset Password';
            if (cooldownBanner) {
                cooldownBanner.style.transition = 'opacity 0.4s ease';
                cooldownBanner.style.opacity = '0';
                setTimeout(() => cooldownBanner?.remove(), 400);
            }
        }

        if (INITIAL_COOLDOWN > 0) {
            let remaining = INITIAL_COOLDOWN;
            submitBtn.disabled = true;
            const interval = setInterval(() => {
                remaining--;
                if (timerEl) timerEl.textContent = remaining;
                if (btnTimerEl) btnTimerEl.textContent = remaining;
                if (remaining <= 0) {
                    clearInterval(interval);
                    enableForm();
                }
            }, 1000);
        }

        // ── Form Submit + reCAPTCHA ──
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }

            const emailInput = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            @if (!empty($recaptchaSiteKey))
                e.preventDefault();
                if (!emailRegex.test(emailInput.value.trim())) {
                    alert('Format email tidak valid');
                    return;
                }
                const originalHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="spinner"></div><span>Mengirim...</span>';
                grecaptcha.ready(() => {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'forgot_password'
                        })
                        .then(token => {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('forgotPasswordForm').submit();
                        })
                        .catch(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalHTML;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
            @else
                if (!emailRegex.test(emailInput.value.trim())) {
                    e.preventDefault();
                    alert('Format email tidak valid');
                }
            @endif
        });

        // ── Auto-dismiss alerts ──
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.alert').forEach(el => {
                if (el.id === 'cooldown-banner') return;
                setTimeout(() => {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 5000);
            });
        });

        // ── Real-time email validation ──
        document.getElementById('email').addEventListener('input', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value.trim().length > 0) {
                this.classList.toggle('is-invalid', !emailRegex.test(this.value.trim()));
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
@endpush

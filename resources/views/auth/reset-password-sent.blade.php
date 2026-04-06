@extends('layouts.auth')

@section('title', 'Link Reset Password Terkirim')

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
        <div class="mb-3.5">
            <h1 class="text-[22px] font-bold text-neutral-800 mb-1">Periksa Email Anda</h1>
            <p class="text-[13px] text-neutral-500">
                Kami telah mengirimkan link reset password ke <strong class="text-neutral-700">{{ $maskedEmail }}</strong>
            </p>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-flash">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error alert-flash">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-flash">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-flash">
                <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Instructions Box --}}
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-[10px] px-4 py-3.5 mb-3.5">
            <h3 class="text-[13px] font-semibold text-green-900 mb-2.5">Langkah Selanjutnya:</h3>
            <ol class="pl-5 text-green-800 flex flex-col gap-1.5">
                <li class="text-[12px] leading-relaxed">Buka inbox email Anda ({{ $maskedEmail }})</li>
                <li class="text-[12px] leading-relaxed">Cari email dengan subjek "Reset Password"</li>
                <li class="text-[12px] leading-relaxed">Klik link yang tersedia dalam email</li>
                <li class="text-[12px] leading-relaxed">
                    Link akan kedaluwarsa dalam
                    <strong>
                        <span id="countdown"
                            class="text-red-600 font-bold bg-red-100 rounded px-1.5 py-px font-[tabular-nums] tracking-wide transition-colors duration-300">
                            15:00
                        </span>
                    </strong>
                </li>
            </ol>
            <div
                class="flex items-start gap-1.5 mt-2.5 pt-2.5 border-t border-green-200 text-green-700 text-[11px] leading-relaxed">
                <svg class="shrink-0 mt-px" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Tidak menerima email? Periksa folder spam atau gunakan tombol kirim ulang di bawah.</span>
            </div>
        </div>

        {{-- Resend Section --}}
        <div class="text-center mb-3">
            <p class="text-[13px] text-neutral-500 mb-2">Tidak menerima email?</p>
            <button type="button" id="resendBtn" onclick="resendResetLink()" disabled
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-neutral-700 border border-slate-200 rounded-[10px] text-[13px] font-medium cursor-pointer transition-all duration-200 hover:bg-slate-200 hover:border-slate-300 disabled:opacity-50 disabled:cursor-not-allowed [&:not(:disabled)_svg]:hover:rotate-180">
                <svg class="transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" width="16"
                    height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                </svg>
                <span id="resendText">Kirim Ulang (<span id="resendTimer">{{ $canResendIn }}</span>s)</span>
            </button>
        </div>

        {{-- Back to Login --}}
        <div class="text-center text-[13px] text-neutral-500">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="text-primary-600 font-semibold hover:underline">Kembali ke Login</a>
        </div>

        <input type="hidden" id="email" value="{{ $email }}">

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
            path: '{{ $setting && $setting->logo_expo ? $setting->logo_expo_url : asset('Auth/auth-logo.json') }}'
        });

        let linkExpirySeconds = {{ $countdownSeconds }};
        let resendCooldownSeconds = {{ $canResendIn }};
        let countdownInterval, resendInterval;

        function formatTime(s) {
            return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0');
        }

        function setResendCooldown(s) {
            document.getElementById('resendText').innerHTML =
                'Kirim Ulang (<span id="resendTimer">' + s + '</span>s)';
        }

        function setResendReady() {
            document.getElementById('resendText').innerHTML = 'Kirim Ulang Link';
            document.getElementById('resendBtn').disabled = false;
        }

        function startLinkCountdown() {
            const el = document.getElementById('countdown');
            el.textContent = formatTime(linkExpirySeconds);

            countdownInterval = setInterval(() => {
                linkExpirySeconds--;
                if (linkExpirySeconds <= 0) {
                    clearInterval(countdownInterval);
                    el.textContent = '0:00';
                    el.classList.add('!bg-red-200', '!text-red-900');
                    showAlert('danger', 'Link reset password telah kedaluwarsa. Silakan kirim ulang.');
                } else {
                    el.textContent = formatTime(linkExpirySeconds);
                    if (linkExpirySeconds <= 60) el.classList.add('!bg-red-200');
                }
            }, 1000);
        }

        function startResendCountdown() {
            document.getElementById('resendBtn').disabled = true;
            resendInterval = setInterval(() => {
                resendCooldownSeconds--;
                if (resendCooldownSeconds <= 0) {
                    clearInterval(resendInterval);
                    setResendReady();
                } else {
                    const t = document.getElementById('resendTimer');
                    if (t) t.textContent = resendCooldownSeconds;
                }
            }, 1000);
        }

        async function resendResetLink() {
            const btn = document.getElementById('resendBtn');
            const email = document.getElementById('email').value;

            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<div class="spinner"></div><span>Mengirim...</span>';

            try {
                @if (!empty($recaptchaSiteKey))
                    const token = await new Promise((resolve, reject) => {
                        grecaptcha.ready(() => {
                            grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                                    action: 'resend_reset_link'
                                })
                                .then(resolve).catch(reject);
                        });
                    });
                @else
                    const token = null;
                @endif

                const response = await fetch('{{ route('password.resend') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email,
                        recaptcha_token: token
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('success', data.message);
                    clearInterval(countdownInterval);
                    clearInterval(resendInterval);

                    linkExpirySeconds = 900;
                    resendCooldownSeconds = data.canResendIn || 60;

                    const countdownEl = document.getElementById('countdown');
                    countdownEl.classList.remove('!bg-red-200', '!text-red-900');

                    setResendCooldown(resendCooldownSeconds);
                    startLinkCountdown();
                    startResendCountdown();
                } else {
                    showAlert('danger', data.message);
                    resendCooldownSeconds > 0 ? setResendCooldown(resendCooldownSeconds) : setResendReady();
                    btn.disabled = resendCooldownSeconds > 0;
                }
            } catch {
                showAlert('danger', 'Terjadi kesalahan. Silakan coba lagi.');
                resendCooldownSeconds > 0 ? setResendCooldown(resendCooldownSeconds) : setResendReady();
                btn.disabled = resendCooldownSeconds > 0;
            }
        }

        // ── Alert helper ──
        function showAlert(type, message) {
            const map = {
                success: 'alert-success',
                danger: 'alert-error',
                warning: 'alert-warning',
                info: 'alert-info',
            };
            const icons = {
                success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                danger: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            };

            document.querySelectorAll('.alert-dynamic').forEach(el => el.remove());

            const div = document.createElement('div');
            div.className = `alert ${map[type] ?? 'alert-error'} alert-dynamic`;
            div.innerHTML =
                `<svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">${icons[type] ?? icons.danger}</svg><span>${message}</span>`;

            const heading = document.querySelector('.auth-right > div');
            heading.insertAdjacentElement('afterend', div);

            setTimeout(() => {
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 500);
            }, 5000);
        }

        // ── Init ──
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.alert-flash').forEach(el => {
                setTimeout(() => {
                    el.style.opacity = '0';
                    el.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => el.remove(), 500);
                }, 5000);
            });

            startLinkCountdown();
            resendCooldownSeconds > 0 ? startResendCountdown() : setResendReady();
        });
    </script>
@endpush

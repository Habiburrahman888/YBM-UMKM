@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

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
        <div class="text-center mb-4">
            <div
                class="inline-flex items-center justify-center w-[72px] h-[72px] rounded-full bg-blue-50 text-blue-600 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <h1 class="text-[22px] font-bold text-neutral-800 mb-1">Cek Email Anda</h1>
            <p class="text-[13px] text-neutral-500">Masukkan kode OTP yang dikirim ke <strong
                    class="text-neutral-700">{{ $email }}</strong></p>
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

        {{-- Steps --}}
        <div class="flex flex-col gap-2 mb-4">
            <div class="flex items-center gap-2.5">
                <div
                    class="w-[22px] h-[22px] min-w-[22px] rounded-full bg-[#1e3a5f] text-white flex items-center justify-center text-[11px] font-bold">
                    1</div>
                <span class="text-[12px] text-neutral-600">Buka email dengan akun <strong
                        class="text-neutral-800">{{ $email }}</strong></span>
            </div>
            <div class="flex items-center gap-2.5">
                <div
                    class="w-[22px] h-[22px] min-w-[22px] rounded-full bg-[#1e3a5f] text-white flex items-center justify-center text-[11px] font-bold">
                    2</div>
                <span class="text-[12px] text-neutral-600">Salin <strong class="text-neutral-800">kode OTP</strong> yang
                    tertera di email</span>
            </div>
            <div class="flex items-center gap-2.5">
                <div
                    class="w-[22px] h-[22px] min-w-[22px] rounded-full bg-[#1e3a5f] text-white flex items-center justify-center text-[11px] font-bold">
                    3</div>
                <span class="text-[12px] text-neutral-600">Masukkan kode OTP di kolom di bawah ini</span>
            </div>
        </div>

        {{-- OTP Form --}}
        <form method="POST" action="{{ route('verify-otp') }}" id="otpForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- OTP Inputs --}}
            <div class="mb-3">
                <label class="block text-[13px] font-medium text-neutral-700 mb-2 text-center">
                    Masukkan Kode OTP (6 Digit)
                </label>
                <div class="flex gap-2 justify-center">
                    <input type="text" class="otp-input @error('otp') is-invalid @enderror" id="otp-1" maxlength="1"
                        pattern="[0-9]" required autofocus inputmode="numeric">
                    <input type="text" class="otp-input" id="otp-2" maxlength="1" pattern="[0-9]" required
                        inputmode="numeric">
                    <input type="text" class="otp-input" id="otp-3" maxlength="1" pattern="[0-9]" required
                        inputmode="numeric">
                    <input type="text" class="otp-input" id="otp-4" maxlength="1" pattern="[0-9]" required
                        inputmode="numeric">
                    <input type="text" class="otp-input" id="otp-5" maxlength="1" pattern="[0-9]" required
                        inputmode="numeric">
                    <input type="text" class="otp-input" id="otp-6" maxlength="1" pattern="[0-9]" required
                        inputmode="numeric">
                </div>
                <input type="hidden" name="otp" id="otp-hidden">
                @error('otp')
                    <span class="block mt-1.5 text-[12px] text-red-500 text-center">{{ $message }}</span>
                @enderror
            </div>

            {{-- Timer --}}
            <div class="flex items-center justify-center gap-1.5 text-[12px] text-neutral-500 mb-3" id="verifyTimerBox">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="text-neutral-400 shrink-0">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Kode berlaku <strong class="text-neutral-800" id="timer"></strong> lagi</span>
            </div>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            {{-- Submit --}}
            <button type="submit" class="btn-primary mb-2" id="submitBtn">
                <span>Verifikasi OTP</span>
            </button>

            {{-- Resend --}}
            <button type="button"
                class="hidden w-full h-10 flex items-center justify-center gap-2 bg-white border border-neutral-200 rounded-lg text-[13px] font-medium text-neutral-700 hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-200 cursor-pointer"
                id="resendBtn" onclick="resendOTP()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                </svg>
                <span id="resendText">Kirim Ulang Kode</span>
            </button>
        </form>

        {{-- Divider --}}
        <div class="my-3 h-px bg-neutral-100"></div>

        {{-- Back --}}
        <div class="text-center text-[13px] text-neutral-500">
            Salah email?
            <a href="{{ route('register') }}" class="text-primary-600 font-semibold hover:underline">Daftar ulang</a>
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
        // Lottie
        lottie.loadAnimation({
            container: document.getElementById('lottie-logo'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '{{ asset('Auth/Pin code Password Protection, Secure Login animation.json') }}'
        });

        // OTP Input Handler
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otp-hidden');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (!/^[0-9]$/.test(this.value)) {
                    this.value = '';
                    return;
                }
                this.classList.add('otp-filled');
                this.classList.remove('is-invalid');
                updateHiddenOTP();
                if (index < otpInputs.length - 1) otpInputs[index + 1].focus();
                if (index === otpInputs.length - 1 && Array.from(otpInputs).every(i => i.value)) {
                    setTimeout(() => document.getElementById('submitBtn').click(), 300);
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('otp-filled');
                    updateHiddenOTP();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasted = e.clipboardData.getData('text').trim();
                if (/^\d{6}$/.test(pasted)) {
                    pasted.split('').forEach((char, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = char;
                            otpInputs[i].classList.add('otp-filled');
                        }
                    });
                    updateHiddenOTP();
                    otpInputs[5].focus();
                    setTimeout(() => document.getElementById('submitBtn').click(), 300);
                }
            });

            input.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key)) e.preventDefault();
            });
        });

        function updateHiddenOTP() {
            otpHidden.value = Array.from(otpInputs).map(i => i.value).join('');
        }

        // Timer
        const expiresAtString = '{{ $expiresAt }}';
        let expiryTime = new Date(expiresAtString).getTime();
        let timerInterval;

        function startTimer() {
            const timerEl = document.getElementById('timer');
            const timerBox = document.getElementById('verifyTimerBox');
            const submitBtn = document.getElementById('submitBtn');
            const resendBtn = document.getElementById('resendBtn');

            timerInterval = setInterval(() => {
                const distance = expiryTime - Date.now();

                if (distance < 0) {
                    clearInterval(timerInterval);
                    timerEl.innerHTML = '<span class="text-red-600">kedaluwarsa</span>';
                    timerBox.classList.add('text-red-500');
                    showAlert('error', 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.');
                    submitBtn.style.display = 'none';
                    resendBtn.classList.remove('hidden');
                } else {
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    timerEl.textContent = `${minutes} menit ${String(seconds).padStart(2, '0')} detik`;
                }
            }, 1000);
        }

        // Resend OTP
        async function resendOTP() {
            const resendBtn = document.getElementById('resendBtn');
            const originalHTML = resendBtn.innerHTML;
            const email = '{{ $email }}';

            resendBtn.disabled = true;
            resendBtn.innerHTML =
                `<div class="spinner" style="border-color:rgba(0,0,0,.15);border-top-color:#475569;"></div><span>Mengirim...</span>`;

            try {
                @if (!empty($recaptchaSiteKey))
                    const token = await new Promise((resolve, reject) => {
                        grecaptcha.ready(() => {
                            grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                                    action: 'resend_otp'
                                })
                                .then(resolve).catch(reject);
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
                        email,
                        recaptcha_token: token
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('success', data.message);
                    expiryTime = data.expires_at ?
                        new Date(data.expires_at).getTime() :
                        Date.now() + 15 * 60 * 1000;
                    clearInterval(timerInterval);

                    const timerBox = document.getElementById('verifyTimerBox');
                    timerBox.classList.remove('text-red-500');

                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = false;
                    submitBtn.style.display = '';
                    resendBtn.classList.add('hidden');

                    otpInputs.forEach(i => {
                        i.value = '';
                        i.classList.remove('otp-filled', 'is-invalid');
                    });
                    otpHidden.value = '';
                    otpInputs[0].focus();
                    startTimer();
                } else {
                    showAlert('error', data.message);
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = originalHTML;
                }
            } catch {
                showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                resendBtn.disabled = false;
                resendBtn.innerHTML = originalHTML;
            }
        }

        // Alert helper — consistent with password-sent.blade.php
        function showAlert(type, message) {
            const map = {
                success: 'alert-success',
                error: 'alert-error',
                warning: 'alert-warning',
                info: 'alert-info',
            };
            const icons = {
                success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            };

            document.querySelectorAll('.alert-dynamic').forEach(el => el.remove());

            const div = document.createElement('div');
            div.className = `alert ${map[type] ?? 'alert-error'} alert-dynamic`;
            div.innerHTML =
                `<svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">${icons[type] ?? icons.error}</svg><span>${message}</span>`;

            const right = document.querySelector('.auth-right');
            right.insertBefore(div, right.querySelector('form'));

            setTimeout(() => {
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 500);
            }, 5000);
        }

        // Form submit + reCAPTCHA
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            if (otpHidden.value.length !== 6) {
                e.preventDefault();
                otpInputs.forEach(i => i.classList.add('is-invalid'));
                otpInputs[0].focus();
                return;
            }

            @if (!empty($recaptchaSiteKey))
                e.preventDefault();
                const btn = document.getElementById('submitBtn');
                const orig = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = `<div class="spinner"></div><span>Memverifikasi...</span>`;
                grecaptcha.ready(() => {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'verify_otp'
                        })
                        .then(token => {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('otpForm').submit();
                        })
                        .catch(() => {
                            btn.disabled = false;
                            btn.innerHTML = orig;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
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

            startTimer();
        });
    </script>

@endpush

@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

    {{-- ── Left Panel ── --}}
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
            <h1 class="text-[22px] font-bold text-neutral-800 mb-1">Buat Password Baru</h1>
            <p class="text-[13px] text-neutral-500">
                Masukkan password baru untuk akun <strong class="text-neutral-700">{{ $maskedEmail }}</strong>
            </p>
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
        <form method="POST" action="{{ route('password.update', $uuid) }}" id="resetPasswordForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- New Password --}}
            <div class="mb-3">
                <label for="password" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password" class="form-input pl-10 pr-10 @error('password') is-invalid @enderror"
                        id="password" name="password" placeholder="Minimal 8 karakter" required autofocus>
                    <button type="button" onclick="togglePassword('password', 'eyeIconPassword')" class="input-icon-right">
                        <svg id="eyeIconPassword" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                {{-- Password Strength --}}
                <div class="hidden mt-2" id="passwordStrength">
                    <div class="flex gap-1 mb-1" id="strengthBars">
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                    </div>
                    <span class="text-[11px] font-medium text-neutral-500" id="strengthText"></span>
                </div>

                @error('password')
                    <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label for="password_confirmation" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password"
                        class="form-input pl-10 pr-10 @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                        required>
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIconConfirmation')"
                        class="input-icon-right">
                        <svg id="eyeIconConfirmation" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <span class="hidden mt-1 text-[12px] font-medium text-red-500" id="confirmationError"></span>
                @error('password_confirmation')
                    <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password Requirements --}}
            <div
                class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-[10px] px-3.5 py-3 mb-3.5">
                <h3 class="text-[12px] font-semibold text-neutral-700 mb-2">Password harus memenuhi:</h3>
                <ul class="flex flex-col gap-1">
                    <li id="req-length"
                        class="flex items-center gap-1.5 text-[12px] text-neutral-400 transition-colors duration-200">
                        <svg class="req-icon w-[14px] h-[14px] shrink-0" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        <span>Minimal 8 karakter</span>
                    </li>
                    <li id="req-case"
                        class="flex items-center gap-1.5 text-[12px] text-neutral-400 transition-colors duration-200">
                        <svg class="req-icon w-[14px] h-[14px] shrink-0" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        <span>Huruf besar dan kecil</span>
                    </li>
                    <li id="req-number"
                        class="flex items-center gap-1.5 text-[12px] text-neutral-400 transition-colors duration-200">
                        <svg class="req-icon w-[14px] h-[14px] shrink-0" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        <span>Mengandung angka</span>
                    </li>
                    <li id="req-special"
                        class="flex items-center gap-1.5 text-[12px] text-neutral-400 transition-colors duration-200">
                        <svg class="req-icon w-[14px] h-[14px] shrink-0" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                        <span>Karakter khusus (!@#$%^&*)</span>
                    </li>
                </ul>
            </div>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            {{-- Submit --}}
            <button type="submit" class="btn-primary" id="submitBtn">
                <span>Reset Password</span>
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

        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden ?
                `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>` :
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>`;
        }

        // Password strength
        const passwordInput = document.getElementById('password');
        const strengthWrapper = document.getElementById('passwordStrength');
        const strengthBarsEl = document.getElementById('strengthBars');
        const strengthText = document.getElementById('strengthText');

        const reqLength = document.getElementById('req-length');
        const reqCase = document.getElementById('req-case');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        const STRENGTH_COLORS = ['#ef4444', '#ef4444', '#f59e0b', '#10b981', '#10b981'];
        const STRENGTH_LABELS = ['', 'Password lemah', 'Password lemah', 'Password sedang', 'Password kuat'];

        passwordInput.addEventListener('input', function() {
            const val = this.value;

            if (!val.length) {
                strengthWrapper.classList.add('hidden');
                resetRequirements();
                return;
            }

            strengthWrapper.classList.remove('hidden');

            const checks = {
                length: val.length >= 8,
                upper: /[A-Z]/.test(val),
                lower: /[a-z]/.test(val),
                number: /[0-9]/.test(val),
                special: /[^a-zA-Z0-9]/.test(val),
            };

            updateRequirement(reqLength, checks.length);
            updateRequirement(reqCase, checks.upper && checks.lower);
            updateRequirement(reqNumber, checks.number);
            updateRequirement(reqSpecial, checks.special);

            const strength = [checks.length, checks.upper && checks.lower, checks.number, checks.special]
                .filter(Boolean).length;

            const bars = strengthBarsEl.querySelectorAll('.strength-bar');
            bars.forEach((bar, i) => {
                bar.style.background = i < strength ? STRENGTH_COLORS[strength] : '#e5e7eb';
            });

            strengthText.textContent = STRENGTH_LABELS[strength] || '';
            strengthText.style.color = STRENGTH_COLORS[strength] || '#6b7280';

            checkPasswordMatch();
        });

        function updateRequirement(el, isValid) {
            const icon = el.querySelector('.req-icon');
            if (isValid) {
                el.classList.remove('text-neutral-400');
                el.classList.add('text-green-600');
                icon.innerHTML =
                    `<circle cx="12" cy="12" r="10" fill="#10b981" stroke="#10b981"></circle><path d="M9 12l2 2 4-4" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>`;
            } else {
                el.classList.remove('text-green-600');
                el.classList.add('text-neutral-400');
                icon.innerHTML = `<circle cx="12" cy="12" r="10"></circle>`;
            }
        }

        function resetRequirements() {
            [reqLength, reqCase, reqNumber, reqSpecial].forEach(req => updateRequirement(req, false));
        }

        // Password confirmation match
        const confirmInput = document.getElementById('password_confirmation');
        const confirmError = document.getElementById('confirmationError');

        confirmInput.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const val = confirmInput.value;
            if (!val.length) {
                confirmInput.classList.remove('is-invalid');
                confirmError.classList.add('hidden');
                return;
            }
            if (passwordInput.value === val) {
                confirmInput.classList.remove('is-invalid');
                confirmError.classList.add('hidden');
            } else {
                confirmInput.classList.add('is-invalid');
                confirmError.textContent = 'Password tidak cocok';
                confirmError.classList.remove('hidden');
            }
        }

        // Form submit + reCAPTCHA
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmInput.value) {
                e.preventDefault();
                confirmInput.classList.add('is-invalid');
                confirmError.textContent = 'Password tidak cocok';
                confirmError.classList.remove('hidden');
                confirmInput.focus();
                return;
            }

            if (passwordInput.value.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter');
                passwordInput.focus();
                return;
            }

            @if (!empty($recaptchaSiteKey))
                e.preventDefault();
                const btn = document.getElementById('submitBtn');
                const orig = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = `<div class="spinner"></div><span>Memproses...</span>`;
                grecaptcha.ready(() => {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'reset_password'
                        })
                        .then(token => {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('resetPasswordForm').submit();
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
        });
    </script>

@endpush

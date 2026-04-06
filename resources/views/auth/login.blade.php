@extends('layouts.auth')

@section('title', 'Login')

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
            <h1 class="text-[22px] font-bold text-neutral-800 mb-1">Hai, Selamat Datang!</h1>
            <p class="text-[13px] text-neutral-500">Silakan masuk dengan akun anda.</p>
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
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Email / Username --}}
            <div class="mb-3">
                <label for="login" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                    Email atau Username <span class="text-red-500">*</span>
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
                    <input type="text" class="form-input pl-10 pr-3 @error('login') is-invalid @enderror" id="login"
                        name="login" placeholder="nama@gmail.com atau username" value="{{ old('login') }}" required
                        autofocus>
                </div>
                @error('login')
                    <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                    Password <span class="text-red-500">*</span>
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
                        id="password" name="password" placeholder="Masukkan password anda" required>
                    <button type="button" onclick="togglePassword()" class="input-icon-right">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remember & Forgot --}}
            <div class="flex items-center justify-between mb-3.5">
                <label class="flex items-center gap-1.5 text-[13px] text-neutral-700 cursor-pointer select-none">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 accent-blue-500 cursor-pointer shrink-0" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
                <a href="{{ route('password.request') }}"
                    class="text-[13px] text-primary-600 font-medium hover:underline whitespace-nowrap">
                    Lupa Password?
                </a>
            </div>

            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            {{-- Submit --}}
            <button type="submit" id="submitBtn" class="btn-primary">
                <span>Masuk</span>
            </button>
        </form>

        {{-- Divider --}}
        <div class="auth-divider my-3.5">
            <span>Atau masuk dengan</span>
        </div>

        {{-- Google --}}
        <a href="{{ route('google.redirect', ['action' => 'login']) }}" class="btn-google">
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4="
                alt="Google" class="w-[18px] h-[18px] shrink-0">
            <span>Masuk dengan Google</span>
        </a>

        {{-- Register Link --}}
        <div class="mt-3.5 text-center text-[13px] text-neutral-500">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="text-primary-600 font-semibold hover:underline">Daftar disini</a>
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
        document.addEventListener('DOMContentLoaded', () => {

            // ── Lottie ──
            const container = document.getElementById('lottie-logo');
            if (container) {
                lottie.loadAnimation({
                    container: container,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '{{ asset('Auth/auth-logo.json') }}'
                });
            }

            // ── Auto-dismiss alerts ──
            document.querySelectorAll('.alert').forEach(el => {
                setTimeout(() => {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 5000);
            });
        });

        // ── Toggle password ──
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden ?
                `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>` :
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>`;
        }

        // ── Form submit + reCAPTCHA ──
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            @if (!empty($recaptchaSiteKey))
                e.preventDefault();
                const btn = document.getElementById('submitBtn');
                const orig = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = `<div class="spinner"></div><span>Memproses...</span>`;

                grecaptcha.ready(() => {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'login'
                        })
                        .then(token => {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('loginForm').submit();
                        })
                        .catch(() => {
                            btn.disabled = false;
                            btn.innerHTML = orig;
                            alert('Terjadi kesalahan pada verifikasi reCAPTCHA. Silakan coba lagi.');
                        });
                });
            @endif
        });
    </script>
@endpush

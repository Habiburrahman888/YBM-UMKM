@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="auth-header">
        <h1>Buat Password Baru</h1>
        <p>Masukkan password baru untuk akun <strong>{{ $maskedEmail }}</strong></p>
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

    {{-- Reset Password Form --}}
    <form method="POST" action="{{ route('password.update', $uuid) }}" id="resetPasswordForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        {{-- New Password --}}
        <div class="form-group">
            <label for="password" class="form-label">Password Baru</label>
            <div class="form-input-wrapper">
                <span class="form-input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <input type="password" class="form-input @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Minimal 8 karakter" required autofocus>
                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                    <svg id="eyeIconPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            <div class="password-strength" id="passwordStrength">
                <div class="strength-bars" id="strengthBars">
                    <div class="strength-bar"></div>
                    <div class="strength-bar"></div>
                    <div class="strength-bar"></div>
                    <div class="strength-bar"></div>
                </div>
                <div class="strength-text" id="strengthText">Masukkan password</div>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="form-input-wrapper">
                <span class="form-input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <input type="password" class="form-input @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                    <svg id="eyeIconPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            <span class="invalid-feedback" id="confirmationError" style="display: none;"></span>
            @error('password_confirmation')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password Requirements --}}
        <div class="password-requirements">
            <h4>Password harus memenuhi:</h4>
            <ul>
                <li id="req-length">
                    <svg class="req-icon unchecked" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span>Minimal 8 karakter</span>
                </li>
                <li id="req-case">
                    <svg class="req-icon unchecked" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span>Huruf besar dan kecil</span>
                </li>
                <li id="req-number">
                    <svg class="req-icon unchecked" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span>Mengandung angka</span>
                </li>
                <li id="req-special">
                    <svg class="req-icon unchecked" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span>Karakter khusus (!@#$%^&*)</span>
                </li>
            </ul>
        </div>

        {{-- reCAPTCHA Token (Hidden) --}}
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">

        {{-- Submit Button --}}
        <button type="submit" class="btn-primary" id="submitBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>Reset Password</span>
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

        .recaptcha-notice {
            margin-top: 8px;
            font-size: 11px;
        }

        /* Password Strength */
        .password-strength {
            margin-top: 8px;
            display: none;
        }

        .password-strength.show {
            display: block;
        }

        .strength-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 6px;
        }

        .strength-bar {
            height: 4px;
            flex: 1;
            background: #e5e7eb;
            border-radius: 2px;
            transition: background 0.3s ease;
        }

        .strength-bar.active {
            background: #ef4444;
        }

        .strength-bars.medium .strength-bar.active {
            background: #f59e0b;
        }

        .strength-bars.strong .strength-bar.active {
            background: #10b981;
        }

        .strength-text {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
        }

        /* Password Requirements */
        .password-requirements {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .password-requirements h4 {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 12px 0;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 0;
            font-size: 13px;
            color: #6b7280;
            transition: color 0.3s ease;
        }

        .password-requirements li.checked {
            color: #10b981;
        }

        .req-icon {
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .req-icon.unchecked {
            stroke: #d1d5db;
        }

        .req-icon.checked {
            stroke: #10b981;
        }

        /* Match indicator for confirmation */
        .password-match {
            color: #10b981 !important;
            border-color: #10b981 !important;
            background: #f0fdf4 !important;
        }

        .password-mismatch {
            color: #ef4444 !important;
            border-color: #ef4444 !important;
            background: #fef2f2 !important;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .password-requirements {
                padding: 16px;
            }

            .password-requirements h4 {
                font-size: 13px;
            }

            .password-requirements li {
                font-size: 12px;
            }
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

        /* Requirements lebih compact */
        .password-requirements {
            padding: 12px 14px;
            margin-bottom: 14px;
        }

        .password-requirements h4 {
            font-size: 13px;
            margin: 0 0 6px 0;
        }

        .password-requirements li {
            padding: 3px 0;
            font-size: 12px;
        }

        /* Button lebih kecil */
        .btn-primary {
            padding: 10px;
            font-size: 14px;
        }

        .auth-footer {
            margin-top: 12px;
            font-size: 13px;
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
        // Toggle Password Visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('eyeIcon' + inputId.charAt(0).toUpperCase() + inputId.slice(1));

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
            }
        }

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        const strengthBars = document.getElementById('strengthBars');
        const strengthText = document.getElementById('strengthText');

        // Requirements elements
        const reqLength = document.getElementById('req-length');
        const reqCase = document.getElementById('req-case');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        passwordInput.addEventListener('input', function() {
            const password = this.value;

            if (password.length === 0) {
                passwordStrength.classList.remove('show');
                resetRequirements();
                return;
            }

            passwordStrength.classList.add('show');

            let strength = 0;
            const bars = strengthBars.querySelectorAll('.strength-bar');

            // Reset bars
            bars.forEach(bar => bar.classList.remove('active'));
            strengthBars.className = 'strength-bars';

            // Check requirements
            const hasLength = password.length >= 8;
            const hasCase = password.match(/[a-z]/) && password.match(/[A-Z]/);
            const hasNumber = password.match(/[0-9]/);
            const hasSpecial = password.match(/[^a-zA-Z0-9]/);

            // Update requirement indicators
            updateRequirement(reqLength, hasLength);
            updateRequirement(reqCase, hasCase);
            updateRequirement(reqNumber, hasNumber);
            updateRequirement(reqSpecial, hasSpecial);

            // Calculate strength
            if (hasLength) strength++;
            if (hasCase) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;

            // Update bars
            for (let i = 0; i < strength; i++) {
                bars[i].classList.add('active');
            }

            // Update class and text
            if (strength <= 1) {
                strengthText.textContent = 'Password lemah';
            } else if (strength <= 2) {
                strengthBars.classList.add('medium');
                strengthText.textContent = 'Password sedang';
            } else {
                strengthBars.classList.add('strong');
                strengthText.textContent = 'Password kuat';
            }

            // Check confirmation match
            checkPasswordMatch();
        });

        function updateRequirement(element, isValid) {
            const icon = element.querySelector('.req-icon');

            if (isValid) {
                element.classList.add('checked');
                icon.classList.remove('unchecked');
                icon.classList.add('checked');
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.innerHTML = `
            <circle cx="12" cy="12" r="10" fill="#10b981" stroke="#10b981"></circle>
            <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
        `;
            } else {
                element.classList.remove('checked');
                icon.classList.add('unchecked');
                icon.classList.remove('checked');
                icon.innerHTML = `<circle cx="12" cy="12" r="10" fill="white" stroke="#d1d5db" stroke-width="2"></circle>`;
            }
        }

        function resetRequirements() {
            [reqLength, reqCase, reqNumber, reqSpecial].forEach(req => {
                updateRequirement(req, false);
            });
        }

        // Password confirmation checker
        const confirmationInput = document.getElementById('password_confirmation');
        const confirmationError = document.getElementById('confirmationError');

        confirmationInput.addEventListener('input', function() {
            checkPasswordMatch();
        });

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmation = confirmationInput.value;

            if (confirmation.length === 0) {
                confirmationInput.classList.remove('password-match', 'password-mismatch');
                confirmationError.style.display = 'none';
                return;
            }

            if (password === confirmation) {
                confirmationInput.classList.add('password-match');
                confirmationInput.classList.remove('password-mismatch');
                confirmationError.style.display = 'none';
            } else {
                confirmationInput.classList.add('password-mismatch');
                confirmationInput.classList.remove('password-match');
                confirmationError.textContent = 'Password tidak cocok';
                confirmationError.style.display = 'block';
            }
        }

        // Form Submission with reCAPTCHA
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmation = confirmationInput.value;

            // Validate password match
            if (password !== confirmation) {
                e.preventDefault();
                confirmationError.textContent = 'Password tidak cocok';
                confirmationError.style.display = 'block';
                confirmationInput.focus();
                return;
            }

            // Validate password requirements
            if (password.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter');
                passwordInput.focus();
                return;
            }

            @if ($recaptchaSiteKey)
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const originalContent = submitBtn.innerHTML;

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
            <div class="spinner"></div>
            <span>Memproses...</span>
        `;

                grecaptcha.ready(function() {
                    grecaptcha.execute(RECAPTCHA_SITE_KEY, {
                            action: 'reset_password'
                        })
                        .then(function(token) {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('resetPasswordForm').submit();
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

@extends('layouts.auth')

@section('title', 'Lengkapi Profil')

@push('styles')
    <style>
        /* Compact typography & spacing */
        .auth-header {
            margin-bottom: 16px;
        }

        .auth-header h1 {
            font-size: 22px;
        }

        .auth-header p {
            font-size: 13px;
        }

        /* Progress steps lebih compact */
        .progress-steps {
            margin-bottom: 24px;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        .step-label {
            font-size: 12px;
        }

        .progress-steps::before {
            top: 18px;
        }

        .progress-line {
            top: 18px;
        }

        /* Form lebih compact */
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

        .form-grid {
            gap: 12px;
            margin-bottom: 12px;
        }

        /* File upload compact */
        .file-upload-box {
            padding: 16px 12px !important;
        }

        .file-upload-icon {
            width: 32px !important;
            height: 32px !important;
            margin-bottom: 6px !important;
        }

        .file-upload-text {
            font-size: 12px !important;
        }

        .file-upload-hint {
            font-size: 11px !important;
        }

        /* Section header alamat */
        .form-step [style*="margin-top: 32px"] {
            margin-top: 16px !important;
            padding-top: 16px !important;
            margin-bottom: 10px !important;
        }

        /* Buttons compact */
        .form-buttons {
            margin-top: 16px;
            padding-top: 16px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 10px 20px;
            font-size: 14px;
        }

        /* Card scroll internal - KUNCI UTAMA */
        .auth-card {
            max-height: 95vh;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .auth-card::-webkit-scrollbar {
            display: none;
        }

        /* Prevent body scroll */
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        /* Override auth-card width untuk form yang lebih lebar */
        .auth-card {
            max-width: 900px !important;
            padding: 40px !important;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e5e7eb;
            z-index: 0;
        }

        .progress-line {
            position: absolute;
            top: 20px;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            transition: width 0.4s ease;
            z-index: 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .step-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            color: #9ca3af;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }

        .step.active .step-circle {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-color: #3b82f6;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .step.completed .step-circle {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
        }

        .step-label {
            font-size: 13px;
            font-weight: 500;
            color: #9ca3af;
            text-align: center;
            transition: color 0.3s ease;
        }

        .step.active .step-label,
        .step.completed .step-label {
            color: #374151;
        }

        /* Form Steps */
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-grid-full {
            grid-column: 1 / -1;
        }

        /* File Upload */
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .file-upload-box {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 32px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .file-upload-box:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .file-upload-box.dragover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .file-upload-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            color: #9ca3af;
        }

        .file-upload-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .file-upload-hint {
            font-size: 12px;
            color: #9ca3af;
        }

        .file-upload-input {
            display: none;
        }

        .file-preview {
            margin-top: 16px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.9);
            color: #ffffff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: background 0.2s;
        }

        .preview-remove:hover {
            background: #dc2626;
        }

        /* Buttons */
        .form-buttons {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #f3f4f6;
        }

        .btn-secondary {
            padding: 12px 28px;
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            grid-column: 1;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-secondary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-primary {
            padding: 12px 28px;
            grid-column: 3;
        }

        /* Validation Icons */
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

        /* Responsive */
        @media (max-width: 768px) {
            .auth-card {
                padding: 32px 24px !important;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .progress-steps {
                flex-wrap: wrap;
            }

            .step {
                flex: 0 0 33.333%;
                margin-bottom: 20px;
            }

            .form-buttons {
                flex-direction: column-reverse;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="auth-header">
        <h1>Lengkapi Profil Anda</h1>
        <p>{{ $isGoogleUser ? 'Akun Google berhasil terhubung. ' : '' }}Silakan lengkapi informasi berikut untuk
            menyelesaikan pendaftaran</p>
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

    {{-- Progress Steps --}}
    <div class="progress-steps">
        <div class="progress-line" id="progressLine"></div>
        <div class="step active" data-step="1">
            <div class="step-circle">1</div>
            <div class="step-label">Akun</div>
        </div>
        <div class="step" data-step="2">
            <div class="step-circle">2</div>
            <div class="step-label">Admin Unit</div>
        </div>
        <div class="step" data-step="3">
            <div class="step-circle">3</div>
            <div class="step-label">Data Unit</div>
        </div>
    </div>

    {{-- Complete Profile Form --}}
    <form method="POST" action="{{ route('complete-profile.store', $token) }}" id="completeProfileForm"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">

        {{-- STEP 1: DATA AKUN --}}
        <div class="form-step active" data-step="1">
            @if (!$isGoogleUser)
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Email Anda</label>
                        <div class="form-input-wrapper">
                            <span class="form-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="text" class="form-input" value="{{ $maskedEmail }}" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">Username <span style="color: #ef4444;">*</span></label>
                        <div class="form-input-wrapper">
                            <span class="form-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" class="form-input @error('username') is-invalid @enderror" id="username"
                                name="username" placeholder="Minimal 6 karakter" value="{{ old('username') }}" required>
                            <span class="validation-icon" id="usernameValidation"></span>
                        </div>
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <span class="invalid-feedback" id="usernameError" style="display: none;"></span>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span style="color: #ef4444;">*</span></label>
                        <div class="form-input-wrapper">
                            <span class="form-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                    </rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" class="form-input @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Minimal 8 karakter" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <svg id="eyeIconPassword" xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                style="color: #ef4444;">*</span></label>
                        <div class="form-input-wrapper">
                            <span class="form-input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                    </rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" class="form-input @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation" placeholder="Ulangi password"
                                required>
                            <button type="button" class="password-toggle"
                                onclick="togglePassword('password_confirmation')">
                                <svg id="eyeIconPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @else
                <div class="form-group">
                    <label class="form-label">Email Anda</label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        <input type="text" class="form-input" value="{{ $maskedEmail }}" disabled>
                    </div>
                </div>

                <div class="alert alert-info">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Anda login menggunakan Google. Username dan password akan dibuat otomatis.</span>
                </div>
            @endif
        </div>

        {{-- STEP 2: DATA ADMIN UNIT --}}
        <div class="form-step" data-step="2">
            <div class="form-grid">
                <div class="form-group">
                    <label for="admin_nama" class="form-label">Nama Lengkap <span
                            style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <input type="text" class="form-input @error('admin_nama') is-invalid @enderror"
                            id="admin_nama" name="admin_nama" placeholder="Nama lengkap admin"
                            value="{{ old('admin_nama') }}" required>
                    </div>
                    @error('admin_nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="admin_telepon" class="form-label">Nomor Telepon <span
                            style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                        </span>
                        <input type="tel" class="form-input @error('admin_telepon') is-invalid @enderror"
                            id="admin_telepon" name="admin_telepon" placeholder="08xxxxxxxxxx"
                            value="{{ old('admin_telepon') }}" required>
                    </div>
                    @error('admin_telepon')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="admin_email" class="form-label">Email Admin <span
                            style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        <input type="email" class="form-input" id="admin_email" name="admin_email"
                            value="{{ $user->email }}" readonly style="background-color: #f9fafb; cursor: not-allowed;">
                    </div>
                    <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 4px;">Email ini diambil dari
                        akun yang Anda daftarkan</small>
                </div>

                <div class="form-group">
                    <label for="admin_foto" class="form-label">Foto Profil (Opsional)</label>
                    <div class="file-upload-wrapper">
                        <label for="admin_foto" class="file-upload-box" id="adminFotoBox" style="padding: 20px 16px;">
                            <svg class="file-upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor"
                                style="width: 32px; height: 32px; margin-bottom: 8px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <div class="file-upload-text" style="font-size: 13px;">Klik atau drag & drop</div>
                            <div class="file-upload-hint" style="font-size: 11px;">JPG, PNG (Max. 2MB)</div>
                        </label>
                        <input type="file" class="file-upload-input" id="admin_foto" name="admin_foto"
                            accept="image/jpeg,image/jpg,image/png">
                        <div class="file-preview" id="adminFotoPreview"></div>
                    </div>
                    @error('admin_foto')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- STEP 3: DATA UNIT --}}
        <div class="form-step" data-step="3">
            {{-- Logo Unit --}}
            <div class="form-group">
                <label for="logo" class="form-label">Logo Unit (Opsional)</label>
                <div class="file-upload-wrapper">
                    <label for="logo" class="file-upload-box" id="logoBox">
                        <svg class="file-upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <div class="file-upload-text">Klik atau drag & drop untuk upload logo</div>
                        <div class="file-upload-hint">JPG, PNG, atau JPEG (Max. 2MB)</div>
                    </label>
                    <input type="file" class="file-upload-input" id="logo" name="logo"
                        accept="image/jpeg,image/jpg,image/png">
                    <div class="file-preview" id="logoPreview"></div>
                </div>
                @error('logo')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Nama Unit & Deskripsi --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_unit" class="form-label">Nama Unit <span style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </span>
                        <input type="text" class="form-input @error('nama_unit') is-invalid @enderror" id="nama_unit"
                            name="nama_unit" placeholder="Nama unit usaha" value="{{ old('nama_unit') }}" required>
                    </div>
                    @error('nama_unit')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email_unit" class="form-label">Email Unit <span style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        <input type="email" class="form-input @error('email_unit') is-invalid @enderror"
                            id="email_unit" name="email_unit" placeholder="unit@email.com"
                            value="{{ old('email_unit') }}" required>
                    </div>
                    @error('email_unit')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label for="deskripsi" class="form-label">Deskripsi Unit (Opsional)</label>
                <textarea class="form-input @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                    placeholder="Deskripsikan unit usaha Anda..." style="resize: vertical; padding-top: 14px;">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- SECTION: ALAMAT & LOKASI --}}
            <div style="margin-top: 32px; margin-bottom: 16px; padding-top: 24px; border-top: 2px solid #f3f4f6;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Alamat & Lokasi
                </h3>
                <p style="font-size: 13px; color: #6b7280; margin: 0;">Lengkapi informasi alamat unit usaha Anda</p>
            </div>

            {{-- Alamat Lengkap --}}
            <div class="form-group">
                <label for="alamat" class="form-label">Alamat Lengkap <span style="color: #ef4444;">*</span></label>
                <textarea class="form-input @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                    placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 05" style="resize: vertical; padding-top: 14px;" required>{{ old('alamat') }}</textarea>
                <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 4px;">
                    Isi dengan detail alamat seperti nama jalan, nomor, RT/RW
                </small>
                @error('alamat')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Provinsi & Kota --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="provinsi_kode" class="form-label">Provinsi <span style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="10" r="3"></circle>
                                <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"></path>
                            </svg>
                        </span>
                        <select class="form-input @error('provinsi_kode') is-invalid @enderror" id="provinsi_kode"
                            name="provinsi_kode" required>
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->code }}"
                                    {{ old('provinsi_kode') == $province->code ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('provinsi_kode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kota_kode" class="form-label">Kota/Kabupaten <span
                            style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            </svg>
                        </span>
                        <select class="form-input @error('kota_kode') is-invalid @enderror" id="kota_kode"
                            name="kota_kode" required disabled>
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                    </div>
                    @error('kota_kode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Kecamatan & Kelurahan --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="kecamatan_kode" class="form-label">Kecamatan <span
                            style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="9" y1="3" x2="9" y2="21"></line>
                                <line x1="15" y1="3" x2="15" y2="21"></line>
                            </svg>
                        </span>
                        <select class="form-input @error('kecamatan_kode') is-invalid @enderror" id="kecamatan_kode"
                            name="kecamatan_kode" required disabled>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    @error('kecamatan_kode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kelurahan_kode" class="form-label">Kelurahan/Desa <span
                            style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </span>
                        <select class="form-input @error('kelurahan_kode') is-invalid @enderror" id="kelurahan_kode"
                            name="kelurahan_kode" required disabled>
                            <option value="">Pilih Kelurahan/Desa</option>
                        </select>
                    </div>
                    @error('kelurahan_kode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Kode Pos & Telepon --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="kode_pos" class="form-label">Kode Pos <span style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                <line x1="6" y1="18" x2="6.01" y2="18"></line>
                            </svg>
                        </span>
                        <input type="text" class="form-input @error('kode_pos') is-invalid @enderror" id="kode_pos"
                            name="kode_pos" placeholder="12345" value="{{ old('kode_pos') }}" maxlength="5" required>
                    </div>
                    <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 4px;">
                        Otomatis terisi saat memilih kelurahan
                    </small>
                    @error('kode_pos')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telepon" class="form-label">Telepon Unit <span style="color: #ef4444;">*</span></label>
                    <div class="form-input-wrapper">
                        <span class="form-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                        </span>
                        <input type="tel" class="form-input @error('telepon') is-invalid @enderror" id="telepon"
                            name="telepon" placeholder="021-xxxxxxx atau 08xxxxxxxxxx" value="{{ old('telepon') }}"
                            required>
                    </div>
                    @error('telepon')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Form Navigation Buttons --}}
        <div class="form-buttons">
            <button type="button" class="btn-secondary" id="prevBtn" onclick="changeStep(-1)"
                style="display: none;">
                Kembali
            </button>
            <button type="button" class="btn-primary" id="nextBtn" onclick="changeStep(1)">
                Selanjutnya
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
            <button type="submit" class="btn-primary" id="submitBtn" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Selesai
            </button>
        </div>
    </form>

    {{-- reCAPTCHA Notice --}}
    @if ($recaptchaSiteKey)
        <div class="recaptcha-notice" style="margin-top: 24px;">
            This site is protected by reCAPTCHA and the Google
            <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and
            <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.
        </div>
    @endif
@endsection

@push('scripts')
    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
        <script>
            const RECAPTCHA_SITE_KEY = '{{ $recaptchaSiteKey }}';
        </script>
    @endif

    <script>
        let currentStep = 1;
        const totalSteps = 3;
        const isGoogleUser = {{ $isGoogleUser ? 'true' : 'false' }};

        // PENANGANAN ERROR STEP DARI SERVER
        @if (session('error_step'))
            // Set step berdasarkan error dari server
            currentStep = {{ session('error_step') }};

            // Jalankan setelah DOM ready
            document.addEventListener('DOMContentLoaded', function() {
                // Sembunyikan semua step
                document.querySelectorAll('.form-step').forEach(step => {
                    step.classList.remove('active');
                });

                // Tampilkan step yang error
                const errorStep = document.querySelector(`.form-step[data-step="${currentStep}"]`);
                if (errorStep) {
                    errorStep.classList.add('active');
                }

                // Update step circles
                document.querySelectorAll('.step').forEach((step, index) => {
                    step.classList.remove('active', 'completed');
                    if (index + 1 < currentStep) {
                        step.classList.add('completed');
                    } else if (index + 1 === currentStep) {
                        step.classList.add('active');
                    }
                });

                // Update progress line dan buttons
                updateProgressLine();
                updateButtons();

                // Auto-scroll ke field yang error
                setTimeout(function() {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                }, 300);
            });
        @endif

        // Multi-step form navigation
        function changeStep(direction) {
            const steps = document.querySelectorAll('.form-step');
            const stepCircles = document.querySelectorAll('.step');

            // Validate current step before moving forward
            if (direction === 1 && !validateStep(currentStep)) {
                return;
            }

            // Hide current step
            steps[currentStep - 1].classList.remove('active');
            stepCircles[currentStep - 1].classList.remove('active');
            stepCircles[currentStep - 1].classList.add('completed');

            // Update current step
            currentStep += direction;

            // Show new step
            steps[currentStep - 1].classList.add('active');
            stepCircles[currentStep - 1].classList.add('active');

            // Update progress line
            updateProgressLine();

            // Update buttons
            updateButtons();

            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function updateProgressLine() {
            const progressLine = document.getElementById('progressLine');
            const percentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
            progressLine.style.width = percentage + '%';
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            if (currentStep === 1) {
                prevBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'flex';
            }

            if (currentStep === totalSteps) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'flex';
            } else {
                nextBtn.style.display = 'flex';
                submitBtn.style.display = 'none';
            }
        }

        function validateStep(step) {
            let isValid = true;
            const currentStepElement = document.querySelector(`.form-step[data-step="${step}"]`);
            const requiredInputs = currentStepElement.querySelectorAll(
                'input[required], select[required], textarea[required]');

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');

                    // Add or update error message
                    let errorElement = input.parentElement.parentElement.querySelector('.invalid-feedback');
                    if (!errorElement) {
                        errorElement = document.createElement('span');
                        errorElement.className = 'invalid-feedback';
                        input.parentElement.parentElement.appendChild(errorElement);
                    }
                    errorElement.textContent = 'Field ini wajib diisi';
                    errorElement.style.display = 'block';
                } else {
                    input.classList.remove('is-invalid');
                    const errorElement = input.parentElement.parentElement.querySelector('.invalid-feedback');
                    if (errorElement && !errorElement.textContent.includes('sudah')) {
                        errorElement.style.display = 'none';
                    }
                }
            });

            // Additional validation for step 1 (password confirmation)
            if (step === 1 && !isGoogleUser) {
                const password = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');

                if (password.value !== passwordConfirmation.value) {
                    isValid = false;
                    passwordConfirmation.classList.add('is-invalid');
                    let errorElement = passwordConfirmation.parentElement.parentElement.querySelector('.invalid-feedback');
                    if (!errorElement) {
                        errorElement = document.createElement('span');
                        errorElement.className = 'invalid-feedback';
                        passwordConfirmation.parentElement.parentElement.appendChild(errorElement);
                    }
                    errorElement.textContent = 'Password tidak cocok';
                    errorElement.style.display = 'block';
                }
            }

            if (!isValid) {
                alert('Mohon lengkapi semua field yang wajib diisi');
            }

            return isValid;
        }

        // Toggle password visibility
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
        if (!isGoogleUser) {
            const passwordInput = document.getElementById('password');
            const passwordStrength = document.getElementById('passwordStrength');
            const strengthBars = document.getElementById('strengthBars');
            const strengthText = document.getElementById('strengthText');

            passwordInput.addEventListener('input', function() {
                const password = this.value;

                if (password.length === 0) {
                    passwordStrength.classList.remove('show');
                    return;
                }

                passwordStrength.classList.add('show');

                let strength = 0;
                const bars = strengthBars.querySelectorAll('.strength-bar');

                // Reset bars
                bars.forEach(bar => bar.classList.remove('active'));
                strengthBars.className = 'strength-bars';

                // Calculate strength
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;

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
            });
        }

        // Username validation (real-time)
        if (!isGoogleUser) {
            let usernameCheckTimeout;
            const usernameInput = document.getElementById('username');
            const usernameValidation = document.getElementById('usernameValidation');
            const usernameError = document.getElementById('usernameError');

            usernameInput.addEventListener('input', function() {
                clearTimeout(usernameCheckTimeout);

                const username = this.value.trim();

                usernameInput.classList.remove('checking', 'valid', 'is-invalid');
                usernameValidation.className = 'validation-icon';
                usernameValidation.innerHTML = '';
                usernameError.style.display = 'none';

                if (username.length === 0) {
                    return;
                }

                if (username.length < 6) {
                    usernameInput.classList.add('is-invalid');
                    usernameValidation.className = 'validation-icon invalid';
                    usernameValidation.innerHTML = '×';
                    usernameError.textContent = 'Username minimal 6 karakter';
                    usernameError.style.display = 'block';
                    return;
                }

                if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                    usernameInput.classList.add('is-invalid');
                    usernameValidation.className = 'validation-icon invalid';
                    usernameValidation.innerHTML = '×';
                    usernameError.textContent = 'Username hanya boleh huruf, angka, dan underscore';
                    usernameError.style.display = 'block';
                    return;
                }

                usernameInput.classList.add('checking');
                usernameValidation.className = 'validation-icon checking';
                usernameValidation.innerHTML = '⟳';

                usernameCheckTimeout = setTimeout(function() {
                    checkUsernameAvailability(username);
                }, 500);
            });

            function checkUsernameAvailability(username) {
                fetch(
                        `{{ route('check-username') }}?username=${encodeURIComponent(username)}&user_id={{ $user->id }}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        usernameInput.classList.remove('checking');
                        usernameValidation.className = 'validation-icon';

                        if (data.available) {
                            usernameInput.classList.add('valid');
                            usernameValidation.className = 'validation-icon valid';
                            usernameValidation.innerHTML = '✓';
                            usernameError.style.display = 'none';
                        } else {
                            usernameInput.classList.add('is-invalid');
                            usernameValidation.className = 'validation-icon invalid';
                            usernameValidation.innerHTML = '×';
                            usernameError.textContent = data.message;
                            usernameError.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error checking username:', error);
                        usernameInput.classList.remove('checking');
                        usernameValidation.className = 'validation-icon';
                        usernameValidation.innerHTML = '';
                    });
            }
        }

        // File upload handling
        function setupFileUpload(inputId, boxId, previewId) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            const preview = document.getElementById(previewId);

            // Click to upload
            box.addEventListener('click', function(e) {
                if (e.target.classList.contains('preview-remove')) return;
                input.click();
            });

            // Drag & drop
            box.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            box.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            box.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    handleFileSelect(input, preview);
                }
            });

            // File input change
            input.addEventListener('change', function() {
                handleFileSelect(this, preview);
            });
        }

        function handleFileSelect(input, previewContainer) {
            previewContainer.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    input.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.match('image/(jpeg|jpg|png)')) {
                    alert('Format file harus JPG, JPEG, atau PNG');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="preview-remove" onclick="removeFile('${input.id}', '${previewContainer.id}')">×</button>
                `;
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            }
        }

        function removeFile(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).innerHTML = '';
        }

        // Setup file uploads
        setupFileUpload('admin_foto', 'adminFotoBox', 'adminFotoPreview');
        setupFileUpload('logo', 'logoBox', 'logoPreview');

        // Location cascading dropdowns
        const provinsiSelect = document.getElementById('provinsi_kode');
        const kotaSelect = document.getElementById('kota_kode');
        const kecamatanSelect = document.getElementById('kecamatan_kode');
        const kelurahanSelect = document.getElementById('kelurahan_kode');
        const kodePosInput = document.getElementById('kode_pos');

        provinsiSelect.addEventListener('change', function() {
            const provinceCode = this.value;
            kotaSelect.disabled = !provinceCode;
            kecamatanSelect.disabled = true;
            kelurahanSelect.disabled = true;

            kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kodePosInput.value = '';

            if (provinceCode) {
                fetch(`{{ route('get-cities') }}?province_code=${provinceCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.cities.forEach(city => {
                            const option = new Option(city.name, city.code);
                            kotaSelect.add(option);
                        });
                    });
            }
        });

        kotaSelect.addEventListener('change', function() {
            const cityCode = this.value;
            kecamatanSelect.disabled = !cityCode;
            kelurahanSelect.disabled = true;

            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kodePosInput.value = '';

            if (cityCode) {
                fetch(`{{ route('get-districts') }}?city_code=${cityCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.districts.forEach(district => {
                            const option = new Option(district.name, district.code);
                            kecamatanSelect.add(option);
                        });
                    });
            }
        });

        kecamatanSelect.addEventListener('change', function() {
            const districtCode = this.value;
            kelurahanSelect.disabled = !districtCode;

            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kodePosInput.value = '';

            if (districtCode) {
                fetch(`{{ route('get-villages') }}?district_code=${districtCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.villages.forEach(village => {
                            const option = new Option(village.name, village.code);
                            kelurahanSelect.add(option);
                        });
                    });
            }
        });

        kelurahanSelect.addEventListener('change', function() {
            const villageCode = this.value;

            if (villageCode) {
                fetch(`{{ route('get-postal-code') }}?village_code=${villageCode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.postal_code) {
                            kodePosInput.value = data.postal_code;
                        }
                    });
            }
        });

        // Form submission with reCAPTCHA
        document.getElementById('completeProfileForm').addEventListener('submit', function(e) {
            @if ($recaptchaSiteKey)
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const originalContent = submitBtn.innerHTML;

                // Validate all steps
                let allValid = true;
                for (let i = 1; i <= totalSteps; i++) {
                    if (!validateStep(i)) {
                        allValid = false;
                        // Jump to first invalid step
                        currentStep = i;
                        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
                        document.querySelector(`.form-step[data-step="${i}"]`).classList.add('active');
                        updateProgressLine();
                        updateButtons();
                        break;
                    }
                }

                if (!allValid) {
                    alert('Mohon lengkapi semua field yang wajib diisi');
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
                            action: 'complete_profile'
                        })
                        .then(function(token) {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('completeProfileForm').submit();
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

        // Auto-hide alerts
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

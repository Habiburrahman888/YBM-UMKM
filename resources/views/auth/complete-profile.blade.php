@extends('layouts.auth')

@section('title', 'Lengkapi Profil')

@section('content')
    <div class="w-full flex flex-col overflow-y-auto max-h-screen px-11 py-9 scrollbar-hide">

        {{-- ── Header ── --}}
        <div class="text-center mb-6 pb-5 border-b border-slate-100">
            <h1 class="text-[22px] font-bold text-neutral-800 mb-1">Lengkapi Profil Anda</h1>
            <p class="text-[13px] text-neutral-500">
                {{ $isGoogleUser ? 'Akun Google berhasil terhubung. ' : '' }}Silakan lengkapi informasi berikut untuk
                menyelesaikan pendaftaran.
            </p>
        </div>

        {{-- ── Alerts ── --}}
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

        {{-- ── Progress Steps ── --}}
        <div class="relative flex items-start mb-7">
            {{-- Track line --}}
            <div class="absolute top-[17px] left-0 right-0 h-0.5 bg-slate-200 z-0"></div>
            {{-- Active progress line --}}
            <div class="absolute top-[17px] left-0 h-0.5 bg-gradient-to-r from-[#1e3a5f] to-[#2563a8] z-[1] transition-all duration-500 ease-in-out"
                id="progressLine" style="width:0%"></div>

            <div class="step flex flex-col items-center flex-1 relative z-[2]" data-step="1">
                <div
                    class="step-circle w-9 h-9 rounded-full bg-white border-2 border-slate-200 flex items-center justify-center text-[13px] font-semibold text-slate-400 mb-1.5 transition-all duration-300">
                    1</div>
                <div class="step-label text-[11.5px] text-slate-400 font-medium transition-colors duration-300">Akun</div>
            </div>
            <div class="step flex flex-col items-center flex-1 relative z-[2]" data-step="2">
                <div
                    class="step-circle w-9 h-9 rounded-full bg-white border-2 border-slate-200 flex items-center justify-center text-[13px] font-semibold text-slate-400 mb-1.5 transition-all duration-300">
                    2</div>
                <div class="step-label text-[11.5px] text-slate-400 font-medium transition-colors duration-300">Admin unit
                </div>
            </div>
            <div class="step flex flex-col items-center flex-1 relative z-[2]" data-step="3">
                <div
                    class="step-circle w-9 h-9 rounded-full bg-white border-2 border-slate-200 flex items-center justify-center text-[13px] font-semibold text-slate-400 mb-1.5 transition-all duration-300">
                    3</div>
                <div class="step-label text-[11.5px] text-slate-400 font-medium transition-colors duration-300">Data unit
                </div>
            </div>
        </div>

        {{-- ── Form ── --}}
        <form method="POST" action="{{ route('complete-profile.store', $token) }}" id="completeProfileForm"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

            {{-- ══════════════════════════════
                 STEP 1: AKUN
            ══════════════════════════════ --}}
            <div class="form-step active" data-step="1">

                @if (!$isGoogleUser)
                    <div class="grid grid-cols-2 gap-4 mb-1">

                        {{-- Email (disabled) --}}
                        <div class="mb-3.5">
                            <label class="block text-[13px] font-medium text-neutral-700 mb-1.5">Email anda</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                        </path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <input type="text"
                                    class="form-input pl-10 pr-3 bg-slate-100 text-slate-500 cursor-not-allowed"
                                    value="{{ $user->email }}" disabled>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="mb-3.5">
                            <label for="username" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input type="text"
                                    class="form-input pl-10 pr-8 @error('username') is-invalid @enderror" id="username"
                                    name="username" placeholder="Minimal 6 karakter" value="{{ old('username') }}"
                                    required>
                                <span class="absolute right-3 text-[13px] font-semibold pointer-events-none hidden"
                                    id="usernameValidation"></span>
                            </div>
                            @error('username')
                                <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                            @enderror
                            <span class="hidden mt-1 text-[12px] font-medium" id="usernameError"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-1">

                        {{-- Password --}}
                        <div class="mb-3.5">
                            <label for="password" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                        </rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </span>
                                <input type="password"
                                    class="form-input pl-10 pr-10 @error('password') is-invalid @enderror" id="password"
                                    name="password" placeholder="Minimal 8 karakter" required>
                                <button type="button" onclick="togglePassword('password','eyePassword')"
                                    class="input-icon-right">
                                    <svg id="eyePassword" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>

                            {{-- Password Strength --}}
                            <div class="mt-2" id="passwordStrength">
                                <div class="flex gap-1 mb-2" id="strengthBars">
                                    <div class="h-[3px] flex-1 rounded bg-slate-200 transition-colors duration-300"></div>
                                    <div class="h-[3px] flex-1 rounded bg-slate-200 transition-colors duration-300"></div>
                                    <div class="h-[3px] flex-1 rounded bg-slate-200 transition-colors duration-300"></div>
                                    <div class="h-[3px] flex-1 rounded bg-slate-200 transition-colors duration-300"></div>
                                    <div class="h-[3px] flex-1 rounded bg-slate-200 transition-colors duration-300"></div>
                                </div>
                                <ul class="flex flex-col gap-1 p-0 list-none" id="passwordRules">
                                    <li id="rule-length"
                                        class="flex items-center gap-1.5 text-[12px] text-slate-400 before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-slate-300 before:shrink-0 before:transition-colors">
                                        Minimal 8 karakter</li>
                                    <li id="rule-upper"
                                        class="flex items-center gap-1.5 text-[12px] text-slate-400 before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-slate-300 before:shrink-0 before:transition-colors">
                                        Huruf besar (A-Z)</li>
                                    <li id="rule-lower"
                                        class="flex items-center gap-1.5 text-[12px] text-slate-400 before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-slate-300 before:shrink-0 before:transition-colors">
                                        Huruf kecil (a-z)</li>
                                    <li id="rule-number"
                                        class="flex items-center gap-1.5 text-[12px] text-slate-400 before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-slate-300 before:shrink-0 before:transition-colors">
                                        Angka (0-9)</li>
                                    <li id="rule-special"
                                        class="flex items-center gap-1.5 text-[12px] text-slate-400 before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-slate-300 before:shrink-0 before:transition-colors">
                                        Karakter khusus (@$!%*?&amp;#)</li>
                                </ul>
                            </div>

                            @error('password')
                                <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-3.5">
                            <label for="password_confirmation"
                                class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                                Konfirmasi password <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                        </rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </span>
                                <input type="password"
                                    class="form-input pl-10 pr-10 @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation" placeholder="Ulangi password"
                                    required>
                                <button type="button" onclick="togglePassword('password_confirmation','eyeConfirm')"
                                    class="input-icon-right">
                                    <svg id="eyeConfirm" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            <span class="hidden mt-1 text-[12px] text-red-500" id="confirmError"></span>
                            @error('password_confirmation')
                                <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @else
                    {{-- Google user: hanya tampilkan email --}}
                    <div class="mb-3.5">
                        <label class="block text-[13px] font-medium text-neutral-700 mb-1.5">Email anda</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="text"
                                class="form-input pl-10 pr-3 bg-slate-100 text-slate-500 cursor-not-allowed"
                                value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <svg class="w-[18px] h-[18px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Anda login menggunakan Google. Username dan password akan dibuat otomatis.</span>
                    </div>
                @endif

            </div>{{-- /step 1 --}}

            {{-- ══════════════════════════════
                 STEP 2: ADMIN UNIT
            ══════════════════════════════ --}}
            <div class="form-step" data-step="2">

                <div class="grid grid-cols-2 gap-4 mb-1">
                    {{-- Nama lengkap --}}
                    <div class="mb-3.5">
                        <label for="admin_nama" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Nama lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" class="form-input pl-10 pr-3 @error('admin_nama') is-invalid @enderror"
                                id="admin_nama" name="admin_nama" placeholder="Nama lengkap admin"
                                value="{{ old('admin_nama') }}" required>
                        </div>
                        @error('admin_nama')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Nomor telepon --}}
                    <div class="mb-3.5">
                        <label for="admin_telepon" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Nomor telepon <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                            </span>
                            {{-- PERBAIKAN: tambah inputmode, pattern, maxlength --}}
                            <input type="tel"
                                class="form-input pl-10 pr-3 @error('admin_telepon') is-invalid @enderror"
                                id="admin_telepon" name="admin_telepon" placeholder="08xxxxxxxxxx" inputmode="numeric"
                                pattern="[0-9\-\+\(\) ]+" maxlength="15" value="{{ old('admin_telepon') }}" required>
                        </div>
                        @error('admin_telepon')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-1">
                    {{-- Email admin (readonly) --}}
                    <div class="mb-3.5">
                        <label for="admin_email" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Email admin <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email"
                                class="form-input pl-10 pr-3 bg-slate-100 text-slate-500 cursor-not-allowed"
                                id="admin_email" name="admin_email" value="{{ $user->email }}" readonly>
                        </div>
                        <span class="block mt-1 text-[11px] text-slate-400">Email diambil dari akun yang didaftarkan</span>
                    </div>

                    {{-- Foto profil --}}
                    <div class="mb-3.5">
                        <label class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Foto profil <span class="text-[13px] text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <label for="admin_foto"
                            class="flex flex-col items-center justify-center gap-1 border-[1.5px] border-dashed border-slate-300 rounded-lg p-4 text-center cursor-pointer bg-slate-50 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200"
                            id="adminFotoBox">
                            <svg class="w-6 h-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="text-[13px] text-slate-500">Klik atau drag &amp; drop</span>
                            <span class="text-[11px] text-slate-400">JPG, PNG (maks. 2MB)</span>
                        </label>
                        <input type="file" class="hidden" id="admin_foto" name="admin_foto"
                            accept="image/jpeg,image/jpg,image/png">
                        <div class="flex flex-wrap gap-2.5 mt-2.5" id="adminFotoPreview"></div>
                        @error('admin_foto')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>{{-- /step 2 --}}

            {{-- ══════════════════════════════
                 STEP 3: DATA UNIT
            ══════════════════════════════ --}}
            <div class="form-step" data-step="3">

                {{-- Logo unit --}}
                <div class="mb-3.5">
                    <label class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                        Logo unit <span class="text-[13px] text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <label for="logo"
                        class="flex flex-col items-center justify-center gap-1 border-[1.5px] border-dashed border-slate-300 rounded-lg p-4 text-center cursor-pointer bg-slate-50 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200"
                        id="logoBox">
                        <svg class="w-6 h-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span class="text-[13px] text-slate-500">Klik atau drag &amp; drop untuk upload logo</span>
                        <span class="text-[11px] text-slate-400">JPG, PNG, JPEG (maks. 2MB)</span>
                    </label>
                    <input type="file" class="hidden" id="logo" name="logo"
                        accept="image/jpeg,image/jpg,image/png">
                    <div class="flex flex-wrap gap-2.5 mt-2.5" id="logoPreview"></div>
                    @error('logo')
                        <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-1">
                    {{-- Nama unit --}}
                    <div class="mb-3.5">
                        <label for="nama_unit" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Nama unit <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </span>
                            <input type="text" class="form-input pl-10 pr-3 @error('nama_unit') is-invalid @enderror"
                                id="nama_unit" name="nama_unit" placeholder="Nama unit usaha"
                                value="{{ old('nama_unit') }}" required>
                        </div>
                        @error('nama_unit')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email unit --}}
                    <div class="mb-3.5">
                        <label for="email_unit" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Email unit <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email" class="form-input pl-10 pr-3 @error('email_unit') is-invalid @enderror"
                                id="email_unit" name="email_unit" placeholder="unit@email.com"
                                value="{{ old('email_unit') }}" required>
                        </div>
                        @error('email_unit')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3.5">
                    <label for="deskripsi" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                        Deskripsi unit <span class="text-[13px] text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <textarea class="form-input pl-3 pr-3 h-auto min-h-[76px] resize-y pt-2.5 @error('deskripsi') is-invalid @enderror"
                        id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsikan unit usaha anda...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Section separator: Alamat --}}
                <div class="border-t border-slate-100 pt-4 mt-4 mb-3.5">
                    <div class="flex items-center gap-1.5 text-[14px] font-semibold text-slate-800 mb-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Alamat &amp; lokasi
                    </div>
                    <p class="text-[12px] text-slate-500">Lengkapi informasi alamat unit usaha anda</p>
                </div>

                {{-- Alamat lengkap --}}
                <div class="mb-3.5">
                    <label for="alamat" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                        Alamat lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea class="form-input pl-3 pr-3 h-auto min-h-[76px] resize-y pt-2.5 @error('alamat') is-invalid @enderror"
                        id="alamat" name="alamat" rows="3" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 05" required>{{ old('alamat') }}</textarea>
                    <span class="block mt-1 text-[11px] text-slate-400">Isi dengan detail alamat seperti nama jalan, nomor,
                        RT/RW</span>
                    @error('alamat')
                        <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-1">
                    {{-- Provinsi --}}
                    <div class="mb-3.5">
                        <label for="provinsi_kode" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="10" r="3"></circle>
                                    <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"></path>
                                </svg>
                            </span>
                            <select
                                class="form-input pl-10 pr-8 appearance-none @error('provinsi_kode') is-invalid @enderror"
                                id="provinsi_kode" name="provinsi_kode" required>
                                <option value="">Pilih provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->code }}"
                                        {{ old('provinsi_kode') == $province->code ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('provinsi_kode')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kota --}}
                    <div class="mb-3.5">
                        <label for="kota_kode" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Kota/kabupaten <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                </svg>
                            </span>
                            <select class="form-input pl-10 pr-8 appearance-none @error('kota_kode') is-invalid @enderror"
                                id="kota_kode" name="kota_kode" required disabled>
                                <option value="">Pilih kota/kabupaten</option>
                            </select>
                        </div>
                        @error('kota_kode')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-1">
                    {{-- Kecamatan --}}
                    <div class="mb-3.5">
                        <label for="kecamatan_kode" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <line x1="9" y1="3" x2="9" y2="21"></line>
                                    <line x1="15" y1="3" x2="15" y2="21"></line>
                                </svg>
                            </span>
                            <select
                                class="form-input pl-10 pr-8 appearance-none @error('kecamatan_kode') is-invalid @enderror"
                                id="kecamatan_kode" name="kecamatan_kode" required disabled>
                                <option value="">Pilih kecamatan</option>
                            </select>
                        </div>
                        @error('kecamatan_kode')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kelurahan --}}
                    <div class="mb-3.5">
                        <label for="kelurahan_kode" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Kelurahan/desa <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </span>
                            <select
                                class="form-input pl-10 pr-8 appearance-none @error('kelurahan_kode') is-invalid @enderror"
                                id="kelurahan_kode" name="kelurahan_kode" required disabled>
                                <option value="">Pilih kelurahan/desa</option>
                            </select>
                        </div>
                        @error('kelurahan_kode')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-1">
                    {{-- Kode pos --}}
                    <div class="mb-3.5">
                        <label for="kode_pos" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Kode pos <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="8" rx="2"></rect>
                                    <rect x="2" y="14" width="20" height="8" rx="2"></rect>
                                    <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                    <line x1="6" y1="18" x2="6.01" y2="18"></line>
                                </svg>
                            </span>
                            <input type="text" class="form-input pl-10 pr-3 @error('kode_pos') is-invalid @enderror"
                                id="kode_pos" name="kode_pos" placeholder="12345" maxlength="5"
                                value="{{ old('kode_pos') }}" required>
                        </div>
                        <span class="block mt-1 text-[11px] text-slate-400">Otomatis terisi saat memilih kelurahan</span>
                        @error('kode_pos')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Telepon unit --}}
                    <div class="mb-3.5">
                        <label for="telepon" class="block text-[13px] font-medium text-neutral-700 mb-1.5">
                            Telepon unit <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                            </span>
                            {{-- PERBAIKAN: tambah inputmode, pattern, maxlength --}}
                            <input type="tel" class="form-input pl-10 pr-3 @error('telepon') is-invalid @enderror"
                                id="telepon" name="telepon" placeholder="021-xxxxxxx atau 08xxxxxxxxxx"
                                inputmode="numeric" pattern="[0-9\-\+\(\) ]+" maxlength="15"
                                value="{{ old('telepon') }}" required>
                        </div>
                        @error('telepon')
                            <span class="block mt-1 text-[12px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>{{-- /step 3 --}}

            {{-- ── Navigation Buttons ── --}}
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100">
                <button type="button" id="prevBtn" onclick="changeStep(-1)"
                    class="invisible inline-flex items-center gap-1.5 px-5 py-2.5 text-[13.5px] font-medium text-neutral-700 bg-slate-100 border border-slate-200 rounded-lg hover:bg-slate-200 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali
                </button>

                <span class="text-[12px] text-slate-400" id="stepCounter">Langkah 1 dari 3</span>

                <div>
                    <button type="button" id="nextBtn" onclick="changeStep(1)"
                        class="btn-primary w-auto h-auto px-5 py-2.5 inline-flex items-center gap-1.5">
                        Selanjutnya
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                    <button type="submit" id="submitBtn"
                        class="btn-primary w-auto h-auto px-5 py-2.5 flex items-center gap-1.5" style="display: none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Selesai
                    </button>
                </div>
            </div>

        </form>

        @if (!empty($recaptchaSiteKey))
            <div class="mt-3.5 text-[11px] text-slate-400 text-center leading-relaxed">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" target="_blank"
                    class="text-slate-500 hover:underline">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank" class="text-slate-500 hover:underline">Terms
                    of Service</a> apply.
            </div>
        @else
            <div class="mt-2.5 text-[11px] text-red-500 text-center">
                ⚠️ Warning: reCAPTCHA is not configured. Please set RECAPTCHA_SITE_KEY in your .env file.
            </div>
        @endif

    </div>{{-- /.cp-inner --}}

    {{-- ── Validation Modal ── --}}
    <div class="fixed inset-0 bg-black/30 flex items-center justify-center z-[9999] opacity-0 pointer-events-none transition-opacity duration-200"
        id="valModalOverlay" onclick="closeValModal(event)">
        <div class="bg-white rounded-2xl p-7 w-[90%] max-w-[380px] shadow-[0_20px_60px_rgba(0,0,0,0.13)] scale-95 translate-y-2 transition-all duration-200"
            id="valModal">
            <div class="w-[46px] h-[46px] rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round" class="text-red-500">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="text-[15px] font-semibold text-gray-900 text-center mb-3">Mohon lengkapi data</div>
            <ul class="list-none p-0 m-0 flex flex-col gap-1.5 max-h-[240px] overflow-y-auto mb-4" id="valModalList"></ul>
            <button onclick="closeValModal()"
                class="w-full py-2.5 bg-gradient-to-br from-[#1e3a5f] to-[#2563a8] text-white border-none rounded-[10px] text-[13.5px] font-semibold cursor-pointer hover:opacity-90 transition-opacity">
                Mengerti
            </button>
        </div>
    </div>

@endsection

@push('scripts')
    @if (!empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
        <script>
            const RECAPTCHA_SITE_KEY = '{{ $recaptchaSiteKey }}';
        </script>
    @endif

    <script>
        /* ─────────────────────────────────────────
                AUTH-CARD OVERRIDE → single column
                ───────────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.auth-card');
            if (card) {
                card.style.cssText =
                    'flex-direction:column;width:min(720px,calc(100vw - 32px));height:auto;max-height:calc(100vh - 32px);overflow-y:auto;';
            }
        });

        /* ─────────────────────────────────────────
        STEP STATE
        ───────────────────────────────────────── */
        let currentStep = 1;
        const totalSteps = 3;
        const isGoogleUser = {{ $isGoogleUser ? 'true' : 'false' }};

        @if (session('error_step'))
            currentStep = {{ session('error_step') }};
            document.addEventListener('DOMContentLoaded', function() {
                restoreStep(currentStep);
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

        function restoreStep(target) {
            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            document.querySelector(`.form-step[data-step="${target}"]`).classList.add('active');
            updateCircles();
            updateProgressLine();
            updateButtons();
        }

        function changeStep(direction) {
            if (direction === 1 && !validateStep(currentStep)) return;
            document.querySelectorAll('.form-step')[currentStep - 1].classList.remove('active');
            currentStep += direction;
            document.querySelectorAll('.form-step')[currentStep - 1].classList.add('active');
            updateCircles();
            updateProgressLine();
            updateButtons();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        /* ─────────────────────────────────────────
        PROGRESS UI
        ───────────────────────────────────────── */
        function updateCircles() {
            document.querySelectorAll('.step').forEach((el, i) => {
                const circle = el.querySelector('.step-circle');
                const label = el.querySelector('.step-label');
                const n = i + 1;

                circle.className =
                    'step-circle w-9 h-9 rounded-full border-2 flex items-center justify-center text-[13px] font-semibold mb-1.5 transition-all duration-300';
                label.className = 'step-label text-[11.5px] font-medium transition-colors duration-300';

                if (n < currentStep) {
                    circle.classList.add('bg-emerald-500', 'border-emerald-500', 'text-white');
                    label.classList.add('text-slate-700');
                    circle.innerHTML =
                        `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`;
                } else if (n === currentStep) {
                    circle.classList.add('bg-gradient-to-br', 'from-[#1e3a5f]', 'to-[#2563a8]', 'border-[#2563a8]',
                        'text-white', 'shadow-[0_0_0_4px_rgba(37,99,168,0.15)]');
                    label.classList.add('text-slate-700');
                    circle.textContent = n;
                } else {
                    circle.classList.add('bg-white', 'border-slate-200', 'text-slate-400');
                    label.classList.add('text-slate-400');
                    circle.textContent = n;
                }
            });
        }

        function updateProgressLine() {
            const pct = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressLine').style.width = pct + '%';
            document.getElementById('stepCounter').textContent = `Langkah ${currentStep} dari ${totalSteps}`;
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            prevBtn.style.visibility = currentStep === 1 ? 'hidden' : 'visible';

            if (currentStep === totalSteps) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-flex';
            } else {
                nextBtn.style.display = 'inline-flex';
                submitBtn.style.display = 'none';
            }
        }

        /* ─────────────────────────────────────────
        VALIDATION MODAL
        ───────────────────────────────────────── */
        function showValModal(errors) {
            document.getElementById('valModalList').innerHTML = errors
                .map(e =>
                    `<li class="flex items-start gap-2 text-[13px] text-neutral-700 bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-2 leading-snug"><span class="w-[17px] h-[17px] min-w-[17px] rounded-full bg-red-500 text-white text-[11px] font-bold flex items-center justify-center mt-px shrink-0">!</span>${e}</li>`
                )
                .join('');
            const overlay = document.getElementById('valModalOverlay');
            const modal = document.getElementById('valModal');
            overlay.classList.remove('pointer-events-none');
            overlay.style.opacity = '1';
            modal.style.transform = 'scale(1) translateY(0)';
        }

        function closeValModal(e) {
            if (e && e.target !== document.getElementById('valModalOverlay')) return;
            const overlay = document.getElementById('valModalOverlay');
            const modal = document.getElementById('valModal');
            overlay.style.opacity = '0';
            modal.style.transform = 'scale(0.95) translateY(8px)';
            setTimeout(() => overlay.classList.add('pointer-events-none'), 200);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeValModal();
        });

        /* ─────────────────────────────────────────
        PASSWORD RULES
        ───────────────────────────────────────── */
        const PASSWORD_RULES = [{
                id: 'rule-length',
                label: 'Password minimal 8 karakter',
                test: v => v.length >= 8
            },
            {
                id: 'rule-upper',
                label: 'Password harus ada huruf besar (A-Z)',
                test: v => /[A-Z]/.test(v)
            },
            {
                id: 'rule-lower',
                label: 'Password harus ada huruf kecil (a-z)',
                test: v => /[a-z]/.test(v)
            },
            {
                id: 'rule-number',
                label: 'Password harus ada angka (0-9)',
                test: v => /[0-9]/.test(v)
            },
            {
                id: 'rule-special',
                label: 'Password harus ada karakter khusus (@$!%*?&#)',
                test: v => /[@$!%*?&#]/.test(v)
            },
        ];

        function checkPasswordRules(val) {
            let passed = 0;
            PASSWORD_RULES.forEach(r => {
                const el = document.getElementById(r.id);
                if (!el) return;
                const ok = r.test(val);
                el.className = 'flex items-center gap-1.5 text-[12px] transition-colors duration-200';
                if (val.length > 0) {
                    if (ok) {
                        el.classList.add('text-emerald-500');
                        el.style.setProperty('--tw-before-bg', '#10b981');
                    } else {
                        el.classList.add('text-red-500');
                    }
                } else {
                    el.classList.add('text-slate-400');
                }
                if (ok) passed++;
            });
            return passed;
        }

        /* ─────────────────────────────────────────
        STEP VALIDATION
        ───────────────────────────────────────── */
        function validateStep(step) {
            let isValid = true;
            const errors = [];
            const stepEl = document.querySelector(`.form-step[data-step="${step}"]`);

            stepEl.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    const labelText = stepEl.querySelector(`label[for="${input.id}"]`)?.textContent
                        .replace('*', '').replace('(opsional)', '').trim() ?? 'Field';
                    errors.push(`${labelText} wajib diisi`);
                } else {
                    input.classList.remove('is-invalid');
                    input.style.borderColor = '';
                    input.style.background = '';
                }
            });

            if (step === 1 && !isGoogleUser) {
                const pw = document.getElementById('password');
                const pwc = document.getElementById('password_confirmation');
                const val = pw?.value ?? '';
                const passed = checkPasswordRules(val);

                if (!val || passed !== PASSWORD_RULES.length) {
                    isValid = false;
                    if (pw) pw.classList.add('is-invalid');
                    PASSWORD_RULES.filter(r => !r.test(val)).forEach(r => errors.push(r.label));
                }

                if (pw?.value && pwc?.value && pw.value !== pwc.value) {
                    isValid = false;
                    const err = document.getElementById('confirmError');
                    if (err) {
                        err.textContent = 'Password tidak cocok';
                        err.classList.remove('hidden');
                    }
                    if (pwc) pwc.classList.add('is-invalid');
                    errors.push('Konfirmasi password tidak cocok dengan password');
                } else if (pwc?.value && pw?.value === pwc.value) {
                    const err = document.getElementById('confirmError');
                    if (err) err.classList.add('hidden');
                    if (pwc) pwc.classList.remove('is-invalid');
                }
            }

            if (!isValid) showValModal([...new Set(errors)]);
            return isValid;
        }

        /* ─────────────────────────────────────────
        PASSWORD TOGGLE
        ───────────────────────────────────────── */
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.innerHTML = isPass ?
                `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>` :
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        }

        /* ─────────────────────────────────────────
        PASSWORD STRENGTH (non-Google)
        ───────────────────────────────────────── */
        if (!isGoogleUser) {
            document.getElementById('password')?.addEventListener('input', function() {
                const val = this.value;
                const passed = checkPasswordRules(val);
                const cls = passed <= 2 ? '#ef4444' : passed <= 3 ? '#f59e0b' : '#10b981';

                document.querySelectorAll('#strengthBars div').forEach((bar, i) => {
                    bar.style.background = i < passed ? cls : '';
                });

                if (val.length > 0) this.classList.remove('is-invalid');
            });

            /* ── Username availability check ── */
            let uTimer;
            document.getElementById('username')?.addEventListener('input', function() {
                clearTimeout(uTimer);
                const val = this.value.trim();
                const vIcon = document.getElementById('usernameValidation');
                const err = document.getElementById('usernameError');

                vIcon.className = 'absolute right-3 text-[13px] font-semibold pointer-events-none hidden';
                vIcon.textContent = '';
                err.className = 'hidden mt-1 text-[12px] font-medium';
                err.textContent = '';
                this.classList.remove('is-invalid');
                this.style.borderColor = '';

                if (!val) return;

                if (val.length < 6) {
                    setUsernameState('invalid', '×', 'Username minimal 6 karakter');
                    return;
                }
                if (!/^[a-zA-Z0-9_]+$/.test(val)) {
                    setUsernameState('invalid', '×', 'Hanya boleh huruf, angka, dan underscore');
                    return;
                }

                vIcon.className =
                    'absolute right-3 text-[13px] font-semibold pointer-events-none block text-slate-400';
                vIcon.textContent = '⟳';
                uTimer = setTimeout(() => checkUsername(val), 500);
            });

            function setUsernameState(state, icon, msg) {
                const el = document.getElementById('username');
                const vIcon = document.getElementById('usernameValidation');
                const err = document.getElementById('usernameError');

                const colors = {
                    valid: {
                        border: '#10b981',
                        icon: 'text-emerald-500'
                    },
                    invalid: {
                        border: '#ef4444',
                        icon: 'text-red-500'
                    }
                };

                el.style.borderColor = state === 'neutral' ? '' : colors[state]?.border ?? '';
                el.style.background = '';

                if (state === 'invalid') el.classList.add('is-invalid');
                else el.classList.remove('is-invalid');

                vIcon.className =
                    `absolute right-3 text-[13px] font-semibold pointer-events-none block ${colors[state]?.icon ?? ''}`;
                vIcon.textContent = icon;

                if (msg) {
                    err.textContent = msg;
                    err.className =
                        `block mt-1 text-[12px] font-medium ${state === 'valid' ? 'text-emerald-500' : 'text-red-500'}`;
                } else {
                    err.className = 'hidden mt-1 text-[12px] font-medium';
                }
            }

            function checkUsername(username) {
                fetch(
                        `{{ route('check-username') }}?username=${encodeURIComponent(username)}&user_id={{ $user->id }}`
                    )
                    .then(r => r.json())
                    .then(data => {
                        if (data.available) setUsernameState('valid', '✓', null);
                        else setUsernameState('invalid', '×', data.message);
                    })
                    .catch(() => {
                        const vIcon = document.getElementById('usernameValidation');
                        vIcon.className = 'absolute right-3 text-[13px] font-semibold pointer-events-none hidden';
                        vIcon.textContent = '';
                    });
            }
        }

        /* ─────────────────────────────────────────
        FILE UPLOAD
        ───────────────────────────────────────── */
        function setupFileUpload(inputId, boxId, previewId) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            const preview = document.getElementById(previewId);

            box.addEventListener('dragover', e => {
                e.preventDefault();
                box.classList.add('border-blue-400', 'bg-blue-50');
            });
            box.addEventListener('dragleave', e => {
                e.preventDefault();
                box.classList.remove('border-blue-400', 'bg-blue-50');
            });
            box.addEventListener('drop', e => {
                e.preventDefault();
                box.classList.remove('border-blue-400', 'bg-blue-50');
                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    handleFile(input, preview);
                }
            });
            input.addEventListener('change', () => handleFile(input, preview));
        }

        function handleFile(input, preview) {
            preview.innerHTML = '';
            const file = input.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                return;
            }
            if (!file.type.match('image/(jpeg|jpg|png)')) {
                alert('Format file harus JPG, JPEG, atau PNG');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `
                    <div class="relative w-[72px] h-[72px] rounded-lg overflow-hidden border border-slate-200">
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                        <button type="button"
                            class="absolute top-0.5 right-0.5 w-[18px] h-[18px] rounded-full bg-red-500/90 text-white border-none cursor-pointer flex items-center justify-center text-[11px] leading-none"
                            onclick="removeFile('${input.id}','${preview.id}')">×</button>
                    </div>`;
            };
            reader.readAsDataURL(file);
        }

        function removeFile(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).innerHTML = '';
        }

        setupFileUpload('admin_foto', 'adminFotoBox', 'adminFotoPreview');
        setupFileUpload('logo', 'logoBox', 'logoPreview');

        /* ─────────────────────────────────────────
        PHONE NUMBER — hanya angka & karakter telepon
        ───────────────────────────────────────── */
        ['admin_telepon', 'telepon'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;

            // Blokir karakter non-numerik saat mengetik
            el.addEventListener('keydown', function(e) {
                const allowed = [
                    'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                    'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                    'Home', 'End'
                ];
                if (allowed.includes(e.key)) return;
                // Izinkan: angka 0-9, +, -, (, ), spasi
                if (!/^[0-9\+\-\(\) ]$/.test(e.key)) {
                    e.preventDefault();
                }
            });

            // Bersihkan jika paste teks non-numerik
            el.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text');
                const cleaned = pasted.replace(/[^0-9\+\-\(\) ]/g, '');
                const pos = this.selectionStart;
                const current = this.value;
                this.value = current.slice(0, pos) + cleaned + current.slice(this.selectionEnd);
            });

            // Sanitasi jika ada nilai yang lolos (misal autofill)
            el.addEventListener('input', function() {
                const pos = this.selectionStart;
                const cleaned = this.value.replace(/[^0-9\+\-\(\) ]/g, '');
                if (this.value !== cleaned) {
                    this.value = cleaned;
                    this.setSelectionRange(pos - 1, pos - 1);
                }
            });
        });

        /* ─────────────────────────────────────────
        LOCATION DROPDOWNS
        ───────────────────────────────────────── */
        const selProvinsi = document.getElementById('provinsi_kode');
        const selKota = document.getElementById('kota_kode');
        const selKecamatan = document.getElementById('kecamatan_kode');
        const selKelurahan = document.getElementById('kelurahan_kode');
        const inputKodePos = document.getElementById('kode_pos');

        // Restore location if there's old input
        document.addEventListener('DOMContentLoaded', function() {
            const oldProv = '{{ old('provinsi_kode') }}';
            const oldKota = '{{ old('kota_kode') }}';
            const oldKec = '{{ old('kecamatan_kode') }}';
            const oldKel = '{{ old('kelurahan_kode') }}';

            if (oldProv) {
                fetchOptions(`{{ route('get-cities') }}?province_code=${oldProv}`, selKota, 'cities',
                        'Pilih kota/kabupaten', oldKota)
                    .then(() => {
                        if (oldKota) return fetchOptions(`{{ route('get-districts') }}?city_code=${oldKota}`,
                            selKecamatan, 'districts', 'Pilih kecamatan', oldKec);
                    })
                    .then(() => {
                        if (oldKec) return fetchOptions(`{{ route('get-villages') }}?district_code=${oldKec}`,
                            selKelurahan, 'villages', 'Pilih kelurahan/desa', oldKel);
                    });
            }
        });

        selProvinsi.addEventListener('change', function() {
            resetSelects([selKota, selKecamatan, selKelurahan]);
            inputKodePos.value = '';
            if (!this.value) return;
            fetchOptions(`{{ route('get-cities') }}?province_code=${this.value}`, selKota, 'cities',
                'Pilih kota/kabupaten');
        });

        selKota.addEventListener('change', function() {
            resetSelects([selKecamatan, selKelurahan]);
            inputKodePos.value = '';
            if (!this.value) return;
            fetchOptions(`{{ route('get-districts') }}?city_code=${this.value}`, selKecamatan, 'districts',
                'Pilih kecamatan');
        });

        selKecamatan.addEventListener('change', function() {
            resetSelects([selKelurahan]);
            inputKodePos.value = '';
            if (!this.value) return;
            fetchOptions(`{{ route('get-villages') }}?district_code=${this.value}`, selKelurahan, 'villages',
                'Pilih kelurahan/desa');
        });

        selKelurahan.addEventListener('change', function() {
            if (!this.value) return;
            fetch(`{{ route('get-postal-code') }}?village_code=${this.value}`)
                .then(r => r.json())
                .then(data => {
                    if (data.postal_code) inputKodePos.value = data.postal_code;
                });
        });

        function resetSelects(selects) {
            const labels = ['Pilih kota/kabupaten', 'Pilih kecamatan', 'Pilih kelurahan/desa'];
            selects.forEach((sel, i) => {
                sel.innerHTML = `<option value="">${labels[i] ?? 'Pilih...'}</option>`;
                sel.disabled = true;
            });
        }

        function fetchOptions(url, select, key, placeholder, selectedValue = null) {
            return fetch(url).then(r => r.json()).then(data => {
                select.disabled = false;
                select.innerHTML = `<option value="">${placeholder}</option>`;
                data[key].forEach(item => {
                    const opt = new Option(item.name, item.code);
                    if (selectedValue && item.code == selectedValue) opt.selected = true;
                    select.add(opt);
                });
                return data;
            });
        }

        /* ─────────────────────────────────────────
        FORM SUBMIT + reCAPTCHA
        ───────────────────────────────────────── */
        document.getElementById('completeProfileForm').addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
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
                            action: 'complete_profile'
                        })
                        .then(token => {
                            document.getElementById('recaptcha_token').value = token;
                            document.getElementById('completeProfileForm').submit();
                        })
                        .catch(() => {
                            btn.disabled = false;
                            btn.innerHTML = orig;
                            alert('Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
                        });
                });
            @endif
        });

        /* ─────────────────────────────────────────
        AUTO-DISMISS ALERTS
        ───────────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert').forEach(el => {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 5000);
            });
        });
    </script>
@endpush

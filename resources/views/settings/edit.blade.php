@extends('layouts.app')

@section('title', 'Edit Pengaturan')

@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Pengaturan Sistem</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi expo dan konfigurasi sistem</p>
                </div>
                <a href="{{ route('settings.show') }}"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Flash Message -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Form Edit -->
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informasi Expo -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Informasi Expo
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Expo -->
                        <div class="md:col-span-2">
                            <label for="logo_expo" class="block text-sm font-medium text-gray-700 mb-2">
                                Logo Expo
                            </label>
                            <div class="space-y-3">
                                @if ($setting && $setting->logo_expo)
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">Logo saat ini:</p>
                                        <img src="{{ asset('storage/' . $setting->logo_expo) }}" alt="Logo Expo"
                                            class="w-40 h-40 object-contain border border-gray-300 rounded-lg p-2 bg-white"
                                            id="logo-preview">
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <img id="logo-preview"
                                            class="w-40 h-40 object-contain border border-gray-300 rounded-lg p-2 bg-white hidden"
                                            alt="Preview">
                                    </div>
                                @endif
                                <input type="file" name="logo_expo" id="logo_expo" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('logo_expo') border-red-500 @enderror">
                                <p class="text-xs text-gray-500">Format: JPG, PNG, SVG (Max: 2MB)</p>
                                @error('logo_expo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama Expo -->
                        <div class="md:col-span-2">
                            <label for="nama_expo" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Expo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_expo" id="nama_expo"
                                value="{{ old('nama_expo', $setting->nama_expo ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('nama_expo') border-red-500 @enderror"
                                placeholder="Masukkan nama expo" required>
                            @error('nama_expo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tentang -->
                        <div class="md:col-span-2">
                            <label for="tentang" class="block text-sm font-medium text-gray-700 mb-2">
                                Tentang <span class="text-red-500">*</span>
                            </label>
                            <textarea name="tentang" id="tentang" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('tentang') border-red-500 @enderror"
                                placeholder="Deskripsi singkat tentang expo" required>{{ old('tentang', $setting->tentang ?? '') }}</textarea>
                            @error('tentang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $setting->email ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('email') border-red-500 @enderror"
                                placeholder="expo@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone', $setting->phone ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('phone') border-red-500 @enderror"
                                placeholder="Contoh: 081234567890">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat
                            </label>
                            <textarea name="alamat" id="alamat" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('alamat') border-red-500 @enderror"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat', $setting->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sosial Media -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Sosial Media
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Facebook -->
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline-block mr-2 text-blue-600" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Facebook
                            </label>
                            <input type="url" name="facebook" id="facebook"
                                value="{{ old('facebook', $sosmed->facebook ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('facebook') border-red-500 @enderror"
                                placeholder="https://facebook.com/expo">
                            @error('facebook')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline-block mr-2 text-pink-600" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                                Instagram
                            </label>
                            <input type="url" name="instagram" id="instagram"
                                value="{{ old('instagram', $sosmed->instagram ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('instagram') border-red-500 @enderror"
                                placeholder="https://instagram.com/expo">
                            @error('instagram')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- YouTube -->
                        <div>
                            <label for="youtube" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline-block mr-2 text-red-600" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                </svg>
                                YouTube
                            </label>
                            <input type="url" name="youtube" id="youtube"
                                value="{{ old('youtube', $sosmed->youtube ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('youtube') border-red-500 @enderror"
                                placeholder="https://youtube.com/@expo">
                            @error('youtube')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Konfigurasi ReCAPTCHA -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Konfigurasi ReCAPTCHA
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ReCAPTCHA Site Key -->
                        <div>
                            <label for="RECAPTCHA_SITE_KEY" class="block text-sm font-medium text-gray-700 mb-2">
                                ReCAPTCHA Site Key
                            </label>
                            <input type="text" name="RECAPTCHA_SITE_KEY" id="RECAPTCHA_SITE_KEY"
                                value="{{ old('RECAPTCHA_SITE_KEY', $recaptchaConfig->RECAPTCHA_SITE_KEY ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('RECAPTCHA_SITE_KEY') border-red-500 @enderror"
                                placeholder="Masukkan ReCAPTCHA Site Key">
                            @error('RECAPTCHA_SITE_KEY')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ReCAPTCHA Secret Key -->
                        <div>
                            <label for="RECAPTCHA_SECRET_KEY" class="block text-sm font-medium text-gray-700 mb-2">
                                ReCAPTCHA Secret Key
                            </label>
                            <div class="relative">
                                <input type="password" name="RECAPTCHA_SECRET_KEY" id="RECAPTCHA_SECRET_KEY"
                                    value="{{ old('RECAPTCHA_SECRET_KEY', $recaptchaConfig->RECAPTCHA_SECRET_KEY ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('RECAPTCHA_SECRET_KEY') border-red-500 @enderror"
                                    placeholder="Masukkan ReCAPTCHA Secret Key">
                                <button type="button" onclick="togglePassword('RECAPTCHA_SECRET_KEY')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-RECAPTCHA_SECRET_KEY" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-RECAPTCHA_SECRET_KEY" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('RECAPTCHA_SECRET_KEY')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Konfigurasi Google OAuth -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Konfigurasi Google OAuth
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Google Client ID -->
                        <div>
                            <label for="GOOGLE_CLIENT_ID" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Client ID
                            </label>
                            <input type="text" name="GOOGLE_CLIENT_ID" id="GOOGLE_CLIENT_ID"
                                value="{{ old('GOOGLE_CLIENT_ID', $googleConfig->GOOGLE_CLIENT_ID ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('GOOGLE_CLIENT_ID') border-red-500 @enderror"
                                placeholder="Masukkan Google Client ID">
                            @error('GOOGLE_CLIENT_ID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Client Secret -->
                        <div>
                            <label for="GOOGLE_CLIENT_SECRET" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Client Secret
                            </label>
                            <div class="relative">
                                <input type="password" name="GOOGLE_CLIENT_SECRET" id="GOOGLE_CLIENT_SECRET"
                                    value="{{ old('GOOGLE_CLIENT_SECRET', $googleConfig->GOOGLE_CLIENT_SECRET ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('GOOGLE_CLIENT_SECRET') border-red-500 @enderror"
                                    placeholder="Masukkan Google Client Secret">
                                <button type="button" onclick="togglePassword('GOOGLE_CLIENT_SECRET')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-GOOGLE_CLIENT_SECRET" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-GOOGLE_CLIENT_SECRET" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('GOOGLE_CLIENT_SECRET')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Redirect URI -->
                        <div>
                            <label for="GOOGLE_REDIRECT_URI" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Redirect URI
                            </label>
                            <input type="url" name="GOOGLE_REDIRECT_URI" id="GOOGLE_REDIRECT_URI"
                                value="{{ old('GOOGLE_REDIRECT_URI', $googleConfig->GOOGLE_REDIRECT_URI ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('GOOGLE_REDIRECT_URI') border-red-500 @enderror"
                                placeholder="https://example.com/auth/google/callback">
                            @error('GOOGLE_REDIRECT_URI')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Connect URL -->
                        <div>
                            <label for="GOOGLE_CONNECT_URL" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Connect URL
                            </label>
                            <input type="url" name="GOOGLE_CONNECT_URL" id="GOOGLE_CONNECT_URL"
                                value="{{ old('GOOGLE_CONNECT_URL', $googleConfig->GOOGLE_CONNECT_URL ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('GOOGLE_CONNECT_URL') border-red-500 @enderror"
                                placeholder="https://console.cloud.google.com/...">
                            @error('GOOGLE_CONNECT_URL')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Konfigurasi Mail (SMTP) -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        <svg class="w-5 h-5 inline-block mr-2 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Konfigurasi Mail (SMTP)
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Mail Mailer -->
                        <div>
                            <label for="MAIL_MAILER" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Mailer
                            </label>
                            <select name="MAIL_MAILER" id="MAIL_MAILER"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_MAILER') border-red-500 @enderror">
                                <option value="smtp"
                                    {{ old('MAIL_MAILER', $mailConfig->MAIL_MAILER ?? 'smtp') == 'smtp' ? 'selected' : '' }}>
                                    SMTP</option>
                                <option value="sendmail"
                                    {{ old('MAIL_MAILER', $mailConfig->MAIL_MAILER ?? '') == 'sendmail' ? 'selected' : '' }}>
                                    Sendmail</option>
                                <option value="mailgun"
                                    {{ old('MAIL_MAILER', $mailConfig->MAIL_MAILER ?? '') == 'mailgun' ? 'selected' : '' }}>
                                    Mailgun</option>
                                <option value="ses"
                                    {{ old('MAIL_MAILER', $mailConfig->MAIL_MAILER ?? '') == 'ses' ? 'selected' : '' }}>
                                    Amazon SES</option>
                                <option value="postmark"
                                    {{ old('MAIL_MAILER', $mailConfig->MAIL_MAILER ?? '') == 'postmark' ? 'selected' : '' }}>
                                    Postmark</option>
                            </select>
                            @error('MAIL_MAILER')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mail Host -->
                        <div>
                            <label for="MAIL_HOST" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Host
                            </label>
                            <input type="text" name="MAIL_HOST" id="MAIL_HOST"
                                value="{{ old('MAIL_HOST', $mailConfig->MAIL_HOST ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('MAIL_HOST') border-red-500 @enderror"
                                placeholder="smtp.gmail.com">
                            @error('MAIL_HOST')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mail Port -->
                        <div>
                            <label for="MAIL_PORT" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Port
                            </label>
                            <select name="MAIL_PORT" id="MAIL_PORT"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_PORT') border-red-500 @enderror">
                                <option value="587"
                                    {{ old('MAIL_PORT', $mailConfig->MAIL_PORT ?? '587') == '587' ? 'selected' : '' }}>587
                                    (TLS)</option>
                                <option value="465"
                                    {{ old('MAIL_PORT', $mailConfig->MAIL_PORT ?? '') == '465' ? 'selected' : '' }}>465
                                    (SSL)</option>
                                <option value="25"
                                    {{ old('MAIL_PORT', $mailConfig->MAIL_PORT ?? '') == '25' ? 'selected' : '' }}>25
                                    (Unencrypted)</option>
                                <option value="2525"
                                    {{ old('MAIL_PORT', $mailConfig->MAIL_PORT ?? '') == '2525' ? 'selected' : '' }}>2525
                                    (Alternative)</option>
                            </select>
                            @error('MAIL_PORT')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mail Username -->
                        <div>
                            <label for="MAIL_USERNAME" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Username
                            </label>
                            <input type="text" name="MAIL_USERNAME" id="MAIL_USERNAME"
                                value="{{ old('MAIL_USERNAME', $mailConfig->MAIL_USERNAME ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('MAIL_USERNAME') border-red-500 @enderror"
                                placeholder="your-email@gmail.com">
                            @error('MAIL_USERNAME')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mail Password -->
                        <div>
                            <label for="MAIL_PASSWORD" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Password
                                @if ($mailConfig && $mailConfig->MAIL_PASSWORD)
                                    <span class="text-xs text-gray-500 font-normal">(Kosongkan jika tidak ingin
                                        mengubah)</span>
                                @endif
                            </label>
                            <div class="relative">
                                <input type="password" name="MAIL_PASSWORD" id="MAIL_PASSWORD" value=""
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('MAIL_PASSWORD') border-red-500 @enderror"
                                    placeholder="{{ $mailConfig && $mailConfig->MAIL_PASSWORD ? '••••••••••••' : 'Masukkan App Password' }}">
                                <button type="button" onclick="togglePassword('MAIL_PASSWORD')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-MAIL_PASSWORD" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-MAIL_PASSWORD" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('MAIL_PASSWORD')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mail Encryption -->
                        <div>
                            <label for="MAIL_ENCRYPTION" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Encryption
                            </label>
                            <select name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_ENCRYPTION') border-red-500 @enderror">
                                <option value="tls"
                                    {{ old('MAIL_ENCRYPTION', $mailConfig->MAIL_ENCRYPTION ?? 'tls') == 'tls' ? 'selected' : '' }}>
                                    TLS</option>
                                <option value="ssl"
                                    {{ old('MAIL_ENCRYPTION', $mailConfig->MAIL_ENCRYPTION ?? '') == 'ssl' ? 'selected' : '' }}>
                                    SSL</option>
                                <option value=""
                                    {{ old('MAIL_ENCRYPTION', $mailConfig->MAIL_ENCRYPTION ?? '') == '' ? 'selected' : '' }}>
                                    None</option>
                            </select>
                            @error('MAIL_ENCRYPTION')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mail From Address -->
                        <div class="lg:col-span-3">
                            <label for="MAIL_FROM_ADDRESS" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail From Address
                            </label>
                            <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS"
                                value="{{ old('MAIL_FROM_ADDRESS', $mailConfig->MAIL_FROM_ADDRESS ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 font-mono text-sm @error('MAIL_FROM_ADDRESS') border-red-500 @enderror"
                                placeholder="noreply@expo.com">
                            @error('MAIL_FROM_ADDRESS')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium mb-1">Catatan untuk Gmail:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-600">
                                    <li>Gunakan <strong>App Password</strong> bukan password akun Gmail biasa</li>
                                    <li>Aktifkan 2-Step Verification di akun Google Anda</li>
                                    <li>Buat App Password di: <a href="https://myaccount.google.com/apppasswords"
                                            target="_blank" class="underline hover:text-blue-800">Google App Passwords</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('settings.show') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        input[type="file"]::file-selector-button {
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #e5e7eb;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-' + fieldId);
            const eyeSlashIcon = document.getElementById('eye-slash-' + fieldId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function testMail() {
            if (!confirm('Apakah Anda yakin ingin mengirim test email?')) {
                return;
            }

            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML =
                '<svg class="animate-spin w-4 h-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';

            fetch('{{ route('settings.test-mail') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                    } else {
                        alert('❌ ' + data.message);
                    }
                })
                .catch(error => {
                    alert('❌ Terjadi kesalahan: ' + error.message);
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide flash messages (Hanya untuk elemen dengan ID specifik if needed)
            // Namun secara global sudah dihandle di app.blade.php
            
            const logoInput = document.getElementById('logo_expo');
            if (logoInput) {
                logoInput.addEventListener('change', function(e) {
                    previewImage(this, 'logo-preview');
                });
            }
        });
    </script>
@endpush

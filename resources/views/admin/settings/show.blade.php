@extends('layouts.app')

@section('title', 'Pengaturan')
@section('page-title', 'Setting')

@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem</h1>
                    <p class="text-gray-600 mt-1">Kelola informasi expo dan konfigurasi sistem</p>
                </div>
                <a href="{{ route('admin.settings.edit') }}"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Pengaturan
                </a>
            </div>

            <!-- Informasi Expo -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                    Informasi Expo
                </h2>

                @if ($setting)
                    <!-- Logo Expo -->
                    <div class="mb-8">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Logo Expo
                        </label>

                        @if ($setting->logo_expo)
                            <div class="inline-block border border-gray-200 rounded-lg p-3 bg-white">
                                <img src="{{ asset('storage/' . $setting->logo_expo) }}" alt="Logo Expo"
                                    class="w-24 h-24 object-contain">
                            </div>
                        @else
                            <p class="text-sm text-gray-400">Logo belum diupload</p>
                        @endif
                    </div>

                    <!-- Data Utama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                        <!-- Nama Expo -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Nama Expo
                            </label>
                            <p class="text-gray-900 text-base">
                                {{ $setting->nama_expo }}
                            </p>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                No. Telepon
                            </label>
                            <p class="text-gray-900 text-base">
                                {{ $setting->phone ?? '-' }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Email
                            </label>
                            <p class="text-gray-900 text-base">
                                {{ $setting->email ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Tentang -->
                    <div class="mb-8">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Tentang
                        </label>
                        <p class="text-gray-900 text-base">
                            {{ $setting->tentang }}
                        </p>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-8">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Alamat
                        </label>
                        <p class="text-gray-900 text-base">
                            {{ $setting->alamat ?? '-' }}
                        </p>
                    </div>
                @else
                    <div class="text-center py-10">
                        <p class="text-gray-400">Belum ada data pengaturan</p>
                    </div>
                @endif
            </div>

            <!-- Sosial Media -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                    Sosial Media
                </h2>

                @if ($sosmed)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Facebook -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-blue-600" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Facebook
                            </label>
                            @if ($sosmed->facebook)
                                <a href="{{ $sosmed->facebook }}" target="_blank"
                                    class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                    {{ $sosmed->facebook }}
                                </a>
                            @else
                                <p class="text-sm text-gray-400">-</p>
                            @endif
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-pink-600" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                                Instagram
                            </label>
                            @if ($sosmed->instagram)
                                <a href="{{ $sosmed->instagram }}" target="_blank"
                                    class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                    {{ $sosmed->instagram }}
                                </a>
                            @else
                                <p class="text-sm text-gray-400">-</p>
                            @endif
                        </div>

                        <!-- YouTube -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                </svg>
                                YouTube
                            </label>
                            @if ($sosmed->youtube)
                                <a href="{{ $sosmed->youtube }}" target="_blank"
                                    class="text-primary-600 hover:text-primary-800 text-sm break-words block">
                                    {{ $sosmed->youtube }}
                                </a>
                            @else
                                <p class="text-sm text-gray-400">-</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400">Belum ada data sosial media</p>
                    </div>
                @endif
            </div>

            <!-- Konfigurasi ReCAPTCHA -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                    Konfigurasi ReCAPTCHA
                </h2>

                @if ($recaptchaConfig)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- ReCAPTCHA Site Key -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                ReCAPTCHA Site Key
                            </label>
                            <div class="flex items-center space-x-3">
                                <p id="recaptcha-site-text" class="text-gray-900 font-mono text-sm break-all hidden">
                                    {{ $recaptchaConfig->RECAPTCHA_SITE_KEY ?? '-' }}
                                </p>
                                <p id="recaptcha-site-masked" class="text-gray-900 font-mono text-sm">
                                    @if ($recaptchaConfig->RECAPTCHA_SITE_KEY)
                                        {{ substr($recaptchaConfig->RECAPTCHA_SITE_KEY, 0, 10) }}••••••••
                                    @else
                                        -
                                    @endif
                                </p>
                                @if ($recaptchaConfig->RECAPTCHA_SITE_KEY)
                                    <button type="button" onclick="toggleKey('recaptcha-site')"
                                        class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        <span id="recaptcha-site-toggle">Tampilkan</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- ReCAPTCHA Secret Key -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                ReCAPTCHA Secret Key
                            </label>
                            <div class="flex items-center space-x-3">
                                <p id="recaptcha-secret-text" class="text-gray-900 font-mono text-sm break-all hidden">
                                    {{ $recaptchaConfig->RECAPTCHA_SECRET_KEY ?? '-' }}
                                </p>
                                <p id="recaptcha-secret-masked" class="text-gray-900 font-mono text-sm">
                                    @if ($recaptchaConfig->RECAPTCHA_SECRET_KEY)
                                        {{ substr($recaptchaConfig->RECAPTCHA_SECRET_KEY, 0, 10) }}••••••••
                                    @else
                                        -
                                    @endif
                                </p>
                                @if ($recaptchaConfig->RECAPTCHA_SECRET_KEY)
                                    <button type="button" onclick="toggleKey('recaptcha-secret')"
                                        class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        <span id="recaptcha-secret-toggle">Tampilkan</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400">Belum ada konfigurasi ReCAPTCHA</p>
                    </div>
                @endif
            </div>

            <!-- Konfigurasi Google OAuth -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                    Konfigurasi Google OAuth
                </h2>

                @if ($googleConfig)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Google Client ID -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Google Client ID
                            </label>
                            <div class="flex items-center space-x-3">
                                <p id="google-client-text" class="text-gray-900 font-mono text-sm break-all hidden">
                                    {{ $googleConfig->GOOGLE_CLIENT_ID ?? '-' }}
                                </p>
                                <p id="google-client-masked" class="text-gray-900 font-mono text-sm">
                                    @if ($googleConfig->GOOGLE_CLIENT_ID)
                                        {{ substr($googleConfig->GOOGLE_CLIENT_ID, 0, 10) }}••••••••
                                    @else
                                        -
                                    @endif
                                </p>
                                @if ($googleConfig->GOOGLE_CLIENT_ID)
                                    <button type="button" onclick="toggleKey('google-client')"
                                        class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        <span id="google-client-toggle">Tampilkan</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Google Client Secret -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Google Client Secret
                            </label>
                            <div class="flex items-center space-x-3">
                                <p id="google-secret-text" class="text-gray-900 font-mono text-sm break-all hidden">
                                    {{ $googleConfig->GOOGLE_CLIENT_SECRET ?? '-' }}
                                </p>
                                <p id="google-secret-masked" class="text-gray-900 font-mono text-sm">
                                    @if ($googleConfig->GOOGLE_CLIENT_SECRET)
                                        {{ substr($googleConfig->GOOGLE_CLIENT_SECRET, 0, 10) }}••••••••
                                    @else
                                        -
                                    @endif
                                </p>
                                @if ($googleConfig->GOOGLE_CLIENT_SECRET)
                                    <button type="button" onclick="toggleKey('google-secret')"
                                        class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        <span id="google-secret-toggle">Tampilkan</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Google Redirect URI -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Google Redirect URI
                            </label>
                            <p class="text-gray-900 font-mono text-sm break-all">
                                {{ $googleConfig->GOOGLE_REDIRECT_URI ?? '-' }}
                            </p>
                        </div>

                        <!-- Google Connect URL -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Google Connect URL
                            </label>
                            @if ($googleConfig->GOOGLE_CONNECT_URL)
                                <a href="{{ $googleConfig->GOOGLE_CONNECT_URL }}" target="_blank"
                                    class="text-primary-600 hover:text-primary-800 font-mono text-sm break-all block">
                                    {{ $googleConfig->GOOGLE_CONNECT_URL }}
                                </a>
                            @else
                                <p class="text-gray-900 font-mono text-sm">-</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400">Belum ada konfigurasi Google OAuth</p>
                    </div>
                @endif
            </div>

            <!-- Konfigurasi Mail -->
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

                @if ($mailConfig)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Mail Mailer -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Mail Mailer
                            </label>
                            <p class="text-gray-900 text-sm">
                                {{ $mailConfig->MAIL_MAILER ?? 'smtp' }}
                            </p>
                        </div>

                        <!-- Mail Host -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Mail Host
                            </label>
                            <p class="text-gray-900 font-mono text-sm">
                                {{ $mailConfig->MAIL_HOST ?? '-' }}
                            </p>
                        </div>

                        <!-- Mail Port -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Mail Port
                            </label>
                            <p class="text-gray-900 font-mono text-sm">
                                {{ $mailConfig->MAIL_PORT ?? '-' }}
                            </p>
                        </div>

                        <!-- Mail Username -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Mail Username
                            </label>
                            <div class="flex items-center space-x-3">
                                <p id="mail-username-text" class="text-gray-900 font-mono text-sm break-all hidden">
                                    {{ $mailConfig->MAIL_USERNAME ?? '-' }}
                                </p>
                                <p id="mail-username-masked" class="text-gray-900 font-mono text-sm">
                                    @if ($mailConfig->MAIL_USERNAME)
                                        {{ substr($mailConfig->MAIL_USERNAME, 0, 5) }}••••••••
                                    @else
                                        -
                                    @endif
                                </p>
                                @if ($mailConfig->MAIL_USERNAME)
                                    <button type="button" onclick="toggleKey('mail-username')"
                                        class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        <span id="mail-username-toggle">Tampilkan</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Mail Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Mail Password
                            </label>
                            <p class="text-gray-900 font-mono text-sm">
                                @if ($mailConfig->MAIL_PASSWORD)
                                    ••••••••••••
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Mail Encryption -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Mail Encryption
                            </label>
                            <p class="text-gray-900 text-sm">
                                @if ($mailConfig->MAIL_ENCRYPTION)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase">
                                        {{ $mailConfig->MAIL_ENCRYPTION }}
                                    </span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Mail From Address -->
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Mail From Address
                            </label>
                            <p class="text-gray-900 font-mono text-sm">
                                {{ $mailConfig->MAIL_FROM_ADDRESS ?? '-' }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400">Belum ada konfigurasi mail</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleKey(keyType) {
            const textElement = document.getElementById(keyType + '-text');
            const maskedElement = document.getElementById(keyType + '-masked');
            const toggleButton = document.getElementById(keyType + '-toggle');

            if (textElement.classList.contains('hidden')) {
                textElement.classList.remove('hidden');
                maskedElement.classList.add('hidden');
                toggleButton.textContent = 'Sembunyikan';

                setTimeout(() => {
                    if (!textElement.classList.contains('hidden')) {
                        textElement.classList.add('hidden');
                        maskedElement.classList.remove('hidden');
                        toggleButton.textContent = 'Tampilkan';
                    }
                }, 30000);
            } else {
                textElement.classList.add('hidden');
                maskedElement.classList.remove('hidden');
                toggleButton.textContent = 'Tampilkan';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Script ini dikurangi karena sudah ada handler global di app.blade.php
            // untuk menghindari penghapusan elemen UI yang tidak sengaja.
        });
    </script>
@endpush

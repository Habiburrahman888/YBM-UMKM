@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex flex-row items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Profil Saya</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap tentang akun Anda</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">Edit Profil</span>
                        </a>
                        <div class="relative">
                            <button type="button" onclick="toggleSettingsMenu()"
                                class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="hidden sm:inline">Pengaturan</span>
                            </button>
                            <div id="settings_menu"
                                class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                <div class="py-1">
                                    <a href="{{ route('profile.password.edit') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Ubah Password
                                    </a>
                                    <a href="{{ route('profile.email.edit') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Ubah Email
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Profile Content --}}
            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-start gap-6">
                    {{-- Foto Profil --}}
                    <div class="flex-shrink-0">
                        @if ($user->foto_profil && file_exists(storage_path('app/public/' . $user->foto_profil)))
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Profile Photo"
                                class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-4 border-gray-100 shadow-md">
                        @else
                            <div
                                class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-primary to-primary-600 flex items-center justify-center shadow-md border-4 border-gray-100">
                                <span class="text-white text-2xl sm:text-3xl font-bold">
                                    {{ strtoupper(substr($user->username ?? $user->email, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Info User --}}
                    <div class="flex-1 w-full">
                        <div class="mb-3">
                            <div class="flex items-center gap-2">
                                <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                    {{ $user->username ?? 'User' }}
                                </h3>
                                @if ($user->email_verified_at)
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <span
                                class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded-full 
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'unit' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="break-all">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Terdaftar sejak {{ $user->created_at->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>
                                    Status:
                                    <span
                                        class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleSettingsMenu() {
            const menu = document.getElementById('settings_menu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.getElementById('settings_menu').classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                const settingsMenu = document.getElementById('settings_menu');
                const settingsButton = e.target.closest('button[onclick="toggleSettingsMenu()"]');

                if (!settingsButton && !settingsMenu.contains(e.target)) {
                    settingsMenu.classList.add('hidden');
                }
            });
        });
    </script>
@endpush
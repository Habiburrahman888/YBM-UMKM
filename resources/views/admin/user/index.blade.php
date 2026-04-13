@extends('layouts.app')

@section('title', 'Kelola Data Pengguna')
@section('page-title', 'Data Pengguna')

@push('styles')
    <style>
        .dropdown-menu {
            display: none;
            position: fixed;
            width: 13rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            z-index: 9999;
        }

        .dropdown-menu.open {
            display: block;
        }

        .dropdown-wrapper {
            position: relative;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card overflow-hidden animate-slide-up">

            {{-- ── HEADER ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Pengguna</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: <span class="font-medium text-gray-600">{{ $users->total() }}</span> Pengguna
                        </p>
                    </div>
                    <div class="flex items-center gap-2">

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('admin.user.create') }}"
                            class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">Tambah</span>
                        </a>

                        {{-- Tombol Filter --}}
                        @php
                            $hasFilter = request()->hasAny(['role', 'status', 'verified']);
                            $filterCount = collect(['role', 'status', 'verified'])
                                ->filter(fn($k) => request($k))
                                ->count();
                        @endphp
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg transition-all {{ $hasFilter ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">
                                Filter
                                @if ($hasFilter)
                                    <span
                                        class="inline-flex items-center justify-center w-4 h-4 text-xs bg-blue-600 text-white rounded-full ml-1">{{ $filterCount }}</span>
                                @endif
                            </span>
                        </button>

                        {{-- Tombol Cari --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width:280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="ml-2 hidden sm:inline-block">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('admin.user.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['role', 'status', 'verified'] as $filterKey)
                                    @if (request($filterKey))
                                        <input type="hidden" name="{{ $filterKey }}" value="{{ request($filterKey) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari username, email..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-xl bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request('q'))
                                        <a href="{{ route('admin.user.index', request()->except('q')) }}"
                                            class="ml-2 inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── PANEL FILTER ── --}}
            <div id="filter-section"
                class="{{ $hasFilter ? '' : 'hidden' }} transition-all duration-300">
                <div class="px-4 sm:px-6 py-4 bg-gray-50">
                    <form method="GET" action="{{ route('admin.user.index') }}">
                        @if (request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">

                            {{-- Role --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                                <select name="role"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="unit" {{ request('role') === 'unit' ? 'selected' : '' }}>Unit
                                    </option>
                                    <option value="umkm" {{ request('role') === 'umkm' ? 'selected' : '' }}>UMKM
                                    </option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                <select name="status"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>
                                        Non-Aktif</option>
                                </select>
                            </div>

                            {{-- Verifikasi Email --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Verifikasi Email</label>
                                <select name="verified"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua</option>
                                    <option value="verified" {{ request('verified') === 'verified' ? 'selected' : '' }}>
                                        Terverifikasi</option>
                                    <option value="unverified"
                                        {{ request('verified') === 'unverified' ? 'selected' : '' }}>Belum Verifikasi
                                    </option>
                                </select>
                            </div>

                            {{-- Tombol Aksi Filter --}}
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                                    Terapkan Filter
                                </button>
                                @if ($hasFilter)
                                    <a href="{{ route('admin.user.index', request()->only('q')) }}"
                                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                        Reset
                                    </a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            @if ($users->count() > 0)

                {{-- ── DESKTOP TABLE ── --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 w-20"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">

                                    {{-- User --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($user->foto_profil)
                                                <img src="{{ Storage::url($user->foto_profil) }}"
                                                    alt="{{ $user->username }}"
                                                    class="w-9 h-9 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                            @else
                                                <div
                                                    class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                    <span
                                                        class="text-xs font-semibold text-primary">{{ strtoupper(substr($user->username, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ '@' . $user->username }}
                                                </p>
                                                @if ($user->google_id)
                                                    <div class="flex items-center gap-1 mt-0.5">
                                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                            <path
                                                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                                                fill="#4285F4" />
                                                            <path
                                                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                                                fill="#34A853" />
                                                            <path
                                                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                                                fill="#FBBC05" />
                                                            <path
                                                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                                                fill="#EA4335" />
                                                        </svg>
                                                        <span class="text-xs text-gray-400">Google</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Email --}}
                                    <td class="px-4 py-4">
                                        <p class="text-sm text-gray-700">{{ $user->email }}</p>
                                        @if ($user->email_verified_at)
                                            <span class="inline-flex items-center gap-1 text-xs text-green-600 mt-0.5">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs text-yellow-600 mt-0.5">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Belum verifikasi
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Role --}}
                                    <td class="px-4 py-4">
                                        <span
                                            class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full
                                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $user->role === 'unit' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $user->role === 'umkm' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-4">
                                        @if ($user->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>Non-aktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="dropdown-wrapper">
                                            <button type="button"
                                                onclick="toggleDropdown('dd-{{ $user->uuid }}', this)"
                                                data-active="{{ $user->is_active ? 'true' : 'false' }}"
                                                data-verified="{{ $user->email_verified_at ? 'true' : 'false' }}"
                                                data-username="{{ $user->username }}" data-uuid="{{ $user->uuid }}"
                                                class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div id="dd-{{ $user->uuid }}" class="dropdown-menu">
                                                <div class="py-1">
                                                    <a href="{{ route('admin.user.edit', $user->uuid) }}"
                                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    @if (!$user->email_verified_at)
                                                        <button type="button"
                                                            onclick="confirmVerify('{{ $user->uuid }}','{{ $user->username }}')"
                                                            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-blue-600 hover:bg-blue-50 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Verifikasi Email
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        onclick="confirmStatus('{{ $user->uuid }}','{{ $user->username }}',{{ $user->is_active ? 'true' : 'false' }})"
                                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm transition-colors {{ $user->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} User
                                                    </button>
                                                    <div class="border-t border-gray-100 my-1"></div>
                                                    <button type="button"
                                                        onclick="confirmDelete('{{ $user->uuid }}','{{ $user->username }}')"
                                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                                {{-- ── MOBILE CARDS ── --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <div class="px-4 py-4 hover:bg-gray-50/60 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    @if ($user->foto_profil)
                                        <img src="{{ Storage::url($user->foto_profil) }}" alt="{{ $user->username }}"
                                            class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <span
                                                class="text-sm font-semibold text-primary">{{ strtoupper(substr($user->username, 0, 2)) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-sm font-semibold text-gray-900">{{ '@' . $user->username }}</h3>
                                            @if ($user->is_active)
                                                <span
                                                    class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Non-aktif
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                            <span
                                                class="px-2 py-0.5 text-xs font-semibold rounded-full
                                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $user->role === 'unit' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $user->role === 'umkm' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                            @if ($user->email_verified_at)
                                                <span class="inline-flex items-center gap-1 text-xs text-green-600">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Verified
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-wrapper flex-shrink-0">
                                    <button type="button" onclick="toggleDropdown('dd-{{ $user->uuid }}', this)"
                                        class="inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── Pagination ── --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4">
                    @include('partials.pagination', ['paginator' => $users])
                </div>
            @else
                {{-- ── EMPTY STATE ── --}}
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <svg class="mx-auto h-12 sm:h-16 w-12 sm:w-16 text-gray-400 mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm14 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                        {{ request()->hasAny(['q', 'role', 'status', 'verified']) ? 'Data Pengguna Tidak Ditemukan' : 'Belum Ada Data Pengguna' }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-4 sm:mb-6">
                        {{ request()->hasAny(['q', 'role', 'status', 'verified'])
                            ? 'Coba ubah filter atau kata kunci pencarian Anda.'
                            : 'Mulai tambahkan data pengguna untuk mengelola akses sistem.' }}
                    </p>
                    @if (!request()->hasAny(['q', 'role', 'status', 'verified']))
                        <a href="{{ route('admin.user.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all shadow-sm">
                            Tambah Pengguna
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── MODAL HAPUS ── --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="p-6 border border-gray-100 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1.5 text-center">Hapus Pengguna</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus user "<span id="modal-user-name"
                    class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs text-gray-400 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete-btn"
                    class="flex-1 rounded-xl px-4 py-2.5 bg-red-500 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL VERIFIKASI EMAIL ── --}}
    <div id="verify-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="p-6 border border-gray-100 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1.5 text-center">Verifikasi Email</h3>
            <p class="text-sm text-gray-500 mb-6 text-center">
                Verifikasi email untuk user "<span id="verify-modal-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('verify-modal').classList.add('hidden')"
                    class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-verify-btn"
                    class="flex-1 rounded-xl px-4 py-2.5 bg-blue-500 text-sm font-medium text-white hover:bg-blue-600 transition-colors">
                    Verifikasi
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL STATUS ── --}}
    <div id="status-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="p-6 border border-gray-100 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div id="status-icon-wrap" class="w-12 h-12 rounded-full flex items-center justify-center">
                    <svg id="status-icon" class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24"></svg>
                </div>
            </div>
            <h3 id="status-modal-title" class="text-base font-semibold text-gray-900 mb-1.5 text-center"></h3>
            <p class="text-sm text-gray-500 mb-6 text-center">
                Apakah Anda yakin ingin <span id="status-action-text" class="font-bold"></span> user
                "<span id="status-modal-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('status-modal').classList.add('hidden')"
                    class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-status-btn"
                    class="flex-1 rounded-xl px-4 py-2.5 text-sm font-medium text-white transition-colors">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Filter Panel ──
        function toggleFilter() {
            document.getElementById('filter-section').classList.toggle('hidden');
        }

        // ── Search Bar Toggle ──
        function toggleSearch() {
            const btn = document.getElementById('search-button');
            const form = document.getElementById('search-form');
            const input = document.getElementById('search-input');
            const cont = document.getElementById('search-container');
            if (form.classList.contains('hidden')) {
                btn.classList.add('hidden');
                form.classList.remove('hidden');
                cont.style.minWidth = '280px';
                setTimeout(() => input.focus(), 50);
            } else {
                if (!'{{ request('q') }}') input.value = '';
                form.classList.add('hidden');
                btn.classList.remove('hidden');
                cont.style.minWidth = 'auto';
            }
        }

        // ── Dropdown Aksi (pola UMKM) ──
        function toggleDropdown(menuId, btn) {
            document.querySelectorAll('.dropdown-menu.open').forEach(m => {
                if (m.id !== menuId) m.classList.remove('open');
            });
            const menu = document.getElementById(menuId);
            if (!menu) return;
            if (menu.classList.contains('open')) {
                menu.classList.remove('open');
                return;
            }
            menu.classList.add('open');
            const rect = btn.getBoundingClientRect();
            const menuW = 208;
            const menuH = menu.scrollHeight;
            let top = rect.bottom + 4;
            let left = rect.right - menuW;
            if (left < 8) left = 8;
            if (top + menuH > window.innerHeight - 8) top = rect.top - menuH - 4;
            menu.style.top = top + 'px';
            menu.style.left = left + 'px';
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-wrapper') && !e.target.closest('.dropdown-menu'))
                document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        });

        window.addEventListener('scroll', function() {
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }, true);

        // ── Submit Action ──
        function submitAction(action, method) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            if (method !== 'POST') {
                const m = document.createElement('input');
                m.type = 'hidden';
                m.name = '_method';
                m.value = method;
                form.appendChild(m);
            }
            document.body.appendChild(form);
            form.submit();
        }

        // ── Confirm Delete ──
        let deleteUuid = null;

        function confirmDelete(uuid, nama) {
            deleteUuid = uuid;
            document.getElementById('modal-user-name').textContent = nama;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }
        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteUuid) submitAction('/admin/user/' + deleteUuid, 'DELETE');
        });
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });

        // ── Confirm Verify ──
        let verifyUuid = null;

        function confirmVerify(uuid, nama) {
            verifyUuid = uuid;
            document.getElementById('verify-modal-name').textContent = nama;
            document.getElementById('verify-modal').classList.remove('hidden');
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }
        document.getElementById('confirm-verify-btn').addEventListener('click', function() {
            if (verifyUuid) submitAction('{{ route('admin.user.verify-email', ':id') }}'.replace(':id', verifyUuid),
                'POST');
        });
        document.getElementById('verify-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });

        // ── Confirm Status ──
        let statusUuid = null,
            statusIsActive = false;

        function confirmStatus(uuid, nama, isActive) {
            statusUuid = uuid;
            statusIsActive = isActive;
            document.getElementById('status-modal-name').textContent = nama;
            document.getElementById('status-action-text').textContent = isActive ? 'menonaktifkan' : 'mengaktifkan';
            document.getElementById('status-modal-title').textContent = isActive ? 'Nonaktifkan User' : 'Aktifkan User';

            const wrap = document.getElementById('status-icon-wrap');
            const icon = document.getElementById('status-icon');
            const btn = document.getElementById('confirm-status-btn');

            if (isActive) {
                wrap.className = 'w-12 h-12 rounded-full flex items-center justify-center bg-red-50';
                icon.className = 'h-6 w-6 text-red-500';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                btn.className =
                    'flex-1 rounded-xl px-4 py-2.5 text-sm font-medium text-white transition-colors bg-red-500 hover:bg-red-600';
            } else {
                wrap.className = 'w-12 h-12 rounded-full flex items-center justify-center bg-green-50';
                icon.className = 'h-6 w-6 text-green-500';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                btn.className =
                    'flex-1 rounded-xl px-4 py-2.5 text-sm font-medium text-white transition-colors bg-green-500 hover:bg-green-600';
            }

            document.getElementById('status-modal').classList.remove('hidden');
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }
        document.getElementById('confirm-status-btn').addEventListener('click', function() {
            if (statusUuid) submitAction('{{ route('admin.user.toggle-status', ':id') }}'.replace(':id', statusUuid),
                'POST');
        });
        document.getElementById('status-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    </script>
@endpush

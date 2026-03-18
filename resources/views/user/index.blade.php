@extends('layouts.app')

@section('title', 'Kelola Data Pengguna')
@section('page-title', 'Data Pengguna')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Pengguna</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $users->total() }} Pengguna</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('user.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Tambah
                            </span>
                        </a>
                        <button type="button" onclick="toggleFilterPanel()" id="filter-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Filter
                            </span>
                        </button>
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span id="search-button-text"
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                    Cari
                                </span>
                            </button>
                            <form method="GET" action="{{ route('user.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if (request('role'))
                                    <input type="hidden" name="role" value="{{ request('role') }}">
                                @endif
                                @if (request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if (request('verified'))
                                    <input type="hidden" name="verified" value="{{ request('verified') }}">
                                @endif
                                <div class="flex items-center">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari username, email..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Filter Panel - Hidden by default -->
                <div id="filter-panel"
                    class="{{ request('role') || request('status') || request('verified') ? '' : 'hidden' }} mt-3 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Role</label>
                            <select name="role" onchange="applyFilter(this, 'role')" id="filter-role"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="unit" {{ request('role') == 'unit' ? 'selected' : '' }}>Unit</option>
                                <option value="umkm" {{ request('role') == 'umkm' ? 'selected' : '' }}>UMKM</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" onchange="applyFilter(this, 'status')" id="filter-status"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif
                                </option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Verifikasi Email</label>
                            <select name="verified" onchange="applyFilter(this, 'verified')" id="filter-verified"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">Semua</option>
                                <option value="verified" {{ request('verified') == 'verified' ? 'selected' : '' }}>
                                    Terverifikasi</option>
                                <option value="unverified" {{ request('verified') == 'unverified' ? 'selected' : '' }}>
                                    Belum Verifikasi
                                </option>
                            </select>
                        </div>
                        @if (request('role') || request('status') || request('verified') || request('q'))
                            <div class="flex items-end">
                                <a href="{{ route('user.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-sm text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($users->count() > 0)
                <!-- Desktop View - Table -->
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($item->foto_profil)
                                                    <img src="{{ Storage::url($item->foto_profil) }}"
                                                        alt="{{ $item->username }}"
                                                        class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                                        <span class="text-primary font-semibold text-sm">
                                                            {{ strtoupper(substr($item->username, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ '@' . $item->username }}
                                                </div>
                                                @if ($item->google_id)
                                                    <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24"
                                                            fill="currentColor">
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
                                                        Google
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->email }}</div>
                                        @if ($item->email_verified_at)
                                            <span class="inline-flex items-center text-xs text-green-600">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-xs text-yellow-600">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Belum verifikasi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $item->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $item->role === 'unit' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $item->role === 'umkm' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($item->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm {{ $item->is_active ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button" data-dropdown-toggle="{{ $item->uuid }}"
                                                data-username="{{ $item->username }}"
                                                data-verified="{{ $item->email_verified_at ? 'true' : 'false' }}"
                                                class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View - Cards -->
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($users as $item)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex gap-3">
                                @if ($item->foto_profil)
                                    <img src="{{ Storage::url($item->foto_profil) }}" alt="{{ $item->username }}"
                                        class="flex-shrink-0 h-12 w-12 rounded-full object-cover">
                                @else
                                    <div
                                        class="flex-shrink-0 h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span class="text-primary font-semibold">
                                            {{ strtoupper(substr($item->username, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-semibold text-gray-900">{{ '@' . $item->username }}
                                            </h3>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->email }}</p>
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $item->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                                    {{ $item->role === 'unit' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $item->role === 'umkm' ? 'bg-green-100 text-green-800' : '' }}">
                                                    {{ ucfirst($item->role) }}
                                                </span>
                                                <span
                                                    class="text-xs {{ $item->is_active ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $item->is_active ? 'Aktif' : 'Non-aktif' }}
                                                </span>
                                                @if ($item->email_verified_at)
                                                    <span class="inline-flex items-center text-xs text-green-600">
                                                        <svg class="w-3 h-3 mr-0.5" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Verified
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button type="button" data-dropdown-toggle="{{ $item->uuid }}"
                                            data-username="{{ $item->username }}"
                                            data-verified="{{ $item->email_verified_at ? 'true' : 'false' }}"
                                            class="dropdown-toggle flex-shrink-0 ml-2 inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data User</h3>
                    <p class="text-sm text-gray-500 mb-6">Mulai tambahkan data user untuk mengelola akses sistem.</p>
                    <a href="{{ route('user.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah User
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
                <button type="button" id="dropdown-verify-btn"
                    class="hidden flex items-center w-full px-3 sm:px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Verifikasi Email
                </button>
                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus User</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus user
                "<span id="modal-user-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Verify Email Modal -->
    <div id="verify-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Verifikasi Email</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">
                Verifikasi email untuk user "<span id="verify-modal-user-name"
                    class="font-semibold text-gray-700"></span>"?
            </p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-verify-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-verify-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-blue-600 text-xs sm:text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Verifikasi
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentDropdownData = null;

        function applyFilter(select, name) {
            const url = new URL(window.location.href);
            if (select.value) {
                url.searchParams.set(name, select.value);
            } else {
                url.searchParams.delete(name);
            }
            // Tambahkan parameter untuk keep filter panel open
            url.searchParams.set('filter_open', '1');
            window.location.href = url.toString();
        }

        function toggleFilterPanel() {
            const filterPanel = document.getElementById('filter-panel');
            filterPanel.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const verifyBtn = document.getElementById('dropdown-verify-btn');
            const tableContainer = document.getElementById('table-container');
            const filterPanel = document.getElementById('filter-panel');

            // Check if filter should be open on page load
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('filter_open') === '1') {
                filterPanel.classList.remove('hidden');
                // Remove the filter_open parameter from URL without reload
                urlParams.delete('filter_open');
                const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                window.history.replaceState({}, '', newUrl);
            }

            // Handle dropdown toggle
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();
                    const dropdownId = toggle.getAttribute('data-dropdown-toggle');
                    const userName = toggle.getAttribute('data-username');
                    const isVerified = toggle.getAttribute('data-verified') === 'true';

                    if (dropdownContainer.getAttribute('data-current-id') === dropdownId &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-id');
                        return;
                    }

                    dropdownContainer.setAttribute('data-current-id', dropdownId);

                    const rect = toggle.getBoundingClientRect();
                    const scrollY = window.scrollY || window.pageYOffset;
                    const scrollX = window.scrollX || window.pageXOffset;
                    const dropdownWidth = window.innerWidth < 640 ? 176 : 192;

                    let top = rect.bottom + scrollY + 8;
                    let left = rect.right + scrollX - dropdownWidth;

                    const dropdownHeight = 120;

                    if (left + dropdownWidth > window.innerWidth + scrollX) {
                        left = window.innerWidth + scrollX - dropdownWidth - 16;
                    }
                    if (left < scrollX) {
                        left = scrollX + 16;
                    }
                    if (top + dropdownHeight > window.innerHeight + scrollY) {
                        top = rect.top + scrollY - dropdownHeight - 8;
                    }

                    dropdownContainer.style.top = top + 'px';
                    dropdownContainer.style.left = left + 'px';

                    editLink.href = `/user/${dropdownId}/edit`;

                    // Show/hide verify button based on verification status
                    if (isVerified) {
                        verifyBtn.classList.add('hidden');
                    } else {
                        verifyBtn.classList.remove('hidden');
                    }

                    currentDropdownData = {
                        id: dropdownId,
                        username: userName,
                        verified: isVerified
                    };

                    dropdownContainer.classList.remove('hidden');
                } else {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-id');
                }
            });

            // Delete button handler
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!currentDropdownData) return;

                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-id');

                const modal = document.getElementById('delete-modal');
                const modalName = document.getElementById('modal-user-name');
                modalName.textContent = currentDropdownData.username;
                modal.classList.remove('hidden');
            });

            // Verify button handler
            verifyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!currentDropdownData) return;

                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-id');

                const modal = document.getElementById('verify-modal');
                const modalName = document.getElementById('verify-modal-user-name');
                modalName.textContent = currentDropdownData.username;
                modal.classList.remove('hidden');
            });

            // Confirm delete
            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (!currentDropdownData) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/user/${currentDropdownData.id}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            });

            // Confirm verify
            document.getElementById('confirm-verify-btn').addEventListener('click', function() {
                if (!currentDropdownData) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/user/${currentDropdownData.id}/verify-email`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            });

            // Cancel modals
            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            document.getElementById('cancel-verify-btn').addEventListener('click', function() {
                document.getElementById('verify-modal').classList.add('hidden');
            });

            // Close modal on backdrop click
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });

            document.getElementById('verify-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });

            // Close dropdown on scroll
            window.addEventListener('scroll', function() {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-id');
                }
            }, true);

            if (tableContainer) {
                tableContainer.addEventListener('scroll', function() {
                    if (!dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-id');
                    }
                }, true);
            }

            // Close dropdown on resize
            window.addEventListener('resize', function() {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-id');
                }
            });
        });

        function toggleSearch() {
            const searchButton = document.getElementById('search-button');
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');
            const searchContainer = document.getElementById('search-container');

            if (searchForm.classList.contains('hidden')) {
                searchButton.classList.add('hidden');
                searchForm.classList.remove('hidden');
                searchContainer.style.minWidth = '280px';
                setTimeout(() => searchInput.focus(), 50);
            } else {
                const hasQuery = '{{ request('q') }}' !== '';
                if (!hasQuery) {
                    searchInput.value = '';
                }
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }
    </script>
@endpush

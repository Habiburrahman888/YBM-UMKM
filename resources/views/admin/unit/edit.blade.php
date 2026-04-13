@extends('layouts.app')

@section('title', 'Edit Unit')
@section('page-title', 'Edit Unit')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Unit</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi unit {{ $unit->nama_unit }}</p>
            </div>

            <form action="{{ route('admin.unit.update', $unit->uuid) }}" method="POST" enctype="multipart/form-data" id="unit-form"
                class="p-4 sm:p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- ══ SEKSI 1: USER PEMILIK ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">1</span>
                            User Pemilik
                        </h3>
                        <div class="space-y-4">

                            {{-- Pilih User --}}
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Pilih User <span class="text-red-500">*</span>
                                </label>
                                <select name="user_id" id="user_id"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('user_id') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih User —</option>
                                    @forelse($availableUsers as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $unit->user_id) == $user->id ? 'selected' : '' }}
                                            data-email="{{ $user->email }}" data-username="{{ $user->username ?? '' }}"
                                            data-verified="{{ $user->email_verified_at ? 'true' : 'false' }}"
                                            data-active="{{ $user->is_active ? 'true' : 'false' }}">
                                            {{ $user->email }}{{ $user->username ? " ({$user->username})" : '' }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Tidak ada user tersedia</option>
                                    @endforelse
                                </select>
                                @error('user_id')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="mt-1 text-xs text-gray-500">Hanya menampilkan user dengan role "unit" yang
                                        belum memiliki unit (atau user saat ini)</p>
                                @enderror
                            </div>

                            {{-- User Preview --}}
                            <div id="user-info-preview" class="{{ old('user_id', $unit->user_id) ? '' : 'hidden' }}">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        @if ($unit->user && $unit->user->foto_profil)
                                            <img src="{{ Storage::url($unit->user->foto_profil) }}"
                                                alt="{{ $unit->user->username }}"
                                                class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" id="preview-email">
                                            {{ old('user_id') ? '' : $unit->user->email ?? '' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5" id="preview-username">
                                            {{ old('user_id') ? '' : $unit->user->username ?? 'Belum set username' }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Unit</span>
                                            <span id="preview-verified-badge"
                                                class="px-2 py-0.5 text-xs font-medium rounded-full {{ $unit->user && $unit->user->email_verified_at ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ $unit->user && $unit->user->email_verified_at ? '✓ Terverifikasi' : 'Belum Terverifikasi' }}
                                            </span>
                                            <span id="preview-active-badge"
                                                class="px-2 py-0.5 text-xs font-medium rounded-full {{ $unit->user && $unit->user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $unit->user && $unit->user->is_active ? 'Aktif' : 'Non-aktif' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ══ SEKSI 2: INFORMASI DASAR ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">2</span>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Nama Unit --}}
                            <div>
                                <label for="nama_unit" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Unit <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_unit" id="nama_unit"
                                    value="{{ old('nama_unit', $unit->nama_unit) }}"
                                    placeholder="Contoh: Kantor Cabang Jakarta Pusat"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama_unit') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('nama_unit')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kode Unit --}}
                            <div>
                                <label for="kode_unit" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kode Unit
                                </label>
                                <input type="text" value="{{ $unit->kode_unit }}" readonly
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none transition-all">
                                <p class="mt-1 text-xs text-gray-400">Kode unit diatur otomatis oleh sistem</p>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Status Unit <span class="text-red-500">*</span>
                                </label>
                                <select name="is_active" id="is_active"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('is_active') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="1"
                                        {{ old('is_active', $unit->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0"
                                        {{ old('is_active', $unit->is_active) == '0' ? 'selected' : '' }}>Non-aktif
                                    </option>
                                </select>
                                @error('is_active')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Logo Unit --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Logo Unit <span class="text-xs font-normal text-gray-400">(opsional)</span>
                                </label>
                                <div class="flex items-start gap-4">
                                    <div id="logo-preview-wrapper"
                                        class="{{ $unit->logo ? '' : 'hidden' }} w-16 h-16 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0">
                                        <img id="logo-preview" src="{{ $unit->logo ? Storage::url($unit->logo) : '#' }}"
                                            alt="Logo" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <label for="logo"
                                            class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <svg class="mx-auto w-6 h-6 text-gray-400 mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span id="logo-label" class="text-xs text-gray-500">
                                                    {{ $unit->logo ? 'Upload logo baru' : 'Klik untuk upload logo' }}
                                                </span>
                                            </div>
                                        </label>
                                        <input type="file" name="logo" id="logo"
                                            accept="image/jpg,image/jpeg,image/png,image/webp,image/svg+xml"
                                            class="hidden">
                                        <p class="mt-1 text-xs text-gray-400">Format: JPG, JPEG, PNG, SVG. Maks. 2MB.</p>
                                        @if ($unit->logo)
                                            <label class="inline-flex items-center mt-1.5 cursor-pointer">
                                                <input type="checkbox" name="remove_logo" value="1"
                                                    class="rounded border-gray-300 text-red-500 focus:ring-red-400">
                                                <span class="ml-2 text-xs text-red-500">Hapus logo saat ini</span>
                                            </label>
                                        @endif
                                        @error('logo')
                                            <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="sm:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Deskripsi Unit <span class="text-xs font-normal text-gray-400">(opsional)</span>
                                </label>
                                <textarea name="deskripsi" id="deskripsi" rows="3"
                                    placeholder="Jelaskan deskripsi singkat tentang unit ini..."
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('deskripsi') border-red-500 ring-1 ring-red-500 @enderror">{{ old('deskripsi', $unit->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="sm:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat" id="alamat" rows="2"
                                    placeholder="Jl. Contoh No. 123, RT 01/RW 02"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('alamat') border-red-500 ring-1 ring-red-500 @enderror">{{ old('alamat', $unit->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ══ SEKSI 3: ADMIN UNIT ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">3</span>
                            Admin Unit
                            <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Nama Admin --}}
                            <div>
                                <label for="admin_nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Admin
                                </label>
                                <input type="text" name="admin_nama" id="admin_nama"
                                    value="{{ old('admin_nama', $unit->admin_nama) }}" placeholder="Nama lengkap admin"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('admin_nama') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('admin_nama')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Telepon Admin --}}
                            <div>
                                <label for="admin_telepon" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Telepon Admin
                                </label>
                                <input type="text" name="admin_telepon" id="admin_telepon"
                                    value="{{ old('admin_telepon', $unit->admin_telepon) }}" placeholder="08xx-xxxx-xxxx"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('admin_telepon') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('admin_telepon')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email Admin --}}
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Email Admin
                                </label>
                                <input type="email" name="admin_email" id="admin_email"
                                    value="{{ old('admin_email', $unit->admin_email) }}" placeholder="admin@example.com"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('admin_email') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('admin_email')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Foto Admin --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Foto Admin
                                </label>
                                <div class="flex items-start gap-4">
                                    <div id="admin-foto-preview-wrapper"
                                        class="{{ $unit->admin_foto ? '' : 'hidden' }} w-16 h-16 rounded-full border border-gray-200 overflow-hidden flex-shrink-0">
                                        <img id="admin-foto-preview"
                                            src="{{ $unit->admin_foto ? Storage::url($unit->admin_foto) : '#' }}"
                                            alt="Foto Admin" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <label for="admin_foto"
                                            class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <svg class="mx-auto w-6 h-6 text-gray-400 mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span id="admin-foto-label" class="text-xs text-gray-500">
                                                    {{ $unit->admin_foto ? 'Upload foto baru' : 'Klik untuk upload foto' }}
                                                </span>
                                            </div>
                                        </label>
                                        <input type="file" name="admin_foto" id="admin_foto"
                                            accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">
                                        <p class="mt-1 text-xs text-gray-400">Format: JPG, JPEG, PNG. Maks. 2MB.</p>
                                        @if ($unit->admin_foto)
                                            <label class="inline-flex items-center mt-1.5 cursor-pointer">
                                                <input type="checkbox" name="remove_admin_foto" value="1"
                                                    class="rounded border-gray-300 text-red-500 focus:ring-red-400">
                                                <span class="ml-2 text-xs text-red-500">Hapus foto saat ini</span>
                                            </label>
                                        @endif
                                        @error('admin_foto')
                                            <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ══ SEKSI 4: WILAYAH ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">4</span>
                            Wilayah
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Provinsi --}}
                            <div>
                                <label for="provinsi_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Provinsi
                                </label>
                                <select name="provinsi_kode" id="provinsi_kode"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('provinsi_kode') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Provinsi —</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->code }}"
                                            {{ old('provinsi_kode', $unit->provinsi_kode) == $province->code ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('provinsi_kode')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kota / Kabupaten --}}
                            <div>
                                <label for="kota_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kota / Kabupaten
                                </label>
                                <select name="kota_kode" id="kota_kode" {{ $cities->isEmpty() ? 'disabled' : '' }}
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kota_kode') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kota —</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->code }}"
                                            {{ old('kota_kode', $unit->kota_kode) == $city->code ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kota_kode')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kecamatan --}}
                            <div>
                                <label for="kecamatan_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kecamatan
                                </label>
                                <select name="kecamatan_kode" id="kecamatan_kode"
                                    {{ $districts->isEmpty() ? 'disabled' : '' }}
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kecamatan_kode') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kecamatan —</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->code }}"
                                            {{ old('kecamatan_kode', $unit->kecamatan_kode) == $district->code ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kecamatan_kode')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kelurahan / Desa --}}
                            <div>
                                <label for="kelurahan_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kelurahan / Desa
                                </label>
                                <select name="kelurahan_kode" id="kelurahan_kode"
                                    {{ $villages->isEmpty() ? 'disabled' : '' }}
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kelurahan_kode') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kelurahan —</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->code }}"
                                            {{ old('kelurahan_kode', $unit->kelurahan_kode) == $village->code ? 'selected' : '' }}>
                                            {{ $village->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelurahan_kode')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kode Pos --}}
                            <div>
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kode Pos
                                </label>
                                <input type="text" name="kode_pos" id="kode_pos"
                                    value="{{ old('kode_pos', $unit->kode_pos) }}" placeholder="Contoh: 12345"
                                    maxlength="5"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kode_pos') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('kode_pos')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ══ SEKSI 5: KONTAK UNIT ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">5</span>
                            Kontak Unit
                            <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Telepon Unit --}}
                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Telepon Unit
                                </label>
                                <input type="text" name="telepon" id="telepon"
                                    value="{{ old('telepon', $unit->telepon) }}" placeholder="021-xxxx-xxxx"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('telepon') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('telepon')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email Unit --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Email Unit
                                </label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $unit->email) }}" placeholder="unit@example.com"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('email') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ── TOMBOL AKSI ── --}}
                <div
                    class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.unit.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Update Unit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ─── User Preview ────────────────────────────────────────────────────────
            function syncUserPreview(opt) {
                const preview = document.getElementById('user-info-preview');
                if (!opt || !opt.value) {
                    preview.classList.add('hidden');
                    return;
                }

                document.getElementById('preview-email').textContent = opt.getAttribute('data-email');
                document.getElementById('preview-username').textContent =
                    opt.getAttribute('data-username') || 'Belum set username';

                const verified = opt.getAttribute('data-verified') === 'true';
                const active = opt.getAttribute('data-active') === 'true';

                const verifiedBadge = document.getElementById('preview-verified-badge');
                verifiedBadge.className =
                    `px-2 py-0.5 text-xs font-medium rounded-full ${verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}`;
                verifiedBadge.textContent = verified ? '✓ Terverifikasi' : 'Belum Terverifikasi';

                const activeBadge = document.getElementById('preview-active-badge');
                activeBadge.className =
                    `px-2 py-0.5 text-xs font-medium rounded-full ${active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
                activeBadge.textContent = active ? 'Aktif' : 'Non-aktif';

                preview.classList.remove('hidden');
            }

            const userSelect = document.getElementById('user_id');
            userSelect.addEventListener('change', function() {
                syncUserPreview(this.options[this.selectedIndex]);
            });

            // Sync saat old() terpilih (setelah validasi gagal)
            if (userSelect.value) {
                syncUserPreview(userSelect.options[userSelect.selectedIndex]);
            }

            // ─── Preview Gambar (reusable) ────────────────────────────────────────────
            function handlePreview(inputId, previewImgId, wrapperId, labelId, newLabel) {
                document.getElementById(inputId).addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(previewImgId).src = e.target.result;
                        document.getElementById(wrapperId).classList.remove('hidden');
                        document.getElementById(labelId).textContent = file.name;
                    };
                    reader.readAsDataURL(file);
                });
            }

            handlePreview('logo', 'logo-preview', 'logo-preview-wrapper', 'logo-label');
            handlePreview('admin_foto', 'admin-foto-preview', 'admin-foto-preview-wrapper', 'admin-foto-label');

            // ─── AJAX Wilayah Bertingkat ──────────────────────────────────────────────
            const provinsiSelect = document.getElementById('provinsi_kode');
            const kotaSelect = document.getElementById('kota_kode');
            const kecamatanSelect = document.getElementById('kecamatan_kode');
            const kelurahanSelect = document.getElementById('kelurahan_kode');

            function resetSelect(el, placeholder) {
                el.innerHTML = `<option value="">${placeholder}</option>`;
                el.disabled = true;
            }

            function loadOptions(url, params, targetSelect, placeholder, restoreValue = null) {
                targetSelect.innerHTML = `<option value="">Memuat...</option>`;
                targetSelect.disabled = true;

                return fetch(`${url}?${new URLSearchParams(params)}`)
                    .then(r => r.json())
                    .then(data => {
                        targetSelect.innerHTML = `<option value="">${placeholder}</option>`;
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.code;
                            opt.textContent = item.name;
                            if (restoreValue && item.code === restoreValue) opt.selected = true;
                            targetSelect.appendChild(opt);
                        });
                        targetSelect.disabled = false;
                        return data;
                    })
                    .catch(() => {
                        targetSelect.innerHTML = `<option value="">Gagal memuat</option>`;
                    });
            }

            provinsiSelect.addEventListener('change', function() {
                resetSelect(kotaSelect, '— Pilih Kota —');
                resetSelect(kecamatanSelect, '— Pilih Kecamatan —');
                resetSelect(kelurahanSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.ajax.cities') }}', {
                    province_code: this.value
                }, kotaSelect, '— Pilih Kota —');
            });

            kotaSelect.addEventListener('change', function() {
                resetSelect(kecamatanSelect, '— Pilih Kecamatan —');
                resetSelect(kelurahanSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.ajax.districts') }}', {
                    city_code: this.value
                }, kecamatanSelect, '— Pilih Kecamatan —');
            });

            kecamatanSelect.addEventListener('change', function() {
                resetSelect(kelurahanSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.ajax.villages') }}', {
                    district_code: this.value
                }, kelurahanSelect, '— Pilih Kelurahan —');
            });

            // ─── Restore old() wilayah setelah validasi gagal ────────────────────────
            @if (old('provinsi_kode'))
                const oldProvinsi = '{{ old('provinsi_kode') }}';
                const oldKota = '{{ old('kota_kode') }}';
                const oldKecamatan = '{{ old('kecamatan_kode') }}';
                const oldKelurahan = '{{ old('kelurahan_kode') }}';

                provinsiSelect.value = oldProvinsi;

                loadOptions('{{ route('umkm.ajax.cities') }}', {
                        province_code: oldProvinsi
                    }, kotaSelect, '— Pilih Kota —', oldKota)
                    .then(() => {
                        if (!oldKota) return;
                        return loadOptions('{{ route('umkm.ajax.districts') }}', {
                            city_code: oldKota
                        }, kecamatanSelect, '— Pilih Kecamatan —', oldKecamatan);
                    })
                    .then(() => {
                        if (!oldKecamatan) return;
                        return loadOptions('{{ route('umkm.ajax.villages') }}', {
                            district_code: oldKecamatan
                        }, kelurahanSelect, '— Pilih Kelurahan —', oldKelurahan);
                    })
                    .catch(err => console.error('Gagal restore wilayah:', err));
            @endif

            // ─── Sanitasi Input ───────────────────────────────────────────────────────
            document.getElementById('kode_pos').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 5);
            });



            ['telepon', 'admin_telepon'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', function() {
                    this.value = this.value.replace(/[^\d+\-\s]/g, '');
                });
            });

        });
    </script>
@endpush

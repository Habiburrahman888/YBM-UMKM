@extends('layouts.app')

@section('title', 'Tambah Unit Baru')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tambah Unit Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Lengkapi formulir di bawah untuk menambahkan unit baru</p>
            </div>
            <a href="{{ route('unit.index') }}"
                class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <form action="{{ route('unit.store') }}" method="POST" enctype="multipart/form-data" id="unit-form">
                @csrf

                {{-- ✅ SECTION BARU: User Pemilik --}}
                <div class="p-4 sm:p-6 border-b border-gray-200 bg-gradient-to-r from-primary/5 to-primary/10">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">User Pemilik Unit</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Pilih user yang akan menjadi pemilik unit ini</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- User Pemilik -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih User <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_id" required
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('user_id') border-red-500 @enderror">
                                <option value="">-- Pilih User --</option>
                                @forelse($availableUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}
                                        data-email="{{ $user->email }}"
                                        data-verified="{{ $user->email_verified_at ? 'true' : 'false' }}">
                                        {{ $user->email }}{{ $user->username ? " ({$user->username})" : '' }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada user tersedia</option>
                                @endforelse
                            </select>
                            @error('user_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1.5 text-xs text-gray-500">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Hanya menampilkan user dengan role "unit" yang belum memiliki unit
                                </p>
                            @enderror
                        </div>

                        <!-- User Info Preview (Dynamic) -->
                        <div id="user-info-preview" class="hidden">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900" id="preview-email"></p>
                                        <p class="text-xs text-gray-600 mt-0.5" id="preview-username"></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                                Unit
                                            </span>
                                            <span id="preview-verified-badge"
                                                class="px-2 py-0.5 text-xs font-medium rounded-full"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Dasar Unit -->
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Informasi Dasar Unit</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Data utama dari unit organisasi</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Nama Unit -->
                        <div>
                            <label for="nama_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Unit <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_unit" id="nama_unit" value="{{ old('nama_unit') }}" required
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama_unit') border-red-500 @enderror"
                                placeholder="Contoh: Kantor Cabang Jakarta Pusat">
                            @error('nama_unit')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Unit -->
                        <div>
                            <label for="kode_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Unit <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_unit" id="kode_unit" value="{{ old('kode_unit') }}"
                                required
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kode_unit') border-red-500 @enderror"
                                placeholder="Contoh: UNIT20250001">
                            @error('kode_unit')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1.5 text-xs text-gray-500">Kode unik untuk unit (huruf kapital dan angka)</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Unit <span class="text-red-500">*</span>
                            </label>
                            <select name="is_active" id="is_active" required
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('is_active') border-red-500 @enderror">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo Unit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Logo Unit
                            </label>
                            <div class="flex items-center gap-4">
                                <div id="logo-preview" class="hidden flex-shrink-0">
                                    <img src="" alt="Preview"
                                        class="h-16 w-16 rounded-lg object-cover border-2 border-gray-200">
                                </div>
                                <div class="flex-1">
                                    <label for="logo"
                                        class="flex items-center justify-center px-4 py-2.5 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Upload Logo</span>
                                    </label>
                                    <input id="logo" name="logo" type="file" class="hidden"
                                        accept="image/png,image/jpeg,image/jpg" />
                                    @error('logo')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @else
                                        <p class="mt-1.5 text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="lg:col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Unit
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('deskripsi') border-red-500 @enderror"
                                placeholder="Jelaskan deskripsi singkat tentang unit ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Admin Unit -->
                <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Informasi Admin Unit</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Data penanggung jawab unit (opsional)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Nama Admin -->
                        <div>
                            <label for="admin_nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Admin
                            </label>
                            <input type="text" name="admin_nama" id="admin_nama" value="{{ old('admin_nama') }}"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('admin_nama') border-red-500 @enderror"
                                placeholder="Nama lengkap admin">
                            @error('admin_nama')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon Admin -->
                        <div>
                            <label for="admin_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon Admin
                            </label>
                            <input type="tel" name="admin_telepon" id="admin_telepon"
                                value="{{ old('admin_telepon') }}"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('admin_telepon') border-red-500 @enderror"
                                placeholder="08xx-xxxx-xxxx">
                            @error('admin_telepon')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Admin -->
                        <div>
                            <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Admin
                            </label>
                            <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('admin_email') border-red-500 @enderror"
                                placeholder="admin@example.com">
                            @error('admin_email')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Admin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Admin
                            </label>
                            <div class="flex items-center gap-4">
                                <div id="admin-foto-preview" class="hidden flex-shrink-0">
                                    <img src="" alt="Preview"
                                        class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                                </div>
                                <div class="flex-1">
                                    <label for="admin_foto"
                                        class="flex items-center justify-center px-4 py-2.5 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Upload Foto</span>
                                    </label>
                                    <input id="admin_foto" name="admin_foto" type="file" class="hidden"
                                        accept="image/png,image/jpeg,image/jpg" />
                                    @error('admin_foto')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Wilayah -->
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Informasi Wilayah</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Lokasi dan alamat lengkap unit</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Provinsi -->
                        <div>
                            <label for="provinsi_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                Provinsi
                            </label>
                            <select name="provinsi_kode" id="provinsi_kode"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('provinsi_kode') border-red-500 @enderror">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->code }}"
                                        {{ old('provinsi_kode') == $province->code ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provinsi_kode')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota/Kabupaten -->
                        <div>
                            <label for="kota_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota/Kabupaten
                            </label>
                            <select name="kota_kode" id="kota_kode" disabled
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all disabled:bg-gray-100 disabled:cursor-not-allowed @error('kota_kode') border-red-500 @enderror">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                            @error('kota_kode')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label for="kecamatan_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                Kecamatan
                            </label>
                            <select name="kecamatan_kode" id="kecamatan_kode" disabled
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all disabled:bg-gray-100 disabled:cursor-not-allowed @error('kecamatan_kode') border-red-500 @enderror">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            @error('kecamatan_kode')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div>
                            <label for="kelurahan_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelurahan/Desa
                            </label>
                            <select name="kelurahan_kode" id="kelurahan_kode" disabled
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all disabled:bg-gray-100 disabled:cursor-not-allowed @error('kelurahan_kode') border-red-500 @enderror">
                                <option value="">Pilih Kelurahan/Desa</option>
                            </select>
                            @error('kelurahan_kode')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Pos -->
                        <div class="lg:col-span-2">
                            <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Pos
                            </label>
                            <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kode_pos') border-red-500 @enderror"
                                placeholder="Contoh: 12345" maxlength="5">
                            @error('kode_pos')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Kontak -->
                <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Informasi Kontak</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Kontak yang bisa dihubungi untuk unit ini</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Telepon -->
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon Unit
                            </label>
                            <input type="tel" name="telepon" id="telepon" value="{{ old('telepon') }}"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('telepon') border-red-500 @enderror"
                                placeholder="021-xxxx-xxxx">
                            @error('telepon')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Unit
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('email') border-red-500 @enderror"
                                placeholder="unit@example.com">
                            @error('email')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="p-4 sm:p-6 bg-gray-50">
                    <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                        <a href="{{ route('unit.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Unit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        {{-- ✅ JAVASCRIPT BARU: User Preview --}}
        document.getElementById('user_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const userInfoPreview = document.getElementById('user-info-preview');

            if (this.value) {
                const email = selectedOption.getAttribute('data-email');
                const verified = selectedOption.getAttribute('data-verified') === 'true';
                const username = selectedOption.text.match(/\(([^)]+)\)/)?.[1] || '';

                document.getElementById('preview-email').textContent = email;
                document.getElementById('preview-username').textContent = username || 'Belum set username';

                const verifiedBadge = document.getElementById('preview-verified-badge');
                if (verified) {
                    verifiedBadge.className =
                        'px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700';
                    verifiedBadge.textContent = '✓ Verified';
                } else {
                    verifiedBadge.className =
                        'px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700';
                    verifiedBadge.textContent = 'Not Verified';
                }

                userInfoPreview.classList.remove('hidden');
            } else {
                userInfoPreview.classList.add('hidden');
            }
        });

        // Logo Preview
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('logo-preview');
                    const img = preview.querySelector('img');
                    img.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Admin Foto Preview
        document.getElementById('admin_foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('admin-foto-preview');
                    const img = preview.querySelector('img');
                    img.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Cascading Dropdowns for Regional Selection
        const provinsiSelect = document.getElementById('provinsi_kode');
        const kotaSelect = document.getElementById('kota_kode');
        const kecamatanSelect = document.getElementById('kecamatan_kode');
        const kelurahanSelect = document.getElementById('kelurahan_kode');

        // Load cities when province changes
        provinsiSelect.addEventListener('change', function() {
            const provinsiCode = this.value;

            kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';

            kecamatanSelect.disabled = true;
            kelurahanSelect.disabled = true;

            if (provinsiCode) {
                kotaSelect.disabled = true;

                fetch(`/api/region/cities/${provinsiCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = new Option(city.name, city.code);
                            kotaSelect.add(option);
                        });
                        kotaSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kotaSelect.disabled = false;
                    });
            } else {
                kotaSelect.disabled = true;
            }
        });

        // Load districts when city changes
        kotaSelect.addEventListener('change', function() {
            const kotaCode = this.value;

            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';

            kelurahanSelect.disabled = true;

            if (kotaCode) {
                kecamatanSelect.disabled = true;

                fetch(`/api/region/districts/${kotaCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(district => {
                            const option = new Option(district.name, district.code);
                            kecamatanSelect.add(option);
                        });
                        kecamatanSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kecamatanSelect.disabled = false;
                    });
            } else {
                kecamatanSelect.disabled = true;
            }
        });

        // Load villages when district changes
        kecamatanSelect.addEventListener('change', function() {
            const kecamatanCode = this.value;

            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';

            if (kecamatanCode) {
                kelurahanSelect.disabled = true;

                fetch(`/api/region/villages/${kecamatanCode}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(village => {
                            const option = new Option(village.name, village.code);
                            kelurahanSelect.add(option);
                        });
                        kelurahanSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kelurahanSelect.disabled = false;
                    });
            } else {
                kelurahanSelect.disabled = true;
            }
        });

        // Kode pos validation (only numbers)
        document.getElementById('kode_pos').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Phone number validation (only numbers and dash)
        ['telepon', 'admin_telepon'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9-]/g, '');
                });
            }
        });

        // Kode unit auto uppercase
        document.getElementById('kode_unit').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        // Form validation before submit
        document.getElementById('unit-form').addEventListener('submit', function(e) {
            const userId = document.getElementById('user_id').value;
            const namaUnit = document.getElementById('nama_unit').value.trim();
            const kodeUnit = document.getElementById('kode_unit').value.trim();

            if (!userId) {
                e.preventDefault();
                alert('User pemilik harus dipilih');
                document.getElementById('user_id').focus();
                return false;
            }

            if (!namaUnit) {
                e.preventDefault();
                alert('Nama Unit harus diisi');
                document.getElementById('nama_unit').focus();
                return false;
            }

            if (!kodeUnit) {
                e.preventDefault();
                alert('Kode Unit harus diisi');
                document.getElementById('kode_unit').focus();
                return false;
            }
        });
    </script>
@endpush

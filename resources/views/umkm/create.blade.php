@extends('layouts.app')

@section('title', 'Tambah UMKM')
@section('page-title', 'Tambah UMKM')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Tambah UMKM Baru</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Lengkapi form berikut untuk menambahkan data UMKM baru</p>
            </div>

            <form action="{{ route('umkm.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf

                <div class="space-y-6">

                    {{-- ══ SEKSI 1: DATA USAHA ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">1</span>
                            Data Usaha
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Nama Pemilik --}}
                            <div>
                                <label for="nama_pemilik" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Pemilik <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_pemilik" id="nama_pemilik"
                                    value="{{ old('nama_pemilik') }}" autofocus placeholder="Masukkan nama lengkap pemilik"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama_pemilik') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('nama_pemilik')
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

                            {{-- Nama Usaha --}}
                            <div>
                                <label for="nama_usaha" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Usaha <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_usaha" id="nama_usaha" value="{{ old('nama_usaha') }}"
                                    placeholder="Masukkan nama usaha / brand"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama_usaha') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('nama_usaha')
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

                            {{-- Tahun Berdiri --}}
                            <div>
                                <label for="tahun_berdiri" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tahun Berdiri
                                </label>
                                <input type="number" name="tahun_berdiri" id="tahun_berdiri"
                                    value="{{ old('tahun_berdiri') }}" placeholder="Contoh: {{ date('Y') }}"
                                    min="1900" max="{{ date('Y') }}"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('tahun_berdiri') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('tahun_berdiri')
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

                            {{-- Kategori --}}
                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kategori
                                </label>
                                <select name="kategori_id" id="kategori_id"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kategori_id') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach ($kategoriList as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
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

                            {{-- Unit (hanya admin) --}}
                            @if (auth()->user()->role === 'admin')
                                <div>
                                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Unit
                                    </label>
                                    <select name="unit_id" id="unit_id"
                                        class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('unit_id') border-red-500 ring-1 ring-red-500 @enderror">
                                        <option value="">— Pilih Unit —</option>
                                        @foreach ($unitList as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->nama ?? ($unit->name ?? 'Unit #' . $unit->id) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
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
                            @endif

                        </div>
                    </div>

                    {{-- ══ SEKSI 2: KONTAK ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">2</span>
                            Kontak
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Telepon --}}
                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    No. Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                                    placeholder="Contoh: 081234567890"
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

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    placeholder="contoh@email.com"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('email') border-red-500 ring-1 ring-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Email ini akan digunakan untuk login akun UMKM</p>
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

                            {{-- Alamat (full width) --}}
                            <div class="sm:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Alamat Usaha <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap usaha"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('alamat') border-red-500 ring-1 ring-red-500 @enderror">{{ old('alamat') }}</textarea>
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

                    {{-- ══ SEKSI 3: WILAYAH ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">3</span>
                            Wilayah
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Provinsi --}}
                            <div>
                                <label for="province_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Provinsi
                                </label>
                                <select name="province_code" id="province_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('province_code') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Provinsi —</option>
                                    @foreach ($provinceList as $prov)
                                        <option value="{{ $prov->code }}"
                                            {{ old('province_code') === $prov->code ? 'selected' : '' }}>
                                            {{ $prov->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province_code')
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
                                <label for="city_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kota / Kabupaten
                                </label>
                                <select name="city_code" id="city_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('city_code') border-red-500 ring-1 ring-red-500 @enderror"
                                    {{ !old('province_code') ? 'disabled' : '' }}>
                                    <option value="">— Pilih Kota —</option>
                                </select>
                                @error('city_code')
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
                                <label for="district_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kecamatan
                                </label>
                                <select name="district_code" id="district_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('district_code') border-red-500 ring-1 ring-red-500 @enderror"
                                    {{ !old('city_code') ? 'disabled' : '' }}>
                                    <option value="">— Pilih Kecamatan —</option>
                                </select>
                                @error('district_code')
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
                                <label for="village_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kelurahan / Desa
                                </label>
                                <select name="village_code" id="village_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('village_code') border-red-500 ring-1 ring-red-500 @enderror"
                                    {{ !old('district_code') ? 'disabled' : '' }}>
                                    <option value="">— Pilih Kelurahan —</option>
                                </select>
                                @error('village_code')
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
                                <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}"
                                    placeholder="Contoh: 12345" maxlength="5"
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

                    {{-- ══ SEKSI 4: BRANDING ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">4</span>
                            Branding
                            <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Logo UMKM --}}
                            <div class="sm:col-span-2">
                                <label for="logo_umkm" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Logo UMKM
                                </label>
                                <div class="flex items-start gap-4">
                                    {{-- Preview --}}
                                    <div id="logo-preview-wrapper"
                                        class="hidden w-16 h-16 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0">
                                        <img id="logo-preview" src="#" alt="Preview"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <label for="logo_umkm"
                                            class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <svg class="mx-auto w-6 h-6 text-gray-400 mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span id="logo-label" class="text-xs text-gray-500">Klik untuk upload
                                                    logo</span>
                                            </div>
                                        </label>
                                        <input type="file" name="logo_umkm" id="logo_umkm"
                                            accept="image/jpg,image/jpeg,image/png" class="hidden">
                                        <p class="mt-1 text-xs text-gray-400">Format: JPG, JPEG, PNG. Maks. 2MB.</p>
                                        @error('logo_umkm')
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

                            {{-- Tentang --}}
                            <div class="sm:col-span-2">
                                <label for="tentang" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tentang Usaha
                                </label>
                                <textarea name="tentang" id="tentang" rows="3" placeholder="Ceritakan sedikit tentang usaha ini..."
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('tentang') border-red-500 ring-1 ring-red-500 @enderror">{{ old('tentang') }}</textarea>
                                @error('tentang')
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

                            {{-- Media Sosial --}}
                            <div>
                                <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Instagram
                                </label>
                                <input type="url" name="instagram" id="instagram" value="{{ old('instagram') }}"
                                    placeholder="https://instagram.com/username"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('instagram') border-red-500 ring-1 ring-red-500 @enderror">
                            </div>

                            <div>
                                <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Facebook
                                </label>
                                <input type="url" name="facebook" id="facebook" value="{{ old('facebook') }}"
                                    placeholder="https://facebook.com/namahalaman"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('facebook') border-red-500 ring-1 ring-red-500 @enderror">
                            </div>

                            <div>
                                <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    TikTok
                                </label>
                                <input type="url" name="tiktok" id="tiktok" value="{{ old('tiktok') }}"
                                    placeholder="https://tiktok.com/@username"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('tiktok') border-red-500 ring-1 ring-red-500 @enderror">
                            </div>

                            <div>
                                <label for="youtube" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    YouTube
                                </label>
                                <input type="url" name="youtube" id="youtube" value="{{ old('youtube') }}"
                                    placeholder="https://youtube.com/@channel"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('youtube') border-red-500 ring-1 ring-red-500 @enderror">
                            </div>

                        </div>
                    </div>

                    {{-- ══ SEKSI 5: DATA PRODUK ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">5</span>
                            Produk Utama
                            <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- Nama Produk --}}
                            <div class="sm:col-span-2">
                                <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Produk
                                </label>
                                <input type="text" name="nama_produk" id="nama_produk"
                                    value="{{ old('nama_produk') }}" placeholder="Masukkan nama produk utama"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama_produk') border-red-500 ring-1 ring-red-500 @enderror">
                                @error('nama_produk')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Harga Produk --}}
                            <div>
                                <label for="harga_produk" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Harga
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="harga_produk" id="harga_produk"
                                        value="{{ old('harga_produk') }}" placeholder="0"
                                        class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('harga_produk') border-red-500 ring-1 ring-red-500 @enderror">
                                </div>
                                @error('harga_produk')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Satuan Produk --}}
                            <div>
                                <label for="kategori_satuan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Satuan <span class="text-xs font-normal text-gray-400">(opsional)</span>
                                </label>
                                <select name="kategori_satuan" id="kategori_satuan"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kategori_satuan') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Satuan —</option>
                                    <option value="pcs" {{ old('kategori_satuan') == 'pcs' ? 'selected' : '' }}>Pcs
                                    </option>
                                    <option value="bungkus" {{ old('kategori_satuan') == 'bungkus' ? 'selected' : '' }}>
                                        Bungkus</option>
                                    <option value="gram" {{ old('kategori_satuan') == 'gram' ? 'selected' : '' }}>Gram
                                    </option>
                                    <option value="kg" {{ old('kategori_satuan') == 'kg' ? 'selected' : '' }}>Kg
                                    </option>
                                    <option value="liter" {{ old('kategori_satuan') == 'liter' ? 'selected' : '' }}>Liter
                                    </option>
                                    <option value="ml" {{ old('kategori_satuan') == 'ml' ? 'selected' : '' }}>Ml
                                    </option>
                                    <option value="box" {{ old('kategori_satuan') == 'box' ? 'selected' : '' }}>Box
                                    </option>
                                    <option value="pack" {{ old('kategori_satuan') == 'pack' ? 'selected' : '' }}>Pack
                                    </option>
                                </select>
                                @error('kategori_satuan')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Deskripsi Produk --}}
                            <div class="sm:col-span-2">
                                <label for="deskripsi_produk" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Deskripsi Produk
                                </label>
                                <textarea name="deskripsi_produk" id="deskripsi_produk" rows="3" placeholder="Jelaskan detail produk Anda..."
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('deskripsi_produk') border-red-500 ring-1 ring-red-500 @enderror">{{ old('deskripsi_produk') }}</textarea>
                                @error('deskripsi_produk')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Foto Produk --}}
                            <div class="sm:col-span-2">
                                <label for="foto_produk" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Foto Produk
                                </label>
                                <div class="flex items-start gap-4">
                                    <div id="produk-preview-wrapper"
                                        class="hidden w-16 h-16 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0">
                                        <img id="produk-preview" src="#" alt="Preview"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <label for="foto_produk"
                                            class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <svg class="mx-auto w-6 h-6 text-gray-400 mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span id="produk-label" class="text-xs text-gray-500">Klik untuk upload
                                                    foto produk</span>
                                            </div>
                                        </label>
                                        <input type="file" name="foto_produk" id="foto_produk"
                                            accept="image/jpg,image/jpeg,image/png" class="hidden">
                                        <p class="mt-1 text-xs text-gray-400">Format: JPG, JPEG, PNG. Maks. 2MB.</p>
                                        @error('foto_produk')
                                            <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ══ SEKSI 6: OPSI AKUN ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">6</span>
                            Akun Login
                        </h3>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="relative mt-0.5">
                                <input type="checkbox" name="create_account" id="create_account" value="1"
                                    {{ old('create_account') ? 'checked' : '' }} class="peer sr-only">
                                <div
                                    class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:bg-primary peer-checked:border-primary transition-all flex items-center justify-center">
                                    <svg class="hidden peer-checked:block w-3 h-3 text-white" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <span
                                    class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                    Buat akun login sekaligus
                                </span>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Username dan password akan dikirim otomatis ke email UMKM yang diisi di atas.
                                </p>
                            </div>
                        </label>
                    </div>

                </div>

                {{-- ── TOMBOL AKSI ── --}}
                <div
                    class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('umkm.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan UMKM
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ─── Preview Logo ─────────────────────────────────────────────────────────
            const logoInput = document.getElementById('logo_umkm');
            const logoPreview = document.getElementById('logo-preview');
            const logoWrapper = document.getElementById('logo-preview-wrapper');
            const logoLabel = document.getElementById('logo-label');

            function handlePreview(input, preview, wrapper, label) {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;

                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        wrapper.classList.remove('hidden');
                        label.textContent = file.name;
                    };
                    reader.readAsDataURL(file);
                });
            }

            handlePreview(logoInput, logoPreview, logoWrapper, logoLabel);

            // Preview Foto Produk
            const produkInput = document.getElementById('foto_produk');
            const produkPreview = document.getElementById('produk-preview');
            const produkWrapper = document.getElementById('produk-preview-wrapper');
            const produkLabel = document.getElementById('produk-label');

            handlePreview(produkInput, produkPreview, produkWrapper, produkLabel);

            // ─── AJAX Wilayah Bertingkat ──────────────────────────────────────────────
            const provinceSelect = document.getElementById('province_code');
            const citySelect = document.getElementById('city_code');
            const districtSelect = document.getElementById('district_code');
            const villageSelect = document.getElementById('village_code');

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

            // Provinsi → Kota
            provinceSelect.addEventListener('change', function() {
                resetSelect(citySelect, '— Pilih Kota —');
                resetSelect(districtSelect, '— Pilih Kecamatan —');
                resetSelect(villageSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.getCities') }}', {
                    province_code: this.value
                }, citySelect, '— Pilih Kota —');
            });

            // Kota → Kecamatan
            citySelect.addEventListener('change', function() {
                resetSelect(districtSelect, '— Pilih Kecamatan —');
                resetSelect(villageSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.getDistricts') }}', {
                    city_code: this.value
                }, districtSelect, '— Pilih Kecamatan —');
            });

            // Kecamatan → Kelurahan
            districtSelect.addEventListener('change', function() {
                resetSelect(villageSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.getVillages') }}', {
                    district_code: this.value
                }, villageSelect, '— Pilih Kelurahan —');
            });

            // ─── Restore old() setelah validasi gagal ────────────────────────────────
            @if (old('province_code'))
                const oldProvince = '{{ old('province_code') }}';
                const oldCity = '{{ old('city_code') }}';
                const oldDistrict = '{{ old('district_code') }}';
                const oldVillage = '{{ old('village_code') }}';

                provinceSelect.value = oldProvince;

                // Load kota, lalu kecamatan, lalu kelurahan secara berantai
                loadOptions(
                    '{{ route('umkm.getCities') }}', {
                        province_code: oldProvince
                    },
                    citySelect,
                    '— Pilih Kota —',
                    oldCity
                ).then(() => {
                    if (!oldCity) return;
                    return loadOptions(
                        '{{ route('umkm.getDistricts') }}', {
                            city_code: oldCity
                        },
                        districtSelect,
                        '— Pilih Kecamatan —',
                        oldDistrict
                    );
                }).then(() => {
                    if (!oldDistrict) return;
                    return loadOptions(
                        '{{ route('umkm.getVillages') }}', {
                            district_code: oldDistrict
                        },
                        villageSelect,
                        '— Pilih Kelurahan —',
                        oldVillage
                    );
                }).catch(err => {
                    console.error('Gagal restore wilayah:', err);
                });
            @endif

            // ─── Kode pos: angka saja ─────────────────────────────────────────────────
            document.getElementById('kode_pos').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 5);
            });

            // ─── Telepon: angka dan simbol dasar ─────────────────────────────────────
            document.getElementById('telepon').addEventListener('input', function() {
                this.value = this.value.replace(/[^\d+\-\s]/g, '');
            });

            // ─── Custom checkbox visual ───────────────────────────────────────────────
            const checkbox = document.getElementById('create_account');
            const checkDiv = checkbox.nextElementSibling;

            function syncCheckbox(checked) {
                const icon = checkDiv.querySelector('svg');
                if (checked) {
                    checkDiv.classList.add('bg-primary', 'border-primary');
                    icon.classList.remove('hidden');
                } else {
                    checkDiv.classList.remove('bg-primary', 'border-primary');
                    icon.classList.add('hidden');
                }
            }

            checkbox.addEventListener('change', function() {
                syncCheckbox(this.checked);
            });

            // Set initial state
            syncCheckbox(checkbox.checked);
        });
    </script>
@endpush

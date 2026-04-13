@extends('layouts.app')

@section('title', 'Edit UMKM')
@section('page-title', 'Edit UMKM')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit UMKM</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi UMKM <span
                        class="font-medium text-gray-700">{{ $umkm->nama_usaha }}</span></p>
            </div>

            {{-- ══════════════════════════════════════════════════════════ --}}
            {{-- FORM UTAMA UMKM (TIDAK mengandung form modal di dalamnya) --}}
            {{-- ══════════════════════════════════════════════════════════ --}}
            <form action="{{ route('umkm.update', $umkm->uuid) }}" method="POST" enctype="multipart/form-data"
                class="p-4 sm:p-6" id="form-umkm-utama">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="px-6 pb-2">
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada input Anda:</h3>
                                    <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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
                                    value="{{ old('nama_pemilik', $umkm->nama_pemilik) }}" autofocus
                                    placeholder="Masukkan nama lengkap pemilik"
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
                                <input type="text" name="nama_usaha" id="nama_usaha"
                                    value="{{ old('nama_usaha', $umkm->nama_usaha) }}"
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
                                    value="{{ old('tahun_berdiri', $umkm->tahun_berdiri) }}"
                                    placeholder="Contoh: {{ date('Y') }}" min="1900" max="{{ date('Y') }}"
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
                                <label for="kategori_id"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                                <select name="kategori_id" id="kategori_id"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kategori_id') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach ($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ old('kategori_id', $umkm->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
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

                            {{-- Kode UMKM (readonly) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode UMKM</label>
                                <input type="text" value="{{ $umkm->kode_umkm }}" readonly
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                                <p class="mt-1 text-xs text-gray-400">Kode UMKM tidak dapat diubah</p>
                            </div>

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
                                <input type="text" name="telepon" id="telepon"
                                    value="{{ old('telepon', $umkm->telepon) }}" placeholder="Contoh: 081234567890"
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
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $umkm->email) }}" placeholder="contoh@email.com"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('email') border-red-500 ring-1 ring-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Email ini digunakan untuk login akun UMKM</p>
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

                            {{-- Alamat --}}
                            <div class="sm:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Alamat Usaha <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap usaha"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('alamat') border-red-500 ring-1 ring-red-500 @enderror">{{ old('alamat', $umkm->alamat) }}</textarea>
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
                                <label for="province_code"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">Provinsi</label>
                                <select name="province_code" id="province_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('province_code') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Provinsi —</option>
                                    @foreach ($provinceList as $province)
                                        <option value="{{ $province->code }}"
                                            {{ old('province_code', $umkm->province_code) === $province->code ? 'selected' : '' }}>
                                            {{ $province->name }}
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

                            {{-- Kota --}}
                            <div>
                                <label for="city_code" class="block text-sm font-medium text-gray-700 mb-1.5">Kota /
                                    Kabupaten</label>
                                <select name="city_code" id="city_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('city_code') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kota —</option>
                                    @foreach ($cityList as $city)
                                        <option value="{{ $city->code }}"
                                            {{ old('city_code', $umkm->city_code) === $city->code ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
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
                                <label for="district_code"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">Kecamatan</label>
                                <select name="district_code" id="district_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('district_code') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kecamatan —</option>
                                    @foreach ($districtList as $district)
                                        <option value="{{ $district->code }}"
                                            {{ old('district_code', $umkm->district_code) === $district->code ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
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

                            {{-- Kelurahan --}}
                            <div>
                                <label for="village_code" class="block text-sm font-medium text-gray-700 mb-1.5">Kelurahan
                                    / Desa</label>
                                <select name="village_code" id="village_code"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('village_code') border-red-500 ring-1 ring-red-500 @enderror">
                                    <option value="">— Pilih Kelurahan —</option>
                                    @foreach ($villageList as $village)
                                        <option value="{{ $village->code }}"
                                            {{ old('village_code', $umkm->village_code) === $village->code ? 'selected' : '' }}>
                                            {{ $village->name }}
                                        </option>
                                    @endforeach
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
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1.5">Kode
                                    Pos</label>
                                <input type="text" name="kode_pos" id="kode_pos"
                                    value="{{ old('kode_pos', $umkm->kode_pos) }}" placeholder="Contoh: 12345"
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
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Logo UMKM</label>
                                <div class="flex items-start gap-4">
                                    <div id="logo-preview-wrapper"
                                        class="w-16 h-16 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0 {{ $umkm->logo_umkm ? '' : 'hidden' }}">
                                        <img id="logo-preview"
                                            src="{{ $umkm->logo_umkm ? Storage::url($umkm->logo_umkm) : '#' }}"
                                            alt="Logo" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <label for="logo_umkm"
                                            class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <svg class="mx-auto w-6 h-6 text-gray-400 mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span id="logo-label" class="text-xs text-gray-500">
                                                    {{ $umkm->logo_umkm ? 'Klik untuk ganti logo' : 'Klik untuk upload logo' }}
                                                </span>
                                            </div>
                                        </label>
                                        <input type="file" name="logo_umkm" id="logo_umkm"
                                            accept="image/jpg,image/jpeg,image/png" class="hidden">
                                        <p class="text-xs text-gray-400">Format: JPG, JPEG, PNG. Maks. 2MB.</p>
                                        @if ($umkm->logo_umkm)
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="checkbox" name="remove_logo_umkm" id="remove_logo_umkm"
                                                    value="1" class="peer sr-only">
                                                <div
                                                    class="w-4 h-4 border-2 border-gray-300 rounded peer-checked:bg-red-500 peer-checked:border-red-500 transition-all flex items-center justify-center flex-shrink-0">
                                                    <svg class="hidden peer-checked:block w-2.5 h-2.5 text-white"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="text-xs text-red-600 group-hover:text-red-700 transition-colors">Hapus
                                                    logo saat ini</span>
                                            </label>
                                        @endif
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
                                <label for="tentang" class="block text-sm font-medium text-gray-700 mb-1.5">Tentang
                                    Usaha</label>
                                <textarea name="tentang" id="tentang" rows="3" placeholder="Ceritakan sedikit tentang usaha ini..."
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('tentang') border-red-500 ring-1 ring-red-500 @enderror">{{ old('tentang', $umkm->tentang) }}</textarea>
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

                            {{-- Instagram --}}
                            <div>
                                <label for="instagram"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">Instagram</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-sm">@</span>
                                    </div>
                                    <input type="text" name="instagram" id="instagram"
                                        value="{{ old('instagram', $umkm->instagram) }}" placeholder="username"
                                        class="block w-full pl-7 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('instagram') border-red-500 ring-1 ring-red-500 @enderror">
                                </div>
                            </div>

                            {{-- Facebook --}}
                            <div>
                                <label for="facebook"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">Facebook</label>
                                <input type="text" name="facebook" id="facebook"
                                    value="{{ old('facebook', $umkm->facebook) }}"
                                    placeholder="URL atau nama halaman Facebook"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('facebook') border-red-500 ring-1 ring-red-500 @enderror">
                            </div>

                            {{-- TikTok --}}
                            <div>
                                <label for="tiktok"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">TikTok</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-sm">@</span>
                                    </div>
                                    <input type="text" name="tiktok" id="tiktok"
                                        value="{{ old('tiktok', $umkm->tiktok) }}" placeholder="username"
                                        class="block w-full pl-7 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('tiktok') border-red-500 ring-1 ring-red-500 @enderror">
                                </div>
                            </div>

                            {{-- YouTube --}}
                            <div>
                                <label for="youtube"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">YouTube</label>
                                <input type="text" name="youtube" id="youtube"
                                    value="{{ old('youtube', $umkm->youtube) }}" placeholder="URL channel YouTube"
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
                                    value="{{ old('nama_produk', $produkUtama?->nama_produk) }}"
                                    placeholder="Masukkan nama produk utama"
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
                                    <input type="text" name="harga_produk" id="harga_produk"
                                        value="{{ old('harga_produk', $produkUtama?->harga ? (int)$produkUtama->harga : null) }}" placeholder="0"
                                        class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all rupiah-input @error('harga_produk') border-red-500 ring-1 ring-red-500 @enderror">
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
                                    @foreach (['pcs', 'bungkus', 'gram', 'kg', 'liter', 'ml', 'box', 'porsi', 'pack', 'cup', 'karung', 'paket', 'unit'] as $satuan)
                                        <option value="{{ $satuan }}"
                                            {{ old('kategori_satuan', $produkUtama?->kategori_satuan) == $satuan ? 'selected' : '' }}>
                                            {{ ucfirst($satuan) }}</option>
                                    @endforeach
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
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('deskripsi_produk') border-red-500 ring-1 ring-red-500 @enderror">{{ old('deskripsi_produk', $produkUtama?->deskripsi_produk) }}</textarea>
                                @error('deskripsi_produk')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Produk Utama <span class="text-xs text-gray-400 font-normal">(Maks. 5 foto)</span>
                                </label>

                                <!-- Preview Container -->
                                <div id="produk-preview-container"
                                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-4">
                                    @if ($produkUtama && $produkUtama->foto_produk)
                                        @foreach ($produkUtama->foto_produk as $index => $foto)
                                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm animate-fade-in"
                                                id="produk-foto-{{ $index }}">
                                                <img src="{{ Storage::url($foto) }}" class="w-full h-full object-cover">
                                                <input type="hidden" name="foto_produk_existing[]"
                                                    value="{{ $foto }}">
                                                <button type="button"
                                                    onclick="removeProdukFotoExisting('produk-foto-{{ $index }}')"
                                                    class="absolute top-1 right-1 w-6 h-6 bg-red-500/90 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Upload Area -->
                                <div id="produk-drop-zone"
                                    class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary transition-colors cursor-pointer @error('foto_produk') border-red-500 @enderror @error('foto_produk.*') border-red-500 @enderror">
                                    <input type="file" name="foto_produk[]" id="foto_produk"
                                        accept="image/jpeg,image/jpg,image/png,image/webp" multiple
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                    <div class="space-y-2">
                                        <div class="flex justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="text-primary font-medium">Klik untuk upload</span> atau drag &
                                            drop
                                        </div>
                                        <p class="text-xs text-gray-400">PNG, JPG, JPEG, WEBP (Maks. 2MB per foto)</p>
                                    </div>
                                </div>

                                @error('foto_produk')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ══ SEKSI 6: MODAL USAHA (hanya tampilan daftar, TANPA form di sini) ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">6</span>
                            Modal Usaha
                            @if ($umkm->modalUmkm->isNotEmpty())
                                <span class="text-xs font-normal text-gray-500">
                                    — Total: <span class="font-semibold text-gray-700">{{ $umkm->total_modal }}</span>
                                </span>
                            @endif
                        </h3>

                        {{-- Daftar item modal yang sudah ada --}}
                        @if ($umkm->modalUmkm->isNotEmpty())
                            <div class="space-y-3 mb-4" id="daftar-modal-umkm">
                                @foreach ($umkm->modalUmkm as $modal)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden"
                                        id="card-modal-{{ $modal->id }}">

                                        {{-- Header item --}}
                                        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50">
                                            @if ($modal->foto_cover)
                                                <img src="{{ asset('storage/' . $modal->foto_cover) }}"
                                                    alt="{{ $modal->nama_item }}"
                                                    class="w-12 h-12 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                                            @else
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900">{{ $modal->nama_item }}</p>
                                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                                    <span
                                                        class="text-xs text-gray-500 capitalize">{{ $modal->kategori_modal }}</span>
                                                    <span class="text-gray-300">·</span>
                                                    <span
                                                        class="text-xs font-medium text-gray-700">{{ $modal->nilai_rupiah }}</span>
                                                    <span class="text-gray-300">·</span>
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium
                                                        {{ $modal->kondisi === 'baru'
                                                            ? 'bg-green-100 text-green-700'
                                                            : ($modal->kondisi === 'baik'
                                                                ? 'bg-blue-100 text-blue-700'
                                                                : ($modal->kondisi === 'cukup'
                                                                    ? 'bg-yellow-100 text-yellow-700'
                                                                    : 'bg-red-100 text-red-700')) }}">
                                                        {{ ucfirst($modal->kondisi) }}
                                                    </span>
                                                    @if (!empty($modal->foto))
                                                        <span class="text-xs text-gray-400">{{ count($modal->foto) }}
                                                            foto</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <button type="button"
                                                    onclick="toggleEditModal('edit-modal-{{ $modal->id }}')"
                                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                                {{-- Tombol hapus pakai JS submitAction agar tidak perlu form nested --}}
                                                <button type="button"
                                                    onclick="hapusModal('{{ route('umkm.modal.destroy', [$umkm->uuid, $modal->id]) }}', '{{ addslashes($modal->nama_item) }}')"
                                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Panel edit inline — ini BUKAN nested form karena berada di luar form utama --}}
                                        {{-- Catatan: div ini akan ditampilkan/disembunyikan via JS --}}
                                        <div id="edit-modal-{{ $modal->id }}"
                                            class="hidden border-t border-gray-200 px-4 py-4 bg-white">
                                            <p
                                                class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-3">
                                                Klik <strong>Simpan Perubahan Modal</strong> di bawah untuk menyimpan
                                                perubahan item ini. Perubahan ini terpisah dari tombol <em>Update UMKM</em>
                                                di atas.
                                            </p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                                id="fields-modal-{{ $modal->id }}">

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Item
                                                        <span class="text-red-500">*</span></label>
                                                    <input type="text" data-modal-field="{{ $modal->id }}"
                                                        data-field="nama_item" value="{{ $modal->nama_item }}"
                                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kategori
                                                        <span class="text-red-500">*</span></label>
                                                    <select data-modal-field="{{ $modal->id }}"
                                                        data-field="kategori_modal"
                                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                        @foreach ($kategoriModal as $kategori)
                                                            <option value="{{ $kategori }}"
                                                                {{ $modal->kategori_modal === $kategori ? 'selected' : '' }}>
                                                                {{ ucfirst($kategori) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nilai Modal
                                                        (Rp)
                                                        <span class="text-red-500">*</span></label>
                                                    <input type="text" data-modal-field="{{ $modal->id }}"
                                                        data-field="nilai_modal" value="{{ (int)$modal->nilai_modal }}"
                                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary rupiah-input">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kondisi
                                                        <span class="text-red-500">*</span></label>
                                                    <select data-modal-field="{{ $modal->id }}" data-field="kondisi"
                                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                        @foreach ($kondisiModal as $kondisi)
                                                            <option value="{{ $kondisi }}"
                                                                {{ $modal->kondisi === $kondisi ? 'selected' : '' }}>
                                                                {{ ucfirst($kondisi) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal
                                                        Perolehan</label>
                                                    <input type="date" data-modal-field="{{ $modal->id }}"
                                                        data-field="tanggal_perolehan"
                                                        value="{{ $modal->tanggal_perolehan?->format('Y-m-d') }}"
                                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                </div>

                                                <div class="sm:col-span-2">
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                                                    <textarea data-modal-field="{{ $modal->id }}" data-field="keterangan" rows="2"
                                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary resize-none">{{ $modal->keterangan }}</textarea>
                                                </div>

                                                {{-- Foto existing --}}
                                                @if (!empty($modal->foto))
                                                    <div class="sm:col-span-2">
                                                        <label class="block text-xs font-medium text-gray-600 mb-2">
                                                            Foto Saat Ini
                                                            <span class="text-gray-400 font-normal">(hover untuk
                                                                hapus)</span>
                                                        </label>
                                                        <div class="flex gap-2 flex-wrap"
                                                            id="foto-existing-wrapper-{{ $modal->id }}">
                                                            @foreach ($modal->foto as $index => $fotoPath)
                                                                <div class="relative group"
                                                                    id="foto-item-{{ $modal->id }}-{{ $index }}">
                                                                    <img src="{{ asset('storage/' . $fotoPath) }}"
                                                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                                                                    <input type="hidden"
                                                                        class="foto-keep-{{ $modal->id }}"
                                                                        value="{{ $fotoPath }}">
                                                                    <button type="button"
                                                                        onclick="removeFotoExisting('foto-item-{{ $modal->id }}-{{ $index }}')"
                                                                        class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                        ✕
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="sm:col-span-2">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tambah Foto
                                                        Baru</label>
                                                    <input type="file" data-modal-field="{{ $modal->id }}"
                                                        data-field="foto_baru" multiple accept="image/*"
                                                        class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                                                    <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, WEBP. Maks. 2MB
                                                        per foto.</p>
                                                </div>

                                            </div>

                                            <div class="flex justify-end gap-2 mt-4">
                                                <button type="button"
                                                    onclick="toggleEditModal('edit-modal-{{ $modal->id }}')"
                                                    class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                    Batal
                                                </button>
                                                <button type="button"
                                                    onclick="submitUpdateModal('{{ route('umkm.modal.update', [$umkm->uuid, $modal->id]) }}', {{ $modal->id }})"
                                                    class="px-3 py-1.5 text-xs font-medium text-white bg-primary hover:bg-primary-600 rounded-lg transition-colors shadow-sm">
                                                    Simpan Perubahan Modal
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic mb-4">Belum ada data modal terdaftar</p>
                        @endif

                        {{-- Tombol tambah modal — trigger panel di bawah --}}
                        <div class="border border-dashed border-gray-300 rounded-lg overflow-hidden">
                            <button type="button" onclick="toggleTambahModal()"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Item Modal
                            </button>
                            {{-- Panel isian — bukan form, submit via JS --}}
                            <div id="panel-tambah-modal" class="hidden border-t border-dashed border-gray-300 px-4 py-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Item <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" id="new-nama_item" placeholder="Contoh: Gerobak Kayu"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Kategori <span
                                                class="text-red-500">*</span></label>
                                        <select id="new-kategori_modal"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                            @foreach ($kategoriModal as $kategori)
                                                <option value="{{ $kategori }}">{{ ucfirst($kategori) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Nilai Modal (Rp) <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" id="new-nilai_modal"
                                            placeholder="Contoh: 5.000.000"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary rupiah-input">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Kondisi <span
                                                class="text-red-500">*</span></label>
                                        <select id="new-kondisi"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                            @foreach ($kondisiModal as $kondisi)
                                                <option value="{{ $kondisi }}">{{ ucfirst($kondisi) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal
                                            Perolehan</label>
                                        <input type="date" id="new-tanggal_perolehan"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                                        <textarea id="new-keterangan" rows="2" placeholder="Deskripsi tambahan item modal..."
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Foto Item</label>
                                        <input type="file" id="new-foto" multiple accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                                        <p class="mt-1 text-xs text-gray-400">Bisa pilih banyak foto. Format: JPG, PNG,
                                            WEBP. Maks. 2MB per foto.</p>
                                    </div>

                                </div>

                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="button" onclick="toggleTambahModal()"
                                        class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="button"
                                        onclick="submitTambahModal('{{ route('umkm.modal.store', $umkm->uuid) }}')"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-primary hover:bg-primary-600 rounded-lg transition-colors shadow-sm">
                                        Tambah Item Modal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══ SEKSI 7: INFO STATUS (readonly) ══ --}}
                    <div>
                        <h3
                            class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white text-xs font-bold">7</span>
                            Informasi Status
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                            <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Status Saat Ini</p>
                                @if ($umkm->status === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-700">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-700">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>Nonaktif
                                    </span>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Tanggal Bergabung</p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($umkm->tanggal_bergabung)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Diverifikasi</p>
                                @if ($umkm->verified_at)
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ \Carbon\Carbon::parse($umkm->verified_at)->translatedFormat('d F Y') }}
                                    </p>
                                    @if ($umkm->verifiedBy)
                                        <p class="text-xs text-gray-400 mt-0.5">oleh
                                            {{ $umkm->verifiedBy->username ?? '-' }}</p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-400">Belum diverifikasi</p>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ── TOMBOL AKSI FORM UTAMA ── --}}
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
                        Update UMKM
                    </button>
                </div>
            </form>
            {{-- ══ END FORM UTAMA UMKM ══ --}}

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
            const removeChk = document.getElementById('remove_logo_umkm');

            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        logoPreview.src = e.target.result;
                        logoWrapper.classList.remove('hidden');
                        logoLabel.textContent = file.name;
                        if (removeChk) {
                            removeChk.checked = false;
                            syncRemoveCheckbox(removeChk);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }

            if (removeChk) {
                removeChk.addEventListener('change', function() {
                    syncRemoveCheckbox(this);
                    if (this.checked) {
                        logoWrapper.classList.add('hidden');
                        if (logoInput) logoInput.value = '';
                        logoLabel.textContent = 'Klik untuk upload logo baru';
                    } else {
                        @if ($umkm->logo_umkm)
                            logoWrapper.classList.remove('hidden');
                            logoLabel.textContent = 'Klik untuk ganti logo';
                        @endif
                    }
                });
            }

            function syncRemoveCheckbox(input) {
                const box = input.nextElementSibling;
                const icon = box?.querySelector('svg');
                if (!box || !icon) return;
                if (input.checked) {
                    box.classList.add('bg-red-500', 'border-red-500');
                    icon.classList.remove('hidden');
                } else {
                    box.classList.remove('bg-red-500', 'border-red-500');
                    icon.classList.add('hidden');
                }
            }

            // ─── AJAX Wilayah Bertingkat ──────────────────────────────────────────────
            const provinceSelect = document.getElementById('province_code');
            const citySelect = document.getElementById('city_code');
            const districtSelect = document.getElementById('district_code');
            const villageSelect = document.getElementById('village_code');

            const currentCity = '{{ old('city_code', $umkm->city_code) }}';
            const currentDistrict = '{{ old('district_code', $umkm->district_code) }}';
            const currentVillage = '{{ old('village_code', $umkm->village_code) }}';

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

            provinceSelect.addEventListener('change', function() {
                resetSelect(citySelect, '— Pilih Kota —');
                resetSelect(districtSelect, '— Pilih Kecamatan —');
                resetSelect(villageSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.getCities') }}', {
                    province_code: this.value
                }, citySelect, '— Pilih Kota —');
            });

            citySelect.addEventListener('change', function() {
                resetSelect(districtSelect, '— Pilih Kecamatan —');
                resetSelect(villageSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.getDistricts') }}', {
                    city_code: this.value
                }, districtSelect, '— Pilih Kecamatan —');
            });

            districtSelect.addEventListener('change', function() {
                resetSelect(villageSelect, '— Pilih Kelurahan —');
                if (!this.value) return;
                loadOptions('{{ route('umkm.getVillages') }}', {
                    district_code: this.value
                }, villageSelect, '— Pilih Kelurahan —');
            });

            @if (old('province_code', $umkm->province_code))
                citySelect.disabled = false;
            @endif
            @if (old('city_code', $umkm->city_code))
                districtSelect.disabled = false;
            @endif
            @if (old('district_code', $umkm->district_code))
                villageSelect.disabled = false;
            @endif

            // ─── Kode pos & telepon ───────────────────────────────────────────────────
            document.getElementById('kode_pos').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 5);
            });
            document.getElementById('telepon').addEventListener('input', function() {
                this.value = this.value.replace(/[^\d+\-\s]/g, '');
            });

            // ─── Format Rupiah ──────────────────────────────────────────────────────
            function formatRupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
            }

            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('rupiah-input')) {
                    e.target.value = formatRupiah(e.target.value);
                }
            });

            document.querySelectorAll('.rupiah-input').forEach(input => {
                if (input.value) input.value = formatRupiah(input.value);
            });

            // Cleanup for main form
            const mainForm = document.getElementById('form-umkm-utama');
            if (mainForm) {
                mainForm.addEventListener('submit', function() {
                    document.querySelectorAll('.rupiah-input').forEach(input => {
                        input.value = input.value.replace(/\./g, '');
                    });
                });
            }

            // ─── Foto Produk Utama (Multiple & Drag Drop) ──────────────────────────────
            const produkInput = document.getElementById('foto_produk');
            const produkDropZone = document.getElementById('produk-drop-zone');
            const produkPreviewContainer = document.getElementById('produk-preview-container');
            const MAX_PRODUK_FOTO = 5;
            let productFiles = [];

            // Hitung foto existing
            function getExistingCount() {
                return produkPreviewContainer.querySelectorAll('[id^="produk-foto-"]').length;
            }

            function updateProductFiles(newFiles) {
                const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                const currentTotal = getExistingCount() + productFiles.length;

                newFiles.forEach(file => {
                    if (!allowed.includes(file.type)) return;
                    if (file.size > 2 * 1024 * 1024) return;
                    if (currentTotal >= MAX_PRODUK_FOTO) return;
                    productFiles.push(file);
                });

                renderProductPreviews();
                syncProductInput();
            }

            function renderProductPreviews() {
                // Clear only the ones added manually
                produkPreviewContainer.querySelectorAll('.new-produk-preview').forEach(el => el.remove());

                productFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm animate-fade-in new-produk-preview';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <button type="button" onclick="removeProductFile(${index})" 
                                class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-all shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        `;
                        produkPreviewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function syncProductInput() {
                const dt = new DataTransfer();
                productFiles.forEach(file => dt.items.add(file));
                produkInput.files = dt.files;
            }

            window.removeProductFile = function(index) {
                productFiles.splice(index, 1);
                renderProductPreviews();
                syncProductInput();
            };

            window.removeProdukFotoExisting = function(id) {
                document.getElementById(id)?.remove();
                // After removing existing, we can potentially add more
            };

            produkInput.addEventListener('change', function() {
                updateProductFiles(Array.from(this.files));
            });

            produkDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                produkDropZone.classList.add('border-primary', 'bg-primary/5');
            });

            produkDropZone.addEventListener('dragleave', () => {
                produkDropZone.classList.remove('border-primary', 'bg-primary/5');
            });

            produkDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                produkDropZone.classList.remove('border-primary', 'bg-primary/5');
                updateProductFiles(Array.from(e.dataTransfer.files));
            });
        });

        // ── Toggle panel edit & tambah modal ─────────────────────────────────────
        function toggleTambahModal() {
            document.getElementById('panel-tambah-modal').classList.toggle('hidden');
        }

        function toggleEditModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function removeFotoExisting(itemId) {
            document.getElementById(itemId)?.remove();
        }

        // ── Submit TAMBAH modal via FormData (tidak butuh <form> nested) ─────────
        function submitTambahModal(action) {
            const namaItem = document.getElementById('new-nama_item').value.trim();
            if (!namaItem) {
                alert('Nama item wajib diisi.');
                return;
            }

            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('nama_item', namaItem);
            fd.append('kategori_modal', document.getElementById('new-kategori_modal').value);
            fd.append('nilai_modal', document.getElementById('new-nilai_modal').value.replace(/\./g, ''));
            fd.append('kondisi', document.getElementById('new-kondisi').value);
            fd.append('tanggal_perolehan', document.getElementById('new-tanggal_perolehan').value);
            fd.append('keterangan', document.getElementById('new-keterangan').value);

            const fotoFiles = document.getElementById('new-foto').files;
            for (let i = 0; i < fotoFiles.length; i++) {
                fd.append('foto[]', fotoFiles[i]);
            }

            submitFormData(action, fd);
        }

        // ── Submit UPDATE modal via FormData ──────────────────────────────────────
        function submitUpdateModal(action, modalId) {
            const fields = document.querySelectorAll(`[data-modal-field="${modalId}"]`);
            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('_method', 'PUT');

            fields.forEach(el => {
                const fieldName = el.dataset.field;
                if (fieldName === 'foto_baru') {
                    for (let i = 0; i < el.files.length; i++) {
                        fd.append('foto[]', el.files[i]);
                    }
                } else {
                    let val = el.value;
                    if (el.classList.contains('rupiah-input')) val = val.replace(/\./g, '');
                    fd.append(fieldName, val);
                }
            });

            // Foto existing yang masih dipertahankan
            document.querySelectorAll(`.foto-keep-${modalId}`).forEach(input => {
                fd.append('foto_existing[]', input.value);
            });

            submitFormData(action, fd);
        }

        // ── Submit HAPUS modal ────────────────────────────────────────────────────
        function hapusModal(action, namaItem) {
            if (!confirm(`Hapus item modal "${namaItem}" beserta fotonya?`)) return;

            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('_method', 'DELETE');

            submitFormData(action, fd);
        }

        // ── Helper: kirim FormData via fetch lalu redirect ────────────────────────
        function submitFormData(action, fd) {
            fetch(action, {
                    method: 'POST',
                    body: fd
                })
                .then(response => {
                    // Laravel redirect → ikuti redirect
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
        }
    </script>
@endpush

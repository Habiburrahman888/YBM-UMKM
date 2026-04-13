@extends('layouts.app')

@section('title', 'Edit Profil UMKM')
@section('page-title', 'Edit Profil UMKM')

@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Profil UMKM</h1>
                    <p class="text-gray-600 mt-1">Perbarui branding dan informasi publik UMKM Anda</p>
                </div>
                <a href="{{ route('umkm.settings.show') }}"
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
            <form action="{{ route('umkm.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Branding -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Branding
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Logo UMKM -->
                        <div class="md:col-span-2">
                            <label for="logo_umkm" class="block text-sm font-medium text-gray-700 mb-2">
                                Logo UMKM
                            </label>
                            <div class="space-y-3">
                                @if ($umkm->logo_umkm)
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">Logo saat ini:</p>
                                        <img src="{{ asset('storage/' . $umkm->logo_umkm) }}" alt="Logo UMKM"
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
                                <input type="file" name="logo_umkm" id="logo_umkm" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('logo_umkm') border-red-500 @enderror">
                                <p class="text-xs text-gray-500">Format: JPG, PNG, SVG (Max: 2MB)</p>
                                @error('logo_umkm')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tentang -->
                        <div class="md:col-span-2">
                            <label for="tentang" class="block text-sm font-medium text-gray-700 mb-2">
                                Tentang UMKM
                            </label>
                            <textarea name="tentang" id="tentang" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('tentang') border-red-500 @enderror"
                                placeholder="Ceritakan tentang usaha Anda...">{{ old('tentang', $umkm->tentang ?? '') }}</textarea>
                            @error('tentang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Informasi Pembayaran -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Informasi Pembayaran
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Foto QRIS -->
                        <div class="md:col-span-2">
                            <label for="qris_foto" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto QRIS
                            </label>
                            <div class="space-y-3">
                                @if ($umkm->qris_foto)
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">QRIS aktif saat ini:</p>
                                        <img src="{{ asset('storage/' . $umkm->qris_foto) }}" alt="QRIS"
                                            class="w-48 h-48 object-contain border border-gray-300 rounded-lg p-2 bg-white"
                                            id="qris-preview">
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <img id="qris-preview"
                                            class="w-48 h-48 object-contain border border-gray-300 rounded-lg p-2 bg-white hidden"
                                            alt="Preview">
                                    </div>
                                @endif
                                <input type="file" name="qris_foto" id="qris_foto" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('qris_foto') border-red-500 @enderror">
                                <p class="text-xs text-gray-500">Format: JPG, PNG (Max: 2MB)</p>
                                @error('qris_foto')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Daftar Rekening Bank -->
                        <div class="md:col-span-2">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Daftar Rekening Bank
                                </label>
                                <button type="button" id="add-rekening"
                                    class="inline-flex items-center px-3 py-1.5 border border-primary-600 text-primary-600 text-sm font-medium rounded-lg hover:bg-primary-50 transition duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Bank
                                </button>
                            </div>

                            <div id="rekening-container" class="space-y-4">
                                @forelse($umkm->rekening as $index => $rek)
                                    <div class="rekening-item p-4 bg-gray-50 rounded-xl border border-gray-200 relative">
                                        <button type="button"
                                            class="remove-rekening absolute top-4 right-4 text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama
                                                    Bank</label>
                                                <input type="text" name="rekening[{{ $index }}][nama_bank]"
                                                    value="{{ $rek->nama_bank }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500"
                                                    placeholder="Contoh: BCA / Mandiri">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nomor
                                                    Rekening</label>
                                                <input type="text"
                                                    name="rekening[{{ $index }}][nomor_rekening]"
                                                    value="{{ $rek->nomor_rekening }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500"
                                                    placeholder="Nomor rekening">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama
                                                    Pemilik</label>
                                                <input type="text" name="rekening[{{ $index }}][nama_rekening]"
                                                    value="{{ $rek->nama_rekening }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500"
                                                    placeholder="Nama di rekening">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rekening-item p-4 bg-gray-50 rounded-xl border border-gray-200 relative">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama
                                                    Bank</label>
                                                <input type="text" name="rekening[0][nama_bank]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500"
                                                    placeholder="Contoh: BCA / Mandiri">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nomor
                                                    Rekening</label>
                                                <input type="text" name="rekening[0][nomor_rekening]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500"
                                                    placeholder="Nomor rekening">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama
                                                    Pemilik</label>
                                                <input type="text" name="rekening[0][nama_rekening]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500"
                                                    placeholder="Nama di rekening">
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sosial Media -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Sosial Media
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- WhatsApp / Telepon -->
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline-block mr-2 text-green-500" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                    <path
                                        d="M12 0C5.373 0 0 5.373 0 12c0 2.124.558 4.121 1.535 5.856L.057 23.625a.75.75 0 00.918.918l5.769-1.478A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.716 9.716 0 01-4.964-1.363l-.355-.212-3.68.942.96-3.596-.232-.371A9.718 9.718 0 012.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z" />
                                </svg>
                                WhatsApp / Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="telepon" id="telepon"
                                value="{{ old('telepon', $umkm->telepon ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('telepon') border-red-500 @enderror"
                                placeholder="Contoh: 08123456789">
                            <p class="mt-1 text-xs text-gray-500">Nomor ini akan ditampilkan sebagai kontak WhatsApp</p>
                            @error('telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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
                                value="{{ old('facebook', $umkm->facebook ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('facebook') border-red-500 @enderror"
                                placeholder="https://facebook.com/namapage">
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
                                value="{{ old('instagram', $umkm->instagram ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('instagram') border-red-500 @enderror"
                                placeholder="https://instagram.com/namaakun">
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
                                value="{{ old('youtube', $umkm->youtube ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('youtube') border-red-500 @enderror"
                                placeholder="https://youtube.com/@namakanal">
                            @error('youtube')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- TikTok -->
                        <div>
                            <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline-block mr-2 text-gray-800" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z" />
                                </svg>
                                TikTok
                            </label>
                            <input type="url" name="tiktok" id="tiktok"
                                value="{{ old('tiktok', $umkm->tiktok ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('tiktok') border-red-500 @enderror"
                                placeholder="https://tiktok.com/@namaakun">
                            @error('tiktok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('umkm.settings.show') }}"
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

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide flash messages dikurangi karena sudah dihandle secara global 
            // di app.blade.php untuk menghindari penghapusan elemen yang salah.

            // Logo preview
            const logoInput = document.getElementById('logo_umkm');
            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    previewImage(this, 'logo-preview');
                });
            }

            // QRIS preview
            const qrisInput = document.getElementById('qris_foto');
            if (qrisInput) {
                qrisInput.addEventListener('change', function() {
                    previewImage(this, 'qris-preview');
                });
            }

            // Rekening management
            const container = document.getElementById('rekening-container');
            const addButton = document.getElementById('add-rekening');
            let index = container.querySelectorAll('.rekening-item').length;

            addButton.addEventListener('click', () => {
                const html = `
                <div class="rekening-item p-4 bg-gray-50 rounded-xl border border-gray-200 relative">
                    <button type="button" class="remove-rekening absolute top-4 right-4 text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Bank</label>
                            <input type="text" name="rekening[${index}][nama_bank]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Contoh: BCA / Mandiri">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nomor Rekening</label>
                            <input type="text" name="rekening[${index}][nomor_rekening]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Nomor rekening">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Pemilik</label>
                            <input type="text" name="rekening[${index}][nama_rekening]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Nama di rekening">
                        </div>
                    </div>
                </div>`;
                container.insertAdjacentHTML('beforeend', html);
                index++;
            });

            container.addEventListener('click', (e) => {
                const btn = e.target.closest('.remove-rekening');
                if (btn) {
                    btn.closest('.rekening-item').remove();
                }
            });
        });
    </script>
@endpush

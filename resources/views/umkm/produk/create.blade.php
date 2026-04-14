@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Tambah Produk UMKM</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi field yang diperlukan untuk menambahkan produk</p>
            </div>

            <form id="main-form-produk" action="{{ route('umkm.produk.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                <div class="space-y-4 sm:space-y-6">

                    <!-- Nama Produk -->
                    <div>
                        <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}"
                            placeholder="Masukkan nama produk"
                            class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nama_produk') border-red-500 @enderror">
                        @error('nama_produk')
                            <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga, Satuan & Stok (3 kolom) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">

                        <!-- Harga -->
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium select-none">Rp</span>
                                <input type="text" name="harga" id="harga" value="{{ old('harga') }}"
                                    placeholder="0"
                                    class="block w-full pl-10 pr-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('harga') border-red-500 @enderror">
                            </div>
                            @error('harga')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label for="kategori_satuan" class="block text-sm font-medium text-gray-700 mb-2">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_satuan" id="kategori_satuan"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('kategori_satuan') border-red-500 @enderror">
                                <option value="">Pilih Satuan</option>
                                <option value="pcs" {{ old('kategori_satuan') == 'pcs' ? 'selected' : '' }}>Pcs
                                    (Biji/Buah)</option>
                                <option value="bungkus" {{ old('kategori_satuan') == 'bungkus' ? 'selected' : '' }}>Bungkus
                                </option>
                                <option value="gram" {{ old('kategori_satuan') == 'gram' ? 'selected' : '' }}>Gram (gr)
                                </option>
                                <option value="kg" {{ old('kategori_satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)
                                </option>
                                <option value="liter" {{ old('kategori_satuan') == 'liter' ? 'selected' : '' }}>Liter (L)
                                </option>
                                <option value="ml" {{ old('kategori_satuan') == 'ml' ? 'selected' : '' }}>Mililiter
                                    (ml)</option>
                                <option value="box" {{ old('kategori_satuan') == 'box' ? 'selected' : '' }}>Box (Kotak)
                                </option>
                                <option value="porsi" {{ old('kategori_satuan') == 'porsi' ? 'selected' : '' }}>Porsi
                                </option>
                                <option value="pack" {{ old('kategori_satuan') == 'pack' ? 'selected' : '' }}>Pack
                                    (Paket/Bungkus)</option>
                                <option value="cup" {{ old('kategori_satuan') == 'cup' ? 'selected' : '' }}>Cup
                                </option>
                                <option value="karung" {{ old('kategori_satuan') == 'karung' ? 'selected' : '' }}>Karung
                                </option>
                                <option value="paket" {{ old('kategori_satuan') == 'paket' ? 'selected' : '' }}>Paket
                                </option>
                                <option value="unit" {{ old('kategori_satuan') == 'unit' ? 'selected' : '' }}>Unit
                                </option>
                            </select>
                            @error('kategori_satuan')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div>
                            <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok') }}" placeholder="0"
                                min="0" required
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('stok') border-red-500 @enderror">
                            @error('stok')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Deskripsi Produk -->
                    <div>
                        <label for="deskripsi_produk" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Produk <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi_produk" id="deskripsi_produk" rows="5"
                            placeholder="Tuliskan deskripsi lengkap produk Anda..."
                            class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('deskripsi_produk') border-red-500 @enderror">{{ old('deskripsi_produk') }}</textarea>
                        <p class="mt-1 text-xs text-gray-400"><span id="char-count">0</span> karakter</p>
                        @error('deskripsi_produk')
                            <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Produk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Produk <span class="text-xs text-gray-400 font-normal">(Opsional, maks. 5 foto)</span>
                        </label>

                        <!-- Preview Container -->
                        <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-4"></div>

                        <!-- Upload Area -->
                        <div id="drop-zone"
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
                                    <span class="text-primary font-medium">Klik untuk upload</span> atau drag & drop
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG, WEBP (Maks. 5MB per foto) — Bisa pilih
                                    hingga 5 foto</p>
                            </div>
                        </div>

                        <p class="mt-1 text-xs text-gray-400" id="foto-count-info"></p>

                        @error('foto_produk')
                            <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('foto_produk.*')
                            <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('umkm.produk.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('foto_produk');
            const previewContainer = document.getElementById('preview-container');
            const hargaInput = document.getElementById('harga');
            const hargaPreview = document.getElementById('harga-preview');
            const deskripsiInput = document.getElementById('deskripsi_produk');
            const charCount = document.getElementById('char-count');
            const fotoCountInfo = document.getElementById('foto-count-info');

            const MAX_FOTO = 5;
            let selectedFiles = [];

            // ── Harga formatter ───────────────────────────────────────────────
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

            hargaInput.addEventListener('input', function(e) {
                this.value = formatRupiah(this.value);
            });

            // Initial format if any
            if (hargaInput.value) {
                hargaInput.value = formatRupiah(hargaInput.value);
            }

            // Strip dots before submit
            const mainFormProduk = document.getElementById('main-form-produk');
            if (mainFormProduk) {
                mainFormProduk.addEventListener('submit', function() {
                    hargaInput.value = hargaInput.value.replace(/\./g, '');
                });
            }

            // ── Character count deskripsi ─────────────────────────────────────
            deskripsiInput.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
            charCount.textContent = deskripsiInput.value.length;

            // ── File input change ─────────────────────────────────────────────
            fileInput.addEventListener('change', function(e) {
                handleFiles(Array.from(e.target.files));
            });

            // ── Drag & Drop ───────────────────────────────────────────────────
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-primary', 'bg-primary-50');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-primary', 'bg-primary-50');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-primary', 'bg-primary-50');
                const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                if (files.length > 0) handleFiles(files);
            });

            // ── Handle files ──────────────────────────────────────────────────
            function handleFiles(files) {
                const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

                files.forEach(file => {
                    if (!allowed.includes(file.type)) {
                        alert(`File "${file.name}" tidak didukung. Gunakan PNG, JPG, atau WEBP.`);
                        return;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File "${file.name}" terlalu besar. Maksimal 5MB per foto.`);
                        return;
                    }
                    if (selectedFiles.length >= MAX_FOTO) {
                        alert(`Maksimal ${MAX_FOTO} foto.`);
                        return;
                    }
                    selectedFiles.push(file);
                });

                updateFileInput();
                refreshPreviews();
            }

            function updateFotoCountInfo() {
                if (fotoCountInfo) {
                    fotoCountInfo.textContent = selectedFiles.length > 0 ?
                        `${selectedFiles.length} dari ${MAX_FOTO} foto digunakan` :
                        '';
                }
            }

            function addPreview(file, index) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.setAttribute('data-index', index);
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}"
                            class="w-full h-24 sm:h-32 object-cover rounded-lg border border-gray-200">
                        <button type="button" onclick="removeImage(${index})"
                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <span class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1.5 py-0.5 rounded pointer-events-none">
                            ${index + 1}
                        </span>
                        <p class="mt-1 text-xs text-gray-500 truncate">${file.name}</p>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            }

            window.removeImage = function(index) {
                selectedFiles.splice(index, 1);
                updateFileInput();
                refreshPreviews();
            };

            function refreshPreviews() {
                previewContainer.innerHTML = '';
                selectedFiles.forEach((file, index) => addPreview(file, index));
                updateFotoCountInfo();
            }

            function updateFileInput() {
                const dt = new DataTransfer();
                selectedFiles.forEach(f => dt.items.add(f));
                fileInput.files = dt.files;
            }

            // Focus on first input (desktop only)
            if (window.innerWidth >= 768) {
                document.getElementById('nama_produk')?.focus();
            }
        });
    </script>
@endpush

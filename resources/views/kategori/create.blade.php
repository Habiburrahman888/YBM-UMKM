@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Tambah Kategori Baru</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Lengkapi form berikut untuk menambahkan kategori baru</p>
            </div>

            <form action="{{ route('kategori.store') }}" method="POST" class="p-4 sm:p-6">
                @csrf

                <div class="space-y-4 sm:space-y-5">
                    <!-- Nama Kategori -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" autofocus
                            class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama') border-red-500 ring-1 ring-red-500 @enderror"
                            placeholder="Contoh: Elektronik, Fashion, Makanan & Minuman">
                        <p class="mt-1 text-xs text-gray-500">Masukkan nama kategori yang mudah dipahami dan deskriptif</p>
                        @error('nama')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Character Counter -->
                    <div class="flex justify-end">
                        <span class="text-xs text-gray-500">
                            <span id="char-count">0</span> / 255 karakter
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('kategori.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.getElementById('nama');
            const charCount = document.getElementById('char-count');

            // Character counter
            namaInput.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;

                // Change color when approaching limit
                if (length > 240) {
                    charCount.classList.remove('text-gray-500');
                    charCount.classList.add('text-orange-500');
                } else if (length > 250) {
                    charCount.classList.remove('text-orange-500');
                    charCount.classList.add('text-red-500');
                } else {
                    charCount.classList.remove('text-orange-500', 'text-red-500');
                    charCount.classList.add('text-gray-500');
                }
            });

            // Auto-capitalize first letter of each word
            namaInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = this.value
                        .split(' ')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                        .join(' ');
                }
            });

            // Prevent form submission if empty
            const form = namaInput.closest('form');
            form.addEventListener('submit', function(e) {
                if (namaInput.value.trim() === '') {
                    e.preventDefault();
                    namaInput.focus();
                    namaInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                    
                    // Show error if not exists
                    if (!document.querySelector('.nama-error')) {
                        const errorP = document.createElement('p');
                        errorP.className = 'mt-1.5 text-xs text-red-500 flex items-center nama-error';
                        errorP.innerHTML = `
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Nama kategori tidak boleh kosong
                        `;
                        namaInput.parentElement.appendChild(errorP);
                    }
                }
            });

            // Remove error on input
            namaInput.addEventListener('input', function() {
                this.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                const errorEl = document.querySelector('.nama-error');
                if (errorEl) {
                    errorEl.remove();
                }
            });

            // Focus on nama input when page loads
            namaInput.focus();
        });
    </script>
@endpush
@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Profil</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi profil Anda</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="p-4 sm:p-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Upload Foto Profil --}}
                    <div>
                        <div class="flex items-start gap-6">
                            <div class="relative">
                                @if ($user->foto_profil && file_exists(storage_path('app/public/' . $user->foto_profil)))
                                    <img id="profile_preview" src="{{ asset('storage/' . $user->foto_profil) }}"
                                        alt="{{ $user->username }}"
                                        class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-md">
                                @else
                                    <div id="profile_placeholder"
                                        class="w-32 h-32 rounded-full bg-gradient-to-br from-primary to-primary-600 flex items-center justify-center shadow-md border-4 border-gray-100">
                                        <span class="text-white text-4xl font-bold">
                                            {{ strtoupper(substr($user->username ?? $user->email, 0, 1)) }}
                                        </span>
                                    </div>
                                    <img id="profile_preview" src="" alt="" class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-md hidden">
                                @endif

                                <button type="button" onclick="document.getElementById('foto_profil_input').click()"
                                    class="absolute bottom-0 right-0 bg-primary hover:bg-primary-600 text-white rounded-full p-2 shadow-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                                <input type="file" id="foto_profil_input" name="foto_profil"
                                    accept="image/png,image/jpeg,image/jpg,image/gif" class="hidden">
                            </div>
                            <div class="flex-1 self-center">
                                <label class="block text-xs font-medium text-gray-700 mb-2">
                                    Upload Foto Profil
                                </label>
                                <p class="text-xs text-gray-500">
                                    Klik ikon kamera untuk mengubah foto profil Anda. Format: JPG, JPEG, PNG, GIF. Max: 2MB.
                                </p>
                                @if ($user->foto_profil)
                                    <button type="button" onclick="confirmDeletePhoto()"
                                        class="mt-2 text-xs text-red-600 hover:text-red-800">
                                        Hapus foto
                                    </button>
                                @endif
                            </div>
                        </div>
                        @error('foto_profil')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                            placeholder="Username Anda"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none @error('username') border-red-500 @enderror"
                            required>
                        @error('username')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-500">Username hanya boleh berisi huruf, angka, dash (-) dan
                                underscore (_)</p>
                        @enderror
                    </div>

                    {{-- Email (Read Only) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
                            {{ $user->email }}
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Untuk mengubah email, silakan gunakan menu <a href="{{ route('profile.email.edit') }}"
                                class="text-primary hover:underline">Ubah Email</a>
                        </p>
                    </div>

                    {{-- Role (Read Only) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            Role
                        </label>
                        <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
                            {{ ucfirst($user->role) }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-row items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('profile.show') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Photo Form (Hidden) --}}
    <form id="delete_photo_form" action="{{ route('profile.delete-photo') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        // Preview foto profil
        document.getElementById('foto_profil_input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran
                if (file.size > 2048 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    e.target.value = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus JPG, JPEG, PNG, atau GIF');
                    e.target.value = '';
                    return;
                }

                // Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profile_preview');
                    const placeholder = document.getElementById('profile_placeholder');

                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Konfirmasi hapus foto
        function confirmDeletePhoto() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                document.getElementById('delete_photo_form').submit();
            }
        }

        // Validasi username
        document.querySelector('[name="username"]').addEventListener('input', function(e) {
            // Hanya izinkan huruf, angka, dash, dan underscore
            this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, '');
        });
    </script>
@endpush
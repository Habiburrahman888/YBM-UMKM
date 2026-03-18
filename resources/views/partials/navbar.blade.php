<nav class="bg-white border-b border-gray-200 px-4 py-3 lg:px-6 shadow-sm">
    <div class="flex items-center justify-between">
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <div class="hidden lg:block">
            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="flex items-center space-x-4">
            @guest
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                    Masuk
                </a>
            @else
                <div class="relative" id="user-dropdown-container">
                    <button type="button" id="user-dropdown-btn"
                        class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            {{-- PERBAIKAN: Sesuaikan dengan kolom foto_profil --}}
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-primary-600 flex items-center justify-center border-2 border-gray-300 overflow-hidden">
                                @if (auth()->user()->foto_profil && file_exists(storage_path('app/public/' . auth()->user()->foto_profil)))
                                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}"
                                        alt="Foto Profil" class="w-full h-full object-cover">
                                @else
                                    {{-- Tampilkan inisial jika tidak ada foto --}}
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->email, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="hidden md:block text-left">
                                {{-- PERBAIKAN: Hapus dataPribadi, langsung gunakan username --}}
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ auth()->user()->username ?? 'User' }}
                                </p>
                                <p class="text-xs text-gray-600 capitalize">
                                    {{ ucfirst(auth()->user()->role) }}
                                </p>
                            </div>
                        </div>
                        <svg id="dropdown-chevron" class="w-4 h-4 text-gray-600 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div id="user-dropdown"
                        class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100">
                            {{-- PERBAIKAN: Username langsung dari tabel users --}}
                            <p class="text-sm font-medium text-gray-900">
                                {{ auth()->user()->username ?? 'User' }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            {{-- PERBAIKAN: Tambahkan route profil --}}
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Profil Saya
                            </a>
                            {{-- PERBAIKAN: Tambahkan route settings jika ada --}}
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Pengaturan
                            </a>
                        </div>
                        <div class="border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('user-dropdown-btn');
        const dropdown = document.getElementById('user-dropdown');
        const chevron = document.getElementById('dropdown-chevron');

        if (dropdownBtn && dropdown) {
            // Toggle dropdown saat button diklik
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = dropdown.classList.contains('hidden');

                if (isHidden) {
                    dropdown.classList.remove('hidden');
                    chevron?.classList.add('rotate-180');
                } else {
                    dropdown.classList.add('hidden');
                    chevron?.classList.remove('rotate-180');
                }
            });

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && !dropdownBtn.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    chevron?.classList.remove('rotate-180');
                }
            });

            // Tutup dropdown saat tekan Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                    chevron?.classList.remove('rotate-180');
                }
            });
        }
    });

    // Fungsi global untuk kompatibilitas (jika dipanggil dari tempat lain)
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }
</script>

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #user-dropdown:not(.hidden) {
        animation: slideDown 0.2s ease-out;
    }
</style>
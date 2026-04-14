@extends('layouts.app')

@section('title', 'Produk Saya')
@section('page-title', 'Produk Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Produk Saya</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $produks->total() }} Produk</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('umkm.produk.create') }}"
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
                            <form method="GET" action="{{ route('umkm.produk.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
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
                                            placeholder="Cari nama..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if ($produks->count() > 0)
                {{-- Desktop View - Table --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($produks as $index => $produk)
                                <tr class="hover:bg-gray-50 transition-colors">

                                    {{-- Thumbnail --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (!empty($produk->foto_produk) && count($produk->foto_produk) > 0)
                                            <button type="button"
                                                onclick="openGallery('{{ $produk->nama_produk }}', {{ json_encode(array_map(fn($foto) => asset('storage/' . $foto), $produk->foto_produk)) }})"
                                                class="flex -space-x-2 group cursor-pointer hover:opacity-90 transition-opacity">
                                                @foreach (array_slice($produk->foto_produk, 0, 3) as $index => $foto)
                                                    <img src="{{ asset('storage/' . $foto) }}"
                                                        alt="{{ $produk->nama_produk }}"
                                                        class="w-12 h-12 rounded-lg object-cover shadow-sm border-2 border-white group-hover:border-primary/30 transition-colors">
                                                @endforeach
                                                @if (count($produk->foto_produk) > 3)
                                                    <div
                                                        class="w-12 h-12 rounded-lg bg-gray-200 group-hover:bg-primary/10 flex items-center justify-center border-2 border-white transition-colors">
                                                        <span
                                                            class="text-xs font-medium text-gray-600 group-hover:text-primary">+{{ count($produk->foto_produk) - 3 }}</span>
                                                    </div>
                                                @endif
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-gray-100 group-hover:bg-primary/5 flex items-center justify-center border-2 border-white opacity-0 group-hover:opacity-100 transition-all -ml-4">
                                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-primary"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </button>
                                        @else
                                            <div class="h-14 w-14 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Nama Produk + Deskripsi --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $produk->nama_produk }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5 line-clamp-1 max-w-xs">
                                            {{ $produk->deskripsi_produk }}</div>
                                    </td>

                                    {{-- Harga --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($produk->harga, 0, ',', '.') }} <span
                                            class="text-gray-500 font-normal">/ {{ $produk->kategori_satuan }}</span>
                                    </td>

                                    {{-- Stok --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($produk->stok > 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ number_format($produk->stok, 0, ',', '.') }} Item
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Stok Habis
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button" data-dropdown-toggle="{{ $produk->uuid }}"
                                                data-nama="{{ $produk->nama_produk }}"
                                                data-stok="{{ $produk->stok ?? 0 }}"
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

                {{-- Mobile View - Cards --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($produks as $index => $produk)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex gap-3">
                                @if (!empty($produk->foto_produk) && count($produk->foto_produk) > 0)
                                    <button type="button"
                                        onclick="openGallery('{{ $produk->nama_produk }}', {{ json_encode(array_map(fn($foto) => asset('storage/' . $foto), $produk->foto_produk)) }})"
                                        class="relative flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden shadow-sm group cursor-pointer">
                                        <img src="{{ asset('storage/' . $produk->foto_produk[0]) }}"
                                            alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                                        @if (count($produk->foto_produk) > 1)
                                            <div
                                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span
                                                    class="text-white text-sm font-medium">{{ count($produk->foto_produk) }}
                                                    foto</span>
                                            </div>
                                            <div
                                                class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded group-hover:hidden">
                                                +{{ count($produk->foto_produk) - 1 }}
                                            </div>
                                        @endif
                                    </button>
                                @else
                                    <div
                                        class="flex-shrink-0 w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="text-sm font-semibold text-gray-900">{{ $produk->nama_produk }}
                                                </h3>
                                                @if ($produk->stok > 0)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Stok: {{ $produk->stok }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Habis
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-600 line-clamp-2">{{ $produk->deskripsi_produk }}
                                            </p>
                                            <p class="text-sm font-medium text-gray-800 mt-1">
                                                Rp {{ number_format($produk->harga, 0, ',', '.') }} <span
                                                    class="text-gray-500 font-normal text-xs">/
                                                    {{ $produk->kategori_satuan }}</span>
                                            </p>
                                        </div>
                                        <button type="button" data-dropdown-toggle="{{ $produk->uuid }}"
                                            data-nama="{{ $produk->nama_produk }}"
                                            data-stok="{{ $produk->stok ?? 0 }}"
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

                {{-- Pagination --}}
                @if ($produks->hasPages())
                    <div class="px-4 sm:px-6 py-3">
                        {{ $produks->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Produk</h3>
                    <p class="text-sm text-gray-500 mb-6">Mulai tambahkan produk untuk ditampilkan ke pelanggan.</p>
                    <a href="{{ route('umkm.produk.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Produk Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Dropdown Menu --}}
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                <a href="#" id="dropdown-show-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat
                </a>
                <button type="button" id="dropdown-add-stok-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Stok
                </button>
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
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

    {{-- Delete Modal --}}
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Produk</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus produk
                "<span id="modal-produk-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Semua foto produk juga akan ikut terhapus.
            </p>
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
    {{-- Tambah Stok Modal --}}
    <div id="stok-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 text-center">Tambah Stok Produk</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-4 text-center">
                Masukkan jumlah stok yang ingin ditambahkan untuk produk
                "<span id="stok-modal-produk-name" class="font-semibold text-gray-700"></span>".
            </p>
            <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-100 text-center">
                <span class="text-xs text-gray-500 block mb-1">Stok Saat Ini</span>
                <span id="stok-modal-current" class="text-lg font-bold text-primary">0</span>
            </div>
            <form id="stok-form" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="stok-input" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tambahan</label>
                    <input type="number" name="stok" id="stok-input" min="0" value="0" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                </div>
                <div class="flex justify-center gap-2 sm:gap-3">
                    <button type="button" id="cancel-stok-btn"
                        class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-primary text-xs sm:text-sm font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- Gallery Modal --}}
    <div id="gallery-modal" class="fixed inset-0 bg-black/90 hidden z-[60] flex items-center justify-center">
        <!-- Close Button -->
        <button type="button" onclick="closeGallery()"
            class="absolute top-4 right-4 z-10 p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Gallery Title -->
        <div class="absolute top-4 left-4 z-10">
            <h3 id="gallery-title" class="text-white font-medium text-lg"></h3>
            <p id="gallery-counter" class="text-white/70 text-sm"></p>
        </div>

        <!-- Previous Button -->
        <button type="button" onclick="prevImage()" id="gallery-prev"
            class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-10 p-2 sm:p-3 text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-colors">
            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Main Image Container -->
        <div class="w-full h-full flex items-center justify-center p-12 sm:p-16">
            <img id="gallery-main-image" src="" alt=""
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        </div>

        <!-- Next Button -->
        <button type="button" onclick="nextImage()" id="gallery-next"
            class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-10 p-2 sm:p-3 text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-colors">
            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Thumbnails -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10">
            <div id="gallery-thumbnails" class="flex gap-2 p-2 bg-black/50 rounded-xl max-w-[90vw] overflow-x-auto">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentDropdownData = null;

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

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const showLink = document.getElementById('dropdown-show-link');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const addStokBtn = document.getElementById('dropdown-add-stok-btn');
            const tableContainer = document.getElementById('table-container');

            // Handle dropdown toggle
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();
                    const dropdownId = toggle.getAttribute('data-dropdown-toggle');
                    const namaProduk = toggle.getAttribute('data-nama');
                    const stokProduk = toggle.getAttribute('data-stok');

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

                    showLink.href = `/produk/${dropdownId}`;
                    editLink.href = `/produk/${dropdownId}/edit`;

                    currentDropdownData = {
                        id: dropdownId,
                        nama: namaProduk,
                        stok: stokProduk
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
                document.getElementById('modal-produk-name').textContent = currentDropdownData.nama;
                modal.classList.remove('hidden');
            });

            // Add stock button handler
            addStokBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!currentDropdownData) return;

                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-id');

                const modal = document.getElementById('stok-modal');
                const form = document.getElementById('stok-form');
                document.getElementById('stok-modal-produk-name').textContent = currentDropdownData.nama;
                document.getElementById('stok-modal-current').textContent = currentDropdownData.stok;
                document.getElementById('stok-input').value = 0;
                form.action = `/produk/${currentDropdownData.id}/tambah-stok`;
                modal.classList.remove('hidden');
            });

            // Confirm delete
            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (!currentDropdownData) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/produk/${currentDropdownData.id}`;

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

            // Cancel modal
            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            document.getElementById('cancel-stok-btn').addEventListener('click', function() {
                document.getElementById('stok-modal').classList.add('hidden');
            });

            // Close modal on backdrop click
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });

            document.getElementById('stok-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });

            // Close modal on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.getElementById('delete-modal').classList.add('hidden');
                    document.getElementById('stok-modal').classList.add('hidden');
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

            // Keyboard navigation for gallery
            document.addEventListener('keydown', function(e) {
                const galleryModal = document.getElementById('gallery-modal');
                if (galleryModal.classList.contains('hidden')) return;

                if (e.key === 'Escape') {
                    closeGallery();
                } else if (e.key === 'ArrowLeft') {
                    prevImage();
                } else if (e.key === 'ArrowRight') {
                    nextImage();
                }
            });
        });

        // Gallery Functions
        let galleryImages = [];
        let currentImageIndex = 0;

        function openGallery(title, images) {
            galleryImages = images;
            currentImageIndex = 0;

            document.getElementById('gallery-title').textContent = title;
            updateGalleryImage();
            renderThumbnails();

            document.getElementById('gallery-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('gallery-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function updateGalleryImage() {
            const mainImage = document.getElementById('gallery-main-image');
            const counter = document.getElementById('gallery-counter');
            const prevBtn = document.getElementById('gallery-prev');
            const nextBtn = document.getElementById('gallery-next');

            mainImage.src = galleryImages[currentImageIndex];
            counter.textContent = `${currentImageIndex + 1} dari ${galleryImages.length} foto`;

            prevBtn.style.visibility = currentImageIndex === 0 ? 'hidden' : 'visible';
            nextBtn.style.visibility = currentImageIndex === galleryImages.length - 1 ? 'hidden' : 'visible';

            document.querySelectorAll('.gallery-thumb').forEach((thumb, index) => {
                if (index === currentImageIndex) {
                    thumb.classList.add('ring-2', 'ring-white', 'opacity-100');
                    thumb.classList.remove('opacity-60');
                } else {
                    thumb.classList.remove('ring-2', 'ring-white', 'opacity-100');
                    thumb.classList.add('opacity-60');
                }
            });
        }

        function renderThumbnails() {
            const container = document.getElementById('gallery-thumbnails');
            container.innerHTML = '';

            galleryImages.forEach((image, index) => {
                const thumb = document.createElement('button');
                thumb.type = 'button';
                thumb.className =
                    `gallery-thumb flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-lg overflow-hidden transition-all hover:opacity-100 ${index === 0 ? 'ring-2 ring-white opacity-100' : 'opacity-60'}`;
                thumb.onclick = () => goToImage(index);
                thumb.innerHTML =
                    `<img src="${image}" alt="Thumbnail ${index + 1}" class="w-full h-full object-cover">`;
                container.appendChild(thumb);
            });
        }

        function prevImage() {
            if (currentImageIndex > 0) {
                currentImageIndex--;
                updateGalleryImage();
            }
        }

        function nextImage() {
            if (currentImageIndex < galleryImages.length - 1) {
                currentImageIndex++;
                updateGalleryImage();
            }
        }

        function goToImage(index) {
            currentImageIndex = index;
            updateGalleryImage();
        }

        document.getElementById('gallery-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGallery();
            }
        });
    </script>
@endpush

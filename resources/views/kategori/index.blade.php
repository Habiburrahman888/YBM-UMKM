@extends('layouts.app')

@section('title', 'Kelola Data Kategori')
@section('page-title', 'Data Kategori')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Kategori</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $kategoris->total() }} Kategori</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('kategori.create') }}"
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
                            <form method="GET" action="{{ route('kategori.index') }}" id="search-form"
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
                                            placeholder="Cari nama kategori..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request('q'))
                                        <a href="{{ route('kategori.index') }}"
                                            class="ml-2 inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="Reset pencarian">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if ($kategoris->count() > 0)
                <!-- Desktop View - Table -->
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="pl-16 pr-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/4">
                                    Nama Kategori
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                             @foreach ($kategoris as $kategori)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="pl-16 pr-6 py-4 whitespace-nowrap w-3/4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $kategori->nama }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button" data-dropdown-toggle="{{ $kategori->uuid }}"
                                                data-kategori="{{ $kategori->nama }}"
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

                <!-- Mobile View - Cards -->
                <div class="md:hidden divide-y divide-gray-200">
                     @foreach ($kategoris as $index => $kategori)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $kategori->nama }}</h3>
                                </div>
                                <button type="button" data-dropdown-toggle="{{ $kategori->uuid }}"
                                    data-kategori="{{ $kategori->nama }}"
                                    class="dropdown-toggle flex-shrink-0 ml-2 inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($kategoris->hasPages())
                    <div class="px-4 sm:px-6 py-3">
                        {{ $kategoris->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0
                                    01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                        @if (request('q'))
                            Kategori Tidak Ditemukan
                        @else
                            Belum Ada Data Kategori
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        @if (request('q'))
                            Tidak ada hasil untuk pencarian "{{ request('q') }}". Coba kata kunci lain.
                        @else
                            Mulai tambahkan data kategori untuk mengelompokkan produk Anda.
                        @endif
                    </p>
                    @if (request('q'))
                        <a href="{{ route('kategori.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <a href="{{ route('kategori.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Kategori
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
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

    <!-- Delete Modal -->
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Kategori</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus kategori
                "<span id="modal-kategori-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.
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
@endsection

@push('scripts')
    <script>
        let currentDropdownData = null;

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const tableContainer = document.getElementById('table-container');

            // Handle dropdown toggle
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();
                    const dropdownId = toggle.getAttribute('data-dropdown-toggle');
                    const kategoriName = toggle.getAttribute('data-kategori');

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

                    const dropdownHeight = 88;

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

                    editLink.href = `/kategori/${dropdownId}/edit`;

                    currentDropdownData = {
                        id: dropdownId,
                        nama: kategoriName
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
                const modalName = document.getElementById('modal-kategori-name');
                modalName.textContent = currentDropdownData.nama;
                modal.classList.remove('hidden');
            });

            // Confirm delete
            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (!currentDropdownData) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/kategori/${currentDropdownData.id}`;

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

            // Cancel delete
            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            // Close modal on backdrop click
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
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

            // Close dropdown on resize
            window.addEventListener('resize', function() {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-id');
                }
            });
        });

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
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Kelola Data Unit')
@section('page-title', 'Data Unit')

@push('styles')
    <style>
        .dropdown-menu {
            display: none;
            position: fixed;
            width: 13rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            z-index: 9999;
        }

        .dropdown-menu.open {
            display: block;
        }

        .dropdown-wrapper {
            position: relative;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 animate-slide-up">

            {{-- ── HEADER ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Unit</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $units->total() }} Unit</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        <a href="{{ route('unit.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Tambah</span>
                        </a>

                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all {{ request()->hasAny(['provinsi', 'kota', 'status']) ? 'bg-blue-100 text-blue-700' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Filter
                                @if (request()->hasAny(['provinsi', 'kota', 'status']))
                                    <span
                                        class="inline-flex items-center justify-center w-4 h-4 text-xs bg-blue-600 text-white rounded-full ml-1">
                                        {{ collect(['provinsi', 'kota', 'status'])->filter(fn($k) => request($k))->count() }}
                                    </span>
                                @endif
                            </span>
                        </button>

                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('unit.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['provinsi', 'kota', 'status'] as $filterKey)
                                    @if (request($filterKey))
                                        <input type="hidden" name="{{ $filterKey }}" value="{{ request($filterKey) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari nama unit, kode, admin..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request('q'))
                                        <a href="{{ route('unit.index', request()->except('q')) }}"
                                            class="ml-2 inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
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

                {{-- ── PANEL FILTER ── --}}
                <div id="filter-panel"
                    class="{{ request()->hasAny(['provinsi', 'kota', 'status']) ? '' : 'hidden' }} mt-4 pt-4 border-t border-gray-100">
                    <form method="GET" action="{{ route('unit.index') }}">
                        @if (request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Provinsi</label>
                                <select name="provinsi" id="filter-provinsi"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->code }}"
                                            {{ request('provinsi') == $province->code ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Kota / Kabupaten</label>
                                <select name="kota" id="filter-kota"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                    {{ $cities->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">Semua Kota</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->code }}"
                                            {{ request('kota') == $city->code ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                <select name="status"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>
                                        Non-Aktif</option>
                                </select>
                            </div>
                            {{-- Kolom ke-4: Tombol sejajar --}}
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                                    Terapkan Filter
                                </button>
                                @if (request()->hasAny(['provinsi', 'kota', 'status']))
                                    <a href="{{ route('unit.index', request()->only('q')) }}"
                                        class="inline-flex items-center p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors"
                                        title="Reset Filter">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if ($units->count() > 0)

                {{-- ── DESKTOP TABLE ── --}}
                <div class="hidden md:block overflow-x-auto rounded-b-2xl overflow-hidden" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-80">
                                    Unit</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">
                                    Wilayah</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($units as $item)
                                {{-- Baris Utama --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                                    onclick="toggleDetail('{{ $item->uuid }}')">

                                    <td class="px-4 py-4 whitespace-nowrap w-80">
                                        <div class="flex items-center gap-3">
                                            <svg id="icon-{{ $item->uuid }}"
                                                class="w-3.5 h-3.5 flex-shrink-0 text-gray-400 transition-transform duration-200"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                            @if ($item->logo)
                                                <img src="{{ Storage::url($item->logo) }}" alt="{{ $item->nama_unit }}"
                                                    class="w-10 h-10 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-primary" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $item->nama_unit }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $item->kode_unit }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap w-64">
                                        @if ($item->kota_nama)
                                            <div class="text-sm text-gray-700">{{ $item->kota_nama }}</div>
                                        @endif
                                        @if ($item->provinsi_nama)
                                            <div class="text-xs text-gray-400">{{ $item->provinsi_nama }}</div>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap w-36">
                                        @if ($item->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Non-aktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Kolom Aksi: inline dropdown, stop propagation --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center w-16"
                                        onclick="event.stopPropagation()">
                                        <div class="dropdown-wrapper">
                                            <button type="button"
                                                onclick="toggleDropdown('menu-{{ $item->uuid }}', this)"
                                                class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div id="menu-{{ $item->uuid }}" class="dropdown-menu">
                                                <div class="py-1">

                                                    {{-- Edit --}}
                                                    <a href="{{ route('unit.edit', $item->uuid) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>

                                                    {{-- Toggle Status --}}
                                                    <div class="border-t border-gray-100 mt-1 pt-1">
                                                        <button type="button"
                                                            onclick="confirmToggleStatus('{{ $item->uuid }}','{{ addslashes($item->nama_unit) }}')"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition-colors">
                                                            <svg class="w-4 h-4 mr-3" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                            </svg>
                                                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </div>

                                                    {{-- Hapus --}}
                                                    <div class="border-t border-gray-100 mt-1 pt-1">
                                                        <button type="button"
                                                            onclick="confirmDelete('{{ $item->uuid }}','{{ addslashes($item->nama_unit) }}')"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                            <svg class="w-4 h-4 mr-3" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Baris Detail Accordion --}}
                                <tr id="detail-{{ $item->uuid }}" class="hidden">
                                    <td colspan="4" class="p-0">
                                        <div
                                            class="mx-6 my-3 bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">

                                            {{-- Baris 1: Informasi Unit + Kontak & Wilayah --}}
                                            <div class="grid grid-cols-2 divide-x divide-gray-200">

                                                {{-- Kolom Kiri: Informasi Unit --}}
                                                <div class="px-6 py-4">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                                                        Informasi Unit
                                                    </p>
                                                    <dl class="grid grid-cols-2 gap-x-6 gap-y-3">
                                                        <div>
                                                            <dt class="text-xs text-gray-400">Nama Unit</dt>
                                                            <dd class="text-sm font-semibold text-gray-900 mt-0.5">
                                                                {{ $item->nama_unit }}
                                                            </dd>
                                                        </div>
                                                        <div>
                                                            <dt class="text-xs text-gray-400">Kode Unit</dt>
                                                            <dd class="text-sm text-gray-700 mt-0.5">
                                                                {{ $item->kode_unit }}
                                                            </dd>
                                                        </div>
                                                        @if ($item->user)
                                                            <div>
                                                                <dt class="text-xs text-gray-400">User Pemilik</dt>
                                                                <dd class="text-sm text-gray-700 mt-0.5">
                                                                    {{ $item->user->username ?? $item->user->email }}
                                                                </dd>
                                                            </div>
                                                        @endif
                                                        @if ($item->admin_nama)
                                                            <div>
                                                                <dt class="text-xs text-gray-400">Admin Unit</dt>
                                                                <dd class="text-sm text-gray-700 mt-0.5">
                                                                    {{ $item->admin_nama }}
                                                                </dd>
                                                            </div>
                                                        @endif
                                                    </dl>
                                                </div>

                                                {{-- Kolom Kanan: Kontak & Wilayah --}}
                                                <div class="px-6 py-4">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                                                        Kontak & Wilayah
                                                    </p>
                                                    <dl class="grid grid-cols-2 gap-x-6 gap-y-3">
                                                        @if ($item->telepon)
                                                            <div>
                                                                <dt class="text-xs text-gray-400">Telepon</dt>
                                                                <dd class="text-sm text-gray-700 mt-0.5">
                                                                    {{ $item->telepon }}
                                                                </dd>
                                                            </div>
                                                        @endif
                                                        @if ($item->email)
                                                            <div>
                                                                <dt class="text-xs text-gray-400">Email</dt>
                                                                <dd class="text-sm text-gray-700 mt-0.5 break-all">
                                                                    {{ $item->email }}
                                                                </dd>
                                                            </div>
                                                        @endif
                                                        @if ($item->provinsi_nama || $item->kota_nama || $item->kecamatan_nama || $item->kelurahan_nama || $item->kode_pos)
                                                            <div class="col-span-2">
                                                                <dt class="text-xs text-gray-400 mb-0.5">Alamat Wilayah
                                                                </dt>
                                                                <dd class="text-sm text-gray-700 leading-relaxed">
                                                                    {{ collect([
                                                                        $item->alamat,
                                                                        $item->kelurahan_nama ? 'Kel. ' . $item->kelurahan_nama : null,
                                                                        $item->kecamatan_nama ? 'Kec. ' . $item->kecamatan_nama : null,
                                                                        $item->kota_nama,
                                                                        $item->provinsi_nama,
                                                                        $item->kode_pos,
                                                                    ])->filter()->implode(', ') }}
                                                                </dd>
                                                            </div>
                                                        @endif
                                                    </dl>
                                                </div>
                                            </div>

                                            {{-- Baris 2: Deskripsi --}}
                                            @if ($item->deskripsi)
                                                <div class="border-t border-gray-200 px-6 py-4">
                                                    <p
                                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                                                        Deskripsi
                                                    </p>
                                                    <p class="text-sm text-gray-700 whitespace-pre-line">
                                                        {{ $item->deskripsi }}
                                                    </p>
                                                </div>
                                            @endif

                                            <div
                                                class="border-t border-gray-200 px-6 py-3 bg-gray-50/50 flex items-center justify-between">
                                                <div class="flex gap-6">
                                                    <div>
                                                        <dt class="text-xs text-gray-400">Dibuat</dt>
                                                        <dd class="text-xs text-gray-600 mt-0.5">
                                                            {{ $item->created_at->format('d M Y, H:i') }}
                                                        </dd>
                                                    </div>
                                                    <div>
                                                        <dt class="text-xs text-gray-400">Diperbarui</dt>
                                                        <dd class="text-xs text-gray-600 mt-0.5">
                                                            {{ $item->updated_at->format('d M Y, H:i') }}
                                                        </dd>
                                                    </div>
                                                </div>
                                                <a href="{{ route('unit.edit', $item->uuid) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-all shadow-sm">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit Unit
                                                </a>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── MOBILE CARDS ── --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($units as $item)
                        <div>
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer"
                                onclick="toggleDetail('{{ $item->uuid }}-mobile')">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-start gap-3 flex-1 min-w-0">
                                        <svg id="icon-{{ $item->uuid }}-mobile"
                                            class="w-3.5 h-3.5 flex-shrink-0 mt-1 text-gray-400 transition-transform duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                        @if ($item->logo)
                                            <img src="{{ Storage::url($item->logo) }}" alt="{{ $item->nama_unit }}"
                                                class="flex-shrink-0 h-12 w-12 rounded-lg object-cover border border-gray-200">
                                        @else
                                            <div
                                                class="flex-shrink-0 h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $item->nama_unit }}
                                                </h3>
                                                @if ($item->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium"><span
                                                            class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Aktif</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-medium"><span
                                                            class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Non-aktif</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->kode_unit }}</p>
                                            @if ($item->user)
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ $item->user->username ?? $item->user->email }}
                                                </p>
                                            @endif
                                            @if ($item->kota_nama || $item->provinsi_nama)
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ collect([$item->kota_nama, $item->provinsi_nama])->filter()->implode(', ') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Mobile dropdown --}}
                                    <div class="dropdown-wrapper flex-shrink-0" onclick="event.stopPropagation()">
                                        <button type="button"
                                            onclick="toggleDropdown('menu-mob-{{ $item->uuid }}', this)"
                                            class="inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div id="menu-mob-{{ $item->uuid }}" class="dropdown-menu">
                                            <div class="py-1">
                                                <a href="{{ route('unit.edit', $item->uuid) }}"
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <div class="border-t border-gray-100 mt-1 pt-1">
                                                    <button type="button"
                                                        onclick="confirmToggleStatus('{{ $item->uuid }}','{{ addslashes($item->nama_unit) }}')"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition-colors">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                        {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-100 mt-1 pt-1">
                                                    <button type="button"
                                                        onclick="confirmDelete('{{ $item->uuid }}','{{ addslashes($item->nama_unit) }}')"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="detail-{{ $item->uuid }}-mobile" class="hidden bg-gray-50 px-4 pb-4">
                                <div class="ml-6 pt-3 border-t border-gray-200 space-y-2">
                                    @if ($item->user)
                                        <div><span class="text-xs text-gray-500">User Pemilik</span>
                                            <p class="text-sm text-gray-700">
                                                {{ $item->user->username ?? $item->user->email }}
                                            </p>
                                        </div>
                                    @endif
                                    @if ($item->admin_nama)
                                        <div><span class="text-xs text-gray-500">Admin Unit</span>
                                            <p class="text-sm text-gray-700">{{ $item->admin_nama }}</p>
                                        </div>
                                    @endif
                                    @if ($item->telepon)
                                        <div><span class="text-xs text-gray-500">Telepon</span>
                                            <p class="text-sm text-gray-700">{{ $item->telepon }}</p>
                                        </div>
                                    @endif
                                    @if ($item->email)
                                        <div><span class="text-xs text-gray-500">Email</span>
                                            <p class="text-sm text-gray-700">{{ $item->email }}</p>
                                        </div>
                                    @endif
                                    @if ($item->alamat)
                                        <div><span class="text-xs text-gray-500">Alamat</span>
                                            <p class="text-sm text-gray-700">{{ $item->alamat }}</p>
                                        </div>
                                    @endif
                                    @if ($item->deskripsi)
                                        <div><span class="text-xs text-gray-500">Deskripsi</span>
                                            <p class="text-sm text-gray-700">{{ $item->deskripsi }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($units->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-gray-200">
                        {{ $units->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                        @if (request()->hasAny(['q', 'provinsi', 'kota', 'status']))
                            Data Unit Tidak Ditemukan
                        @else
                            Belum Ada Data Unit
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        @if (request()->hasAny(['q', 'provinsi', 'kota', 'status']))
                            Tidak ada hasil yang sesuai dengan pencarian atau filter yang diterapkan.
                        @else
                            Mulai tambahkan data unit untuk mengelola organisasi.
                        @endif
                    </p>
                    @if (request()->hasAny(['q', 'provinsi', 'kota', 'status']))
                        <a href="{{ route('unit.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Reset Semua Filter
                        </a>
                    @else
                        <a href="{{ route('unit.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Unit
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── MODAL HAPUS ── --}}
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Unit</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus unit "<span id="modal-unit-name"
                    class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan
                dan semua data terkait akan ikut terhapus.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL TOGGLE STATUS ── --}}
    <div id="toggle-status-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Ubah Status Unit
            </h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin mengubah status unit "<span id="toggle-modal-unit-name"
                    class="font-semibold text-gray-700"></span>"?
            </p>
            <div class="flex justify-center gap-2 sm:gap-3 mt-5">
                <button type="button" onclick="document.getElementById('toggle-status-modal').classList.add('hidden')"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-toggle-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-blue-600 text-xs sm:text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Ya, Ubah
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let deleteUuid = null;
        let toggleUuid = null;

        // ── Toggle accordion detail baris ─────────────────────────────────────────
        function toggleDetail(id) {
            const row = document.getElementById('detail-' + id);
            const icon = document.getElementById('icon-' + id);
            if (!row) return;
            const isHidden = row.classList.contains('hidden');
            row.classList.toggle('hidden', !isHidden);
            if (icon) icon.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
        }

        // ── Toggle dropdown (fixed positioning agar tidak terpotong) ─────────────
        function toggleDropdown(menuId, btn) {
            document.querySelectorAll('.dropdown-menu.open').forEach(function(m) {
                if (m.id !== menuId) m.classList.remove('open');
            });

            const menu = document.getElementById(menuId);
            if (!menu) return;

            if (menu.classList.contains('open')) {
                menu.classList.remove('open');
                return;
            }

            // Tampilkan dulu baru hitung posisi
            menu.classList.add('open');

            const rect = btn.getBoundingClientRect();
            const menuW = 208;
            const menuH = menu.offsetHeight;

            let top = rect.bottom + 4;
            let left = rect.right - menuW;

            if (left < 8) left = 8;
            if (top + menuH > window.innerHeight - 8) top = rect.top - menuH - 4;

            menu.style.top = top + 'px';
            menu.style.left = left + 'px';
        }

        // Tutup saat klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-wrapper') && !e.target.closest('.dropdown-menu')) {
                document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
            }
        });

        // Tutup saat scroll
        window.addEventListener('scroll', function() {
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }, true);

        // ── Filter Panel ──────────────────────────────────────────────────────────
        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // ── Search Toggle ─────────────────────────────────────────────────────────
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
                const hasQuery = '{{ request('
                                                                            q ') }}' !== '';
                if (!hasQuery) searchInput.value = '';
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        // ── Konfirmasi Hapus ──────────────────────────────────────────────────────
        function confirmDelete(uuid, nama) {
            deleteUuid = uuid;
            document.getElementById('modal-unit-name').textContent = nama;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (!deleteUuid) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/unit/${deleteUuid}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });

        // ── Konfirmasi Toggle Status ──────────────────────────────────────────────
        function confirmToggleStatus(uuid, nama) {
            toggleUuid = uuid;
            document.getElementById('toggle-modal-unit-name').textContent = nama;
            document.getElementById('toggle-status-modal').classList.remove('hidden');
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }

        document.getElementById('confirm-toggle-btn').addEventListener('click', function() {
            if (!toggleUuid) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/unit/${toggleUuid}/toggle-status`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        });

        document.getElementById('toggle-status-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    </script>
@endpush

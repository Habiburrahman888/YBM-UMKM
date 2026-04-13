@extends('layouts.app')

@section('title', 'Kelola Data UMKM')
@section('page-title', 'Data UMKM')
@section('page-subtitle', 'Manajemen mitra UMKM binaan dan status pemberdayaan')

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
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card overflow-hidden animate-slide-up">

            {{-- ── HEADER ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar UMKM</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: <span class="font-medium text-gray-600">{{ $umkmList->total() }}</span> UMKM terdaftar
                        </p>
                    </div>
                    <div class="flex items-center gap-2">

                        {{-- Tombol Tambah --}}
                        @if ($permissions['canCreate'])
                            <a href="{{ route('umkm.create') }}"
                                class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="ml-2 hidden sm:inline-block">Tambah</span>
                            </a>
                        @endif

                        {{-- Tombol Filter --}}
                        @php $hasFilter = request()->hasAny(['status', 'kategori_id', 'province_code', 'city_code', 'unit_id']); @endphp
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg transition-all {{ $hasFilter ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">
                                Filter
                                @if ($hasFilter)
                                    @php
                                        $filterCount = collect([
                                            'status',
                                            'kategori_id',
                                            'province_code',
                                            'city_code',
                                            'unit_id',
                                        ])
                                            ->filter(fn($key) => request($key))
                                            ->count();
                                    @endphp
                                    <span
                                        class="inline-flex items-center justify-center w-4 h-4 text-xs bg-blue-600 text-white rounded-full ml-1">
                                        {{ $filterCount }}
                                    </span>
                                @endif
                            </span>
                        </button>

                        {{-- Tombol Cari --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width:280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="ml-2 hidden sm:inline-block">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('umkm.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status', 'kategori_id', 'province_code', 'city_code', 'unit_id'] as $filterKey)
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
                                            placeholder="Cari nama usaha / pemilik..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-xl bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request('q'))
                                        <a href="{{ route('umkm.index', request()->except('q')) }}"
                                            class="ml-2 inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- ── PANEL FILTER ── --}}
            <div id="filter-section"
                class="{{ $hasFilter ? '' : 'hidden' }} transition-all duration-300">
                <div class="px-4 sm:px-6 py-4 bg-gray-50">
                    <form method="GET" action="{{ route('umkm.index') }}">
                        @if (request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">

                            {{-- Status --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                <select name="status"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif</option>
                                </select>
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                                <select name="kategori_id"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Unit (admin only) --}}
                            @if (auth()->user()->role === 'admin')
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Unit</label>
                                    <select name="unit_id"
                                        class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                        <option value="">Semua Unit</option>
                                        @foreach ($unitList as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Provinsi --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Provinsi</label>
                                <select name="province_code" id="filter-province"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Provinsi</option>
                                    @foreach ($provinceList as $province)
                                        <option value="{{ $province->code }}"
                                            {{ request('province_code') === $province->code ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kota / Kabupaten --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Kota / Kabupaten</label>
                                <select name="city_code" id="filter-city"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Kota</option>
                                    @foreach ($cityList as $city)
                                        <option value="{{ $city->code }}"
                                            {{ request('city_code') === $city->code ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Aksi Filter --}}
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                                    Terapkan Filter
                                </button>
                                @if ($hasFilter)
                                    <a href="{{ route('umkm.index', request()->only('q')) }}"
                                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                        Reset
                                    </a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            @if ($umkmList->count() > 0)

                {{-- ── DESKTOP TABLE ── --}}
                <div class="hidden md:block overflow-x-auto overflow-y-visible">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            @php
                                $isGrouped = auth()->user()->role === 'admin' && !request('unit_id') && !request('q');
                                $displayList = $isGrouped ? $umkmList->getCollection()->groupBy('unit_id') : $umkmList;
                            @endphp

                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Informasi UMKM</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Wilayah</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Modal Bantuan</th>
                                <th
                                    class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    {{ $isGrouped ? 'Total UMKM' : 'Status' }}
                                </th>
                                @if (in_array(auth()->user()->role, ['admin', 'unit']))
                                    <th class="px-6 py-4 w-20"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-visible">


                            @if ($isGrouped)

                                {{-- ── Grouped by Unit (Admin view) ── --}}
                                @foreach ($displayList as $unitId => $group)
                                    @php $unit = $group->first()->unit; @endphp

                                    {{-- Unit Header Row --}}
                                    <tr class="bg-gray-50/80 cursor-pointer border-y border-gray-100 group/unit hover:bg-gray-100/50 transition-colors"
                                        onclick="toggleUnitGroup('unit-group-{{ $unitId ?: '0' }}', this)">

                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <svg class="unit-chevron w-4 h-4 text-gray-400 transition-transform flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-md flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-semibold text-gray-900 leading-tight">
                                                        {{ $unit->nama_unit ?? 'PUSAT / TANPA UNIT' }}</h3>
                                                    <p class="text-xs text-gray-400 mt-0.5">{{ $unit->kode_unit ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 max-w-xs">
                                            @if ($unit && $unit->alamat)
                                                <div class="text-sm text-gray-700 line-clamp-2 leading-relaxed">
                                                    {{ $unit->alamat }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">
                                                    {{ $unit->kota_nama ?? '' }}{{ $unit->provinsi_nama ? ' • ' . $unit->provinsi_nama : '' }}
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Alamat tidak diset</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4">
                                            <p class="text-xs text-gray-500 mb-0.5">Total Modal Unit</p>
                                            <p class="text-sm font-semibold text-primary">
                                                Rp
                                                {{ number_format($group->sum(fn($umkm) => $umkm->modalUmkm->sum('nilai_modal')), 0, ',', '.') }}
                                            </p>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div
                                                class="px-2.5 py-1 rounded-full bg-primary/10 border border-primary/20 inline-flex">
                                                <span class="text-xs font-semibold text-primary">{{ $group->count() }}
                                                    UMKM</span>
                                            </div>
                                        </td>

                                        @if (in_array(auth()->user()->role, ['admin', 'unit']))
                                            <td class="px-6 py-4"></td>
                                        @endif
                                    </tr>

                                    {{-- Unit Items --}}
                                    @foreach ($group as $item)
                                        @php $rowClass = 'unit-group-' . ($unitId ?: '0') . ' hidden'; @endphp

                                        {{-- Baris Utama --}}
                                        <tr class="{{ $rowClass }} hover:bg-gray-50 transition-colors cursor-pointer"
                                            onclick="toggleDetail('{{ $item->uuid }}')">

                                            {{-- Nama Usaha / Pemilik --}}
                                            <td class="px-4 sm:px-6 py-4">
                                                <div class="flex items-start gap-3">
                                                    <button type="button"
                                                        class="flex-shrink-0 mt-0.5 text-gray-400 hover:text-gray-600 transition-colors">
                                                        <svg id="icon-{{ $item->uuid }}"
                                                            class="w-3.5 h-3.5 transition-transform duration-200"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                    @if ($item->logo_umkm)
                                                        <img src="{{ Storage::url($item->logo_umkm) }}"
                                                            alt="{{ $item->nama_usaha }}"
                                                            class="w-9 h-9 rounded-lg object-cover border border-gray-100 flex-shrink-0 cursor-pointer hover:brightness-90 transition"
                                                            onclick="event.stopPropagation(); openGaleri(['{{ asset('storage/' . $item->logo_umkm) }}'], 0)">
                                                    @else
                                                        <div
                                                            class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                            <span
                                                                class="text-xs font-semibold text-primary">{{ strtoupper(substr($item->nama_usaha, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="flex flex-col flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 mb-0.5">
                                                            <p
                                                                class="text-[10px] font-medium text-primary uppercase tracking-tight">
                                                                {{ $item->kategori?->nama ?? '—' }}</p>
                                                            @if (auth()->user()->role === 'admin' && !$isGrouped)
                                                                <span class="text-[10px] text-gray-300">•</span>
                                                                <p class="text-[10px] font-medium text-gray-500 truncate">
                                                                    {{ $item->unit->nama_unit ?? 'Pusat' }}</p>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm font-semibold text-gray-900 leading-snug">
                                                            {{ $item->nama_usaha }}</p>
                                                        <p class="text-xs font-medium text-gray-400 mt-0.5">
                                                            {{ $item->nama_pemilik }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Wilayah --}}
                                            <td class="px-4 py-4">
                                                @if ($item->city || $item->province)
                                                    <div class="text-sm text-gray-700">{{ $item->city?->name ?? '—' }}
                                                    </div>
                                                    @if ($item->province)
                                                        <div class="text-xs text-gray-400 mt-0.5">
                                                            {{ $item->province->name }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Modal Usaha --}}
                                            <td class="px-4 py-4 min-w-[220px] overflow-visible">
                                                @include('umkm.partials.modal_usaha_column', [
                                                    'item' => $item,
                                                ])
                                            </td>

                                            {{-- Status --}}
                                            <td class="px-4 py-4">
                                                @if ($item->status === 'aktif' && (!$item->unit || $item->unit->is_active) && ($item->user && $item->user->is_active))
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>Aktif
                                                    </span>
                                                @elseif ($item->status === 'aktif' && $item->user && !$item->user->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-600 text-xs font-medium"
                                                        title="Produk tayang di guest, tapi UMKM tidak bisa login. Silakan klik Aktifkan Akun di menu aksi.">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Akun Inaktif
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>Nonaktif
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Aksi --}}
                                            @if (in_array(auth()->user()->role, ['admin', 'unit']))
                                                <td class="px-4 sm:px-6 py-4 text-right"
                                                    onclick="event.stopPropagation()">
                                                    @include('umkm.partials.actions_dropdown', [
                                                        'item' => $item,
                                                        'permissions' => $permissions,
                                                    ])
                                                </td>
                                            @endif
                                        </tr>

                                        {{-- Accordion Detail --}}
                                        <tr id="detail-{{ $item->uuid }}"
                                            class="{{ $rowClass }} hidden bg-gray-50">
                                            <td colspan="{{ in_array(auth()->user()->role, ['admin', 'unit']) ? 5 : 4 }}"
                                                class="px-4 sm:px-6 py-3">
                                                @include('umkm.partials.detail_accordion', [
                                                    'item' => $item,
                                                ])
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                {{-- ── Flat List ── --}}
                                @foreach ($umkmList as $item)
                                    {{-- Baris Utama --}}
                                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                                        onclick="toggleDetail('{{ $item->uuid }}')">

                                        {{-- Nama Usaha / Pemilik --}}
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="flex items-start gap-3">
                                                <button type="button"
                                                    class="flex-shrink-0 mt-0.5 text-gray-400 hover:text-gray-600 transition-colors">
                                                    <svg id="icon-{{ $item->uuid }}"
                                                        class="w-3.5 h-3.5 transition-transform duration-200"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>
                                                @if ($item->logo_umkm)
                                                    <img src="{{ Storage::url($item->logo_umkm) }}"
                                                        alt="{{ $item->nama_usaha }}"
                                                        class="w-9 h-9 rounded-lg object-cover border border-gray-100 flex-shrink-0 cursor-pointer hover:brightness-90 transition"
                                                        onclick="event.stopPropagation(); openGaleri(['{{ asset('storage/' . $item->logo_umkm) }}'], 0)">
                                                @else
                                                    <div
                                                        class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                        <span
                                                            class="text-xs font-semibold text-primary">{{ strtoupper(substr($item->nama_usaha, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex flex-col flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-0.5">
                                                        <p
                                                            class="text-[10px] font-medium text-primary uppercase tracking-tight">
                                                            {{ $item->kategori?->nama ?? '—' }}</p>
                                                        @if (auth()->user()->role === 'admin')
                                                            <span class="text-[10px] text-gray-300">•</span>
                                                            <p class="text-[10px] font-medium text-gray-500 truncate">
                                                                {{ $item->unit->nama_unit ?? 'Pusat' }}</p>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm font-semibold text-gray-900 leading-snug">
                                                        {{ $item->nama_usaha }}</p>
                                                    <p class="text-xs font-medium text-gray-400 mt-0.5">
                                                        {{ $item->nama_pemilik }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Wilayah --}}
                                        <td class="px-4 py-4">
                                            @if ($item->city || $item->province)
                                                <div class="text-sm text-gray-700">{{ $item->city?->name ?? '—' }}</div>
                                                @if ($item->province)
                                                    <div class="text-xs text-gray-400 mt-0.5">{{ $item->province->name }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-400">—</span>
                                            @endif
                                        </td>

                                        {{-- Modal Usaha --}}
                                        <td class="px-4 py-4 min-w-[220px] overflow-visible">
                                            @include('umkm.partials.modal_usaha_column', ['item' => $item])
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-4 py-4">
                                            @if ($item->status === 'aktif' && (!$item->unit || $item->unit->is_active) && ($item->user && $item->user->is_active))
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>Aktif
                                                </span>
                                            @elseif ($item->status === 'aktif' && $item->user && !$item->user->is_active)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-600 text-xs font-medium"
                                                    title="Produk tayang di guest, tapi UMKM tidak bisa login. Silakan klik Aktifkan Akun di menu aksi.">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></span>Akun Inaktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>Nonaktif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        @if (in_array(auth()->user()->role, ['admin', 'unit']))
                                            <td class="px-4 sm:px-6 py-4 text-right" onclick="event.stopPropagation()">
                                                @include('umkm.partials.actions_dropdown', [
                                                    'item' => $item,
                                                    'permissions' => $permissions,
                                                ])
                                            </td>
                                        @endif
                                    </tr>

                                    {{-- Accordion Detail --}}
                                    <tr id="detail-{{ $item->uuid }}" class="hidden bg-gray-50">
                                        <td colspan="{{ in_array(auth()->user()->role, ['admin', 'unit']) ? 5 : 4 }}"
                                            class="px-4 sm:px-6 py-3">
                                            @include('umkm.partials.detail_accordion', ['item' => $item])
                                        </td>
                                    </tr>
                                @endforeach

                            @endif

                        </tbody>
                    </table>
                </div>

                {{-- ── MOBILE CARDS ── --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($umkmList as $item)
                        <div>
                            {{-- Card Header --}}
                            <div class="px-4 py-4 hover:bg-gray-50/60 transition-colors cursor-pointer"
                                onclick="toggleDetail('{{ $item->uuid }}-mobile')">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-start gap-3 flex-1 min-w-0">
                                        <svg id="icon-{{ $item->uuid }}-mobile"
                                            class="w-3 h-3 flex-shrink-0 mt-1.5 text-gray-300 transition-transform duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                        @if ($item->logo_umkm)
                                            <img src="{{ Storage::url($item->logo_umkm) }}"
                                                alt="{{ $item->nama_usaha }}"
                                                class="w-10 h-10 rounded-lg object-cover border border-gray-100 cursor-pointer flex-shrink-0"
                                                onclick="openGaleri(['{{ Storage::url($item->logo_umkm) }}'], 0)">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span
                                                    class="text-sm font-semibold text-primary">{{ strtoupper(substr($item->nama_usaha, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $item->nama_usaha }}</h3>
                                                @if ($item->status === 'aktif' && (!$item->unit || $item->unit->is_active) && ($item->user && $item->user->is_active))
                                                    <span
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Aktif
                                                    </span>
                                                @elseif ($item->status === 'aktif' && $item->user && !$item->user->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-yellow-50 text-yellow-600 text-xs font-medium">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>Akun Inaktif
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Nonaktif
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->nama_pemilik }}</p>
                                            @if ($item->city || $item->province)
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ collect([$item->city?->name, $item->province?->name])->filter()->implode(', ') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dropdown-wrapper flex-shrink-0" onclick="event.stopPropagation()">
                                        @if (in_array(auth()->user()->role, ['admin', 'unit']))
                                            @include('umkm.partials.actions_dropdown', [
                                                'item' => $item,
                                                'permissions' => $permissions,
                                            ])
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Card Detail Accordion --}}
                            <div id="detail-{{ $item->uuid }}-mobile" class="hidden bg-gray-50 px-4 pb-4">
                                <div class="ml-6 pt-3 border-t border-gray-100 space-y-2.5">
                                    @if ($item->email)
                                        <div>
                                            <span class="text-xs text-gray-400">Email</span>
                                            <p class="text-sm text-gray-700 mt-0.5">{{ $item->email }}</p>
                                        </div>
                                    @endif
                                    @if ($item->alamat)
                                        <div>
                                            <span class="text-xs text-gray-400">Alamat</span>
                                            <p class="text-sm text-gray-700 mt-0.5">{{ $item->alamat }}</p>
                                        </div>
                                    @endif
                                    @if ($item->kategori)
                                        <div>
                                            <span class="text-xs text-gray-400">Kategori</span>
                                            <p class="text-sm text-gray-700 mt-0.5">{{ $item->kategori->nama }}</p>
                                        </div>
                                    @endif
                                    @if ($item->modalUmkm->isNotEmpty())
                                        <div>
                                            <span class="text-xs text-gray-400">Modal Usaha</span>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <p class="text-sm text-gray-700 font-medium">
                                                    {{ $item->modalUmkm->count() }} item · {{ $item->total_modal }}
                                                </p>
                                                @if ($item->modalUmkm->count() === 1)
                                                    @php $kondisi = $item->modalUmkm->first()->kondisi; @endphp
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium
                                                    {{ $kondisi === 'baru' ? 'bg-green-50 text-green-600' : ($kondisi === 'baik' ? 'bg-blue-50 text-blue-600' : ($kondisi === 'cukup' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600')) }}">
                                                        {{ ucfirst($kondisi) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── Pagination ── --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4">
                    @include('partials.pagination', ['paginator' => $umkmList])
                </div>
            @else
                        {{-- ── EMPTY STATE ── --}}
                        <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                            <svg class="mx-auto h-12 sm:h-16 w-12 sm:w-16 text-gray-400 mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993
                                    0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378
                                    3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                            </svg>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                                {{ request()->hasAny(['q', 'status', 'kategori_id', 'province_code', 'city_code']) ? 'Data UMKM Tidak Ditemukan' : 'Belum Ada Data UMKM' }}
                            </h3>
                            <p class="text-sm text-gray-500 mb-4 sm:mb-6">
                                {{ request()->hasAny(['q', 'status', 'kategori_id', 'province_code', 'city_code'])
                ? 'Coba ubah filter atau kata kunci pencarian Anda.'
                : 'Mulai tambahkan data UMKM untuk mengelola usaha mikro, kecil, dan menengah.' }}
                            </p>
                            @if ($permissions['canCreate'] && !request()->hasAny(['q', 'status', 'kategori_id', 'province_code', 'city_code']))
                                <a href="{{ route('umkm.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all shadow-sm">
                                    Tambah UMKM
                                </a>
                            @endif
                        </div>

            @endif
        </div>
    </div>

    {{-- ── TOOLTIP MODAL ── --}}
    <div id="modal-tooltip"
        class="fixed z-[9999] hidden w-44 p-2.5 bg-white rounded-lg shadow-xl border border-gray-100 pointer-events-none transition-opacity duration-100">
        <p id="modal-tooltip-nama" class="text-[11px] font-semibold text-gray-900"></p>
        <p id="modal-tooltip-meta" class="text-[10px] text-gray-500 mt-0.5"></p>
        <span id="modal-tooltip-kondisi"
            class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium mt-1"></span>
    </div>

    {{-- ── MODAL HAPUS ── --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="p-6 border border-gray-100 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1.5 text-center">Hapus UMKM</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus UMKM "<span id="modal-umkm-name"
                    class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs text-gray-400 mb-6 text-center">Tindakan ini tidak dapat dibatalkan dan akan menghapus akun
                terkait.</p>
            <div class="flex gap-3">
                <button type="button"
                    onclick="document.getElementById('delete-modal').classList.add('hidden'); document.getElementById('delete-modal').classList.remove('flex')"
                    class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete-btn"
                    class="flex-1 rounded-xl px-4 py-2.5 bg-red-500 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- ── LIGHTBOX GALERI ── --}}
    <div id="galeri-modal" class="fixed inset-0 z-[9999] bg-black/90 hidden items-center justify-center p-4"
        onclick="closeGaleri()">
        <div class="relative w-full max-w-3xl" onclick="event.stopPropagation()">
            <div class="relative flex items-center justify-center min-h-[300px]">
                <img id="galeri-img" src="" alt="Preview"
                    class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl">
            </div>
            <button onclick="galeriNav(-1)"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button onclick="galeriNav(1)"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <button onclick="closeGaleri()" class="absolute -top-10 right-0 text-white/60 hover:text-white transition">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mt-4 text-center">
                <p id="galeri-counter" class="text-white/50 text-xs mb-3"></p>
                <div id="galeri-thumbs" class="flex justify-center gap-2 flex-wrap"></div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Tooltip Modal ──
        const modalTooltip = document.getElementById('modal-tooltip');
        const kondisiClasses = {
            baru: 'bg-green-100 text-green-700',
            baik: 'bg-blue-100 text-blue-700',
            cukup: 'bg-yellow-100 text-yellow-700',
        };

        function showModalTooltip(el) {
            document.getElementById('modal-tooltip-nama').textContent = el.dataset.nama;
            document.getElementById('modal-tooltip-meta').textContent = el.dataset.kategori + ' · ' + el.dataset.nilai;

            const badge = document.getElementById('modal-tooltip-kondisi');
            badge.textContent = el.dataset.kondisiLabel;
            badge.className = 'inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium mt-1 ' +
                (kondisiClasses[el.dataset.kondisi] || 'bg-gray-100 text-gray-700');

            const rect = el.getBoundingClientRect();
            const tooltipW = 176;
            const tooltipH = 80;
            let left = rect.left;
            let top = rect.top - tooltipH - 8;
            if (top < 8) top = rect.bottom + 8;
            if (left + tooltipW > window.innerWidth - 8) left = window.innerWidth - tooltipW - 8;

            modalTooltip.style.left = left + 'px';
            modalTooltip.style.top = top + 'px';
            modalTooltip.classList.remove('hidden');
        }

        function hideModalTooltip() {
            modalTooltip.classList.add('hidden');
        }

        // ── Filter Panel ──
        function toggleFilter() {
            document.getElementById('filter-section').classList.toggle('hidden');
        }

        // ── Province → City Cascade ──
        document.addEventListener('DOMContentLoaded', function() {
            const filterProvince = document.getElementById('filter-province');
            const filterCity = document.getElementById('filter-city');
            if (!filterProvince) return;
            filterProvince.addEventListener('change', function() {
                filterCity.innerHTML = '<option value="">Semua Kota</option>';
                if (!this.value) return;
                fetch(`{{ route('umkm.getCities') }}?province_code=${this.value}`)
                    .then(r => r.json())
                    .then(data => data.forEach(city => {
                        const opt = document.createElement('option');
                        opt.value = city.code;
                        opt.textContent = city.name;
                        filterCity.appendChild(opt);
                    }));
            });
        });

        // ── Search Bar Toggle ──
        function toggleSearch() {
            const btn = document.getElementById('search-button');
            const form = document.getElementById('search-form');
            const input = document.getElementById('search-input');
            const container = document.getElementById('search-container');
            if (form.classList.contains('hidden')) {
                btn.classList.add('hidden');
                form.classList.remove('hidden');
                container.style.minWidth = '280px';
                setTimeout(() => input.focus(), 50);
            } else {
                if (!'{{ request('q') }}') input.value = '';
                form.classList.add('hidden');
                btn.classList.remove('hidden');
                container.style.minWidth = 'auto';
            }
        }

        // ── Accordion Detail ──
        function toggleDetail(id) {
            const row = document.getElementById('detail-' + id);
            const icon = document.getElementById('icon-' + id);
            if (!row) return;

            const isHidden = row.classList.contains('hidden');

            // Jika sedang ingin membuka, tutup semua detail yang lain dulu
            if (isHidden) {
                document.querySelectorAll('[id^="detail-"]').forEach(el => {
                    if (el.id !== 'detail-' + id) {
                        el.classList.add('hidden');
                        const otherId = el.id.replace('detail-', '');
                        const otherIcon = document.getElementById('icon-' + otherId);
                        if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                    }
                });
            }

            row.classList.toggle('hidden', !isHidden);
            if (icon) icon.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
        }

        // ── Dropdown Aksi ──
        function toggleDropdown(menuId, btn) {
            document.querySelectorAll('.dropdown-menu.open').forEach(m => {
                if (m.id !== menuId) m.classList.remove('open');
            });
            const menu = document.getElementById(menuId);
            if (!menu) return;
            if (menu.classList.contains('open')) {
                menu.classList.remove('open');
                return;
            }
            menu.classList.add('open');
            const rect = btn.getBoundingClientRect();
            const menuW = 208;
            const menuH = menu.scrollHeight;
            let top = rect.bottom + 4;
            let left = rect.right - menuW;
            if (left < 8) left = 8;
            if (top + menuH > window.innerHeight - 8) top = rect.top - menuH - 4;
            menu.style.top = top + 'px';
            menu.style.left = left + 'px';
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-wrapper') && !e.target.closest('.dropdown-menu'))
                document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        });

        window.addEventListener('scroll', function() {
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }, true);

        // ── Confirm Delete ──
        let deleteUuid = null;

        function confirmDelete(uuid, nama) {
            deleteUuid = uuid;
            document.getElementById('modal-umkm-name').textContent = nama;
            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteUuid) submitAction('/umkm/' + deleteUuid, 'DELETE');
        });

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
            }
        });

        // ── Submit Action ──
        function submitAction(action, method, extraFields = {}) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            if (method !== 'POST') {
                const _m = document.createElement('input');
                _m.type = 'hidden';
                _m.name = '_method';
                _m.value = method;
                form.appendChild(_m);
            }
            Object.entries(extraFields).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
        }

        // ── Lightbox Galeri ──
        let galeriPhotos = [],
            galeriIndex = 0;

        function openGaleri(photos, index) {
            galeriPhotos = photos;
            galeriIndex = index;
            renderGaleri();
            const modal = document.getElementById('galeri-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeGaleri() {
            const modal = document.getElementById('galeri-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function galeriNav(dir) {
            galeriIndex = (galeriIndex + dir + galeriPhotos.length) % galeriPhotos.length;
            renderGaleri();
        }

        function renderGaleri() {
            document.getElementById('galeri-img').src = galeriPhotos[galeriIndex];
            document.getElementById('galeri-counter').textContent = (galeriIndex + 1) + ' / ' + galeriPhotos.length;
            const thumbs = document.getElementById('galeri-thumbs');
            thumbs.innerHTML = '';
            galeriPhotos.forEach((src, i) => {
                const img = document.createElement('img');
                img.src = src;
                img.className =
                    'w-12 h-12 rounded-lg object-cover cursor-pointer border-2 transition-all duration-200 ' +
                    (i === galeriIndex ? 'border-white opacity-100' :
                        'border-transparent opacity-40 hover:opacity-70');
                img.onclick = () => {
                    galeriIndex = i;
                    renderGaleri();
                };
                thumbs.appendChild(img);
            });
        }

        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('galeri-modal').classList.contains('hidden')) {
                if (e.key === 'ArrowLeft') galeriNav(-1);
                if (e.key === 'ArrowRight') galeriNav(1);
                if (e.key === 'Escape') closeGaleri();
            }
        });

        // ── Toggle Unit Group ──
        function toggleUnitGroup(className, headerRow) {
            const rows = document.getElementsByClassName(className);
            const chevron = headerRow.querySelector('.unit-chevron');
            const isHidden = rows.length > 0 && rows[0].classList.contains('hidden');

            // Jika sedang ingin membuka unit group, tutup unit group lain dan semua detail UMKM
            if (isHidden) {
                // Tutup semua detail UMKM
                document.querySelectorAll('[id^="detail-"]').forEach(el => {
                    el.classList.add('hidden');
                    const umkmId = el.id.replace('detail-', '');
                    const umkmIcon = document.getElementById('icon-' + umkmId);
                    if (umkmIcon) umkmIcon.style.transform = 'rotate(0deg)';
                });

                // Tutup unit group lain (baris UMKM-nya)
                document.querySelectorAll('tr[class*="unit-group-"]').forEach(row => {
                    if (!row.classList.contains(className)) {
                        row.classList.add('hidden');
                    }
                });

                // Reset semua chevron unit group lain
                document.querySelectorAll('.unit-chevron').forEach(c => {
                    if (c !== chevron) c.style.transform = 'rotate(0deg)';
                });
            }

            for (let i = 0; i < rows.length; i++) {
                if (rows[i].id && rows[i].id.startsWith('detail-')) {
                    rows[i].classList.add('hidden');
                    const umkmIcon = document.getElementById('icon-' + rows[i].id.replace('detail-', ''));
                    if (umkmIcon) umkmIcon.style.transform = 'rotate(0deg)';
                } else {
                    rows[i].classList.toggle('hidden', !isHidden);
                }
            }

            if (chevron) chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    </script>
@endpush

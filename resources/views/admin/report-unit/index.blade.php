@extends('layouts.app')

@section('title', 'Laporan Unit & UMKM Binaan')
@section('page-title', 'Rekapitulasi Unit')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
        }

        .ts-wrapper.single .ts-control {
            background-image: none !important;
        }

        .ts-wrapper.single .ts-control::after {
            content: "";
            display: block;
            width: 10px;
            height: 10px;
            border-right: 2px solid #94a3b8;
            border-bottom: 2px solid #94a3b8;
            transform: rotate(45deg);
            position: absolute;
            right: 12px;
            top: 14px;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-4 sm:space-y-6">

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── HEADER ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Rekapitulasi Unit & UMKM Binaan</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Laporan UMKM binaan yang dikelompokkan berdasarkan unit.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">

                        {{-- Tombol Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg transition-all {{ request()->hasAny(['unit_id', 'unit_status']) ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">Filter</span>
                        </button>

                        {{-- Tombol Download PDF --}}
                        <a href="{{ route('umkm.report.all', request()->query()) }}" target="_blank"
                            class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">Ekspor PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── PANEL FILTER ── --}}
            <div id="filter-section"
                class="{{ request()->hasAny(['unit_id', 'unit_status']) ? '' : 'hidden' }} border-b border-gray-200 transition-all duration-300">
                <div class="px-4 sm:px-6 py-4 bg-gray-50">
                    <form method="GET" action="{{ url()->current() }}">
                        <div class="flex flex-wrap lg:flex-nowrap gap-3 items-end">

                            {{-- Filter Status Unit --}}
                            <div class="w-full sm:w-auto flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Status Unit</label>
                                <select name="unit_status"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Unit</option>
                                    <option value="aktif" {{ request('unit_status') === 'aktif' ? 'selected' : '' }}>Unit
                                        Aktif</option>
                                    <option value="nonaktif" {{ request('unit_status') === 'nonaktif' ? 'selected' : '' }}>
                                        Unit Nonaktif</option>
                                </select>
                            </div>

                            {{-- Filter Unit --}}
                            <div class="w-full sm:w-auto flex-1 min-w-[130px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Unit</label>
                                <select name="unit_id" id="unit-select"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Unit</option>
                                    @foreach ($unitList as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <a href="{{ route('umkm.report.preview') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                    Reset
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── SUMMARY BAR ── --}}
            @php
                $totalUmkm = $umkmList->flatten()->count();
                $totalNilaiModal = $umkmList->flatten()->sum(fn($u) => $u->modalUmkm->sum('nilai_modal'));
            @endphp
            <div
                class="px-4 sm:px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Total UMKM Binaan</span>
                        <span class="text-lg font-semibold text-gray-900">{{ number_format($totalUmkm, 0, ',', '.') }}
                            <span class="text-sm font-medium text-gray-500">Mitra</span></span>
                    </div>
                    <div class="w-px h-8 bg-gray-200"></div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Akumulasi Modal</span>
                        <span class="text-lg font-semibold text-primary">Rp
                            {{ number_format($totalNilaiModal, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Badge filter unit aktif/nonaktif jika ada filter --}}
                    @if (request('unit_status') === 'nonaktif')
                        <span
                            class="flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-xs font-medium">
                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                            Menampilkan Unit Nonaktif
                        </span>
                    @elseif (request('unit_status') === 'aktif')
                        <span
                            class="flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                            Menampilkan Unit Aktif
                        </span>
                    @endif
                    <span
                        class="flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-medium">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        {{ $umkmList->flatten()->where('status', 'aktif')->count() }} Aktif
                    </span>
                    <span
                        class="flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-medium">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                        {{ $umkmList->flatten()->where('status', 'nonaktif')->count() }} Nonaktif
                    </span>
                </div>
            </div>

            @if ($unitList->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Informasi UMKM</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Wilayah</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Modal Bantuan</th>
                                <th
                                    class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Total UMKM</th>
                                <th class="px-6 py-4 w-20"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($unitList as $unit)
                                @php
                                    $unitId = $unit->id;
                                    $group = $umkmList->get($unitId, collect());
                                @endphp

                                {{-- Unit Group Header --}}
                                <tr class="bg-gray-50/80 cursor-pointer border-y border-gray-100 hover:bg-gray-100/50 transition-colors"
                                    onclick="toggleUnitGroup('unit-group-{{ $unitId }}', this)">

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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-semibold text-gray-900 leading-tight">
                                                        {{ $unit->nama_unit ?? 'PUSAT / TANPA UNIT' }}</h3>
                                                    {{-- Badge status unit --}}
                                                    @if ($unit->is_active)
                                                        <span
                                                            class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-green-50 text-green-600 text-[10px] font-medium">
                                                            <span class="w-1 h-1 rounded-full bg-green-500"></span>Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-red-50 text-red-500 text-[10px] font-medium">
                                                            <span class="w-1 h-1 rounded-full bg-red-400"></span>Nonaktif
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $unit->kode_unit ?? '-' }}</p>
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
                                            {{ number_format($group->sum(fn($u) => $u->modalUmkm->sum('nilai_modal')), 0, ',', '.') }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div
                                            class="px-2.5 py-1 rounded-full bg-primary/10 border border-primary/20 inline-flex">
                                            <span class="text-xs font-semibold text-primary">{{ $group->count() }}
                                                UMKM</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                        <a href="{{ route('umkm.report.unit', array_merge(['unitId' => $unitId, 'slug' => Str::slug($unit->nama_unit ?? 'pusat')], request()->query())) }}"
                                            target="_blank"
                                            class="inline-flex items-center p-2 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Download PDF Unit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Group Items --}}
                                @forelse ($group as $item)
                                    <tr class="unit-group-{{ $unitId }} hidden hover:bg-gray-50 transition-colors cursor-pointer"
                                        onclick="toggleDetail('{{ $item->uuid }}')">

                                        {{-- Informasi UMKM --}}
                                        <td class="px-6 py-4">
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
                                                        class="w-9 h-9 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                                @else
                                                    <div
                                                        class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                        <span
                                                            class="text-xs font-semibold text-primary">{{ strtoupper(substr($item->nama_usaha, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex flex-col flex-1 min-w-0">
                                                    <p
                                                        class="text-[10px] font-medium text-primary uppercase tracking-tight mb-0.5">
                                                        {{ $item->kategori?->nama ?? '—' }}</p>
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

                                        {{-- Modal Bantuan --}}
                                        <td class="px-4 py-4 min-w-[220px] overflow-visible">
                                            @include('umkm.partials.modal_usaha_column', ['item' => $item])
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-4 py-4">
                                            @if ($item->status === 'aktif')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>Nonaktif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi (Download PDF) --}}
                                        <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                            <a href="{{ route('umkm.report.single', $item->uuid) }}" target="_blank"
                                                class="inline-flex items-center p-2 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Download PDF UMKM">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>

                                    {{-- Accordion Detail --}}
                                    <tr id="detail-{{ $item->uuid }}"
                                        class="unit-group-{{ $unitId }} hidden bg-gray-50">
                                        <td colspan="5" class="px-4 sm:px-6 py-3">
                                            @include('umkm.partials.detail_accordion', ['item' => $item])
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="unit-group-{{ $unitId }} hidden">
                                        <td colspan="5" class="px-6 py-6 text-center text-gray-400 italic text-sm">
                                            Belum ada data UMKM yang terdaftar di unit ini yang sesuai dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <svg class="mx-auto h-12 sm:h-16 w-12 sm:w-16 text-gray-400 mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-4 sm:mb-6 max-w-xs mx-auto">Silakan sesuaikan filter atau pilih
                        kriteria lain untuk menampilkan laporan unit.</p>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#unit-select', {
                create: false,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },
                placeholder: 'Cari Unit...',
                allowEmptyOption: true,
            });
        });

        function toggleFilter() {
            document.getElementById('filter-section').classList.toggle('hidden');
        }

        function toggleUnitGroup(className, headerRow) {
            const rows = document.getElementsByClassName(className);
            const chevron = headerRow.querySelector('.unit-chevron');
            const isHidden = rows.length > 0 && rows[0].classList.contains('hidden');

            for (let i = 0; i < rows.length; i++) {
                if (rows[i].id && rows[i].id.startsWith('detail-')) {
                    rows[i].classList.add('hidden');
                    const umkmIcon = document.getElementById('icon-' + rows[i].id.replace('detail-', ''));
                    if (umkmIcon) umkmIcon.style.transform = 'rotate(0deg)';
                } else {
                    rows[i].classList.toggle('hidden');
                }
            }

            if (chevron) chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        }

        function toggleDetail(id) {
            const row = document.getElementById('detail-' + id);
            const icon = document.getElementById('icon-' + id);
            if (!row) return;
            const isHidden = row.classList.contains('hidden');
            row.classList.toggle('hidden', !isHidden);
            if (icon) icon.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
        }
    </script>
@endpush

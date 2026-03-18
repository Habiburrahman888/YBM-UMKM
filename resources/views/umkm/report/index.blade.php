@extends('layouts.app')

@section('title', 'Laporan UMKM')
@section('page-title', 'Laporan UMKM')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── HEADER ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Laporan Data UMKM</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Pratinjau laporan sebelum di-download sebagai PDF
                        </p>
                    </div>
                    <div class="flex items-center gap-2">

                        {{-- Tombol Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all {{ request()->hasAny(['tahun', 'tahun_berdiri', 'status', 'kategori_id', 'unit_id']) ? 'bg-blue-100 text-blue-700' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">
                                Filter
                                @if (request()->hasAny(['tahun', 'tahun_berdiri', 'status', 'kategori_id', 'unit_id']))
                                    <span
                                        class="inline-flex items-center justify-center w-4 h-4 text-xs bg-blue-600 text-white rounded-full ml-1">
                                        {{ collect(['tahun', 'tahun_berdiri', 'status', 'kategori_id', 'unit_id'])->filter(fn($k) => request($k))->count() }}
                                    </span>
                                @endif
                            </span>
                        </button>

                        {{-- Tombol Download PDF --}}
                        <a href="{{ route('umkm.report.all') }}?{{ http_build_query(request()->only(['tahun', 'tahun_berdiri', 'status', 'kategori_id', 'unit_id'])) }}"
                            target="_blank"
                            class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">Download PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── PANEL FILTER ── --}}
            <div id="filter-section"
                class="{{ request()->hasAny(['tahun', 'tahun_berdiri', 'status', 'kategori_id', 'unit_id']) ? '' : 'hidden' }} border-b border-gray-200 transition-all duration-300">
                <div class="px-4 sm:px-6 py-4 bg-gray-50">
                    <form method="GET" action="{{ route('umkm.report.preview') }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">

                            {{-- Tahun Bergabung --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tahun Bergabung</label>
                                <select name="tahun"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($tahunBergabungList as $t)
                                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                                            {{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun Berdiri --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tahun Berdiri</label>
                                <select name="tahun_berdiri"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($tahunBerdiriList as $t)
                                        <option value="{{ $t }}"
                                            {{ request('tahun_berdiri') == $t ? 'selected' : '' }}>{{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

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
                                    @foreach ($kategoriList as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}
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
                                                {{ $unit->nama ?? 'Unit #' . $unit->id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Tombol Terapkan & Reset --}}
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                                    Terapkan Filter
                                </button>
                                @if (request()->hasAny(['tahun', 'tahun_berdiri', 'status', 'kategori_id', 'unit_id']))
                                    <a href="{{ route('umkm.report.preview') }}"
                                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                        Reset
                                    </a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- ── SUMMARY BAR ── --}}
            @php
                $totalUmkm = auth()->user()->role === 'admin' ? $umkmList->flatten()->count() : $umkmList->count();
                $totalModal = auth()->user()->role === 'admin' ? $umkmList->flatten()->sum(fn($u) => $u->modalUmkm->sum('nilai_modal')) : $umkmList->sum(fn($u) => $u->modalUmkm->sum('nilai_modal'));
            @endphp
            <div class="px-4 sm:px-6 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <span class="text-xs sm:text-sm text-gray-500">
                    Total: <span class="font-medium text-gray-600">{{ $totalUmkm }}</span> UMKM ·
                    Total Nilai Modal
                </span>
                <span class="text-sm font-bold text-gray-900">
                    Rp {{ number_format($totalModal, 0, ',', '.') }}
                </span>
            </div>

            @if ($totalUmkm > 0)

                {{-- ── TABEL DESKTOP ── --}}
                <div class="hidden md:block overflow-x-auto">
                    @if (auth()->user()->role === 'admin')
                        {{-- Grouped by Unit --}}
                        @foreach ($umkmList as $unitId => $items)
                            @php $unit = $items->first()->unit; @endphp
                            <div class="bg-gray-100/50 px-4 sm:px-6 py-2 font-bold text-gray-900 border-y border-gray-200">
                                Unit: {{ $unit->nama ?? 'Tanpa Unit' }}
                            </div>
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Usaha / Pemilik</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modal Usaha</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Bergabung</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thn. Berdiri</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Modal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Status</th>
                                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">PDF</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($items as $i => $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-400">{{ $i + 1 }}</td>
                                            <td class="px-4 py-4">
                                                <p class="text-xs text-gray-500 mb-0.5">{{ $item->kategori?->nama ?? '—' }}</p>
                                                <p class="text-sm font-medium text-gray-900 leading-snug">{{ $item->nama_usaha }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $item->nama_pemilik }}</p>
                                            </td>
                                            <td class="px-4 py-4 min-w-[200px]">
                                                @if ($item->modalUmkm->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($item->modalUmkm as $m)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[11px] font-medium" title="{{ $m->nama_item }} - Rp {{ number_format($m->nilai_modal, 0, ',', '.') }}">
                                                                {{ $m->nama_item }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                @if ($item->city || $item->province)
                                                    <div class="text-sm font-medium text-gray-700">{{ $item->city?->name ?? '—' }}</div>
                                                    @if ($item->province)
                                                        <div class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide">{{ $item->province->name }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $item->tanggal_bergabung?->translatedFormat('d M Y') ?? '—' }}</td>
                                            <td class="px-4 py-4 text-center text-sm text-gray-700">{{ $item->tahun_berdiri ?? '—' }}</td>
                                            <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">{{ $item->total_modal }}</td>
                                            <td class="px-4 py-4">
                                                @if ($item->status === 'aktif')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>Nonaktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 text-center">
                                                <a href="{{ route('umkm.report.single', $item->uuid) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Usaha / Pemilik</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modal Usaha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wilayah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Bergabung</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thn. Berdiri</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Modal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Status</th>
                                    <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">PDF</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($umkmList as $i => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-400">{{ $i + 1 }}</td>
                                        <td class="px-4 py-4">
                                            <p class="text-xs text-gray-500 mb-0.5">{{ $item->kategori?->nama ?? '—' }}</p>
                                            <p class="text-sm font-medium text-gray-900 leading-snug">{{ $item->nama_usaha }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->nama_pemilik }}</p>
                                        </td>
                                        <td class="px-4 py-4 min-w-[200px]">
                                            @if ($item->modalUmkm->isNotEmpty())
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($item->modalUmkm as $m)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[11px] font-medium" title="{{ $m->nama_item }} - Rp {{ number_format($m->nilai_modal, 0, ',', '.') }}">
                                                            {{ $m->nama_item }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($item->city || $item->province)
                                                <div class="text-sm font-medium text-gray-700">{{ $item->city?->name ?? '—' }}</div>
                                                @if ($item->province)
                                                    <div class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide">{{ $item->province->name }}</div>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $item->tanggal_bergabung?->translatedFormat('d M Y') ?? '—' }}</td>
                                        <td class="px-4 py-4 text-center text-sm text-gray-700">{{ $item->tahun_berdiri ?? '—' }}</td>
                                        <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">{{ $item->total_modal }}</td>
                                        <td class="px-4 py-4">
                                            @if ($item->status === 'aktif')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-center">
                                            <a href="{{ route('umkm.report.single', $item->uuid) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- ── MOBILE CARDS ── --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @php
                        $mobileItems = auth()->user()->role === 'admin' ? $umkmList->flatten() : $umkmList;
                    @endphp
                    @foreach ($mobileItems as $i => $item)
                        <div>
                            {{-- Baris utama (klik untuk accordion) --}}
                            <div class="px-4 py-4 hover:bg-gray-50/60 transition-colors cursor-pointer"
                                onclick="toggleDetail('mob-report-{{ $item->uuid }}')">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-start gap-3 flex-1 min-w-0">

                                        {{-- Chevron --}}
                                        <svg id="icon-mob-report-{{ $item->uuid }}"
                                            class="w-3 h-3 flex-shrink-0 mt-1.5 text-gray-300 transition-transform duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7" />
                                        </svg>

                                        {{-- Avatar / Inisial --}}
                                        @if ($item->logo_umkm)
                                            <img src="{{ Storage::url($item->logo_umkm) }}"
                                                alt="{{ $item->nama_usaha }}"
                                                class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span
                                                    class="text-sm font-bold text-primary">{{ strtoupper(substr($item->nama_usaha, 0, 1)) }}</span>
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            {{-- Nama + Status --}}
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $item->nama_usaha }}</h3>
                                                @if ($item->status === 'aktif')
                                                    <span
                                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-green-50 text-green-600 text-xs font-medium">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Aktif
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

                                    {{-- Tombol PDF --}}
                                    <a href="{{ route('umkm.report.single', $item->uuid) }}" target="_blank"
                                        title="Download PDF" onclick="event.stopPropagation()"
                                        class="flex-shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Accordion Detail --}}
                            <div id="detail-mob-report-{{ $item->uuid }}" class="hidden bg-gray-50 px-4 pb-4">
                                <div class="ml-6 pt-3 border-t border-gray-100 space-y-2.5">
                                    @if ($item->kategori)
                                        <div>
                                            <span class="text-xs text-gray-400">Kategori</span>
                                            <p class="text-sm text-gray-700 mt-0.5">{{ $item->kategori->nama }}</p>
                                        </div>
                                    @endif
                                    @if ($item->tanggal_bergabung)
                                        <div>
                                            <span class="text-xs text-gray-400">Tanggal Bergabung</span>
                                            <p class="text-sm text-gray-700 mt-0.5">
                                                {{ $item->tanggal_bergabung->translatedFormat('d F Y') }}</p>
                                        </div>
                                    @endif
                                    @if ($item->tahun_berdiri)
                                        <div>
                                            <span class="text-xs text-gray-400">Tahun Berdiri</span>
                                            <p class="text-sm text-gray-700 mt-0.5">{{ $item->tahun_berdiri }}</p>
                                        </div>
                                    @endif
                                    @if ($item->modalUmkm->isNotEmpty())
                                        <div>
                                            <span class="text-xs text-gray-400">Modal Usaha</span>
                                            <div class="mt-0.5 space-y-0.5">
                                                @foreach ($item->modalUmkm as $modal)
                                                    <p class="text-xs text-gray-700">{{ $modal->nama_item }}</p>
                                                @endforeach
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 mt-1">{{ $item->total_modal }}</p>
                                        </div>
                                    @endif
                                    @if (auth()->user()->role === 'admin' && $item->unit)
                                        <div>
                                            <span class="text-xs text-gray-400">Unit</span>
                                            <p class="text-sm text-gray-700 mt-0.5">{{ $item->unit->nama }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- ── EMPTY STATE ── --}}
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <svg class="mx-auto h-12 sm:h-16 w-12 sm:w-16 text-gray-400 mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Tidak Ada Data</h3>
                    <p class="text-sm text-gray-500 mb-4 sm:mb-6">
                        @if (request()->hasAny(['tahun', 'tahun_berdiri', 'status', 'kategori_id', 'unit_id']))
                            Tidak ditemukan data UMKM yang sesuai dengan filter yang diterapkan.
                        @else
                            Belum ada data UMKM yang tersedia untuk ditampilkan.
                        @endif
                    </p>
                </div>

            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleFilter() {
            document.getElementById('filter-section').classList.toggle('hidden');
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

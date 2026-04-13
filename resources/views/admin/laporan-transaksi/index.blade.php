@extends('layouts.app')

@section('title', 'Laporan Transaksi Lintas Unit')
@section('page-title', 'Laporan Transaksi')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-wrapper .ts-control {
            border-radius: 0.5rem;
            padding: 0.45rem 0.75rem;
            font-size: 0.875rem;
            border-color: #e5e7eb;
            background-color: white !important;
        }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 2px rgba(59,130,246,0.12);
            border-color: #3b82f6;
        }
        .ts-dropdown {
            border-radius: 0.5rem;
            margin-top: 4px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            border-color: #e5e7eb;
        }
        .stat-card { transition: transform .15s, box-shadow .15s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.08); }
        .chart-container { position: relative; height: 220px; }
        .bar-group { display: flex; align-items: flex-end; gap: 3px; }
        .top-rank-badge {
            width: 22px; height: 22px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
        }
    </style>
@endpush

@section('content')
<div class="space-y-5">

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-semibold text-gray-900">Laporan Transaksi Lintas Unit</h1>
            <p class="text-xs text-gray-500 mt-0.5">Rekap pendapatan seluruh UMKM binaan berdasarkan transaksi selesai</p>
        </div>
        <a href="{{ route('admin.laporan-transaksi.export-pdf', request()->only(['unit_id','umkm_id','dari','sampai'])) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="hidden sm:inline">Export PDF</span>
        </a>
    </div>

    {{-- ── STAT CARDS ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Pendapatan --}}
        <div class="stat-card col-span-2 sm:col-span-1 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-blue-200 uppercase tracking-wider font-medium">Total Pendapatan</p>
                    <p class="text-2xl font-bold mt-1 leading-none">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-blue-200 mt-1.5">Transaksi selesai</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Bulan Ini --}}
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Bulan Ini</p>
                    <p class="text-xl font-bold text-gray-900 mt-1 leading-none">
                        Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1.5">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Pesanan --}}
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Pesanan</p>
                    <p class="text-xl font-bold text-gray-900 mt-1 leading-none">
                        {{ number_format($totalPesanan, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1.5">Transaksi selesai</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- UMKM Terlibat --}}
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">UMKM Aktif</p>
                    <p class="text-xl font-bold text-gray-900 mt-1 leading-none">{{ $umkmTerlibat }}</p>
                    <p class="text-xs text-gray-400 mt-1.5">Dengan transaksi selesai</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ── CHART + TOP UMKM ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Grafik Tren 6 Bulan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-card p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Tren Pendapatan (6 Bulan Terakhir)</h3>
            <div class="chart-container">
                <canvas id="trenChart"></canvas>
            </div>
        </div>

        {{-- Top 5 UMKM --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-card p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Top 5 UMKM</h3>
            @php
                $maxPenjualan = $topUmkm->max('total_penjualan') ?: 1;
            @endphp
            <div class="space-y-3">
                @forelse($topUmkm as $i => $top)
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="top-rank-badge
                                {{ $i === 0 ? 'bg-amber-100 text-amber-700' : ($i === 1 ? 'bg-gray-100 text-gray-600' : ($i === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400')) }}">
                                {{ $i + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate">{{ $top->nama_usaha }}</p>
                                <p class="text-[10px] text-gray-400">{{ $top->unit?->nama_unit ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-bold text-blue-700 whitespace-nowrap">
                                Rp {{ number_format($top->total_penjualan, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full transition-all"
                                style="width: {{ round(($top->total_penjualan / $maxPenjualan) * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada data transaksi</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── TABEL REKAP ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-card overflow-hidden">

        {{-- Header + Filter --}}
        <div class="px-5 py-4 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Rekap Per UMKM</h2>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $rekapUmkm->total() }} UMKM dengan transaksi selesai
                    </p>
                </div>
                <button type="button" onclick="toggleFilter()"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-xl transition-all
                    {{ request()->hasAny(['unit_id','umkm_id','dari','sampai'])
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter
                    @if(request()->hasAny(['unit_id','umkm_id','dari','sampai']))
                        <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center">
                            {{ collect(['unit_id','umkm_id','dari','sampai'])->filter(fn($k) => request($k))->count() }}
                        </span>
                    @endif
                </button>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['unit_id','umkm_id','dari','sampai']) ? '' : 'hidden' }} mt-4">
                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                    <form method="GET" action="{{ route('admin.laporan-transaksi.index') }}"
                          class="flex flex-wrap items-end gap-3">

                        {{-- Filter Unit --}}
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Unit</label>
                            <select name="unit_id" id="unit-select"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="">Semua Unit</option>
                                @foreach($unitList as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter UMKM --}}
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">UMKM</label>
                            <select name="umkm_id" id="umkm-select"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="">Semua UMKM</option>
                                @foreach($umkmList as $umkm)
                                    <option value="{{ $umkm->id }}" {{ request('umkm_id') == $umkm->id ? 'selected' : '' }}>
                                        {{ $umkm->nama_usaha }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dari Tanggal --}}
                        <div class="w-full sm:w-40">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Dari Tanggal</label>
                            <input type="date" name="dari" value="{{ request('dari') }}"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        {{-- Sampai Tanggal --}}
                        <div class="w-full sm:w-40">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1">Sampai Tanggal</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm">
                                Terapkan
                            </button>
                            @if(request()->hasAny(['unit_id','umkm_id','dari','sampai']))
                                <a href="{{ route('admin.laporan-transaksi.index') }}"
                                    class="inline-flex items-center justify-center p-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-400 rounded-lg transition-colors shadow-sm"
                                    title="Reset Filter">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Filter Active Badges --}}
        @if(request()->hasAny(['unit_id','umkm_id','dari','sampai']))
            <div class="px-5 py-2.5 bg-blue-50/50 border-b border-blue-100 flex flex-wrap gap-2 text-xs">
                @if(request('unit_id'))
                    @php $selUnit = $unitList->firstWhere('id', request('unit_id')); @endphp
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                        Unit: {{ $selUnit?->nama_unit ?? '-' }}
                    </span>
                @endif
                @if(request('dari'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                        Dari: {{ \Carbon\Carbon::parse(request('dari'))->translatedFormat('d M Y') }}
                    </span>
                @endif
                @if(request('sampai'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                        Sampai: {{ \Carbon\Carbon::parse(request('sampai'))->translatedFormat('d M Y') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-10">No</th>
                        <th class="px-5 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">UMKM</th>
                        <th class="px-5 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Unit</th>
                        <th class="px-5 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Transaksi</th>
                        <th class="px-5 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Total Penjualan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($rekapUmkm as $i => $row)
                        <tr class="hover:bg-gray-50/60 transition-colors">

                            {{-- No --}}
                            <td class="px-5 py-4 text-sm text-gray-400">
                                {{ $rekapUmkm->firstItem() + $i }}
                            </td>

                            {{-- UMKM Info --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($row->logo_umkm)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($row->logo_umkm) }}"
                                             alt="{{ $row->nama_usaha }}"
                                             class="w-9 h-9 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-bold text-blue-600">
                                                {{ strtoupper(substr($row->nama_usaha, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $row->nama_usaha }}</p>
                                        <p class="text-xs text-gray-400">{{ $row->nama_pemilik }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Unit --}}
                            <td class="px-5 py-4">
                                @if($row->unit)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">
                                        {{ $row->unit->nama_unit }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- Kategori --}}
                            <td class="px-5 py-4">
                                @if($row->kategori)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-violet-50 text-violet-600 text-xs font-medium">
                                        {{ $row->kategori->nama }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- Jumlah Transaksi --}}
                            <td class="px-5 py-4 text-right">
                                <span class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                    {{ number_format($row->jumlah_transaksi, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Total Penjualan --}}
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-bold text-gray-900">
                                    Rp {{ number_format($row->total_penjualan ?? 0, 0, ',', '.') }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Belum Ada Data Transaksi</p>
                                        <p class="text-xs text-gray-400 mt-1">Coba ubah filter atau tunggu hingga ada pesanan yang selesai.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- Total Footer --}}
                @if($rekapUmkm->isNotEmpty())
                    <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                        <tr>
                            <td colspan="4" class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Total Halaman Ini
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-xs font-semibold text-gray-700">
                                    {{ number_format($rekapUmkm->sum('jumlah_transaksi'), 0, ',', '.') }} transaksi
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-sm font-bold text-blue-700">
                                    Rp {{ number_format($rekapUmkm->sum('total_penjualan'), 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination --}}
        @if($rekapUmkm->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $rekapUmkm->links() }}
            </div>
        @endif

    </div>{{-- /.card --}}

</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        // ── Filter toggle ──────────────────────────────────────────────────
        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // ── Tom Select ────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#unit-select', {
                create: false, sortField: { field: 'text', direction: 'asc' },
                placeholder: 'Cari unit...', allowEmptyOption: true,
            });
            new TomSelect('#umkm-select', {
                create: false, sortField: { field: 'text', direction: 'asc' },
                placeholder: 'Cari UMKM...', allowEmptyOption: true,
            });
        });

        // ── Grafik Tren ───────────────────────────────────────────────────
        const trenLabels = @json($grafikTren['labels']);
        const trenData   = @json($grafikTren['data']);

        const ctx = document.getElementById('trenChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0, 'rgba(59,130,246,0.25)');
        gradient.addColorStop(1, 'rgba(59,130,246,0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trenLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: trenData,
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#94a3b8',
                        bodyColor: '#f8fafc',
                        padding: 10,
                        callbacks: {
                            label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#94a3b8' },
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 11 }, color: '#94a3b8',
                            callback: v => 'Rp ' + (v >= 1000000
                                ? (v/1000000).toFixed(1) + 'jt'
                                : v.toLocaleString('id-ID')),
                        }
                    }
                }
            }
        });
    </script>
@endpush

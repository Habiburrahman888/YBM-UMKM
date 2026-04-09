@extends('layouts.app')

@section('title', 'Laporan Transaksi UMKM Binaan')
@section('page-title', 'Laporan Transaksi')

@section('content')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-wrapper .ts-control {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border-color: #e5e7eb;
            background-color: white !important;
        }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }
        .ts-dropdown {
            border-radius: 0.5rem;
            margin-top: 4px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #e5e7eb;
        }
    </style>
@endpush


    {{-- PAGE HEADER --}}
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-gray-900">Laporan Transaksi UMKM Binaan</h1>
        <p class="text-xs text-gray-500 mt-0.5">Pantau semua pesanan UMKM binaan unit Anda</p>
    </div>


    {{-- DATA TABLE CARD --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">

        {{-- Card Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Transaksi</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $pesanans->total() }} transaksi</p>
                </div>
                <div class="flex gap-2">
                    {{-- Filter Toggle --}}
                    <button type="button" onclick="toggleFilter()"
                        class="group inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg transition-all
                        {{ request()->hasAny(['umkm_id', 'status', 'dari', 'sampai'])
                            ? 'bg-blue-100 text-blue-700 hover:bg-blue-200'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">
                            Filter
                            @if (request()->hasAny(['umkm_id', 'status', 'dari', 'sampai']))
                                <span
                                    class="inline-flex items-center justify-center w-4 h-4 text-xs bg-blue-600 text-white rounded-full ml-1">
                                    {{ collect(['umkm_id', 'dari', 'sampai'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
                        </span>
                    </button>

                    {{-- Export PDF --}}
                    <a href="{{ route('unit.laporan-transaksi.export-pdf', request()->only(['umkm_id', 'dari', 'sampai'])) }}"
                        target="_blank"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <span class="hidden sm:inline">Export PDF</span>
                        <span class="sm:hidden">PDF</span>
                    </a>
                </div>
            </div>

            {{-- Filter Panel (hidden by default) --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['umkm_id', 'dari', 'sampai']) ? '' : 'hidden' }} mt-4">
                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                    <form method="GET" action="{{ route('unit.laporan-transaksi.index') }}" class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1 ml-0.5">UMKM Binaan</label>
                            <select name="umkm_id" id="umkm_select"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Semua UMKM</option>
                                @foreach ($umkmList as $u)
                                    <option value="{{ $u->id }}"
                                        {{ request('umkm_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nama_usaha }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-44">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1 ml-0.5">Dari Tanggal</label>
                            <input type="date" name="dari" value="{{ request('dari') }}"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        <div class="w-full sm:w-44">
                            <label class="block text-[11px] font-medium text-gray-400 mb-1 ml-0.5">Sampai Tanggal</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="block w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm">
                                Terapkan Filter
                            </button>
                            @if (request()->hasAny(['umkm_id', 'status', 'dari', 'sampai']))
                                <a href="{{ route('unit.laporan-transaksi.index') }}"
                                    class="inline-flex items-center justify-center p-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-400 rounded-lg transition-colors shadow-sm"
                                    title="Reset Filter">
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

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama UMKM
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            Penjualan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pesanans as $i => $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pesanans->firstItem() + $i }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $p->nama_usaha }}</div>
                                <div class="text-xs text-gray-500">{{ $p->nama_pemilik }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($p->total_penjualan ?? 0, 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <p class="text-base font-medium text-gray-900 mb-1">Belum Ada Data Transaksi</p>
                                <p class="text-sm text-gray-500">Coba ubah filter atau tambahkan transaksi baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pesanans->hasPages())
            <div class="px-4 sm:px-6 py-3 border-t border-gray-200">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#umkm_select', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Cari UMKM..."
            });
        });

        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }
    </script>
@endpush

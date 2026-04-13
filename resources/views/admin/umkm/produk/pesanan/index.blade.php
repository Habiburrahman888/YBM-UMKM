@extends('layouts.app')

@section('title', 'Daftar Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── HEADER ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Pesanan Masuk</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Kelola dan pantau pesanan dari pelanggan Anda.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">

                        {{-- Tombol Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg transition-all {{ request()->hasAny(['status', 'dari', 'sampai']) ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="ml-2 hidden sm:inline-block">Filter</span>
                        </button>

                        {{-- Tombol Ekspor PDF --}}
                        <a href="{{ route('umkm.pesanan.export-pdf', request()->only(['status', 'dari', 'sampai'])) }}"
                            target="_blank"
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
                class="{{ request()->hasAny(['status', 'dari', 'sampai']) ? '' : 'hidden' }} border-b border-gray-200 transition-all duration-300">
                <div class="px-4 sm:px-6 py-4 bg-gray-50">
                    <form method="GET" action="{{ route('umkm.pesanan.index') }}">
                        <div class="flex flex-wrap lg:flex-nowrap gap-3 items-end">

                            {{-- Filter Status --}}
                            <div class="w-full sm:w-auto flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Status Pesanan</label>
                                <select name="status"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Sedang Diantar</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            {{-- Filter Dari Tanggal --}}
                            <div class="w-full sm:w-auto flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                                <input type="date" name="dari" value="{{ request('dari') }}"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>

                            {{-- Filter Sampai Tanggal --}}
                            <div class="w-full sm:w-auto flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                                <input type="date" name="sampai" value="{{ request('sampai') }}"
                                    class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <a href="{{ route('umkm.pesanan.index') }}"
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
                $totalPesanan   = $pesanans->total();
                $totalPendapatan = $pesanans->getCollection()->sum('total_harga');
            @endphp
            <div class="px-4 sm:px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Total Pesanan</span>
                        <span class="text-lg font-semibold text-gray-900">
                            {{ number_format($totalPesanan, 0, ',', '.') }}
                            <span class="text-sm font-medium text-gray-500">Pesanan</span>
                        </span>
                    </div>
                    <div class="w-px h-8 bg-gray-200"></div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Total Pendapatan</span>
                        <span class="text-lg font-semibold text-primary">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    @php
                        $pesananCollection = $pesanans->getCollection();
                    @endphp
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-medium border border-amber-100">
                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                        {{ $pesananCollection->where('status','pending')->count() }} Pending
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium border border-blue-100">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                        {{ $pesananCollection->where('status','diproses')->count() }} Diproses
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-violet-50 text-violet-600 rounded-full text-xs font-medium border border-violet-100">
                        <span class="w-1.5 h-1.5 bg-violet-500 rounded-full"></span>
                        {{ $pesananCollection->where('status','dikirim')->count() }} Diantar
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-medium border border-green-100">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        {{ $pesananCollection->where('status','selesai')->count() }} Selesai
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-500 rounded-full text-xs font-medium border border-red-100">
                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                        {{ $pesananCollection->where('status','dibatalkan')->count() }} Dibatalkan
                    </span>
                </div>
            </div>

            {{-- ── TABEL PESANAN ── --}}
            @if ($pesanans->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($pesanans as $pesanan)
                                @php
                                    $statusLabel = $pesanan->status == 'dikirim' ? 'Sedang Diantar' : ucfirst($pesanan->status);
                                    $badgeClass = match ($pesanan->status) {
                                        'pending'    => 'bg-amber-50 text-amber-600 border border-amber-100',
                                        'diproses'   => 'bg-blue-50 text-blue-600 border border-blue-100',
                                        'dikirim'    => 'bg-violet-50 text-violet-600 border border-violet-100',
                                        'selesai'    => 'bg-green-50 text-green-600 border border-green-100',
                                        default      => 'bg-red-50 text-red-500 border border-red-100',
                                    };
                                    $dotClass = match ($pesanan->status) {
                                        'pending'    => 'bg-amber-400',
                                        'diproses'   => 'bg-blue-500 animate-pulse',
                                        'dikirim'    => 'bg-violet-500',
                                        'selesai'    => 'bg-green-500',
                                        default      => 'bg-red-400',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
 
                                    {{-- Pelanggan --}}
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-900">{{ $pesanan->nama_pembeli }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $pesanan->telepon_pembeli }}</p>
                                        <p class="text-[11px] text-gray-300 mt-0.5">{{ $pesanan->created_at->format('d M Y, H:i') }}</p>
                                    </td>
 
                                    {{-- Produk --}}
                                    <td class="px-4 py-4">
                                        @if ($pesanan->items->count() > 0)
                                            <p class="text-sm font-semibold text-gray-800">{{ $pesanan->items->count() }} Produk</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Total Qty: {{ $pesanan->items->sum('jumlah') }}</p>
                                        @else
                                            <p class="text-sm font-semibold text-gray-800">{{ $pesanan->produk->nama_produk }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Qty: {{ $pesanan->jumlah }}</p>
                                        @endif
                                    </td>
 
                                    {{-- Total --}}
                                    <td class="px-4 py-4">
                                        <span class="text-sm font-semibold text-emerald-600">
                                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </span>
                                    </td>
 
                                    {{-- Status --}}
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $dotClass }}"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
 
                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('umkm.pesanan.show', $pesanan->uuid) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 text-gray-600 rounded-lg text-xs font-medium transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail & Update
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($pesanans->hasPages())
                    <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $pesanans->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-sm text-gray-500 max-w-xs mx-auto">Pesanan dari pelanggan akan muncul di sini. Coba sesuaikan filter jika sedang aktif.</p>
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
    </script>
@endpush
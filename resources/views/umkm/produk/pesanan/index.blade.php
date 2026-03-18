@extends('layouts.app')

@section('title', 'Daftar Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-3 mb-1">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Pesanan Masuk</h1>
            </div>
            <p class="text-sm text-gray-600">Kelola pesanan dari pelanggan untuk usaha Anda.</p>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <form method="GET" action="{{ route('umkm.pesanan.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Status</label>
                <select name="status"
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}"
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Sampai
                    Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('umkm.pesanan.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
            {{-- PDF Export Button with same params --}}
            <div class="ml-auto">
                <a href="{{ route('umkm.pesanan.export-pdf', request()->only(['status', 'dari', 'sampai'])) }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pesanans as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $p->nama_pembeli }}</div>
                                <div class="text-xs text-gray-500">{{ $p->telepon_pembeli }}</div>
                                <div class="text-[10px] text-gray-400 mt-1">{{ $p->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->items->count() > 0)
                                    <div class="text-sm text-gray-700">{{ $p->items->count() }} Produk</div>
                                    <div class="text-xs text-gray-400">Total Qty: {{ $p->items->sum('jumlah') }}</div>
                                @else
                                    <div class="text-sm text-gray-700">{{ $p->produk->nama_produk }}</div>
                                    <div class="text-xs text-gray-400">Qty: {{ $p->jumlah }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-green-700">Rp
                                    {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-[10px] font-bold rounded-full
                            @if ($p->status === 'pending') bg-amber-100 text-amber-700
                            @elseif($p->status === 'diproses') bg-blue-100 text-blue-700
                            @elseif($p->status === 'selesai') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                                    {{ strtoupper($p->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('umkm.pesanan.show', $p->uuid) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-eye mr-1.5"></i> Detail & Update Status
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada pesanan masuk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pesanans->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
@endsection

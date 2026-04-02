@extends('layouts.app')

@section('title', 'Daftar Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')

@section('content')

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 leading-tight">Pesanan Masuk</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola dan pantau pesanan dari pelanggan Anda</p>
    </div>

    {{-- Filter & Export Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('umkm.pesanan.index') }}">
            <div class="flex flex-wrap items-end gap-4">

                {{-- Status --}}
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-widest">Status</label>
                    <div class="relative">
                        <select name="status"
                            class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-800 text-sm font-medium rounded-xl px-4 py-2.5 pr-9 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Sedang Diantar
                            </option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                            </option>
                        </select>
                        <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Dari Tanggal --}}
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-widest">Dari
                        Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-medium rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                {{-- Sampai Tanggal --}}
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-widest">Sampai
                        Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-medium rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 active:scale-95 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('umkm.pesanan.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 active:scale-95 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </a>
                </div>

                {{-- Export PDF --}}
                <div class="ml-auto">
                    <a href="{{ route('umkm.pesanan.export-pdf', request()->only(['status', 'dari', 'sampai'])) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 border border-red-100 text-sm font-semibold rounded-xl hover:bg-red-100 active:scale-95 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd" />
                        </svg>
                        Export PDF
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50/70">
                            Pelanggan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50/70">Produk
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50/70">Total
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50/70">Status
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50/70 text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pesanans as $p)
                        <tr class="hover:bg-blue-50/30 transition-colors group">

                            {{-- Pelanggan --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 text-sm">{{ $p->nama_pembeli }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $p->telepon_pembeli }}</div>
                                <div class="text-[11px] text-gray-300 mt-0.5">{{ $p->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>

                            {{-- Produk --}}
                            <td class="px-6 py-4">
                                @if ($p->items->count() > 0)
                                    <div class="text-sm font-semibold text-gray-800">{{ $p->items->count() }} Produk</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Total Qty: {{ $p->items->sum('jumlah') }}
                                    </div>
                                @else
                                    <div class="text-sm font-semibold text-gray-800">{{ $p->produk->nama_produk }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Qty: {{ $p->jumlah }}</div>
                                @endif
                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-emerald-600">
                                    Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @php
                                    $statusLabel = $p->status == 'dikirim' ? 'Sedang Diantar' : ucfirst($p->status);
                                    $statusClass = match ($p->status) {
                                        'pending' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                        'diproses' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                        'dikirim' => 'bg-violet-50 text-violet-600 border border-violet-100',
                                        'selesai' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                        default => 'bg-red-50 text-red-500 border border-red-100',
                                    };
                                    $dotClass = match ($p->status) {
                                        'pending' => 'bg-amber-400',
                                        'diproses' => 'bg-blue-500',
                                        'dikirim' => 'bg-violet-500',
                                        'selesai' => 'bg-emerald-500',
                                        default => 'bg-red-400',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $dotClass }} {{ $p->status === 'diproses' ? 'animate-pulse' : '' }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('umkm.pesanan.show', $p->uuid) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl text-xs font-semibold transition-all group-hover:border-blue-200 shadow-sm">
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
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 font-semibold text-sm">Belum ada pesanan</p>
                                        <p class="text-gray-400 text-xs mt-1">Pesanan dari pelanggan akan muncul di sini
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($pesanans->hasPages())
            <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/40">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('umkm.pesanan.index') }}"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer & Order Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    Informasi Pelanggan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-800 uppercase font-bold tracking-wider mb-1">Nama Pembeli</p>
                        <p class="text-sm text-gray-600">{{ $pesanan->nama_pembeli }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-800 uppercase font-bold tracking-wider mb-1">Nomor Telepon</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesanan->telepon_pembeli) }}"
                            target="_blank" class="text-sm text-gray-600 hover:underline">
                            {{ $pesanan->telepon_pembeli }} <i class="fab fa-whatsapp ml-1"></i>
                        </a>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-800 uppercase font-bold tracking-wider mb-1">Alamat Pengiriman</p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $pesanan->alamat_pembeli }}</p>
                    </div>
                    @if ($pesanan->catatan)
                        <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg italic">
                            <p class="text-xs text-gray-400 font-bold mb-1">Catatan:</p>
                            <p class="text-sm text-gray-600">"{{ $pesanan->catatan }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Detail -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    Detail Produk
                </h2>
                <div class="space-y-6">
                    @if ($pesanan->items->count() > 0)
                        @foreach ($pesanan->items as $item)
                            <div class="flex items-center space-x-4">
                                @php
                                    $fotos = $item->produk->foto_produk;
                                    $foto = is_array($fotos) ? $fotos[0] ?? null : null;
                                @endphp
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                    @if ($foto)
                                        <img src="{{ Storage::url($foto) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-2xl">🛍️</div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900">{{ $item->produk->nama_produk }}</h3>
                                    <p class="text-sm text-gray-500">Jumlah: {{ $item->jumlah }} unit</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-800 font-bold">Harga Satuan</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-800 font-bold mt-1">Subtotal</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center space-x-4">
                            @php
                                $fotos = $pesanan->produk->foto_produk;
                                $foto = is_array($fotos) ? $fotos[0] ?? null : null;
                            @endphp
                            <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                @if ($foto)
                                    <img src="{{ Storage::url($foto) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-2xl">🛍️</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900">{{ $pesanan->produk->nama_produk }}</h3>
                                <p class="text-sm text-gray-500">Jumlah: {{ $pesanan->jumlah }} unit</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Harga Satuan</p>
                                <p class="text-sm font-semibold">Rp
                                    {{ number_format($pesanan->produk->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-base font-bold text-gray-900">Total Pembayaran</span>
                    <span class="text-xl font-black text-gray-900">Rp
                        {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Sidebar Info (Bukti Transfer & Action) -->
        <div class="space-y-6">
            <!-- Bukti Transfer -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    Bukti Transfer
                </h2>
                @if ($pesanan->bukti_transfer)
                    <a href="{{ Storage::url($pesanan->bukti_transfer) }}" target="_blank"
                        class="block group relative rounded-xl overflow-hidden border border-gray-200">
                        <img src="{{ Storage::url($pesanan->bukti_transfer) }}"
                            class="w-full h-auto group-hover:scale-105 transition-transform duration-300">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                            <span class="text-white text-xs font-bold"><i class="fas fa-search-plus mr-1"></i>
                                Perbesar</span>
                        </div>
                    </a>
                @else
                    <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <i class="fas fa-image text-gray-300 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-400">Bukti transfer tidak tersedia</p>
                    </div>
                @endif
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    Update Status
                </h2>
                @if ($pesanan->status === 'selesai')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                        <p class="text-green-800 font-bold">Pesanan Selesai</p>
                        <p class="text-green-600 text-xs">Status pesanan ini tidak dapat diubah lagi.</p>
                    </div>
                @elseif ($pesanan->status === 'dibatalkan')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <i class="fas fa-times-circle text-red-500 text-3xl mb-2"></i>
                        <p class="text-red-800 font-bold">Pesanan Dibatalkan</p>
                        <p class="text-red-600 text-xs">Status pesanan ini tidak dapat diubah lagi.</p>
                    </div>
                @else
                    <form action="{{ route('umkm.pesanan.update-status', $pesanan->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <select name="status"
                                    class="w-full p-3 rounded-lg border border-gray-200 text-sm font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                                    @if ($pesanan->status === 'pending')
                                        <option value="pending" selected>PENDING</option>
                                        <option value="diproses">DIPROSES</option>
                                        <option value="dikirim">SEDANG DIANTAR</option>
                                        <option value="selesai">SELESAI</option>
                                        <option value="dibatalkan">DIBATALKAN</option>
                                    @elseif ($pesanan->status === 'diproses')
                                        <option value="diproses" selected>DIPROSES</option>
                                        <option value="dikirim">SEDANG DIANTAR</option>
                                        <option value="selesai">SELESAI</option>
                                        <option value="dibatalkan">DIBATALKAN</option>
                                    @elseif ($pesanan->status === 'dikirim')
                                        <option value="dikirim" selected>SEDANG DIANTAR</option>
                                        <option value="selesai">SELESAI</option>
                                        <option value="dibatalkan">DIBATALKAN</option>
                                    @endif

                                </select>
                            </div>
                            <button type="submit"
                                class="w-full py-3 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                                Update Status
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

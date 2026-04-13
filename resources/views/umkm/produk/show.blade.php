@extends('layouts.app')

@section('title', 'Produk Saya')
@section('page-title', 'Detail Produk')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Produk Saya</h1>
                <p class="text-gray-600 mt-1">Informasi produk UMKM</p>
            </div>
            @if ($produk)
            <div class="flex space-x-2 mt-4 sm:mt-0">
                <a href="{{ route('umkm.produk.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('umkm.produk.edit', $produk->uuid) }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Produk
                </a>
            </div>
            @endif
        </div>

        @if ($produk)

        {{-- Informasi Produk --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                Informasi Produk
            </h2>

            {{-- Data Utama --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">

                {{-- Nama Produk --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Nama Produk
                    </label>
                    <p class="text-gray-900 text-base">{{ $produk->nama_produk }}</p>
                </div>

                {{-- Harga --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Harga
                    </label>
                    <p class="text-gray-900 text-base font-semibold">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }} <span class="text-gray-500 font-normal">/ {{ $produk->kategori_satuan }}</span>
                    </p>
                </div>

                {{-- Nama UMKM --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Nama UMKM
                    </label>
                    <p class="text-gray-900 text-base">{{ $produk->umkm->nama_usaha ?? '-' }}</p>
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Stok
                    </label>
                    <p class="text-gray-900 text-base">
                        @if ($produk->stok !== null)
                            {{ number_format($produk->stok, 0, ',', '.') }} <span class="text-gray-500 font-normal">Tersedia</span>
                        @else
                            <span class="text-gray-400 italic">Tidak dibatasi</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Deskripsi Produk --}}
            <div class="mb-8">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    Deskripsi Produk
                </label>
                <p class="text-gray-900 text-base leading-relaxed">
                    {{ trim($produk->deskripsi_produk) }}
                </p>
            </div>

            {{-- Terakhir Diperbarui --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    Terakhir Diperbarui
                </label>
                <p class="text-gray-500 text-sm">
                    {{ $produk->updated_at->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>
        </div>

        {{-- Foto Produk --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                Foto Produk
            </h2>

            @if (!empty($produk->foto_produk) && count($produk->foto_produk) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($produk->foto_produk as $index => $foto)
                <div class="relative group cursor-pointer"
                    onclick="openLightbox('{{ asset('storage/' . $foto) }}')">
                    <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto Produk {{ $index + 1 }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    {{-- Overlay zoom icon --}}
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                    </div>
                    {{-- Nomor foto --}}
                    <span
                        class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs font-medium px-1.5 py-0.5 rounded">
                        {{ $index + 1 }}
                    </span>
                </div>
                @endforeach
            </div>
            <p class="mt-3 text-xs text-gray-400">{{ count($produk->foto_produk) }} foto tersimpan &bull; Klik
                foto untuk memperbesar</p>
            @else
            <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-lg">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-400 text-sm">Belum ada foto produk</p>
                <a href="{{ route('umkm.produk.edit', $produk->uuid) }}"
                    class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-800 font-medium">
                    Upload foto sekarang →
                </a>
            </div>
            @endif
        </div>
        @else
        {{-- Empty State --}}
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Produk</h3>
            <p class="text-gray-400 text-sm">Produk UMKM Anda belum terdaftar di sistem.</p>
            <p class="text-gray-400 text-sm mt-1">Silakan hubungi admin untuk mendaftarkan produk Anda.</p>
        </div>
        @endif

    </div>
</div>

{{-- Lightbox Modal --}}
<div id="lightbox" class="fixed inset-0 z-50 bg-black bg-opacity-80 hidden items-center justify-center p-4"
    onclick="closeLightbox()">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <img id="lightbox-img" src="" alt="Preview Foto"
            class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
        <button onclick="closeLightbox()"
            class="absolute -top-3 -right-3 bg-white rounded-full p-1.5 shadow-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ── Lightbox ──────────────────────────────────────────────────────────
    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        const lb = document.getElementById('lightbox');
        lb.classList.remove('hidden');
        lb.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lb = document.getElementById('lightbox');
        lb.classList.add('hidden');
        lb.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Tutup lightbox dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endpush
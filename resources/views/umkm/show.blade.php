@extends('layouts.app')

@section('title', 'Detail UMKM - ' . $umkm->nama_usaha)
@section('page-title', 'Detail UMKM')

@push('styles')
    <style>
        .umkm-header-bg {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ── BREADCRUMBS ── --}}
        <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                @foreach ($breadcrumbs as $index => $crumb)
                    <li class="inline-flex items-center">
                        @if ($index > 0)
                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        @endif
                        <a href="{{ $crumb['url'] }}"
                            class="inline-flex items-center font-medium {{ $loop->last ? 'text-gray-500 cursor-default' : 'text-gray-700 hover:text-primary' }}">
                            {{ $crumb['name'] }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>

        {{-- ── HEADER SECTION ── --}}
        <div class="relative rounded-3xl overflow-hidden shadow-xl mb-8">
            <div class="umkm-header-bg h-48 sm:h-64 w-full"></div>
            <div class="absolute inset-0 bg-black/10"></div>

            <div
                class="relative px-6 pb-6 -mt-16 sm:-mt-20 flex flex-col sm:flex-row items-end sm:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="relative">
                        @if ($umkm->logo_umkm)
                            <img src="{{ asset('storage/' . $umkm->logo_umkm) }}" alt="{{ $umkm->nama_usaha }}"
                                class="w-32 h-32 sm:w-40 h-40 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                        @else
                            <div
                                class="w-32 h-32 sm:w-40 h-40 rounded-2xl bg-indigo-100 border-4 border-white shadow-lg flex items-center justify-center">
                                <span
                                    class="text-4xl font-bold text-indigo-600">{{ strtoupper(substr($umkm->nama_usaha, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="absolute -bottom-2 -right-2">
                            @if ($umkm->status === 'aktif')
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-full border-4 border-white shadow-sm"
                                    title="Aktif">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            @else
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-full border-4 border-white shadow-sm"
                                    title="Nonaktif">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4 sm:mb-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $umkm->nama_usaha }}</h1>
                            <span
                                class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full tracking-wider uppercase">{{ $umkm->kode_umkm }}</span>
                        </div>
                        <p class="text-lg text-gray-600 font-medium mt-1">{{ $umkm->nama_pemilik }}</p>
                        <div class="flex items-center gap-2 mt-3 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $umkm->lokasi_singkat }}</span>
                            @if ($umkm->kategori)
                                <span class="mx-2">•</span>
                                <span class="text-indigo-600 font-semibold">{{ $umkm->kategori->nama }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($permissions['canEdit'])
                        <a href="{{ route('umkm.edit', $umkm) }}"
                            class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Profil
                        </a>
                    @endif
                    <a href="{{ route('umkm.report.single', $umkm) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4">
                            </path>
                        </svg>
                        Cetak Laporan
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ── LEFT COLUMN: MAIN INFO ── --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Deskripsi / Tentang --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tentang Usaha
                    </h2>
                    <p class="text-gray-600 leading-relaxed italic whitespace-pre-line">
                        {{ $umkm->tentang ?: 'Pemilik belum menambahkan deskripsi untuk usaha ini.' }}
                    </p>

                    @if ($umkm->tahun_berdiri)
                        <div class="mt-6 flex items-center gap-4 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                            <div class="p-3 bg-white rounded-xl shadow-sm">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-indigo-500 font-bold uppercase tracking-wider">Berdiri Sejak</p>
                                <p class="text-lg font-bold text-indigo-900">{{ $umkm->tahun_berdiri }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Produk Unggulan --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Produk Unggulan
                        </h2>
                        <span
                            class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-full">{{ $umkm->produkUmkm->count() }}
                            Produk</span>
                    </div>

                    @forelse ($umkm->produkUmkm->take(5) as $produk)
                        <div
                            class="flex items-start gap-4 p-4 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <div class="w-20 h-20 rounded-xl overflow-hidden shadow-sm flex-shrink-0">
                                @if (!empty($produk->foto_produk) && isset($produk->foto_produk[0]))
                                    <img src="{{ asset('storage/' . $produk->foto_produk[0]) }}"
                                        alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg">{{ $produk->nama_produk }}</h3>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $produk->deskripsi_produk }}</p>
                                <p class="text-indigo-600 font-bold mt-2">Rp
                                    {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr class="my-2 border-gray-100 mx-4">
                        @endif
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Belum ada data produk yang diunggah.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Modal & Aset --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Aset & Modal Bantuan
                        </h2>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Nilai Aset</p>
                            <p class="text-xl font-bold text-emerald-600">{{ $umkm->total_modal }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($umkm->modalUmkm as $modal)
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="px-2 py-1 bg-white border border-gray-200 text-[10px] font-bold text-gray-500 uppercase rounded-md">{{ $modal->kategori_modal }}</span>
                                    <span
                                        class="px-2 py-0.5 text-[10px] font-bold rounded-full
                                    {{ $modal->kondisi === 'baru'
                                        ? 'bg-green-100 text-green-700'
                                        : ($modal->kondisi === 'baik'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-amber-100 text-amber-700') }}">
                                        {{ strtoupper($modal->kondisi) }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-gray-900 mb-1">{{ $modal->nama_item }}</h4>
                                <p class="text-sm text-emerald-600 font-bold">Rp
                                    {{ number_format($modal->nilai_modal, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-400 mt-2">Diterima:
                                    {{ $modal->tanggal_perolehan ? $modal->tanggal_perolehan->format('d M Y') : '-' }}</p>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8">
                                <p class="text-gray-500 italic text-sm">Belum ada data aset modal.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ── RIGHT COLUMN: SIDEBAR INFO ── --}}
            <div class="space-y-8">

                {{-- Kontak & Lokasi --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                        </svg>
                        Informasi Kontak
                    </h2>

                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="bg-blue-50 p-2.5 rounded-xl flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Email Utama</p>
                                <p class="text-sm font-semibold text-gray-800 break-all">{{ $umkm->email }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="bg-green-50 p-2.5 rounded-xl flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">No. Telepon</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $umkm->telepon }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="bg-amber-50 p-2.5 rounded-xl flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Alamat Lengkap</p>
                                <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $umkm->alamat_lengkap }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Social Media --}}
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-4">Media Sosial</p>
                        <div class="flex flex-wrap gap-2">
                            @if ($umkm->instagram)
                                <a href="{{ $umkm->instagram }}" target="_blank"
                                    class="p-2 bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-600 text-white rounded-lg shadow-sm hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                </a>
                            @endif
                            @if ($umkm->facebook)
                                <a href="{{ $umkm->facebook }}" target="_blank"
                                    class="p-2 bg-[#1877F2] text-white rounded-lg shadow-sm hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                            @endif
                            @if ($umkm->tiktok)
                                <a href="{{ $umkm->tiktok }}" target="_blank"
                                    class="p-2 bg-black text-white rounded-lg shadow-sm hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Info Unit Pembina --}}
                <div class="bg-gradient-to-br from-indigo-900 to-purple-900 rounded-3xl shadow-xl p-8 text-white">
                    <h2 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        Unit Pembina
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider">Nama Unit</p>
                            <p class="text-base font-bold">{{ $umkm->unit->nama_unit ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider">ID Unit</p>
                            <p class="text-sm font-medium">{{ $umkm->unit->kode_unit ?? '-' }}</p>
                        </div>
                        <hr class="border-white/10 my-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider">Didaftarkan Oleh
                                </p>
                                <p class="text-sm font-bold">
                                    {{ $umkm->creator->name ?? ($umkm->creator->username ?? 'Sistem') }}</p>
                                <p class="text-[10px] text-indigo-400 italic mt-0.5">Pada
                                    {{ $umkm->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Metadata / Audit --}}
                <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100">
                    <div class="space-y-4 text-xs text-gray-500">
                        <div class="flex justify-between">
                            <span>Status Verifikasi:</span>
                            <span class="font-bold {{ $umkm->verified_at ? 'text-green-600' : 'text-amber-600' }}">
                                {{ $umkm->verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                            </span>
                        </div>
                        @if ($umkm->verified_at)
                            <div class="flex justify-between">
                                <span>Waktu Verifikasi:</span>
                                <span class="text-gray-700">{{ $umkm->verified_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Diverifikasi Oleh:</span>
                                <span class="text-gray-700">{{ $umkm->verifiedBy->name ?? 'Admin' }}</span>
                            </div>
                        @endif
                        <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between">
                            <span>Terakhir Update:</span>
                            <span>{{ $umkm->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

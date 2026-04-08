@extends('layouts.app')
@section('title', 'Dashboard - ' . $umkm->nama_usaha)

@section('content')
    <div class="p-6">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard UMKM</h1>
            <p class="text-sm text-gray-600 mt-1">
                Selamat datang,
                <span class="font-semibold text-gray-800">{{ auth()->user()->name ?? auth()->user()->username }}</span>
                &mdash; {{ $umkm->nama_usaha }}
            </p>
        </div>

        {{-- ===== ALERTS ===== --}}
        @if ($statusUmkm === 'pending')
            <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl mb-6">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Akun sedang menunggu persetujuan</p>
                    <p class="text-xs text-amber-600 mt-0.5">Pengajuan Anda sedang ditinjau oleh admin unit. Harap menunggu
                        konfirmasi lebih lanjut.</p>
                </div>
            </div>
        @endif

        @if (!$isVerified && $statusUmkm === 'aktif')
            <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-800">Profil belum diverifikasi</p>
                    <p class="text-xs text-blue-600 mt-0.5">Lengkapi profil dan produk Anda agar dapat diverifikasi oleh
                        admin.</p>
                </div>
            </div>
        @endif

        {{-- ===== STAT CARDS (3 kartu) ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

            {{-- Total Produk --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalProduk) }}</h3>
                <p class="text-sm text-gray-600">Total Produk</p>
            </div>

            {{-- Pesanan Pending --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $quickStats['pesanan_pending'] }}</h3>
                <p class="text-sm text-gray-600">Pesanan Pending</p>
            </div>

            {{-- Total Pesanan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $quickStats['total_pesanan'] }}</h3>
                <p class="text-sm text-gray-600">Total Pesanan</p>
            </div>

        </div>

        {{-- ===== INFORMASI USAHA + PRODUK TERBARU ===== --}}
        <div class="grid lg:grid-cols-2 gap-6 mb-6">

            {{-- Informasi Usaha --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Usaha</h2>
                    <a href="{{ route('umkm.settings.edit') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Profil
                    </a>
                </div>

                {{-- Logo + nama --}}
                <div class="flex items-center gap-4 mb-5 pb-5 border-b border-gray-100">
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center shrink-0">
                        @if ($umkm->logo_umkm)
                            <img src="{{ Storage::url($umkm->logo_umkm) }}" alt="Logo {{ $umkm->nama_usaha }}"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-gray-900 truncate">{{ $umkm->nama_usaha }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $umkm->nama_pemilik }}</p>
                        @if ($umkm->kategori)
                            <span
                                class="mt-1.5 inline-block px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full font-medium">
                                {{ $umkm->kategori->nama }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Detail info --}}
                <div class="space-y-3">
                    @if ($umkm->telepon)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Telepon</p>
                                <p class="text-sm font-medium text-gray-800">{{ $umkm->telepon }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($umkm->unit)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Unit</p>
                                <p class="text-sm font-medium text-gray-800">{{ $umkm->unit->nama_unit }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Bergabung Sejak</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($umkm->tanggal_bergabung)->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if ($isVerified && $umkm->verified_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Tanggal Verifikasi</p>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($umkm->verified_at)->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($umkm->tentang)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-400 mb-1">Tentang Usaha</p>
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">{{ $umkm->tentang }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Produk Terbaru --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Produk Terbaru</h2>
                    <a href="{{ route('umkm.produk.index') }}"
                        class="text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($produkTerbaru as $produk)
                        <div
                            class="p-3 bg-white rounded-lg border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-3">
                                {{-- Foto --}}
                                <div class="w-11 h-11 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                    @if (!empty($produk->foto_produk) && count($produk->foto_produk) > 0)
                                        <img src="{{ Storage::url($produk->foto_produk[0]) }}"
                                            alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $produk->nama_produk }}</p>
                                    <p class="text-xs font-bold text-gray-900 mt-0.5">Rp
                                        {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $produk->created_at->diffForHumans() }}</p>
                                </div>
                                {{-- Edit button --}}
                                <a href="{{ route('umkm.produk.edit', $produk->uuid) }}"
                                    class="w-7 h-7 shrink-0 text-gray-300 hover:text-blue-600 hover:bg-blue-50 rounded-lg flex items-center justify-center transition-colors opacity-0 group-hover:opacity-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-sm text-gray-500 mb-4">Belum ada produk terdaftar</p>
                            <a href="{{ route('umkm.produk.create') }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Produk
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ===== RINGKASAN USAHA + MEDIA SOSIAL ===== --}}
        <div class="grid lg:grid-cols-3 gap-6 mb-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Usaha</h2>
                <div class="divide-y divide-gray-100">

                    @php
                        $summaryRows = [
                            ['label' => 'Total Produk', 'value' => $umkmSummary['total_produk']],
                            ['label' => 'Unit', 'value' => $umkmSummary['unit']],
                            ['label' => 'Kategori', 'value' => $umkmSummary['kategori']],
                        ];
                    @endphp

                    @foreach ($summaryRows as $row)
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-600">{{ $row['label'] }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $row['value'] }}</span>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ ucfirst($umkmSummary['status']) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-600">Verifikasi</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $umkmSummary['verified'] ? 'Terverifikasi' : 'Belum' }}
                        </span>
                    </div>

                </div>
            </div>

            {{-- Media Sosial --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Media Sosial</h2>

                @if ($umkm->facebook || $umkm->instagram || $umkm->youtube || $umkm->tiktok)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                        @if ($umkm->instagram)
                            <a href="{{ $umkm->instagram }}" target="_blank"
                                class="flex items-center gap-2.5 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-all group">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-700 group-hover:text-pink-700">Instagram</p>
                                    <p class="text-xs text-gray-400 truncate">Terhubung</p>
                                </div>
                            </a>
                        @endif

                        @if ($umkm->facebook)
                            <a href="{{ $umkm->facebook }}" target="_blank"
                                class="flex items-center gap-2.5 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-all group">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-700 group-hover:text-blue-700">Facebook</p>
                                    <p class="text-xs text-gray-400 truncate">Terhubung</p>
                                </div>
                            </a>
                        @endif

                        @if ($umkm->youtube)
                            <a href="{{ $umkm->youtube }}" target="_blank"
                                class="flex items-center gap-2.5 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-all group">
                                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-700 group-hover:text-red-700">YouTube</p>
                                    <p class="text-xs text-gray-400 truncate">Terhubung</p>
                                </div>
                            </a>
                        @endif

                        @if ($umkm->tiktok)
                            <a href="{{ $umkm->tiktok }}" target="_blank"
                                class="flex items-center gap-2.5 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-all group">
                                <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.85a8.27 8.27 0 004.83 1.55V6.95a4.84 4.84 0 01-1.06-.26z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-700 group-hover:text-gray-900">TikTok</p>
                                    <p class="text-xs text-gray-400 truncate">Terhubung</p>
                                </div>
                            </a>
                        @endif

                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <p class="text-sm text-gray-500 mb-3">Belum ada media sosial terhubung</p>
                        <a href="{{ route('umkm.settings.edit') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                            Tambah Media Sosial
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- Custom Scrollbar Style --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
    </style>
@endsection

@extends('layouts.app')
@section('title', 'Dashboard - ' . $umkm->nama_usaha)
@section('content')
<div class="h-full flex">
    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto p-6 rounded-l-2xl">

        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-3 mb-1">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard UMKM</h1>
            </div>
            <p class="text-sm text-gray-600 mt-1">
                Selamat datang, <span
                    class="font-semibold text-gray-800">{{ auth()->user()->name ?? auth()->user()->username }}</span>
                &mdash; {{ $umkm->nama_usaha }}
                <span
                    class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full
                        @if ($statusUmkm === 'aktif') bg-green-100 text-green-700
                        @elseif($statusUmkm === 'pending') bg-amber-100 text-amber-700
                        @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst($statusUmkm) }}
                </span>
                @if ($isVerified)
                <span class="ml-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full font-medium">
                    ✓ Terverifikasi
                </span>
                @else
                <span class="ml-1 px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded-full font-medium">
                    Belum Diverifikasi
                </span>
                @endif
            </p>
        </div>

        <!-- Alert: Status Pending -->
        @if ($statusUmkm === 'pending')
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start space-x-3">
            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800">Akun Anda sedang menunggu persetujuan</p>
                <p class="text-xs text-amber-600 mt-0.5">Pengajuan Anda sedang ditinjau oleh admin unit. Harap
                    menunggu konfirmasi lebih lanjut.</p>
            </div>
        </div>
        @endif

        <!-- Alert: Belum Terverifikasi -->
        @if (!$isVerified && $statusUmkm === 'aktif')
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start space-x-3">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-800">Profil Anda belum diverifikasi</p>
                <p class="text-xs text-blue-600 mt-0.5">Lengkapi profil dan produk Anda agar dapat diverifikasi
                    oleh admin.</p>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Produk -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalProduk) }}</h3>
                <p class="text-sm text-gray-600">Total Produk</p>
            </div>

            <!-- Produk Hari Ini -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($produkHariIni) }}</h3>
                <p class="text-sm text-gray-600">Produk Hari Ini</p>
            </div>

            <!-- Produk Bulan Ini -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($produkBulanIni) }}</h3>
                <p class="text-sm text-gray-600">Produk Bulan Ini</p>
            </div>

            <!-- Status Verifikasi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-10 h-10 {{ $isVerified ? 'bg-emerald-50' : 'bg-gray-50' }} rounded-lg flex items-center justify-center">
                        @if ($isVerified)
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>
                </div>
                <h3 class="text-lg font-bold {{ $isVerified ? 'text-emerald-600' : 'text-gray-400' }} mb-1">
                    {{ $isVerified ? 'Terverifikasi' : 'Belum Verifikasi' }}
                </h3>
                <p class="text-sm text-gray-600">Status Verifikasi</p>
            </div>
        </div>

        <!-- Order Summary Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-sm border border-amber-100 p-5 flex items-center space-x-4">
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 line-height-1">{{ $quickStats['pesanan_pending'] }}</h3>
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pesanan Pending</p>
                </div>
                <a href="{{ route('umkm.pesanan.index') }}" class="ml-auto p-2 bg-white rounded-lg text-amber-500 hover:text-amber-600 shadow-sm border border-amber-100">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl shadow-sm border border-emerald-100 p-5 flex items-center space-x-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 line-height-1">{{ $quickStats['total_pesanan'] }}</h3>
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Total Pesanan</p>
                </div>
                <a href="{{ route('umkm.pesanan.index') }}" class="ml-auto p-2 bg-white rounded-lg text-emerald-600 hover:text-emerald-700 shadow-sm border border-emerald-100">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Profil UMKM & Produk Terbaru -->
        <div class="grid lg:grid-cols-2 gap-6 mb-6">

            <!-- Informasi Profil UMKM -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Usaha</h2>
                    <a href="{{ route('umkm.settings.edit') }}"
                        class="text-sm text-green-600 hover:text-green-700 font-medium flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span>Edit Profil</span>
                    </a>
                </div>

                <!-- Logo + Nama -->
                <div class="flex items-center space-x-4 mb-5 pb-5 border-b border-gray-100">
                    <div
                        class="w-16 h-16 rounded-xl overflow-hidden bg-green-50 flex items-center justify-center flex-shrink-0">
                        @if ($umkm->logo_umkm)
                        <img src="{{ Storage::url($umkm->logo_umkm) }}" alt="Logo {{ $umkm->nama_usaha }}"
                            class="w-full h-full object-cover">
                        @else
                        <svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">{{ $umkm->nama_usaha }}</h3>
                        <p class="text-sm text-gray-500">{{ $umkm->nama_pemilik }}</p>
                        @if ($umkm->kategori)
                        <span
                            class="mt-1 inline-block px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full font-medium">
                            {{ $umkm->kategori->nama }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Detail Info -->
                <div class="space-y-3">
                    @if ($umkm->telepon)
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
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
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
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
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Bergabung Sejak</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($umkm->tanggal_bergabung)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    @if ($isVerified && $umkm->verified_at)
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Tanggal Verifikasi</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($umkm->verified_at)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    @endif
                    @if ($umkm->tentang)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-400 mb-1">Tentang Usaha</p>
                        <p class="text-sm text-gray-700 leading-relaxed line-clamp-3">{{ $umkm->tentang }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Produk Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Produk Terbaru</h2>
                    <a href="{{ route('umkm.produk.index') }}"
                        class="text-sm text-green-600 hover:text-green-700 font-medium">Lihat Semua</a>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($produkTerbaru as $produk)
                    <div
                        class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg hover:shadow-md transition-shadow border-l-4 border-green-400">
                        <div class="flex items-start space-x-3">
                            <!-- Foto Produk -->
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-green-100 flex-shrink-0">
                                @if (!empty($produk->foto_produk) && count($produk->foto_produk) > 0)
                                <img src="{{ Storage::url($produk->foto_produk[0]) }}"
                                    alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-300" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $produk->nama_produk }}
                                </p>
                                <p class="text-xs text-green-700 font-medium mt-0.5">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $produk->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('umkm.produk.edit', $produk->uuid) }}"
                                class="flex-shrink-0 p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-sm text-gray-500 mb-3">Belum ada produk terdaftar</p>
                        <a href="{{ route('umkm.produk.create') }}"
                            class="inline-flex items-center px-4 py-2 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- Media Sosial -->
        @if ($umkm->facebook || $umkm->instagram || $umkm->youtube || $umkm->tiktok)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Media Sosial</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @if ($umkm->instagram)
                <a href="{{ $umkm->instagram }}" target="_blank"
                    class="flex items-center space-x-2 p-3 bg-pink-50 hover:bg-pink-100 rounded-lg transition-colors group">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-pink-400 to-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-pink-700">Instagram</span>
                </a>
                @endif
                @if ($umkm->facebook)
                <a href="{{ $umkm->facebook }}" target="_blank"
                    class="flex items-center space-x-2 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Facebook</span>
                </a>
                @endif
                @if ($umkm->youtube)
                <a href="{{ $umkm->youtube }}" target="_blank"
                    class="flex items-center space-x-2 p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors group">
                    <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-700">YouTube</span>
                </a>
                @endif
                @if ($umkm->tiktok)
                <a href="{{ $umkm->tiktok }}" target="_blank"
                    class="flex items-center space-x-2 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                    <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.85a8.27 8.27 0 004.83 1.55V6.95a4.84 4.84 0 01-1.06-.26z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">TikTok</span>
                </a>
                @endif
            </div>
        </div>
        @endif

    </div>

    <!-- Right Sidebar -->
    <div class="w-80 bg-white hidden lg:block rounded-2xl overflow-hidden shadow-sm ml-1">
        <div class="overflow-y-auto p-6 h-full">

            <!-- Info UMKM -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 p-4 mb-6">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Info UMKM</h3>
                <div class="space-y-2">
                    <div class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Nama Usaha</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $umkm->nama_usaha }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Nama Pemilik</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $umkm->nama_pemilik }}</p>
                        </div>
                    </div>
                    @if ($umkm->telepon)
                    <div class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Telepon</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $umkm->telepon }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Bergabung Sejak</p>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($umkm->tanggal_bergabung)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Hari Ini -->
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-100 p-4 mb-6">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Statistik Hari Ini</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2 bg-white rounded-lg">
                        <span class="text-sm text-gray-700">Produk Ditambahkan</span>
                        <span class="text-sm font-bold text-blue-600">{{ $produkHariIni }}</span>
                    </div>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 p-4 mb-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Ringkasan Usaha</h4>
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Total Produk</span>
                        <span class="font-bold text-gray-900">{{ $umkmSummary['total_produk'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Status</span>
                        <span
                            class="font-bold
                                @if ($umkmSummary['status'] === 'aktif') text-green-700
                                @elseif($umkmSummary['status'] === 'pending') text-amber-700
                                @else text-red-700 @endif">
                            {{ ucfirst($umkmSummary['status']) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Verifikasi</span>
                        <span class="font-bold {{ $umkmSummary['verified'] ? 'text-blue-700' : 'text-gray-500' }}">
                            {{ $umkmSummary['verified'] ? 'Terverifikasi' : 'Belum' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Unit</span>
                        <span class="font-bold text-gray-700">{{ $umkmSummary['unit'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Kategori</span>
                        <span class="font-bold text-gray-700">{{ $umkmSummary['kategori'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('umkm.produk.index') }}"
                        class="flex items-center justify-between p-2 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <span class="text-sm text-gray-700">Lihat Produk Saya</span>
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="{{ route('umkm.produk.index') }}"
                        class="flex items-center justify-between p-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <span class="text-sm text-gray-700">Edit Produk</span>
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="{{ route('umkm.settings.edit') }}"
                        class="flex items-center justify-between p-2 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                        <span class="text-sm text-gray-700">Edit Profil</span>
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
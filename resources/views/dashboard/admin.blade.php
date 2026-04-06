@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
    <div class="p-6">

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-sm text-gray-600 mt-1">Selamat datang di panel administrasi sistem UMKM</p>
        </div>

        <!-- Statistics Cards -->
        @php
            $totalUsers = $totalUsers ?? 0;
            $usersGrowth = $usersGrowth ?? 0;
            $totalUnits = $totalUnits ?? 0;
            $unitAktif = $unitAktif ?? 0;
            $unitNonaktif = $unitNonaktif ?? 0;
            $unitsGrowth = $unitsGrowth ?? 0;
            $totalUmkm = $totalUmkm ?? 0;
            $umkmGrowth = $umkmGrowth ?? 0;
            $umkmAktif = $umkmAktif ?? 0;
            $totalKategori = $totalKategori ?? 0;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Users Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalUsers) }}</h3>
                <p class="text-sm text-gray-600">Total Users</p>
            </div>

            <!-- Total Units Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalUnits) }}</h3>
                <p class="text-sm text-gray-600">Total Units</p>
            </div>

            <!-- Unit Aktif Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($unitAktif) }}</h3>
                <p class="text-sm text-gray-600">Unit Aktif</p>
            </div>

            <!-- Unit Nonaktif Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($unitNonaktif) }}</h3>
                <p class="text-sm text-gray-600">Unit Nonaktif</p>
            </div>

            <!-- Total UMKM Card -->
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
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalUmkm) }}</h3>
                <p class="text-sm text-gray-600">Total UMKM</p>
            </div>

            <!-- UMKM Aktif Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($umkmAktif) }}</h3>
                <p class="text-sm text-gray-600">UMKM Aktif</p>
            </div>

            <!-- UMKM Nonaktif Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalUmkm - $umkmAktif) }}</h3>
                <p class="text-sm text-gray-600">UMKM Nonaktif</p>
            </div>

            <!-- Kategori Produk Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalKategori) }}</h3>
                <p class="text-sm text-gray-600">Kategori Produk</p>
            </div>
        </div>

        <!-- Charts Section -->
        @php
            $grafikRegistrasi = $grafikRegistrasi ?? ['labels' => [], 'umkm' => [], 'users' => [], 'units' => []];
            $statusUmkm = $statusUmkm ?? ['aktif' => 0, 'nonaktif' => 0];
            $statusUnit = $statusUnit ?? ['aktif' => 0, 'nonaktif' => 0];
            $topProvinsi = $topProvinsi ?? [];
        @endphp

        <!-- Registration Trend Chart (Full Width) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Tren Registrasi (6 Bulan Terakhir)</h2>
            </div>
            <div class="h-80">
                @if (!empty($grafikRegistrasi['labels']))
                    <canvas id="registrasiChart"></canvas>
                @else
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-gray-400 text-sm">Tidak ada data tren registrasi</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Charts & Top Provinsi -->
        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <!-- Status UMKM Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status UMKM</h2>
                <div class="h-64 flex items-center justify-center">
                    @if ($statusUmkm['aktif'] + $statusUmkm['nonaktif'] > 0)
                        <canvas id="statusChart"></canvas>
                    @else
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                            <p class="text-gray-400 text-sm">Tidak ada data</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-2 mt-4">
                    <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-700">Aktif</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($statusUmkm['aktif']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-700">Nonaktif</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($statusUmkm['nonaktif']) }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Unit Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Unit</h2>
                <div class="h-64 flex items-center justify-center">
                    @if ($statusUnit['aktif'] + $statusUnit['nonaktif'] > 0)
                        <canvas id="statusUnitChart"></canvas>
                    @else
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-gray-400 text-sm">Tidak ada data</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-2 mt-4">
                    <div class="flex items-center justify-between p-2 bg-purple-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <span class="text-sm text-gray-700">Aktif</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($statusUnit['aktif']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-700">Nonaktif</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($statusUnit['nonaktif']) }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Provinsi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Provinsi dengan UMKM Terbanyak</h2>
                <div class="space-y-3">
                    @forelse($topProvinsi as $index => $provinsi)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-400 w-6">#{{ $index + 1 }}</span>
                                    <span class="text-sm text-gray-700">{{ $provinsi['name'] ?? '-' }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($provinsi['total'] ?? 0) }}
                                    UMKM</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ min(100, $provinsi['percentage'] ?? 0) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <p class="text-center text-gray-400 text-sm">Belum ada data provinsi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Units, Aktivitas & UMKM Terbaru -->
        @php
            $unitList = $unitList ?? collect();
            $aktivitasTerbaru = $aktivitasTerbaru ?? [];
            $umkmTerbaru = $umkmTerbaru ?? collect();
        @endphp

        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <!-- Units List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Units Aktif</h2>
                    <span class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-medium">
                        {{ number_format($totalUnits) }} Units
                    </span>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($unitList as $unit)
                        <div
                            class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg hover:shadow-md transition-all border border-purple-100">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">
                                    {{ $unit->kode_unit ?? '-' }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900 mb-1">{{ $unit->nama_unit ?? '-' }}</p>
                            <p class="text-xs text-gray-600 mb-2">Admin:
                                {{ optional($unit->user)->name ?? 'Belum ada admin' }}</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span
                                    class="truncate">{{ $unit->kota_nama ?? ($unit->alamat ?? 'Lokasi belum diset') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-sm text-gray-500">Belum ada unit terdaftar</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
                </div>
                <div class="space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($aktivitasTerbaru as $aktivitas)
                        <div class="flex gap-3">
                            <div
                                class="w-2 h-2 bg-{{ $aktivitas['color'] ?? 'gray' }}-500 rounded-full mt-2 flex-shrink-0">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $aktivitas['title'] ?? 'Aktivitas' }}</p>
                                <p class="text-xs text-gray-600 mt-1 break-words">{{ $aktivitas['description'] ?? '' }}
                                </p>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="text-xs text-gray-400">{{ $aktivitas['time'] ?? '' }}</span>
                                    @if (!empty($aktivitas['user']))
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500 truncate">{{ $aktivitas['user'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-400 text-sm">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- UMKM Terdaftar Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">UMKM Terbaru</h2>
                    <a href="{{ route('umkm.index') }}"
                        class="text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        Lihat Semua →
                    </a>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($umkmTerbaru as $umkm)
                        <div
                            class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg hover:shadow-md transition-all border border-blue-100">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ ($umkm->status ?? 'nonaktif') === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($umkm->status ?? 'nonaktif') }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                    {{ $umkm->kode_umkm ?? '-' }}
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900 mb-1 truncate"
                                title="{{ $umkm->nama_usaha ?? '-' }}">
                                {{ $umkm->nama_usaha ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-600 mb-2">{{ optional($umkm->kategori)->nama ?? '-' }}</p>
                            <div class="flex items-center text-xs text-gray-500 mb-1">
                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                <span class="truncate">{{ $umkm->email ?? '-' }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ optional($umkm->created_at)->diffForHumans() ?? '-' }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-sm text-gray-500">Belum ada UMKM terdaftar</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Chart.js Script -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Registration Trend Chart
                @if (!empty($grafikRegistrasi['labels']))
                    const ctxRegistrasi = document.getElementById('registrasiChart');
                    if (ctxRegistrasi) {
                        new Chart(ctxRegistrasi, {
                            type: 'line',
                            data: {
                                labels: @json($grafikRegistrasi['labels']),
                                datasets: [{
                                        label: 'UMKM',
                                        data: @json($grafikRegistrasi['umkm']),
                                        borderColor: 'rgb(59, 130, 246)',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        borderWidth: 2
                                    },
                                    {
                                        label: 'Users',
                                        data: @json($grafikRegistrasi['users']),
                                        borderColor: 'rgb(168, 85, 247)',
                                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        borderWidth: 2
                                    },
                                    {
                                        label: 'Units',
                                        data: @json($grafikRegistrasi['units']),
                                        borderColor: 'rgb(34, 197, 94)',
                                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        borderWidth: 2
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 15,
                                            font: {
                                                size: 12
                                            },
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        titleFont: {
                                            size: 13
                                        },
                                        bodyFont: {
                                            size: 12
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(0,0,0,0.05)'
                                        },
                                        ticks: {
                                            font: {
                                                size: 11
                                            },
                                            precision: 0
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            font: {
                                                size: 11
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                @endif

                // Status UMKM Chart
                @if ($statusUmkm['aktif'] + $statusUmkm['nonaktif'] > 0)
                    const ctxStatus = document.getElementById('statusChart');
                    if (ctxStatus) {
                        new Chart(ctxStatus, {
                            type: 'doughnut',
                            data: {
                                labels: ['Aktif', 'Nonaktif'],
                                datasets: [{
                                    data: [
                                        @json($statusUmkm['aktif']),
                                        @json($statusUmkm['nonaktif'])
                                    ],
                                    backgroundColor: [
                                        'rgba(34, 197, 94, 0.8)',
                                        'rgba(239, 68, 68, 0.8)'
                                    ],
                                    borderColor: [
                                        'rgb(34, 197, 94)',
                                        'rgb(239, 68, 68)'
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.label || '';
                                                const value = context.parsed || 0;
                                                const total = context.dataset.data.reduce((a, b) => a + b,
                                                    0);
                                                const percentage = total > 0 ? ((value / total) * 100)
                                                    .toFixed(1) : 0;
                                                return `${label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                },
                                cutout: '70%'
                            }
                        });
                    }
                @endif

                // Status Unit Chart
                @if ($statusUnit['aktif'] + $statusUnit['nonaktif'] > 0)
                    const ctxStatusUnit = document.getElementById('statusUnitChart');
                    if (ctxStatusUnit) {
                        new Chart(ctxStatusUnit, {
                            type: 'doughnut',
                            data: {
                                labels: ['Aktif', 'Nonaktif'],
                                datasets: [{
                                    data: [
                                        @json($statusUnit['aktif']),
                                        @json($statusUnit['nonaktif'])
                                    ],
                                    backgroundColor: [
                                        'rgba(168, 85, 247, 0.8)',
                                        'rgba(239, 68, 68, 0.8)'
                                    ],
                                    borderColor: [
                                        'rgb(168, 85, 247)',
                                        'rgb(239, 68, 68)'
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        callbacks: {
                                            label: function(context) {
                                                const label = context.label || '';
                                                const value = context.parsed || 0;
                                                const total = context.dataset.data.reduce((a, b) => a + b,
                                                    0);
                                                const percentage = total > 0 ? ((value / total) * 100)
                                                    .toFixed(1) : 0;
                                                return `${label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                },
                                cutout: '70%'
                            }
                        });
                    }
                @endif
            });
        </script>

        <!-- Custom Scrollbar Style -->
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

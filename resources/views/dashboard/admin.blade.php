@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
    <div class="h-full overflow-y-auto p-6">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Selamat datang di panel administrasi sistem UMKM</p>
        </div>

        {{-- Statistics Cards --}}
        @php
            $totalUsers = $totalUsers ?? 0;
            $totalUnits = $totalUnits ?? 0;
            $unitAktif = $unitAktif ?? 0;
            $unitNonaktif = $unitNonaktif ?? 0;
            $totalUmkm = $totalUmkm ?? 0;
            $umkmAktif = $umkmAktif ?? 0;
            $totalKategori = $totalKategori ?? 0;
        @endphp

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#7c3aed">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($totalUsers) }}</h3>
                <p class="text-xs text-gray-400">Total Pengguna</p>
            </div>

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#2563eb">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($totalUnits) }}</h3>
                <p class="text-xs text-gray-400">Total Unit</p>
            </div>

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#16a34a">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($unitAktif) }}</h3>
                <p class="text-xs text-gray-400">Unit Aktif</p>
            </div>

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#dc2626">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($unitNonaktif) }}</h3>
                <p class="text-xs text-gray-400">Unit Nonaktif</p>
            </div>

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                        style="color:#ca8a04">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($totalUmkm) }}</h3>
                <p class="text-xs text-gray-400">Total UMKM</p>
            </div>

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#16a34a">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($umkmAktif) }}</h3>
                <p class="text-xs text-gray-400">UMKM Aktif</p>
            </div>

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#dc2626">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($totalUmkm - $umkmAktif) }}</h3>
                <p class="text-xs text-gray-400">UMKM Nonaktif</p>
            </div>

            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        style="color:#e11d48">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($totalKategori) }}</h3>
                <p class="text-xs text-gray-400">Kategori Produk</p>
            </div>

        </div>

        {{-- Charts Section --}}
        @php
            $grafikRegistrasi = $grafikRegistrasi ?? ['labels' => [], 'umkm' => [], 'users' => [], 'units' => []];
            $statusUmkm = $statusUmkm ?? ['aktif' => 0, 'nonaktif' => 0];
            $statusUnit = $statusUnit ?? ['aktif' => 0, 'nonaktif' => 0];
            $topProvinsi = $topProvinsi ?? [];
        @endphp

        {{-- Tren Registrasi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tren Registrasi 6 Bulan Terakhir</h2>
            <div style="height: 280px;">
                @if (!empty($grafikRegistrasi['labels']))
                    <canvas id="registrasiChart"></canvas>
                @else
                    <div
                        class="flex flex-col items-center justify-center h-full bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada data tren registrasi</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Status Charts & Top Provinsi --}}
        <div class="grid lg:grid-cols-3 gap-6 mb-6">

            {{-- Status UMKM --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex-shrink-0">Status UMKM</h2>
                @if ($statusUmkm['aktif'] + $statusUmkm['nonaktif'] > 0)
                    <div class="flex-shrink-0" style="height: 160px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-3 space-y-1.5">
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#5b8fa8"></span>
                                <span class="text-xs text-gray-600">Aktif</span>
                            </div>
                            <span class="text-xs font-bold text-gray-700">{{ number_format($statusUmkm['aktif']) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#c47f6e"></span>
                                <span class="text-xs text-gray-600">Nonaktif</span>
                            </div>
                            <span
                                class="text-xs font-bold text-gray-700">{{ number_format($statusUmkm['nonaktif']) }}</span>
                        </div>
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center flex-1 bg-gray-50 rounded-xl border border-dashed border-gray-200 py-10">
                        <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada data</p>
                    </div>
                @endif
            </div>

            {{-- Status Unit --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex-shrink-0">Status Unit</h2>
                @if ($statusUnit['aktif'] + $statusUnit['nonaktif'] > 0)
                    <div class="flex-shrink-0" style="height: 160px;">
                        <canvas id="statusUnitChart"></canvas>
                    </div>
                    <div class="mt-3 space-y-1.5">
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#7a9e7e"></span>
                                <span class="text-xs text-gray-600">Aktif</span>
                            </div>
                            <span class="text-xs font-bold text-gray-700">{{ number_format($statusUnit['aktif']) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#c47f6e"></span>
                                <span class="text-xs text-gray-600">Nonaktif</span>
                            </div>
                            <span
                                class="text-xs font-bold text-gray-700">{{ number_format($statusUnit['nonaktif']) }}</span>
                        </div>
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center flex-1 bg-gray-50 rounded-xl border border-dashed border-gray-200 py-10">
                        <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada data</p>
                    </div>
                @endif
            </div>

            {{-- Top Provinsi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Provinsi UMKM Terbanyak</h2>
                <div class="space-y-3">
                    @forelse($topProvinsi as $index => $provinsi)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-300 w-5">#{{ $index + 1 }}</span>
                                    <span class="text-xs text-gray-600">{{ $provinsi['name'] ?? '-' }}</span>
                                </div>
                                <span
                                    class="text-xs font-bold text-gray-700">{{ number_format($provinsi['total'] ?? 0) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all duration-500"
                                    style="width: {{ min(100, $provinsi['percentage'] ?? 0) }}%; background:#5b8fa8">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <p class="text-sm text-gray-400">Belum ada data provinsi</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Units, Aktivitas & UMKM Terbaru --}}
        @php
            $unitList = $unitList ?? collect();
            $aktivitasTerbaru = $aktivitasTerbaru ?? [];
            $umkmTerbaru = $umkmTerbaru ?? collect();
        @endphp

        <div class="grid lg:grid-cols-3 gap-6 mb-6">

            {{-- Units Aktif --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Unit Aktif</h2>
                    <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full font-medium">
                        {{ number_format($totalUnits) }} unit
                    </span>
                </div>
                <div class="space-y-2 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($unitList as $unit)
                        <div
                            class="p-3.5 bg-gray-50 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span
                                    class="text-xs font-medium text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded-full">
                                    {{ $unit->kode_unit ?? '-' }}
                                </span>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                    style="background:#edf3f6;color:#5b8fa8">
                                    Aktif
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-800 mb-0.5">{{ $unit->nama_unit ?? '-' }}</p>
                            <div class="flex items-center text-xs text-gray-400 mt-1">
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
                        <div
                            class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-sm text-gray-400">Belum ada unit terdaftar</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Aktivitas Terbaru --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h2>
                <div class="space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($aktivitasTerbaru as $aktivitas)
                        <div class="flex gap-3">
                            <div class="mt-1.5 flex-shrink-0">
                                <span class="block w-2 h-2 rounded-full bg-gray-300"></span>
                            </div>
                            <div class="flex-1 min-w-0 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                <p class="text-sm font-medium text-gray-800">{{ $aktivitas['title'] ?? 'Aktivitas' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 break-words">{{ $aktivitas['description'] ?? '' }}
                                </p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="text-xs text-gray-400">{{ $aktivitas['time'] ?? '' }}</span>
                                    @if (!empty($aktivitas['user']))
                                        <span class="text-xs text-gray-300">•</span>
                                        <span class="text-xs text-gray-400 truncate">{{ $aktivitas['user'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-gray-400">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- UMKM Terbaru --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">UMKM Terbaru</h2>
                    <a href="{{ route('umkm.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center space-x-1">
                        <span>Lihat Semua</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="space-y-2 max-h-96 overflow-y-auto custom-scrollbar">
                    @forelse($umkmTerbaru as $umkm)
                        <div
                            class="p-3.5 bg-gray-50 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span
                                    class="text-xs font-medium px-2 py-0.5 rounded-full
                                        {{ ($umkm->status ?? 'nonaktif') === 'aktif' ? 'bg-[#edf3f6] text-[#5b8fa8]' : 'bg-[#faf0ee] text-[#c47f6e]' }}">
                                    {{ ucfirst($umkm->status ?? 'nonaktif') }}
                                </span>
                                <span
                                    class="text-xs text-gray-400 bg-white border border-gray-200 px-2 py-0.5 rounded-full">
                                    {{ $umkm->kode_umkm ?? '-' }}
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-800 mb-0.5 truncate">{{ $umkm->nama_usaha ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-400 mb-1">{{ optional($umkm->kategori)->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ optional($umkm->created_at)->diffForHumans() ?? '-' }}</p>
                        </div>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-sm text-gray-400">Belum ada UMKM terdaftar</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ─── Tren Registrasi ─────────────────────────────────────────────────────
            @if (!empty($grafikRegistrasi['labels']))
                const ctxReg = document.getElementById('registrasiChart');
                if (ctxReg) {
                    new Chart(ctxReg, {
                        type: 'line',
                        data: {
                            labels: @json($grafikRegistrasi['labels']),
                            datasets: [{
                                    label: 'UMKM',
                                    data: @json($grafikRegistrasi['umkm']),
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37,99,235,0.06)',
                                    borderWidth: 2.5,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#2563eb',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                },
                                {
                                    label: 'Pengguna',
                                    data: @json($grafikRegistrasi['users']),
                                    borderColor: '#6b7280',
                                    backgroundColor: 'rgba(107,114,128,0.06)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#6b7280',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                },
                                {
                                    label: 'Unit',
                                    data: @json($grafikRegistrasi['units']),
                                    borderColor: '#9ca3af',
                                    backgroundColor: 'rgba(156,163,175,0.06)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#9ca3af',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 10,
                                        padding: 16,
                                        font: {
                                            size: 12
                                        },
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.y
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.04)'
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

            // ─── Donut Status UMKM ───────────────────────────────────────────────────
            @if ($statusUmkm['aktif'] + $statusUmkm['nonaktif'] > 0)
                const ctxUmkm = document.getElementById('statusChart');
                if (ctxUmkm) {
                    new Chart(ctxUmkm, {
                        type: 'pie',
                        data: {
                            labels: ['Aktif', 'Nonaktif'],
                            datasets: [{
                                data: [@json($statusUmkm['aktif']), @json($statusUmkm['nonaktif'])],
                                backgroundColor: ['#5b8fa8', '#c47f6e'],
                                borderColor: '#fff',
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
                                    callbacks: {
                                        label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' UMKM'
                                    }
                                }
                            }
                        }
                    });
                }
            @endif

            // ─── Donut Status Unit ───────────────────────────────────────────────────
            @if ($statusUnit['aktif'] + $statusUnit['nonaktif'] > 0)
                const ctxUnit = document.getElementById('statusUnitChart');
                if (ctxUnit) {
                    new Chart(ctxUnit, {
                        type: 'pie',
                        data: {
                            labels: ['Aktif', 'Nonaktif'],
                            datasets: [{
                                data: [@json($statusUnit['aktif']), @json($statusUnit['nonaktif'])],
                                backgroundColor: ['#7a9e7e', '#c47f6e'],
                                borderColor: '#fff',
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
                                    callbacks: {
                                        label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' Unit'
                                    }
                                }
                            }
                        }
                    });
                }
            @endif

        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 99px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
    </style>

@endsection

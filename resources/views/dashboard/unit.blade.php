@extends('layouts.app')
@section('title', 'Dashboard Unit - ' . $unit->nama_unit)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        #umkm-map {
            position: relative;
            background-color: #aad3df;
        }

        #umkm-map .leaflet-container {
            font-family: inherit;
        }

        .umkm-popup .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
            padding: 0 !important;
            overflow: hidden;
        }

        .umkm-popup .leaflet-popup-content {
            margin: 0 !important;
            width: auto !important;
        }

        .umkm-popup .leaflet-popup-tip-container {
            margin-top: -1px;
        }
    </style>
@endpush

@section('content')
    <div class="h-full overflow-y-auto p-6">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Unit</h1>
            <p class="text-sm text-gray-500 mt-1">
                Selamat datang, <span
                    class="font-semibold text-gray-700">{{ auth()->user()->name ?? auth()->user()->username }}</span>
                &mdash; {{ $unit->nama_unit }}
            </p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-6">

            {{-- Total UMKM --}}
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

            {{-- UMKM Aktif --}}
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

            {{-- UMKM Nonaktif --}}
            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#dc2626">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($umkmNonaktif) }}</h3>
                <p class="text-xs text-gray-400">UMKM Nonaktif</p>
            </div>

            {{-- Total Produk --}}
            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        style="color:#e11d48">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($totalProduk) }}</h3>
                <p class="text-xs text-gray-400">Total Produk</p>
            </div>

            {{-- Nilai Modal Aktif --}}
            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#0891b2">
                        <path
                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-0.5">Rp {{ number_format($totalModal, 0, ',', '.') }}</h3>
                <p class="text-xs text-gray-400">Nilai Modal Aktif</p>
            </div>

            {{-- Punya Rekening --}}
            <div class="bg-white rounded-xl p-5 hover:shadow-sm transition-shadow">
                <div class="mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color:#7c3aed">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                        <path fill-rule="evenodd"
                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-0.5">{{ number_format($umkmDenganRekening) }}</h3>
                <p class="text-xs text-gray-400">Punya Rekening</p>
            </div>

        </div>

        {{-- Peta Sebaran UMKM --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Peta Sebaran UMKM Binaan</h2>
                    <p class="text-xs text-gray-400">{{ count($umkmMapData) }} UMKM ditampilkan di peta</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#5b8fa8"></span>
                        <span class="text-xs text-gray-500">Aktif</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#c47f6e"></span>
                        <span class="text-xs text-gray-500">Nonaktif</span>
                    </div>
                </div>
            </div>

            @if (count($umkmMapData) > 0)
                <div id="umkm-map" class="w-full rounded-xl border border-gray-100"
                    style="height: 420px; position: relative; z-index: 1;"></div>
            @else
                <div
                    class="flex flex-col items-center justify-center h-64 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm text-gray-400">Belum ada data lokasi UMKM</p>
                    <p class="text-xs text-gray-400 mt-1">Lokasi akan muncul setelah UMKM memiliki kode provinsi</p>
                </div>
            @endif
        </div>

        {{-- Grafik Registrasi + Pie Chart Kategori --}}
        <div class="grid lg:grid-cols-3 gap-6 mb-6 items-stretch">

            {{-- Grafik Registrasi --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <h2 class="text-lg font-semibold text-gray-900">Registrasi UMKM 6 Bulan Terakhir</h2>
                    <a href="{{ route('umkm.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center space-x-1">
                        <span>Lihat Semua</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="flex-1 min-h-0" style="height: 260px;">
                    <canvas id="registrasiChart"></canvas>
                </div>
            </div>

            {{-- Pie Chart Kategori --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex-shrink-0">Kategori UMKM</h2>
                @if ($topKategori->isEmpty())
                    <div
                        class="flex flex-col items-center justify-center flex-1 bg-gray-50 rounded-xl border border-dashed border-gray-200 py-10">
                        <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <p class="text-sm text-gray-400">Belum ada data kategori</p>
                    </div>
                @else
                    <div class="flex-shrink-0" style="height: 160px;">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                    <div class="mt-3 space-y-1.5 overflow-y-auto flex-1 min-h-0">
                        @php
                            $pieColors = [
                                '#5b8fa8',
                                '#7a9e7e',
                                '#a89b6e',
                                '#c47f6e',
                                '#8a7fa8',
                                '#7aa0a0',
                                '#a8856e',
                                '#7a8fa8',
                            ];
                        @endphp
                        @foreach ($topKategori as $index => $kategori)
                            <div class="flex items-center justify-between py-0.5">
                                <div class="flex items-center space-x-2 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                        style="background-color: {{ $pieColors[$index % count($pieColors)] }}"></span>
                                    <span class="text-xs text-gray-600 truncate">{{ $kategori['nama'] }}</span>
                                </div>
                                <span
                                    class="text-xs font-bold text-gray-700 ml-2 flex-shrink-0">{{ $kategori['total'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxRegistrasi = document.getElementById('registrasiChart');
            if (ctxRegistrasi) {
                new Chart(ctxRegistrasi, {
                    type: 'line',
                    data: {
                        labels: @json($grafikRegistrasi['labels']),
                        datasets: [{
                            label: 'UMKM Terdaftar',
                            data: @json($grafikRegistrasi['data']),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.07)',
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
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
                                    label: ctx => ' ' + ctx.parsed.y + ' UMKM'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.04)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            const ctxKategori = document.getElementById('kategoriChart');
            if (ctxKategori) {
                const kategoriData = @json($topKategori);
                const pieColors = ['#5b8fa8', '#7a9e7e', '#a89b6e', '#c47f6e', '#8a7fa8', '#7aa0a0', '#a8856e',
                    '#7a8fa8'
                ];
                new Chart(ctxKategori, {
                    type: 'pie',
                    data: {
                        labels: kategoriData.map(k => k.nama),
                        datasets: [{
                            data: kategoriData.map(k => k.total),
                            backgroundColor: pieColors.slice(0, kategoriData.length),
                            borderColor: '#fff',
                            borderWidth: 2,
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
        });
    </script>

    {{-- Leaflet.js Map --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        window.addEventListener('load', function() {
            const mapEl = document.getElementById('umkm-map');
            if (!mapEl) return;

            const umkmData = @json($umkmMapData);
            if (!umkmData || umkmData.length === 0) return;

            const avgLat = umkmData.reduce((s, u) => s + u.lat, 0) / umkmData.length;
            const avgLng = umkmData.reduce((s, u) => s + u.lng, 0) / umkmData.length;

            const indonesiaBounds = [
                [-15.0, 92.0],
                [10.0, 145.0]
            ];

            const map = L.map('umkm-map', {
                center: [avgLat, avgLng],
                zoom: 5,
                scrollWheelZoom: true,
                zoomControl: true,
                maxBounds: indonesiaBounds,
                maxBoundsViscosity: 0.8,
                minZoom: 5
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 18,
                minZoom: 5,
                noWrap: true
            }).addTo(map);

            function makeIcon(status) {
                let color = status === 'aktif' ? '#5b8fa8' : '#c47f6e';
                const svg = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 42" width="32" height="42">
                        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="rgba(0,0,0,0.25)"/>
                        </filter>
                        <path d="M16 0C9.373 0 4 5.373 4 12c0 9 12 30 12 30S28 21 28 12C28 5.373 22.627 0 16 0z"
                              fill="${color}" filter="url(#shadow)"/>
                        <circle cx="16" cy="12" r="6" fill="white" fill-opacity="0.95"/>
                        <circle cx="16" cy="12" r="3.5" fill="${color}"/>
                    </svg>`;
                return L.divIcon({
                    html: svg,
                    className: '',
                    iconSize: [32, 42],
                    iconAnchor: [16, 42],
                    popupAnchor: [0, -44],
                });
            }

            const bounds = [];
            umkmData.forEach(function(umkm) {
                const icon = makeIcon(umkm.status);
                const statusBadge = umkm.status === 'aktif' ?
                    '<span style="background:#edf3f6;color:#5b8fa8;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;">Aktif</span>' :
                    '<span style="background:#faf0ee;color:#c47f6e;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;">Nonaktif</span>';

                const popupContent = `
                    <div style="min-width:220px;max-width:280px;font-family:inherit;">
                        <div style="background:linear-gradient(135deg,#334155,#0f172a);padding:12px 16px;border-radius:12px 12px 0 0;">
                            <p style="margin:0;color:white;font-size:13px;font-weight:700;line-height:1.3;">${umkm.nama_usaha}</p>
                            <p style="margin:4px 0 0;color:rgba(255,255,255,0.75);font-size:11px;">${umkm.kode_umkm}</p>
                        </div>
                        <div style="padding:12px 16px;">
                            <div style="display:flex;gap:6px;margin-bottom:10px;flex-wrap:wrap;">
                                ${statusBadge}
                            </div>
                            <table style="width:100%;border-collapse:collapse;font-size:12px;">
                                <tr>
                                    <td style="color:#94a3b8;padding:3px 0;">Pemilik</td>
                                    <td style="color:#1e293b;font-weight:600;padding:3px 0 3px 8px;">${umkm.nama_pemilik}</td>
                                </tr>
                                <tr>
                                    <td style="color:#94a3b8;padding:3px 0;">Kategori</td>
                                    <td style="color:#1e293b;font-weight:600;padding:3px 0 3px 8px;">${umkm.kategori || '-'}</td>
                                </tr>
                                <tr>
                                    <td style="color:#94a3b8;padding:3px 0;">Wilayah</td>
                                    <td style="color:#1e293b;font-weight:600;padding:3px 0 3px 8px;">
                                        ${umkm.kelurahan ? umkm.kelurahan + ', ' : ''}
                                        ${umkm.kecamatan ? 'Kec. ' + umkm.kecamatan : '-'}
                                    </td>
                                </tr>
                                ${umkm.alamat ? `<tr>
                                                <td style="color:#94a3b8;padding:3px 0;vertical-align:top;">Alamat</td>
                                                <td style="color:#1e293b;font-weight:600;padding:3px 0 3px 8px;">${umkm.alamat}</td>
                                            </tr>` : ''}
                            </table>
                            <a href="{{ route('umkm.show', '') }}/${umkm.uuid}"
                               style="display:block;margin-top:10px;background:#1e293b;color:white;text-align:center;padding:7px 12px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;">
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>`;

                const marker = L.marker([umkm.lat, umkm.lng], {
                        icon
                    })
                    .bindPopup(popupContent, {
                        className: 'umkm-popup',
                        maxWidth: 300
                    });
                marker.addTo(map);
                bounds.push([umkm.lat, umkm.lng]);
            });

            if (bounds.length > 1) {
                map.fitBounds(bounds, {
                    padding: [40, 40],
                    maxZoom: 8
                });
            }

            setTimeout(function() {
                map.invalidateSize();
            }, 300);
        });
    </script>
@endsection

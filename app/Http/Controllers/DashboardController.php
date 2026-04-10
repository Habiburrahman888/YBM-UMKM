<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Unit;
use App\Models\Umkm;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard - Overview sistem UMKM
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        if ($user->role === 'unit') {
            return $this->unitDashboard();
        }

        if ($user->role === 'umkm') {
            return $this->umkmDashboard();
        }

        // role lain (umkm, dll) redirect ke halaman lain
        return redirect()->route('umkm.dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    /**
     * Main dashboard method
     */
    private function adminDashboard()
    {
        // ===== STATISTICS CARDS =====
        $totalUsers = Users::count();
        $totalUnits = Unit::count();
        $unitAktif = Unit::where('is_active', true)->count();
        $unitNonaktif = Unit::where('is_active', false)->count();
        $totalUmkm = Umkm::count();
        $umkmAktif = Umkm::where('status', 'aktif')
            ->where(function ($q) {
                $q->whereNull('unit_id')
                    ->orWhereHas('unit', function ($uq) {
                        $uq->where('is_active', true);
                    });
            })->count();
        $totalKategori = Kategori::count();

        // ===== GROWTH METRICS =====
        $usersGrowth = $this->calculateGrowth(Users::class);
        $unitsGrowth = $this->calculateGrowth(Unit::class);
        $umkmGrowth = $this->calculateGrowth(Umkm::class);

        // ===== CHART DATA =====
        $grafikRegistrasi = $this->getRegistrasiChart();

        $statusUmkm = [
            'aktif' => $umkmAktif,
            'pending' => 0,
            'nonaktif' => $totalUmkm - $umkmAktif,
        ];

        $statusUnit = [
            'aktif' => Unit::where('is_active', true)->count(),
            'nonaktif' => Unit::where('is_active', false)->count(),
        ];

        // ===== DISTRIBUSI USERS BY ROLE =====
        $distribusiRole = Users::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get()
            ->mapWithKeys(fn($item) => [$item->role => $item->total]);

        // ===== UMKM TERBARU =====
        $umkmTerbaru = Umkm::with(['unit', 'user', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ===== UNIT LIST =====
        $unitList = Unit::with('user')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ===== AKTIVITAS TERBARU =====
        $aktivitasTerbaru = $this->getRecentActivities();

        // ===== TOP 5 PROVINSI =====
        $topProvinsi = $this->getTopProvinsi();

        // ===== STATISTIK HARI INI =====
        $today = Carbon::today();
        $umkmHariIni = Umkm::whereDate('created_at', $today)->count();
        $userHariIni = Users::whereDate('created_at', $today)->count();
        $verifikasiHariIni = Umkm::whereDate('verified_at', $today)->count();

        // ===== STATISTIK BULAN INI =====
        $startOfMonth = Carbon::now()->startOfMonth();
        $umkmBulanIni = Umkm::where('created_at', '>=', $startOfMonth)->count();
        $userBulanIni = Users::where('created_at', '>=', $startOfMonth)->count();

        // ===== TARGET PROGRESS =====
        $targetUmkmBulan = 50;
        $targetUserBulan = 30;
        $progressUmkm = min(100, ($umkmBulanIni / $targetUmkmBulan) * 100);
        $progressUser = min(100, ($userBulanIni / $targetUserBulan) * 100);

        // ===== SUMMARY =====
        $umkmSummary = [
            'total' => $totalUmkm,
            'aktif' => $umkmAktif,
            'nonaktif' => $totalUmkm - $umkmAktif,
            'verified' => Umkm::whereNotNull('verified_at')->count(),
            'unverified' => Umkm::whereNull('verified_at')->count(),
        ];

        $userVerification = [
            'verified' => Users::whereNotNull('email_verified_at')->count(),
            'unverified' => Users::whereNull('email_verified_at')->count(),
        ];

        $quickStats = [
            'inactive_units' => Unit::where('is_active', false)->count(),
            'unverified_emails' => $userVerification['unverified'],
            'umkm_no_user' => Umkm::whereNull('user_id')->count(),
        ];

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalUnits',
            'unitAktif',
            'unitNonaktif',
            'totalUmkm',
            'umkmAktif',
            'totalKategori',
            'usersGrowth',
            'unitsGrowth',
            'umkmGrowth',
            'grafikRegistrasi',
            'statusUmkm',
            'statusUnit',
            'distribusiRole',
            'topProvinsi',
            'umkmTerbaru',
            'unitList',
            'aktivitasTerbaru',
            'umkmHariIni',
            'userHariIni',
            'verifikasiHariIni',
            'umkmBulanIni',
            'userBulanIni',
            'progressUmkm',
            'progressUser',
            'targetUmkmBulan',
            'targetUserBulan',
            'umkmSummary',
            'userVerification',
            'quickStats'
        ));
    }

    /**
     * Calculate growth percentage (bulan ini vs bulan lalu)
     */
    private function calculateGrowth($model)
    {
        $thisMonth = $model::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->count();

        $lastMonth = $model::whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->count();

        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Get registration chart data (6 bulan terakhir)
     */
    private function getRegistrasiChart()
    {
        $labels = [];
        $umkmData = [];
        $userData = [];
        $unitData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y'); // Format: Jan 2025

            $umkmData[] = Umkm::whereBetween('created_at', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->count();

            $userData[] = Users::whereBetween('created_at', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->count();

            $unitData[] = Unit::whereBetween('created_at', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ])->count();
        }

        return [
            'labels' => $labels,
            'umkm' => $umkmData,
            'users' => $userData,
            'units' => $unitData,
        ];
    }

    /**
     * Get top 5 provinsi dengan UMKM terbanyak
     */
    private function getTopProvinsi()
    {
        $totalUmkm = Umkm::count();

        return Umkm::select('province_code', DB::raw('count(*) as total'))
            ->whereNotNull('province_code')
            ->groupBy('province_code')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($totalUmkm) {
                // Ambil nama provinsi dari tabel Laravolt Indonesia
                $province = DB::table(config('laravolt.indonesia.table_prefix', '') . 'provinces')
                    ->where('code', $item->province_code)
                    ->first();

                return [
                    'name' => $province->name ?? 'Unknown',
                    'code' => $item->province_code,
                    'total' => $item->total,
                    'percentage' => $this->calculatePercentage($item->total, $totalUmkm)
                ];
            });
    }

    /**
     * Get recent activities (timeline aktivitas sistem)
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // UMKM registrations (3 terbaru)
        $recentUmkm = Umkm::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentUmkm as $umkm) {
            $activities->push([
                'type' => 'umkm_register',
                'color' => 'blue',
                'title' => 'UMKM Baru Terdaftar',
                'description' => $umkm->nama_usaha . ' telah mendaftar',
                'user' => $umkm->nama_pemilik,
                'time' => $umkm->created_at->diffForHumans(),
                'timestamp' => $umkm->created_at,
            ]);
        }

        // Verifications (2 terbaru)
        $recentVerified = Umkm::with('verifiedBy')
            ->whereNotNull('verified_at')
            ->orderBy('verified_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($recentVerified as $umkm) {
            $activities->push([
                'type' => 'verification',
                'color' => 'green',
                'title' => 'UMKM Diverifikasi',
                'description' => $umkm->nama_usaha . ' telah diverifikasi',
                'user' => optional($umkm->verifiedBy)->username ?? 'YBM UMKM',
                'time' => $umkm->verified_at->diffForHumans(),
                'timestamp' => $umkm->verified_at,
            ]);
        }

        // Units (2 terbaru)
        $recentUnits = Unit::orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($recentUnits as $unit) {
            $activities->push([
                'type' => 'unit_created',
                'color' => 'purple',
                'title' => 'Unit Baru Dibuat',
                'description' => $unit->nama_unit . ' telah ditambahkan',
                'user' => $unit->admin_nama ?? 'Admin',
                'time' => $unit->created_at->diffForHumans(),
                'timestamp' => $unit->created_at,
            ]);
        }

        // Users (1 terbaru)
        $recentUsers = Users::orderBy('created_at', 'desc')
            ->limit(1)
            ->get();

        foreach ($recentUsers as $newUser) {
            $activities->push([
                'type' => 'user_registered',
                'color' => 'indigo',
                'title' => 'User Baru Terdaftar',
                'description' => 'User baru bergabung ke sistem',
                'user' => $newUser->username,
                'time' => $newUser->created_at->diffForHumans(),
                'timestamp' => $newUser->created_at,
            ]);
        }

        // Sort by timestamp descending
        $activities = $activities->sortByDesc('timestamp')->values();

        // Return max 8 activities
        return $activities->take(8);
    }

    private function calculatePercentage($value, $total)
    {
        if ($total == 0)
            return 0;
        return round(($value / $total) * 100, 1);
    }

    /**
     * Unit Dashboard - Overview untuk Admin Unit (Kepala Unit)
     */
    public function unitDashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'unit') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $unit = Unit::where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();

        // ===== BASE QUERY =====
        $baseQuery = Umkm::where('unit_id', $unit->id);

        // ===== STATISTICS =====
        $totalUmkm = (clone $baseQuery)->count();
        $umkmAktif = (clone $baseQuery)->where('status', 'aktif')->count();
        $umkmNonaktif = (clone $baseQuery)->where('status', 'nonaktif')->count();

        // ===== GROWTH METRICS =====
        $umkmGrowth = $this->calculateUnitGrowth($unit->id, Umkm::class);

        // ===== TOP KATEGORI (untuk pie chart) =====
        $topKategori = $this->getTopKategoriUnit($unit->id);

        // ===== GRAFIK REGISTRASI 6 BULAN =====
        $grafikRegistrasi = $this->getGrafikRegistrasiUnit($unit->id);

        // ===== DATA PETA =====
        $umkmMapData = $this->getUmkmMapData($unit->id);

        // ===== PRODUK, MODAL, REKENING =====
        $umkmIds = (clone $baseQuery)->pluck('id');

        $totalProduk = \App\Models\ProdukUmkm::whereIn('umkm_id', $umkmIds)->count();

        $totalModal = \App\Models\ModalUmkm::whereIn('umkm_id', $umkmIds)
            ->where('status', 'aktif')
            ->sum('nilai_modal');

        $umkmDenganRekening = \App\Models\UmkmRekening::whereIn('umkm_id', $umkmIds)
            ->distinct('umkm_id')
            ->count('umkm_id');

        return view('dashboard.unit', compact(
            'unit',
            'totalUmkm',
            'umkmAktif',
            'umkmNonaktif',
            'umkmGrowth',
            'topKategori',
            'grafikRegistrasi',
            'umkmMapData',
            'totalProduk',
            'totalModal',
            'umkmDenganRekening',
        ));
    }


    /**
     * Hitung growth UMKM dalam unit (bulan ini vs bulan lalu)
     */
    private function calculateUnitGrowth($unitId, $model)
    {
        $thisMonth = $model::where('unit_id', $unitId)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])->count();

        $lastMonth = $model::where('unit_id', $unitId)
            ->whereBetween('created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])->count();

        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Get top kategori UMKM dalam unit
     */
    private function getTopKategoriUnit($unitId)
    {
        $totalUmkm = Umkm::where('unit_id', $unitId)->count();

        return Umkm::select('kategori_id', DB::raw('count(*) as total'))
            ->where('unit_id', $unitId)
            ->whereNotNull('kategori_id')
            ->groupBy('kategori_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($totalUmkm) {
                $kategori = Kategori::find($item->kategori_id);

                return [
                    'nama' => $kategori->nama ?? 'Unknown',
                    'total' => $item->total,
                    'percentage' => $this->calculatePercentage($item->total, $totalUmkm),
                ];
            });
    }

    /**
     * Get data UMKM untuk ditampilkan di peta Leaflet
     * Koordinat berdasarkan kecamatan (district) dan kelurahan/desa (village)
     * menggunakan Nominatim OSM Geocoding API dengan cache Laravel
     */
    private function getUmkmMapData($unitId)
    {
        // Koordinat fallback per kode provinsi (2 digit) — digunakan jika geocoding gagal
        $provinceCoords = [
            '11' => [-4.0, 96.8],    // Aceh
            '12' => [2.0, 99.5],     // Sumatera Utara
            '13' => [-0.7, 101.5],   // Sumatera Barat
            '14' => [0.5, 101.4],    // Riau
            '15' => [-1.6, 103.6],   // Jambi
            '16' => [-3.3, 104.0],   // Sumatera Selatan
            '17' => [-3.8, 102.3],   // Bengkulu
            '18' => [-5.4, 105.2],   // Lampung
            '19' => [-2.1, 106.1],   // Bangka Belitung
            '21' => [0.9, 104.4],    // Kepulauan Riau
            '31' => [-6.2, 106.8],   // DKI Jakarta
            '32' => [-6.9, 107.6],   // Jawa Barat
            '33' => [-7.2, 110.0],   // Jawa Tengah
            '34' => [-7.8, 110.4],   // DI Yogyakarta
            '35' => [-7.5, 112.5],   // Jawa Timur
            '36' => [-6.4, 106.3],   // Banten
            '51' => [-8.5, 115.0],   // Bali
            '52' => [-8.6, 117.4],   // NTB
            '53' => [-9.0, 121.0],   // NTT
            '61' => [-0.1, 109.3],   // Kalimantan Barat
            '62' => [-1.7, 113.9],   // Kalimantan Tengah
            '63' => [-3.3, 115.3],   // Kalimantan Selatan
            '64' => [0.5, 116.4],    // Kalimantan Timur
            '65' => [3.0, 116.0],    // Kalimantan Utara
            '71' => [0.6, 124.0],    // Sulawesi Utara
            '72' => [-1.4, 121.4],   // Sulawesi Tengah
            '73' => [-5.1, 119.4],   // Sulawesi Selatan
            '74' => [-3.9, 122.5],   // Sulawesi Tenggara
            '75' => [0.6, 122.5],    // Gorontalo
            '76' => [-2.8, 119.2],   // Sulawesi Barat
            '81' => [-3.7, 128.2],   // Maluku
            '82' => [0.6, 127.4],    // Maluku Utara
            '91' => [-4.3, 138.1],   // Papua Barat
            '94' => [-4.3, 138.1],   // Papua
        ];

        $umkmList = Umkm::with(['kategori', 'district', 'village', 'city', 'province'])
            ->where('unit_id', $unitId)
            ->whereNotNull('province_code')
            ->select([
                'uuid',
                'nama_usaha',
                'nama_pemilik',
                'status',
                'kode_umkm',
                'province_code',
                'city_code',
                'district_code',
                'village_code',
                'alamat',
                'kategori_id',
                'verified_at',
            ])
            ->get();

        $mapped = $umkmList->map(function ($umkm) use ($provinceCoords) {
            $districtName = optional($umkm->district)->name;
            $villageName = optional($umkm->village)->name;
            $cityName = optional($umkm->city)->name;
            $provCode = $umkm->province_code;

            // ── Coba geocode berdasarkan wilayah ──────────────────────────
            $coords = null;

            // 1. Coba Desa/Kelurahan + Kecamatan + Kota
            if ($villageName && $districtName && $cityName) {
                $cacheKey = 'geocode_v2_vill_' . md5(strtolower($villageName . '_' . $districtName . '_' . $cityName));
                $coords = \Illuminate\Support\Facades\Cache::remember(
                    $cacheKey,
                    now()->addDays(30),
                    function () use ($villageName, $districtName, $cityName) {
                        return $this->geocodeByNominatim($villageName . ', Kecamatan ' . $districtName, $cityName);
                    }
                );
            }

            // 2. Coba Kecamatan + Kota (jika kelurahan gagal/tidak ada)
            if (!$coords && $districtName && $cityName) {
                $cacheKey = 'geocode_v2_dist_' . md5(strtolower($districtName . '_' . $cityName));
                $coords = \Illuminate\Support\Facades\Cache::remember(
                    $cacheKey,
                    now()->addDays(30),
                    function () use ($districtName, $cityName) {
                        return $this->geocodeByNominatim('Kecamatan ' . $districtName, $cityName);
                    }
                );
            }

            // 3. Fallback: hanya kota
            if (!$coords && $cityName) {
                $cacheKey = 'geocode_v2_city_' . md5(strtolower($cityName));
                $coords = \Illuminate\Support\Facades\Cache::remember(
                    $cacheKey,
                    now()->addDays(30),
                    function () use ($cityName) {
                        return $this->geocodeByNominatim(null, $cityName);
                    }
                );
            }

            // 4. Fallback Terakhir: koordinat provinsi 
            if (!$coords) {
                $provKey = substr((string) $provCode, 0, 2);
                $coords = $provinceCoords[$provKey] ?? [-2.5, 118.0];
            }

            // ── Tambahkan Jitter Deterministik ──────────────────────────────
            // Menggunakan hash UUID agar pergeseran tetap sama saat refresh (tidak acak/pindah-pindah)
            // tapi tetap bergeser sedikit jika titiknya sama (agar tidak tumpuk sempurna)
            $seed = crc32($umkm->uuid);
            $offsetX = (($seed % 100) - 50) / 10000; // range +/- 0.005
            $offsetY = ((floor($seed / 100) % 100) - 50) / 10000;

            return [
                'uuid' => $umkm->uuid,
                'nama_usaha' => $umkm->nama_usaha,
                'nama_pemilik' => $umkm->nama_pemilik,
                'kode_umkm' => $umkm->kode_umkm,
                'status' => $umkm->status,
                'verified' => !is_null($umkm->verified_at),
                'kategori' => optional($umkm->kategori)->nama ?? '-',
                'alamat' => $umkm->alamat,
                'kecamatan' => $districtName,
                'kelurahan' => $villageName,
                'kota' => $cityName,
                'lat' => $coords[0] + $offsetX,
                'lng' => $coords[1] + $offsetY,
            ];
        })->values();

        return $mapped;
    }

    /**
     * Geocode nama kecamatan + kota ke koordinat lat/lng
     * menggunakan Nominatim OpenStreetMap API (gratis, tanpa API key)
     *
     * @param  string|null $districtName  nama kecamatan
     * @param  string      $cityName      nama kabupaten/kota
     * @return array|null  [lat, lng] atau null jika gagal
     */
    private function geocodeByNominatim(?string $districtName, string $cityName): ?array
    {
        try {
            $query = $districtName
                ? "Kecamatan {$districtName}, {$cityName}, Indonesia"
                : "{$cityName}, Indonesia";

            $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
                'q' => $query,
                'format' => 'json',
                'limit' => 1,
                'countrycodes' => 'id',
                'addressdetails' => 0,
            ]);

            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: YBM-UMKM-App/1.0\r\n",
                    'timeout' => 5,
                ],
            ];

            $context = stream_context_create($opts);
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return null;
            }

            $data = json_decode($response, true);

            if (!empty($data[0]['lat']) && !empty($data[0]['lon'])) {
                return [(float) $data[0]['lat'], (float) $data[0]['lon']];
            }
        } catch (\Throwable $e) {
            // Diam-diam gagal, gunakan fallback
        }

        return null;
    }

    /**
     * Get grafik registrasi UMKM per bulan untuk unit tertentu (6 bulan terakhir)
     */
    private function getGrafikRegistrasiUnit($unitId)
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');
            $data[] = Umkm::where('unit_id', $unitId)
                ->whereBetween('created_at', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth(),
                ])->count();
        }

        return compact('labels', 'data');
    }

    /**
     * UMKM Dashboard - Overview untuk pemilik UMKM
     */
    public function umkmDashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'umkm') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $umkm = Umkm::with(['unit', 'kategori', 'produkUmkm'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        // ===== STATUS & VERIFIKASI =====
        $isVerified = !is_null($umkm->verified_at);
        $statusUmkm = $umkm->status;

        // ===== INFORMASI PRODUK (hasMany) =====
        $startOfMonth = Carbon::now()->startOfMonth();
        $produk = $umkm->produkUmkm; // Returns a Collection

        $totalProduk = $produk->count();
        $produkTerbaru = $produk->sortByDesc('created_at')->take(5);
        $produkHariIni = $produk->filter(fn($p) => $p->created_at->isToday())->count();
        $produkBulanIni = $produk->filter(fn($p) => $p->created_at->gte($startOfMonth))->count();

        // ===== INFORMASI PESANAN =====
        $pesanan = \App\Models\Pesanan::where('umkm_id', $umkm->id)->get();
        $totalPesanan = $pesanan->count();
        $pesananPending = $pesanan->where('status', 'pending')->count();
        $pesananTerbaru = $pesanan->sortByDesc('created_at')->take(5);
        $pesananHariIni = $pesanan->filter(fn($p) => $p->created_at->isToday())->count();

        // ===== QUICK STATS =====
        $quickStats = [
            'total_produk' => $totalProduk,
            'produk_hari_ini' => $produkHariIni,
            'total_pesanan' => $totalPesanan,
            'pesanan_pending' => $pesananPending,
            'pesanan_hari_ini' => $pesananHariIni,
            'status' => $statusUmkm,
            'is_verified' => $isVerified,
        ];

        // ===== SUMMARY =====
        $umkmSummary = [
            'nama_usaha' => $umkm->nama_usaha,
            'nama_pemilik' => $umkm->nama_pemilik,
            'status' => $statusUmkm,
            'verified' => $isVerified,
            'verified_at' => $umkm->verified_at,
            'tanggal_bergabung' => $umkm->tanggal_bergabung,
            'unit' => $umkm->unit?->nama_unit ?? '-',
            'kategori' => $umkm->kategori?->nama ?? '-',
            'total_produk' => $totalProduk,
            'total_pesanan' => $totalPesanan,
            'pesanan_pending' => $pesananPending,
        ];

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
        ];

        return view('dashboard.umkm', compact(
            'umkm',
            'isVerified',
            'statusUmkm',
            'totalProduk',
            'produkTerbaru',
            'produkHariIni',
            'produkBulanIni',
            'totalPesanan',
            'pesananPending',
            'pesananTerbaru',
            'pesananHariIni',
            'quickStats',
            'umkmSummary',
            'breadcrumbs',
        ));
    }
}

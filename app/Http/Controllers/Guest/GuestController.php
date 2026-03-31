<?php

namespace App\Http\Controllers\Guest;

use App\Models\Kategori;
use App\Models\ProdukUmkm;
use App\Models\SettingAdmin;
use App\Models\Sosmed;
use App\Models\Umkm;
use App\Models\Pesanan;
use App\Models\PesananItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuestController extends Controller
{
    public function checkout($uuid)
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();
        $produk   = ProdukUmkm::with('umkm.rekening')->where('uuid', $uuid)->firstOrFail();
        $umkm_produk = ProdukUmkm::where('umkm_id', $produk->umkm_id)->get();

        return view('guest.checkout', compact('setting', 'sosmed', 'kategori', 'produk', 'umkm_produk'));
    }

    public function storeCheckout(Request $request, $uuid)
    {
        $produk = ProdukUmkm::with('umkm')->where('uuid', $uuid)->firstOrFail();
        $umkm = $produk->umkm;

        $request->validate([
            'nama_pembeli'    => 'required|string|max:255',
            'telepon_pembeli' => 'required|string|max:20',
            'alamat_pembeli'  => 'required|string',
            'items'           => 'required|array|min:1',
            'items.*.id'      => 'required|exists:produk_umkm,id',
            'items.*.jumlah'  => 'required|integer|min:1',
            'bukti_transfer'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $totalHarga = 0;
        $orderItems = [];
        foreach ($request->items as $item) {
            $p = ProdukUmkm::find($item['id']);
            $subtotal = $p->harga * $item['jumlah'];
            $totalHarga += $subtotal;
            $orderItems[] = [
                'produk_id' => $p->id,
                'nama' => $p->nama_produk,
                'jumlah' => $item['jumlah'],
                'harga' => $p->harga,
                'subtotal' => $subtotal
            ];
        }

        $pesanan = Pesanan::create([
            'umkm_id'         => $produk->umkm_id,
            'produk_id'       => $orderItems[0]['produk_id'],
            'nama_pembeli'    => $request->nama_pembeli,
            'telepon_pembeli' => $request->telepon_pembeli,
            'alamat_pembeli'  => $request->alamat_pembeli,
            'jumlah'          => $orderItems[0]['jumlah'],
            'total_harga'     => $totalHarga,
            'bukti_transfer'  => $path,
            'catatan'         => $request->catatan,
            'status'          => 'pending',
        ]);

        foreach ($orderItems as $item) {
            PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'produk_id'  => $item['produk_id'],
                'jumlah'     => $item['jumlah'],
                'harga'      => $item['harga'],
                'subtotal'   => $item['subtotal']
            ]);
        }

        $targetWA = preg_replace('/[^0-9]/', '', $umkm->telepon);
        if (str_starts_with($targetWA, '0')) {
            $targetWA = '62' . substr($targetWA, 1);
        }

        $message = "KONFIRMASI PESANAN (WEBSITE UMKM)\n\n";
        $message .= "Halo " . $umkm->nama_usaha . ",\n";
        $message .= "Saya ingin mengonfirmasi pesanan saya yang baru saja dibuat di website:\n\n";

        foreach ($orderItems as $item) {
            $message .= "Item: " . $item['nama'] . " (x" . $item['jumlah'] . ") - Rp " . number_format($item['subtotal'], 0, ',', '.') . "\n";
        }

        $message .= "\nTotal Harga: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n";
        $message .= "Nama Pembeli: " . $request->nama_pembeli . "\n";
        $message .= "Alamat: " . $request->alamat_pembeli . "\n";

        if ($request->catatan) {
            $message .= "Catatan: " . $request->catatan . "\n";
        }

        $message .= "\n\nHalo UMKM, Saya telah mengunggah bukti transfer di sistem. Mohon segera diproses ya. Terimakasih!\n\nLacak Pesanan Saya: " . route('guest.cek-pesanan', ['id_pesanan' => $pesanan->uuid, 'telepon' => $pesanan->telepon_pembeli]);

        $waLink = "https://wa.me/" . $targetWA . "?text=" . urlencode($message);

        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();

        return view('guest.checkout-success', compact('waLink', 'pesanan', 'setting', 'sosmed', 'kategori'));
    }

    public function beranda()
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::withCount('umkm')->get();

        $produkQuery = ProdukUmkm::with(['umkm.kategori', 'umkm.city'])
            ->whereHas('umkm', fn($q) => $q->where('status', 'aktif'));

        if (request()->filled('province')) {
            $produkQuery->whereHas('umkm', fn($q) => $q->where('province_code', request('province')));
        }

        if (request()->filled('city')) {
            $produkQuery->whereHas('umkm', fn($q) => $q->where('city_code', request('city')));
        }

        if (request()->filled('kategori')) {
            $produkQuery->whereHas('umkm', fn($q) => $q->where('kategori_id', request('kategori')));
        }

        $produkQuery->latest();

        $produk = $produkQuery->take(8)->get();
        $umkm   = Umkm::where('status', 'aktif')->latest()->take(6)->get();

        $provinceCoords = [
            '11' => [-4.0, 96.8],
            '12' => [2.0, 99.5],
            '13' => [-0.7, 101.5],
            '14' => [0.5, 101.4],
            '15' => [-1.6, 103.6],
            '16' => [-3.3, 104.0],
            '17' => [-3.8, 102.3],
            '18' => [-5.4, 105.2],
            '19' => [-2.1, 106.1],
            '21' => [0.9, 104.4],
            '31' => [-6.2, 106.8],
            '32' => [-6.9, 107.6],
            '33' => [-7.2, 110.0],
            '34' => [-7.8, 110.4],
            '35' => [-7.5, 112.5],
            '36' => [-6.4, 106.3],
            '51' => [-8.5, 115.0],
            '52' => [-8.6, 117.4],
            '53' => [-9.0, 121.0],
            '61' => [-0.1, 109.3],
            '62' => [-1.7, 113.9],
            '63' => [-3.3, 115.3],
            '64' => [0.5, 116.4],
            '65' => [3.0, 116.0],
            '71' => [0.6, 124.0],
            '72' => [-1.4, 121.4],
            '73' => [-5.1, 119.4],
            '74' => [-3.9, 122.5],
            '75' => [0.6, 122.5],
            '76' => [-2.8, 119.2],
            '81' => [-3.7, 128.2],
            '82' => [0.6, 127.4],
            '91' => [-4.3, 138.1],
            '94' => [-4.3, 138.1],
        ];

        $allUmkm = Umkm::with(['city', 'province'])
            ->where('status', 'aktif')
            ->whereNotNull('province_code')
            ->whereNotNull('city_code')
            ->get(['uuid', 'nama_usaha', 'province_code', 'city_code', 'alamat', 'status', 'latitude', 'longitude']);

        $cleanCityName = function (string $raw): string {
            $prefixes = ['KOTA ADMINISTRASI ', 'KAB. ADMINISTRASI ', 'KABUPATEN ADMINISTRASI ', 'KOTA ', 'KABUPATEN ', 'KAB. ', 'KAB '];
            $upper = strtoupper($raw);
            foreach ($prefixes as $p) {
                if (str_starts_with($upper, $p)) {
                    return ucwords(strtolower(substr($raw, strlen($p))));
                }
            }
            return ucwords(strtolower($raw));
        };

        $cityUmkmList = [];
        $cityCoords   = [];

        foreach ($allUmkm as $u) {
            $cityCode = $u->city_code;
            $cityName = optional($u->city)->name ?? 'Tidak Diketahui';

            if (!isset($cityUmkmList[$cityCode])) {
                $cityUmkmList[$cityCode] = [
                    'city_name' => $cleanCityName($cityName),
                    'city_code' => $cityCode,
                    'umkm'      => []
                ];

                $coords = null;
                if ($u->city && $u->city->meta) {
                    $lat = $u->city->meta['lat'] ?? $u->city->meta['latitude'] ?? null;
                    $lng = $u->city->meta['long'] ?? $u->city->meta['longitude'] ?? null;
                    if ($lat && $lng) $coords = [(float) $lat, (float) $lng];
                }
                if (!$coords && $u->latitude && $u->longitude) {
                    $coords = [(float) $u->latitude, (float) $u->longitude];
                }
                if (!$coords) {
                    $provKey = substr((string) ($u->province_code), 0, 2);
                    $coords  = $provinceCoords[$provKey] ?? [-2.5, 118.0];
                }
                $cityCoords[$cityCode] = $coords;
            }

            $cityUmkmList[$cityCode]['umkm'][] = [
                'uuid'       => $u->uuid,
                'nama_usaha' => $u->nama_usaha,
                'alamat'     => $u->alamat,
                'status'     => $u->status,
            ];
        }

        $umkmMap = collect($cityUmkmList)->map(function ($cityData) use ($cityCoords) {
            $coords = $cityCoords[$cityData['city_code']];
            return [
                'city_name' => $cityData['city_name'],
                'count'     => count($cityData['umkm']),
                'latitude'  => $coords[0],
                'longitude' => $coords[1],
                'umkm_list' => $cityData['umkm'],
            ];
        })->values();

        $totalUmkm     = Umkm::where('status', 'aktif')->count();
        $totalProduk   = ProdukUmkm::count();
        $totalKategori = Kategori::count();

        $provinces_filter = \Laravolt\Indonesia\Models\Province::whereIn('code', Umkm::where('status', 'aktif')->pluck('province_code')->filter()->unique())->get();

        $cities_query = \Laravolt\Indonesia\Models\City::whereIn('code', Umkm::where('status', 'aktif')->pluck('city_code')->filter()->unique());
        if (request()->filled('province')) {
            $cities_query->where('province_code', request('province'));
        }
        $cities_filter = $cities_query->get();

        // Unified location selector
        $grouped_locations = \Laravolt\Indonesia\Models\Province::with('cities')->get();

        $selectedCityCoords = null;
        if (request()->filled('city')) {
            $selCity = \Laravolt\Indonesia\Models\City::where('code', request('city'))->first();
            if ($selCity && $selCity->meta) {
                $lat = $selCity->meta['lat'] ?? $selCity->meta['latitude'] ?? null;
                $lng = $selCity->meta['long'] ?? $selCity->meta['longitude'] ?? null;
                if ($lat && $lng) {
                    $selectedCityCoords = [(float) $lat, (float) $lng];
                }
            }
        }

        return view('guest.beranda', compact(
            'setting',
            'sosmed',
            'kategori',
            'produk',
            'umkm',
            'umkmMap',
            'totalUmkm',
            'totalProduk',
            'totalKategori',
            'provinces_filter',
            'cities_filter',
            'selectedCityCoords',
            'grouped_locations'
        ));
    }

    public function tentang()
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();

        return view('guest.tentang', compact('setting', 'sosmed', 'kategori'));
    }

    public function katalog(Request $request)
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();

        $query = ProdukUmkm::with(['umkm.kategori', 'umkm.city'])
            ->whereHas('umkm', fn($q) => $q->where('status', 'aktif'));

        if ($request->filled('province')) {
            $query->whereHas('umkm', fn($q) => $q->where('province_code', $request->province));
        }

        if ($request->filled('city')) {
            $query->whereHas('umkm', fn($q) => $q->where('city_code', $request->city));
        }
        $query->latest();

        if ($request->filled('kategori')) {
            $query->whereHas('umkm', fn($q) => $q->where('kategori_id', $request->kategori));
        }
        if ($request->filled('cari')) {
            $query->where('nama_produk', 'like', '%' . $request->cari . '%');
        }

        $produk = $query->paginate(12)->withQueryString();

        $provinces_filter = \Laravolt\Indonesia\Models\Province::whereIn('code', Umkm::where('status', 'aktif')->pluck('province_code')->filter()->unique())->get();

        $cities_query = \Laravolt\Indonesia\Models\City::whereIn('code', Umkm::where('status', 'aktif')->pluck('city_code')->filter()->unique());
        if ($request->filled('province')) {
            $cities_query->where('province_code', $request->province);
        }
        $cities_filter = $cities_query->get();

        // Unified location selector
        $grouped_locations = \Laravolt\Indonesia\Models\Province::with('cities')->get();

        return view('guest.katalog', compact('setting', 'sosmed', 'kategori', 'produk', 'provinces_filter', 'cities_filter', 'grouped_locations'));
    }

    public function detailProduk($uuid)
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();
        $produk   = ProdukUmkm::with(['umkm.kategori', 'umkm.city', 'umkm.district'])->where('uuid', $uuid)->firstOrFail();
        $related  = ProdukUmkm::with(['umkm.city', 'umkm.kategori'])
            ->where('umkm_id', $produk->umkm_id)
            ->where('uuid', '!=', $uuid)
            ->take(4)
            ->get();

        return view('guest.detail-produk', compact('setting', 'sosmed', 'kategori', 'produk', 'related'));
    }

    public function umkm(Request $request)
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();

        $query = Umkm::with(['kategori', 'province', 'city', 'district', 'village'])->withCount('produkUmkm')->where('status', 'aktif');

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->filled('cari')) {
            $query->where('nama_usaha', 'like', '%' . $request->cari . '%');
        }

        $provinces_filter = \Laravolt\Indonesia\Models\Province::whereIn('code', Umkm::where('status', 'aktif')->pluck('province_code')->filter()->unique())->get();

        $cities_query = \Laravolt\Indonesia\Models\City::whereIn('code', Umkm::where('status', 'aktif')->pluck('city_code')->filter()->unique());
        if ($request->filled('province')) {
            $query->where('province_code', $request->province);
            $cities_query->where('province_code', $request->province);
        }
        if ($request->filled('city')) {
            $query->where('city_code', $request->city);
        }
        
        $cities_filter = $cities_query->get();

        $umkm = $query->latest()->paginate(12)->withQueryString();

        // Unified location selector
        $grouped_locations = \Laravolt\Indonesia\Models\Province::with('cities')->get();

        return view('guest.umkm', compact('setting', 'sosmed', 'kategori', 'umkm', 'provinces_filter', 'cities_filter', 'grouped_locations'));
    }

    public function nearestLocation(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;

        if (!$lat || !$lng) {
            return redirect()->back()->with('error', 'Koordinat tidak valid');
        }

        // Find nearest city from active cities (with UMKM) for better relevance
        // Or all cities if desired. Let's use cities that have active UMKM first.
        $activeCityCodes = Umkm::where('status', 'aktif')->pluck('city_code')->filter()->unique();
        $cities = \Laravolt\Indonesia\Models\City::whereIn('code', $activeCityCodes)->get();

        if ($cities->isEmpty()) {
            // Fallback to all cities if no active UMKM
            $cities = \Laravolt\Indonesia\Models\City::whereNotNull('meta')->take(100)->get();
        }

        $nearestCity = null;
        $minDistance = INF;

        foreach ($cities as $city) {
            if (!$city->meta) continue;
            
            $cityLat = $city->meta['lat'] ?? $city->meta['latitude'] ?? null;
            $cityLng = $city->meta['long'] ?? $city->meta['longitude'] ?? null;

            if (!$cityLat || !$cityLng) continue;

            // Simple distance (Euclidean is enough for "closest")
            $dist = sqrt(pow($lat - $cityLat, 2) + pow($lng - $cityLng, 2));

            if ($dist < $minDistance) {
                $minDistance = $dist;
                $nearestCity = $city;
            }
        }

        if ($nearestCity) {
            $params = array_merge(request()->except(['lat', 'lng', 'province', 'city']), [
                'province' => $nearestCity->province_code,
                'city' => $nearestCity->code
            ]);
            
            $url = url()->previous();
            $baseUrl = strtok($url, '?');
            
            // Build the final URL with parameters
            $finalUrl = $baseUrl . '?' . http_build_query($params);
            
            // If we are on home page, we want the anchor
            if (str_contains($url, 'beranda') || $url == url('/')) {
                $finalUrl .= '#produk';
            }

            return redirect()->to($finalUrl);
        }

        return redirect()->back();
    }

    public function detailUmkm($uuid)
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();
        $umkm     = Umkm::with(['kategori', 'city', 'district', 'village', 'produkUmkm.umkm.kategori', 'produkUmkm.umkm.city'])->where('uuid', $uuid)->firstOrFail();

        return view('guest.detail-umkm', compact('setting', 'sosmed', 'kategori', 'umkm'));
    }
    public function cekPesanan()
    {
        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();

        return view('guest.cek-pesanan', compact('setting', 'sosmed', 'kategori'));
    }

    public function searchPesanan(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|string',
            'telepon'    => 'required|string',
        ]);

        $pesanan = Pesanan::with(['umkm', 'items.produk'])
            ->where('uuid', $request->id_pesanan)
            ->where('telepon_pembeli', $request->telepon)
            ->first();

        if (!$pesanan) {
            return back()->with('error', 'Pesanan tidak ditemukan. Pastikan ID Pesanan dan Nomor Telepon benar.');
        }

        $setting  = SettingAdmin::first();
        $sosmed   = Sosmed::first();
        $kategori = Kategori::all();

        return view('guest.cek-pesanan', compact('setting', 'sosmed', 'kategori', 'pesanan'));
    }
}
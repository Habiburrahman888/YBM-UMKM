<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Users;
use App\Mail\UnitCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('user');

        // Search
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_unit', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('kode_unit', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('admin_nama', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('admin_email', 'LIKE', '%' . $request->q . '%')
                  // ✅ TAMBAHAN: Search berdasarkan user
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('username', 'LIKE', '%' . $request->q . '%')
                               ->orWhere('email', 'LIKE', '%' . $request->q . '%');
                  });
            });
        }

        // Filter by provinsi
        if ($request->filled('provinsi')) {
            $query->where('provinsi_kode', $request->provinsi);
        }

        // Filter by kota
        if ($request->filled('kota')) {
            $query->where('kota_kode', $request->kota);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $units = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Data untuk filter
        $provinces = Province::orderBy('name')->get();
        $cities = collect();
        
        if ($request->filled('provinsi')) {
            $cities = City::where('province_code', $request->provinsi)->orderBy('name')->get();
        }

        $breadcrumbs = [
            ['name' => 'Kelola Unit', 'url' => route('unit.index')]
        ];

        return view('unit.index', compact('units', 'breadcrumbs', 'provinces', 'cities'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        
        // ✅ TAMBAHAN: Ambil user yang belum punya unit (role = unit)
        $availableUsers = Users::role('unit')
            ->doesntHave('unit') // User yang belum punya unit
            ->active()
            ->verified()
            ->orderBy('email')
            ->get();

        $breadcrumbs = [
            ['name' => 'Kelola Unit', 'url' => route('unit.index')],
            ['name' => 'Tambah Unit', 'url' => route('unit.create')]
        ];

        return view('unit.create', compact('breadcrumbs', 'provinces', 'availableUsers')); // ✅ PASS availableUsers
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id|unique:units,user_id',
            'admin_nama'        => 'nullable|string|max:255',
            'admin_telepon'     => 'nullable|string|max:20',
            'admin_email'       => 'nullable|email|max:255',
            'admin_foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nama_unit'         => 'required|string|max:255|unique:units,nama_unit',
            'kode_unit'         => 'required|string|max:50|unique:units,kode_unit',
            'logo'              => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'provinsi_kode'     => 'nullable|exists:indonesia_provinces,code',
            'kota_kode'         => 'nullable|exists:indonesia_cities,code',
            'kecamatan_kode'    => 'nullable|exists:indonesia_districts,code',
            'kelurahan_kode'    => 'nullable|exists:indonesia_villages,code',
            'kode_pos'          => 'nullable|string|max:10',
            'telepon'           => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'deskripsi'         => 'nullable|string',
            'alamat'            => 'required|string',
            'is_active'         => 'nullable|boolean',
        ], [
            'user_id.required' => 'User harus dipilih',
            'user_id.exists'   => 'User tidak ditemukan',
            'user_id.unique'   => 'User sudah memiliki unit',
        ]);
    
        $data = [
            'user_id'           => $request->user_id,
            'admin_nama'        => $request->admin_nama,
            'admin_telepon'     => $request->admin_telepon,
            'admin_email'       => $request->admin_email,
            'nama_unit'         => $request->nama_unit,
            'kode_unit'         => strtoupper($request->kode_unit),
            'provinsi_kode'     => $request->provinsi_kode,
            'kota_kode'         => $request->kota_kode,
            'kecamatan_kode'    => $request->kecamatan_kode,
            'kelurahan_kode'    => $request->kelurahan_kode,
            'kode_pos'          => $request->kode_pos,
            'telepon'           => $request->telepon,
            'unit_email'        => $request->email,
            'deskripsi'         => $request->deskripsi,
            'alamat'            => $request->alamat,
            'is_active'         => $request->boolean('is_active', true),
        ];
    
        // Nama wilayah (denormalized)
        if ($request->provinsi_kode) {
            $provinsi = Province::where('code', $request->provinsi_kode)->first();
            $data['provinsi_nama'] = $provinsi?->name;
        }
        if ($request->kota_kode) {
            $kota = City::where('code', $request->kota_kode)->first();
            $data['kota_nama'] = $kota?->name;
        }
        if ($request->kecamatan_kode) {
            $kecamatan = District::where('code', $request->kecamatan_kode)->first();
            $data['kecamatan_nama'] = $kecamatan?->name;
        }
        if ($request->kelurahan_kode) {
            $kelurahan = Village::where('code', $request->kelurahan_kode)->first();
            $data['kelurahan_nama'] = $kelurahan?->name;
        }
    
        // Upload admin foto
        if ($request->hasFile('admin_foto')) {
            $data['admin_foto'] = $request->file('admin_foto')->store('units/admin', 'public');
        }
    
        // Upload logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('units/logos', 'public');
        }
    
        // ── Buat unit ──────────────────────────────────────────────────────────────
        $unit = Unit::create($data);
    
        // ── Set password default untuk user unit ──────────────────────────────────
        $defaultPassword = '12345678';
        $userUnit = Users::find($request->user_id);
    
        if ($userUnit) {
            $userUnit->update([
                'password' => Hash::make($defaultPassword),
            ]);
    
            // ── Kirim email notifikasi ─────────────────────────────────────────────
            try {
                Mail::to($userUnit->email)
                    ->send(new UnitCreatedMail($unit, $userUnit->email, $defaultPassword));
            } catch (\Exception $e) {
                // Email gagal terkirim — jangan gagalkan proses utama,
                // cukup log agar bisa dicek di storage/logs/laravel.log
                \Log::error('Gagal kirim email unit created: ' . $e->getMessage(), [
                    'unit_id' => $unit->id,
                    'user_id' => $userUnit->id,
                ]);
            }
        }
    
        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit berhasil ditambahkan. Email notifikasi telah dikirim ke ' . ($userUnit?->email ?? '-'));
    }

    public function edit($uuid)
    {
        $unit = Unit::with('user')->where('uuid', $uuid)->first(); // ✅ EAGER LOAD user

        if (!$unit) {
            return redirect()->route('unit.index')
                ->with('error', 'Data unit tidak ditemukan!');
        }

        $provinces = Province::orderBy('name')->get();
        $cities = collect();
        $districts = collect();
        $villages = collect();

        if ($unit->provinsi_kode) {
            $cities = City::where('province_code', $unit->provinsi_kode)->orderBy('name')->get();
        }
        
        if ($unit->kota_kode) {
            $districts = District::where('city_code', $unit->kota_kode)->orderBy('name')->get();
        }
        
        if ($unit->kecamatan_kode) {
            $villages = Village::where('district_code', $unit->kecamatan_kode)->orderBy('name')->get();
        }

        // ✅ TAMBAHAN: Ambil user yang belum punya unit ATAU user ini
        $availableUsers = Users::role('unit')
            ->where(function($q) use ($unit) {
                $q->doesntHave('unit')
                  ->orWhere('id', $unit->user_id);
            })
            ->active()
            ->verified()
            ->orderBy('email')
            ->get();

        $breadcrumbs = [
            ['name' => 'Kelola Unit', 'url' => route('unit.index')],
            ['name' => 'Edit Unit', 'url' => route('unit.edit', $uuid)]
        ];

        return view('unit.edit', compact('unit', 'breadcrumbs', 'provinces', 'cities', 'districts', 'villages', 'availableUsers')); // ✅ PASS availableUsers
    }

    public function update(Request $request, $uuid)
    {
        $unit = Unit::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            // ✅ TAMBAHAN: Validasi user_id (ignore unit ini)
            'user_id'           => [
                'required',
                'exists:users,id',
                Rule::unique('units', 'user_id')->ignore($unit->id)
            ],
            
            // Data Admin
            'admin_nama'        => 'nullable|string|max:255',
            'admin_telepon'     => 'nullable|string|max:20',
            'admin_email'       => 'nullable|email|max:255',
            'admin_foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            
            // Data Unit
            'nama_unit'         => ['required', 'string', 'max:255', Rule::unique('units', 'nama_unit')->ignore($unit->id)],
            'kode_unit'         => ['required', 'string', 'max:50', Rule::unique('units', 'kode_unit')->ignore($unit->id)],
            'logo'              => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            
            // Wilayah
            'provinsi_kode'     => 'nullable|exists:indonesia_provinces,code',
            'kota_kode'         => 'nullable|exists:indonesia_cities,code',
            'kecamatan_kode'    => 'nullable|exists:indonesia_districts,code',
            'kelurahan_kode'    => 'nullable|exists:indonesia_villages,code',
            
            // Kontak
            'kode_pos'          => 'nullable|string|max:10',
            'telepon'           => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'deskripsi'         => 'nullable|string',
            'alamat'            => 'required|string',
            'is_active'         => 'nullable|boolean',
        ], [
            'user_id.required' => 'User harus dipilih',
            'user_id.exists'   => 'User tidak ditemukan',
            'user_id.unique'   => 'User sudah memiliki unit lain',
        ]);

        $data = [
            'user_id'           => $request->user_id, // ✅ TAMBAHKAN
            'admin_nama'        => $request->admin_nama,
            'admin_telepon'     => $request->admin_telepon,
            'admin_email'       => $request->admin_email,
            'nama_unit'         => $request->nama_unit,
            'kode_unit'         => strtoupper($request->kode_unit),
            'provinsi_kode'     => $request->provinsi_kode,
            'kota_kode'         => $request->kota_kode,
            'kecamatan_kode'    => $request->kecamatan_kode,
            'kelurahan_kode'    => $request->kelurahan_kode,
            'kode_pos'          => $request->kode_pos,
            'telepon'           => $request->telepon,
            'unit_email'        => $request->email,
            'deskripsi'         => $request->deskripsi,
            'alamat'            => $request->alamat,
            'is_active'         => $request->boolean('is_active', true),
        ];

        // Get nama wilayah (denormalized)
        if ($request->provinsi_kode) {
            $provinsi = Province::where('code', $request->provinsi_kode)->first();
            $data['provinsi_nama'] = $provinsi ? $provinsi->name : null;
        } else {
            $data['provinsi_nama'] = null;
        }
        
        if ($request->kota_kode) {
            $kota = City::where('code', $request->kota_kode)->first();
            $data['kota_nama'] = $kota ? $kota->name : null;
        } else {
            $data['kota_nama'] = null;
        }
        
        if ($request->kecamatan_kode) {
            $kecamatan = District::where('code', $request->kecamatan_kode)->first();
            $data['kecamatan_nama'] = $kecamatan ? $kecamatan->name : null;
        } else {
            $data['kecamatan_nama'] = null;
        }
        
        if ($request->kelurahan_kode) {
            $kelurahan = Village::where('code', $request->kelurahan_kode)->first();
            $data['kelurahan_nama'] = $kelurahan ? $kelurahan->name : null;
        } else {
            $data['kelurahan_nama'] = null;
        }

        // Upload admin foto baru
        if ($request->hasFile('admin_foto')) {
            // Hapus foto lama
            if ($unit->admin_foto) {
                Storage::disk('public')->delete($unit->admin_foto);
            }
            $data['admin_foto'] = $request->file('admin_foto')->store('units/admin', 'public');
        }

        // Hapus admin foto jika diminta
        if ($request->boolean('remove_admin_foto') && $unit->admin_foto) {
            Storage::disk('public')->delete($unit->admin_foto);
            $data['admin_foto'] = null;
        }

        // Upload logo baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama
            if ($unit->logo) {
                Storage::disk('public')->delete($unit->logo);
            }
            $data['logo'] = $request->file('logo')->store('units/logos', 'public');
        }

        // Hapus logo jika diminta
        if ($request->boolean('remove_logo') && $unit->logo) {
            Storage::disk('public')->delete($unit->logo);
            $data['logo'] = null;
        }

        $unit->update($data);

        // ✅ TAMBAHAN: Cascade status ke UMKM & User jika unit dinonaktifkan
        if ($unit->is_active === false) {
            // Nonaktifkan semua UMKM di bawah unit ini
            $unit->umkm()->update(['status' => 'nonaktif']);
            
            // Nonaktifkan user unit 
            if ($unit->user) {
                $unit->user->update(['is_active' => false]);
            }
            
            // Nonaktifkan semua user UMKM di bawah unit ini
            $umkmUserIds = $unit->umkm()->pluck('user_id');
            Users::whereIn('id', $umkmUserIds)->update(['is_active' => false]);
        } elseif ($unit->is_active === true) {
            // Jika unit diaktifkan kembali, aktifkan user unitnya
            if ($unit->user) {
                $unit->user->update(['is_active' => true]);
            }
        }

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit berhasil diupdate');
    }

    public function destroy($uuid)
    {
        $unit = Unit::where('uuid', $uuid)->firstOrFail();

        // Hapus admin foto
        if ($unit->admin_foto) {
            Storage::disk('public')->delete($unit->admin_foto);
        }

        // Hapus logo
        if ($unit->logo) {
            Storage::disk('public')->delete($unit->logo);
        }

        $unit->delete();

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit berhasil dihapus');
    }

    /**
     * Toggle unit active status
     */
    public function toggleStatus($uuid)
    {
        $unit = Unit::where('uuid', $uuid)->firstOrFail();

        $unit->update([
            'is_active' => !$unit->is_active
        ]);

        // ✅ TAMBAHAN: Cascade status ke UMKM & User jika unit dinonaktifkan
        if (!$unit->is_active) {
            // Nonaktifkan semua UMKM di bawah unit ini
            $unit->umkm()->update(['status' => 'nonaktif']);
            
            // Nonaktifkan user unit 
            if ($unit->user) {
                $unit->user->update(['is_active' => false]);
            }
            
            // Nonaktifkan semua user UMKM di bawah unit ini
            $umkmUserIds = $unit->umkm()->pluck('user_id');
            Users::whereIn('id', $umkmUserIds)->update(['is_active' => false]);
        } else {
            // Jika unit diaktifkan kembali, aktifkan user unitnya
            if ($unit->user) {
                $unit->user->update(['is_active' => true]);
            }
        }

        $status = $unit->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('unit.index')
            ->with('success', "Unit berhasil {$status}");
    }

    /**
     * API untuk get cities berdasarkan province
     */
    public function getCities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get(['code', 'name']);
        
        return response()->json($cities);
    }

    /**
     * API untuk get districts berdasarkan city
     */
    public function getDistricts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)
            ->orderBy('name')
            ->get(['code', 'name']);
        
        return response()->json($districts);
    }

    /**
     * API untuk get villages berdasarkan district
     */
    public function getVillages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)
            ->orderBy('name')
            ->get(['code', 'name']);
        
        return response()->json($villages);
    }
}
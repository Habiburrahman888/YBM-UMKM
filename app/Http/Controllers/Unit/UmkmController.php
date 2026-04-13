<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\ModalUmkm;
use App\Models\Unit;
use App\Models\Kategori;
use App\Models\Users;
use App\Mail\UmkmRegistrationMail;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class UmkmController extends Controller
{
    /**
     * Menampilkan daftar UMKM milik unit yang sedang login.
     * Jika admin mengakses ini, akan diarahkan ke admin dashboard UMKM.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Redirect admin ke controller khusus admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.umkm.index');
        }

        $query = Umkm::with(['unit', 'user', 'creator', 'kategori', 'province', 'city', 'produkUmkm', 'modalUmkm']);

        // Filter otomatis berdasarkan unit user
        $userUnit = Unit::where('user_id', $user->id)->first();
        if ($userUnit) {
            $query->where('unit_id', $userUnit->id);
        } else {
            // Jika role umkm, biarkan login as umkm melihat datanya sendiri (biasanya ditangani router lain tapi aman aja)
            if ($user->role === 'umkm') {
                $query->where('user_id', $user->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('q')) {
            $query->search($request->q);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('kategori_id')) {
            $query->byKategori($request->kategori_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->latest()
            ->paginate(15);

        $kategoriList = Kategori::orderBy('nama')->get();
        $provinceList = Province::orderBy('name')->get();
        
        $cityList = [];
        if ($request->filled('province_code')) {
            $cityList = City::where('province_code', $request->province_code)->orderBy('name')->get();
        }

        $permissions = [
            'canCreate'       => $user->role === 'unit',
            'canEdit'         => $user->role === 'unit',
            'canDelete'       => $user->role === 'unit',
            'canVerify'       => $user->role === 'unit', // Unit bisa verify UMKM barunya sendiri jika diizinkan sistem
            'canCreateAccount'=> true,
            'canChangeStatus' => false, // Hanya admin
            'userRole'        => $user->role,
        ];

        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('umkm.index')],
        ];

        return view('unit.umkm.index', compact('umkmList', 'permissions', 'kategoriList', 'provinceList', 'cityList', 'breadcrumbs'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'unit') {
            abort(403, 'Hanya Unit yang dapat mendaftarkan UMKM baru.');
        }

        $kategoriList = Kategori::orderBy('nama')->get();
        $provinceList = Province::orderBy('name')->get();
        
        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('umkm.index')],
            ['name' => 'Tambah UMKM', 'url' => route('umkm.create')],
        ];

        return view('unit.umkm.create', compact('kategoriList', 'provinceList', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'unit') {
            abort(403);
        }

        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit) {
            return back()->with('error', 'Akun Anda tidak terhubung ke unit manapun.');
        }

        $validated = $request->validate([
            'nama_pemilik'    => 'required|string|max:255',
            'nama_usaha'      => 'required|string|max:255',
            'tahun_berdiri'   => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_id'     => 'nullable|exists:kategori,id',
            'telepon'         => 'required|string|max:20',
            'email'           => 'required|email|unique:umkm,email',
            'alamat'          => 'required|string',
            'province_code'   => 'nullable|exists:indonesia_provinces,code',
            'city_code'       => 'nullable|exists:indonesia_cities,code',
            'district_code'   => 'nullable|exists:indonesia_districts,code',
            'village_code'    => 'nullable|exists:indonesia_villages,code',
            'kode_pos'        => 'nullable|string|max:5',
            'tentang'         => 'nullable|string',
            'logo_umkm'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'create_account'  => 'nullable|boolean',
            'nama_produk'     => 'nullable|string|max:255',
            'harga_produk'    => 'nullable|numeric|min:0',
            'kategori_satuan' => 'nullable|in:pcs,bungkus,gram,kg,liter,ml,box,pack,porsi,cup,karung,paket,unit',
            'foto_produk'     => 'nullable|array|max:5',
            'foto_produk.*'   => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('logo_umkm')) {
                $validated['logo_umkm'] = $request->file('logo_umkm')->store('umkm/logo', 'public');
            }

            $umkm = Umkm::create([
                'uuid'              => (string) Str::uuid(),
                'unit_id'           => $userUnit->id,
                'kategori_id'       => $validated['kategori_id'],
                'nama_pemilik'      => $validated['nama_pemilik'],
                'nama_usaha'        => $validated['nama_usaha'],
                'tahun_berdiri'     => $validated['tahun_berdiri'],
                'telepon'           => $validated['telepon'],
                'email'             => $validated['email'],
                'alamat'            => $validated['alamat'],
                'province_code'     => $validated['province_code'],
                'city_code'         => $validated['city_code'],
                'district_code'     => $validated['district_code'],
                'village_code'      => $validated['village_code'],
                'kode_pos'          => $validated['kode_pos'],
                'logo_umkm'         => $validated['logo_umkm'] ?? null,
                'tentang'           => $validated['tentang'],
                'kode_umkm'         => $this->generateKodeUmkm(),
                'tanggal_bergabung' => now()->toDateString(),
                'status'            => $userUnit->is_active ? 'aktif' : 'nonaktif',
                'created_by'        => auth()->id(),
                'verified_at'       => $userUnit->is_active ? now() : null,
                'verified_by'       => $userUnit->is_active ? auth()->id() : null,
            ]);

            // Handle Produk jika ada
            if ($request->filled('nama_produk')) {
                $fotoProdukPaths = [];
                if ($request->hasFile('foto_produk')) {
                    foreach ($request->file('foto_produk') as $file) {
                        $fotoProdukPaths[] = $file->store('produk/' . $umkm->kode_umkm, 'public');
                    }
                }

                \App\Models\ProdukUmkm::create([
                    'uuid'             => (string) Str::uuid(),
                    'umkm_id'          => $umkm->id,
                    'nama_produk'      => $validated['nama_produk'],
                    'deskripsi_produk' => $validated['nama_produk'],
                    'harga'            => $validated['harga_produk'] ?? 0,
                    'kategori_satuan'  => $validated['kategori_satuan'],
                    'foto_produk'      => !empty($fotoProdukPaths) ? $fotoProdukPaths : null,
                    'created_by'       => auth()->id(),
                ]);
            }

            ActivityLogger::logCreate($umkm, "Mendaftarkan UMKM baru '{$umkm->nama_usaha}'");

            DB::commit();
            return redirect()->route('umkm.index')->with('success', 'UMKM berhasil didaftarkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal daftarkan UMKM: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
            abort(403);
        }

        $umkm->load(['kategori', 'province', 'city', 'district', 'village', 'modalUmkm', 'produkUmkm']);
        $produkUtama = $umkm->produkUmkm->first();

        $kategoriList  = Kategori::orderBy('nama')->get();
        $provinceList  = Province::orderBy('name')->get();
        $cityList      = $umkm->province_code ? City::where('province_code', $umkm->province_code)->orderBy('name')->get() : [];
        $districtList  = $umkm->city_code ? District::where('city_code', $umkm->city_code)->orderBy('name')->get() : [];
        $villageList   = $umkm->district_code ? Village::where('district_code', $umkm->district_code)->orderBy('name')->get() : [];

        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('umkm.index')],
            ['name' => 'Ubah UMKM', 'url' => route('umkm.edit', $umkm)],
        ];

        return view('unit.umkm.edit', compact('umkm', 'kategoriList', 'provinceList', 'cityList', 'districtList', 'villageList', 'breadcrumbs', 'produkUtama'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_pemilik'      => 'required|string|max:255',
            'nama_usaha'        => 'required|string|max:255',
            'tahun_berdiri'     => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_id'       => 'nullable|exists:kategori,id',
            'telepon'           => 'required|string|max:20',
            'email'             => ['required', 'email', Rule::unique('umkm', 'email')->ignore($umkm->id)],
            'alamat'            => 'required|string',
            'logo_umkm'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $old = ActivityLogger::safeAttributes($umkm);

        if ($request->hasFile('logo_umkm')) {
            if ($umkm->logo_umkm) Storage::disk('public')->delete($umkm->logo_umkm);
            $validated['logo_umkm'] = $request->file('logo_umkm')->store('umkm/logo', 'public');
        }

        $umkm->update($validated);

        ActivityLogger::logUpdate($umkm, "Memperbarui data UMKM '{$umkm->nama_usaha}'", $old, ActivityLogger::safeAttributes($umkm->refresh()));

        return redirect()->route('umkm.index')->with('success', 'Data UMKM berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
            abort(403);
        }

        ActivityLogger::logDelete("Menghapus UMKM '{$umkm->nama_usaha}'", get_class($umkm), $umkm->id, $umkm->nama_usaha);

        if ($umkm->logo_umkm) Storage::disk('public')->delete($umkm->logo_umkm);
        $umkm->delete();

        return redirect()->route('umkm.index')->with('success', 'Data UMKM berhasil dihapus.');
    }

    // Modal, Ajax Wilayah, dan Helper Kode UMKM tetap ada di sini karena Unit yang paling sering pakai
    public function getCities(Request $request)
    {
        $cities = City::where('province_code', $request->province_code)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($cities);
    }

    public function getDistricts(Request $request)
    {
        $districts = District::where('city_code', $request->city_code)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($districts);
    }

    public function getVillages(Request $request)
    {
        $villages = Village::where('district_code', $request->district_code)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($villages);
    }

    public function verify(Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
            abort(403);
        }

        if ($umkm->unit && !$umkm->unit->is_active) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan UMKM. Unit Anda sedang tidak aktif.');
        }

        $umkm->update([
            'status'      => 'aktif',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        if ($umkm->user) {
            $umkm->user->update(['is_active' => true]);
        }

        ActivityLogger::log('verify', "Unit '" . $userUnit->nama_unit . "' memverifikasi UMKM '{$umkm->nama_usaha}'", $umkm);

        return redirect()->back()->with('success', 'UMKM berhasil diaktifkan.');
    }

    public function reject(Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
            abort(403);
        }

        $umkm->update([
            'status'      => 'nonaktif',
            'verified_at' => null,
            'verified_by' => null,
        ]);

        if ($umkm->user) {
            $umkm->user->update(['is_active' => false]);
        }

        ActivityLogger::log('reject', "Unit '" . $userUnit->nama_unit . "' menonaktifkan UMKM '{$umkm->nama_usaha}'", $umkm);

        return redirect()->back()->with('success', 'UMKM berhasil dinonaktifkan.');
    }

    public function createAccount(Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
            abort(403);
        }

        if ($umkm->user_id) {
            return back()->with('error', 'UMKM sudah memiliki akun.');
        }

        DB::beginTransaction();
        try {
            $this->createUserAccount($umkm, true);
            DB::commit();

            return back()->with('success', 'Akun UMKM berhasil dibuat dan email telah dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create account: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    private function createUserAccount(Umkm $umkm, bool $mustSendEmail = false)
    {
        $username = $this->generateUsername($umkm->nama_pemilik);
        $password = '12345678';

        $user = Users::create([
            'uuid'              => (string) Str::uuid(),
            'username'          => $username,
            'email'             => $umkm->email,
            'password'          => Hash::make($password),
            'role'              => 'umkm',
            'is_active'         => $umkm->status === 'aktif',
            'email_verified_at' => now(),
        ]);

        $umkm->update(['user_id' => $user->id]);

        if ($mustSendEmail) {
            try {
                Mail::to($umkm->email)->send(new UmkmRegistrationMail($umkm, $password));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email akun UMKM: ' . $e->getMessage());
            }
        }

        return $user;
    }

    private function generateUsername($name)
    {
        $base = strtolower(str_replace(' ', '', $name));
        $username = $base;
        $counter = 1;
        while (Users::where('username', $username)->exists()) {
            $username = $base . $counter++;
        }
        return $username;
    }

    private function generateKodeUmkm()
    {
        $prefix = 'UMKM' . date('Y');
        $latest = Umkm::where('kode_umkm', 'LIKE', $prefix . '%')->orderBy('kode_umkm', 'desc')->first();
        $number = $latest ? ((int)substr($latest->kode_umkm, -4) + 1) : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
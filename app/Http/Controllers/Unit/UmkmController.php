<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\ModalUmkm;
use App\Models\Unit;
use App\Models\Kategori;
use App\Models\Users;
use App\Mail\UmkmRegistrationMail;
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
use Barryvdh\DomPDF\Facade\Pdf;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with(['unit', 'user', 'creator', 'kategori', 'province', 'city', 'produkUmkm', 'modalUmkm']);

        $userUnit = null;
        if (auth()->user()->role !== 'admin') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if ($userUnit) {
                $query->where('unit_id', $userUnit->id);
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

        if ($request->filled('province_code')) {
            $query->byProvince($request->province_code);
        }

        if ($request->filled('city_code')) {
            $query->byCity($request->city_code);
        }

        if (auth()->user()->role === 'admin' && $request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->paginate(15);

        $kategoriList = Kategori::orderBy('nama')->get();
        $provinceList = Province::orderBy('name')->get();
        $unitList     = auth()->user()->role === 'admin' ? Unit::orderBy('nama_unit')->get() : collect();

        $cityList = [];
        if ($request->filled('province_code')) {
            $cityList = City::where('province_code', $request->province_code)
                ->orderBy('name')
                ->get();
        }

        $userRole = auth()->user()->role;

        $permissions = [
            'canCreate'        => in_array($userRole, ['admin', 'unit']),
            'canEdit'          => in_array($userRole, ['admin', 'unit']),
            'canDelete'        => in_array($userRole, ['admin', 'unit']),
            'canVerify'        => in_array($userRole, ['admin', 'unit']),
            'canCreateAccount' => in_array($userRole, ['admin', 'unit']),
            'canChangeStatus'  => $userRole === 'admin',
            'userRole'         => $userRole,
        ];

        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('umkm.index')],
        ];

        return view('umkm.index', compact('umkmList', 'permissions', 'kategoriList', 'provinceList', 'cityList', 'unitList', 'breadcrumbs'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah UMKM.');
        }

        $kategoriList = Kategori::orderBy('nama')->get();
        $provinceList = Province::orderBy('name')->get();
        $unitList     = Unit::orderBy('id')->get();

        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('umkm.index')],
            ['name' => 'Tambah UMKM', 'url' => route('umkm.create')],
        ];

        return view('umkm.create', compact('kategoriList', 'provinceList', 'unitList', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah UMKM.');
        }

        $tablePrefix = config('laravolt.indonesia.table_prefix', '');

        $validated = $request->validate([
            'nama_pemilik'   => 'required|string|max:255',
            'nama_usaha'     => 'required|string|max:255',
            'tahun_berdiri'  => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_id'    => 'nullable|exists:kategori,id',
            'telepon'        => 'required|string|max:20',
            'email'          => 'required|email|unique:umkm,email',
            'alamat'         => 'required|string',
            'province_code'  => "nullable|exists:{$tablePrefix}provinces,code",
            'city_code'      => "nullable|exists:{$tablePrefix}cities,code",
            'district_code'  => "nullable|exists:{$tablePrefix}districts,code",
            'village_code'   => "nullable|exists:{$tablePrefix}villages,code",
            'kode_pos'       => 'nullable|string|max:5',
            'tentang'        => 'nullable|string',
            'facebook'       => 'nullable|string|max:255',
            'instagram'      => 'nullable|string|max:255',
            'youtube'        => 'nullable|string|max:255',
            'tiktok'         => 'nullable|string|max:255',
            'logo_umkm'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'create_account' => 'nullable|boolean',
            'unit_id'        => 'nullable|exists:units,id',

            // Produk (opsional)
            'nama_produk'      => 'nullable|string|max:255',
            'harga_produk'     => 'nullable|numeric|min:0',
            'kategori_satuan'  => 'nullable|in:pcs,bungkus,gram,kg,liter,ml,box,pack',
            'deskripsi_produk' => 'nullable|string',
            'foto_produk'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('logo_umkm')) {
                $filename               = time() . '_logo_' . uniqid() . '.' . $request->file('logo_umkm')->getClientOriginalExtension();
                $validated['logo_umkm'] = $request->file('logo_umkm')->storeAs('umkm/logo', $filename, 'public');
            }

            if (auth()->user()->role === 'unit') {
                $unit = Unit::where('user_id', auth()->id())->first();
                if (!$unit) {
                    throw new \Exception('Akun Anda belum terhubung ke unit manapun. Hubungi admin.');
                }
                $validated['unit_id'] = $unit->id;
            } else {
                if (empty($validated['unit_id'])) {
                    $firstUnit = Unit::first();
                    if (!$firstUnit) {
                        throw new \Exception('Belum ada data unit. Buat unit terlebih dahulu.');
                    }
                    $validated['unit_id'] = $firstUnit->id;
                }
            }

            $validated['uuid']              = (string) Str::uuid();
            $validated['kode_umkm']         = $this->generateKodeUmkm();
            $validated['tanggal_bergabung'] = now()->toDateString();
            $validated['created_by']        = auth()->id();

            // Semua role langsung aktif saat mendaftarkan UMKM binaan
            $validated['status']      = 'aktif';
            $validated['verified_at'] = now();
            $validated['verified_by'] = auth()->id();

            $umkm = Umkm::create([
                'uuid'              => $validated['uuid'],
                'unit_id'           => $validated['unit_id'],
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
                'facebook'          => $validated['facebook'],
                'instagram'         => $validated['instagram'],
                'youtube'           => $validated['youtube'],
                'tiktok'            => $validated['tiktok'],
                'kode_umkm'         => $validated['kode_umkm'],
                'tanggal_bergabung' => $validated['tanggal_bergabung'],
                'status'            => $validated['status'],
                'created_by'        => $validated['created_by'],
                'verified_at'       => $validated['verified_at'],
                'verified_by'       => $validated['verified_by'],
            ]);

            // Jika ada data produk, simpan ke tabel produk_umkm
            if ($request->filled('nama_produk')) {
                $fotoProdukPaths = [];
                if ($request->hasFile('foto_produk')) {
                    $filename = time() . '_produk_' . uniqid() . '.' . $request->file('foto_produk')->getClientOriginalExtension();
                    $path     = $request->file('foto_produk')->storeAs('produk/' . $umkm->kode_umkm, $filename, 'public');
                    $fotoProdukPaths[] = $path;
                }

                \App\Models\ProdukUmkm::create([
                    'uuid'             => (string) Str::uuid(),
                    'umkm_id'          => $umkm->id,
                    'nama_produk'      => $validated['nama_produk'],
                    'deskripsi_produk' => $validated['deskripsi_produk'],
                    'harga'            => $validated['harga_produk'] ?? 0,
                    'kategori_satuan'  => $validated['kategori_satuan'], // Unit opsional
                    'foto_produk'      => !empty($fotoProdukPaths) ? $fotoProdukPaths : null,
                    'created_by'       => auth()->id(),
                ]);
            }

            if ($request->boolean('create_account')) {
                $this->createUserAccount($umkm, true);
            }

            DB::commit();

            $message = 'UMKM berhasil ditambahkan dan langsung diaktifkan.';

            if ($request->boolean('create_account')) {
                $message .= ' Akun login telah dikirim ke email UMKM.';
            }

            return redirect()->route('umkm.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($validated['logo_umkm'])) {
                Storage::disk('public')->delete($validated['logo_umkm']);
            }

            Log::error('Failed to create UMKM: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();

        $userUnitId = $userUnit ? $userUnit->id : null;
        if (auth()->user()->role !== 'admin' && $umkm->unit_id !== $userUnitId) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $umkm->load(['unit', 'user', 'creator', 'verifiedBy', 'kategori', 'province', 'city', 'district', 'village', 'modalUmkm']);

        $userRole = auth()->user()->role;

        $permissions = [
            'canEdit'          => in_array($userRole, ['admin', 'unit']),
            'canDelete'        => in_array($userRole, ['admin', 'unit']),
            'canVerify'        => in_array($userRole, ['admin', 'unit']),
            'canCreateAccount' => in_array($userRole, ['admin', 'unit']) && !$umkm->user_id,
            'canChangeStatus'  => $userRole === 'admin',
            'userRole'         => $userRole,
        ];

        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('umkm.index')],
            ['name' => $umkm->nama_usaha, 'url' => route('umkm.show', $umkm)],
        ];

        return view('umkm.show', compact('umkm', 'permissions', 'breadcrumbs'));
    }

    public function edit(Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit UMKM.');
        }

        $userUnit = Unit::where('user_id', auth()->id())->first();

        $userUnitId = $userUnit ? $userUnit->id : null;
        if (auth()->user()->role !== 'admin' && $umkm->unit_id !== $userUnitId) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        if (auth()->user()->role === 'unit') {
            if ($umkm->created_by !== auth()->id()) {
                abort(403, 'Anda hanya bisa mengedit data yang Anda buat.');
            }
        }

        $umkm->load(['kategori', 'province', 'city', 'district', 'village', 'modalUmkm']);

        $kategoriList = Kategori::orderBy('nama')->get();
        $provinceList = Province::orderBy('name')->get();

        $cityList = $umkm->province_code
            ? City::where('province_code', $umkm->province_code)->orderBy('name')->get()
            : [];

        $districtList = $umkm->city_code
            ? District::where('city_code', $umkm->city_code)->orderBy('name')->get()
            : [];

        $villageList = $umkm->district_code
            ? Village::where('district_code', $umkm->district_code)->orderBy('name')->get()
            : [];

        // Kategori modal untuk dropdown form tambah/edit modal
        $kategoriModal = ['peralatan', 'kendaraan', 'perlengkapan', 'bangunan', 'lainnya'];
        $kondisiModal  = ['baru', 'baik', 'cukup', 'rusak'];

        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('umkm.index')],
            ['name' => $umkm->nama_usaha, 'url' => route('umkm.show', $umkm)],
            ['name' => 'Ubah UMKM', 'url' => route('umkm.edit', $umkm)],
        ];

        return view('umkm.edit', compact(
            'umkm',
            'kategoriList',
            'provinceList',
            'cityList',
            'districtList',
            'villageList',
            'kategoriModal',
            'kondisiModal',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit UMKM.');
        }

        $userUnit = Unit::where('user_id', auth()->id())->first();

        $userUnitId = $userUnit ? $userUnit->id : null;
        if (auth()->user()->role !== 'admin' && $umkm->unit_id !== $userUnitId) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $tablePrefix = config('laravolt.indonesia.table_prefix', '');

        $validated = $request->validate([
            'nama_pemilik'     => 'required|string|max:255',
            'nama_usaha'       => 'required|string|max:255',
            'tahun_berdiri'    => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_id'      => 'nullable|exists:kategori,id',
            'telepon'          => 'required|string|max:20',
            'email'            => ['required', 'email', Rule::unique('umkm', 'email')->ignore($umkm->id)],
            'alamat'           => 'required|string',
            'province_code'    => "nullable|exists:{$tablePrefix}provinces,code",
            'city_code'        => "nullable|exists:{$tablePrefix}cities,code",
            'district_code'    => "nullable|exists:{$tablePrefix}districts,code",
            'village_code'     => "nullable|exists:{$tablePrefix}villages,code",
            'kode_pos'         => 'nullable|string|max:5',
            'tentang'          => 'nullable|string',
            'facebook'         => 'nullable|string|max:255',
            'instagram'        => 'nullable|string|max:255',
            'youtube'          => 'nullable|string|max:255',
            'tiktok'           => 'nullable|string|max:255',
            'logo_umkm'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_logo_umkm' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_logo_umkm')) {
            if ($umkm->logo_umkm) {
                Storage::disk('public')->delete($umkm->logo_umkm);
            }
            $validated['logo_umkm'] = null;
        }

        if ($request->hasFile('logo_umkm')) {
            if ($umkm->logo_umkm) {
                Storage::disk('public')->delete($umkm->logo_umkm);
            }
            $filename               = time() . '_logo_' . uniqid() . '.' . $request->file('logo_umkm')->getClientOriginalExtension();
            $validated['logo_umkm'] = $request->file('logo_umkm')->storeAs('umkm/logo', $filename, 'public');
        }

        // Unit tidak mereset status saat update
        $validated['updated_by'] = auth()->id();

        $umkm->update($validated);

        return redirect()->route('umkm.index')->with('success', 'Data UMKM berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus UMKM.');
        }

        $userUnit = Unit::where('user_id', auth()->id())->first();

        $userUnitId = $userUnit ? $userUnit->id : null;
        if (auth()->user()->role !== 'admin' && $umkm->unit_id !== $userUnitId) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        if (auth()->user()->role === 'unit') {
            if ($umkm->created_by !== auth()->id()) {
                abort(403, 'Anda hanya bisa menghapus data yang Anda buat.');
            }
        }

        if ($umkm->logo_umkm) {
            Storage::disk('public')->delete($umkm->logo_umkm);
        }

        if ($umkm->user_id) {
            $userAccount = Users::find($umkm->user_id);
            if ($userAccount) {
                $userAccount->delete();
            }
        }

        $umkm->delete();

        return redirect()->route('umkm.index')
            ->with('success', 'Data UMKM berhasil dihapus.');
    }

    // =========================================================================
    // MODAL UMKM
    // =========================================================================

    /**
     * Simpan item modal baru untuk UMKM tertentu.
     */
    public function storeModal(Request $request, Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_item'          => 'required|string|max:255',
            'kategori_modal'     => 'required|in:peralatan,kendaraan,perlengkapan,bangunan,lainnya',
            'keterangan'         => 'nullable|string',
            'nilai_modal'        => 'required|integer|min:0',
            'kondisi'            => 'required|in:baru,baik,cukup,rusak',
            'tanggal_perolehan'  => 'nullable|date',
            'foto'               => 'nullable|array|max:10',
            'foto.*'             => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload foto-foto jika ada
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename    = time() . '_modal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $fotoPaths[] = $file->storeAs('modal_umkm/' . $umkm->kode_umkm, $filename, 'public');
            }
        }

        $umkm->modalUmkm()->create([
            'nama_item'         => $validated['nama_item'],
            'kategori_modal'    => $validated['kategori_modal'],
            'keterangan'        => $validated['keterangan'] ?? null,
            'nilai_modal'       => $validated['nilai_modal'],
            'kondisi'           => $validated['kondisi'],
            'tanggal_perolehan' => $validated['tanggal_perolehan'] ?? null,
            'foto'              => !empty($fotoPaths) ? $fotoPaths : null,
            'created_by'        => auth()->id(),
        ]);

        return back()->with('success', 'Item modal berhasil ditambahkan.');
    }

    /**
     * Update item modal.
     */
    public function updateModal(Request $request, Umkm $umkm, ModalUmkm $modal)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_item'          => 'required|string|max:255',
            'kategori_modal'     => 'required|in:peralatan,kendaraan,perlengkapan,bangunan,lainnya',
            'keterangan'         => 'nullable|string',
            'nilai_modal'        => 'required|integer|min:0',
            'kondisi'            => 'required|in:baru,baik,cukup,rusak',
            'tanggal_perolehan'  => 'nullable|date',
            'foto'               => 'nullable|array|max:10',
            'foto.*'             => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_existing'      => 'nullable|array', // foto lama yang dipertahankan
            'foto_existing.*'    => 'string',
        ]);

        // Mulai dari foto lama yang dipertahankan user
        $fotoPaths = $request->input('foto_existing', []);

        // Hapus foto lama yang tidak dipertahankan
        $fotoLama = $modal->foto ?? [];
        foreach ($fotoLama as $path) {
            if (!in_array($path, $fotoPaths)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Upload foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename    = time() . '_modal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $fotoPaths[] = $file->storeAs('modal_umkm/' . $umkm->kode_umkm, $filename, 'public');
            }
        }

        $modal->update([
            'nama_item'         => $validated['nama_item'],
            'kategori_modal'    => $validated['kategori_modal'],
            'keterangan'        => $validated['keterangan'] ?? null,
            'nilai_modal'       => $validated['nilai_modal'],
            'kondisi'           => $validated['kondisi'],
            'tanggal_perolehan' => $validated['tanggal_perolehan'] ?? null,
            'foto'              => !empty($fotoPaths) ? $fotoPaths : null,
            'updated_by'        => auth()->id(),
        ]);

        return back()->with('success', 'Item modal berhasil diperbarui.');
    }

    /**
     * Hapus item modal beserta semua fotonya.
     */
    public function destroyModal(Umkm $umkm, ModalUmkm $modal)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        // Hapus semua file foto dari storage
        if (!empty($modal->foto)) {
            foreach ($modal->foto as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $modal->delete();

        return back()->with('success', 'Item modal berhasil dihapus.');
    }

    // =========================================================================
    // STATUS & AKUN
    // =========================================================================

    public function verify(Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengaktifkan UMKM.');
        }

        if (auth()->user()->role === 'unit') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $umkm->update([
            'status'      => 'aktif',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'UMKM berhasil diaktifkan.');
    }

    public function reject(Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menonaktifkan UMKM.');
        }

        if (auth()->user()->role === 'unit') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $umkm->update([
            'status'      => 'nonaktif',
            'verified_at' => null,
            'verified_by' => null,
        ]);

        return redirect()->back()->with('success', 'UMKM berhasil dinonaktifkan.');
    }

    public function toggleStatus(Request $request, Umkm $umkm)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengubah status UMKM melalui menu ini.');
        }

        $newStatus = $request->input('status');

        if (!in_array($newStatus, ['aktif', 'nonaktif'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        $umkm->update([
            'status'      => $newStatus,
            'verified_at' => $newStatus === 'aktif' ? now()        : $umkm->verified_at,
            'verified_by' => $newStatus === 'aktif' ? auth()->id() : $umkm->verified_by,
        ]);

        $statusLabels = ['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'];

        return back()->with('success', 'Status berhasil diubah menjadi ' . ($statusLabels[$newStatus] ?? $newStatus));
    }

    public function createAccount(Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403, 'Tidak memiliki akses.');
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

    // =========================================================================
    // AJAX WILAYAH
    // =========================================================================

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

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    private function createUserAccount(Umkm $umkm, bool $mustSendEmail = false): array
    {
        $username = $this->generateUsername($umkm->nama_pemilik);
        $password = '12345678';

        $user = Users::create([
            'uuid'              => (string) Str::uuid(),
            'username'          => $username,
            'email'             => $umkm->email,
            'password'          => Hash::make($password),
            'role'              => 'umkm',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $umkm->update(['user_id' => $user->id]);

        $umkm->relationLoaded('kategori') ?: $umkm->load('kategori');

        try {
            Mail::to($umkm->email)->send(new UmkmRegistrationMail($umkm, $username, $password));
        } catch (\Exception $e) {
            Log::error('Failed to send registration email: ' . $e->getMessage());

            if ($mustSendEmail) {
                throw new \RuntimeException(
                    'Akun tidak jadi dibuat karena email gagal dikirim ke ' . $umkm->email . '. Periksa konfigurasi mail server.',
                    0,
                    $e
                );
            }
        }

        return [$username, $password];
    }

    private function generateKodeUmkm(): string
    {
        $lastUmkm   = Umkm::orderBy('id', 'desc')->lockForUpdate()->first();
        $lastNumber = ($lastUmkm && $lastUmkm->kode_umkm)
            ? intval(substr($lastUmkm->kode_umkm, -4))
            : 0;

        return sprintf('UMKM-%04d', $lastNumber + 1);
    }

    private function generateUsername(string $namaPemilik): string
    {
        $base     = Str::slug(Str::limit($namaPemilik, 15, ''));
        $username = $base;
        $counter  = 1;

        while (Users::where('username', $username)->exists()) {
            $username = $base . $counter++;
        }

        return $username;
    }
}

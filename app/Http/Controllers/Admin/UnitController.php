<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Unit;
use App\Models\Users;
use App\Mail\UnitCreatedMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Services\ActivityLogger;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with('user');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_unit', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('kode_unit', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('admin_nama', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('admin_email', 'LIKE', '%' . $request->q . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('username', 'LIKE', '%' . $request->q . '%')
                               ->orWhere('email', 'LIKE', '%' . $request->q . '%');
                  });
            });
        }

        if ($request->filled('provinsi')) {
            $query->where('provinsi_kode', $request->provinsi);
        }

        if ($request->filled('kota')) {
            $query->where('kota_kode', $request->kota);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $units = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $provinces = Province::orderBy('name')->get();
        $cities = collect();

        if ($request->filled('provinsi')) {
            $cities = City::where('province_code', $request->provinsi)->orderBy('name')->get();
        }

        $breadcrumbs = [
            ['name' => 'Kelola Unit', 'url' => route('admin.unit.index')]
        ];

        return view('admin.unit.index', compact('units', 'breadcrumbs', 'provinces', 'cities'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();

        $availableUsers = Users::role('unit')
            ->doesntHave('unit')
            ->active()
            ->verified()
            ->orderBy('email')
            ->get();

        $breadcrumbs = [
            ['name' => 'Kelola Unit', 'url' => route('admin.unit.index')],
            ['name' => 'Tambah Unit', 'url' => route('admin.unit.create')]
        ];

        return view('admin.unit.create', compact('breadcrumbs', 'provinces', 'availableUsers'));
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

        $kodeUnit = $this->generateKodeUnit();

        $data = [
            'user_id'           => $request->user_id,
            'admin_nama'        => $request->admin_nama,
            'admin_telepon'     => $request->admin_telepon,
            'admin_email'       => $request->admin_email,
            'nama_unit'         => $request->nama_unit,
            'kode_unit'         => $kodeUnit,
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

        if ($request->hasFile('admin_foto')) {
            $data['admin_foto'] = $request->file('admin_foto')->store('units/admin', 'public');
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('units/logos', 'public');
        }

        $unit = Unit::create($data);

        ActivityLogger::logCreate($unit, "Unit baru '{$unit->nama_unit}' berhasil dibuat", [
            'nama_unit'  => $unit->nama_unit,
            'kode_unit'  => $unit->kode_unit,
            'kota_nama'  => $unit->kota_nama,
            'is_active'  => $unit->is_active,
        ]);

        $defaultPassword = '12345678';
        $userUnit = Users::find($request->user_id);

        if ($userUnit) {
            $userUnit->update([
                'password' => Hash::make($defaultPassword),
            ]);

            try {
                Mail::to($userUnit->email)
                    ->send(new UnitCreatedMail($unit, $userUnit->email, $defaultPassword));
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email unit created: ' . $e->getMessage(), [
                    'unit_id' => $unit->id,
                    'user_id' => $userUnit->id,
                ]);
            }
        }

        return redirect()
            ->route('admin.unit.index')
            ->with('success', 'Unit berhasil ditambahkan. Email notifikasi telah dikirim ke ' . ($userUnit?->email ?? '-'));
    }

    public function edit($uuid)
    {
        $unit = Unit::with('user')->where('uuid', $uuid)->first();

        if (!$unit) {
            return redirect()->route('admin.unit.index')
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
            ['name' => 'Kelola Unit', 'url' => route('admin.unit.index')],
            ['name' => 'Edit Unit', 'url' => route('admin.unit.edit', $uuid)]
        ];

        return view('admin.unit.edit', compact('unit', 'breadcrumbs', 'provinces', 'cities', 'districts', 'villages', 'availableUsers'));
    }

    public function update(Request $request, $uuid)
    {
        $unit = Unit::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'user_id'           => [
                'required',
                'exists:users,id',
                Rule::unique('units', 'user_id')->ignore($unit->id)
            ],
            'admin_nama'        => 'nullable|string|max:255',
            'admin_telepon'     => 'nullable|string|max:20',
            'admin_email'       => 'nullable|email|max:255',
            'admin_foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nama_unit'         => ['required', 'string', 'max:255', Rule::unique('units', 'nama_unit')->ignore($unit->id)],
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
            'user_id.unique'   => 'User sudah memiliki unit lain',
        ]);

        $data = [
            'user_id'           => $request->user_id,
            'admin_nama'        => $request->admin_nama,
            'admin_telepon'     => $request->admin_telepon,
            'admin_email'       => $request->admin_email,
            'nama_unit'         => $request->nama_unit,
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

        if ($request->hasFile('admin_foto')) {
            if ($unit->admin_foto) {
                Storage::disk('public')->delete($unit->admin_foto);
            }
            $data['admin_foto'] = $request->file('admin_foto')->store('units/admin', 'public');
        }

        if ($request->boolean('remove_admin_foto') && $unit->admin_foto) {
            Storage::disk('public')->delete($unit->admin_foto);
            $data['admin_foto'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($unit->logo) {
                Storage::disk('public')->delete($unit->logo);
            }
            $data['logo'] = $request->file('logo')->store('units/logos', 'public');
        }

        if ($request->boolean('remove_logo') && $unit->logo) {
            Storage::disk('public')->delete($unit->logo);
            $data['logo'] = null;
        }

        $old = ActivityLogger::safeAttributes($unit);
        $unit->update($data);
        $unit->refresh();

        ActivityLogger::logUpdate($unit, "Unit '{$unit->nama_unit}' diupdate", $old, ActivityLogger::safeAttributes($unit));

        if ($unit->is_active === false) {
            $unit->umkm()->update(['status' => 'nonaktif']);
            if ($unit->user) {
                $unit->user->update(['is_active' => false]);
            }
            $umkmUserIds = $unit->umkm()->pluck('user_id');
            Users::whereIn('id', $umkmUserIds)->update(['is_active' => false]);
        } elseif ($unit->is_active === true) {
            if ($unit->user) {
                $unit->user->update(['is_active' => true]);
            }
        }

        return redirect()
            ->route('admin.unit.index')
            ->with('success', 'Unit berhasil diupdate');
    }

    public function destroy($uuid)
    {
        $unit = Unit::where('uuid', $uuid)->firstOrFail();

        ActivityLogger::logDelete(
            "Unit '{$unit->nama_unit}' dihapus",
            get_class($unit), $unit->id, $unit->nama_unit,
            ['kode_unit' => $unit->kode_unit]
        );

        if ($unit->admin_foto) {
            Storage::disk('public')->delete($unit->admin_foto);
        }
        if ($unit->logo) {
            Storage::disk('public')->delete($unit->logo);
        }

        $unit->delete();

        return redirect()
            ->route('admin.unit.index')
            ->with('success', 'Unit berhasil dihapus');
    }

    public function toggleStatus($uuid)
    {
        $unit = Unit::where('uuid', $uuid)->firstOrFail();

        $unit->update([
            'is_active' => !$unit->is_active
        ]);

        if (!$unit->is_active) {
            $unit->umkm()->update(['status' => 'nonaktif']);
            if ($unit->user) {
                $unit->user->update(['is_active' => false]);
            }
            $umkmUserIds = $unit->umkm()->pluck('user_id');
            Users::whereIn('id', $umkmUserIds)->update(['is_active' => false]);
        } else {
            if ($unit->user) {
                $unit->user->update(['is_active' => true]);
            }
        }

        $status = $unit->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::log('toggle_status', "Unit '{$unit->nama_unit}' {$status}", $unit, [
            'is_active' => $unit->is_active,
        ]);

        return redirect()
            ->route('admin.unit.index')
            ->with('success', "Unit berhasil {$status}");
    }

    // =========================================================================
    // LAPORAN PDF
    // =========================================================================

    /**
     * Download Laporan UMKM per Unit
     */
    public function downloadByUnit($unitId)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        if (auth()->user()->role !== 'admin') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if (!$userUnit || $userUnit->id != $unitId) {
                abort(403, 'Anda tidak memiliki akses ke unit ini.');
            }
        }

        if ($unitId == 0 || $unitId == '0') {
            $unitName = 'Pusat atau Tanpa Unit';
            $umkmList = Umkm::with(['unit', 'kategori', 'modalUmkm', 'produkUmkm', 'province', 'city', 'district', 'village'])
                ->whereNull('unit_id')
                ->get();
            $unitList = collect([null]);
        } else {
            $unit = Unit::findOrFail($unitId);
            $unitName = $unit->nama_unit;
            $umkmList = Umkm::with(['unit', 'kategori', 'modalUmkm', 'produkUmkm', 'province', 'city', 'district', 'village'])
                ->where('unit_id', $unitId)
                ->get();
            $unitList = collect([$unit]);
        }

        $pdf = Pdf::loadView('admin.report-unit.pdf', [
            'umkmList' => $umkmList->groupBy('unit_id'),
            'unitList' => $unitList
        ])->setPaper('a4', 'portrait')
          ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-umkm-' . Str::slug($unitName) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Download Semua Laporan UMKM (Berdasarkan Filter)
     */
    public function downloadAll(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        $query = Umkm::with(['unit', 'kategori', 'modalUmkm', 'produkUmkm', 'province', 'city', 'district', 'village']);

        if (auth()->user()->role !== 'admin') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if ($userUnit) {
                $query->where('unit_id', $userUnit->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }
        if ($request->filled('tahun_berdiri')) {
            $query->where('tahun_berdiri', $request->tahun_berdiri);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if (auth()->user()->role === 'admin' && $request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        if (auth()->user()->role === 'admin') {
            $pdfList  = $umkmList->groupBy('unit_id');
            $viewName = 'admin.report-unit.pdf';
            $unitList = Unit::orderBy('nama_unit')
                ->when($request->filled('unit_id'), fn($q) => $q->where('id', $request->unit_id))
                ->get();
            $data = [
                'umkmList' => $pdfList,
                'unitList' => $unitList,
            ];
        } else {
            $pdfList  = $umkmList;
            $viewName = 'umkm.pdf.report_all';
            $data     = [
                'umkmList' => $pdfList,
            ];
        }

        $pdf = Pdf::loadView($viewName, $data)
            ->setPaper('a4', auth()->user()->role === 'admin' ? 'portrait' : 'landscape')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-rekapitulasi-umkm-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    // =========================================================================
    // API WILAYAH
    // =========================================================================

    public function getCities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($cities);
    }

    public function getDistricts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($districts);
    }

    public function getVillages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($villages);
    }
    private function generateKodeUnit()
    {
        $year = date('Y');
        $prefix = 'UNIT' . $year;

        $latestUnit = Unit::where('kode_unit', 'LIKE', $prefix . '%')
            ->orderBy('kode_unit', 'desc')
            ->first();

        if (!$latestUnit) {
            return $prefix . '0001';
        }

        $lastCode = $latestUnit->kode_unit;
        $lastNumber = (int) substr($lastCode, -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }
}
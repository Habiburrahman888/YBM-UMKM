<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Unit;
use App\Models\Kategori;
use App\Models\Users;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with(['unit', 'user', 'creator', 'kategori', 'province', 'city', 'produkUmkm', 'modalUmkm']);

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
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->latest()
            ->paginate(15);

        $kategoriList = Kategori::orderBy('nama')->get();
        $provinceList = Province::orderBy('name')->get();
        $unitList     = Unit::orderBy('nama_unit')->get();

        $cityList = [];
        if ($request->filled('province_code')) {
            $cityList = City::where('province_code', $request->province_code)->orderBy('name')->get();
        }

        $permissions = [
            'canCreate'       => true,
            'canEdit'         => false,
            'canDelete'       => true,
            'canVerify'       => true,
            'canCreateAccount'=> true,
            'canChangeStatus' => true,
            'userRole'        => 'admin',
        ];

        $breadcrumbs = [
            ['name' => 'Kelola UMKM', 'url' => route('admin.umkm.index')],
        ];

        return view('admin.umkm.index', compact('umkmList', 'permissions', 'kategoriList', 'provinceList', 'cityList', 'unitList', 'breadcrumbs'));
    }

    public function toggleStatus(Request $request, Umkm $umkm)
    {
        $newStatus = $request->input('status');

        if (!in_array($newStatus, ['aktif', 'nonaktif'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        if ($newStatus === 'aktif' && $umkm->unit && !$umkm->unit->is_active) {
            return back()->with('error', 'Gagal mengaktifkan UMKM. Unit yang menaungi sedang tidak aktif.');
        }

        $oldStatus = $umkm->status;
        $umkm->update([
            'status'      => $newStatus,
            'verified_at' => $newStatus === 'aktif' ? now() : $umkm->verified_at,
            'verified_by' => $newStatus === 'aktif' ? auth()->id() : $umkm->verified_by,
        ]);

        if ($umkm->user) {
            $umkm->user->update(['is_active' => ($newStatus === 'aktif')]);
        }

        ActivityLogger::log('toggle_status', "Status UMKM '{$umkm->nama_usaha}' diubah dari {$oldStatus} menjadi {$newStatus}", $umkm, [
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        return back()->with('success', 'Status berhasil diubah menjadi ' . ucfirst($newStatus));
    }

    public function verify(Umkm $umkm)
    {
        if ($umkm->unit && !$umkm->unit->is_active) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan UMKM. Unit yang menaungi sedang tidak aktif.');
        }

        $umkm->update([
            'status'      => 'aktif',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        if ($umkm->user) {
            $umkm->user->update(['is_active' => true]);
        }

        ActivityLogger::log('verify', "UMKM '{$umkm->nama_usaha}' disetujui (diverifikasi)", $umkm);

        return redirect()->back()->with('success', 'UMKM berhasil diaktifkan.');
    }

    public function destroy(Umkm $umkm)
    {
        ActivityLogger::logDelete("Admin menghapus UMKM '{$umkm->nama_usaha}'", get_class($umkm), $umkm->id, $umkm->nama_usaha);
        $umkm->delete();
        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil dihapus.');
    }

    public function reject(Umkm $umkm)
    {
        $umkm->update([
            'status'      => 'nonaktif',
            'verified_at' => null,
            'verified_by' => null,
        ]);

        if ($umkm->user) {
            $umkm->user->update(['is_active' => false]);
        }

        ActivityLogger::log('reject', "UMKM '{$umkm->nama_usaha}' dinonaktifkan (reject)", $umkm);

        return redirect()->back()->with('success', 'UMKM berhasil dinonaktifkan.');
    }

    public function createAccount(Umkm $umkm)
    {
        if ($umkm->user_id) {
            return back()->with('error', 'UMKM sudah memiliki akun.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $this->createUserAccount($umkm, true);
            \Illuminate\Support\Facades\DB::commit();

            return back()->with('success', 'Akun UMKM berhasil dibuat dan email telah dikirim.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Failed to create account: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    private function createUserAccount(Umkm $umkm, bool $mustSendEmail = false)
    {
        $username = $this->generateUsername($umkm->nama_pemilik);
        $password = '12345678';

        $user = Users::create([
            'uuid'              => (string) \Illuminate\Support\Str::uuid(),
            'username'          => $username,
            'email'             => $umkm->email,
            'password'          => \Illuminate\Support\Facades\Hash::make($password),
            'role'              => 'umkm',
            'is_active'         => $umkm->status === 'aktif',
            'email_verified_at' => now(),
        ]);

        $umkm->update(['user_id' => $user->id]);

        if ($mustSendEmail) {
            try {
                \Illuminate\Support\Facades\Mail::to($umkm->email)->send(new \App\Mail\UmkmRegistrationMail($umkm, $username, $password));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim email akun UMKM: ' . $e->getMessage());
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
}

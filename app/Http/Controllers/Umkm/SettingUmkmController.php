<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\UmkmRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SettingUmkmController extends Controller
{
    private function getUmkm()
    {
        return Umkm::where('user_id', Auth::id())->firstOrFail();
    }

    public function show()
    {
        $umkm = $this->getUmkm();

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Pengaturan Profil', 'url' => route('umkm.settings.show')],
        ];

        return view('settings-umkm.show', compact('umkm', 'breadcrumbs'));
    }

    public function edit()
    {
        $umkm = $this->getUmkm();

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Pengaturan Profil', 'url' => route('umkm.settings.show')],
            ['name' => 'Edit Profil', 'url' => route('umkm.settings.edit')],
        ];

        return view('settings-umkm.edit', compact('umkm', 'breadcrumbs'));
    }

    public function update(Request $request)
    {
        $umkm = $this->getUmkm();

        $validator = Validator::make($request->all(), [
            'logo_umkm' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'qris_foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'tentang'   => 'nullable|string',
            'telepon'   => 'required|string|max:20',
            'facebook'  => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube'   => 'nullable|url|max:255',
            'tiktok'    => 'nullable|url|max:255',
            'rekening'  => 'nullable|array',
            'rekening.*.nama_bank' => 'required_with:rekening|string|max:255',
            'rekening.*.nomor_rekening' => 'required_with:rekening|string|max:255',
            'rekening.*.nama_rekening' => 'required_with:rekening|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'telepon'   => $request->telepon,
                'tentang'   => $request->tentang,
                'facebook'  => $request->facebook,
                'instagram' => $request->instagram,
                'youtube'   => $request->youtube,
                'tiktok'    => $request->tiktok,
            ];

            if ($request->hasFile('logo_umkm')) {
                if ($umkm->logo_umkm) {
                    Storage::disk('public')->delete($umkm->logo_umkm);
                }

                $data['logo_umkm'] = $request->file('logo_umkm')
                    ->store('umkm/logos', 'public');
            }

            if ($request->hasFile('qris_foto')) {
                if ($umkm->qris_foto) {
                    Storage::disk('public')->delete($umkm->qris_foto);
                }

                $data['qris_foto'] = $request->file('qris_foto')
                    ->store('umkm/qris', 'public');
            }

            $umkm->update($data);

            // Sync Rekening
            $umkm->rekening()->delete();
            if ($request->has('rekening')) {
                foreach ($request->rekening as $rek) {
                    if (!empty($rek['nama_bank']) && !empty($rek['nomor_rekening'])) {
                        $umkm->rekening()->create([
                            'nama_bank'      => $rek['nama_bank'],
                            'nomor_rekening' => $rek['nomor_rekening'],
                            'nama_rekening'  => $rek['nama_rekening'] ?? '-',
                        ]);
                    }
                }
            }

            return redirect()->route('umkm.settings.show')
                ->with('success', 'Profil UMKM berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteLogo()
    {
        try {
            $umkm = $this->getUmkm();

            if (!$umkm->logo_umkm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada logo untuk dihapus.',
                ], 404);
            }

            Storage::disk('public')->delete($umkm->logo_umkm);
            $umkm->update(['logo_umkm' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Logo berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus logo: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteQris()
    {
        try {
            $umkm = $this->getUmkm();

            if (!$umkm->qris_foto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada foto QRIS untuk dihapus.',
                ], 404);
            }

            Storage::disk('public')->delete($umkm->qris_foto);
            $umkm->update(['qris_foto' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Foto QRIS berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto QRIS: ' . $e->getMessage(),
            ], 500);
        }
    }
}

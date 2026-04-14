<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    // Helper: ambil UMKM milik user yang login
    private function getUmkm()
    {
        return Umkm::where('user_id', Auth::id())->firstOrFail();
    }

    // ── INDEX — Daftar semua produk milik UMKM ────────────────────────────
    public function index()
    {
        $umkm   = $this->getUmkm();
        $produks = ProdukUmkm::where('umkm_id', $umkm->id)
                    ->latest()
                    ->paginate(10);

        $breadcrumbs = [
            ['name' => 'Produk Saya', 'url' => route('umkm.produk.index')],
        ];

        return view('umkm.produk.index', compact('produks', 'breadcrumbs'));
    }

    // ── SHOW — Detail satu produk ─────────────────────────────────────────
    public function show(string $uuid)
    {
        $umkm   = $this->getUmkm();
        $produk = ProdukUmkm::where('uuid', $uuid)
                    ->where('umkm_id', $umkm->id)
                    ->firstOrFail();

        $breadcrumbs = [
            ['name' => 'Produk Saya', 'url' => route('umkm.produk.index')],
            ['name' => $produk->nama_produk, 'url' => route('umkm.produk.show', $produk->uuid)],
        ];

        return view('umkm.produk.show', compact('produk', 'breadcrumbs'));
    }

    // ── CREATE — Form tambah produk baru ──────────────────────────────────
    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Produk Saya', 'url' => route('umkm.produk.index')],
            ['name' => 'Tambah Produk', 'url' => route('umkm.produk.create')],
        ];

        return view('umkm.produk.create', compact('breadcrumbs'));
    }

    // ── STORE — Simpan produk baru ────────────────────────────────────────
    public function store(Request $request)
    {
        $umkm = $this->getUmkm();

        if ($request->has('harga')) {
            $harga = preg_replace('/[^0-9]/', '', $request->harga);
            $request->merge([
                'harga' => (int) $harga
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nama_produk'      => 'required|string|max:255',
            'deskripsi_produk' => 'required|string',
            'harga'            => 'required|numeric|min:0',
            'kategori_satuan'  => 'required|in:pcs,bungkus,gram,kg,liter,ml,box,pack,porsi,cup,karung,paket,unit',
            'stok'             => 'required|integer|min:0',
            'foto_produk'      => 'nullable|array|max:5',
            'foto_produk.*'    => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hargaFix = preg_replace('/[^0-9]/', '', $request->harga);

        try {
            DB::beginTransaction();

            $fotos = $this->uploadFotos($request);

            ProdukUmkm::create([
                'uuid'             => (string) Str::uuid(),
                'umkm_id'          => $umkm->id,
                'nama_produk'      => $request->nama_produk,
                'deskripsi_produk' => $request->deskripsi_produk,
                'harga'            => (int) $hargaFix,
                'kategori_satuan'  => $request->kategori_satuan,
                'stok'             => $request->stok,
                'foto_produk'      => $fotos,
                'created_by'       => Auth::id(),
                'updated_by'       => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('umkm.produk.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ── EDIT — Form edit produk ───────────────────────────────────────────
    public function edit(string $uuid)
    {
        $umkm   = $this->getUmkm();
        $produk = ProdukUmkm::where('uuid', $uuid)
                    ->where('umkm_id', $umkm->id)
                    ->firstOrFail();

        $breadcrumbs = [
            ['name' => 'Produk Saya', 'url' => route('umkm.produk.index')],
            ['name' => $produk->nama_produk, 'url' => route('umkm.produk.show', $produk->uuid)],
            ['name' => 'Edit', 'url' => route('umkm.produk.edit', $produk->uuid)],
        ];

        return view('umkm.produk.edit', compact('produk', 'breadcrumbs'));
    }

    // ── UPDATE — Simpan perubahan produk ──────────────────────────────────
    public function update(Request $request, string $uuid)
    {
        $umkm   = $this->getUmkm();
        $produk = ProdukUmkm::where('uuid', $uuid)
                    ->where('umkm_id', $umkm->id)
                    ->firstOrFail();

        if ($request->has('harga')) {
            $harga = preg_replace('/[^0-9]/', '', $request->harga);
            $request->merge([
                'harga' => (int) $harga
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nama_produk'      => 'required|string|max:255',
            'deskripsi_produk' => 'required|string',
            'harga'            => 'required|numeric|min:0',
            'kategori_satuan'  => 'required|in:pcs,bungkus,gram,kg,liter,ml,box,pack,porsi,cup,karung,paket,unit',
            'stok'             => 'required|integer|min:0',
            'foto_produk'      => 'nullable|array|max:5',
            'foto_produk.*'    => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_hapus'       => 'nullable|array',
            'foto_hapus.*'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $existingFotos = is_array($produk->foto_produk) ? $produk->foto_produk : [];

            if ($request->has('foto_hapus') && !empty($request->foto_hapus)) {
                foreach ($request->foto_hapus as $fotoPath) {
                    if (in_array($fotoPath, $existingFotos)) {
                        Storage::disk('public')->delete($fotoPath);
                        $existingFotos = array_filter($existingFotos, fn($f) => $f !== $fotoPath);
                    }
                }
            }

            $newPaths = $this->uploadFotos($request);
            $finalFotos = array_values(array_merge($existingFotos, $newPaths));

            $produk->update([
                'nama_produk'      => $request->nama_produk,
                'deskripsi_produk' => $request->deskripsi_produk,
                'harga'            => (int) preg_replace('/[^0-9]/', '', $request->harga),
                'kategori_satuan'  => $request->kategori_satuan,
                'stok'             => $request->stok,
                'foto_produk'      => $finalFotos,
                'updated_by'       => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('umkm.produk.index')
                ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ── TAMBAH STOK (AJAX) ──────────────────────────────────────────────
    public function tambahStok(Request $request, string $uuid)
    {
        $umkm   = $this->getUmkm();
        $produk = ProdukUmkm::where('uuid', $uuid)
                    ->where('umkm_id', $umkm->id)
                    ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $currentStok = $produk->stok ?? 0;
            $newStok = $currentStok + $request->stok;

            $produk->update([
                'stok' => $newStok,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('umkm.produk.index')
                ->with('success', 'Stok berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── DESTROY — Hapus produk ────────────────────────────────────────────
    public function destroy(string $uuid)
    {
        $umkm   = $this->getUmkm();
        $produk = ProdukUmkm::where('uuid', $uuid)
                    ->where('umkm_id', $umkm->id)
                    ->firstOrFail();

        // Hapus semua foto dari storage
        foreach ($produk->foto_produk ?? [] as $foto) {
            Storage::disk('public')->delete($foto);
        }

        $produk->delete();

        return redirect()->route('umkm.produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    // ── DESTROY FOTO (AJAX) ───────────────────────────────────────────────
    public function destroyFoto(Request $request, string $uuid)
    {
        $umkm   = $this->getUmkm();
        $produk = ProdukUmkm::where('uuid', $uuid)
                    ->where('umkm_id', $umkm->id)
                    ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'foto_path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Path foto tidak valid.'], 422);
        }

        $fotoPath      = $request->foto_path;
        $existingFotos = $produk->foto_produk ?? [];

        if (!in_array($fotoPath, $existingFotos)) {
            return response()->json(['success' => false, 'message' => 'Foto tidak ditemukan.'], 404);
        }

        Storage::disk('public')->delete($fotoPath);

        $produk->update([
            'foto_produk' => array_values(array_filter($existingFotos, fn($f) => $f !== $fotoPath)),
            'updated_by'  => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
    }

    // ── PRIVATE HELPER ────────────────────────────────────────────────────
    private function uploadFotos(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('foto_produk')) {
            $files = $request->file('foto_produk');
            // Pastikan $files adalah array (karena multiple="multiple" atau name="foto_produk[]")
            if (is_array($files)) {
                foreach ($files as $foto) {
                    $paths[] = $foto->store('produk_umkm/fotos', 'public');
                }
            } else {
                $paths[] = $files->store('produk_umkm/fotos', 'public');
            }
        }
        return $paths;
    }
}
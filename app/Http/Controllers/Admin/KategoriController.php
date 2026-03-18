<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::query();

        // Search
        if ($request->filled('q')) {
            $query->where('nama', 'LIKE', '%' . $request->q . '%');
        }

        $kategoris = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $breadcrumbs = [
            ['name' => 'Kelola Kategori', 'url' => route('kategori.index')]
        ];

        return view('kategori.index', compact('kategoris', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Kelola Kategori', 'url' => route('kategori.index')],
            ['name' => 'Tambah Kategori', 'url' => route('kategori.create')]
        ];

        return view('kategori.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ]);

        Kategori::create([
            'nama' => $request->nama,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($uuid)
    {
        $kategori = Kategori::where('uuid', $uuid)->first();

        if (!$kategori) {
            return redirect()->route('kategori.index')
                ->with('error', 'Data kategori tidak ditemukan!');
        }

        $breadcrumbs = [
            ['name' => 'Kelola Kategori', 'url' => route('kategori.index')],
            ['name' => 'Ubah Kategori', 'url' => route('kategori.edit', $uuid)]
        ];

        return view('kategori.edit', compact('kategori', 'breadcrumbs'));
    }

    public function update(Request $request, $uuid)
    {
        $kategori = Kategori::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('kategori', 'nama')->ignore($kategori->id)],
        ]);

        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($uuid)
    {
        $kategori = Kategori::where('uuid', $uuid)->firstOrFail();

        $kategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
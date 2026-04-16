<?php

namespace App\Http\Controllers\Umkm;

use App\Models\Umkm;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $umkm = Umkm::where('user_id', $user->id)->firstOrFail();

        $query = Pesanan::with(['produk', 'items.produk'])->where('umkm_id', $umkm->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $pesanans = $query->latest()->paginate(10)->withQueryString();

        return view('umkm.pesanan.index', compact('umkm', 'pesanans'));
    }

    public function show($uuid)
    {
        $user = auth()->user();
        $umkm = Umkm::where('user_id', $user->id)->firstOrFail();

        $pesanan = Pesanan::with(['produk', 'items.produk'])
            ->where('umkm_id', $umkm->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('umkm.pesanan.show', compact('umkm', 'pesanan'));
    }

    public function updateStatus(Request $request, $uuid)
    {
        $user = auth()->user();
        $umkm = Umkm::where('user_id', $user->id)->firstOrFail();

        $pesanan = Pesanan::with('items.produk')
            ->where('umkm_id', $umkm->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan'
        ]);

        if ($pesanan->status === 'selesai') {
            return redirect()->back()->with('error', 'Pesanan yang sudah selesai tidak dapat diubah lagi.');
        }

        if ($pesanan->status !== 'pending' && $request->status === 'pending') {
            return redirect()->back()->with('error', 'Pesanan yang sudah diproses tidak dapat dikembalikan ke status pending.');
        }

        $oldStatus = $pesanan->status;

        $pesanan->update([
            'status' => $request->status
        ]);

        $activeStatuses = ['diproses', 'dikirim', 'selesai'];
        $inactiveStatuses = ['pending', 'dibatalkan'];

        if (in_array($oldStatus, $inactiveStatuses) && in_array($request->status, $activeStatuses)) {
            // Pindah dari tidak aktif ke aktif — Kurangi stok
            foreach ($pesanan->items as $item) {
                if ($item->produk && $item->produk->stok !== null) {
                    $item->produk->decrement('stok', $item->jumlah);
                }
            }
        } elseif (in_array($oldStatus, $activeStatuses) && in_array($request->status, $inactiveStatuses)) {
            // Pindah dari aktif ke tidak aktif — Kembalikan stok
            foreach ($pesanan->items as $item) {
                if ($item->produk && $item->produk->stok !== null) {
                    $item->produk->increment('stok', $item->jumlah);
                }
            }
        }

        return redirect()->route('umkm.pesanan.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $umkm = Umkm::where('user_id', $user->id)->firstOrFail();

        $query = Pesanan::with(['produk', 'items.produk'])->where('umkm_id', $umkm->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $pesanans        = $query->latest()->get();
        $totalPendapatan = $pesanans->where('status', 'selesai')->sum('total_harga');
        $filters         = $request->only(['status', 'dari', 'sampai']);

        $pdf = Pdf::loadView('umkm.pesanan.pdf', compact('umkm', 'pesanans', 'totalPendapatan', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-pesanan-' . $umkm->nama_usaha . '-' . now()->format('Ymd') . '.pdf');
    }
}

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

        return view('umkm.produk.pesanan.index', compact('umkm', 'pesanans'));
    }

    public function show($uuid)
    {
        $user = auth()->user();
        $umkm = Umkm::where('user_id', $user->id)->firstOrFail();

        $pesanan = Pesanan::with(['produk', 'items.produk'])
            ->where('umkm_id', $umkm->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('umkm.produk.pesanan.show', compact('umkm', 'pesanan'));
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
            'status' => 'required|in:pending,diproses,selesai,dibatalkan'
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

        if ($oldStatus == 'pending' && in_array($request->status, ['diproses', 'selesai'])) {
            // Kurangi stok saat pesanan diproses atau selesai
            foreach ($pesanan->items as $item) {
                if ($item->produk) {
                    $item->produk->decrement('stok', $item->jumlah);
                }
            }
        } elseif (in_array($oldStatus, ['diproses', 'selesai']) && in_array($request->status, ['pending', 'dibatalkan'])) {
            // Kembalikan stok jika pesanan dibatalkan atau dikembalikan ke pending
            foreach ($pesanan->items as $item) {
                if ($item->produk) {
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

        $pdf = Pdf::loadView('umkm.produk.pesanan.pdf', compact('umkm', 'pesanans', 'totalPendapatan', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pesanan-' . $umkm->nama_usaha . '-' . now()->format('Ymd') . '.pdf');
    }
}

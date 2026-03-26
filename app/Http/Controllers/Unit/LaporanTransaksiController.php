<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Umkm;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanTransaksiController extends Controller
{
    private function getUnit()
    {
        return Unit::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $unit = $this->getUnit();

        $pesanans = Umkm::where('unit_id', $unit->id)
            ->withSum(['pesanans as total_penjualan' => function ($q) use ($request) {
                $q->where('status', 'selesai');
                if ($request->filled('dari')) {
                    $q->whereDate('created_at', '>=', $request->dari);
                }
                if ($request->filled('sampai')) {
                    $q->whereDate('created_at', '<=', $request->sampai);
                }
            }], 'total_harga')
            ->when($request->filled('umkm_id'), fn($q) => $q->where('id', $request->umkm_id))
            ->paginate(15)->withQueryString();

        // Stats
        $umkmIds         = Umkm::where('unit_id', $unit->id)->pluck('id');
        $allQuery        = Pesanan::whereIn('umkm_id', $umkmIds)->where('status', 'selesai');
        $totalPesanan    = (clone $allQuery)->count();
        $totalPendapatan = (clone $allQuery)->sum('total_harga');

        $umkmList = Umkm::where('unit_id', $unit->id)->orderBy('nama_usaha')->get();

        return view('unit.laporan-transaksi.index', compact(
            'unit',
            'pesanans',
            'umkmList',
            'totalPesanan',
            'totalPendapatan'
        ));
    }

    public function exportPdf(Request $request)
    {
        $unit    = $this->getUnit();

        $pesanans = Umkm::where('unit_id', $unit->id)
            ->withSum(['pesanans as total_penjualan' => function ($q) use ($request) {
                $q->where('status', 'selesai');
                if ($request->filled('dari')) {
                    $q->whereDate('created_at', '>=', $request->dari);
                }
                if ($request->filled('sampai')) {
                    $q->whereDate('created_at', '<=', $request->sampai);
                }
            }], 'total_harga')
            ->when($request->filled('umkm_id'), fn($q) => $q->where('id', $request->umkm_id))
            ->get();

        $totalPendapatan = $pesanans->sum('total_penjualan');
        $filters         = $request->only(['umkm_id', 'dari', 'sampai']);
        $umkmList        = Umkm::where('unit_id', $unit->id)->get()->keyBy('id');

        $pdf = Pdf::loadView('unit.laporan-transaksi.pdf', compact(
            'unit', 'pesanans', 'totalPendapatan', 'filters', 'umkmList'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-transaksi-' . $unit->nama_unit . '-' . now()->format('Ymd') . '.pdf');
    }
}

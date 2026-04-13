<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Umkm;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanTransaksiController extends Controller
{
    private function getUnit()
    {
        return Unit::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $unit = $this->getUnit();

        // ── Base Query pesanan selesai (scoped to unit) ─────────────────────
        $query = Pesanan::whereHas('umkm', fn($q) => $q->where('unit_id', $unit->id))
            ->where('status', 'selesai');

        // ── Filters ────────────────────────────────────────────────────────
        if ($request->filled('umkm_id')) {
            $query->where('umkm_id', $request->umkm_id);
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        // ── Stat Cards ─────────────────────────────────────────────────────
        $allQuery        = clone $query;
        $totalPesanan    = (clone $allQuery)->count();
        $totalPendapatan = (clone $allQuery)->sum('total_harga');

        // Pesanan bulan ini (selesai)
        $pendapatanBulanIni = Pesanan::whereHas('umkm', fn($q) => $q->where('unit_id', $unit->id))
            ->where('status', 'selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');

        // UMKM terlibat
        $umkmTerlibat = (clone $allQuery)->distinct('umkm_id')->count('umkm_id');

        // ── Rekap per UMKM (grouped) ────────────────────────────────────────
        $rekapUmkm = Umkm::where('unit_id', $unit->id)
            ->withSum(
                ['pesanans as total_penjualan' => function ($q) use ($request) {
                    $q->where('status', 'selesai');
                    if ($request->filled('dari')) {
                        $q->whereDate('created_at', '>=', $request->dari);
                    }
                    if ($request->filled('sampai')) {
                        $q->whereDate('created_at', '<=', $request->sampai);
                    }
                }],
                'total_harga'
            )
            ->withCount(
                ['pesanans as jumlah_transaksi' => function ($q) use ($request) {
                    $q->where('status', 'selesai');
                    if ($request->filled('dari')) {
                        $q->whereDate('created_at', '>=', $request->dari);
                    }
                    if ($request->filled('sampai')) {
                        $q->whereDate('created_at', '<=', $request->sampai);
                    }
                }]
            )
            ->with(['kategori'])
            ->when($request->filled('umkm_id'), fn($q) => $q->where('id', $request->umkm_id))
            ->having('jumlah_transaksi', '>', 0)
            ->orderByDesc('total_penjualan')
            ->paginate(20)
            ->withQueryString();

        // ── Data untuk filter dropdown ──────────────────────────────────────
        $umkmList = Umkm::where('unit_id', $unit->id)->orderBy('nama_usaha')->get();

        // ── Grafik Tren Pendapatan 6 Bulan ─────────────────────────────────
        $grafikTren = $this->getGrafikTren($unit, $request);

        // ── Top 5 UMKM berdasarkan pendapatan ──────────────────────────────
        $topUmkm = $this->getTopUmkm($unit, $request);

        return view('unit.laporan-transaksi.index', compact(
            'unit',
            'rekapUmkm',
            'totalPesanan',
            'totalPendapatan',
            'pendapatanBulanIni',
            'umkmTerlibat',
            'umkmList',
            'grafikTren',
            'topUmkm'
        ));
    }

    public function exportPdf(Request $request)
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
            ->withCount(['pesanans as jumlah_transaksi' => function ($q) use ($request) {
                $q->where('status', 'selesai');
                if ($request->filled('dari')) {
                    $q->whereDate('created_at', '>=', $request->dari);
                }
                if ($request->filled('sampai')) {
                    $q->whereDate('created_at', '<=', $request->sampai);
                }
            }])
            ->with('kategori')
            ->when($request->filled('umkm_id'), fn($q2) => $q2->where('id', $request->umkm_id))
            ->get()
            ->filter(fn($u) => $u->jumlah_transaksi > 0);

        $totalPendapatan = $pesanans->sum('total_penjualan');
        $totalTransaksi  = $pesanans->sum('jumlah_transaksi');
        $filters         = $request->only(['umkm_id', 'dari', 'sampai']);
        $umkmList        = Umkm::where('unit_id', $unit->id)->get()->keyBy('id');

        // Note: use the existing pdf view or create a specific one if needed
        // For now, I'll assume unit.laporan-transaksi.pdf exists and works with these vars
        $pdf = Pdf::loadView('unit.laporan-transaksi.pdf', compact(
            'unit', 'pesanans', 'totalPendapatan', 'totalTransaksi', 'filters', 'umkmList'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-transaksi-' . $unit->nama_unit . '-' . now()->format('Ymd') . '.pdf');
    }

    private function getGrafikTren($unit, Request $request): array
    {
        $labels = [];
        $data   = [];

        for ($i = 5; $i >= 0; $i--) {
            $date     = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');

            $q = Pesanan::whereHas('umkm', fn($u) => $u->where('unit_id', $unit->id))
                ->where('status', 'selesai')
                ->whereBetween('created_at', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth(),
                ]);

            if ($request->filled('umkm_id')) {
                $q->where('umkm_id', $request->umkm_id);
            }

            $data[] = (int) $q->sum('total_harga');
        }

        return compact('labels', 'data');
    }

    private function getTopUmkm($unit, Request $request): \Illuminate\Database\Eloquent\Collection
    {
        return Umkm::where('unit_id', $unit->id)
            ->withSum(
                ['pesanans as total_penjualan' => function ($q) use ($request) {
                    $q->where('status', 'selesai');
                    if ($request->filled('dari')) {
                        $q->whereDate('created_at', '>=', $request->dari);
                    }
                    if ($request->filled('sampai')) {
                        $q->whereDate('created_at', '<=', $request->sampai);
                    }
                }],
                'total_harga'
            )
            ->with(['kategori'])
            ->having('total_penjualan', '>', 0)
            ->orderByDesc('total_penjualan')
            ->limit(5)
            ->get();
    }
}

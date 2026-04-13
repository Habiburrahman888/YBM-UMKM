<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Umkm;
use App\Models\Unit;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanTransaksiController extends Controller
{
    public function index(Request $request)
    {
        // ── Base Query pesanan selesai ──────────────────────────────────────
        $query = Pesanan::with(['umkm.unit', 'umkm.kategori'])
            ->where('status', 'selesai');

        // ── Filters ────────────────────────────────────────────────────────
        if ($request->filled('unit_id')) {
            $query->whereHas('umkm', fn($q) => $q->where('unit_id', $request->unit_id));
        }

        if ($request->filled('umkm_id')) {
            $query->where('umkm_id', $request->umkm_id);
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('metode_pembayaran', $request->status_pembayaran);
        }

        // ── Stat Cards ─────────────────────────────────────────────────────
        $allQuery        = clone $query;
        $totalPesanan    = (clone $allQuery)->count();
        $totalPendapatan = (clone $allQuery)->sum('total_harga');

        // Pesanan bulan ini (selesai)
        $bulanIniQuery   = Pesanan::where('status', 'selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);

        if ($request->filled('unit_id')) {
            $bulanIniQuery->whereHas('umkm', fn($q) => $q->where('unit_id', $request->unit_id));
        }
        $pendapatanBulanIni = $bulanIniQuery->sum('total_harga');

        // UMKM terlibat
        $umkmTerlibat = (clone $allQuery)->distinct('umkm_id')->count('umkm_id');

        // ── Rekap per UMKM (grouped) ────────────────────────────────────────
        $rekapUmkm = Umkm::withSum(
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
        ->with(['unit', 'kategori'])
        ->when($request->filled('unit_id'), fn($q) => $q->where('unit_id', $request->unit_id))
        ->when($request->filled('umkm_id'), fn($q) => $q->where('id', $request->umkm_id))
        ->having('jumlah_transaksi', '>', 0)
        ->orderByDesc('total_penjualan')
        ->paginate(20)
        ->withQueryString();

        // ── Data untuk filter dropdown ──────────────────────────────────────
        $unitList = Unit::orderBy('nama_unit')->get();
        $umkmList = Umkm::when($request->filled('unit_id'),
                fn($q) => $q->where('unit_id', $request->unit_id)
            )
            ->orderBy('nama_usaha')
            ->get();

        // ── Grafik Tren Pendapatan 6 Bulan ─────────────────────────────────
        $grafikTren = $this->getGrafikTren($request);

        // ── Top 5 UMKM berdasarkan pendapatan ──────────────────────────────
        $topUmkm = $this->getTopUmkm($request);

        return view('admin.laporan-transaksi.index', compact(
            'rekapUmkm',
            'totalPesanan',
            'totalPendapatan',
            'pendapatanBulanIni',
            'umkmTerlibat',
            'unitList',
            'umkmList',
            'grafikTren',
            'topUmkm'
        ));
    }

    public function exportPdf(Request $request)
    {
        // ── Rekap per Unit → per UMKM ───────────────────────────────────────
        $unitQuery = Unit::with(['umkm' => function ($q) use ($request) {
            $q->withSum(
                ['pesanans as total_penjualan' => function ($p) use ($request) {
                    $p->where('status', 'selesai');
                    if ($request->filled('dari')) {
                        $p->whereDate('created_at', '>=', $request->dari);
                    }
                    if ($request->filled('sampai')) {
                        $p->whereDate('created_at', '<=', $request->sampai);
                    }
                }],
                'total_harga'
            )
            ->withCount(
                ['pesanans as jumlah_transaksi' => function ($p) use ($request) {
                    $p->where('status', 'selesai');
                    if ($request->filled('dari')) {
                        $p->whereDate('created_at', '>=', $request->dari);
                    }
                    if ($request->filled('sampai')) {
                        $p->whereDate('created_at', '<=', $request->sampai);
                    }
                }]
            )
            ->with('kategori')
            ->when($request->filled('umkm_id'), fn($q2) => $q2->where('id', $request->umkm_id));
        }]);

        if ($request->filled('unit_id')) {
            $unitQuery->where('id', $request->unit_id);
        }

        $unitList = $unitQuery->orderBy('nama_unit')->get();

        // filter unit yang ada transaksinya
        $unitList = $unitList->filter(function ($unit) {
            return $unit->umkm->sum('total_penjualan') > 0 || $unit->umkm->sum('jumlah_transaksi') > 0;
        });

        $totalPendapatanGlobal = $unitList->sum(
            fn($u) => $u->umkm->sum('total_penjualan')
        );

        $totalTransaksiGlobal = $unitList->sum(
            fn($u) => $u->umkm->sum('jumlah_transaksi')
        );

        $filters = $request->only(['unit_id', 'umkm_id', 'dari', 'sampai']);
        $unitRef = Unit::find($request->unit_id);

        $pdf = Pdf::loadView('admin.laporan-transaksi.pdf', compact(
            'unitList',
            'totalPendapatanGlobal',
            'totalTransaksiGlobal',
            'filters',
            'unitRef',
        ))->setPaper('a4', 'portrait');

        $filename = 'laporan-transaksi-lintas-unit-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    // ── Private Helpers ────────────────────────────────────────────────────

    private function getGrafikTren(Request $request): array
    {
        $labels = [];
        $data   = [];

        for ($i = 5; $i >= 0; $i--) {
            $date     = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');

            $q = Pesanan::where('status', 'selesai')
                ->whereBetween('created_at', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth(),
                ]);

            if ($request->filled('unit_id')) {
                $q->whereHas('umkm', fn($q2) => $q2->where('unit_id', $request->unit_id));
            }

            $data[] = (int) $q->sum('total_harga');
        }

        return compact('labels', 'data');
    }

    private function getTopUmkm(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        return Umkm::withSum(
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
        ->with(['unit', 'kategori'])
        ->when($request->filled('unit_id'), fn($q) => $q->where('unit_id', $request->unit_id))
        ->having('total_penjualan', '>', 0)
        ->orderByDesc('total_penjualan')
        ->limit(5)
        ->get();
    }
}

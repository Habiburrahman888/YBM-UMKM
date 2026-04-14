<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Unit;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportUnitDanUmkmController extends Controller
{
    /**
     * Preview Laporan Unit & UMKM (Bulk) - Admin Only
     */
    public function preview(Request $request)
    {
        $query = Umkm::with(['unit', 'kategori', 'city', 'province', 'district', 'village', 'modalUmkm', 'produkUmkm']);

        // Filter
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }
        if ($request->filled('tahun_berdiri')) {
            $query->where('tahun_berdiri', $request->tahun_berdiri);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        $kategoriList = Kategori::orderBy('nama')->get();
        $unitList     = Unit::orderBy('nama_unit')
            ->when($request->filled('unit_id'), fn($q) => $q->where('id', $request->unit_id))
            ->when($request->unit_status === 'aktif', fn($q) => $q->where('is_active', true))
            ->when($request->unit_status === 'nonaktif', fn($q) => $q->where('is_active', false))
            ->get();

        // Data for filters
        $tahunBergabungList = Umkm::selectRaw('YEAR(tanggal_bergabung) as tahun')
            ->whereNotNull('tanggal_bergabung')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $tahunBerdiriList = Umkm::selectRaw('tahun_berdiri')
            ->whereNotNull('tahun_berdiri')
            ->groupBy('tahun_berdiri')
            ->orderByDesc('tahun_berdiri')
            ->pluck('tahun_berdiri');

        $breadcrumbs = [
            ['name' => 'Laporan Unit', 'url' => route('admin.report.preview')],
        ];

        // Group by unit
        if ($request->filled('unit_status')) {
            $filteredUnitIds = $unitList->pluck('id');
            $umkmList = $umkmList->filter(fn($u) => $filteredUnitIds->contains($u->unit_id));
        }
        $umkmList = $umkmList->groupBy('unit_id');

        // Jika filter UMKM diisi, sembunyikan unit yang tidak punya data matching
        if ($request->filled(['tahun', 'tahun_berdiri'])) {
            $unitIdsWithData = $umkmList->keys();
            $unitList = $unitList->whereIn('id', $unitIdsWithData);
        }

        return view('admin.report-unit-umkm.index', compact(
            'umkmList',
            'kategoriList',
            'unitList',
            'tahunBergabungList',
            'tahunBerdiriList',
            'breadcrumbs'
        ));
    }

    /**
     * Download Laporan Unit & UMKM (Bulk PDF) - Admin Only
     */
    public function downloadAll(Request $request)
    {
        $query = Umkm::with(['unit', 'kategori', 'city', 'province', 'district', 'village', 'modalUmkm', 'produkUmkm']);

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }
        if ($request->filled('tahun_berdiri')) {
            $query->where('tahun_berdiri', $request->tahun_berdiri);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        $unitList = Unit::orderBy('nama_unit')
            ->when($request->filled('unit_id'), fn($q) => $q->where('id', $request->unit_id))
            ->when($request->unit_status === 'aktif', fn($q) => $q->where('is_active', true))
            ->when($request->unit_status === 'nonaktif', fn($q) => $q->where('is_active', false))
            ->get();

        // Saring umkmList agar hanya berisi unit yang lolos filter unit_status
        if ($request->filled('unit_status')) {
            $filteredUnitIds = $unitList->pluck('id');
            $umkmList = $umkmList->filter(fn($u) => $filteredUnitIds->contains($u->unit_id));
        }

        // Group by unit_id
        $umkmList = $umkmList->groupBy('unit_id');

        // Jika filter UMKM diisi, sembunyikan unit yang tidak punya data matching
        if ($request->filled(['tahun', 'tahun_berdiri'])) {
            $unitIdsWithData = $umkmList->keys();
            $unitList = $unitList->whereIn('id', $unitIdsWithData);
        }

        $pdf = Pdf::loadView('admin.report-unit-umkm.pdf', compact('umkmList', 'unitList'))
            ->setPaper('a4', 'portrait')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-unit-umkm-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Download Laporan UMKM Individual (Single PDF) - Admin Only
     */
    public function downloadSingle(Request $request, Umkm $umkm)
    {
        $umkm->load([
            'unit',
            'user',
            'kategori',
            'province',
            'city',
            'district',
            'village',
            'modalUmkm',
            'produkUmkm',
            'creator',
            'verifiedBy',
        ]);

        $pdf = Pdf::loadView('admin.report-unit-umkm.report_single', compact('umkm'))
            ->setPaper('a4', 'portrait')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-' . Str::slug($umkm->nama_usaha) . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Download Laporan UMKM per Unit (Support Filters) - Admin Only
     */
    public function downloadByUnit(Request $request, $unitId)
    {
        $query = Umkm::with(['unit', 'kategori', 'modalUmkm', 'produkUmkm', 'province', 'city', 'district', 'village']);

        if ($unitId == 0 || $unitId == '0') {
            $unitName = 'Pusat atau Tanpa Unit';
            $query->whereNull('unit_id');
            $unitList = collect([null]);
        } else {
            $unit = Unit::findOrFail($unitId);
            $unitName = $unit->nama_unit;
            $query->where('unit_id', $unitId);
            $unitList = collect([$unit]);
        }

        // Apply Filters if any
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        $pdf = Pdf::loadView('admin.report-unit-umkm.pdf', [
            'umkmList' => $umkmList->groupBy('unit_id'),
            'unitList' => $unitList
        ])->setPaper('a4', 'portrait')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-umkm-' . Str::slug($unitName) . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->stream($filename);
    }
}

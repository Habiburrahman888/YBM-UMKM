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
     * Preview Laporan Unit & UMKM (Bulk)
     */
    public function preview(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        $query = Umkm::with(['unit', 'kategori', 'city', 'province', 'district', 'village', 'modalUmkm', 'produkUmkm']);

        if (auth()->user()->role !== 'admin') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if ($userUnit) {
                $query->where('unit_id', $userUnit->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Filter
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }
        if ($request->filled('tahun_berdiri')) {
            $query->where('tahun_berdiri', $request->tahun_berdiri);
        }


        if (auth()->user()->role === 'admin' && $request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        $kategoriList = Kategori::orderBy('nama')->get();
        $unitList     = auth()->user()->role === 'admin'
            ? Unit::orderBy('nama_unit')
                ->when($request->filled('unit_id'), fn($q) => $q->where('id', $request->unit_id))
                ->when($request->unit_status === 'aktif', fn($q) => $q->where('is_active', true))
                ->when($request->unit_status === 'nonaktif', fn($q) => $q->where('is_active', false))
                ->get()
            : collect();

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
            ['name' => auth()->user()->role === 'admin' ? 'Laporan Unit' : 'Laporan UMKM', 'url' => route('umkm.report.preview')],
        ];

        // Group by unit if admin
        if (auth()->user()->role === 'admin') {
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

            return view('admin.report-unit.index', compact(
                'umkmList',
                'kategoriList',
                'unitList',
                'tahunBergabungList',
                'tahunBerdiriList',
                'breadcrumbs'
            ));
        }

        return view('umkm.report.index', compact(
            'umkmList',
            'kategoriList',
            'unitList',
            'tahunBergabungList',
            'tahunBerdiriList',
            'breadcrumbs'
        ));
    }

    /**
     * Download Laporan Unit & UMKM (Bulk PDF)
     */
    public function downloadAll(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        $query = Umkm::with(['unit', 'kategori', 'city', 'province', 'district', 'village', 'modalUmkm', 'produkUmkm']);

        if (auth()->user()->role !== 'admin') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if ($userUnit) {
                $query->where('unit_id', $userUnit->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }
        if ($request->filled('tahun_berdiri')) {
            $query->where('tahun_berdiri', $request->tahun_berdiri);
        }


        if (auth()->user()->role === 'admin' && $request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        if (auth()->user()->role === 'admin') {
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

            $pdf = Pdf::loadView('admin.report-unit.pdf', compact('umkmList', 'unitList'))
                ->setPaper('a4', 'portrait')
                ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

            $filename = 'laporan-unit-umkm-' . now()->format('Ymd-His') . '.pdf';
            return $pdf->stream($filename);
        }

        $pdf = Pdf::loadView('umkm.pdf.report_all', compact('umkmList'))
            ->setPaper('a4', 'landscape')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-umkm-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Download Laporan UMKM Individual (Single PDF)
     */
    public function downloadSingle(Request $request, Umkm $umkm)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        if (auth()->user()->role !== 'admin') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

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

        $pdf = Pdf::loadView('umkm.pdf.report_single', compact('umkm'))
            ->setPaper('a4', 'portrait')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-' . Str::slug($umkm->nama_usaha) . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Download Laporan UMKM per Unit (Support Filters)
     */
    public function downloadByUnit(Request $request, $unitId)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        if (auth()->user()->role !== 'admin') {
            $userUnit = Unit::where('user_id', auth()->id())->first();
            if (!$userUnit || $userUnit->id != $unitId) {
                abort(403, 'Anda tidak memiliki akses ke unit ini.');
            }
        }

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

        $pdf = Pdf::loadView('admin.report-unit.pdf', [
            'umkmList' => $umkmList->groupBy('unit_id'),
            'unitList' => $unitList
        ])->setPaper('a4', 'portrait')
          ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-umkm-' . Str::slug($unitName) . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->stream($filename);
    }
}

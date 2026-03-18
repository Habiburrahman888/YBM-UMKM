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
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if (auth()->user()->role === 'admin' && $request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        $kategoriList = Kategori::orderBy('nama')->get();
        $unitList     = auth()->user()->role === 'admin'
            ? Unit::orderBy('id')->get()
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
            $umkmList = $umkmList->groupBy('unit_id');
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
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if (auth()->user()->role === 'admin' && $request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        if (auth()->user()->role === 'admin') {
            $umkmList = $umkmList->groupBy('unit_id');
            $pdf = Pdf::loadView('admin.report-unit.pdf', compact('umkmList'))
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
}

<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Unit;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportUmkmController extends Controller
{
    /**
     * Preview Laporan UMKM milik unit
     */
    public function preview(Request $request)
    {
        $user = auth()->user();
        $userUnit = Unit::where('user_id', $user->id)->first();
        
        if (!$userUnit) {
            abort(403, 'Akun Anda tidak terhubung ke unit manapun.');
        }

        $query = Umkm::with(['unit', 'kategori', 'city', 'province', 'district', 'village', 'modalUmkm', 'produkUmkm'])
            ->where('unit_id', $userUnit->id);

        // Filter
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }
        if ($request->filled('tahun_berdiri')) {
            $query->where('tahun_berdiri', $request->tahun_berdiri);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        $kategoriList = Kategori::orderBy('nama')->get();
        
        // Data for filters
        $tahunBergabungList = Umkm::where('unit_id', $userUnit->id)
            ->selectRaw('YEAR(tanggal_bergabung) as tahun')
            ->whereNotNull('tanggal_bergabung')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $tahunBerdiriList = Umkm::where('unit_id', $userUnit->id)
            ->selectRaw('tahun_berdiri')
            ->whereNotNull('tahun_berdiri')
            ->groupBy('tahun_berdiri')
            ->orderByDesc('tahun_berdiri')
            ->pluck('tahun_berdiri');

        $breadcrumbs = [
            ['name' => 'Laporan UMKM', 'url' => route('unit.report.preview')],
        ];

        return view('unit.umkm.report.index', compact(
            'umkmList',
            'kategoriList',
            'tahunBergabungList',
            'tahunBerdiriList',
            'breadcrumbs'
        ));
    }

    /**
     * Download Laporan UMKM Unit (Bulk PDF)
     */
    public function downloadAll(Request $request)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit) abort(403);

        $query = Umkm::with(['unit', 'kategori', 'city', 'province', 'district', 'village', 'modalUmkm', 'produkUmkm'])
            ->where('unit_id', $userUnit->id);

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bergabung', $request->tahun);
        }
        if ($request->filled('tahun_berdiri')) {
            $query->where('tahun_berdiri', $request->tahun_berdiri);
        }

        $umkmList = $query->withSum('modalUmkm as total_modal_sum', 'nilai_modal')
            ->orderByDesc('total_modal_sum')
            ->get();

        $pdf = Pdf::loadView('unit.umkm.pdf.report_all', compact('umkmList'))
            ->setPaper('a4', 'landscape')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-umkm-' . Str::slug($userUnit->nama_unit) . '-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Download Laporan UMKM Individual (Single PDF)
     */
    public function downloadSingle(Request $request, Umkm $umkm)
    {
        $userUnit = Unit::where('user_id', auth()->id())->first();
        if (!$userUnit || $umkm->unit_id !== $userUnit->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
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

        $pdf = Pdf::loadView('unit.umkm.pdf.report_single', compact('umkm'))
            ->setPaper('a4', 'portrait')
            ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-' . Str::slug($umkm->nama_usaha) . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->stream($filename);
    }
}

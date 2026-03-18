<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanUmkmAllController extends Controller
{
    /**
     * Download Laporan UMKM per Unit
     */
    public function downloadByUnit($unitId)
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

        if ($unitId == 0 || $unitId == '0') {
            $unitName = 'Pusat atau Tanpa Unit';
            $umkmList = Umkm::with(['unit', 'kategori', 'modalUmkm', 'produkUmkm', 'province', 'city', 'district', 'village'])
                ->whereNull('unit_id')
                ->get();
        } else {
            $unit = Unit::findOrFail($unitId);
            $unitName = $unit->nama_unit;
            $umkmList = Umkm::with(['unit', 'kategori', 'modalUmkm', 'produkUmkm', 'province', 'city', 'district', 'village'])
                ->where('unit_id', $unitId)
                ->get();
        }

        if ($umkmList->isEmpty()) {
            return back()->with('error', 'Tidak ada data UMKM untuk unit ini.');
        }

        $pdf = Pdf::loadView('admin.report-unit.pdf', [
            'umkmList' => $umkmList->groupBy('unit_id')
        ])->setPaper('a4', 'portrait')
          ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-umkm-' . Str::slug($unitName) . '-' . now()->format('Ymd') . '.pdf';
        
        return $pdf->stream($filename);
    }

    /**
     * Download Semua Laporan UMKM (Berdasarkan Filter)
     */
    public function downloadAll(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'unit'])) {
            abort(403);
        }

        $query = Umkm::with(['unit', 'kategori', 'modalUmkm', 'produkUmkm', 'province', 'city', 'district', 'village']);

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

        if ($umkmList->isEmpty()) {
            return back()->with('error', 'Tidak ada data UMKM yang ditemukan.');
        }

        if (auth()->user()->role === 'admin') {
            $pdfList = $umkmList->groupBy('unit_id');
            $viewName = 'admin.report-unit.pdf';
        } else {
            $pdfList = $umkmList;
            $viewName = 'umkm.pdf.report_all';
        }

        $pdf = Pdf::loadView($viewName, [
            'umkmList' => $pdfList
        ])->setPaper('a4', auth()->user()->role === 'admin' ? 'portrait' : 'landscape')
          ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'laporan-rekapitulasi-umkm-' . now()->format('Ymd') . '.pdf';
        
        return $pdf->stream($filename);
    }

    /**
     * Download Laporan UMKM Individual (Single PDF)
     */
    public function downloadSingle(Umkm $umkm)
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

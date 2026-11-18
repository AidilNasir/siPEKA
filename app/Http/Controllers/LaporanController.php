<?php

namespace App\Http\Controllers;

use App\Models\AnalisisKesenjangan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Display the Executive Reports & Printable Audit page.
     */
    public function index(Request $request): View
    {
        $allAnalisis = AnalisisKesenjangan::with('pegawai')
            ->latest()
            ->get();

        $totalPegawai = Pegawai::count();
        $totalAnalisis = AnalisisKesenjangan::count();
        $totalMemenuhi = AnalisisKesenjangan::where('status', 'like', '%Memenuhi%')->count();
        $totalTargetJp = AnalisisKesenjangan::sum('target_jp');
        $totalRealisasiJp = AnalisisKesenjangan::sum('jp_terealisasi');

        return view('laporan.index', compact(
            'allAnalisis',
            'totalPegawai',
            'totalAnalisis',
            'totalMemenuhi',
            'totalTargetJp',
            'totalRealisasiJp'
        ));
    }

    /**
     * Export all gap analysis records as CSV (matching the Google Sheet format).
     */
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'Laporan_Monev_Kesenjangan_ASN_BPVP_Kendari_'.date('Ymd_His').'.csv';
        $records = AnalisisKesenjangan::with('pegawai')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID_Monev',
                'NIP',
                'Nama_Pegawai',
                'Jabatan',
                'Unit_Kerja',
                'Standar_Kompetensi',
                'Kompetensi_Saat_Ini',
                'Gap_Kesenjangan',
                'Rencana_Pengembangan',
                'Target_JP',
                'JP_Terealisasi',
                'Status',
                'Catatan',
            ]);

            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->id_monev,
                    $item->nip,
                    $item->pegawai->nama ?? '-',
                    $item->pegawai->jabatan ?? '-',
                    $item->pegawai->unit_kerja ?? 'BPVP KENDARI',
                    $item->standar_kompetensi,
                    $item->kompetensi_saat_ini,
                    $item->gap_kesenjangan,
                    $item->rencana_pengembangan,
                    $item->target_jp,
                    $item->jp_terealisasi,
                    $item->status,
                    $item->catatan,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}

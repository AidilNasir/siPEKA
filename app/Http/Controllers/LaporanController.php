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
        $user = $request->user();
        $isPegawai = $user && $user->isPegawai();

        $query = AnalisisKesenjangan::with('pegawai')->latest();

        // Jika login sebagai Pegawai, batasi HANYA untuk rapor pegawai tersebut saja
        if ($isPegawai && $user->nip) {
            $query->where('nip', $user->nip);
        }

        $allAnalisis = $query->get();

        if ($isPegawai && $user->nip) {
            $totalPegawai = 1;
            $totalAnalisis = $allAnalisis->count();
            $totalMemenuhi = $allAnalisis->where('status', 'like', '%Memenuhi%')->count();
            $totalTargetJp = $allAnalisis->sum('target_jp');
            $totalRealisasiJp = $allAnalisis->sum('jp_terealisasi');
        } else {
            $totalPegawai = Pegawai::count();
            $totalAnalisis = AnalisisKesenjangan::count();
            $totalMemenuhi = AnalisisKesenjangan::where('status', 'like', '%Memenuhi%')->count();
            $totalTargetJp = AnalisisKesenjangan::sum('target_jp');
            $totalRealisasiJp = AnalisisKesenjangan::sum('jp_terealisasi');
        }

        return view('laporan.index', compact(
            'allAnalisis',
            'totalPegawai',
            'totalAnalisis',
            'totalMemenuhi',
            'totalTargetJp',
            'totalRealisasiJp',
            'isPegawai'
        ));
    }

    /**
     * Export complete Excel Workbook (.xls) with tailored column widths and 2 separate sheets
     * (Pegawai & Analisis_Kesenjangan) matching the exact official template.
     * Prevents Excel scientific notation (e.g. 1.98309E+17) by strictly formatting NIP as Text.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $user = $request->user();
        $isPegawai = $user && $user->isPegawai();

        $fileName = 'siPEKA_Monev_BPVP_Kendari_'.date('Ymd_His').'.xls';
        if ($isPegawai && $user->nip) {
            $fileName = 'Rapor_Monev_Personal_'.$user->nip.'_'.date('Ymd_His').'.xls';
            $pegawaiRecords = Pegawai::where('nip', $user->nip)->get();
            $analisisRecords = AnalisisKesenjangan::where('nip', $user->nip)->latest()->get();
        } else {
            $pegawaiRecords = Pegawai::orderBy('nama')->get();
            $analisisRecords = AnalisisKesenjangan::with('pegawai')->latest()->get();
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($pegawaiRecords, $analisisRecords) {
            $xmlEscape = fn ($val) => htmlspecialchars((string) $val, ENT_XML1, 'UTF-8');

            echo '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
            echo '<?mso-application progid="Excel.Sheet"?>'."\r\n";
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'."\r\n";
            echo ' xmlns:o="urn:schemas-microsoft-com:office:office"'."\r\n";
            echo ' xmlns:x="urn:schemas-microsoft-com:office:excel"'."\r\n";
            echo ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'."\r\n";
            echo ' xmlns:html="http://www.w3.org/TR/REC-html40">'."\r\n";

            // Styles
            echo ' <Styles>'."\r\n";
            echo '  <Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Center"/><Font ss:FontName="Arial" ss:Size="10"/></Style>'."\r\n";
            echo '  <Style ss:ID="Header"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#091E3A"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#091E3A"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#091E3A"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#091E3A"/></Borders><Font ss:FontName="Arial" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#091E3A" ss:Pattern="Solid"/></Style>'."\r\n";
            echo '  <Style ss:ID="NipText"><NumberFormat ss:Format="@"/><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders></Style>'."\r\n";
            echo '  <Style ss:ID="TextCell"><Alignment ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders></Style>'."\r\n";
            echo '  <Style ss:ID="CenterCell"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders></Style>'."\r\n";
            echo '  <Style ss:ID="WrapCell"><Alignment ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders></Style>'."\r\n";
            echo '  <Style ss:ID="NumberCell"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders><NumberFormat ss:Format="0"/></Style>'."\r\n";
            echo ' </Styles>'."\r\n";

            // SHEET 1: Pegawai
            echo ' <Worksheet ss:Name="Pegawai">'."\r\n";
            echo '  <Table>'."\r\n";
            echo '   <Column ss:Width="160"/>'."\r\n"; // nip
            echo '   <Column ss:Width="240"/>'."\r\n"; // nama
            echo '   <Column ss:Width="240"/>'."\r\n"; // jabatan
            echo '   <Column ss:Width="140"/>'."\r\n"; // unit_Kerja
            echo '   <Column ss:Width="420"/>'."\r\n"; // standar_Kompetensi_Jabatan
            echo '   <Row ss:Height="24">'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">nip</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">nama</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">jabatan</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">unit_Kerja</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">standar_Kompetensi_Jabatan</Data></Cell>'."\r\n";
            echo '   </Row>'."\r\n";
            foreach ($pegawaiRecords as $p) {
                echo '   <Row ss:Height="22">'."\r\n";
                echo '    <Cell ss:StyleID="NipText"><Data ss:Type="String">'.$xmlEscape($p->nip).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">'.$xmlEscape($p->nama).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">'.$xmlEscape($p->jabatan).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="CenterCell"><Data ss:Type="String">'.$xmlEscape($p->unit_kerja).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="WrapCell"><Data ss:Type="String">'.$xmlEscape($p->standar_kompetensi_jabatan).'</Data></Cell>'."\r\n";
                echo '   </Row>'."\r\n";
            }
            echo '  </Table>'."\r\n";
            echo ' </Worksheet>'."\r\n";

            // SHEET 2: Analisis_Kesenjangan
            echo ' <Worksheet ss:Name="Analisis_Kesenjangan">'."\r\n";
            echo '  <Table>'."\r\n";
            echo '   <Column ss:Width="160"/>'."\r\n"; // ID_Monev
            echo '   <Column ss:Width="160"/>'."\r\n"; // NIP
            echo '   <Column ss:Width="360"/>'."\r\n"; // Standar_Kompetensi
            echo '   <Column ss:Width="260"/>'."\r\n"; // Kompetensi_Saat_Ini
            echo '   <Column ss:Width="260"/>'."\r\n"; // Gap_Kesenjangan
            echo '   <Column ss:Width="220"/>'."\r\n"; // Rencana_Pengembangan
            echo '   <Column ss:Width="90"/>'."\r\n";  // Target_JP
            echo '   <Column ss:Width="110"/>'."\r\n"; // JP_Terealisasi
            echo '   <Column ss:Width="160"/>'."\r\n"; // Status
            echo '   <Row ss:Height="24">'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">ID_Monev</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">NIP</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">Standar_Kompetensi</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">Kompetensi_Saat_Ini</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">Gap_Kesenjangan</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">Rencana_Pengembangan</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">Target_JP</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">JP_Terealisasi</Data></Cell>'."\r\n";
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">Status</Data></Cell>'."\r\n";
            echo '   </Row>'."\r\n";
            foreach ($analisisRecords as $a) {
                echo '   <Row ss:Height="22">'."\r\n";
                echo '    <Cell ss:StyleID="CenterCell"><Data ss:Type="String">'.$xmlEscape($a->id_monev).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="NipText"><Data ss:Type="String">'.$xmlEscape($a->nip).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="WrapCell"><Data ss:Type="String">'.$xmlEscape($a->standar_kompetensi).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="WrapCell"><Data ss:Type="String">'.$xmlEscape($a->kompetensi_saat_ini).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="WrapCell"><Data ss:Type="String">'.$xmlEscape($a->gap_kesenjangan).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">'.$xmlEscape($a->rencana_pengembangan).'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="NumberCell"><Data ss:Type="Number">'.(int) $a->target_jp.'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="NumberCell"><Data ss:Type="Number">'.(int) $a->jp_terealisasi.'</Data></Cell>'."\r\n";
                echo '    <Cell ss:StyleID="CenterCell"><Data ss:Type="String">'.$xmlEscape($a->status).'</Data></Cell>'."\r\n";
                echo '   </Row>'."\r\n";
            }
            echo '  </Table>'."\r\n";
            echo ' </Worksheet>'."\r\n";

            echo '</Workbook>'."\r\n";
        }, 200, $headers);
    }

    /**
     * Export gap analysis records as formatted CSV.
     * Uses ="[NIP]" text formula to prevent Excel from converting 18-digit NIP into 1.98309E+17.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();
        $isPegawai = $user && $user->isPegawai();

        $query = AnalisisKesenjangan::with('pegawai');
        $fileName = 'Analisis_Kesenjangan_'.date('Ymd_His').'.csv';

        if ($isPegawai && $user->nip) {
            $query->where('nip', $user->nip);
            $fileName = 'Analisis_Kesenjangan_Personal_'.$user->nip.'_'.date('Ymd_His').'.csv';
        }

        $records = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // Sesuai persis dengan Sheet Analisis_Kesenjangan
            fputcsv($handle, [
                'ID_Monev',
                'NIP',
                'Standar_Kompetensi',
                'Kompetensi_Saat_Ini',
                'Gap_Kesenjangan',
                'Rencana_Pengembangan',
                'Target_JP',
                'JP_Terealisasi',
                'Status',
            ]);

            foreach ($records as $item) {
                // Gunakan formula ="[NIP]" agar Excel membaca NIP sebagai teks murni tanpa notasi ilmiah (1.98309E+17)
                $safeNip = '="'.$item->nip.'"';

                fputcsv($handle, [
                    $item->id_monev,
                    $safeNip,
                    $item->standar_kompetensi,
                    $item->kompetensi_saat_ini,
                    $item->gap_kesenjangan,
                    $item->rencana_pengembangan,
                    $item->target_jp,
                    $item->jp_terealisasi,
                    $item->status,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Pegawai Master Data as formatted CSV.
     * Uses ="[NIP]" text formula to prevent Excel from converting 18-digit NIP into 1.98309E+17.
     */
    public function exportPegawaiCsv(Request $request): StreamedResponse
    {
        $user = $request->user();
        $isPegawai = $user && $user->isPegawai();

        $query = Pegawai::query();
        $fileName = 'Pegawai_BPVP_Kendari_'.date('Ymd_His').'.csv';

        if ($isPegawai && $user->nip) {
            $query->where('nip', $user->nip);
            $fileName = 'Pegawai_Personal_'.$user->nip.'_'.date('Ymd_His').'.csv';
        } else {
            $query->orderBy('nama');
        }

        $records = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // Sesuai persis dengan Sheet Pegawai
            fputcsv($handle, [
                'nip',
                'nama',
                'jabatan',
                'unit_Kerja',
                'standar_Kompetensi_Jabatan',
            ]);

            foreach ($records as $p) {
                $safeNip = '="'.$p->nip.'"';

                fputcsv($handle, [
                    $safeNip,
                    $p->nama,
                    $p->jabatan,
                    $p->unit_kerja,
                    $p->standar_kompetensi_jabatan,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AnalisisKesenjangan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the siPEKA Executive Dashboard with live database stats,
     * tailored to both Administrator and Pegawai ASN roles.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isPegawai = $user && $user->isPegawai();
        $myPegawai = null;
        $myAnalisis = collect();
        $myTargetJp = 20;
        $myRealisasiJp = 0;
        $myJpProgress = 0;

        // Jika yang login adalah Pegawai, ambil data personalnya
        if ($isPegawai && $user->nip) {
            $myPegawai = Pegawai::where('nip', $user->nip)->first();
            if ($myPegawai) {
                $myAnalisis = AnalisisKesenjangan::where('nip', $myPegawai->nip)->latest()->get();
                $myTargetJp = $myAnalisis->sum('target_jp') ?: 20;
                $myRealisasiJp = $myAnalisis->sum('jp_terealisasi');
                $myJpProgress = $myTargetJp > 0 ? min(100, round(($myRealisasiJp / $myTargetJp) * 100, 1)) : 0;
            }
        }

        $totalPegawai = Pegawai::count();
        $totalAnalisis = AnalisisKesenjangan::count();
        $memenuhiHak = AnalisisKesenjangan::where('status', 'like', '%Memenuhi%')->count();
        $dalamProses = AnalisisKesenjangan::where('status', 'like', '%Proses%')->count();
        $belumMemenuhi = AnalisisKesenjangan::whereIn('status', ['Belum Memenuhi', 'Kesenjangan Kritis'])->count();

        // Calculate average JP realisasi vs target
        $targetJpSum = AnalisisKesenjangan::sum('target_jp');
        $realisasiJpSum = AnalisisKesenjangan::sum('jp_terealisasi');
        $jpAchievementRate = $targetJpSum > 0 ? round(($realisasiJpSum / $targetJpSum) * 100, 1) : 0;

        $metrics = [
            'total_asn' => [
                'value' => (string) $totalPegawai,
                'label' => 'Total ASN Terpantau',
                'change' => '100% Terdata',
                'trend' => 'up',
                'subtext' => 'Unit Kerja: BPVP Kendari',
            ],
            'total_analisis' => [
                'value' => (string) $totalAnalisis,
                'label' => 'Asesmen Kesenjangan',
                'change' => round(($totalAnalisis / max(1, $totalPegawai)) * 100, 1).'% Populasi',
                'trend' => 'up',
                'subtext' => 'Tervalidasi di Database Monev',
            ],
            'compliance_rate' => [
                'value' => (string) $memenuhiHak,
                'label' => 'Memenuhi Hak 20 JP',
                'change' => $totalAnalisis > 0 ? round(($memenuhiHak / $totalAnalisis) * 100, 1).'%' : '0%',
                'trend' => 'up',
                'subtext' => 'Sesuai PP 11/2017 & PerLAN',
            ],
            'need_intervention' => [
                'value' => (string) ($belumMemenuhi + $dalamProses),
                'label' => 'Butuh Tindak Lanjut',
                'change' => (string) $belumMemenuhi.' Kritis/Belum',
                'trend' => 'alert',
                'subtext' => 'Prioritas Pelatihan Triwulan Ini',
            ],
        ];

        // Recent monitored records
        $recentAnalisis = AnalisisKesenjangan::with('pegawai')
            ->latest()
            ->take(10)
            ->get();

        // Top Jabatan breakdown
        $jabatanStats = Pegawai::selectRaw('jabatan, count(*) as total')
            ->groupBy('jabatan')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'metrics',
            'totalPegawai',
            'totalAnalisis',
            'memenuhiHak',
            'dalamProses',
            'belumMemenuhi',
            'jpAchievementRate',
            'recentAnalisis',
            'jabatanStats',
            'isPegawai',
            'myPegawai',
            'myAnalisis',
            'myTargetJp',
            'myRealisasiJp',
            'myJpProgress'
        ));
    }
}

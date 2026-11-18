<?php

namespace App\Http\Controllers;

use App\Models\AnalisisKesenjangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RencanaPengembanganController extends Controller
{
    /**
     * Display the IDP & Hak 20 JP Development Tracker.
     */
    public function index(Request $request): View
    {
        $query = AnalisisKesenjangan::with('pegawai');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                    ->orWhere('rencana_pengembangan', 'like', "%{$search}%")
                    ->orWhere('gap_kesenjangan', 'like', "%{$search}%")
                    ->orWhereHas('pegawai', function ($sub) use ($search) {
                        $sub->where('nama', 'like', "%{$search}%")
                            ->orWhere('jabatan', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('tipe') && $request->input('tipe') !== 'all') {
            $query->where('rencana_pengembangan', $request->input('tipe'));
        }

        $plans = $query->orderBy('jp_terealisasi', 'desc')->paginate(15)->withQueryString();

        $totalPlans = AnalisisKesenjangan::count();
        $totalTargetJp = AnalisisKesenjangan::sum('target_jp');
        $totalRealisasiJp = AnalisisKesenjangan::sum('jp_terealisasi');
        $avgProgress = $totalTargetJp > 0 ? round(($totalRealisasiJp / $totalTargetJp) * 100, 1) : 0;

        $completedCount = AnalisisKesenjangan::whereColumn('jp_terealisasi', '>=', 'target_jp')->count();
        $inProgressCount = AnalisisKesenjangan::whereColumn('jp_terealisasi', '<', 'target_jp')->where('jp_terealisasi', '>', 0)->count();
        $zeroCount = AnalisisKesenjangan::where('jp_terealisasi', 0)->count();

        // Types of training breakdown
        $trainingTypes = AnalisisKesenjangan::selectRaw('rencana_pengembangan, count(*) as count, sum(jp_terealisasi) as total_jp')
            ->groupBy('rencana_pengembangan')
            ->get();

        return view('pengembangan.index', compact(
            'plans',
            'totalPlans',
            'totalTargetJp',
            'totalRealisasiJp',
            'avgProgress',
            'completedCount',
            'inProgressCount',
            'zeroCount',
            'trainingTypes'
        ));
    }

    /**
     * Quick update for realisasi JP.
     */
    public function updateJp(Request $request, AnalisisKesenjangan $analisis): RedirectResponse
    {
        $validated = $request->validate([
            'jp_terealisasi' => 'required|integer|min:0',
        ]);

        $analisis->jp_terealisasi = $validated['jp_terealisasi'];

        if ($analisis->jp_terealisasi >= $analisis->target_jp) {
            $analisis->status = $analisis->target_jp >= 100 ? 'Memenuhi Hak 100 JP' : 'Memenuhi Hak 20 JP';
        } elseif ($analisis->jp_terealisasi > 0) {
            $analisis->status = 'Dalam Proses';
        } else {
            $analisis->status = 'Belum Memenuhi';
        }

        $analisis->save();

        return redirect()->route('pengembangan.index')->with('success', 'Realisasi JP untuk '.$analisis->nip.' berhasil diperbarui.');
    }
}

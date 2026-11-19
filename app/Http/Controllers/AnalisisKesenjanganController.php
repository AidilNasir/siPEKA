<?php

namespace App\Http\Controllers;

use App\Models\AnalisisKesenjangan;
use App\Models\Pegawai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalisisKesenjanganController extends Controller
{
    /**
     * Display a listing of Analisis Kesenjangan ASN (Monev Gap).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $isPegawai = $user && $user->isPegawai();

        $query = AnalisisKesenjangan::with('pegawai');

        // Jika login sebagai Pegawai, batasi HANYA untuk data pegawai tersebut saja
        if ($isPegawai && $user->nip) {
            $query->where('nip', $user->nip);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id_monev', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('gap_kesenjangan', 'like', "%{$search}%")
                    ->orWhere('rencana_pengembangan', 'like', "%{$search}%")
                    ->orWhereHas('pegawai', function ($sub) use ($search) {
                        $sub->where('nama', 'like', "%{$search}%")
                            ->orWhere('jabatan', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('rencana') && $request->input('rencana') !== 'all') {
            $query->where('rencana_pengembangan', $request->input('rencana'));
        }

        $analisisList = $query->latest()->paginate(15)->withQueryString();

        $statuses = AnalisisKesenjangan::distinct()->whereNotNull('status')->pluck('status');
        $rencanaTypes = AnalisisKesenjangan::distinct()->whereNotNull('rencana_pengembangan')->pluck('rencana_pengembangan');

        if ($isPegawai && $user->nip) {
            $allPegawai = Pegawai::where('nip', $user->nip)->get(['id', 'nip', 'nama', 'jabatan', 'standar_kompetensi_jabatan']);
        } else {
            $allPegawai = Pegawai::orderBy('nama')->get(['id', 'nip', 'nama', 'jabatan', 'standar_kompetensi_jabatan']);
        }

        // Summary stats scoped per role
        if ($isPegawai && $user->nip) {
            $scopedQuery = AnalisisKesenjangan::where('nip', $user->nip);
            $totalEvaluated = (clone $scopedQuery)->count();
            $totalMet = (clone $scopedQuery)->where('status', 'like', '%Memenuhi%')->count();
            $totalProcess = (clone $scopedQuery)->where('status', 'like', '%Proses%')->count();
            $totalUnmet = (clone $scopedQuery)->whereIn('status', ['Belum Memenuhi', 'Kesenjangan Kritis'])->count();
        } else {
            $totalEvaluated = AnalisisKesenjangan::count();
            $totalMet = AnalisisKesenjangan::where('status', 'like', '%Memenuhi%')->count();
            $totalProcess = AnalisisKesenjangan::where('status', 'like', '%Proses%')->count();
            $totalUnmet = AnalisisKesenjangan::whereIn('status', ['Belum Memenuhi', 'Kesenjangan Kritis'])->count();
        }

        return view('analisis.index', compact(
            'analisisList',
            'statuses',
            'rencanaTypes',
            'allPegawai',
            'totalEvaluated',
            'totalMet',
            'totalProcess',
            'totalUnmet'
        ));
    }

    /**
     * Store a newly created Analisis Kesenjangan in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Hanya Administrator yang berwenang menginput data Asesmen Monev.');
        }

        $validated = $request->validate([
            'nip' => 'required|string|exists:pegawais,nip',
            'standar_kompetensi' => 'nullable|string',
            'kompetensi_saat_ini' => 'required|string',
            'gap_kesenjangan' => 'required|string',
            'rencana_pengembangan' => 'required|string',
            'target_jp' => 'required|integer|min:1',
            'jp_terealisasi' => 'required|integer|min:0',
            'status' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        // Auto-assign ID_Monev matching the spreadsheet standard
        $validated['id_monev'] = 'GAP-'.(string) round(microtime(true) * 1000);

        // Auto determine status if not explicitly set
        if (empty($validated['status'])) {
            if ($validated['jp_terealisasi'] >= $validated['target_jp']) {
                $validated['status'] = $validated['target_jp'] >= 100 ? 'Memenuhi Hak 100 JP' : 'Memenuhi Hak 20 JP';
            } elseif ($validated['jp_terealisasi'] > 0) {
                $validated['status'] = 'Dalam Proses';
            } else {
                $validated['status'] = 'Belum Memenuhi';
            }
        }

        AnalisisKesenjangan::create($validated);

        return redirect()->route('analisis.index')->with('success', 'Analisis Kesenjangan ASN berhasil dicatat dengan ID: '.$validated['id_monev']);
    }

    /**
     * Update the specified Analisis Kesenjangan in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Hanya Administrator yang berwenang memperbarui hasil asesmen monev.');
        }

        $analisis = $id instanceof AnalisisKesenjangan ? $id : AnalisisKesenjangan::findOrFail($id);

        $validated = $request->validate([
            'standar_kompetensi' => 'nullable|string',
            'kompetensi_saat_ini' => 'required|string',
            'gap_kesenjangan' => 'required|string',
            'rencana_pengembangan' => 'required|string',
            'target_jp' => 'required|integer|min:1',
            'jp_terealisasi' => 'required|integer|min:0',
            'status' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $analisis->update($validated);

        return redirect()->route('analisis.index')->with('success', 'Data Analisis Kesenjangan berhasil diperbarui.');
    }

    /**
     * Remove the specified Analisis Kesenjangan from storage.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Hanya Administrator yang berwenang menghapus data monev.');
        }

        $analisis = $id instanceof AnalisisKesenjangan ? $id : AnalisisKesenjangan::findOrFail($id);
        $analisis->delete();

        return redirect()->route('analisis.index')->with('success', 'Data Analisis Kesenjangan berhasil dihapus.');
    }
}

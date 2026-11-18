<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StandarKompetensiController extends Controller
{
    /**
     * Display the catalog of Standar Kompetensi Jabatan (SKJ) for BPVP Kendari.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $jabatansQuery = Pegawai::select('jabatan')
            ->distinct();

        if ($search) {
            $jabatansQuery->where('jabatan', 'like', "%{$search}%");
        }

        $uniqueJabatans = $jabatansQuery->orderBy('jabatan')->pluck('jabatan');

        $catalog = $uniqueJabatans->map(function ($jabatan) {
            $pegawaiList = Pegawai::where('jabatan', $jabatan)->get();
            $skj = $pegawaiList->firstWhere('standar_kompetensi_jabatan', '!=', null)->standar_kompetensi_jabatan ?? 'Belum ada standar kompetensi baku';

            return [
                'jabatan' => $jabatan,
                'pegawai_count' => $pegawaiList->count(),
                'standar_kompetensi' => $skj,
                'pegawai_samples' => $pegawaiList->take(3),
            ];
        });

        return view('kompetensi.index', compact('catalog'));
    }

    /**
     * Update SKJ for all Pegawai with a specific jabatan.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jabatan' => 'required|string',
            'standar_kompetensi_jabatan' => 'required|string',
        ]);

        Pegawai::where('jabatan', $validated['jabatan'])->update([
            'standar_kompetensi_jabatan' => $validated['standar_kompetensi_jabatan'],
        ]);

        return redirect()->route('kompetensi.index')->with('success', 'Standar Kompetensi Jabatan untuk "'.$validated['jabatan'].'" berhasil diperbarui.');
    }
}

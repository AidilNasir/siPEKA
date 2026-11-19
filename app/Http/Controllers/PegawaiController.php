<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    /**
     * Display a listing of ASN Pegawai BPVP Kendari.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $isPegawai = $user && $user->isPegawai();

        $query = Pegawai::with('analisisKesenjangan');

        // Jika login sebagai Pegawai, tampilkan profil pegawai itu saja
        if ($isPegawai && $user->nip) {
            $query->where('nip', $user->nip);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jabatan') && $request->input('jabatan') !== 'all') {
            $query->where('jabatan', $request->input('jabatan'));
        }

        if ($request->filled('status_analisis')) {
            if ($request->input('status_analisis') === 'sudah') {
                $query->has('analisisKesenjangan');
            } elseif ($request->input('status_analisis') === 'belum') {
                $query->doesntHave('analisisKesenjangan');
            }
        }

        $pegawais = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();

        $allJabatans = Pegawai::distinct()->orderBy('jabatan')->pluck('jabatan');

        if ($isPegawai && $user->nip) {
            $totalCount = Pegawai::where('nip', $user->nip)->count();
            $assessedCount = Pegawai::where('nip', $user->nip)->has('analisisKesenjangan')->count();
        } else {
            $totalCount = Pegawai::count();
            $assessedCount = Pegawai::has('analisisKesenjangan')->count();
        }

        return view('pegawai.index', compact('pegawais', 'allJabatans', 'totalCount', 'assessedCount', 'isPegawai'));
    }

    /**
     * Store a newly created Pegawai in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Hanya Administrator yang berwenang menambahkan ASN baru.');
        }

        $validated = $request->validate([
            'nip' => 'required|string|unique:pegawais,nip|max:50',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'standar_kompetensi_jabatan' => 'nullable|string',
        ]);

        Pegawai::create($validated);

        return redirect()->route('pegawai.index')->with('success', 'Data ASN Pegawai berhasil ditambahkan.');
    }

    /**
     * Update the specified Pegawai in storage.
     */
    public function update(Request $request, Pegawai $pegawai): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Hanya Administrator yang berwenang memperbarui data ASN.');
        }

        $validated = $request->validate([
            'nip' => 'required|string|max:50|unique:pegawais,nip,'.$pegawai->id,
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'standar_kompetensi_jabatan' => 'nullable|string',
        ]);

        $oldNip = $pegawai->nip;
        $pegawai->update($validated);

        // Sinkronisasi otomatis ke akun User login pegawai
        User::where('nip', $oldNip)->update([
            'nip' => $validated['nip'],
            'name' => $validated['nama'],
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Data ASN Pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified Pegawai from storage.
     */
    public function destroy(Request $request, Pegawai $pegawai): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            return back()->with('error', 'Hanya Administrator yang berwenang menghapus data ASN.');
        }

        // Hapus akun user terkait agar selalu sinkron
        User::where('nip', $pegawai->nip)->delete();
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data ASN Pegawai berhasil dihapus.');
    }
}

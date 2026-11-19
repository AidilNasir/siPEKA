<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman Login siPEKA dengan Single Card UI/UX Figma-Grade.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login dengan deteksi otomatis nama pegawai (lengkap maupun parsial) & admin.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Nama pegawai atau username wajib diisi.',
            'password.required' => 'Password atau NIP wajib diisi.',
        ]);

        $inputUsername = trim($request->username);
        $inputPassword = trim($request->password);
        $lowerUsername = strtolower($inputUsername);
        $cleanPasswordNip = preg_replace('/[^0-9]/', '', $inputPassword);
        $cleanUsernameNip = preg_replace('/[^0-9]/', '', $inputUsername);

        // ========================================================
        // 1. DETEKSI OTOMATIS: Role Admin (username: admin, password: admin)
        // ========================================================
        if ($lowerUsername === 'admin') {
            User::firstOrCreate(
                ['username' => 'admin'],
                [
                    'name' => 'Administrator BPVP',
                    'email' => 'admin@bpvpkendari.kemnaker.go.id',
                    'password' => Hash::make('admin'),
                    'role' => 'admin',
                    'nip' => null,
                ]
            );

            if (Auth::attempt(['username' => 'admin', 'password' => $inputPassword], $request->filled('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang kembali, Administrator BPVP Kendari!');
            }

            return back()
                ->withInput($request->only('username'))
                ->withErrors(['password' => 'Password Administrator salah.']);
        }

        // ========================================================
        // 2. DETEKSI OTOMATIS: Role Pegawai ASN
        // Pegawai dapat login dengan memasukkan nama parsial / nama lengkap,
        // dan password menggunakan NIP resmi mereka.
        // ========================================================
        $pegawai = null;

        // A. Jika password berisi angka NIP (min 6 digit), cari pegawai berdasarkan NIP
        if (! empty($cleanPasswordNip) && strlen($cleanPasswordNip) >= 6) {
            $pegawaiCandidate = Pegawai::whereRaw("REPLACE(nip, ' ', '') = ?", [$cleanPasswordNip])
                ->orWhere('nip', $inputPassword)
                ->first();

            if ($pegawaiCandidate) {
                // Cocokkan apakah username cocok dengan nama pegawai (parsial/lengkap), kata kunci, atau 'pegawai'
                $lowerNama = strtolower($pegawaiCandidate->nama);
                $keywords = array_filter(explode(' ', preg_replace('/[^a-zA-Z0-9]/', ' ', $lowerUsername)));

                $nameMatches = false;
                if ($lowerUsername === 'pegawai' || str_contains($lowerNama, $lowerUsername) || $cleanUsernameNip === $cleanPasswordNip) {
                    $nameMatches = true;
                } else {
                    foreach ($keywords as $kw) {
                        if (strlen($kw) >= 3 && str_contains($lowerNama, $kw)) {
                            $nameMatches = true;
                            break;
                        }
                    }
                }

                if ($nameMatches) {
                    $pegawai = $pegawaiCandidate;
                }
            }
        }

        // B. Jika belum ditemukan, cari kandidat Pegawai berdasarkan Nama (parsial maupun kata kunci)
        if (! $pegawai) {
            // 1. Cari exact match atau substring nama
            $candidates = Pegawai::where('nama', 'like', '%'.$inputUsername.'%')->get();

            // 2. Jika tidak ditemukan, cari per kata kunci nama (misal "habibi" atau "anshari")
            if ($candidates->isEmpty()) {
                $keywords = array_filter(explode(' ', preg_replace('/[^a-zA-Z0-9]/', ' ', $inputUsername)));
                if (! empty($keywords)) {
                    $query = Pegawai::query();
                    foreach ($keywords as $kw) {
                        if (strlen($kw) >= 3) {
                            $query->orWhere('nama', 'like', '%'.$kw.'%');
                        }
                    }
                    $candidates = $query->get();
                }
            }

            // Jika ada kandidat nama, periksa apakah password cocok dengan salah satu NIP kandidat
            if ($candidates->isNotEmpty()) {
                foreach ($candidates as $cand) {
                    $candCleanNip = preg_replace('/[^0-9]/', '', $cand->nip);
                    if ($candCleanNip === $cleanPasswordNip || $cand->nip === $inputPassword) {
                        $pegawai = $cand;
                        break;
                    }
                }

                // Jika nama pegawai dikenali namun password/NIP yang dimasukkan salah
                if (! $pegawai) {
                    $matchedName = $candidates->first()->nama;

                    return back()
                        ->withInput($request->only('username'))
                        ->withErrors(['password' => 'Pegawai "'.$matchedName.'" terdeteksi, namun password/NIP salah. Masukkan NIP resmi Anda sebagai password.']);
                }
            }
        }

        // C. Jika pegawai berhasil terverifikasi
        if ($pegawai) {
            $cleanNip = preg_replace('/[^0-9]/', '', $pegawai->nip);

            $user = User::firstOrCreate(
                ['nip' => $pegawai->nip],
                [
                    'name' => $pegawai->nama,
                    'username' => 'pegawai_'.$cleanNip,
                    'email' => $cleanNip.'@bpvpkendari.kemnaker.go.id',
                    'password' => Hash::make($cleanNip),
                    'role' => 'pegawai',
                ]
            );

            // Sinkronkan nama dan role jika ada pembaruan
            $user->name = $pegawai->nama;
            $user->role = 'pegawai';
            $user->save();

            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang di siPEKA, '.$pegawai->nama.' ('.$pegawai->jabatan.')');
        }

        // D. Jika username adalah 'pegawai' namun NIP tidak ditemukan
        if ($lowerUsername === 'pegawai') {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['password' => 'NIP Pegawai ("'.$inputPassword.'") tidak terdaftar dalam database ASN BPVP Kendari.']);
        }

        // E. Kredensial tidak dikenali
        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Nama pegawai atau akun tidak ditemukan. Masukkan nama Anda (lengkap/parsial) dengan password NIP.']);
    }

    /**
     * Proses keluar sistem (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem siPEKA BPVP Kendari.');
    }
}

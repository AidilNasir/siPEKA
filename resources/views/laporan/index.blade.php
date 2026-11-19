@extends('layouts.app')

@section('title', 'Laporan Monev Kesenjangan')
@section('header_title', 'Laporan Formal Pemantauan Kesenjangan Aparatur BPVP Kendari')

@section('content')
<div 
    class="space-y-6 max-w-7xl mx-auto"
    x-data="{
        nomorSurat: localStorage.getItem('sipeka_nomor_surat') || 'B.04/BPVP-KDR/KP.03.01/XI/{{ date('Y') }}',
        tanggalSurat: localStorage.getItem('sipeka_tanggal_surat') || '{{ date('d F Y') }}',
        kotaSurat: localStorage.getItem('sipeka_kota_surat') || 'Kendari',
        namaKepala: localStorage.getItem('sipeka_nama_kepala') || 'M.A. HABIBI KADIR, S.T., M.M.',
        nipKepala: localStorage.getItem('sipeka_nip_kepala') || '198108022009121001',
        namaKasubag: localStorage.getItem('sipeka_nama_kasubag') || 'ANSHARI, S.Sos., M.Ak',
        nipKasubag: localStorage.getItem('sipeka_nip_kasubag') || '197707312003121007',
        isPanelOpen: false,
        saveData() {
            localStorage.setItem('sipeka_nomor_surat', this.nomorSurat);
            localStorage.setItem('sipeka_tanggal_surat', this.tanggalSurat);
            localStorage.setItem('sipeka_kota_surat', this.kotaSurat);
            localStorage.setItem('sipeka_nama_kepala', this.namaKepala);
            localStorage.setItem('sipeka_nip_kepala', this.nipKepala);
            localStorage.setItem('sipeka_nama_kasubag', this.namaKasubag);
            localStorage.setItem('sipeka_nip_kasubag', this.nipKasubag);
        },
        resetDefault() {
            this.nomorSurat = 'B.04/BPVP-KDR/KP.03.01/XI/{{ date('Y') }}';
            this.tanggalSurat = '{{ date('d F Y') }}';
            this.kotaSurat = 'Kendari';
            this.namaKepala = 'M.A. HABIBI KADIR, S.T., M.M.';
            this.nipKepala = '198108022009121001';
            this.namaKasubag = 'ANSHARI, S.Sos., M.Ak';
            this.nipKasubag = '197707312003121007';
            this.saveData();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Format naskah dinas direset ke standar',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        },
        notifySave() {
            this.saveData();
            this.isPanelOpen = false;
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Nomor & naskah dinas berhasil diterapkan!',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        }
    }"
    x-init="$watch('nomorSurat', () => saveData()); $watch('tanggalSurat', () => saveData()); $watch('kotaSurat', () => saveData()); $watch('namaKepala', () => saveData()); $watch('nipKepala', () => saveData()); $watch('namaKasubag', () => saveData()); $watch('nipKasubag', () => saveData());"
>

    <!-- Actions Bar (Screen only - Responsive & Figma-grade) -->
    <div class="no-print rounded-2xl bg-white border border-slate-200/80 p-4 sm:p-5 shadow-[0_2px_12px_rgba(0,0,0,0.04)] flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="min-w-0 flex-1">
            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Dokumen Formal Hasil Pemantauan Kesenjangan Kompetensi Aparatur Sipil Negara</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola nomor naskah dinas, cetak laporan resmi, atau ekspor ke format Excel.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0 self-start md:self-center">
            <!-- Edit Nomor & Naskah Dinas Button -->
            <button 
                type="button"
                @click="isPanelOpen = !isPanelOpen" 
                class="h-10 px-3.5 whitespace-nowrap inline-flex items-center justify-center gap-2 text-xs font-semibold rounded-xl transition-all cursor-pointer shadow-2xs shrink-0"
                :class="isPanelOpen ? 'bg-indigo-600 text-white border border-indigo-600' : 'text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80'"
            >
                <svg class="w-4 h-4 stroke-[2] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                <span x-text="isPanelOpen ? 'Tutup Input Nomor' : 'Input Nomor Dokumen'">Input Nomor Dokumen</span>
            </button>

            <!-- Print Button (Refined Figma-grade) -->
            <button 
                type="button"
                onclick="window.print()" 
                class="h-10 px-3.5 whitespace-nowrap inline-flex items-center justify-center gap-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-[0.98] border border-slate-200/80 rounded-xl transition-all cursor-pointer shadow-2xs shrink-0"
            >
                <svg class="w-4 h-4 text-slate-600 stroke-[2] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                <span>Cetak Dokumen</span>
            </button>

            <!-- Export Excel Button -->
            <a 
                href="{{ route('laporan.exportExcel') }}" 
                class="h-10 px-4 whitespace-nowrap inline-flex items-center justify-center gap-2 text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 active:scale-[0.98] rounded-xl transition-all shadow-md shadow-emerald-900/20 cursor-pointer shrink-0"
                title="Unduh format Excel (.xls) dengan 2 sheet (Pegawai & Analisis Kesenjangan), kolom rapi otomatis, dan NIP teks"
            >
                <svg class="w-4 h-4 stroke-[2] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="8" y1="13" x2="16" y2="13" />
                    <line x1="8" y1="17" x2="16" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Panel Input Naskah Dinas (Hanya tampil di layar monitor, otomatis tidak ikut ter-print) -->
    <div 
        x-show="isPanelOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="no-print rounded-2xl bg-indigo-50/70 border border-indigo-100 p-5 sm:p-6 shadow-sm space-y-5"
        style="display: none;"
    >
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-indigo-100 pb-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-950">Sistem Input & Penomoran Naskah Dinas BPVP Kendari</h4>
                    <p class="text-xs text-indigo-700/80">Ketik nomor surat atau tanggal di bawah, dokumen resmi di bawah langsung berubah secara *realtime*.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button 
                    type="button" 
                    @click="resetDefault()" 
                    class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-indigo-200/80 rounded-lg hover:bg-slate-50 transition-colors"
                >
                    Reset Standar
                </button>
                <button 
                    type="button" 
                    @click="notifySave()" 
                    class="px-3.5 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-xs"
                >
                    Terapkan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Input Nomor Dokumen -->
            <div class="lg:col-span-2 space-y-1.5">
                <label class="block text-xs font-bold text-indigo-950">
                    Nomor Naskah Dinas / Surat Monev
                </label>
                <input 
                    type="text" 
                    x-model="nomorSurat" 
                    placeholder="Contoh: B.04/BPVP-KDR/KP.03.01/XI/{{ date('Y') }}" 
                    class="w-full h-10 px-3.5 text-xs font-mono font-bold text-slate-900 bg-white border border-indigo-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-2xs"
                >
                <p class="text-[11px] text-slate-500">Sesuaikan dengan nomor agenda surat dinas di Sub Bagian Umum.</p>
            </div>

            <!-- 2. Input Kota -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-indigo-950">
                    Kota Tempat Penetapan
                </label>
                <input 
                    type="text" 
                    x-model="kotaSurat" 
                    placeholder="Kendari" 
                    class="w-full h-10 px-3.5 text-xs font-semibold text-slate-800 bg-white border border-indigo-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-2xs"
                >
                <p class="text-[11px] text-slate-500">Kota penandatanganan dokumen.</p>
            </div>

            <!-- 3. Input Tanggal -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-indigo-950">
                    Tanggal Pengesahan
                </label>
                <input 
                    type="text" 
                    x-model="tanggalSurat" 
                    placeholder="{{ date('d F Y') }}" 
                    class="w-full h-10 px-3.5 text-xs font-semibold text-slate-800 bg-white border border-indigo-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-2xs"
                >
                <p class="text-[11px] text-slate-500">Format tanggal naskah dinas resmi.</p>
            </div>
        </div>

        <!-- Bagian Pengaturan Pejabat Penandatangan -->
        <div class="pt-2 border-t border-indigo-100/80">
            <p class="text-xs font-bold uppercase tracking-wider text-indigo-900/80 mb-3">Pejabat Penandatangan Dokumen</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kepala Balai -->
                <div class="p-3.5 rounded-xl bg-white border border-indigo-100 space-y-2.5">
                    <span class="text-xs font-bold text-indigo-950 block">Pihak 1: Kepala Balai Pelatihan Vokasi dan Produktivitas Kendari</span>
                    <div class="space-y-2">
                        <input 
                            type="text" 
                            x-model="namaKepala" 
                            placeholder="Nama Lengkap & Gelar Kepala Balai" 
                            class="w-full h-9 px-3 text-xs font-medium text-slate-800 border border-slate-200 rounded-lg focus:outline-none focus:border-indigo-500"
                        >
                        <input 
                            type="text" 
                            x-model="nipKepala" 
                            placeholder="NIP Kepala Balai" 
                            class="w-full h-9 px-3 text-xs font-mono text-slate-800 border border-slate-200 rounded-lg focus:outline-none focus:border-indigo-500"
                        >
                    </div>
                </div>

                <!-- Kasubag Umum -->
                <div class="p-3.5 rounded-xl bg-white border border-indigo-100 space-y-2.5">
                    <span class="text-xs font-bold text-indigo-950 block">Pihak 2: Kepala Sub Bagian Umum / Asesor Monev</span>
                    <div class="space-y-2">
                        <input 
                            type="text" 
                            x-model="namaKasubag" 
                            placeholder="Nama Lengkap & Gelar Kasubag Umum" 
                            class="w-full h-9 px-3 text-xs font-medium text-slate-800 border border-slate-200 rounded-lg focus:outline-none focus:border-indigo-500"
                        >
                        <input 
                            type="text" 
                            x-model="nipKasubag" 
                            placeholder="NIP Kasubag Umum" 
                            class="w-full h-9 px-3 text-xs font-mono text-slate-800 border border-slate-200 rounded-lg focus:outline-none focus:border-indigo-500"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Official Document Container (Format Formal Naskah Dinas, Font Arial 12pt, Printable) -->
    <div class="bg-white border border-slate-300 p-6 sm:p-10 lg:p-14 shadow-sm printable-area formal-document">
        
        <!-- 1. Kop Surat Resmi BPVP Kendari Kemnaker RI -->
        <div class="border-b-[3px] border-black pb-3 mb-6 kop-surat-wrapper">
            <img 
                src="{{ asset('images/kop-kemnaker.png') }}" 
                alt="Kop Surat Resmi Balai Pelatihan Vokasi dan Produktivitas Kendari - Kementerian Ketenagakerjaan Republik Indonesia" 
                class="w-full max-w-4xl mx-auto h-auto object-contain block"
            >
        </div>

        <!-- 2. Judul Laporan Formal Naskah Dinas (Tanpa akronim aplikasi) -->
        <div class="text-center my-6 space-y-1">
            <h2 class="text-[13pt] sm:text-[14pt] font-bold uppercase tracking-wide text-black leading-tight underline decoration-black underline-offset-4">
                LAPORAN HASIL PEMANTAUAN DAN EVALUASI KESENJANGAN KOMPETENSI APARATUR SIPIL NEGARA
            </h2>
            <p class="text-[12pt] font-bold uppercase tracking-wider text-black pt-1">
                BALAI PELATIHAN VOKASI DAN PRODUKTIVITAS KENDARI
            </p>
            <p class="text-[11pt] font-semibold text-black">
                TAHUN ANGGARAN {{ date('Y') }} &bull; PERIODE EVALUASI TAHUNAN
            </p>
            <p class="text-[10pt] font-mono text-black/80 pt-1 inline-flex items-center justify-center gap-1.5">
                <span>Nomor:</span>
                <span class="font-bold underline decoration-slate-400/80 decoration-dotted" x-text="nomorSurat">B.04/BPVP-KDR/KP.03.01/XI/{{ date('Y') }}</span>
                <button 
                    type="button" 
                    @click="isPanelOpen = true; window.scrollTo({ top: 0, behavior: 'smooth' })" 
                    class="no-print p-1 text-slate-400 hover:text-indigo-600 rounded-md hover:bg-slate-100 transition-colors cursor-pointer"
                    title="Ubah Nomor Naskah Dinas"
                >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </button>
            </p>
        </div>

        <!-- 3. Narasi Formal: Dasar Hukum & Ruang Lingkup -->
        <div class="my-6 space-y-3 text-[12pt] text-black leading-relaxed text-justify">
            <div>
                <p class="font-bold">I. DASAR HUKUM</p>
                <ol class="list-decimal pl-6 space-y-1 text-[11pt]">
                    <li>Undang-Undang Republik Indonesia Nomor 20 Tahun 2023 tentang Aparatur Sipil Negara;</li>
                    <li>Peraturan Pemerintah Nomor 11 Tahun 2017 tentang Manajemen Pegawai Negeri Sipil sebagaimana telah diubah dengan Peraturan Pemerintah Nomor 17 Tahun 2020;</li>
                    <li>Peraturan Lembaga Administrasi Negara Republik Indonesia tentang Pengembangan Kompetensi Pegawai Negeri Sipil (Kewajiban Pemenuhan Minimal 20 Jam Pelajaran per tahun);</li>
                    <li>Standar Kompetensi Jabatan (SKJ) di lingkungan Balai Pelatihan Vokasi dan Produktivitas Kendari, Kementerian Ketenagakerjaan RI.</li>
                </ol>
            </div>

            <div class="pt-2">
                <p class="font-bold">II. MAKSUD DAN TUJUAN</p>
                <p class="text-[11pt]">
                    Laporan ini disusun sebagai dokumen resmi pemantauan dan evaluasi terhadap kesenjangan (<em>gap</em>) kompetensi aparatur berdasarkan Standar Kompetensi Jabatan (SKJ) yang dipersyaratkan, serta pengawalan pemenuhan hak minimal 20 Jam Pelajaran (JP) pengembangan kapasitas aparatur per tahun di lingkungan Balai Pelatihan Vokasi dan Produktivitas Kendari.
                </p>
            </div>
        </div>

        <!-- 4. Tabel Rekapitulasi Statistik Formal -->
        <div class="my-6">
            <p class="font-bold text-[12pt] text-black mb-2">III. REKAPITULASI HASIL PEMANTAUAN (MONEV)</p>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-black text-[11pt] text-black">
                    <thead>
                        <tr class="bg-slate-100 border-b border-black font-bold text-center">
                            <th class="border border-black p-2 w-12">No</th>
                            <th class="border border-black p-2 text-left">Indikator Pemantauan</th>
                            <th class="border border-black p-2 w-44 text-center">Hasil Capaian</th>
                            <th class="border border-black p-2 w-48 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black p-2 text-center">1</td>
                            <td class="border border-black p-2">Total Populasi Pegawai ASN BPVP Kendari</td>
                            <td class="border border-black p-2 text-center font-bold">{{ $totalPegawai }} Pegawai</td>
                            <td class="border border-black p-2">Data Induk Master ASN</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 text-center">2</td>
                            <td class="border border-black p-2">Jumlah Pegawai Telah Terasesmen Monev Gap</td>
                            <td class="border border-black p-2 text-center font-bold">{{ $totalAnalisis }} Pegawai</td>
                            <td class="border border-black p-2">Selesai Dianalisis</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 text-center">3</td>
                            <td class="border border-black p-2">Pegawai Telah Memenuhi Hak 20 JP</td>
                            <td class="border border-black p-2 text-center font-bold">{{ $totalMemenuhi }} Pegawai</td>
                            <td class="border border-black p-2">{{ $totalAnalisis > 0 ? round(($totalMemenuhi / $totalAnalisis) * 100, 1) : 0 }}% Kepatuhan</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 text-center">4</td>
                            <td class="border border-black p-2">Akumulasi Realisasi Jam Pelajaran (JP)</td>
                            <td class="border border-black p-2 text-center font-bold">{{ $totalRealisasiJp }} / {{ $totalTargetJp }} JP</td>
                            <td class="border border-black p-2">{{ $totalTargetJp > 0 ? round(($totalRealisasiJp / $totalTargetJp) * 100, 1) : 0 }}% Tercapai</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. Matriks Rincian Hasil Analisis Kesenjangan Formal (Tanpa Kolom ID, Lega dan Anti-Tumpuk) -->
        <div class="my-6">
            <p class="font-bold text-[12pt] text-black mb-2">IV. MATRIKS HASIL ANALISIS KESENJANGAN DAN RENCANA PENGEMBANGAN ASN</p>
            <div class="table-responsive-wrapper">
                <table class="w-full text-left border-collapse border border-black text-[9pt] text-black table-fixed formal-table">
                    <colgroup>
                        <col style="width: 4%;">
                        <col style="width: 17%;">
                        <col style="width: 13%;">
                        <col style="width: 18%;">
                        <col style="width: 14%;">
                        <col style="width: 10%;">
                        <col style="width: 11%;">
                        <col style="width: 13%;">
                    </colgroup>
                    <thead>
                        <tr class="bg-slate-100 border-b border-black font-bold uppercase text-[8pt] sm:text-[8.5pt]">
                            <th class="border border-black px-1 py-2 text-center align-middle">No</th>
                            <th class="border border-black px-2 py-2 align-middle">Nama Pegawai / NIP</th>
                            <th class="border border-black px-2 py-2 align-middle">Jabatan</th>
                            <th class="border border-black px-2 py-2 align-middle">Kesenjangan Kompetensi (Gap)</th>
                            <th class="border border-black px-2 py-2 align-middle">Rencana Pengembangan</th>
                            <th class="border border-black px-1.5 py-2 text-center align-middle leading-tight">TARGET<br>(JP)</th>
                            <th class="border border-black px-1.5 py-2 text-center align-middle leading-tight">REALISASI<br>(JP)</th>
                            <th class="border border-black px-1.5 py-2 text-center align-middle leading-tight">STATUS<br>HAK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allAnalisis as $index => $item)
                        <tr class="align-top">
                            <td class="border border-black px-1 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-black px-2 py-2 break-words">
                                <span class="font-bold block">{{ $item->pegawai->nama ?? '-' }}</span>
                                <span class="text-[8.5pt] font-mono text-black/80 block">{{ $item->nip }}</span>
                            </td>
                            <td class="border border-black px-2 py-2 break-words">{{ $item->pegawai->jabatan ?? '-' }}</td>
                            <td class="border border-black px-2 py-2 break-words text-justify">{{ $item->gap_kesenjangan }}</td>
                            <td class="border border-black px-2 py-2 break-words">{{ $item->rencana_pengembangan }}</td>
                            <td class="border border-black px-1.5 py-2 text-center font-medium">{{ $item->target_jp }}</td>
                            <td class="border border-black px-1.5 py-2 text-center font-bold">{{ $item->jp_terealisasi }}</td>
                            <td class="border border-black px-1.5 py-2 text-center font-semibold text-[8pt] leading-tight break-words">
                                {{ $item->status }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="border border-black p-4 text-center text-black/70 italic">
                                Belum ada rekam pemantauan kesenjangan kompetensi yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. Penutup Formal -->
        <div class="my-6 text-[12pt] text-black leading-relaxed text-justify">
            <p class="font-bold">V. PENUTUP</p>
            <p class="text-[11pt]">
                Demikian laporan hasil pemantauan kesenjangan kompetensi aparatur sipil negara ini disusun dengan sebenar-benarnya untuk dijadikan bahan pertimbangan pengusulan keikutsertaan pelatihan, workshop, bimbingan teknis, dan sertifikasi profesi guna mendukung peningkatan produktivitas serta pemenuhan Standar Kompetensi Jabatan di Balai Pelatihan Vokasi dan Produktivitas Kendari.
            </p>
        </div>

        <!-- 7. Lembar Pengesahan / Tanda Tangan Formal Tata Naskah Dinas -->
        <div class="mt-10 pt-4 text-[12pt] text-black signature-block">
            <div class="flex flex-row justify-between items-start gap-8">
                <!-- Penanda Tangan Kiri: Mengetahui Kepala Balai -->
                <div class="w-1/2 text-center space-y-16">
                    <div>
                        <p class="leading-normal">Mengetahui,</p>
                        <p class="font-bold leading-normal">Kepala Balai Pelatihan Vokasi dan Produktivitas Kendari,</p>
                    </div>
                    <div>
                        <p class="font-bold underline uppercase" x-text="namaKepala">M.A. HABIBI KADIR, S.T., M.M.</p>
                        <p class="text-[11pt]">NIP. <span x-text="nipKepala">198108022009121001</span></p>
                    </div>
                </div>

                <!-- Penanda Tangan Kanan: Asesor Monev / Kasubag Umum -->
                <div class="w-1/2 text-center space-y-16">
                    <div>
                        <p class="leading-normal"><span x-text="kotaSurat">Kendari</span>, <span x-text="tanggalSurat">{{ date('d F Y') }}</span></p>
                        <p class="font-bold leading-normal">Kepala Sub Bagian Umum,</p>
                    </div>
                    <div>
                        <p class="font-bold underline uppercase" x-text="namaKasubag">ANSHARI, S.Sos., M.Ak</p>
                        <p class="text-[11pt]">NIP. <span x-text="nipKasubag">197707312003121007</span></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Specific Formal Document Typography & Print Rules (Anti Cut-Off & Font Arial) -->
<style>
.formal-document, 
.formal-document * {
    font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif !important;
}

@media screen {
    .table-responsive-wrapper {
        overflow-x: auto;
    }
}

@media print {
    @page {
        size: A4 landscape;
        margin: 12mm 10mm 12mm 10mm;
    }

    /* Reset all overflow constraints so pages paginate seamlessly */
    html, body {
        height: auto !important;
        min-height: 100% !important;
        overflow: visible !important;
        background: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    aside, header, nav, .no-print {
        display: none !important;
    }

    main, 
    .printable-area, 
    .table-responsive-wrapper {
        overflow: visible !important;
        height: auto !important;
        max-height: none !important;
        position: static !important;
        display: block !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .kop-surat-wrapper {
        width: 100% !important;
        margin-bottom: 20px !important;
    }

    .kop-surat-wrapper img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
    }

    table.formal-table {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        page-break-inside: auto !important;
    }

    table.formal-table thead {
        display: table-header-group !important;
    }

    table.formal-table tr {
        page-break-inside: avoid !important;
        page-break-after: auto !important;
    }

    table.formal-table th, 
    table.formal-table td {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        border: 1px solid #000000 !important;
        padding: 6px 3px !important;
        font-size: 8.5pt !important;
        line-height: 1.2 !important;
        box-sizing: border-box !important;
    }

    table.formal-table th {
        background-color: #f1f5f9 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        overflow: hidden !important;
    }

    .signature-block {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        margin-top: 35px !important;
    }
}
</style>
@endsection

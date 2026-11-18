@extends('layouts.app')

@section('title', 'Laporan Monev Kesenjangan')
@section('header_title', 'Laporan Formal Pemantauan Kesenjangan Aparatur BPVP Kendari')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Actions Bar (Screen only - Responsive & Figma-grade) -->
    <div class="no-print rounded-2xl bg-white border border-slate-200/80 p-4 sm:p-5 shadow-[0_2px_12px_rgba(0,0,0,0.04)] flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="min-w-0 flex-1">
            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Dokumen Formal Hasil Pemantauan Kesenjangan Kompetensi Aparatur Sipil Negara</h3>
        </div>

        <div class="flex items-center gap-3 shrink-0 self-start md:self-center">
            <!-- Print Button (Refined Figma-grade) -->
            <button 
                type="button"
                onclick="window.print()" 
                class="h-10 px-4 whitespace-nowrap inline-flex items-center justify-center gap-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-[0.98] border border-slate-200/80 rounded-xl transition-all cursor-pointer shadow-2xs shrink-0"
            >
                <svg class="w-4 h-4 text-slate-600 stroke-[2] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                <span>Cetak Dokumen Resmi</span>
            </button>

            <!-- Export CSV Button (Matching Deep Navy Theme) -->
            <a 
                href="{{ route('laporan.exportCsv') }}" 
                class="h-10 px-4 whitespace-nowrap inline-flex items-center justify-center gap-2 text-xs font-bold text-white bg-[#091E3A] hover:bg-[#0e2c54] active:scale-[0.98] rounded-xl transition-all shadow-md shadow-[#091E3A]/20 cursor-pointer shrink-0"
            >
                <svg class="w-4 h-4 stroke-[2] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                <span>Ekspor CSV</span>
            </a>
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
            <p class="text-[10pt] font-mono text-black/80 pt-1">
                Nomor: B.04/BPVP-KDR/KP.03.01/XI/{{ date('Y') }}
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
                        <p class="font-bold underline uppercase">M.A. HABIBI KADIR, S.T., M.M.</p>
                        <p class="text-[11pt]">NIP. 198108022009121001</p>
                    </div>
                </div>

                <!-- Penanda Tangan Kanan: Asesor Monev / Kasubag Umum -->
                <div class="w-1/2 text-center space-y-16">
                    <div>
                        <p class="leading-normal">Kendari, {{ date('d F Y') }}</p>
                        <p class="font-bold leading-normal">Kepala Sub Bagian Umum,</p>
                    </div>
                    <div>
                        <p class="font-bold underline uppercase">ANSHARI, S.Sos., M.Ak</p>
                        <p class="text-[11pt]">NIP. 197707312003121007</p>
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

@extends('layouts.app')

@section('title', 'Pemantauan Kesenjangan')
@section('header_title', 'Analisis & Pemantauan Kesenjangan ASN (Monev Gap)')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{
    createModalOpen: {{ request('nip') ? 'true' : 'false' }},
    editModalOpen: false,
    selectedPegawaiNip: '{{ request('nip', '') }}',
    pegawaiList: {{ json_encode($allPegawai) }},
    currentAnalisis: {
        id: null,
        nip: '',
        standar_kompetensi: '',
        kompetensi_saat_ini: '',
        gap_kesenjangan: '',
        rencana_pengembangan: 'Pelatihan Klasikal / Bimtek',
        target_jp: 20,
        jp_terealisasi: 0,
        status: 'Belum Memenuhi',
        catatan: ''
    },
    onPegawaiChange(nip) {
        const found = this.pegawaiList.find(p => p.nip === nip);
        if (found) {
            this.currentAnalisis.standar_kompetensi = found.standar_kompetensi_jabatan || '';
        }
    },
    openEdit(item) {
        this.currentAnalisis = Object.assign({}, item);
        this.editModalOpen = true;
    }
}" x-init="if (selectedPegawaiNip) { onPegawaiChange(selectedPegawaiNip); currentAnalisis.nip = selectedPegawaiNip; }">

    <!-- KPI Summary Cards (All icons have NO background box) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Hasil Monev</span>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalEvaluated }}</p>
                <span class="text-[11px] text-slate-500">Rekam Asesmen Valid</span>
            </div>
            <!-- Pure Icon (NO Background) -->
            <div class="text-blue-600">
                <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 14 14" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Memenuhi Hak 20 JP</span>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $totalMet }}</p>
                <span class="text-[11px] text-emerald-600 font-semibold">{{ $totalEvaluated > 0 ? round(($totalMet / $totalEvaluated) * 100) : 0 }}% Tuntas Pelatihan</span>
            </div>
            <!-- Pure Icon (NO Background) -->
            <div class="text-emerald-600">
                <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Proses Diklat</span>
                <p class="text-2xl font-extrabold text-indigo-600 mt-1">{{ $totalProcess }}</p>
                <span class="text-[11px] text-indigo-600 font-semibold">Sedang Mengikuti Bimtek</span>
            </div>
            <!-- Pure Icon (NO Background) -->
            <div class="text-indigo-600">
                <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Belum Memenuhi / Kritis</span>
                <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $totalUnmet }}</p>
                <span class="text-[11px] text-rose-600 font-semibold">Prioritas Intervensi</span>
            </div>
            <!-- Pure Icon (NO Background) -->
            <div class="text-rose-600">
                <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Redesigned High-Fidelity Filter & Action Bar (Compact Font, Fits in 1 Row) -->
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-3 sm:p-4">
        <form method="GET" action="{{ route('analisis.index') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
            <!-- Left Filters Group (Compact font & padding to fit in single line) -->
            <div class="flex-1 flex flex-wrap lg:flex-nowrap items-center gap-2 min-w-0">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[170px] max-w-[240px]">
                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari ID, NIP, Nama ASN..." 
                        class="w-full pl-8 pr-2.5 py-2 text-[11.5px] font-medium bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-slate-800 placeholder-slate-400"
                    >
                </div>

                <!-- Status Filter Dropdown (Compact font) -->
                <div class="relative w-auto min-w-[140px] max-w-[165px] shrink-0">
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="w-full pl-2.5 pr-7 py-2 text-[11.5px] font-semibold text-slate-700 bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer truncate"
                    >
                        <option value="all">Semua Status Monev</option>
                        @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ $st }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rencana Filter Dropdown (Compact font) -->
                <div class="relative w-auto min-w-[170px] max-w-[210px] shrink-0">
                    <select 
                        name="rencana" 
                        onchange="this.form.submit()"
                        class="w-full pl-2.5 pr-7 py-2 text-[11.5px] font-semibold text-slate-700 bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer truncate"
                    >
                        <option value="all">Semua Rencana Pengembangan</option>
                        @foreach($rencanaTypes as $rt)
                        <option value="{{ $rt }}" {{ request('rencana') === $rt ? 'selected' : '' }}>
                            {{ $rt }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Filter Badge Button -->
                @if(request('search') || request('status') || request('rencana'))
                <a 
                    href="{{ route('analisis.index') }}" 
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100/80 border border-rose-200/80 rounded-xl transition-all shrink-0 cursor-pointer shadow-2xs"
                    title="Hapus filter"
                >
                    <svg class="w-3 h-3 stroke-[2.2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    <span>Reset</span>
                </a>
                @endif
            </div>

            <!-- Right CTA Button Group (Fits on 1 line with compact sizing) -->
            <div class="shrink-0 flex items-center justify-end">
                <button 
                    type="button" 
                    @click="createModalOpen = true"
                    class="h-[38px] px-3.5 whitespace-nowrap inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#091E3A] hover:bg-[#0e2c54] active:scale-[0.98] text-white text-[11.5px] font-bold shadow-md shadow-[#091E3A]/20 transition-all shrink-0 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <span>Input Asesmen Monev</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Analisis Kesenjangan Master Table (Scrollable on mobile/tablet) -->
    <div class="rounded-2xl bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[850px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-4 pl-4 sm:pl-6 pr-3">ID Monev</th>
                        <th class="py-4 px-3">Pegawai / NIP</th>
                        <th class="py-4 px-3">Standar Kompetensi Jabatan</th>
                        <th class="py-4 px-3">Kompetensi Saat Ini</th>
                        <th class="py-4 px-3">Gap Kesenjangan</th>
                        <th class="py-4 px-3">Rencana Pengembangan</th>
                        <th class="py-4 px-3">Target vs Realisasi</th>
                        <th class="py-4 px-3">Status</th>
                        <th class="py-4 pl-3 pr-4 sm:pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($analisisList as $item)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <!-- ID Monev -->
                        <td class="py-4 pl-4 sm:pl-6 pr-3 font-mono font-bold text-[#091E3A] text-[11px] whitespace-nowrap">
                            {{ $item->id_monev }}
                        </td>

                        <!-- Pegawai -->
                        <td class="py-4 px-3">
                            <p class="font-bold text-slate-900">{{ $item->pegawai->nama ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400 font-mono">{{ $item->nip }}</p>
                            <span class="text-[10px] font-medium text-slate-500">{{ $item->pegawai->jabatan ?? '-' }}</span>
                        </td>

                        <!-- Standar Kompetensi -->
                        <td class="py-4 px-3">
                            <p class="text-slate-700 line-clamp-2 max-w-[200px]" title="{{ $item->standar_kompetensi }}">
                                {{ $item->standar_kompetensi ?? '-' }}
                            </p>
                        </td>

                        <!-- Kompetensi Saat Ini -->
                        <td class="py-4 px-3">
                            <p class="text-slate-600 line-clamp-2 max-w-[180px]" title="{{ $item->kompetensi_saat_ini }}">
                                {{ $item->kompetensi_saat_ini ?? '-' }}
                            </p>
                        </td>

                        <!-- Gap Kesenjangan -->
                        <td class="py-4 px-3">
                            <span class="font-semibold text-rose-700 bg-rose-50/80 border border-rose-100 px-2 py-1 rounded-lg block line-clamp-2 max-w-[180px]" title="{{ $item->gap_kesenjangan }}">
                                {{ $item->gap_kesenjangan }}
                            </span>
                        </td>

                        <!-- Rencana Pengembangan -->
                        <td class="py-4 px-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50/80 border border-indigo-100 px-2 py-0.5 rounded-md">
                                {{ $item->rencana_pengembangan }}
                            </span>
                        </td>

                        <!-- Target vs Realisasi JP -->
                        <td class="py-4 px-3 whitespace-nowrap">
                            <div class="w-24 sm:w-28 space-y-1">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-slate-800">{{ $item->jp_terealisasi }} / {{ $item->target_jp }} JP</span>
                                    <span class="text-[10px] text-slate-400">{{ $item->jp_progress }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full @if($item->jp_progress >= 100) bg-emerald-500 @elseif($item->jp_progress > 0) bg-[#091E3A] @else bg-slate-300 @endif" style="width: {{ $item->jp_progress }}%"></div>
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border backdrop-blur-xs
                                @if(str_contains($item->status, 'Memenuhi')) bg-emerald-500/10 text-emerald-700 border-emerald-500/20
                                @elseif(str_contains($item->status, 'Proses')) bg-indigo-500/10 text-indigo-700 border-indigo-500/20
                                @else bg-rose-500/10 text-rose-700 border-rose-500/20 @endif">
                                <span class="w-1.5 h-1.5 rounded-full @if(str_contains($item->status, 'Memenuhi')) bg-emerald-500 @elseif(str_contains($item->status, 'Proses')) bg-indigo-500 @else bg-rose-500 @endif"></span>
                                {{ $item->status }}
                            </span>
                        </td>

                        <!-- Actions (Pure icons) -->
                        <td class="py-4 pl-3 pr-4 sm:pr-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <button 
                                    @click="openEdit({{ json_encode($item) }})"
                                    class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors"
                                    title="Edit Analisis"
                                >
                                    <svg class="w-4 h-4 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <form method="POST" action="{{ route('analisis.destroy', $item->id) }}" onsubmit="return confirm('Hapus rekam analisis ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-slate-400">
                            Belum ada rekam analisis kesenjangan yang sesuai dengan kriteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 sm:px-6 py-4 bg-slate-50/70 border-t border-slate-100">
            {{ $analisisList->links() }}
        </div>
    </div>

    <!-- Modal: Input Asesmen Kesenjangan Baru (Responsive) -->
    <div 
        x-show="createModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
        style="display: none;"
    >
        <div 
            @click.away="createModalOpen = false" 
            class="bg-white rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 sm:pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Entri Hasil Analisis Kesenjangan ASN</h3>
                    <p class="text-xs text-slate-400">Monev pemantauan kompetensi dan rencana pengembangan 20 JP</p>
                </div>
                <button @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('analisis.store') }}" class="mt-4 space-y-3 sm:space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Pilih ASN Pegawai BPVP Kendari *</label>
                    <select 
                        name="nip" 
                        x-model="currentAnalisis.nip" 
                        @change="onPegawaiChange($event.target.value)"
                        required 
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"
                    >
                        <option value="">-- Pilih Pegawai --</option>
                        <template x-for="p in pegawaiList" :key="p.nip">
                            <option :value="p.nip" x-text="p.nama + ' (' + p.jabatan + ') - NIP: ' + p.nip"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Standar Kompetensi Jabatan (SKJ yang disyaratkan)</label>
                    <textarea 
                        name="standar_kompetensi" 
                        x-model="currentAnalisis.standar_kompetensi"
                        rows="2" 
                        placeholder="Otomatis terisi dari SKJ pegawai yang dipilih..." 
                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50/70 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kompetensi Saat Ini (Hasil Asesmen) *</label>
                    <textarea 
                        name="kompetensi_saat_ini" 
                        required 
                        rows="2" 
                        placeholder="Contoh: Menguasai teknik instruksi dasar, perlu penguatan metodologi pelatihan lanjutan..." 
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Identifikasi Gap Kesenjangan *</label>
                    <input 
                        type="text" 
                        name="gap_kesenjangan" 
                        required 
                        placeholder="Contoh: Diklat Metodologi Pelatihan Kerja BNSP Level 4" 
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Bentuk Pengembangan *</label>
                        <select name="rencana_pengembangan" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                            <option value="Pelatihan Klasikal / Bimtek">Pelatihan Klasikal / Bimtek</option>
                            <option value="Pelatihan Non-Klasikal / E-learning">Pelatihan Non-Klasikal / E-learning</option>
                            <option value="Magang / Praktek Kerja">Magang / Praktek Kerja Industri</option>
                            <option value="Pelatihan Klasikal / Sertifikasi">Pelatihan Klasikal / Sertifikasi</option>
                            <option value="Workshop / Seminar">Workshop / Seminar</option>
                            <option value="Coaching & Mentoring">Coaching & Mentoring</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Target JP (Hak Min. 20 JP) *</label>
                        <input type="number" name="target_jp" value="20" min="1" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">JP Terealisasi (Saat Ini) *</label>
                        <input type="number" name="jp_terealisasi" value="0" min="0" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Catatan Tambahan / Keterangan</label>
                    <input type="text" name="catatan" placeholder="Contoh: Terdaftar pada angkatan I Pusdiklat Kemnaker" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 sm:pt-4 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2.5 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-white bg-[#091E3A] hover:bg-[#0e2c54] font-bold transition-all shadow-xs">Simpan Hasil Monev</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Asesmen Kesenjangan (Responsive) -->
    <div 
        x-show="editModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
        style="display: none;"
    >
        <div 
            @click.away="editModalOpen = false" 
            class="bg-white rounded-2xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 sm:pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Perbarui Rekam Monev Kesenjangan</h3>
                    <p class="text-xs text-slate-400 font-mono" x-text="'ID: ' + currentAnalisis.id_monev + ' | NIP: ' + currentAnalisis.nip"></p>
                </div>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form :action="'{{ url('analisis') }}/' + currentAnalisis.id" method="POST" class="mt-4 space-y-3 sm:space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Standar Kompetensi Jabatan</label>
                    <textarea name="standar_kompetensi" x-model="currentAnalisis.standar_kompetensi" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kompetensi Saat Ini *</label>
                    <textarea name="kompetensi_saat_ini" x-model="currentAnalisis.kompetensi_saat_ini" required rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Gap Kesenjangan *</label>
                    <input type="text" name="gap_kesenjangan" x-model="currentAnalisis.gap_kesenjangan" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Bentuk Pengembangan *</label>
                        <select name="rencana_pengembangan" x-model="currentAnalisis.rencana_pengembangan" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                            <option value="Pelatihan Klasikal / Bimtek">Pelatihan Klasikal / Bimtek</option>
                            <option value="Pelatihan Non-Klasikal / E-learning">Pelatihan Non-Klasikal / E-learning</option>
                            <option value="Magang / Praktek Kerja">Magang / Praktek Kerja Industri</option>
                            <option value="Pelatihan Klasikal / Sertifikasi">Pelatihan Klasikal / Sertifikasi</option>
                            <option value="Workshop / Seminar">Workshop / Seminar</option>
                            <option value="Coaching & Mentoring">Coaching & Mentoring</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Target JP *</label>
                        <input type="number" name="target_jp" x-model="currentAnalisis.target_jp" min="1" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">JP Terealisasi *</label>
                        <input type="number" name="jp_terealisasi" x-model="currentAnalisis.jp_terealisasi" min="0" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Status Kualifikasi *</label>
                    <select name="status" x-model="currentAnalisis.status" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                        <option value="Memenuhi Hak 20 JP">Memenuhi Hak 20 JP</option>
                        <option value="Memenuhi Hak 100 JP">Memenuhi Hak 100 JP</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Belum Memenuhi">Belum Memenuhi</option>
                        <option value="Kesenjangan Kritis">Kesenjangan Kritis</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Catatan Tambahan</label>
                    <input type="text" name="catatan" x-model="currentAnalisis.catatan" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 sm:pt-4 border-t border-slate-100">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-white bg-[#091E3A] hover:bg-[#0e2c54] font-bold transition-all shadow-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

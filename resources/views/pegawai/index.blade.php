@extends('layouts.app')

@section('title', 'Data Pegawai ASN')
@section('header_title', 'Master Data Pegawai ASN BPVP Kendari')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{
    createModalOpen: false,
    editModalOpen: false,
    currentPegawai: { id: null, nip: '', nama: '', jabatan: '', unit_kerja: 'BPVP KENDARI', standar_kompetensi_jabatan: '' },
    openEdit(pegawai) {
        this.currentPegawai = Object.assign({}, pegawai);
        this.editModalOpen = true;
    }
}">

    <!-- Top KPI cards (All icons have NO background box) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pegawai ASN</span>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalCount }}</p>
                <span class="text-[11px] text-slate-500">Unit Kerja BPVP Kendari</span>
            </div>
            <!-- Pure Icon (NO Background) -->
            <div class="text-blue-600">
                <svg class="w-7 h-7 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sudah Dimonev</span>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $assessedCount }}</p>
                <span class="text-[11px] text-emerald-600 font-semibold">{{ round(($assessedCount / max(1, $totalCount)) * 100, 1) }}% Memiliki Rekam Gap</span>
            </div>
            <!-- Pure Icon (NO Background) -->
            <div class="text-emerald-600">
                <svg class="w-7 h-7 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu Pemantauan</span>
                <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $totalCount - $assessedCount }}</p>
                <span class="text-[11px] text-amber-600 font-semibold">Perlu Asesmen Kesenjangan</span>
            </div>
            <!-- Pure Icon (NO Background) -->
            <div class="text-amber-600">
                <svg class="w-7 h-7 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar (Compact Font, Fits in 1 Row without overlap) -->
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-3 sm:p-4">
        <form method="GET" action="{{ route('pegawai.index') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
            <div class="flex-1 flex flex-wrap lg:flex-nowrap items-center gap-2 min-w-0">
                <!-- Search input -->
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
                        placeholder="Cari Nama / NIP..." 
                        class="w-full pl-8 pr-2.5 py-2 text-[11.5px] font-medium bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-slate-800 placeholder-slate-400"
                    >
                </div>

                <!-- Jabatan Filter (Truncated & max-width so long text doesn't push layout) -->
                <div class="relative w-auto min-w-[160px] max-w-[210px] shrink-0">
                    <select 
                        name="jabatan" 
                        onchange="this.form.submit()"
                        class="w-full pl-2.5 pr-7 py-2 text-[11.5px] font-semibold text-slate-700 bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer truncate"
                    >
                        <option value="all">Semua Jabatan (22)</option>
                        @foreach($allJabatans as $jabatan)
                        <option value="{{ $jabatan }}" {{ request('jabatan') === $jabatan ? 'selected' : '' }}>
                            {{ $jabatan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="relative w-auto min-w-[130px] max-w-[155px] shrink-0">
                    <select 
                        name="status_analisis" 
                        onchange="this.form.submit()"
                        class="w-full pl-2.5 pr-7 py-2 text-[11.5px] font-semibold text-slate-700 bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer truncate"
                    >
                        <option value="">Status Monev (Semua)</option>
                        <option value="sudah" {{ request('status_analisis') === 'sudah' ? 'selected' : '' }}>Sudah Dimonev</option>
                        <option value="belum" {{ request('status_analisis') === 'belum' ? 'selected' : '' }}>Belum Dimonev</option>
                    </select>
                </div>

                <!-- Reset Filter Badge Button -->
                @if(request('search') || request('jabatan') || request('status_analisis'))
                <a 
                    href="{{ route('pegawai.index') }}" 
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

            <!-- Action Button: Tambah Pegawai (Deep navy theme, isolated shrink-0) -->
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
                    <span>Tambah ASN Baru</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Pegawai Table (Fully scrollable horizontally on mobile/tablet) -->
    <div class="rounded-2xl bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-4 pl-4 sm:pl-6 pr-3">No</th>
                        <th class="py-4 px-3">Pegawai / NIP</th>
                        <th class="py-4 px-3">Jabatan & Unit Kerja</th>
                        <th class="py-4 px-3">Standar Kompetensi Jabatan (SKJ)</th>
                        <th class="py-4 px-3">Status Monev</th>
                        <th class="py-4 pl-3 pr-4 sm:pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pegawais as $index => $pegawai)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="py-4 pl-4 sm:pl-6 pr-3 text-slate-400 font-mono text-[11px]">
                            {{ $pegawais->firstItem() + $index }}
                        </td>
                        <td class="py-4 px-3">
                            <div class="flex items-center gap-3">
                                <!-- Clean Avatar -->
                                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-800 font-bold flex items-center justify-center text-xs shrink-0 ring-1 ring-slate-200">
                                    {{ strtoupper(substr($pegawai->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $pegawai->nama }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ $pegawai->nip }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-3">
                            <p class="font-semibold text-slate-800">{{ $pegawai->jabatan }}</p>
                            <span class="text-[10px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $pegawai->unit_kerja }}</span>
                        </td>
                        <td class="py-4 px-3">
                            <p class="text-slate-600 line-clamp-2 max-w-[280px]" title="{{ $pegawai->standar_kompetensi_jabatan }}">
                                {{ $pegawai->standar_kompetensi_jabatan ?? 'Belum ada standar khusus' }}
                            </p>
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            @if($pegawai->analisisKesenjangan->count() > 0)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $pegawai->analisisKesenjangan->count() }} Asesmen
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                Belum Dimonev
                            </span>
                            @endif
                        </td>
                        <td class="py-4 pl-3 pr-4 sm:pr-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <!-- Input Monev Button (Pure icon) -->
                                <a 
                                    href="{{ route('analisis.index') }}?nip={{ $pegawai->nip }}" 
                                    class="p-1.5 text-indigo-600 hover:text-indigo-800 transition-colors" 
                                    title="Monev Kesenjangan"
                                >
                                    <svg class="w-4 h-4 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                </a>

                                <!-- Edit Pegawai Button (Pure icon) -->
                                <button 
                                    @click="openEdit({{ json_encode($pegawai) }})"
                                    class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors" 
                                    title="Edit Data"
                                >
                                    <svg class="w-4 h-4 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <!-- Delete Pegawai (Pure icon) -->
                                <form method="POST" action="{{ route('pegawai.destroy', $pegawai->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ASN ini?')" class="inline">
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
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            Tidak ada data ASN yang sesuai dengan pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-4 bg-slate-50/70 border-t border-slate-100">
            {{ $pegawais->links() }}
        </div>
    </div>

    <!-- Modal: Tambah ASN Baru (Responsive) -->
    <div 
        x-show="createModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
        style="display: none;"
    >
        <div 
            @click.away="createModalOpen = false" 
            class="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-7 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 sm:pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Tambah Data ASN Baru</h3>
                <button @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('pegawai.store') }}" class="mt-4 space-y-3 sm:space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nomor Induk Pegawai (NIP) *</label>
                    <input type="text" name="nip" required placeholder="Contoh: 198309062006041007" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                    <input type="text" name="nama" required placeholder="Contoh: Dr. Nama Pegawai, S.T., M.M." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Jabatan *</label>
                    <input type="text" name="jabatan" required placeholder="Contoh: INSTRUKTUR AHLI MADYA" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Unit Kerja *</label>
                    <input type="text" name="unit_kerja" value="BPVP KENDARI" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Standar Kompetensi Jabatan (SKJ)</label>
                    <textarea name="standar_kompetensi_jabatan" rows="3" placeholder="Uraian kompetensi yang dipersyaratkan..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 sm:pt-4 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2.5 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-white bg-[#091E3A] hover:bg-[#0e2c54] font-bold transition-all shadow-xs">Simpan ASN</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Pegawai (Responsive) -->
    <div 
        x-show="editModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
        style="display: none;"
    >
        <div 
            @click.away="editModalOpen = false" 
            class="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-7 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 sm:pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Perbarui Data ASN</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form :action="'{{ url('pegawai') }}/' + currentPegawai.id" method="POST" class="mt-4 space-y-3 sm:space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nomor Induk Pegawai (NIP) *</label>
                    <input type="text" name="nip" x-model="currentPegawai.nip" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                    <input type="text" name="nama" x-model="currentPegawai.nama" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Jabatan *</label>
                    <input type="text" name="jabatan" x-model="currentPegawai.jabatan" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Unit Kerja *</label>
                    <input type="text" name="unit_kerja" x-model="currentPegawai.unit_kerja" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Standar Kompetensi Jabatan (SKJ)</label>
                    <textarea name="standar_kompetensi_jabatan" x-model="currentPegawai.standar_kompetensi_jabatan" rows="3" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"></textarea>
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

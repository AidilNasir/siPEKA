@extends('layouts.app')

@section('title', 'Pemenuhan Hak 20 JP')
@section('header_title', 'Monitoring Rencana Pengembangan & Hak 20 JP ASN')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{
    updateModalOpen: false,
    currentId: null,
    currentNip: '',
    currentNama: '',
    currentJp: 0,
    currentTarget: 20,
    openUpdate(id, nip, nama, currentJp, target) {
        this.currentId = id;
        this.currentNip = nip;
        this.currentNama = nama;
        this.currentJp = currentJp;
        this.currentTarget = target;
        this.updateModalOpen = true;
    }
}">

    <!-- Top KPI Cards (All icons have NO background box) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)]">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Target Akumulasi JP</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalTargetJp }} <span class="text-xs font-medium text-slate-400">JP</span></p>
            <span class="text-[11px] text-slate-500">Standar 20 JP/ASN/Tahun</span>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)]">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Realisasi Jam Pelajaran</span>
            <p class="text-2xl font-extrabold text-[#091E3A] mt-1">{{ $totalRealisasiJp }} <span class="text-xs font-medium text-slate-400">JP</span></p>
            <span class="text-[11px] text-indigo-600 font-bold">{{ $avgProgress }}% Tercapai</span>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)]">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tuntas Hak Min. 20 JP</span>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $completedCount }} <span class="text-xs font-medium text-slate-400">ASN</span></p>
            <span class="text-[11px] text-emerald-600 font-semibold">{{ $totalPlans > 0 ? round(($completedCount / $totalPlans) * 100) : 0 }}% Telah Memenuhi</span>
        </div>

        <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)]">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Belum Ada Realisasi</span>
            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $zeroCount }} <span class="text-xs font-medium text-slate-400">ASN</span></p>
            <span class="text-[11px] text-amber-600 font-semibold">Perlu Penjadwalan Diklat</span>
        </div>
    </div>

    <!-- Metode Pelatihan Breakdown Strip (Responsive) -->
    <div class="rounded-2xl bg-white border border-slate-200/80 p-4 sm:p-5 shadow-[0_2px_8px_rgba(0,0,0,0.03)]">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Distribusi Metode Pelatihan Vokasi (Monev)</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            @foreach($trainingTypes as $type)
            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-100 space-y-1">
                <span class="font-bold text-slate-800 line-clamp-1" title="{{ $type->rencana_pengembangan }}">{{ $type->rencana_pengembangan }}</span>
                <div class="flex items-center justify-between text-slate-500 text-[11px]">
                    <span>{{ $type->count }} Pegawai</span>
                    <span class="font-bold text-[#091E3A]">{{ $type->total_jp ?? 0 }} JP Terealisasi</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Filter Bar (Compact Font, Fits in 1 Row) -->
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-3 sm:p-4">
        <form method="GET" action="{{ route('pengembangan.index') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 min-w-0">
                <div class="relative flex-1 min-w-[160px]">
                    <!-- Pure icon (NO Background) -->
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
                        placeholder="Cari NIP, Nama ASN, atau Rencana Diklat..." 
                        class="w-full pl-8 pr-3 py-2 text-[11.5px] font-medium bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-slate-800 placeholder-slate-400"
                    >
                </div>

                <div class="relative sm:w-auto min-w-[185px] shrink-0">
                    <select 
                        name="tipe" 
                        onchange="this.form.submit()"
                        class="w-full pl-2.5 pr-7 py-2 text-[11.5px] font-semibold text-slate-700 bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer"
                    >
                        <option value="all">Semua Bentuk Pelatihan</option>
                        @foreach($trainingTypes as $t)
                        <option value="{{ $t->rencana_pengembangan }}" {{ request('tipe') === $t->rencana_pengembangan ? 'selected' : '' }}>
                            {{ $t->rencana_pengembangan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                @if(request('search') || request('tipe'))
                <a 
                    href="{{ route('pengembangan.index') }}" 
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

            <div class="shrink-0 flex items-center justify-end">
                <a 
                    href="{{ route('analisis.index') }}" 
                    class="h-[38px] px-3.5 whitespace-nowrap inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#091E3A] hover:bg-[#0e2c54] active:scale-[0.98] text-white text-[11.5px] font-bold shadow-md shadow-[#091E3A]/20 transition-all shrink-0 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <span>Tambah Rencana Diklat</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Table (Scrollable horizontally on mobile/tablet) -->
    <div class="rounded-2xl bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[750px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-4 pl-4 sm:pl-6 pr-3">Pegawai / NIP</th>
                        <th class="py-4 px-3">Jabatan</th>
                        <th class="py-4 px-3">Bentuk Pelatihan & Gap</th>
                        <th class="py-4 px-3">Target JP</th>
                        <th class="py-4 px-3">Realisasi JP</th>
                        <th class="py-4 px-3">Progress Capaian</th>
                        <th class="py-4 px-3">Status Hak</th>
                        <th class="py-4 pl-3 pr-4 sm:pr-6 text-right">Update JP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($plans as $plan)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 pl-4 sm:pl-6 pr-3">
                            <p class="font-bold text-slate-900">{{ $plan->pegawai->nama ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400 font-mono">{{ $plan->nip }}</p>
                        </td>
                        <td class="py-4 px-3 text-slate-700 font-medium">
                            {{ $plan->pegawai->jabatan ?? '-' }}
                        </td>
                        <td class="py-4 px-3">
                            <span class="font-semibold text-indigo-700 bg-indigo-50/80 border border-indigo-100 px-2 py-0.5 rounded-md inline-block">
                                {{ $plan->rencana_pengembangan }}
                            </span>
                            <p class="text-[11px] text-slate-500 mt-1 line-clamp-1">{{ $plan->gap_kesenjangan }}</p>
                        </td>
                        <td class="py-4 px-3 font-semibold text-slate-800 whitespace-nowrap">
                            {{ $plan->target_jp }} JP
                        </td>
                        <td class="py-4 px-3 font-bold text-[#091E3A] whitespace-nowrap">
                            {{ $plan->jp_terealisasi }} JP
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <div class="w-24 sm:w-28 space-y-1">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-semibold text-slate-700">{{ $plan->jp_progress }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full @if($plan->jp_progress >= 100) bg-emerald-500 @elseif($plan->jp_progress > 0) bg-[#091E3A] @else bg-slate-300 @endif" style="width: {{ $plan->jp_progress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border
                                @if(str_contains($plan->status, 'Memenuhi')) bg-emerald-500/10 text-emerald-700 border-emerald-500/20
                                @elseif(str_contains($plan->status, 'Proses')) bg-indigo-500/10 text-indigo-700 border-indigo-500/20
                                @else bg-amber-500/10 text-amber-700 border-amber-500/20 @endif">
                                {{ $plan->status }}
                            </span>
                        </td>
                        <td class="py-4 pl-3 pr-4 sm:pr-6 text-right whitespace-nowrap">
                            <button 
                                @click="openUpdate({{ $plan->id }}, '{{ $plan->nip }}', '{{ addslashes($plan->pegawai->nama ?? '') }}', {{ $plan->jp_terealisasi }}, {{ $plan->target_jp }})"
                                class="px-3 py-1.5 text-[11px] font-bold text-[#091E3A] bg-slate-100 hover:bg-[#091E3A] hover:text-white rounded-xl transition-all border border-slate-200"
                            >
                                Catat JP
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            Belum ada rekam rencana pengembangan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 sm:px-6 py-4 bg-slate-50/70 border-t border-slate-100">
            {{ $plans->links() }}
        </div>
    </div>

    <!-- Quick Update JP Modal (Responsive) -->
    <div 
        x-show="updateModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
        style="display: none;"
    >
        <div 
            @click.away="updateModalOpen = false" 
            class="bg-white rounded-2xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Perbarui Realisasi Jam Pelajaran</h3>
                <button @click="updateModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('rencana-pengembangan') }}/' + currentId + '/jp'" method="POST" class="mt-4 space-y-3 sm:space-y-4 text-xs">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nama ASN</label>
                    <p class="font-bold text-slate-900 text-sm" x-text="currentNama"></p>
                    <p class="font-mono text-slate-400 text-[11px]" x-text="'NIP: ' + currentNip"></p>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Target Jam Pelajaran (JP)</label>
                    <p class="font-bold text-slate-800" x-text="currentTarget + ' JP (Hak Tahunan)'"></p>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">JP Terealisasi (Tuntas Mengikuti Pelatihan) *</label>
                    <input 
                        type="number" 
                        name="jp_terealisasi" 
                        x-model="currentJp" 
                        min="0" 
                        required 
                        class="w-full px-3.5 py-2.5 text-sm font-bold border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:outline-none"
                    >
                    <span class="text-[11px] text-slate-400 mt-1 block">Status pemenuhan hak akan dihitung secara otomatis berdasarkan target.</span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="updateModalOpen = false" class="px-3.5 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-white bg-[#091E3A] hover:bg-[#0e2c54] font-bold transition-all shadow-xs">Simpan JP</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

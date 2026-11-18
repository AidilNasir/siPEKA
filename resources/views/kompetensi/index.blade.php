@extends('layouts.app')

@section('title', 'Standar Kompetensi Jabatan')
@section('header_title', 'Katalog Standar Kompetensi Jabatan (SKJ)')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{
    editModalOpen: false,
    selectedJabatan: '',
    selectedSkj: '',
    openEdit(jabatan, skj) {
        this.selectedJabatan = jabatan;
        this.selectedSkj = skj;
        this.editModalOpen = true;
    }
}">

    <!-- Header info banner (Responsive) -->
    <div class="rounded-2xl bg-gradient-to-r from-blue-900 to-indigo-900 p-5 sm:p-6 text-white shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl md:text-2xl font-extrabold text-white">Standar Kompetensi Jabatan (SKJ)</h2>
                <p class="text-slate-300 text-xs mt-1 max-w-2xl leading-relaxed">
                    Kamus persyaratan kualifikasi, pengetahuan teknis, dan kecakapan kerja untuk 22 jenis jabatan fungsional dan pelaksana di lingkungan BPVP Kendari.
                </p>
            </div>
            <div class="text-left sm:text-right shrink-0 border-t sm:border-t-0 pt-3 sm:pt-0 border-white/10">
                <span class="text-2xl sm:text-3xl font-extrabold text-white">{{ count($catalog) }}</span>
                <span class="block text-[11px] text-indigo-200">Jenis Jabatan Terstandarisasi</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar (Figma-grade styling) -->
    <div class="rounded-2xl bg-white border border-slate-200/80 p-4 sm:p-5 shadow-[0_2px_12px_rgba(0,0,0,0.04)]">
        <form method="GET" action="{{ route('kompetensi.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative flex-1">
                <!-- Pure icon (NO Background) -->
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari Jabatan (misal: Instruktur, Pranata Komputer, Analis SDM, Arsiparis)..." 
                    class="w-full pl-10 pr-4 py-2.5 text-xs font-medium bg-slate-50/70 hover:bg-slate-100/50 focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-slate-800 placeholder-slate-400"
                >
            </div>
            <button 
                type="submit" 
                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-[#091E3A] hover:bg-[#0e2c54] active:scale-[0.98] text-white text-xs font-bold shadow-md shadow-[#091E3A]/20 transition-all shrink-0 cursor-pointer"
            >
                <svg class="w-4 h-4 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <span>Cari SKJ</span>
            </button>
            @if(request('search'))
            <a href="{{ route('kompetensi.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-800 text-center sm:text-left py-1 shrink-0">Reset</a>
            @endif
        </form>
    </div>

    <!-- Cards Grid (Responsive 1 col on mobile, 2 col on tablet/desktop) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
        @foreach($catalog as $item)
        <div class="rounded-2xl bg-white p-5 border border-slate-200/70 shadow-2xs hover:shadow-md transition-shadow flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">{{ $item['jabatan'] }}</h3>
                        <p class="text-[11px] text-slate-400">Unit Kerja: BPVP Kendari</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 shrink-0">
                        {{ $item['pegawai_count'] }} Pegawai
                    </span>
                </div>

                <!-- SKJ text box -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Uraian Standar Kompetensi:</span>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        {{ $item['standar_kompetensi'] }}
                    </p>
                </div>

                <!-- Sample pegawai -->
                @if($item['pegawai_samples']->count() > 0)
                <div class="flex items-center gap-2 pt-1 text-[11px] text-slate-500">
                    <span class="font-semibold text-slate-400">Contoh ASN:</span>
                    <div class="flex -space-x-1 overflow-hidden">
                        @foreach($item['pegawai_samples'] as $pegawai)
                        <span class="inline-block w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 text-[9px] font-bold text-center leading-5 ring-1 ring-white" title="{{ $pegawai->nama }}">
                            {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                        </span>
                        @endforeach
                    </div>
                    <span class="truncate max-w-[200px]">{{ $item['pegawai_samples']->pluck('nama')->first() }}</span>
                </div>
                @endif
            </div>

            <!-- Action footer -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('pegawai.index', ['jabatan' => $item['jabatan']]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    Lihat {{ $item['pegawai_count'] }} Pegawai &rarr;
                </a>
                <button 
                    @click="openEdit('{{ addslashes($item['jabatan']) }}', '{{ addslashes($item['standar_kompetensi']) }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors"
                >
                    Edit Standar
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal Edit SKJ (Responsive) -->
    <div 
        x-show="editModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
        style="display: none;"
    >
        <div 
            @click.away="editModalOpen = false" 
            class="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 sm:pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Perbarui Standar Kompetensi Jabatan</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('kompetensi.update') }}" class="mt-4 space-y-3 sm:space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nama Jabatan</label>
                    <input type="text" name="jabatan" x-model="selectedJabatan" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-bold focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Standar Kompetensi yang Dipersyaratkan *</label>
                    <textarea 
                        name="standar_kompetensi_jabatan" 
                        x-model="selectedSkj" 
                        required 
                        rows="4" 
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                    ></textarea>
                    <span class="text-[11px] text-slate-400 mt-1 block">Perubahan akan diterapkan secara otomatis ke seluruh ASN pemangku jabatan ini.</span>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 sm:pt-4 border-t border-slate-100">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-bold transition-all shadow-xs">Simpan SKJ</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

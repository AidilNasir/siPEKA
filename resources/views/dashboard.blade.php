@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')
@section('header_title', 'Dashboard Pemantauan Kesenjangan')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-7xl mx-auto">

    <!-- Welcome & Executive Summary Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-5 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-slate-800">
        <!-- Abstract Glow & Blur Accents -->
        <div class="absolute -top-24 -right-24 w-72 sm:w-96 h-72 sm:h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-60 sm:w-80 h-60 sm:h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5 sm:gap-6">
            <div class="space-y-2">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Sistem Pemantauan Kesenjangan Aparatur (siPEKA)
                </h2>
                <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed font-light">
                    Sistem informasi pemantauan kesenjangan kompetensi aparatur sipil negara berbasis Standar Kompetensi Jabatan (SKJ) dan pemenuhan hak pengembangan kompetensi minimal 20 JP per tahun.
                </p>
            </div>

            <!-- Action buttons (Responsive stack on mobile) -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 shrink-0">
                <a href="{{ route('laporan.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold text-white transition-all shadow-xs hover:border-white/30 text-center">
                    <!-- Clean icon (No background) -->
                    <svg class="w-4 h-4 text-indigo-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Rapor Kesenjangan</span>
                </a>

                <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-xs font-bold text-white transition-all shadow-lg shadow-indigo-600/30 hover:scale-[1.02] text-center">
                    <!-- Clean icon (No background) -->
                    <svg class="w-4 h-4 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <span>Entri Analisis Kesenjangan</span>
                </a>
            </div>
        </div>

        <!-- Quick highlights bottom strip (Responsive 2x2 grid on mobile, 4 columns on tablet/desktop) -->
        <div class="relative z-10 mt-6 pt-5 border-t border-white/10 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-xs">
            <div>
                <span class="text-slate-400 block text-[10px] sm:text-[11px]">Unit Kerja Terdaftar</span>
                <span class="font-semibold text-slate-200">BPVP KENDARI</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] sm:text-[11px]">Regulasi Pelatihan</span>
                <span class="font-semibold text-slate-200">PP 11/2017 (Min. 20 JP/Thn)</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] sm:text-[11px]">Realisasi JP Akumulatif</span>
                <span class="font-semibold text-emerald-400">{{ $jpAchievementRate }}% dari Target</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] sm:text-[11px]">Rasio Kepatuhan Hak</span>
                <span class="font-semibold text-indigo-300">{{ $totalAnalisis > 0 ? round(($memenuhiHak / $totalAnalisis) * 100, 1) : 0 }}% Memenuhi</span>
            </div>
        </div>
    </div>

    <!-- 4 Overview Metric Cards (Responsive Grid: 1 col on mobile, 2 col on tablet, 4 col on desktop) -->
    <!-- All Icons have NO BACKGROUND WRAPPERS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Card 1: Total ASN -->
        <div class="group relative rounded-2xl bg-white p-5 sm:p-6 shadow-2xs hover:shadow-xl hover:shadow-slate-200/50 border border-slate-200/70 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $metrics['total_asn']['label'] }}</span>
                <!-- Pure Icon (NO background box) -->
                <div class="text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['total_asn']['value'] }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                    {{ $metrics['total_asn']['change'] }}
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400 font-medium">{{ $metrics['total_asn']['subtext'] }}</p>
            <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Card 2: Asesmen Kesenjangan -->
        <div class="group relative rounded-2xl bg-white p-5 sm:p-6 shadow-2xs hover:shadow-xl hover:shadow-slate-200/50 border border-slate-200/70 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $metrics['total_analisis']['label'] }}</span>
                <!-- Pure Icon (NO background box) -->
                <div class="text-indigo-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['total_analisis']['value'] }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                    {{ $metrics['total_analisis']['change'] }}
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400 font-medium">{{ $metrics['total_analisis']['subtext'] }}</p>
            <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min(100, round(($totalAnalisis / max(1, $totalPegawai)) * 100)) }}%"></div>
            </div>
        </div>

        <!-- Card 3: Memenuhi Hak 20 JP -->
        <div class="group relative rounded-2xl bg-white p-5 sm:p-6 shadow-2xs hover:shadow-xl hover:shadow-slate-200/50 border border-slate-200/70 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $metrics['compliance_rate']['label'] }}</span>
                <!-- Pure Icon (NO background box) -->
                <div class="text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['compliance_rate']['value'] }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                    {{ $metrics['compliance_rate']['change'] }}
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400 font-medium">{{ $metrics['compliance_rate']['subtext'] }}</p>
            <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $totalAnalisis > 0 ? round(($memenuhiHak / $totalAnalisis) * 100) : 0 }}%"></div>
            </div>
        </div>

        <!-- Card 4: Butuh Intervensi -->
        <div class="group relative rounded-2xl bg-white p-5 sm:p-6 shadow-2xs hover:shadow-xl hover:shadow-slate-200/50 border border-slate-200/70 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $metrics['need_intervention']['label'] }}</span>
                <!-- Pure Icon (NO background box) -->
                <div class="text-amber-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['need_intervention']['value'] }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">
                    {{ $metrics['need_intervention']['change'] }}
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400 font-medium">{{ $metrics['need_intervention']['subtext'] }}</p>
            <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full" style="width: {{ $totalAnalisis > 0 ? round(($dalamProses + $belumMemenuhi) / $totalAnalisis * 100) : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section (Responsive: 1 col on mobile/tablet, 3 col on large desktop) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
        <!-- Main Chart: Realisasi Jam Pelajaran (JP) -->
        <div class="lg:col-span-2 rounded-2xl bg-white p-5 sm:p-6 shadow-2xs border border-slate-200/70 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Distribusi Pemenuhan Jam Pelajaran (JP) Pegawai</h3>
                        <p class="text-[11px] sm:text-xs text-slate-400">Monitoring realisasi hak pengembangan kompetensi ASN BPVP Kendari</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Realisasi JP ({{ $jpAchievementRate }}%)
                        </span>
                    </div>
                </div>

                <!-- ApexChart Container -->
                <div class="mt-4 -ml-2 sm:-ml-3 overflow-hidden">
                    <div id="jpProgressChart" class="w-full h-64 sm:h-72"></div>
                </div>
            </div>

            <!-- Footer summary stats (Responsive grid) -->
            <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-center">
                <div class="p-2.5 rounded-xl bg-slate-50/70">
                    <span class="text-[11px] text-slate-400 font-medium">Memenuhi Hak 20 JP</span>
                    <p class="text-sm font-bold text-emerald-600">{{ $memenuhiHak }} Pegawai</p>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-50/70">
                    <span class="text-[11px] text-slate-400 font-medium">Dalam Proses Pelatihan</span>
                    <p class="text-sm font-bold text-indigo-600">{{ $dalamProses }} Pegawai</p>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-50/70">
                    <span class="text-[11px] text-slate-400 font-medium">Belum Ada Realisasi (0 JP)</span>
                    <p class="text-sm font-bold text-amber-600">{{ $belumMemenuhi }} Pegawai</p>
                </div>
            </div>
        </div>

        <!-- Jabatan Distribution (1 column) -->
        <div class="rounded-2xl bg-white p-5 sm:p-6 shadow-2xs border border-slate-200/70 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Komposisi Jabatan ASN</h3>
                        <p class="text-[11px] sm:text-xs text-slate-400">BPVP Kendari (Total {{ $totalPegawai }} Pegawai)</p>
                    </div>
                    <a href="{{ route('pegawai.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Semua &rarr;</a>
                </div>

                <div class="mt-4 space-y-2.5 sm:space-y-3">
                    @foreach($jabatanStats as $stat)
                    <div class="p-3 rounded-xl bg-slate-50/70 border border-slate-100 space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800 line-clamp-1 text-[11px] sm:text-xs">{{ $stat->jabatan }}</span>
                            <span class="font-semibold text-indigo-600 shrink-0 ml-2">{{ $stat->total }} Pegawai</span>
                        </div>
                        <div class="w-full bg-slate-200/80 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ round(($stat->total / max(1, $totalPegawai)) * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick link to SKJ -->
            <div class="mt-5 p-3 rounded-xl bg-indigo-50/60 border border-indigo-100 flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs font-bold text-indigo-950">Standar Kompetensi</p>
                    <p class="text-[10px] sm:text-[11px] text-indigo-700">Tersedia untuk 22 jabatan di BPVP</p>
                </div>
                <a href="{{ route('kompetensi.index') }}" class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold shadow-2xs hover:bg-indigo-700 transition-colors shrink-0">
                    Lihat SKJ
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Monitored ASN Data Table Section (Responsive Table) -->
    <div class="rounded-2xl bg-white shadow-2xs border border-slate-200/70 overflow-hidden">
        <!-- Table Header & Actions -->
        <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900">Hasil Pemantauan Kesenjangan Terkini</h3>
                <p class="text-[11px] sm:text-xs text-slate-400">Data hasil monev gap kompetensi pegawai BPVP Kendari</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('analisis.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                    <span>Lihat Semua Analisis ({{ $totalAnalisis }})</span>
                    <!-- Clean icon (No background) -->
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Modern Table without harsh borders & with horizontal scroll on mobile -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 pl-4 sm:pl-6 pr-3">ID Monev</th>
                        <th class="py-3.5 px-3">Pegawai / NIP</th>
                        <th class="py-3.5 px-3">Jabatan</th>
                        <th class="py-3.5 px-3">Gap Kesenjangan</th>
                        <th class="py-3.5 px-3">Rencana Pengembangan</th>
                        <th class="py-3.5 px-3">Capaian JP</th>
                        <th class="py-3.5 pl-3 pr-4 sm:pr-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($recentAnalisis as $item)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 pl-4 sm:pl-6 pr-3 font-mono font-semibold text-indigo-600 text-[11px] whitespace-nowrap">
                            {{ $item->id_monev }}
                        </td>
                        <td class="py-4 px-3">
                            <p class="font-bold text-slate-900">{{ $item->pegawai->nama ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400 font-mono">{{ $item->nip }}</p>
                        </td>
                        <td class="py-4 px-3 text-slate-700 font-medium">
                            {{ $item->pegawai->jabatan ?? '-' }}
                        </td>
                        <td class="py-4 px-3">
                            <span class="text-slate-800 font-medium line-clamp-1 max-w-[200px]" title="{{ $item->gap_kesenjangan }}">
                                {{ $item->gap_kesenjangan }}
                            </span>
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">
                                {{ $item->rencana_pengembangan }}
                            </span>
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <div class="w-24 sm:w-28 space-y-1">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-slate-800">{{ $item->jp_terealisasi }}/{{ $item->target_jp }} JP</span>
                                    <span class="text-[10px] text-slate-400">{{ $item->jp_progress }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full @if($item->jp_progress >= 100) bg-emerald-500 @elseif($item->jp_progress > 0) bg-indigo-600 @else bg-slate-300 @endif" style="width: {{ $item->jp_progress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 pl-3 pr-4 sm:pr-6 text-right whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border backdrop-blur-xs
                                @if(str_contains($item->status, 'Memenuhi')) bg-emerald-500/10 text-emerald-700 border-emerald-500/20
                                @elseif(str_contains($item->status, 'Proses')) bg-indigo-500/10 text-indigo-700 border-indigo-500/20
                                @else bg-rose-500/10 text-rose-700 border-rose-500/20 @endif">
                                <span class="w-1.5 h-1.5 rounded-full @if(str_contains($item->status, 'Memenuhi')) bg-emerald-500 @elseif(str_contains($item->status, 'Proses')) bg-indigo-500 @else bg-rose-500 @endif"></span>
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Belum ada riwayat asesmen tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [
                {
                    name: 'Target JP Kumulatif',
                    data: [20, 24, 20, 30, 40, 25, 20, 20]
                },
                {
                    name: 'Realisasi JP Tercapai',
                    data: [20, 12, 0, 30, 40, 0, 10, 20]
                }
            ],
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#cbd5e1', '#4f46e5'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 6
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: ['Instruktur 1', 'Kasubag', 'Instruktur 2', 'Kepala BPVP', 'Instruktur 3', 'Instruktur 4', 'Instruktur 5', 'Asesor Mutu'],
                labels: {
                    style: { colors: '#64748b', fontSize: '11px' }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                title: { text: 'Jam Pelajaran (JP)', style: { color: '#94a3b8', fontSize: '11px' } },
                labels: {
                    style: { colors: '#64748b', fontSize: '11px' }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                markers: { radius: 12 }
            }
        };

        var chart = new ApexCharts(document.querySelector("#jpProgressChart"), options);
        chart.render();
    });
</script>
@endpush

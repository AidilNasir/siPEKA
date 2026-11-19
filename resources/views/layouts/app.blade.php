<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50/70 antialiased font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - siPEKA BPVP Kendari</title>

    <!-- Favicon with User's Official Logo -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- ApexCharts CDN for smooth modern charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- SweetAlert2 CDN for professional popup notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js CDN for fluid micro-interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        /* Custom subtle scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* SweetAlert2 SiPEKA Figma-Grade Popup Styling */
        .swal2-popup.swal2-toast.swal2-toast-sipeka {
            background: #ffffff !important;
            border-radius: 18px !important;
            box-shadow: 0 20px 40px -10px rgba(9, 30, 58, 0.18), 0 0 0 1px rgba(9, 30, 58, 0.08) !important;
            padding: 14px 18px !important;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
        }
        .swal2-toast.swal2-toast-sipeka .swal2-title {
            font-size: 13.5px !important;
            font-weight: 700 !important;
            color: #091E3A !important;
            margin-bottom: 2px !important;
        }
        .swal2-toast.swal2-toast-sipeka .swal2-html-container {
            font-size: 12px !important;
            color: #475569 !important;
            line-height: 1.45 !important;
            margin: 0 !important;
        }
        .swal2-toast.swal2-toast-sipeka .swal2-timer-progress-bar {
            background: #10b981 !important;
            height: 3px !important;
        }

        /* Essential print reset for proper multipage pagination */
        @media print {
            html, body {
                height: auto !important;
                min-height: 100% !important;
                overflow: visible !important;
                background: white !important;
            }
            aside, header, nav, .no-print {
                display: none !important;
            }
            div, main {
                overflow: visible !important;
                height: auto !important;
                max-height: none !important;
            }
        }
    </style>
</head>
<body class="h-full text-slate-800 flex overflow-hidden selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Mobile / Tablet Sidebar Backdrop -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
        style="display: none;"
    ></div>

    <!-- Sidebar Component (Styled matching Image 2: Deep Navy #091e3a with crisp white text & white active pill) -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-[#091E3A] text-white flex flex-col justify-between transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-[#0d2a4f]"
    >
        <!-- Sidebar Top: Brand & Navigation -->
        <div class="flex flex-col h-full overflow-hidden">
            <!-- Brand / Logo Area (Exact match to Image 2 header) -->
            <div class="h-24 px-6 flex items-center justify-between border-b border-white/10 shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 group">
                    <!-- Kemnaker Logo in Pure White matching Image 2 -->
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="Logo BPVP Kendari" 
                        class="w-10 h-10 object-contain brightness-0 invert drop-shadow-sm transition-transform group-hover:scale-105"
                    >
                    <div>
                        <div class="font-extrabold text-base tracking-tight text-white uppercase leading-tight">
                            BPVP KENDARI
                        </div>
                        <p class="text-xs font-semibold text-sky-400 tracking-wide">
                            Kemnaker RI
                        </p>
                    </div>
                </a>

                <!-- Mobile close button (No background) -->
                <button 
                    @click="sidebarOpen = false" 
                    class="lg:hidden p-1.5 text-sky-200/70 hover:text-white transition-colors"
                    aria-label="Tutup Menu"
                >
                    <svg class="w-5 h-5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Nav Links (Scrollable & closes mobile sidebar on navigation) -->
            <div class="flex-1 px-4 py-6 space-y-7 overflow-y-auto">
                @if(auth()->check() && auth()->user()->isPegawai())
                <!-- ================= PEGAWAI ASN NAVIGATION ================= -->
                <!-- Group 1: Menu Pegawai -->
                <div>
                    <p class="px-4 text-[11px] font-extrabold uppercase tracking-widest text-sky-200/60 mb-3">Menu Pegawai</p>
                    <nav class="space-y-1.5">
                        <!-- Dashboard -->
                        <a 
                            href="{{ route('dashboard') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('dashboard*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('dashboard*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect width="7" height="9" x="3" y="3" rx="1.5" />
                                    <rect width="7" height="5" x="14" y="3" rx="1.5" />
                                    <rect width="7" height="9" x="14" y="12" rx="1.5" />
                                    <rect width="7" height="5" x="3" y="16" rx="1.5" />
                                </svg>
                                <span>Dashboard</span>
                            </div>
                        </a>

                        <!-- Kesenjangan ASN -->
                        <a 
                            href="{{ route('analisis.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('analisis*') || request()->routeIs('gap.monitoring') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('analisis*') || request()->routeIs('gap.monitoring') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Kesenjangan ASN</span>
                            </div>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ request()->routeIs('analisis*') ? 'bg-[#091E3A] text-white' : 'bg-sky-400/20 text-sky-300' }}">Monev</span>
                        </a>

                        <!-- Pemenuhan 20 JP -->
                        <a 
                            href="{{ route('pengembangan.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('pengembangan*') || request()->routeIs('training.plans') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('pengembangan*') || request()->routeIs('training.plans') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                </svg>
                                <span>Pemenuhan 20 JP</span>
                            </div>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ request()->routeIs('pengembangan*') ? 'bg-[#091E3A] text-white' : 'bg-emerald-400/20 text-emerald-300' }}">IDP</span>
                        </a>
                    </nav>
                </div>

                <!-- Group 2: Data & Standar -->
                <div>
                    <p class="px-4 text-[11px] font-extrabold uppercase tracking-widest text-sky-200/60 mb-3">Data & Standar</p>
                    <nav class="space-y-1.5">
                        <!-- Profil Pegawai -->
                        <a 
                            href="{{ route('pegawai.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('pegawai*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('pegawai*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                <span>Profil Pegawai</span>
                            </div>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ request()->routeIs('pegawai*') ? 'bg-[#091E3A] text-white' : 'bg-white/10 text-slate-300' }}">ASN</span>
                        </a>

                        <!-- Standar SKJ Jabatan -->
                        <a 
                            href="{{ route('kompetensi.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center gap-3.5 px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('kompetensi*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('kompetensi*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Standar SKJ</span>
                        </a>
                    </nav>
                </div>

                <!-- Group 3: Laporan & Cetak -->
                <div>
                    <p class="px-4 text-[11px] font-extrabold uppercase tracking-widest text-sky-200/60 mb-3">Laporan & Cetak</p>
                    <nav class="space-y-1.5">
                        <a 
                            href="{{ route('laporan.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center gap-3.5 px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('laporan*') || request()->routeIs('reports*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('laporan*') || request()->routeIs('reports*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                            </svg>
                            <span>Laporan Monev</span>
                        </a>
                    </nav>
                </div>
                @else
                <!-- ================= ADMINISTRATOR NAVIGATION ================= -->
                <!-- Group 1: Menu Utama Eksekutif -->
                <div>
                    <p class="px-4 text-[11px] font-extrabold uppercase tracking-widest text-sky-200/60 mb-3">Menu Administrator</p>
                    <nav class="space-y-1.5">
                        <!-- Dashboard -->
                        <a 
                            href="{{ route('dashboard') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('dashboard*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('dashboard*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect width="7" height="9" x="3" y="3" rx="1.5" />
                                    <rect width="7" height="5" x="14" y="3" rx="1.5" />
                                    <rect width="7" height="9" x="14" y="12" rx="1.5" />
                                    <rect width="7" height="5" x="3" y="16" rx="1.5" />
                                </svg>
                                <span>Dashboard Eksekutif</span>
                            </div>
                        </a>

                        <!-- Analisis Kesenjangan -->
                        <a 
                            href="{{ route('analisis.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('analisis*') || request()->routeIs('gap.monitoring') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('analisis*') || request()->routeIs('gap.monitoring') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                    <path d="M2 12h20" />
                                </svg>
                                <span>Kesenjangan ASN</span>
                            </div>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ request()->routeIs('analisis*') ? 'bg-[#091E3A] text-white' : 'bg-sky-400/20 text-sky-300' }}">Monev</span>
                        </a>

                        <!-- Pemenuhan Hak 20 JP -->
                        <a 
                            href="{{ route('pengembangan.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('pengembangan*') || request()->routeIs('training.plans') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('pengembangan*') || request()->routeIs('training.plans') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                </svg>
                                <span>Pemenuhan 20 JP</span>
                            </div>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ request()->routeIs('pengembangan*') ? 'bg-[#091E3A] text-white' : 'bg-emerald-400/20 text-emerald-300' }}">IDP</span>
                        </a>
                    </nav>
                </div>

                <!-- Group 2: Master Data -->
                <div>
                    <p class="px-4 text-[11px] font-extrabold uppercase tracking-widest text-sky-200/60 mb-3">Master Data</p>
                    <nav class="space-y-1.5">
                        <!-- Data Pegawai -->
                        <a 
                            href="{{ route('pegawai.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center justify-between px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('pegawai*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('pegawai*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                <span>Kelola Pegawai</span>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ request()->routeIs('pegawai*') ? 'bg-[#091E3A] text-white' : 'bg-white/10 text-slate-300' }}">83</span>
                        </a>

                        <!-- Standar Jabatan (SKJ) -->
                        <a 
                            href="{{ route('kompetensi.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center gap-3.5 px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('kompetensi*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('kompetensi*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Standar SKJ</span>
                        </a>
                    </nav>
                </div>

                <!-- Group 3: Laporan & Audit -->
                <div>
                    <p class="px-4 text-[11px] font-extrabold uppercase tracking-widest text-sky-200/60 mb-3">Laporan & Audit</p>
                    <nav class="space-y-1.5">
                        <a 
                            href="{{ route('laporan.index') }}" 
                            @click="if (window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center gap-3.5 px-4 py-3 text-xs sm:text-sm font-bold rounded-2xl transition-all group {{ request()->routeIs('laporan*') || request()->routeIs('reports*') ? 'bg-white text-[#091E3A] shadow-lg shadow-black/10' : 'text-slate-300 hover:text-white hover:bg-white/10' }}"
                        >
                            <svg class="w-5 h-5 stroke-[2] shrink-0 {{ request()->routeIs('laporan*') || request()->routeIs('reports*') ? 'text-[#091E3A]' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                            </svg>
                            <span>Laporan Monev</span>
                        </a>
                    </nav>
                </div>
                @endif
            </div>

            <!-- Sidebar Bottom: System Action Capsule matching Image 2 -->
            <div class="p-4 border-t border-white/10">
                <a 
                    href="{{ route('logout') }}" 
                    class="w-full flex items-center justify-center gap-2.5 py-3 px-4 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white font-bold text-xs sm:text-sm transition-all shadow-sm active:scale-[0.98]"
                >
                    <svg class="w-4 h-4 stroke-[2.2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    <span>Keluar Sistem</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area (Fully responsive) -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-16 sm:h-20 bg-white border-b border-slate-200/80 z-30 px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-3 sm:gap-4 shrink-0 shadow-2xs">
            <!-- Left: Toggle Sidebar on Mobile & Breadcrumb -->
            <div class="flex items-center gap-2.5 sm:gap-4 min-w-0">
                <!-- Hamburger Button (Pure icon) -->
                <button 
                    @click="sidebarOpen = true" 
                    class="lg:hidden p-2 text-slate-700 hover:text-slate-900 transition-colors"
                    aria-label="Buka Menu"
                >
                    <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>

                <!-- Mobile Logo indicator -->
                <div class="lg:hidden flex items-center shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 sm:gap-2 text-[10px] sm:text-xs font-medium text-slate-400 mb-0.5 truncate">
                        <span>BPVP KENDARI</span>
                        <svg class="w-3 h-3 text-slate-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-[#091E3A] font-semibold truncate">Kementerian Ketenagakerjaan</span>
                    </div>
                    <h1 class="text-xs sm:text-sm lg:text-base font-bold text-slate-900 tracking-tight truncate">
                        @yield('header_title', 'Dashboard Pemantauan Kesenjangan')
                    </h1>
                </div>
            </div>

            <!-- Right: Search, Filter, Profile -->
            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                <!-- Agency / Period Selector (Tablet/Desktop) -->
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-50 rounded-xl border border-slate-200/80">
                    <!-- Clean Calendar Icon (No Background) -->
                    <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span>Tahun Anggaran 2026</span>
                </div>

                <!-- Quick Export Excel Button (Pure icon with text) -->
                <a 
                    href="{{ route('laporan.exportExcel') }}" 
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors"
                    title="Unduh Workbook Excel (2-Sheet Rapi)"
                >
                    <svg class="w-3.5 h-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    <span>Export Excel</span>
                </a>

                <!-- User Profile / Agency Logo with pure transparent logo and no role badge for Pegawai -->
                <div class="flex items-center gap-2.5 sm:gap-3 pl-3 border-l border-slate-200">
                    <div class="flex flex-col text-right">
                        <span class="text-xs sm:text-sm font-semibold text-slate-800 leading-tight truncate max-w-[140px] sm:max-w-[220px]">
                            {{ Auth::check() ? Auth::user()->name : 'Tamu Sistem' }}
                        </span>
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <div class="flex items-center justify-end mt-0.5">
                                <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-800 border border-amber-200/80">Admin</span>
                            </div>
                        @endif
                    </div>
                    <!-- Pure Official Logo (NO Background Box) -->
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="Logo Kemnaker" 
                        class="w-7 h-7 sm:w-8 sm:h-8 object-contain shrink-0"
                    >
                </div>
            </div>
        </header>

        <!-- Main Dynamic View Container (Responsive padding across mobile, tablet, desktop) -->
        <main class="flex-1 overflow-y-auto p-3.5 sm:p-6 lg:p-8 bg-slate-50/70">
            @yield('content')
        </main>
    </div>

    <!-- SweetAlert2 Toast & Popup Notification Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                },
                customClass: {
                    popup: 'swal2-toast-sipeka'
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: 'Pemberitahuan',
                    text: @json(session('error')),
                });
            @endif

            @if(session('status'))
                Toast.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: @json(session('status')),
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>

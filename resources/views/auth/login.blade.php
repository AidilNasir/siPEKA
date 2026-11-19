<!DOCTYPE html>
<html lang="id" class="h-full antialiased font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in - siPEKA BPVP Kendari</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        /* Modern High-Tech Grid Pattern */
        .bg-tech-grid {
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 36px 36px;
            mask-image: radial-gradient(ellipse at 50% 50%, black 50%, transparent 95%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 50%, black 50%, transparent 95%);
        }

        /* 3D Spheres harmonized with Logo Color #13416B */
        .sphere-logo-1 {
            background: radial-gradient(circle at 35% 30%, #3880b9 0%, #1e5a8f 45%, #13416b 75%, #0a2540 100%);
            box-shadow: 0 24px 48px -12px rgba(10, 37, 64, 0.65), inset -8px -8px 20px rgba(0, 0, 0, 0.4);
        }
        .sphere-logo-2 {
            background: radial-gradient(circle at 30% 30%, #4a95d4 0%, #1b558b 55%, #13416b 85%, #081d33 100%);
            box-shadow: 0 16px 32px -8px rgba(10, 37, 64, 0.55), inset -5px -5px 14px rgba(0, 0, 0, 0.35);
        }
        .sphere-logo-corner {
            background: radial-gradient(circle at 35% 35%, #4a95d4 0%, #1b558b 60%, #13416b 100%);
            box-shadow: -8px -8px 30px rgba(19, 65, 107, 0.35);
        }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-[#0a2540] via-[#13416B] to-[#1c5588] relative selection:bg-[#13416B] selection:text-white"
    x-data="{
        showPassword: false
    }"
>
    <!-- Background High-Tech Grid & Ambient Lighting -->
    <div class="fixed inset-0 bg-tech-grid pointer-events-none"></div>
    <div class="fixed -top-40 -left-40 w-96 h-96 rounded-full bg-sky-400/15 blur-3xl pointer-events-none"></div>
    <div class="fixed -bottom-40 -right-40 w-96 h-96 rounded-full bg-[#0a2540]/50 blur-3xl pointer-events-none"></div>

    <!-- The Master Single Card (Matching User Image Layout) -->
    <div class="w-full max-w-4xl bg-white rounded-[36px] shadow-2xl shadow-slate-950/45 overflow-hidden relative flex flex-col md:flex-row min-h-[520px] z-10">
        
        <!-- LEFT HALF: Navy Curved Visual matching Logo Palette (#13416B) with 3D Spheres -->
        <div class="relative w-full md:w-[47%] bg-gradient-to-br from-[#1b558b] via-[#13416B] to-[#0c2842] text-white p-6 sm:p-8 md:p-10 lg:p-12 flex flex-col justify-between overflow-hidden">
            
            <!-- Large Organic 3D Sphere Overlays matching user reference -->
            <div class="absolute -top-16 -right-16 w-80 h-80 rounded-full bg-gradient-to-br from-sky-400/15 to-transparent pointer-events-none"></div>
            
            <!-- Big Central-Bottom Floating 3D Sphere -->
            <div class="sphere-logo-1 w-44 h-44 sm:w-52 sm:h-52 rounded-full absolute -bottom-10 left-16 sm:left-20 pointer-events-none transition-transform hover:scale-105 duration-300"></div>
            
            <!-- Secondary Bottom-Left Overlapping Sphere -->
            <div class="sphere-logo-2 w-32 h-32 rounded-full absolute -bottom-8 -left-8 pointer-events-none opacity-90"></div>

            <!-- Left Content: Clean Enlarged Logo without Background Pill -->
            <div class="relative z-10">
                <!-- Enlarged Logo with BPVP KENDARI on Top & Kemnaker RI on Bottom -->
                <div class="flex items-center gap-3.5 sm:gap-4 mb-5 md:mb-9">
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="Logo Kemnaker" 
                        class="w-12 h-12 sm:w-14 sm:h-14 object-contain brightness-0 invert drop-shadow-md"
                    >
                    <div class="text-left">
                        <div class="text-sm sm:text-base md:text-lg font-black text-white tracking-tight leading-tight uppercase">
                            BPVP KENDARI
                        </div>
                        <div class="text-[11px] sm:text-xs font-semibold text-sky-200/90 tracking-wide mt-0.5">
                            Kemnaker RI
                        </div>
                    </div>
                </div>

                <!-- Big Bold Typography matching image -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white tracking-tight uppercase leading-none">
                    WELCOME
                </h1>
                <div class="text-[11px] sm:text-xs md:text-sm font-extrabold tracking-widest text-sky-200 uppercase mt-2 md:mt-2.5 mb-1 md:mb-4">
                    siPEKA APARATUR PORTAL
                </div>

                <!-- Paragraph Description (Hidden on mobile to keep card compact & not elongated) -->
                <p class="hidden md:block text-xs sm:text-sm text-sky-100/90 leading-relaxed max-w-xs font-normal">
                    Sistem Pemantauan Kesenjangan Aparatur dan Akselerasi Pemenuhan Hak 20 Jam Pelajaran (JP) Balai Pelatihan Vokasi dan Produktivitas Kendari.
                </p>
            </div>

            <!-- Left Footer Reassurance (Hidden on mobile) -->
            <div class="hidden md:block relative z-10 pt-8 mt-auto text-[11px] text-sky-200/80 font-medium">
                Portal Resmi Tata Kelola SDM
            </div>
        </div>

        <!-- RIGHT HALF: Clean White Form with Show Password & Direct Login -->
        <div class="relative w-full md:w-[53%] bg-white p-6 sm:p-8 md:p-10 lg:p-12 flex flex-col justify-center overflow-hidden">
            
            <!-- Decorative Blue Corner Sphere (Exact match to bottom-right sphere in user image) -->
            <div class="sphere-logo-corner w-32 h-32 sm:w-40 sm:h-40 rounded-full absolute -bottom-16 -right-16 pointer-events-none opacity-85"></div>

            <div class="relative z-10 max-w-sm w-full mx-auto">
                <!-- Header: Sign in -->
                <div class="mb-6">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#13416B] tracking-tight">
                        Sign in
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Silakan masukkan kredensial akun untuk mengakses sistem
                    </p>
                </div>

                <!-- Flash Alert: Success -->
                @if(session('success'))
                <div class="mb-4 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <!-- Flash Alert: Error -->
                @if($errors->any())
                <div class="mb-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                    @foreach($errors->all() as $err)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $err }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- The Form (Direct Username & Password) -->
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Input 1: User Name (Styled with User Icon) -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <!-- User Silhouette Icon -->
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus
                            placeholder="User Name"
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#13416B]/15 focus:border-[#13416B] transition-all"
                        >
                    </div>

                    <!-- Input 2: Password (Styled with Lock Icon & SHOW Toggle) -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <!-- Padlock Icon -->
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="Password"
                            class="w-full pl-10 pr-20 py-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#13416B]/15 focus:border-[#13416B] transition-all"
                        >
                        <!-- Uppercase SHOW / HIDE Button matching reference image -->
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-[11px] font-extrabold text-[#13416B] hover:text-[#0a2540] tracking-wider transition-colors select-none"
                        >
                            <span x-text="showPassword ? 'HIDE' : 'SHOW'">SHOW</span>
                        </button>
                    </div>

                    <!-- Options Line: Remember me (Clean, no extra petunjuk link) -->
                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 rounded text-[#13416B] border-slate-300 focus:ring-[#13416B]/20"
                            >
                            <span class="text-slate-600 font-medium text-[11.5px]">Remember me</span>
                        </label>
                    </div>

                    <!-- Main Sign In Button (Matching Logo Color #13416B) -->
                    <button 
                        type="submit"
                        class="w-full py-3.5 px-4 rounded-2xl bg-[#13416B] hover:bg-[#0d2f4d] text-white font-extrabold text-xs sm:text-sm shadow-md shadow-[#13416B]/30 hover:shadow-lg hover:shadow-[#13416B]/40 active:scale-[0.99] transition-all flex items-center justify-center gap-2 mt-4"
                    >
                        <span>Sign in</span>
                    </button>
                </form>

                <!-- Footer disclaimer text -->
                <div class="mt-8 text-center text-[10.5px] text-slate-400 font-medium">
                    BPVP Kendari | Design by  <a href="https://a2sultra.com/">A2 Dev</a>
                </div>
            </div>
        </div>

    </div>
</body>
</html>

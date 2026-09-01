<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB] overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Konsol Admin - Potret Diri' }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fredoka:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .logo-text {
            font-family: 'Fredoka', 'Plus Jakarta Sans', sans-serif;
            color: #F5BD23;
            -webkit-text-stroke: 1.2px #1E293B;
            text-shadow: 2px 2px 0px #0F172A;
        }
        /* Custom smooth scrollbar for main content */
        main::-webkit-scrollbar {
            width: 6px;
        }
        main::-webkit-scrollbar-track {
            background: transparent;
        }
        main::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        main::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>
<body class="h-full flex flex-col bg-[#E5E7EB] text-slate-800 antialiased selection:bg-[#F5BD23] selection:text-slate-900 overflow-hidden">

    <!-- Top Navigation Header -->
    <header class="w-full h-16 bg-white border-b border-slate-200/80 px-6 py-3 flex justify-between items-center z-30 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="inline-block transition-transform hover:scale-105">
            <h1 class="logo-text text-2xl sm:text-3xl font-black tracking-wide select-none">
                Potret Diri
            </h1>
        </a>

        <!-- Profile Avatar & Studio Name -->
        <div class="flex items-center gap-3">
            <div class="hidden sm:block text-right">
                <div class="text-xs font-black text-slate-900">{{ auth()->user()->studio_name ?? 'Studio Potret Diri' }}</div>
                <div class="text-[10px] text-slate-400 font-bold uppercase">{{ auth()->user()->name }}</div>
            </div>
            <div class="w-10 h-10 rounded-full border border-slate-300 bg-slate-100 flex items-center justify-center text-slate-600 text-lg shadow-sm">
                👤
            </div>
        </div>
    </header>

    <div class="flex-1 flex w-full h-[calc(100vh-4rem)] overflow-hidden">
        
        <!-- Left Sidebar Navigation (Locked / Fixed / Perfectly Aligned) -->
        <aside class="w-64 bg-white border-r border-slate-200/80 p-5 flex flex-col justify-between flex-shrink-0 h-full overflow-hidden select-none">
            <div class="space-y-5">
                
                <div class="px-3 pt-1">
                    <span class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase block">
                        ADMIN STUDIO
                    </span>
                </div>

                <!-- Menu Items List (Pixel-Perfect Alignment) -->
                <nav class="space-y-1.5 text-xs font-bold">
                    
                    <!-- 1. Beranda -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Beranda</span>
                    </a>

                    <!-- 2. Kontrol Sesi -->
                    <a href="{{ route('admin.session-control') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.session-control*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Kontrol Sesi</span>
                    </a>

                    <!-- 3. Galeri Foto -->
                    <a href="{{ route('admin.gallery') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.gallery*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Galeri Foto</span>
                    </a>

                    <!-- 4. Pengaturan QRIS -->
                    <a href="{{ route('admin.qris') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.qris*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Pengaturan QRIS</span>
                    </a>

                    <!-- 5. Template Foto -->
                    <a href="{{ route('admin.templates') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.templates*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Template Foto</span>
                    </a>

                    <!-- 6. Status Sistem -->
                    <a href="{{ route('admin.status') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.status*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Status Sistem</span>
                    </a>
                </nav>
            </div>

            <!-- Bottom Sidebar Actions (Mulai Sesi Baru & Logout) -->
            <div class="space-y-2.5 pt-5 border-t border-slate-100">
                <a href="{{ route('booth.index') }}" target="_blank"
                   class="w-full py-3.5 px-4 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Mulai Sesi Baru</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2.5 px-3.5 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 font-bold text-xs transition flex items-center gap-3">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight">Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content View Area -->
        <main class="flex-1 p-6 sm:p-10 max-w-7xl overflow-y-auto">
            
            <!-- Toast Notification if any -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center gap-2 shadow-sm animate-in fade-in">
                    <span class="text-emerald-600 text-base">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

</body>
</html>

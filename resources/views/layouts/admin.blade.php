<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB] dark:bg-[#090D16] overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Konsol Admin - POTRET' }}</title>
    <!-- Prevent Dark Mode FOUC (Flash of Unstyled Content) -->
    <script>
        if (localStorage.getItem('admin_theme') === 'dark' || (!('admin_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        logo: ['Fredoka', 'Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
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

        /* Dark Mode Admin Styles */
        html.dark {
            color-scheme: dark;
        }
        html.dark body {
            background-color: #090D16;
            color: #F1F5F9;
        }
        html.dark header {
            background-color: #0F172A;
            border-color: #1E293B;
        }
        html.dark aside {
            background-color: #0F172A;
            border-color: #1E293B;
        }
        
        /* Dark Mode Containers & Cards */
        html.dark .bg-white {
            background-color: #0F172A !important;
            border-color: #1E293B !important;
        }
        html.dark .bg-white\/60,
        html.dark .bg-white\/70,
        html.dark .bg-white\/80 {
            background-color: rgba(15, 23, 42, 0.8) !important;
            border-color: #1E293B !important;
        }
        html.dark .bg-slate-50,
        html.dark .bg-slate-100,
        html.dark .bg-slate-100\/90 {
            background-color: #1E293B !important;
            border-color: #334155 !important;
            color: #F1F5F9;
        }
        html.dark .border-slate-100,
        html.dark .border-slate-200,
        html.dark .border-slate-200\/80,
        html.dark .border-slate-200\/60,
        html.dark .border-slate-300 {
            border-color: #1E293B !important;
        }
        html.dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html.dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #1E293B !important;
        }

        /* Typography */
        html.dark .text-slate-900 {
            color: #F8FAFC !important;
        }
        html.dark .text-slate-800 {
            color: #F1F5F9 !important;
        }
        html.dark .text-slate-700 {
            color: #CBD5E1 !important;
        }
        html.dark .text-slate-600 {
            color: #94A3B8 !important;
        }
        html.dark .text-slate-500 {
            color: #94A3B8 !important;
        }
        html.dark .text-slate-400 {
            color: #64748B !important;
        }

        /* Inputs & Form Controls */
        html.dark input[type="text"],
        html.dark input[type="number"],
        html.dark input[type="email"],
        html.dark input[type="password"],
        html.dark input[type="date"],
        html.dark select,
        html.dark textarea {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
            border-color: #334155 !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #64748B !important;
        }

        /* Table hovers & lists */
        html.dark tr.hover\:bg-slate-50:hover,
        html.dark tr.hover\:bg-slate-50\/80:hover,
        html.dark tr.hover\:bg-slate-50\/50:hover,
        html.dark .hover\:bg-slate-50:hover,
        html.dark .hover\:bg-slate-100:hover {
            background-color: #1E293B !important;
        }

        /* Keep brand yellow elements vibrant with high-contrast dark text */
        html.dark .bg-\[\#F5BD23\] {
            background-color: #F5BD23 !important;
            color: #020617 !important;
        }
        html.dark .bg-\[\#F5BD23\] * {
            color: #020617 !important;
        }

        /* Pastel status pill badges in dark mode */
        html.dark .bg-amber-50 {
            background-color: rgba(245, 158, 11, 0.15) !important;
            border-color: rgba(245, 158, 11, 0.3) !important;
        }
        html.dark .bg-indigo-50,
        html.dark .bg-indigo-50\/70 {
            background-color: rgba(99, 102, 241, 0.15) !important;
            border-color: rgba(99, 102, 241, 0.3) !important;
        }
        html.dark .bg-rose-50 {
            background-color: rgba(244, 63, 94, 0.15) !important;
            border-color: rgba(244, 63, 94, 0.3) !important;
        }
        html.dark .bg-emerald-50 {
            background-color: rgba(16, 185, 129, 0.15) !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }
        html.dark .bg-sky-50,
        html.dark .bg-sky-100 {
            background-color: rgba(14, 165, 233, 0.15) !important;
            border-color: rgba(14, 165, 233, 0.3) !important;
        }

        /* Pill text contrast in dark mode */
        html.dark .text-emerald-700,
        html.dark .text-emerald-600,
        html.dark .text-emerald-800 {
            color: #34D399 !important;
        }
        html.dark .text-rose-700,
        html.dark .text-rose-600,
        html.dark .text-rose-800 {
            color: #FB7185 !important;
        }
        html.dark .text-amber-700,
        html.dark .text-amber-600,
        html.dark .text-amber-800 {
            color: #FBBF24 !important;
        }
        html.dark .text-indigo-700,
        html.dark .text-indigo-600,
        html.dark .text-indigo-800,
        html.dark .text-indigo-900 {
            color: #818CF8 !important;
        }
        html.dark .text-sky-700,
        html.dark .text-sky-600,
        html.dark .text-sky-800 {
            color: #38BDF8 !important;
        }

        /* Active category tabs in template manager */
        html.dark .admin-cat-tab.bg-slate-900 {
            background-color: #F5BD23 !important;
            color: #020617 !important;
            border-color: #F5BD23 !important;
        }

        /* Dark scrollbar */
        html.dark main::-webkit-scrollbar-thumb {
            background: #334155;
        }
        html.dark main::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body class="h-full flex flex-col bg-[#E5E7EB] dark:bg-[#090D16] text-slate-800 dark:text-slate-100 antialiased selection:bg-[#F5BD23] selection:text-slate-900 overflow-hidden transition-colors duration-150">

    <!-- Top Navigation Header -->
    <header class="w-full h-16 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 px-4 sm:px-6 py-3 flex justify-between items-center z-30 flex-shrink-0 transition-colors duration-150">
        <div class="flex items-center gap-3">
            <!-- Mobile Sidebar Hamburger Toggle Button -->
            <button type="button" 
                    id="admin-sidebar-toggle"
                    onclick="toggleAdminSidebar()" 
                    class="lg:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition active:scale-95 shadow-xs"
                    aria-label="Toggle Sidebar Menu">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="inline-block transition-transform hover:scale-105">
                <h1 class="logo-text text-2xl sm:text-3xl font-black tracking-wide select-none">
                    POTRET
                </h1>
            </a>
        </div>

        <!-- Profile Avatar & Studio Name & Dark Mode Toggle -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Theme Toggle Button -->
            <button type="button" 
                    id="admin-theme-toggle" 
                    onclick="toggleAdminTheme()" 
                    class="p-2 sm:px-3 sm:py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all flex items-center gap-2 shadow-sm"
                    title="Ganti Mode Gelap / Terang"
                    aria-label="Toggle Dark Mode">
                <!-- Sun Icon (Shown in Dark Mode) -->
                <svg id="theme-icon-sun" class="w-4 h-4 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon Icon (Shown in Light Mode) -->
                <svg id="theme-icon-moon" class="w-4 h-4 block dark:hidden text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <span id="theme-label" class="text-xs font-bold hidden md:inline text-slate-700 dark:text-slate-200">
                    <span class="dark:hidden">Mode Gelap</span>
                    <span class="hidden dark:inline">Mode Terang</span>
                </span>
            </button>

            <div class="h-6 w-px bg-slate-200 dark:bg-slate-700 hidden sm:block"></div>

            <div class="hidden sm:block text-right">
                <div class="text-xs font-black text-slate-900 dark:text-white truncate max-w-[140px]">{{ auth()->user()->studio_name ?? 'Studio POTRET' }}</div>
                <div class="text-[10px] text-slate-400 font-bold uppercase truncate max-w-[140px]">{{ auth()->user()->name }}</div>
            </div>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 text-base sm:text-lg shadow-sm shrink-0">
                👤
            </div>
        </div>
    </header>

    <div class="flex-1 flex w-full h-[calc(100vh-4rem)] overflow-hidden relative">
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="admin-sidebar-backdrop" 
             onclick="closeAdminSidebar()" 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden hidden transition-opacity duration-300 opacity-0"
             aria-hidden="true"></div>

        <!-- Left Sidebar Navigation (Off-canvas drawer on mobile/tablet, fixed static on desktop) -->
        <aside id="admin-sidebar" 
               class="fixed inset-y-0 left-0 z-50 lg:static lg:z-auto w-64 bg-white dark:bg-slate-900 border-r border-slate-200/80 dark:border-slate-800 p-5 flex flex-col justify-between flex-shrink-0 h-full overflow-y-auto select-none transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none">
            <div class="space-y-5">
                
                <div class="px-3 pt-1 flex items-center justify-between">
                    <span class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase block">
                        ADMIN STUDIO
                    </span>
                    <!-- Mobile Close Button (✕) -->
                    <button type="button" 
                            onclick="closeAdminSidebar()" 
                            class="lg:hidden p-1 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition" 
                            aria-label="Tutup Menu Navigasi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Menu Items List (Pixel-Perfect Alignment) -->
                <nav class="space-y-1.5 text-xs font-bold">
                    
                    <!-- 1. Beranda -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Beranda</span>
                    </a>

                    <!-- 2. Kontrol Sesi -->
                    <a href="{{ route('admin.session-control') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.session-control*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Kontrol Sesi</span>
                    </a>

                    <!-- 3. Galeri Foto -->
                    <a href="{{ route('admin.gallery') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.gallery*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Galeri Foto</span>
                    </a>

                    <!-- 4. Pengaturan QRIS -->
                    <a href="{{ route('admin.qris') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.qris*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Pengaturan QRIS</span>
                    </a>

                    <!-- 5. Template Foto -->
                    <a href="{{ route('admin.templates') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.templates*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                            </svg>
                        </div>
                        <span class="flex-1 text-left leading-tight text-xs tracking-tight">Template Foto</span>
                    </a>

                    <!-- 6. Status Sistem -->
                    <a href="{{ route('admin.status') }}" 
                       class="flex items-center gap-3 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.status*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
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
            <div class="space-y-2.5 pt-5 border-t border-slate-100 dark:border-slate-800">
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
                            class="w-full py-2.5 px-3.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 font-bold text-xs transition flex items-center gap-3">
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
        <main class="flex-1 p-4 sm:p-6 lg:p-10 max-w-7xl overflow-y-auto w-full">
            
            <!-- Toast Notification if any -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 text-xs font-bold flex items-center gap-2 shadow-sm animate-in fade-in">
                    <span class="text-emerald-600 text-base">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <!-- Scripts: Dark Mode & Responsive Off-Canvas Drawer -->
    <script>
        function toggleAdminTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('admin_theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('admin_theme', 'dark');
            }
        }

        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            if (!sidebar || !backdrop) return;
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                closeAdminSidebar();
            } else {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                }, 10);
            }
        }

        function closeAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            if (!sidebar || !backdrop) return;
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
        }

        // Auto-close drawer when clicking on navigation links on small screens
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('#admin-sidebar nav a, #admin-sidebar a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        closeAdminSidebar();
                    }
                });
            });
        });
    </script>
</body>
</html>

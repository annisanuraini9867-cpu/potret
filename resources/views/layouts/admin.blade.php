<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
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
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#E5E7EB] text-slate-800 antialiased selection:bg-[#F5BD23] selection:text-slate-900">

    <!-- Top Navigation Header -->
    <header class="w-full bg-white border-b border-slate-200/80 px-6 py-3 flex justify-between items-center z-30 sticky top-0">
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

    <div class="flex-1 flex w-full">
        
        <!-- Left Sidebar Navigation (Exact Match across all 5 images) -->
        <aside class="w-64 bg-white border-r border-slate-200/80 p-5 flex flex-col justify-between flex-shrink-0 min-h-[calc(100vh-61px)]">
            <div class="space-y-6">
                
                <span class="text-[11px] font-black tracking-widest text-slate-400 uppercase block px-3">
                    ADMIN STUDIO
                </span>

                <!-- Menu Items List -->
                <nav class="space-y-1.5 text-xs font-bold">
                    
                    <!-- 1. Beranda -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="text-base">⊞</span>
                        <span>Beranda</span>
                    </a>

                    <!-- 2. Kontrol Sesi -->
                    <a href="{{ route('admin.session-control') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.session-control*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="text-base">📹</span>
                        <span>Kontrol Sesi</span>
                    </a>

                    <!-- 3. Galeri Foto -->
                    <a href="{{ route('admin.gallery') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.gallery*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="text-base">🖼</span>
                        <span>Galeri Foto</span>
                    </a>

                    <!-- 4. Pengaturan QRIS -->
                    <a href="{{ route('admin.qris') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.qris*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="text-base">▦</span>
                        <span>Pengaturan QRIS</span>
                    </a>

                    <!-- 5. Template Foto -->
                    <a href="{{ route('admin.templates') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.templates*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="text-base">⧉</span>
                        <span>Template Foto</span>
                    </a>

                    <!-- 6. Status Sistem -->
                    <a href="{{ route('admin.status') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.status*') ? 'bg-[#F5BD23] text-slate-950 font-black shadow-md shadow-amber-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="text-base">🛡</span>
                        <span>Status Sistem</span>
                    </a>
                </nav>
            </div>

            <!-- Bottom Sidebar Actions (Mulai Sesi Baru & Logout) -->
            <div class="space-y-3 pt-6 border-t border-slate-100">
                <a href="{{ route('booth.index') }}" target="_blank"
                   class="w-full py-3.5 px-4 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>📸</span>
                    <span>Mulai Sesi Baru</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 font-bold text-xs transition flex items-center gap-2">
                        <span>🚪</span>
                        <span>Keluar Akun</span>
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

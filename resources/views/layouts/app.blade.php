<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F3F4F6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'POTRET - Studio Self-Photo Booth Modern' }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fredoka:wght@600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        logo: ['Fredoka', 'Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            yellow: '#F5BD23',
                            yellowHover: '#E5AC10',
                            blue: '#1D4ED8',
                            dark: '#0F172A',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="flex flex-col min-h-full font-sans text-slate-800 antialiased selection:bg-[#F5BD23] selection:text-slate-900">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Brand Logo Image -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="POTRET Logo" class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                </a>

                <!-- Nav links -->
                <nav class="hidden md:flex items-center gap-6 text-sm font-bold text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-[#1D4ED8] transition-colors {{ request()->routeIs('home') ? 'text-[#1D4ED8]' : '' }}">Beranda</a>
                    <a href="{{ route('home') }}#fitur" class="hover:text-[#1D4ED8] transition-colors">Fitur Software</a>
                    <a href="{{ route('home') }}#harga" class="hover:text-[#1D4ED8] transition-colors flex items-center gap-1.5">
                        <span>Harga Paket</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-rose-500 text-white uppercase tracking-wider animate-pulse">Promo</span>
                    </a>
                    <a href="{{ route('home') }}#kalkulator" class="hover:text-[#1D4ED8] transition-colors">Simulasi Cuan</a>
                    <a href="{{ route('booth.index') }}" class="px-3.5 py-1.5 rounded-full bg-slate-900 text-[#F5BD23] hover:bg-slate-800 transition-all font-black flex items-center gap-1.5 text-xs shadow-sm">
                        <span>📸 Demo Kiosk</span>
                    </a>
                    <a href="{{ route('gallery.index') }}" class="hover:text-[#1D4ED8] transition-colors {{ request()->routeIs('gallery.*') ? 'text-[#1D4ED8]' : '' }} text-xs font-semibold text-slate-500">Unduh Foto</a>
                </nav>

                <!-- Auth Buttons & Mobile Menu Button -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow transition">
                                <span>👤 Konsol Admin</span>
                            </a>
                        @else
                            <a href="{{ route('bookings.my') }}" class="text-xs font-bold text-slate-700 hover:text-[#1D4ED8]">Pesanan Saya</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-rose-600 transition">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-extrabold text-slate-700 hover:text-[#1D4ED8] px-2.5 sm:px-3 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 text-xs font-black shadow-md shadow-amber-500/20 transition-all hover:scale-105">
                            <span class="hidden sm:inline">✨ Mulai Berlangganan</span>
                            <span class="sm:hidden">✨ Coba Gratis</span>
                        </a>
                    @endauth

                    <!-- Mobile Hamburger Toggle Button -->
                    <button type="button" 
                            id="app-mobile-menu-btn"
                            onclick="toggleAppMobileMenu()" 
                            class="md:hidden p-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 transition active:scale-95 ml-1"
                            aria-label="Menu Utama">
                        <svg id="app-menu-icon-open" class="w-5 h-5 block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="app-menu-icon-close" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div id="app-mobile-menu" class="hidden md:hidden border-t border-slate-100 py-3 space-y-2 animate-in fade-in slide-in-from-top-2 duration-200">
                <nav class="flex flex-col space-y-1 text-xs font-bold text-slate-700">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl hover:bg-slate-100 transition {{ request()->routeIs('home') ? 'text-[#1D4ED8] bg-blue-50' : '' }}">Beranda</a>
                    <a href="{{ route('home') }}#fitur" onclick="toggleAppMobileMenu()" class="px-3 py-2 rounded-xl hover:bg-slate-100 transition">Fitur Software</a>
                    <a href="{{ route('home') }}#harga" onclick="toggleAppMobileMenu()" class="px-3 py-2 rounded-xl hover:bg-slate-100 transition flex items-center justify-between">
                        <span>Harga Paket</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-rose-500 text-white uppercase tracking-wider">Promo 40%</span>
                    </a>
                    <a href="{{ route('home') }}#kalkulator" onclick="toggleAppMobileMenu()" class="px-3 py-2 rounded-xl hover:bg-slate-100 transition">Simulasi Cuan</a>
                    <a href="{{ route('booth.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-900 text-[#F5BD23] font-black flex items-center gap-2 text-xs shadow-sm">
                        <span>📸 Buka Demo Kiosk</span>
                    </a>
                    <a href="{{ route('gallery.index') }}" class="px-3 py-2 rounded-xl hover:bg-slate-100 transition text-slate-500">Unduh Foto Hasil Sesi</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-medium shadow-sm mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-600 font-bold text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center justify-between p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-sm font-medium shadow-sm mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-rose-600 font-bold text-lg">⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-sm shadow-sm mb-4">
                <div class="font-bold mb-1">Periksa Error:</div>
                <ul class="list-disc pl-5 space-y-0.5 text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-6">
        @yield('content')
    </main>

    <!-- Footer Copyright -->
    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col md:flex-row justify-between items-center gap-6 text-xs text-slate-500">
            <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-7 w-auto">
                <div>
                    <p class="font-bold text-slate-800">POTRET - Software Self-Photo Booth Kiosk #1 di Indonesia</p>
                    <p class="font-medium text-slate-400">&copy; {{ date('Y') }} POTRET BY. Caboo. All rights reserved.</p>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-4 sm:gap-6 font-semibold">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <a href="{{ route('home') }}#fitur" class="hover:underline">Fitur Software</a>
                <a href="{{ route('home') }}#harga" class="hover:underline text-rose-600 font-bold">Harga Paket</a>
                <a href="{{ route('home') }}#kalkulator" class="hover:underline">Kalkulator ROI</a>
                <a href="{{ route('booth.index') }}" class="hover:underline text-amber-600 font-bold">Demo Kiosk</a>
                <a href="{{ route('gallery.index') }}" class="hover:underline">Ambil Foto</a>
                <a href="{{ route('login') }}" class="hover:underline">Masuk Admin</a>
                <a href="{{ route('register') }}" class="hover:underline text-blue-600 font-bold">Daftar Studio</a>
            </div>
        </div>
    </footer>

    <script>
        function toggleAppMobileMenu() {
            const menu = document.getElementById('app-mobile-menu');
            const iconOpen = document.getElementById('app-menu-icon-open');
            const iconClose = document.getElementById('app-menu-icon-close');
            if (!menu) return;
            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                menu.classList.remove('hidden');
                if (iconOpen) iconOpen.classList.add('hidden');
                if (iconClose) iconClose.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
                if (iconOpen) iconOpen.classList.remove('hidden');
                if (iconClose) iconClose.classList.add('hidden');
            }
        }
    </script>
</body>
</html>

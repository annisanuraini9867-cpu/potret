<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F3F4F6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Potret Diri - Studio Self-Photo Booth Modern' }}</title>
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
                    <img src="{{ asset('images/logo.png') }}" alt="Potret Diri Logo" class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                </a>

                <!-- Nav links -->
                <nav class="hidden md:flex items-center gap-7 text-sm font-bold text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-[#1D4ED8] transition-colors {{ request()->routeIs('home') ? 'text-[#1D4ED8]' : '' }}">Beranda</a>
                    <a href="{{ route('bookings.create') }}" class="hover:text-[#1D4ED8] transition-colors {{ request()->routeIs('bookings.create') ? 'text-[#1D4ED8]' : '' }}">Pesan Jadwal</a>
                    <a href="{{ route('booth.index') }}" class="px-3.5 py-1.5 rounded-full bg-[#F5BD23]/20 text-slate-900 hover:bg-[#F5BD23] transition-all font-black flex items-center gap-1.5">
                        <span>📸 Photo Booth Kiosk</span>
                    </a>
                    <a href="{{ route('gallery.index') }}" class="hover:text-[#1D4ED8] transition-colors {{ request()->routeIs('gallery.*') ? 'text-[#1D4ED8]' : '' }}">Ambil Foto</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow transition">
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
                        <a href="{{ route('login') }}" class="text-xs font-extrabold text-slate-700 hover:text-[#1D4ED8] px-3 py-2">Masuk</a>
                        <a href="{{ route('bookings.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 text-xs font-black shadow-md shadow-amber-500/20 transition-all hover:scale-105">
                            Pesan Sesi Foto
                        </a>
                    @endauth
                </div>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 w-auto">
                <span class="font-medium">&copy; {{ date('Y') }} PotretDiri BY. Caboo. All rights reserved.</span>
            </div>
            <div class="flex gap-4 font-semibold">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <a href="{{ route('bookings.create') }}" class="hover:underline">Pemesanan</a>
                <a href="{{ route('booth.index') }}" class="hover:underline text-amber-600">Photo Booth Kiosk</a>
                <a href="{{ route('gallery.index') }}" class="hover:underline">Ambil Foto</a>
                <a href="{{ route('login') }}" class="hover:underline">Konsol Admin</a>
            </div>
        </div>
    </footer>
</body>
</html>

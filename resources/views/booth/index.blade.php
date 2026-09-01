<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Potret Diri - Kiosk</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #7C621A;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Pulse glow animation for the center START PHOTO button hotspot */
        @keyframes pulse-ring {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(249, 200, 14, 0);
            }
            50% {
                box-shadow: 0 0 40px 15px rgba(249, 200, 14, 0.45);
            }
        }
        .glow-hover:hover {
            animation: pulse-ring 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="w-full h-full flex items-center justify-center relative select-none">

    <!-- 1:1 Responsive Aspect Ratio Stage (1024 x 728) -->
    <div class="relative w-full h-full max-w-[calc(100vh*1024/728)] max-h-[calc(100vw*728/1024)] aspect-[1024/728] flex items-center justify-center">
        
        <!-- Exact Artwork Background Image (Pixel-Perfect Match) -->
        <img src="{{ asset('images/kiosk-exact.png') }}" 
             alt="Potret Diri Photo Booth Kiosk" 
             class="w-full h-full object-contain pointer-events-none drop-shadow-2xl">

        <!-- ======================================================== -->
        <!-- INTERACTIVE HOTSPOTS OVER EXACT BUTTON POSITIONS        -->
        <!-- ======================================================== -->

        <!-- 1. Top-Left: Konsol Admin Hotspot -->
        <a href="{{ route('login') }}" 
           title="Konsol Admin"
           class="absolute rounded-full cursor-pointer hover:bg-white/10 active:scale-95 transition-all duration-150"
           style="left: 3.2%; top: 5.0%; width: 20.0%; height: 5.6%;">
            <span class="sr-only">Konsol Admin</span>
        </a>

        <!-- 2. Dead Center: START PHOTO Circular Button Hotspot -->
        <button type="button" 
                onclick="openStartModal()"
                title="START PHOTO - Sentuh untuk Memulai"
                class="absolute rounded-full cursor-pointer glow-hover hover:scale-105 active:scale-95 transition-all duration-200"
                style="left: 39.9%; top: 29.5%; width: 20.0%; height: 28.0%;">
            <span class="sr-only">START PHOTO</span>
        </button>

        <!-- 3. Bottom Center: Sesi Terkunci Pill Hotspot -->
        <button type="button" 
                onclick="openStartModal()"
                title="Sesi terkunci — klik untuk membuka sesi foto"
                class="absolute rounded-full cursor-pointer hover:bg-white/10 active:scale-95 transition-all duration-150"
                style="left: 30.0%; top: 57.7%; width: 38.3%; height: 12.6%;">
            <span class="sr-only">Sesi terkunci — pembayaran diperlukan</span>
        </button>

    </div>

    <!-- ======================================================== -->
    <!-- INTERACTIVE UNLOCK / START MODAL POPUP                   -->
    <!-- ======================================================== -->
    <div id="start-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-[#18181B] rounded-3xl border border-white/20 p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5 text-center relative animate-in fade-in zoom-in duration-200">
            
            <button type="button" onclick="closeStartModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-white rounded-full text-lg">
                ✕
            </button>

            <div class="w-16 h-16 rounded-full bg-[#F9C80E]/20 text-[#F9C80E] flex items-center justify-center mx-auto text-3xl">
                📸
            </div>

            <div class="space-y-3">
                <!-- Direct Button: Mulai Sesi Baru & Pilih Template -->
                <a href="{{ route('booth.start.template') }}" 
                   class="inline-flex items-center justify-center gap-2 w-full py-4 px-6 rounded-2xl bg-[#F9C80E] hover:bg-[#FFD12E] active:scale-95 text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl transition-all">
                    <span>✨ Mulai Sesi Baru & Pilih Template →</span>
                </a>

                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-white/10"></div>
                    <span class="flex-shrink mx-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ATAU GUNAKAN KODE</span>
                    <div class="flex-grow border-t border-white/10"></div>
                </div>

                <form action="{{ route('booth.search') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="space-y-1 text-left">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode Booking Reservasi</label>
                        <input type="text" name="booking_code" value="PTD-DEMO-BOOTH" required placeholder="Contoh: PTD-20260901-XXXXX" 
                               class="w-full px-4 py-3 bg-black/60 border-2 border-white/20 rounded-2xl text-center font-mono font-black text-xs uppercase tracking-widest text-white focus:border-[#F9C80E] focus:outline-none">
                    </div>

                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                        Buka dengan Kode Booking
                    </button>
                </form>
            </div>
            </form>

            <div class="pt-2 border-t border-white/10 text-xs text-slate-400 flex justify-between items-center">
                <a href="{{ route('bookings.create') }}" class="text-[#F9C80E] hover:underline">Pesan Jadwal Baru</a>
                <a href="{{ route('home') }}" class="hover:underline">Halaman Utama Web</a>
            </div>
        </div>
    </div>

    <script>
        function openStartModal() {
            document.getElementById('start-modal').classList.remove('hidden');
        }
        function closeStartModal() {
            document.getElementById('start-modal').classList.add('hidden');
        }
    </script>
</body>
</html>

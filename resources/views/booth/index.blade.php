<!DOCTYPE html>
<html lang="id" class="h-full w-full bg-[#7C621A] overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Potret Diri - Kiosk Photo Booth</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #7C621A;
        }

        /* Pulse glow animation on the center START button */
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(249, 200, 14, 0.6), inset 0 0 15px rgba(249, 200, 14, 0.4);
            }
            70% {
                box-shadow: 0 0 0 25px rgba(249, 200, 14, 0), inset 0 0 25px rgba(249, 200, 14, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(249, 200, 14, 0), inset 0 0 0 rgba(249, 200, 14, 0);
            }
        }
        .glow-hover:hover {
            animation: pulse-ring 2s infinite ease-in-out;
        }

        /* Shake animation for wrong PIN */
        @keyframes pin-shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-10px); }
            40%, 80% { transform: translateX(10px); }
        }
        .shake-active {
            animation: pin-shake 0.4s ease-in-out;
        }
    </style>
</head>
<body class="w-full h-full flex items-center justify-center relative select-none overflow-hidden">

    <!-- 1:1 Responsive Aspect Ratio Stage (1024 x 728) -->
    <div class="relative w-full h-full max-w-[calc(100vh*1024/728)] max-h-[calc(100vw*728/1024)] aspect-[1024/728] flex items-center justify-center">
        
        <!-- Exact Artwork Background Image (Pixel-Perfect Match) -->
        <img src="{{ asset('images/kiosk-exact.png') }}" 
             alt="Potret Diri Photo Booth Kiosk" 
             class="w-full h-full object-contain pointer-events-none drop-shadow-2xl">

        <!-- ======================================================== -->
        <!-- INTERACTIVE HOTSPOTS OVER EXACT BUTTON POSITIONS        -->
        <!-- ======================================================== -->

        <!-- 1. Top-Left: Konsol Admin Hotspot (Interactive PIN Modal Trigger) -->
        <button type="button" 
                onclick="openAdminPinModal()"
                title="Konsol Admin - Masukkan PIN Studio"
                class="absolute rounded-full cursor-pointer hover:bg-white/10 active:scale-95 transition-all duration-150 group"
                style="left: 3.2%; top: 5.0%; width: 20.0%; height: 5.6%;">
            <span class="sr-only">Konsol Admin</span>
        </button>

        <!-- 2. Dead Center: START PHOTO Circular Button Hotspot -->
        <button type="button" 
                onclick="openStartModal()"
                title="START PHOTO - Sentuh untuk Memulai"
                class="absolute rounded-full cursor-pointer glow-hover hover:scale-105 active:scale-95 transition-all duration-200"
                style="left: 39.9%; top: 29.5%; width: 20.0%; height: 28.0%;">
            <span class="sr-only">START PHOTO</span>
        </button>

        <!-- 3. Dynamic Bottom Center Pill (Sesuai Status Buka vs Tutup) -->
        <button type="button" 
                onclick="openStartModal()"
                title="{{ ($kioskStatus ?? 'buka') === 'buka' ? 'Sesi Terbuka — Foto bebas tanpa perlu pembayaran' : 'Sesi Terkunci — Pembayaran QRIS diperlukan' }}"
                class="absolute rounded-full cursor-pointer hover:scale-[1.02] active:scale-95 transition-all duration-200 z-10"
                style="left: 30.5%; top: 62.2%; width: 39.0%; height: 12.2%;">
            
            @if(($kioskStatus ?? 'buka') === 'buka')
                <!-- Pill Mode BUKA (Hijau Emas) -->
                <div class="w-full h-full rounded-full bg-[#2A2107]/90 hover:bg-[#2A2107] border-2 border-emerald-400/80 shadow-2xl backdrop-blur-sm flex items-center justify-center gap-3.5 px-5 text-left transition-all">
                    <div class="w-9 h-9 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                        🔓
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs sm:text-sm font-black text-emerald-300 tracking-wide uppercase leading-tight">Sesi Terbuka</div>
                        <div class="text-[10px] sm:text-[11px] text-emerald-100/80 font-semibold truncate leading-tight">Foto bebas langsung tanpa pembayaran</div>
                    </div>
                </div>
            @else
                <!-- Pill Mode TUTUP (Emas Gelap Terkunci) -->
                <div class="w-full h-full rounded-full bg-[#2A2107]/90 hover:bg-[#2A2107] border-2 border-amber-400/80 shadow-2xl backdrop-blur-sm flex items-center justify-center gap-3.5 px-5 text-left transition-all">
                    <div class="w-9 h-9 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/40 flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                        🔒
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs sm:text-sm font-black text-amber-300 tracking-wide uppercase leading-tight">Sesi Terkunci</div>
                        <div class="text-[10px] sm:text-[11px] text-amber-100/80 font-semibold truncate leading-tight">Pembayaran QRIS diperlukan sebelum foto</div>
                    </div>
                </div>
            @endif
        </button>

    </div>

    <!-- ======================================================== -->
    <!-- INTERACTIVE ADMIN PIN MODAL POPUP                        -->
    <!-- ======================================================== -->
    <div id="admin-pin-modal" class="fixed inset-0 bg-black/85 backdrop-blur-md flex items-center justify-center p-4 z-50 hidden">
        <div id="pin-card" class="bg-[#18181B] rounded-3xl border border-white/20 p-6 sm:p-8 max-w-sm w-full shadow-2xl space-y-5 text-center relative animate-in zoom-in duration-200">
            
            <button type="button" onclick="closeAdminPinModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-white rounded-full text-lg leading-none">
                ✕
            </button>

            <!-- Shield Icon -->
            <div class="w-14 h-14 rounded-2xl bg-[#F5BD23]/20 text-[#F5BD23] flex items-center justify-center mx-auto text-2xl border border-[#F5BD23]/30 shadow-inner">
                🛡️
            </div>

            <div class="space-y-1">
                <h3 class="text-xl font-black text-white">Konsol Admin</h3>
                <p class="text-xs text-slate-400">Masukkan 6 digit PIN studio yang dibuat saat registrasi</p>
            </div>

            <!-- PIN Dots Display (6 Digits) -->
            <div id="pin-dots-container" class="flex items-center justify-center gap-3 py-2">
                <div id="pin-dot-0" class="w-4 h-4 rounded-full border-2 border-white/30 transition-all duration-150"></div>
                <div id="pin-dot-1" class="w-4 h-4 rounded-full border-2 border-white/30 transition-all duration-150"></div>
                <div id="pin-dot-2" class="w-4 h-4 rounded-full border-2 border-white/30 transition-all duration-150"></div>
                <div id="pin-dot-3" class="w-4 h-4 rounded-full border-2 border-white/30 transition-all duration-150"></div>
                <div id="pin-dot-4" class="w-4 h-4 rounded-full border-2 border-white/30 transition-all duration-150"></div>
                <div id="pin-dot-5" class="w-4 h-4 rounded-full border-2 border-white/30 transition-all duration-150"></div>
            </div>

            <!-- Error Feedback Box -->
            <div id="pin-error-msg" class="hidden p-2.5 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/40 text-xs font-bold animate-in fade-in"></div>

            <!-- Touch Keypad Grid (3x4) -->
            <div class="grid grid-cols-3 gap-2.5 max-w-[260px] mx-auto pt-1">
                <button type="button" onclick="pressPinKey('1')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">1</button>
                <button type="button" onclick="pressPinKey('2')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">2</button>
                <button type="button" onclick="pressPinKey('3')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">3</button>
                <button type="button" onclick="pressPinKey('4')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">4</button>
                <button type="button" onclick="pressPinKey('5')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">5</button>
                <button type="button" onclick="pressPinKey('6')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">6</button>
                <button type="button" onclick="pressPinKey('7')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">7</button>
                <button type="button" onclick="pressPinKey('8')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">8</button>
                <button type="button" onclick="pressPinKey('9')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">9</button>
                <button type="button" onclick="clearPinKey()" class="pin-key h-12 rounded-2xl bg-white/5 hover:bg-white/15 active:scale-95 text-slate-400 font-bold text-xs transition">C</button>
                <button type="button" onclick="pressPinKey('0')" class="pin-key h-12 rounded-2xl bg-white/10 hover:bg-white/20 active:bg-[#F5BD23] active:text-slate-950 text-white font-black text-lg transition shadow">0</button>
                <button type="button" onclick="deletePinKey()" class="pin-key h-12 rounded-2xl bg-white/5 hover:bg-white/15 active:scale-95 text-slate-400 font-bold text-base transition">⌫</button>
            </div>

            <!-- Alternative Login Link -->
            <div class="pt-2 border-t border-white/10">
                <a href="{{ route('login') }}" class="text-xs text-[#F5BD23] hover:underline font-semibold flex items-center justify-center gap-1">
                    <span>Atau masuk dengan Password →</span>
                </a>
            </div>

        </div>
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

            <!-- Status Indicator Banner -->
            <div class="p-2.5 rounded-2xl text-[11px] font-bold {{ ($kioskStatus ?? 'buka') === 'buka' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                @if(($kioskStatus ?? 'buka') === 'buka')
                    <span>🔓 <strong>Status Kios: BUKA</strong> — Sesi foto bebas tanpa perlu pembayaran!</span>
                @else
                    <span>🔒 <strong>Status Kios: TUTUP</strong> — Sesi terkunci, perlu pembayaran QRIS dahulu.</span>
                @endif
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

            <div class="pt-2 border-t border-white/10 text-xs text-slate-400 flex justify-between items-center">
                <a href="{{ route('bookings.create') }}" class="text-[#F9C80E] hover:underline">Pesan Jadwal Baru</a>
                <a href="{{ route('home') }}" class="hover:underline">Halaman Utama Web</a>
            </div>
        </div>
    </div>

    <!-- Interactive Script -->
    <script>
        function openStartModal() {
            document.getElementById('start-modal').classList.remove('hidden');
        }
        function closeStartModal() {
            document.getElementById('start-modal').classList.add('hidden');
        }

        // ========================================================
        // ADMIN PIN LOGIC & KEYPAD HANDLER
        // ========================================================
        let currentPin = '';
        const VERIFY_URL = "{{ route('booth.verify-admin-pin') }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function openAdminPinModal() {
            currentPin = '';
            updatePinDots();
            hidePinError();
            document.getElementById('admin-pin-modal').classList.remove('hidden');
        }

        function closeAdminPinModal() {
            document.getElementById('admin-pin-modal').classList.add('hidden');
            currentPin = '';
            updatePinDots();
        }

        function pressPinKey(digit) {
            if (currentPin.length < 6) {
                currentPin += digit;
                updatePinDots();
                hidePinError();

                if (currentPin.length === 6) {
                    submitAdminPin();
                }
            }
        }

        function deletePinKey() {
            if (currentPin.length > 0) {
                currentPin = currentPin.slice(0, -1);
                updatePinDots();
                hidePinError();
            }
        }

        function clearPinKey() {
            currentPin = '';
            updatePinDots();
            hidePinError();
        }

        function updatePinDots() {
            for (let i = 0; i < 6; i++) {
                const dot = document.getElementById('pin-dot-' + i);
                if (dot) {
                    if (i < currentPin.length) {
                        dot.className = 'w-4 h-4 rounded-full bg-[#F5BD23] border-2 border-[#F5BD23] shadow-md shadow-amber-500/40 scale-110 transition-all duration-150';
                    } else {
                        dot.className = 'w-4 h-4 rounded-full border-2 border-white/30 transition-all duration-150';
                    }
                }
            }
        }

        function showPinError(message) {
            const errEl = document.getElementById('pin-error-msg');
            const cardEl = document.getElementById('pin-card');
            errEl.innerText = message;
            errEl.classList.remove('hidden');

            cardEl.classList.remove('shake-active');
            void cardEl.offsetWidth; // Trigger reflow
            cardEl.classList.add('shake-active');

            // Reset dots to red briefly
            for (let i = 0; i < 6; i++) {
                const dot = document.getElementById('pin-dot-' + i);
                if (dot) {
                    dot.className = 'w-4 h-4 rounded-full bg-rose-500 border-2 border-rose-500 transition-all duration-150';
                }
            }

            setTimeout(() => {
                currentPin = '';
                updatePinDots();
            }, 600);
        }

        function hidePinError() {
            const errEl = document.getElementById('pin-error-msg');
            errEl.classList.add('hidden');
        }

        async function submitAdminPin() {
            try {
                const response = await fetch(VERIFY_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ pin: currentPin })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Turn dots green
                    for (let i = 0; i < 6; i++) {
                        const dot = document.getElementById('pin-dot-' + i);
                        if (dot) {
                            dot.className = 'w-4 h-4 rounded-full bg-emerald-400 border-2 border-emerald-400 shadow-md shadow-emerald-400/50 transition-all';
                        }
                    }
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 400);
                } else {
                    showPinError(data.message || 'PIN Admin salah. Silakan coba lagi.');
                }
            } catch (err) {
                showPinError('Gagal memverifikasi PIN. Silakan coba lagi.');
            }
        }

        // Support physical keyboard number input
        window.addEventListener('keydown', (e) => {
            const modal = document.getElementById('admin-pin-modal');
            if (modal && !modal.classList.contains('hidden')) {
                if (/^[0-9]$/.test(e.key)) {
                    pressPinKey(e.key);
                } else if (e.key === 'Backspace') {
                    deletePinKey();
                } else if (e.key === 'Escape') {
                    closeAdminPinModal();
                }
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat PIN Admin - Potret Diri</title>
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
<body class="min-h-full flex flex-col justify-between items-center py-6 px-4 antialiased selection:bg-[#F5BD23] selection:text-slate-900">

    <!-- Top Left Brand Logo Header -->
    <header class="w-full max-w-5xl flex items-center justify-start py-2">
        <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
            <h1 class="logo-text text-3xl sm:text-4xl font-black tracking-wide select-none">
                Potret Diri
            </h1>
        </a>
    </header>

    <!-- Main Card Container -->
    <main class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 shadow-sm border border-slate-100 text-center my-auto space-y-6">
        
        <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Buat PIN Admin</h2>
            <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                Atur 6 digit PIN keamanan untuk mengakses konsol manajemen dan kontrol kiosk.
            </p>
        </div>

        <!-- 6 PIN Dots Indicator -->
        <div class="flex items-center justify-center gap-3 py-2">
            <div id="dot-0" class="w-3.5 h-3.5 rounded-full border-2 border-slate-400 bg-transparent transition-all"></div>
            <div id="dot-1" class="w-3.5 h-3.5 rounded-full border-2 border-slate-400 bg-transparent transition-all"></div>
            <div id="dot-2" class="w-3.5 h-3.5 rounded-full border-2 border-slate-400 bg-transparent transition-all"></div>
            <div id="dot-3" class="w-3.5 h-3.5 rounded-full border-2 border-slate-400 bg-transparent transition-all"></div>
            <div id="dot-4" class="w-3.5 h-3.5 rounded-full border-2 border-slate-400 bg-transparent transition-all"></div>
            <div id="dot-5" class="w-3.5 h-3.5 rounded-full border-2 border-slate-400 bg-transparent transition-all"></div>
        </div>

        <form id="pin-form" action="{{ route('onboarding.postSetPin') }}" method="POST">
            @csrf
            <input type="hidden" name="pin" id="pin-value" value="">

            <!-- Touch Keypad Grid (Exact match: gray rounded squares) -->
            <div class="grid grid-cols-3 gap-3 max-w-[260px] mx-auto pt-2">
                <button type="button" onclick="pressKey('1')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">1</button>
                <button type="button" onclick="pressKey('2')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">2</button>
                <button type="button" onclick="pressKey('3')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">3</button>
                <button type="button" onclick="pressKey('4')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">4</button>
                <button type="button" onclick="pressKey('5')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">5</button>
                <button type="button" onclick="pressKey('6')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">6</button>
                <button type="button" onclick="pressKey('7')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">7</button>
                <button type="button" onclick="pressKey('8')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">8</button>
                <button type="button" onclick="pressKey('9')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">9</button>
                <div></div>
                <button type="button" onclick="pressKey('0')" class="h-14 rounded-2xl bg-slate-500 hover:bg-slate-600 active:scale-95 text-white font-bold text-lg shadow transition">0</button>
                <button type="button" onclick="pressBackspace()" class="h-14 rounded-2xl bg-transparent hover:bg-slate-200 active:scale-95 text-slate-500 font-bold text-xl flex items-center justify-center transition">⌫</button>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" id="btn-submit-pin" disabled 
                        class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] disabled:bg-slate-300 disabled:cursor-not-allowed active:scale-[0.99] text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>SIMPAN & LANJUTKAN</span>
                    <span>→</span>
                </button>
            </div>
        </form>

    </main>

    <!-- Footer Space -->
    <footer class="w-full text-center py-4"></footer>

    <script>
        let currentPin = '';

        function pressKey(digit) {
            if (currentPin.length < 6) {
                currentPin += digit;
                updatePinDisplay();
            }
        }

        function pressBackspace() {
            if (currentPin.length > 0) {
                currentPin = currentPin.slice(0, -1);
                updatePinDisplay();
            }
        }

        function updatePinDisplay() {
            document.getElementById('pin-value').value = currentPin;

            for (let i = 0; i < 6; i++) {
                const dot = document.getElementById(`dot-${i}`);
                if (i < currentPin.length) {
                    dot.className = 'w-3.5 h-3.5 rounded-full border-2 border-slate-900 bg-slate-900 transition-all';
                } else {
                    dot.className = 'w-3.5 h-3.5 rounded-full border-2 border-slate-400 bg-transparent transition-all';
                }
            }

            const submitBtn = document.getElementById('btn-submit-pin');
            if (currentPin.length === 6) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // Support keyboard typing
        window.addEventListener('keydown', (e) => {
            if (e.key >= '0' && e.key <= '9') {
                pressKey(e.key);
            } else if (e.key === 'Backspace') {
                pressBackspace();
            } else if (e.key === 'Enter' && currentPin.length === 6) {
                document.getElementById('pin-form').submit();
            }
        });
    </script>
</body>
</html>

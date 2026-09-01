<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Selesaikan Pembayaran - Potret Diri</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between items-center py-6 px-4 antialiased selection:bg-[#F5BD23] selection:text-slate-900 select-none">

    <!-- Top Bar with Countdown Timer (Exact Match to Image 3) -->
    <header class="w-full max-w-4xl flex items-center justify-end gap-3 py-2">
        <div class="px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-800 font-mono font-bold text-xs shadow-sm flex items-center gap-1.5">
            <span>⏱</span>
            <span id="timer-display">05:00</span>
        </div>
        <button type="button" onclick="alert('Hubungi staf studio jika mengalami kendala saat scan QRIS.')" class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-500 font-bold text-xs flex items-center justify-center shadow-sm">
            ?
        </button>
    </header>

    <!-- Main 2-Columns Grid Layout -->
    <main class="w-full max-w-4xl my-auto grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
        
        <!-- KOLOM KIRI: Detail & Tombol Batal (6 Kolom) -->
        <div class="md:col-span-6 space-y-6 text-left">
            
            <div class="space-y-2">
                <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 font-mono font-bold text-[10px] tracking-wider uppercase inline-block">
                    MENUNGGU PEMBAYARAN
                </span>
                <h1 class="text-3xl font-black text-slate-900">Selesaikan Pembayaran</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Scan kode QR di samping menggunakan aplikasi e-wallet pilihan Anda untuk memulai sesi foto premium Anda.
                </p>
            </div>

            <!-- Price & Transaction Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">TOTAL PEMBAYARAN</span>
                    <span class="text-2xl font-black text-slate-900">Rp {{ number_format($session['price'] ?? 35000, 0, ',', '.') }}</span>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
                    <span class="text-slate-400">ID TRANSAKSI</span>
                    <span class="font-mono font-bold text-slate-800">{{ $session['txn_id'] ?? 'PD-8829310X' }}</span>
                </div>
            </div>

            <!-- Cancel Button -->
            <div class="space-y-3">
                <a href="{{ route('booth.index') }}" 
                   class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-2xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-sm transition">
                    <span>✕</span>
                    <span>Batal</span>
                </a>

                <!-- Demo Simulation Button -->
                <form action="{{ route('booth.start.confirmPayment') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-black text-xs border border-emerald-200 transition flex items-center justify-center gap-2">
                        <span>⚡</span>
                        <span>[Demo Kiosk] Simulasikan Pembayaran Berhasil</span>
                    </button>
                </form>
            </div>

        </div>

        <!-- KOLOM KANAN: Tablet Stand QRIS (6 Kolom) -->
        <div class="md:col-span-6 flex justify-center">
            <div class="w-full max-w-[340px] bg-white rounded-3xl p-6 shadow-2xl border border-slate-100 space-y-5 text-center relative">
                
                <!-- Top Gateway Logo Header -->
                <div class="flex justify-between items-center text-xs font-bold px-2">
                    <div class="w-6 h-6 rounded-md bg-[#00AED6] text-white flex items-center justify-center font-black text-[10px]">
                        G
                    </div>
                    <span class="font-mono text-[10px] text-slate-400 font-bold">GPN</span>
                </div>

                <!-- High-Contrast QR Code on Slate Platform -->
                <div class="bg-slate-950 rounded-2xl p-5 aspect-square flex flex-col items-center justify-center relative overflow-hidden shadow-inner group">
                    <div id="qris-qrcode" class="p-3 bg-white rounded-xl shadow-lg"></div>
                    <div class="absolute bottom-2 text-[9px] font-mono text-slate-400">QRIS STANDAR BI</div>
                </div>

                <!-- Studio & NMID Information -->
                <div class="space-y-1">
                    <h4 class="font-black text-xs uppercase tracking-wider text-slate-900">POTRET DIRI STUDIO</h4>
                    <p class="font-mono text-[10px] text-slate-400">NMID: ID1020230000451</p>
                </div>

                <!-- Supported E-Wallets Bar -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-center gap-2">
                    <span class="w-6 h-3.5 bg-slate-400 rounded-sm inline-block opacity-60"></span>
                    <span class="w-6 h-3.5 bg-slate-400 rounded-sm inline-block opacity-60"></span>
                    <span class="w-6 h-3.5 bg-slate-400 rounded-sm inline-block opacity-60"></span>
                    <span class="w-6 h-3.5 bg-slate-400 rounded-sm inline-block opacity-60"></span>
                </div>

            </div>
        </div>

    </main>

    <!-- Bottom Notice (Exact Match) -->
    <footer class="w-full text-center py-4 text-xs text-slate-400 flex items-center justify-center gap-1.5">
        <span>ⓘ</span>
        <span>Tunggu beberapa saat setelah melakukan pembayaran</span>
    </footer>

    <script>
        // Generate QRIS QR code
        window.addEventListener('DOMContentLoaded', () => {
            const qrContainer = document.getElementById('qris-qrcode');
            if (typeof QRCode !== 'undefined') {
                new QRCode(qrContainer, {
                    text: "00020101021226580014ID.LINKAJA.WWW01189360091100200000000215ID10202300004510303UMI51440014ID.GO.GPN.WWW0215ID10202300004510303UMI5204581253033605406{{ $session['price'] ?? 35000 }}5802ID5918POTRET DIRI STUDIO6007JAKARTA61051219062070703A0163048E12",
                    width: 170,
                    height: 170,
                    colorDark : "#0F172A",
                    colorLight : "#FFFFFF",
                    correctLevel : QRCode.CorrectLevel.M
                });
            }

            // 5-minute countdown
            let timeLeft = 300;
            const timerEl = document.getElementById('timer-display');
            const interval = setInterval(() => {
                timeLeft--;
                const min = String(Math.floor(timeLeft / 60)).padStart(2, '0');
                const sec = String(timeLeft % 60).padStart(2, '0');
                timerEl.innerText = `${min}:${sec}`;
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    location.reload();
                }
            }, 1000);
        });
    </script>
</body>
</html>

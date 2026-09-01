<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pembayaran Berhasil - Potret Diri</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between items-center py-8 px-4 antialiased selection:bg-[#F5BD23] selection:text-slate-900 select-none">

    <!-- Top Spacer -->
    <header class="w-full max-w-xl py-2"></header>

    <!-- Main Centered Receipt Card (Exact Match to Image 4) -->
    <main class="w-full max-w-lg bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-slate-100 text-center my-auto space-y-6">
        
        <!-- Big Green Checkmark Icon Badge -->
        <div class="w-24 h-24 rounded-full bg-[#22C55E] text-white flex items-center justify-center mx-auto text-4xl font-black shadow-xl shadow-emerald-500/30 animate-in zoom-in duration-300">
            ✓
        </div>

        <!-- Heading & Subtitle -->
        <div class="space-y-2">
            <h1 class="text-3xl font-black text-slate-900">Pembayaran Berhasil!</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-sm mx-auto">
                Terima kasih. Transaksi Anda telah diverifikasi. Silakan masuk ke dalam studio untuk memulai sesi foto Anda.
            </p>
        </div>

        <!-- Receipt Details Table (Exact Match) -->
        <div class="space-y-3 pt-2 text-xs text-left border-t border-b border-slate-100 py-4">
            <div class="flex justify-between items-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">ORDER ID</span>
                <span class="font-mono font-black text-slate-900">{{ $session['order_id'] ?? '#PD-2026-8892' }}</span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">PAKET</span>
                <span class="font-extrabold text-slate-800">{{ $packageName }}</span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">METODE</span>
                <span class="font-extrabold text-slate-800">QRIS (GoPay)</span>
            </div>
        </div>

        <!-- Glowing CTA Button (Exact Match) -->
        <div class="pt-2 space-y-4">
            <a href="{{ route('booth.session', $bookingCode) }}" 
               class="inline-flex items-center justify-center gap-2 w-full py-4 px-8 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wide shadow-xl shadow-amber-500/40 transition-all">
                <span>📸</span>
                <span>Mulai Sesi Foto</span>
            </a>

            <!-- Auto Redirect Text Countdown -->
            <div class="text-xs text-slate-400 flex items-center justify-center gap-2">
                <span class="w-3.5 h-3.5 rounded-full border-2 border-slate-400 border-t-transparent animate-spin"></span>
                <span>Mengarahkan ke sesi foto dalam <strong id="countdown-num" class="text-amber-600 font-bold">3</strong> detik...</span>
            </div>
        </div>

    </main>

    <!-- Footer Space -->
    <footer class="w-full text-center py-4"></footer>

    <script>
        // 3-second auto-redirect to live capture booth session
        let countdown = 3;
        const countdownEl = document.getElementById('countdown-num');
        const interval = setInterval(() => {
            countdown--;
            if (countdownEl) countdownEl.innerText = countdown;
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('booth.session', $bookingCode) }}";
            }
        }, 1000);
    </script>
</body>
</html>

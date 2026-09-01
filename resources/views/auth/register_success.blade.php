<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Potret Diri</title>
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

    <!-- Main Receipt Card Container -->
    <main class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 shadow-sm border border-slate-100 text-center my-auto space-y-6">
        
        <!-- Big Yellow Checkmark Badge -->
        <div class="w-20 h-20 rounded-full bg-[#F5BD23] text-slate-950 flex items-center justify-center mx-auto text-3xl font-black shadow-lg shadow-amber-500/30">
            ✓
        </div>

        <div class="space-y-1">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Pembayaran Berhasil!</h2>
            <p class="text-xs text-slate-500">
                Selamat! Registrasi admin Anda telah aktif.
            </p>
        </div>

        <!-- Receipt Summary Box -->
        <div class="bg-slate-100/90 rounded-2xl p-5 text-left space-y-3 text-xs">
            <div class="flex justify-between items-center text-slate-500">
                <span>ID Transaksi</span>
                <span class="font-mono font-bold text-slate-800">{{ $receipt['txn_id'] }}</span>
            </div>

            <div class="flex justify-between items-center text-slate-500">
                <span>Paket</span>
                <span class="font-bold text-slate-800">{{ $receipt['package_name'] }}</span>
            </div>

            <div class="flex justify-between items-center text-slate-500">
                <span>Metode Pembayaran</span>
                <span class="font-bold text-slate-800">{{ $receipt['payment_method'] }}</span>
            </div>

            <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                <span class="font-extrabold text-slate-700">Total Pembayaran</span>
                <span class="text-base font-black text-slate-900">Rp {{ number_format($receipt['total_amount'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3 pt-2">
            <a href="{{ route('onboarding.setPin') }}" 
               class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wide shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                <span>Masuk ke Konsol Admin</span>
                <span>→</span>
            </a>

            <button type="button" onclick="window.print()" 
                    class="w-full py-3.5 px-6 rounded-2xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition">
                Unduh Invoice
            </button>
        </div>

    </main>

    <!-- Footer Support Note -->
    <footer class="w-full text-center py-6 text-xs text-slate-500 font-medium select-none max-w-lg">
        Butuh bantuan? Silakan hubungi <a href="mailto:support@potretdiri.id" class="underline text-slate-800 font-bold">Pusat Bantuan</a> kami atau kirim email ke <span class="font-mono">support@potretdiri.id</span>
    </footer>

</body>
</html>

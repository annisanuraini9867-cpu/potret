<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran - Potret Diri</title>
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

    <!-- Step Progress Bar (Step 3 Active) -->
    <div class="w-full max-w-xl my-4">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-[#F5BD23] z-0"></div>

            <!-- Step 1 (Completed) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <a href="{{ route('register') }}" class="w-10 h-10 rounded-full bg-[#F5BD23] text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                    ✓
                </a>
                <span class="text-xs font-bold text-slate-800">Profil</span>
            </div>

            <!-- Step 2 (Completed) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <a href="{{ route('onboarding.step2') }}" class="w-10 h-10 rounded-full bg-[#F5BD23] text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                    ✓
                </a>
                <span class="text-xs font-bold text-slate-800">Studio</span>
            </div>

            <!-- Step 3 (Active) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full bg-[#F5BD23] text-slate-950 font-black text-sm flex items-center justify-center shadow-md ring-4 ring-amber-200/50">
                    3
                </div>
                <span class="text-xs font-bold text-slate-900">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Main Content 2-Columns Layout -->
    <main class="w-full max-w-5xl my-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI: Metode Pembayaran (7 Kolom) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Metode Pembayaran</h2>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Pilih metode pembayaran yang paling sesuai untuk Anda untuk segera mengaktifkan studio Anda.
                </p>
            </div>

            <form id="payment-form" action="{{ route('onboarding.postStep3') }}" method="POST" class="space-y-3.5">
                @csrf

                <!-- Option 1: Virtual Account (Default selected) -->
                <label class="group relative flex items-center justify-between p-5 bg-white border-2 border-[#F5BD23] rounded-2xl cursor-pointer shadow-sm transition-all">
                    <input type="radio" name="payment_method" value="Virtual Account BCA" checked class="peer sr-only">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-700">
                            🏦
                        </div>
                        <div>
                            <h4 class="font-extrabold text-sm text-slate-900">Virtual Account</h4>
                            <p class="text-xs text-slate-400">BCA, Mandiri, BNI, BRI</p>
                        </div>
                    </div>
                    <div class="w-6 h-6 rounded-full border-2 border-[#F5BD23] flex items-center justify-center p-0.5">
                        <div class="w-3.5 h-3.5 rounded-full bg-[#F5BD23]"></div>
                    </div>
                </label>

                <!-- Option 2: QRIS -->
                <label class="group relative flex items-center justify-between p-5 bg-white border-2 border-slate-200 hover:border-[#F5BD23] rounded-2xl cursor-pointer shadow-sm transition-all">
                    <input type="radio" name="payment_method" value="QRIS Instan" class="peer sr-only">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-700">
                            🏁
                        </div>
                        <div>
                            <h4 class="font-extrabold text-sm text-slate-900">QRIS</h4>
                            <p class="text-xs text-slate-400">Gopay, OVO, ShopeePay, Dana</p>
                        </div>
                    </div>
                    <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center p-0.5"></div>
                </label>

                <!-- Option 3: E-Wallet -->
                <label class="group relative flex items-center justify-between p-5 bg-white border-2 border-slate-200 hover:border-[#F5BD23] rounded-2xl cursor-pointer shadow-sm transition-all">
                    <input type="radio" name="payment_method" value="E-Wallet Instan" class="peer sr-only">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-700">
                            📱
                        </div>
                        <div>
                            <h4 class="font-extrabold text-sm text-slate-900">E-Wallet</h4>
                            <p class="text-xs text-slate-400">Pembayaran instan via aplikasi</p>
                        </div>
                    </div>
                    <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center p-0.5"></div>
                </label>
            </form>
        </div>

        <!-- KOLOM KANAN: Ringkasan Pesanan (5 Kolom) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-7 sm:p-8 shadow-sm border border-slate-100 space-y-6">
            <h3 class="text-xl font-black text-slate-900">Ringkasan Pesanan</h3>

            <!-- Studio Name -->
            <div class="space-y-1">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center justify-between">
                    <span>NAMA STUDIO</span>
                    <span>🏪</span>
                </span>
                <p class="text-sm font-extrabold text-slate-900 truncate">
                    {{ $data['studio_name'] ?? 'Lensa Cerah Studio' }}
                </p>
            </div>

            <!-- Plan & Calculation -->
            <div class="space-y-3 pt-3 border-t border-slate-100 text-xs">
                <div class="flex justify-between items-center text-slate-600">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">PAKET TERPILIH</span>
                        <span class="font-extrabold text-slate-800 text-xs">{{ $packageName }}</span>
                    </div>
                    <span class="font-black text-slate-900 text-sm">Rp {{ number_format($packagePrice, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center text-slate-600">
                    <span class="font-semibold text-slate-500">PPN (11%)</span>
                    <span class="font-extrabold text-slate-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-sm font-extrabold text-slate-800">Total Pembayaran</span>
                    <span class="text-xl font-black text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Auto Renewal Info Note -->
            <div class="p-3.5 bg-slate-50 rounded-2xl text-[11px] text-slate-500 leading-relaxed flex items-start gap-2 border border-slate-100">
                <span class="text-slate-400 font-bold text-sm leading-none">ⓘ</span>
                <span>Langganan Anda akan diperpanjang secara otomatis setiap bulan.</span>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5 pt-2">
                <button type="button" onclick="document.getElementById('payment-form').submit()" 
                        class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wide shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>Selesaikan Pendaftaran</span>
                </button>

                <a href="{{ route('onboarding.step2') }}" 
                   class="block w-full py-3.5 px-6 rounded-2xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition">
                    Kembali
                </a>
            </div>

            <!-- Security Footer -->
            <div class="text-center pt-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center justify-center gap-1.5">
                    <span>🔒</span>
                    <span>ENKRIPSI AMAN</span>
                </span>
            </div>
        </div>

    </main>

    <!-- Footer Space -->
    <footer class="w-full text-center py-4"></footer>

    <script>
        // Highlight radio card on click
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('label.group').forEach(l => {
                    l.className = 'group relative flex items-center justify-between p-5 bg-white border-2 border-slate-200 hover:border-[#F5BD23] rounded-2xl cursor-pointer shadow-sm transition-all';
                    l.querySelector('.rounded-full.border-2').className = 'w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center p-0.5';
                    l.querySelector('.rounded-full.border-2').innerHTML = '';
                });

                const parent = this.closest('label');
                parent.className = 'group relative flex items-center justify-between p-5 bg-white border-2 border-[#F5BD23] rounded-2xl cursor-pointer shadow-sm transition-all';
                const circle = parent.querySelector('.rounded-full.border-2');
                circle.className = 'w-6 h-6 rounded-full border-2 border-[#F5BD23] flex items-center justify-center p-0.5';
                circle.innerHTML = '<div class="w-3.5 h-3.5 rounded-full bg-[#F5BD23]"></div>';
            });
        });
    </script>
</body>
</html>

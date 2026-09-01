<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Jumlah Cetakan - Potret Diri</title>
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

    <!-- Top Spacer / Header -->
    <header class="w-full max-w-5xl flex items-center justify-between py-2"></header>

    <!-- Main Container -->
    <main class="w-full max-w-5xl my-auto text-center space-y-8">
        
        <!-- Heading & Subtitle (Exact Match) -->
        <div class="space-y-2 max-w-2xl mx-auto">
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Berapa banyak yang ingin Anda cetak?</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Setiap cetakan berkualitas premium. Harga akan otomatis menyesuaikan untuk pembayaran QRIS.
            </p>
        </div>

        <form id="copies-form" action="{{ route('booth.start.postCopies') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="copies" id="selected-copies" value="{{ $selectedCopies }}">

            <!-- 4 Option Cards (Exact Match to Image 2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto">
                
                <!-- Option 1: 1 Lembar -->
                <div onclick="selectCopyOption('1', 20000)" id="card-copy-1" 
                     class="copy-card bg-white rounded-3xl p-7 shadow-sm border-2 {{ $selectedCopies == '1' ? 'border-slate-900' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col items-center justify-center space-y-3 min-h-[160px]">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-700">
                        📄
                    </div>
                    <div>
                        <h3 class="font-black text-base text-slate-900">1 Lembar</h3>
                        <p class="text-xs text-slate-400">Rp 20.000 / unit</p>
                    </div>
                </div>

                <!-- Option 2: 2 Lembar -->
                <div onclick="selectCopyOption('2', 40000)" id="card-copy-2" 
                     class="copy-card bg-white rounded-3xl p-7 shadow-sm border-2 {{ $selectedCopies == '2' ? 'border-slate-900' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col items-center justify-center space-y-3 min-h-[160px]">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-700">
                        📑
                    </div>
                    <div>
                        <h3 class="font-black text-base text-slate-900">2 Lembar</h3>
                        <p class="text-xs text-slate-400">Rp 40.000 / unit</p>
                    </div>
                </div>

                <!-- Option 3: 3 Lembar -->
                <div onclick="selectCopyOption('3', 60000)" id="card-copy-3" 
                     class="copy-card bg-white rounded-3xl p-7 shadow-sm border-2 {{ $selectedCopies == '3' ? 'border-slate-900' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col items-center justify-center space-y-3 min-h-[160px]">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-700">
                        ⧉
                    </div>
                    <div>
                        <h3 class="font-black text-base text-slate-900">3 Lembar</h3>
                        <p class="text-xs text-slate-400">Rp 60.000 / unit</p>
                    </div>
                </div>

                <!-- Option 4: Hanya Digital -->
                <div onclick="selectCopyOption('digital', 25000)" id="card-copy-digital" 
                     class="copy-card bg-white rounded-3xl p-7 shadow-sm border-2 {{ $selectedCopies == 'digital' ? 'border-slate-900' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col items-center justify-center space-y-3 min-h-[160px]">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                        ☁
                    </div>
                    <div>
                        <h3 class="font-black text-base text-slate-900">Hanya Digital</h3>
                        <p class="text-xs text-slate-400">Rp 25.000 (Semua File)</p>
                    </div>
                </div>

            </div>

            <!-- Dynamic Payment Summary Pill (Exact Match to Image 2) -->
            <div class="max-w-md mx-auto bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex items-center justify-between text-left">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                        💵
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">TOTAL PEMBAYARAN</span>
                        <span id="display-price" class="text-xl font-black text-slate-900">Rp 20.000</span>
                    </div>
                </div>

                <div class="text-right space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 block">Metode: QRIS Dinamis</span>
                    <div class="flex items-center gap-1.5">
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[9px]">GOPAY</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[9px]">OVO</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[9px]">DANA</span>
                    </div>
                </div>
            </div>

            <!-- Bottom CTA Button (Exact Match) -->
            <div class="pt-4 flex items-center justify-center gap-3">
                <a href="{{ route('booth.start.template') }}" class="py-4 px-8 rounded-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-sm transition">
                    Kembali
                </a>
                <button type="submit" 
                        class="py-4 px-12 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-sm tracking-wide shadow-xl shadow-amber-500/30 transition-all">
                    Lanjut ke Pembayaran →
                </button>
            </div>
        </form>

    </main>

    <!-- Footer Space -->
    <footer class="w-full text-center py-2"></footer>

    <script>
        const prices = {
            '1': 20000,
            '2': 40000,
            '3': 60000,
            'digital': 25000
        };

        function selectCopyOption(key, amount) {
            document.getElementById('selected-copies').value = key;
            document.querySelectorAll('.copy-card').forEach(card => {
                card.className = 'copy-card bg-white rounded-3xl p-7 shadow-sm border-2 border-transparent hover:border-slate-300 cursor-pointer transition-all flex flex-col items-center justify-center space-y-3 min-h-[160px]';
            });

            const activeCard = document.getElementById('card-copy-' + key);
            if (activeCard) {
                activeCard.className = 'copy-card bg-white rounded-3xl p-7 shadow-sm border-2 border-slate-900 cursor-pointer transition-all flex flex-col items-center justify-center space-y-3 min-h-[160px]';
            }

            document.getElementById('display-price').innerText = 'Rp ' + amount.toLocaleString('id-ID');
        }

        // Initialize price on load
        window.addEventListener('DOMContentLoaded', () => {
            const initialKey = document.getElementById('selected-copies').value || '1';
            selectCopyOption(initialKey, prices[initialKey] || 20000);
        });
    </script>
</body>
</html>

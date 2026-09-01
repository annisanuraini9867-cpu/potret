<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Studio - Potret Diri</title>
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

    <!-- Step Progress Bar (Step 2 Active) -->
    <div class="w-full max-w-xl my-4">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-300 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1/2 h-0.5 bg-[#F5BD23] z-0"></div>

            <!-- Step 1 (Completed) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <a href="{{ route('register') }}" class="w-10 h-10 rounded-full bg-[#F5BD23] text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                    ✓
                </a>
                <span class="text-xs font-bold text-slate-800">Profil</span>
            </div>

            <!-- Step 2 (Active) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full bg-[#403B1E] text-[#F5BD23] border-2 border-[#F5BD23] font-black text-sm flex items-center justify-center shadow-md">
                    2
                </div>
                <span class="text-xs font-bold text-slate-900">Studio</span>
            </div>

            <!-- Step 3 (Pending) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-300 text-slate-500 font-bold text-sm flex items-center justify-center">
                    3
                </div>
                <span class="text-xs font-semibold text-slate-400">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Main Card Container -->
    <main class="w-full max-w-xl bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-slate-100 my-auto">
        <div class="space-y-2 mb-8">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Informasi Studio</h2>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Lengkapi detail studio Anda untuk mulai dikurasi di platform Potret Diri.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-2xl">
                <ul class="list-disc pl-4 space-y-1 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('onboarding.postStep2') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Studio -->
            <div class="space-y-2">
                <label for="studio_name" class="block text-xs font-bold text-slate-700">Nama Studio</label>
                <input type="text" id="studio_name" name="studio_name" 
                       value="{{ old('studio_name', $saved['studio_name'] ?? '') }}" 
                       placeholder="Contoh: Studio Cahaya Abadi" 
                       required autofocus 
                       class="w-full px-4 py-3.5 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition">
            </div>

            <!-- Alamat Lengkap -->
            <div class="space-y-2">
                <label for="studio_address" class="block text-xs font-bold text-slate-700">Alamat Lengkap</label>
                <input type="text" id="studio_address" name="studio_address" 
                       value="{{ old('studio_address', $saved['studio_address'] ?? '') }}" 
                       placeholder="Jl. Senopati No. 123, Kebayoran Baru" 
                       required 
                       class="w-full px-4 py-3.5 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition">
            </div>

            <!-- Kota -->
            <div class="space-y-2">
                <label for="studio_city" class="block text-xs font-bold text-slate-700">Kota</label>
                <input type="text" id="studio_city" name="studio_city" 
                       value="{{ old('studio_city', $saved['studio_city'] ?? '') }}" 
                       placeholder="Jakarta Selatan" 
                       required 
                       class="w-full px-4 py-3.5 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition">
            </div>

            <!-- Tipe Booth -->
            <div class="space-y-2">
                <label for="booth_type" class="block text-xs font-bold text-slate-700">Tipe Booth</label>
                <div class="relative">
                    <select id="booth_type" name="booth_type" required 
                            class="w-full px-4 py-3.5 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition appearance-none cursor-pointer">
                        <option value="" disabled {{ empty($saved['booth_type']) ? 'selected' : '' }}>Pilih tipe booth</option>
                        <option value="Self-Photo Studio (Box Room)" {{ ($saved['booth_type'] ?? '') == 'Self-Photo Studio (Box Room)' ? 'selected' : '' }}>Self-Photo Studio (Box Room)</option>
                        <option value="Photo Booth Kiosk (Touchscreen Event)" {{ ($saved['booth_type'] ?? '') == 'Photo Booth Kiosk (Touchscreen Event)' ? 'selected' : '' }}>Photo Booth Kiosk (Touchscreen Event)</option>
                        <option value="Glamour & Portrait Studio" {{ ($saved['booth_type'] ?? '') == 'Glamour & Portrait Studio' ? 'selected' : '' }}>Glamour & Portrait Studio</option>
                        <option value="360 Spin Video Booth" {{ ($saved['booth_type'] ?? '') == '360 Spin Video Booth' ? 'selected' : '' }}>360 Spin Video Booth</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Buttons Row -->
            <div class="pt-4 flex items-center justify-center gap-3">
                <a href="{{ route('register') }}" 
                   class="py-3.5 px-8 rounded-2xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-sm transition">
                    Kembali
                </a>
                <button type="submit" 
                        class="py-3.5 px-8 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wide shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>Lanjut ke Pembayaran</span>
                    <span>→</span>
                </button>
            </div>
        </form>
    </main>

    <!-- Footer Space -->
    <footer class="w-full text-center py-4"></footer>

</body>
</html>

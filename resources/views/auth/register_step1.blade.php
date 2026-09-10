<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account - POTRET</title>
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
    <header class="w-full max-w-4xl flex items-center justify-start py-2">
        <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
            <h1 class="logo-text text-3xl sm:text-4xl font-black tracking-wide select-none">
                POTRET
            </h1>
        </a>
    </header>

    <!-- Step Progress Bar (Step 1 Active) -->
    <div class="w-full max-w-xl my-3">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-300 z-0"></div>
            
            <!-- Step 1 (Active) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full bg-[#F5BD23] text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                    1
                </div>
                <span class="text-xs font-bold text-slate-800">Paket & Akun</span>
            </div>

            <!-- Step 2 -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-300 text-slate-500 font-bold text-sm flex items-center justify-center">
                    2
                </div>
                <span class="text-xs font-semibold text-slate-400">Studio</span>
            </div>

            <!-- Step 3 -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-300 text-slate-500 font-bold text-sm flex items-center justify-center">
                    3
                </div>
                <span class="text-xs font-semibold text-slate-400">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Main Card Container -->
    <main class="w-full max-w-3xl bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-100 my-auto">
        <div class="space-y-1 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Create Admin Account</h2>
                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-black">
                    Langkah 1 dari 3
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Welcome to POTRET. Pilih paket premium booth Anda dan lengkapi akun studio untuk memulai.
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

        <form action="{{ route('onboarding.postStep1') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. PILIH PAKET PREMIUM SECTION -->
            <div class="space-y-3 p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-wider text-amber-700 block">LANGKAH A: PILIH PAKET PREMIUM</span>
                        <h3 class="text-sm font-black text-slate-900">Pilih Paket Berlangganan Software</h3>
                    </div>

                    <!-- Billing Toggle -->
                    <div class="inline-flex items-center gap-1.5 p-1 bg-slate-200 rounded-xl self-start sm:self-auto">
                        <button type="button" id="btn-toggle-monthly" onclick="selectCycle('monthly')"
                                class="px-3 py-1 rounded-lg text-xs font-black transition-all bg-white text-slate-900 shadow-sm">
                            Bulanan
                        </button>
                        <button type="button" id="btn-toggle-yearly" onclick="selectCycle('yearly')"
                                class="px-3 py-1 rounded-lg text-xs font-black transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1">
                            <span>Tahunan</span>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-500 text-white text-[9px] font-black">-25%</span>
                        </button>
                    </div>
                </div>

                <!-- Hidden Inputs to carry plan and billing cycle -->
                <input type="hidden" name="plan" id="input-plan" value="{{ $selectedPlan ?? 'pro' }}">
                <input type="hidden" name="billing" id="input-billing" value="{{ $selectedBilling ?? 'monthly' }}">

                <!-- 3 Interactive Plan Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-1">
                    
                    <!-- Card 1: Starter -->
                    <div id="card-plan-starter" onclick="selectPlan('starter')"
                         class="plan-card relative p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white flex flex-col justify-between {{ ($selectedPlan ?? 'pro') === 'starter' ? 'border-[#F5BD23] ring-2 ring-amber-300 shadow-md' : 'border-slate-200 hover:border-slate-300' }}">
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-black text-slate-900">Starter Booth</h4>
                                <div id="check-starter" class="w-4 h-4 rounded-full border-2 flex items-center justify-center {{ ($selectedPlan ?? 'pro') === 'starter' ? 'border-[#F5BD23] bg-[#F5BD23]' : 'border-slate-300' }}">
                                    <span class="text-[10px] font-black text-slate-950 {{ ($selectedPlan ?? 'pro') === 'starter' ? 'block' : 'hidden' }}">✓</span>
                                </div>
                            </div>

                            <div>
                                <p class="plan-price-monthly text-base font-black text-slate-900">Rp 149.000<span class="text-[10px] text-slate-400 font-normal">/bln</span></p>
                                <p class="plan-price-yearly hidden text-base font-black text-slate-900">Rp 111.750<span class="text-[10px] text-slate-400 font-normal">/bln</span></p>
                                <p class="text-[10px] text-slate-400 font-semibold">1 Kiosk Booth</p>
                            </div>

                            <ul class="text-[11px] text-slate-600 space-y-1 font-medium pt-1 border-t border-slate-100">
                                <li>✓ 10 Template Dasar</li>
                                <li>✓ Cloud 30 Hari</li>
                                <li>✓ Remote Shutter</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card 2: Studio Pro (Default) -->
                    <div id="card-plan-pro" onclick="selectPlan('pro')"
                         class="plan-card relative p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white flex flex-col justify-between {{ ($selectedPlan ?? 'pro') === 'pro' ? 'border-[#F5BD23] ring-2 ring-amber-300 shadow-md' : 'border-slate-200 hover:border-slate-300' }}">
                        <span class="absolute -top-2.5 right-3 px-2 py-0.5 rounded-full bg-[#F5BD23] text-slate-950 text-[9px] font-black uppercase tracking-wider">
                            Paling Populer
                        </span>

                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-black text-slate-900">Studio Pro</h4>
                                <div id="check-pro" class="w-4 h-4 rounded-full border-2 flex items-center justify-center {{ ($selectedPlan ?? 'pro') === 'pro' ? 'border-[#F5BD23] bg-[#F5BD23]' : 'border-slate-300' }}">
                                    <span class="text-[10px] font-black text-slate-950 {{ ($selectedPlan ?? 'pro') === 'pro' ? 'block' : 'hidden' }}">✓</span>
                                </div>
                            </div>

                            <div>
                                <p class="plan-price-monthly text-base font-black text-[#1D4ED8]">Rp 250.000<span class="text-[10px] text-slate-400 font-normal">/bln</span></p>
                                <p class="plan-price-yearly hidden text-base font-black text-[#1D4ED8]">Rp 187.500<span class="text-[10px] text-slate-400 font-normal">/bln</span></p>
                                <p class="text-[10px] text-emerald-600 font-bold">Unlimited Sesi</p>
                            </div>

                            <ul class="text-[11px] text-slate-600 space-y-1 font-medium pt-1 border-t border-slate-100">
                                <li>✓ <strong>QRIS Otomatis</strong></li>
                                <li>✓ 50+ Frame Viral</li>
                                <li>✓ White-label Studio</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card 3: Business Multi-Booth -->
                    <div id="card-plan-business" onclick="selectPlan('business')"
                         class="plan-card relative p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white flex flex-col justify-between {{ ($selectedPlan ?? 'pro') === 'business' ? 'border-[#F5BD23] ring-2 ring-amber-300 shadow-md' : 'border-slate-200 hover:border-slate-300' }}">
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-black text-slate-900">Business Multi</h4>
                                <div id="check-business" class="w-4 h-4 rounded-full border-2 flex items-center justify-center {{ ($selectedPlan ?? 'pro') === 'business' ? 'border-[#F5BD23] bg-[#F5BD23]' : 'border-slate-300' }}">
                                    <span class="text-[10px] font-black text-slate-950 {{ ($selectedPlan ?? 'pro') === 'business' ? 'block' : 'hidden' }}">✓</span>
                                </div>
                            </div>

                            <div>
                                <p class="plan-price-monthly text-base font-black text-slate-900">Rp 499.000<span class="text-[10px] text-slate-400 font-normal">/bln</span></p>
                                <p class="plan-price-yearly hidden text-base font-black text-slate-900">Rp 374.250<span class="text-[10px] text-slate-400 font-normal">/bln</span></p>
                                <p class="text-[10px] text-slate-400 font-semibold">Hingga 5 Kiosk</p>
                            </div>

                            <ul class="text-[11px] text-slate-600 space-y-1 font-medium pt-1 border-t border-slate-100">
                                <li>✓ Multi-Cabang</li>
                                <li>✓ Custom Frame Builder</li>
                                <li>✓ Cloud Selamanya</li>
                            </ul>
                        </div>
                    </div>

                </div>

                <!-- Info pill -->
                <div class="flex items-center gap-2 pt-1 text-[11px] text-slate-500 font-medium">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <span id="plan-selected-summary">
                        Paket terpilih: <strong class="text-slate-900 font-black" id="plan-name-label">{{ ($selectedPlan ?? 'pro') === 'starter' ? 'Starter Booth' : (($selectedPlan ?? 'pro') === 'business' ? 'Business Multi-Booth' : 'Studio Pro (Paling Populer)') }}</strong>
                    </span>
                </div>
            </div>

            <!-- 2. DATA AKUN ADMIN SECTION -->
            <div class="space-y-4 pt-1">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block">LANGKAH B: DATA PROFIL ADMIN</span>

                <!-- Full Name -->
                <!-- Full Name (Wajib Huruf) -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold text-slate-700">Full Name</label>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', $saved['name'] ?? '') }}" 
                           placeholder="John Doe" 
                           required autofocus 
                           pattern="[a-zA-Z\s\.\']+"
                           title="Nama lengkap hanya boleh berisi huruf dan spasi"
                           oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\']/g, '')"
                           class="w-full px-4 py-3 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition">
                    <p class="text-[10px] text-slate-400 font-medium">Hanya boleh berisi karakter huruf dan spasi.</p>
                </div>

                <!-- Work Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-slate-700">Work Email</label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email', $saved['email'] ?? '') }}" 
                           placeholder="john@studio.com" 
                           required 
                           class="w-full px-4 py-3 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition">
                </div>

                <!-- Password (Dengan Label Bisa Lihat Password) -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                        <label for="toggle-show-password" class="inline-flex items-center gap-1.5 text-xs text-slate-600 font-bold cursor-pointer select-none hover:text-slate-900 transition">
                            <input type="checkbox" id="toggle-show-password" onchange="togglePasswordVisibility()" class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-400 cursor-pointer">
                            <span>Lihat Password</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" 
                               value="{{ old('password', $saved['password'] ?? '') }}" 
                               placeholder="••••••••" 
                               required 
                               class="w-full px-4 py-3 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition font-medium pr-12">
                        <button type="button" onclick="togglePasswordButton()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700" title="Lihat/Sembunyikan Password">
                            <svg id="eye-icon-open" class="w-5 h-5 hidden text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eye-icon-closed" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wide shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>Continue to Studio Info</span>
                    <span>→</span>
                </button>
            </div>
        </form>
    </main>

    <!-- Footer Switch to Sign In -->
    <footer class="w-full text-center py-6 text-xs text-slate-600 font-medium select-none">
        Already have an account? <a href="{{ route('login') }}" class="font-bold text-slate-900 underline hover:text-[#1D4ED8]">Sign In</a>
    </footer>

    <script>
        let currentPlan = "{{ $selectedPlan ?? 'pro' }}";
        let currentCycle = "{{ $selectedBilling ?? 'monthly' }}";

        const planNames = {
            starter: 'Starter Booth',
            pro: 'Studio Pro (Paling Populer)',
            business: 'Business Multi-Booth'
        };

        function selectPlan(planKey) {
            currentPlan = planKey;
            document.getElementById('input-plan').value = planKey;
            document.getElementById('plan-name-label').innerText = planNames[planKey];

            ['starter', 'pro', 'business'].forEach(key => {
                const card = document.getElementById(`card-plan-${key}`);
                const check = document.getElementById(`check-${key}`);
                const checkIcon = check.querySelector('span');

                if (key === planKey) {
                    card.className = 'plan-card relative p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white flex flex-col justify-between border-[#F5BD23] ring-2 ring-amber-300 shadow-md';
                    check.className = 'w-4 h-4 rounded-full border-2 border-[#F5BD23] bg-[#F5BD23] flex items-center justify-center';
                    checkIcon.classList.remove('hidden');
                } else {
                    card.className = 'plan-card relative p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white flex flex-col justify-between border-slate-200 hover:border-slate-300';
                    check.className = 'w-4 h-4 rounded-full border-2 border-slate-300 flex items-center justify-center';
                    checkIcon.classList.add('hidden');
                }
            });
        }

        function selectCycle(cycle) {
            currentCycle = cycle;
            document.getElementById('input-billing').value = cycle;

            const btnMonthly = document.getElementById('btn-toggle-monthly');
            const btnYearly = document.getElementById('btn-toggle-yearly');
            const monthlyPrices = document.querySelectorAll('.plan-price-monthly');
            const yearlyPrices = document.querySelectorAll('.plan-price-yearly');

            if (cycle === 'yearly') {
                btnYearly.className = 'px-3 py-1 rounded-lg text-xs font-black transition-all bg-white text-slate-900 shadow-sm flex items-center gap-1';
                btnMonthly.className = 'px-3 py-1 rounded-lg text-xs font-black transition-all text-slate-600 hover:text-slate-900';
                monthlyPrices.forEach(el => el.classList.add('hidden'));
                yearlyPrices.forEach(el => el.classList.remove('hidden'));
            } else {
                btnMonthly.className = 'px-3 py-1 rounded-lg text-xs font-black transition-all bg-white text-slate-900 shadow-sm';
                btnYearly.className = 'px-3 py-1 rounded-lg text-xs font-black transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1';
                yearlyPrices.forEach(el => el.classList.add('hidden'));
                monthlyPrices.forEach(el => el.classList.remove('hidden'));
            }
        }

        function togglePasswordVisibility() {
            const pwd = document.getElementById('password');
            const chk = document.getElementById('toggle-show-password');
            const eyeOpen = document.getElementById('eye-icon-open');
            const eyeClosed = document.getElementById('eye-icon-closed');
            if (chk.checked) {
                pwd.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                pwd.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }

        function togglePasswordButton() {
            const chk = document.getElementById('toggle-show-password');
            chk.checked = !chk.checked;
            togglePasswordVisibility();
        }

        document.addEventListener('DOMContentLoaded', () => {
            selectPlan(currentPlan);
            selectCycle(currentCycle);
        });
    </script>
</body>
</html>


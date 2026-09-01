<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - PotretDiri</title>
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
            -webkit-text-stroke: 1.5px #1E293B;
            text-shadow: 2px 3px 0px #0F172A;
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between items-center py-10 px-4 antialiased selection:bg-[#F5BD23] selection:text-slate-900">

    <!-- Top Logo Title -->
    <div class="w-full text-center pt-2 pb-6">
        <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
            <h1 class="logo-text text-5xl sm:text-6xl font-black tracking-wide select-none">
                PotretDiri
            </h1>
        </a>
    </div>

    <!-- Login Card Container -->
    <div class="w-full max-w-lg bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-slate-100">
        
        <!-- Alerts if any -->
        @if(session('error'))
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold rounded-xl flex items-center gap-2">
                <span>⚠</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-bold text-slate-700">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email', 'admin@potretdiri.id') }}" 
                           placeholder="admin@potretdiri.id" 
                           required autofocus 
                           class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#F5BD23] focus:border-transparent transition">
                </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label for="password" class="text-xs font-bold text-slate-700">Password</label>
                    <a href="javascript:alert('Silakan hubungi tim sistem untuk reset password.')" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        Lupa Kata Sandi?
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <input type="password" id="password" name="password" 
                           value="password123" 
                           placeholder="••••••••" 
                           required 
                           class="w-full pl-11 pr-11 py-3 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#F5BD23] focus:border-transparent transition font-medium">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember me checkbox -->
            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" name="remember" checked class="w-4 h-4 rounded border-slate-300 text-[#F5BD23] focus:ring-[#F5BD23] focus:ring-offset-0">
                    <span class="text-xs text-slate-700 font-medium">Ingat saya untuk sesi ini</span>
                </label>
            </div>

            <!-- Main SIGN IN Button -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wider uppercase shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>SIGN IN</span>
                    <span>→</span>
                </button>
            </div>

            <!-- OR Divider -->
            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-[11px] font-bold text-slate-400 tracking-widest uppercase">ATAU</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Sign in with Google Button -->
            <div>
                <button type="button" onclick="alert('Autentikasi Google OAuth dapat dihubungkan melalui file .env (GOOGLE_CLIENT_ID).')" 
                        class="w-full py-3.5 px-4 bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 flex items-center justify-center gap-2.5 shadow-sm transition">
                    <img src="{{ asset('images/google-icon.svg') }}" alt="Google" class="w-4 h-4">
                    <span>Masuk dengan Google</span>
                </button>
            </div>

            <!-- Link to Register New Studio Account -->
            <div class="text-center pt-2">
                <a href="{{ route('register') }}" class="text-xs font-bold text-slate-700 hover:text-[#1D4ED8] transition">
                    Belum punya akun admin? <span class="text-[#1D4ED8] underline">Daftar Akun Studio Baru →</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Footer Copyright Note (Exact match) -->
    <div class="w-full text-center pt-8 text-xs text-slate-500 font-medium select-none">
        &copy; {{ date('Y') }} PotretDiri BY. Caboo.
    </div>

    <script>
        function togglePasswordVisibility() {
            const pwd = document.getElementById('password');
            if (pwd.type === 'password') {
                pwd.type = 'text';
            } else {
                pwd.type = 'password';
            }
        }
    </script>
</body>
</html>

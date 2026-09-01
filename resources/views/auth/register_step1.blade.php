<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account - Potret Diri</title>
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

    <!-- Step Progress Bar (Step 1 Active) -->
    <div class="w-full max-w-xl my-4">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-300 z-0"></div>
            
            <!-- Step 1 (Active) -->
            <div class="relative z-10 flex flex-col items-center gap-1.5">
                <div class="w-10 h-10 rounded-full bg-[#F5BD23] text-slate-950 font-black text-sm flex items-center justify-center shadow-md">
                    1
                </div>
                <span class="text-xs font-bold text-slate-800">Account</span>
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
                <span class="text-xs font-semibold text-slate-400">Payment</span>
            </div>
        </div>
    </div>

    <!-- Main Card Container -->
    <main class="w-full max-w-xl bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-slate-100 my-auto">
        <div class="space-y-2 mb-8">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Create Admin Account</h2>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Welcome to Potret Diri. Let's get your professional studio profile started.
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

            <!-- Full Name -->
            <div class="space-y-2">
                <label for="name" class="block text-xs font-bold text-slate-700">Full Name</label>
                <input type="text" id="name" name="name" 
                       value="{{ old('name', $saved['name'] ?? '') }}" 
                       placeholder="John Doe" 
                       required autofocus 
                       class="w-full px-4 py-3.5 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition">
            </div>

            <!-- Work Email -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-bold text-slate-700">Work Email</label>
                <input type="email" id="email" name="email" 
                       value="{{ old('email', $saved['email'] ?? '') }}" 
                       placeholder="john@studio.com" 
                       required 
                       class="w-full px-4 py-3.5 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition">
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                <input type="password" id="password" name="password" 
                       value="{{ old('password', $saved['password'] ?? '') }}" 
                       placeholder="••••••••" 
                       required 
                       class="w-full px-4 py-3.5 bg-slate-100/90 border border-transparent rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none transition font-medium">
            </div>

            <!-- CTA Button -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wide shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>Continue to Studio Info</span>
                </button>
            </div>
        </form>
    </main>

    <!-- Footer Switch to Sign In -->
    <footer class="w-full text-center py-6 text-xs text-slate-600 font-medium select-none">
        Already have an account? <a href="{{ route('login') }}" class="font-bold text-slate-900 underline hover:text-[#1D4ED8]">Sign In</a>
    </footer>

</body>
</html>

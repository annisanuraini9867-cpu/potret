<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8C435]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Potret Diri - Self-Photo Booth App</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800;900&family=Fredoka:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8C435;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Splash Entrance Animation */
        @keyframes splash-pop {
            0% {
                opacity: 0;
                transform: scale(0.65) translateY(20px);
            }
            60% {
                opacity: 1;
                transform: scale(1.05) translateY(-5px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Floating Gentle Shimmer */
        @keyframes gentle-float {
            0%, 100% {
                transform: translateY(0px) scale(1);
            }
            50% {
                transform: translateY(-8px) scale(1.02);
            }
        }

        /* Screen Fade Out Transition to Login */
        @keyframes splash-exit {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(1.08);
            }
        }

        .animate-splash-pop {
            animation: splash-pop 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s forwards;
            opacity: 0;
        }

        .animate-float {
            animation: gentle-float 3s ease-in-out infinite;
        }

        .splash-exit-active {
            animation: splash-exit 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
    </style>
</head>
<body onclick="navigateToLogin()" class="h-full w-full flex flex-col items-center justify-center p-6 overflow-hidden cursor-pointer selection:bg-transparent">

    <div id="splash-container" class="relative flex flex-col items-center justify-center w-full max-w-2xl mx-auto text-center transition-all px-4">
        
        <!-- Logo Image with Entrance & Floating Animation (Zoomed In Size) -->
        <div class="w-full max-w-[500px] sm:max-w-[620px] md:max-w-[720px] aspect-[4/3] flex items-center justify-center animate-splash-pop">
            <div class="animate-float w-full flex items-center justify-center">
                <img src="{{ asset('images/logo-clean.png') }}" 
                     alt="Potret Diri" 
                     class="w-full h-auto object-contain drop-shadow-[0_16px_32px_rgba(0,0,0,0.22)] scale-110">
            </div>
        </div>

        <!-- Subtle Loading Indicator & Tagline -->
        <div class="mt-8 space-y-3 animate-splash-pop" style="animation-delay: 0.4s;">
            
            <!-- Sleek Minimalist Progress Bar -->
            <div class="w-44 h-1.5 bg-black/10 rounded-full mx-auto overflow-hidden p-0.5">
                <div id="progress-bar" class="h-full bg-slate-900 rounded-full w-0 transition-all duration-[1800ms] ease-out"></div>
            </div>

            <div class="flex items-center justify-center gap-2 text-slate-900/80 text-[11px] font-black tracking-widest uppercase">
                <span class="w-2 h-2 rounded-full bg-slate-900 animate-ping"></span>
                <span>MEMUAT STUDIO...</span>
            </div>
        </div>

        <!-- Quick Tap to Skip Hint -->
        <div class="absolute -bottom-24 text-[11px] text-slate-800/60 font-semibold">
            Sentuh layar untuk langsung masuk
        </div>

    </div>

    <script>
        const LOGIN_URL = "{{ route('login') }}";
        let isNavigating = false;

        // Auto transition after progress animation
        window.addEventListener('DOMContentLoaded', () => {
            // Animate progress bar
            setTimeout(() => {
                const bar = document.getElementById('progress-bar');
                if (bar) bar.style.width = '100%';
            }, 100);

            // Auto redirect to Login after 2.3 seconds
            setTimeout(() => {
                navigateToLogin();
            }, 2300);
        });

        // Click / Touch or Keyboard trigger to navigate immediately
        function navigateToLogin() {
            if (isNavigating) return;
            isNavigating = true;

            const container = document.getElementById('splash-container');
            if (container) {
                container.classList.add('splash-exit-active');
            }

            setTimeout(() => {
                window.location.href = LOGIN_URL;
            }, 350);
        }

        // Support Space / Enter key
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                navigateToLogin();
            }
        });
    </script>
</body>
</html>

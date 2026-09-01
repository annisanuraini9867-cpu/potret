<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pilih Template Foto - Potret Diri</title>
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
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Pilih Template Foto</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Tentukan tata letak yang paling sesuai dengan momen Anda hari ini. Setiap template dirancang secara proporsional untuk hasil cetak premium.
            </p>
        </div>

        <form id="template-form" action="{{ route('booth.start.postTemplate') }}" method="POST">
            @csrf
            <input type="hidden" name="template_id" id="selected-template-id" value="{{ $selectedTemplate }}">

            <!-- 4 Template Cards Grid (Exact Match to Image 1) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                
                <!-- 1. Classic 4-Grid -->
                <div onclick="selectTemplateCard('classic-4-grid')" id="card-classic-4-grid" 
                     class="template-card bg-white rounded-3xl p-5 shadow-sm border-2 {{ $selectedTemplate == 'classic-4-grid' ? 'border-slate-900 ring-2 ring-slate-900/10' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col justify-between space-y-4 group">
                    <div class="bg-slate-50 rounded-2xl aspect-[3/4] p-4 flex items-center justify-center border border-slate-100">
                        <div class="w-28 bg-white p-2 shadow-md rounded border border-slate-200 grid grid-cols-2 gap-1.5 aspect-[3/4]">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                            <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="font-extrabold text-sm text-slate-900">Classic 4–Grid</h3>
                        <p class="text-xs text-slate-400">4 Frames (Square)</p>
                    </div>
                </div>

                <!-- 2. Cinematic Strip -->
                <div onclick="selectTemplateCard('cinematic-strip')" id="card-cinematic-strip" 
                     class="template-card bg-white rounded-3xl p-5 shadow-sm border-2 {{ $selectedTemplate == 'cinematic-strip' ? 'border-slate-900 ring-2 ring-slate-900/10' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col justify-between space-y-4 group">
                    <div class="bg-slate-50 rounded-2xl aspect-[3/4] p-4 flex items-center justify-center border border-slate-100">
                        <div class="w-full bg-white p-2 shadow-md rounded border border-slate-200 flex flex-col items-center gap-1">
                            <div class="grid grid-cols-3 gap-1 w-full aspect-[3/1]">
                                <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                            </div>
                            <span class="text-[7px] font-mono text-slate-400 uppercase tracking-widest pt-1">PHOTO BOOTH CO.</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="font-extrabold text-sm text-slate-900">Cinematic Strip</h3>
                        <p class="text-xs text-slate-400">3 Frames (Panoramic)</p>
                    </div>
                </div>

                <!-- 3. Polaroid Wide -->
                <div onclick="selectTemplateCard('polaroid-wide')" id="card-polaroid-wide" 
                     class="template-card bg-white rounded-3xl p-5 shadow-sm border-2 {{ $selectedTemplate == 'polaroid-wide' ? 'border-slate-900 ring-2 ring-slate-900/10' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col justify-between space-y-4 group">
                    <div class="bg-slate-50 rounded-2xl aspect-[3/4] p-4 flex items-center justify-center border border-slate-100">
                        <div class="w-32 bg-[#FAF7EE] p-2.5 shadow-md rounded border border-amber-200/60 space-y-2">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80" class="w-full aspect-square object-cover rounded-sm grayscale">
                            <div class="text-center">
                                <span class="text-[8px] font-serif text-stone-600 block italic">Soulful Moments - est. 2026</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="font-extrabold text-sm text-slate-900">Polaroid Wide</h3>
                        <p class="text-xs text-slate-400">1 Frame (Nostalgic)</p>
                    </div>
                </div>

                <!-- 4. Passport Trio -->
                <div onclick="selectTemplateCard('passport-trio')" id="card-passport-trio" 
                     class="template-card bg-white rounded-3xl p-5 shadow-sm border-2 {{ $selectedTemplate == 'passport-trio' ? 'border-slate-900 ring-2 ring-slate-900/10' : 'border-transparent' }} hover:border-slate-300 cursor-pointer transition-all flex flex-col justify-between space-y-4 group">
                    <div class="bg-slate-50 rounded-2xl aspect-[3/4] p-4 flex items-center justify-center border border-slate-100">
                        <div class="w-full bg-white p-2.5 shadow-md rounded border border-slate-200 space-y-1">
                            <div class="grid grid-cols-3 gap-1 w-full aspect-[3/2]">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                                <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80" class="w-full h-full object-cover rounded-sm">
                            </div>
                            <span class="text-[7px] font-mono text-slate-400 uppercase tracking-widest block text-center">STUDIO ATELIER</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="font-extrabold text-sm text-slate-900">Passport Trio</h3>
                        <p class="text-xs text-slate-400">3 Frames (Portrait)</p>
                    </div>
                </div>

            </div>

            <!-- Bottom CTA Button (Exact Match) -->
            <div class="pt-6">
                <button type="submit" 
                        class="py-4 px-12 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-sm tracking-wide shadow-xl shadow-amber-500/30 transition-all">
                    Pilih & Lanjut →
                </button>
            </div>
        </form>

    </main>

    <!-- Footer Space -->
    <footer class="w-full text-center py-2"></footer>

    <script>
        function selectTemplateCard(id) {
            document.getElementById('selected-template-id').value = id;
            document.querySelectorAll('.template-card').forEach(card => {
                card.className = 'template-card bg-white rounded-3xl p-5 shadow-sm border-2 border-transparent hover:border-slate-300 cursor-pointer transition-all flex flex-col justify-between space-y-4 group';
            });

            const activeCard = document.getElementById('card-' + id);
            if (activeCard) {
                activeCard.className = 'template-card bg-white rounded-3xl p-5 shadow-sm border-2 border-slate-900 ring-2 ring-slate-900/10 cursor-pointer transition-all flex flex-col justify-between space-y-4 group';
            }
        }
    </script>
</body>
</html>

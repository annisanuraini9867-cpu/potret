<!DOCTYPE html>
<html lang="id" class="h-full bg-[#0B0F19]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pilih Template & Kolase Foto - Potret Diri</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,700&family=Courier+Prime:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-title { font-family: 'Playfair Display', serif; }
        .font-typewriter { font-family: 'Courier Prime', monospace; }
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(245, 189, 35, 0.3);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(245, 189, 35, 0.6);
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between items-center text-slate-100 antialiased selection:bg-[#F5BD23] selection:text-slate-950 select-none pb-28">

    <!-- Top Header Navigation -->
    <header class="w-full max-w-7xl px-4 sm:px-6 py-5 flex items-center justify-between border-b border-white/10 bg-[#0B0F19]/80 backdrop-blur-md sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <a href="{{ route('booth.index') }}" 
               class="w-10 h-10 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center text-slate-300 hover:text-white transition active:scale-95"
               title="Kembali ke Standby Kiosk">
                ←
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-extrabold text-sm sm:text-base tracking-wide text-white">Potret Diri</span>
                    <span class="text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full bg-[#F5BD23]/20 text-[#F5BD23] border border-[#F5BD23]/30 font-mono">
                        Template Gallery
                    </span>
                </div>
                <p class="text-[11px] text-slate-400">Pilih tata letak kolase foto sesuai keinginan Anda</p>
            </div>
        </div>

        <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-mono">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-slate-400">Total Koleksi:</span>
            <strong class="text-[#F5BD23]">{{ count($templates) }} Desain</strong>
        </div>
    </header>

    <!-- Main Container -->
    <main class="w-full max-w-7xl px-4 sm:px-6 py-8 space-y-8">
        
        <!-- Page Title & Intro -->
        <div class="text-center space-y-3 max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#F5BD23]/10 border border-[#F5BD23]/30 text-[#F5BD23] text-xs font-bold uppercase tracking-wider">
                <span>✨</span>
                <span>Koleksi Kolase Eksklusif</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
                Pilih Template & Jumlah Kolase
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed max-w-2xl mx-auto">
                Tersedia beragam pilihan mulai dari <span class="text-[#F5BD23] font-bold">8 Kolase</span> untuk pesta ramai, <span class="text-pink-400 font-bold">6 Kolase</span> ala studio Korea & retro, <span class="text-blue-400 font-bold">4 Kolase</span> klasik, hingga <span class="text-amber-400 font-bold">3, 2 & Polaroid</span>.
            </p>
        </div>

        <!-- Filter Category Tabs -->
        <div class="flex items-center justify-start sm:justify-center gap-2 overflow-x-auto custom-scrollbar py-2 px-1">
            <button type="button" onclick="filterCategory('all')" id="tab-all"
                    class="filter-tab px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider transition whitespace-nowrap bg-[#F5BD23] text-slate-950 shadow-lg shadow-amber-500/20">
                Semua Desain ({{ count($templates) }})
            </button>
            <button type="button" onclick="filterCategory('8_slots')" id="tab-8_slots"
                    class="filter-tab px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition whitespace-nowrap bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10">
                🎉 8 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) == 8)) }})
            </button>
            <button type="button" onclick="filterCategory('6_slots')" id="tab-6_slots"
                    class="filter-tab px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition whitespace-nowrap bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10">
                🌸 6 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) == 6)) }})
            </button>
            <button type="button" onclick="filterCategory('4_slots')" id="tab-4_slots"
                    class="filter-tab px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition whitespace-nowrap bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10">
                ⭐ 4 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) == 4)) }})
            </button>
            <button type="button" onclick="filterCategory('other_slots')" id="tab-other_slots"
                    class="filter-tab px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition whitespace-nowrap bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10">
                🎬 3, 2 & 1 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) < 4)) }})
            </button>
        </div>

        <!-- Frame Color Palette Picker Bar -->
        <div class="bg-white/5 border border-white/10 rounded-3xl p-4 sm:p-5 space-y-3">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="text-base">🎨</span>
                    <span class="text-xs sm:text-sm font-extrabold text-white">PILIH WARNA FRAME (Background Bingkai)</span>
                    <span class="text-[10px] text-slate-400 font-mono hidden sm:inline">• Bisa disesuaikan lagi saat pratinjau cetak</span>
                </div>
                <div class="flex items-center gap-2 text-xs font-mono text-[#F5BD23]">
                    <span>Warna Pilihan:</span>
                    <strong id="active-color-name" class="font-bold text-white">{{ $colors[0]['name'] ?? 'Bawaan Template' }}</strong>
                </div>
            </div>

            <!-- Color Swatches Row -->
            <div class="flex items-center gap-2.5 overflow-x-auto custom-scrollbar py-1">
                @foreach ($colors as $c)
                @php $isColSelected = (($selectedColor ?? 'original') === $c['id']); @endphp
                <button type="button" 
                        onclick="selectFrameColor('{{ $c['id'] }}', '{{ $c['name'] }}', '{{ $c['bg'] }}', '{{ $c['text'] }}')"
                        id="color-btn-{{ $c['id'] }}"
                        title="{{ $c['name'] }}"
                        class="color-swatch-btn px-3 py-2 rounded-2xl border-2 {{ $isColSelected ? 'border-[#F5BD23] ring-2 ring-[#F5BD23]/40 bg-white/15' : 'border-white/10 bg-white/5 hover:bg-white/10' }} flex items-center gap-2 transition shrink-0 group">
                    <span class="w-4 h-4 rounded-full shadow border border-white/30 shrink-0 flex items-center justify-center text-[9px]"
                          style="background-color: {{ $c['hex'] }};">
                        @if ($c['id'] === 'original') ✦ @endif
                    </span>
                    <span class="text-xs font-bold text-slate-200 group-hover:text-white whitespace-nowrap">{{ $c['name'] }}</span>
                </button>
                @endforeach

                <!-- Custom Color Picker -->
                <label class="px-3 py-2 rounded-2xl border-2 border-white/10 bg-white/5 hover:bg-white/10 flex items-center gap-2 transition shrink-0 cursor-pointer text-xs font-bold text-slate-200 hover:text-white"
                       title="Pilih warna bebas / kustom">
                    <input type="color" id="custom-color-input" onchange="onCustomColorPick(this.value)" class="w-4 h-4 rounded-full border-0 p-0 cursor-pointer bg-transparent">
                    <span class="whitespace-nowrap">Warna Bebas</span>
                </label>
            </div>
        </div>

        <form id="template-form" action="{{ route('booth.start.postTemplate') }}" method="POST">
            @csrf
            <input type="hidden" name="template_id" id="selected-template-id" value="{{ $selectedTemplate }}">
            <input type="hidden" name="frame_color" id="selected-frame-color" value="{{ $selectedColor ?? 'original' }}">

            <!-- Template Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 items-stretch">
                
                @foreach ($templates as $tmpl)
                @php
                    $isSelected = ($selectedTemplate === $tmpl['id']);
                    $slots = $tmpl['slots'] ?? 4;
                    $cat = $tmpl['category'] ?? '4_slots';
                @endphp
                <div onclick="selectTemplateCard('{{ $tmpl['id'] }}')" 
                     id="card-{{ $tmpl['id'] }}"
                     data-category="{{ $cat }}"
                     data-name="{{ $tmpl['name'] }}"
                     data-slots="{{ $slots }}"
                     class="template-card relative bg-[#131926] hover:bg-[#172033] rounded-3xl p-5 shadow-xl border-2 {{ $isSelected ? 'border-[#F5BD23] ring-4 ring-[#F5BD23]/20 bg-[#172033]' : 'border-white/10' }} hover:border-[#F5BD23]/60 cursor-pointer transition-all duration-300 flex flex-col justify-between space-y-4 group">
                    
                    <!-- Top Card Badges -->
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase {{ $tmpl['slots'] == 8 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : ($tmpl['slots'] == 6 ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40' : 'bg-blue-500/20 text-blue-300 border border-blue-500/40') }}">
                            {{ $tmpl['badge'] ?? ($tmpl['slots'] . ' Poses') }}
                        </span>

                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="event.stopPropagation(); openTemplatePreviewModal('{{ $tmpl['id'] }}')" 
                                    class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-[#F5BD23] hover:text-slate-950 text-slate-300 border border-white/15 text-[10px] font-bold flex items-center gap-1 transition shadow-sm"
                                    title="Lihat Desain Frame Lengkap">
                                <span>👁️</span>
                                <span>Lihat Desain</span>
                            </button>
                            <span class="text-[10px] font-mono text-slate-400 hidden sm:inline">
                                {{ $tmpl['aspect'] ?? '1200 x 1800 px' }}
                            </span>
                        </div>
                    </div>

                    <!-- Visual Mini Mockup Container -->
                    <div class="mockup-box rounded-2xl aspect-[3/4] p-3 flex items-center justify-center overflow-hidden border border-white/10 relative transition-all duration-300 group-hover:scale-[1.02]"
                         data-original-bg="{{ $tmpl['bg_color'] ?? '#1E293B' }}"
                         style="background-color: {{ $tmpl['bg_color'] ?? '#1E293B' }};">
                        
                        <!-- Dynamic Mockup Layout Based on ID -->
                        @if ($tmpl['id'] === 'party-8-grid')
                            <!-- 8 Slots 2x4 Grid -->
                            <div class="w-full h-full flex flex-col justify-between p-1">
                                <div class="text-center">
                                    <span class="text-[7px] font-black tracking-widest text-[#F5BD23]">PARTY SQUAD • 8 SHOTS</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5 flex-1 my-1">
                                    @for ($s = 0; $s < 8; $s++)
                                        <div class="bg-slate-800 border border-amber-400/30 rounded overflow-hidden relative flex items-center justify-center">
                                            <span class="text-[9px] font-mono text-amber-300/70">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center">
                                    <span class="text-[6px] font-mono text-slate-400">POTRET DIRI STUDIO</span>
                                </div>
                            </div>

                        @elseif ($tmpl['id'] === 'korean-8-strip')
                            <!-- 8 Slots Twin 4-Strips -->
                            <div class="w-full h-full flex items-stretch justify-center gap-1.5 p-1 bg-pink-50 rounded">
                                <!-- Strip 1 -->
                                <div class="flex-1 bg-white p-1 rounded shadow-sm flex flex-col justify-between border border-pink-200">
                                    <span class="text-[6px] font-bold text-pink-700 text-center block">🌸 LIFE 4 CUTS</span>
                                    <div class="grid grid-rows-4 gap-1 flex-1 my-0.5">
                                        @for ($s = 1; $s <= 4; $s++)
                                            <div class="bg-pink-100 rounded border border-pink-300/50 flex items-center justify-center">
                                                <span class="text-[8px] font-mono text-pink-700">{{ $s }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                    <span class="text-[5px] text-pink-400 text-center block font-mono">POTRET DIRI</span>
                                </div>
                                <!-- Center Perforation Line -->
                                <div class="w-0 border-r border-dashed border-pink-400/60 my-2"></div>
                                <!-- Strip 2 -->
                                <div class="flex-1 bg-white p-1 rounded shadow-sm flex flex-col justify-between border border-pink-200">
                                    <span class="text-[6px] font-bold text-pink-700 text-center block">🌸 LIFE 4 CUTS</span>
                                    <div class="grid grid-rows-4 gap-1 flex-1 my-0.5">
                                        @for ($s = 5; $s <= 8; $s++)
                                            <div class="bg-pink-100 rounded border border-pink-300/50 flex items-center justify-center">
                                                <span class="text-[8px] font-mono text-pink-700">{{ $s }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                    <span class="text-[5px] text-pink-400 text-center block font-mono">POTRET DIRI</span>
                                </div>
                            </div>

                        @elseif ($tmpl['id'] === 'korean-6-grid')
                            <!-- 6 Slots Korean Pastel 2x3 -->
                            <div class="w-full h-full flex flex-col justify-between p-1 bg-rose-50/90 rounded border border-rose-200">
                                <div class="flex items-center justify-between px-1">
                                    <span class="text-[7px] font-bold text-rose-800">포토이즘 • 6 CUTS</span>
                                    <span class="text-[8px] text-rose-500">♡</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5 flex-1 my-1">
                                    @for ($s = 0; $s < 6; $s++)
                                        <div class="bg-white rounded-lg border border-rose-200 shadow-sm flex items-center justify-center">
                                            <span class="text-[10px] font-bold text-rose-600">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center">
                                    <span class="text-[6px] font-mono text-rose-700">KOREAN ATELIER • MEMORIES</span>
                                </div>
                            </div>

                        @elseif ($tmpl['id'] === 'filmstrip-6-retro')
                            <!-- 6 Slots 35mm Analog Filmstrip -->
                            <div class="w-full h-full flex items-stretch p-0.5 bg-zinc-950 rounded border border-zinc-800">
                                <!-- Left Film Sprocket -->
                                <div class="w-2.5 flex flex-col justify-around items-center py-1">
                                    @for ($sp = 0; $sp < 8; $sp++)
                                        <span class="w-1.5 h-1.5 bg-zinc-800 rounded-sm"></span>
                                    @endfor
                                </div>
                                <!-- Center 2x3 Grid -->
                                <div class="flex-1 flex flex-col justify-between px-1 py-1">
                                    <div class="flex justify-between items-center text-[6px] font-mono text-amber-500">
                                        <span>KODAK 400</span>
                                        <span>35MM FILM</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-1 flex-1 my-0.5">
                                        @for ($s = 0; $s < 6; $s++)
                                            <div class="bg-zinc-900 border border-zinc-700 rounded-sm flex items-center justify-center relative">
                                                <span class="text-[9px] font-mono text-zinc-400">{{ $s + 1 }}</span>
                                                <span class="absolute top-0.5 left-0.5 text-[5px] font-mono text-amber-400">#{{ $s + 1 }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="text-center text-[5px] font-mono text-zinc-500">POTRET DIRI ANALOG ARCHIVE</div>
                                </div>
                                <!-- Right Film Sprocket -->
                                <div class="w-2.5 flex flex-col justify-around items-center py-1">
                                    @for ($sp = 0; $sp < 8; $sp++)
                                        <span class="w-1.5 h-1.5 bg-zinc-800 rounded-sm"></span>
                                    @endfor
                                </div>
                            </div>

                        @elseif ($tmpl['id'] === 'vintage-newspaper-6')
                            <!-- 6 Slots Vintage Newspaper -->
                            <div class="w-full h-full flex flex-col justify-between p-1.5 bg-[#F4EBD9] rounded border border-amber-200 text-stone-900">
                                <div class="text-center border-b border-stone-800 pb-0.5">
                                    <span class="text-[8px] font-serif-title font-bold block leading-tight">THE DAILY POTRET</span>
                                    <span class="text-[5px] font-typewriter text-stone-600 block">SPECIAL EDITION • ISSUE NO. 26</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1 flex-1 my-1">
                                    @for ($s = 0; $s < 6; $s++)
                                        <div class="bg-stone-300/80 border border-stone-600/40 rounded-sm flex items-center justify-center">
                                            <span class="text-[9px] font-typewriter text-stone-700">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center border-t border-stone-800 pt-0.5">
                                    <span class="text-[5px] font-typewriter text-stone-600">BREAKING NEWS: UNFORGETTABLE MOMENTS</span>
                                </div>
                            </div>

                        @elseif ($tmpl['id'] === 'dark-elegance-6')
                            <!-- 6 Slots Dark Elegance & Gold -->
                            <div class="w-full h-full flex flex-col justify-between p-1.5 bg-[#0F172A] rounded border border-amber-500/50">
                                <div class="text-center border-b border-amber-500/40 pb-0.5">
                                    <span class="text-[8px] font-serif-title tracking-widest text-[#F5BD23] block">POTRET NOIR</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1 flex-1 my-1">
                                    @for ($s = 0; $s < 6; $s++)
                                        <div class="bg-slate-900 border border-amber-400/40 rounded flex items-center justify-center">
                                            <span class="text-[10px] font-serif-title text-[#F5BD23]">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center border-t border-amber-500/40 pt-0.5">
                                    <span class="text-[6px] tracking-widest text-slate-300 font-mono">PREMIUM EDITION</span>
                                </div>
                            </div>

                        @elseif ($tmpl['id'] === 'studio-minimal-6')
                            <!-- 6 Slots Studio Minimal White -->
                            <div class="w-full h-full flex flex-col justify-between p-1.5 bg-white rounded border border-slate-200 text-slate-900">
                                <div class="text-center">
                                    <span class="text-[7px] font-extrabold tracking-widest text-slate-800 block">STUDIO ATELIER</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1 flex-1 my-1">
                                    @for ($s = 0; $s < 6; $s++)
                                        <div class="bg-slate-100 border border-slate-300 rounded flex items-center justify-center">
                                            <span class="text-[10px] font-extrabold text-slate-700">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center">
                                    <span class="text-[6px] font-mono text-slate-400 block">MINIMALIST PHOTO ARCHIVE</span>
                                </div>
                            </div>

                        @elseif ($tmpl['id'] === 'korean-4-strip')
                            <!-- 4 Slots Korean 1x4 Strip -->
                            <div class="w-28 h-full bg-pink-100/90 rounded p-1.5 flex flex-col justify-between border border-pink-300 shadow-sm mx-auto">
                                <span class="text-[7px] font-bold text-pink-700 text-center block">🌸 LIFE 4 CUTS</span>
                                <div class="grid grid-rows-4 gap-1 flex-1 my-1">
                                    @for ($s = 0; $s < 4; $s++)
                                        <div class="bg-white rounded border border-pink-200 flex items-center justify-center">
                                            <span class="text-[10px] font-mono text-pink-600">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <span class="text-[6px] font-mono text-pink-500 text-center block">POTRET DIRI</span>
                            </div>

                        @elseif ($tmpl['id'] === 'y2k-cyber-4')
                            <!-- 4 Slots Y2K Cyber Hologram -->
                            <div class="w-full h-full flex flex-col justify-between p-1.5 bg-[#050510] rounded border border-cyan-500/60">
                                <div class="flex justify-between items-center text-[7px] font-mono text-cyan-400">
                                    <span>Y2K CYBER</span>
                                    <span>REC ●</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5 flex-1 my-1">
                                    @for ($s = 0; $s < 4; $s++)
                                        <div class="bg-slate-900 border border-purple-500/60 rounded flex items-center justify-center relative">
                                            <span class="text-[11px] font-mono text-cyan-300 font-bold">{{ $s + 1 }}</span>
                                            <span class="absolute bottom-0.5 right-0.5 text-[5px] font-mono text-pink-400">GLITCH</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center text-[6px] font-mono text-purple-400">SYSTEM ONLINE • 2026</div>
                            </div>

                        @elseif ($tmpl['id'] === 'cinematic-strip')
                            <!-- 3 Slots Cinematic Strip (1x3 Panoramic) -->
                            <div class="w-32 h-full bg-zinc-950 rounded p-1.5 flex flex-col justify-between border border-zinc-800 shadow-sm mx-auto">
                                <div class="text-center text-[7px] font-mono text-amber-400">CINEMA ARCHIVE</div>
                                <div class="grid grid-rows-3 gap-1.5 flex-1 my-1">
                                    @for ($s = 0; $s < 3; $s++)
                                        <div class="bg-zinc-900 border border-zinc-700 rounded flex items-center justify-center">
                                            <span class="text-[11px] font-mono text-amber-300">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center text-[6px] font-mono text-zinc-500">DIRECTOR CUT • 35MM</div>
                            </div>

                        @elseif ($tmpl['id'] === 'duo-bestie-2')
                            <!-- 2 Slots Duo Bestie (1x2 Large) -->
                            <div class="w-36 h-full bg-blue-50 rounded p-1.5 flex flex-col justify-between border border-blue-200 shadow-sm mx-auto text-blue-950">
                                <div class="text-center text-[7px] font-extrabold text-blue-900">BEST FRIENDS FOREVER</div>
                                <div class="grid grid-rows-2 gap-1.5 flex-1 my-1">
                                    @for ($s = 0; $s < 2; $s++)
                                        <div class="bg-white rounded-lg border border-blue-200 shadow-sm flex items-center justify-center">
                                            <span class="text-sm font-black text-blue-600">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center text-[6px] font-mono text-blue-500">DUO BESTIE MOMENTS</div>
                            </div>

                        @elseif ($tmpl['id'] === 'polaroid-wide')
                            <!-- 1 Slot Polaroid Nostalgia Wide -->
                            <div class="w-40 h-full bg-[#FAF7EE] rounded-lg p-2 flex flex-col justify-between border border-amber-200/80 shadow-md mx-auto text-stone-900">
                                <div class="w-full aspect-square bg-stone-300 rounded border border-stone-400/50 flex items-center justify-center">
                                    <span class="text-xl font-serif-title text-stone-600">1</span>
                                </div>
                                <div class="text-center pt-2">
                                    <span class="text-[8px] font-serif-title italic text-stone-700 block">Nostalgic Moments</span>
                                    <span class="text-[6px] font-mono text-stone-500">POTRET DIRI • 2026</span>
                                </div>
                            </div>

                        @else
                            <!-- Default 4 Slots 2x2 Grid (Classic 4-Grid) -->
                            <div class="w-full h-full flex flex-col justify-between p-1.5 bg-white rounded border border-slate-200 text-slate-900">
                                <div class="text-center">
                                    <span class="text-[7px] font-extrabold tracking-widest text-slate-800 block">CLASSIC PHOTO BOOTH</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5 flex-1 my-1">
                                    @for ($s = 0; $s < 4; $s++)
                                        <div class="bg-slate-100 border border-slate-300 rounded flex items-center justify-center">
                                            <span class="text-xs font-black text-slate-700">{{ $s + 1 }}</span>
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-center">
                                    <span class="text-[6px] font-mono text-slate-400">STANDARD 4-SHOTS</span>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Card Info -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-black text-sm text-white group-hover:text-[#F5BD23] transition">
                                {{ $tmpl['name'] }}
                            </h3>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-white/5 text-slate-300 font-mono">
                                {{ $slots }} Pose
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed">
                            {{ $tmpl['description'] }}
                        </p>
                    </div>

                    <!-- Selection Status Pill -->
                    <div class="pt-2 border-t border-white/5">
                        <div id="badge-status-{{ $tmpl['id'] }}" 
                             class="py-2 px-3 rounded-xl text-center text-xs font-bold transition flex items-center justify-center gap-1.5 {{ $isSelected ? 'bg-[#F5BD23] text-slate-950 font-black' : 'bg-white/5 text-slate-400 group-hover:text-white' }}">
                            <span>{{ $isSelected ? '✓ Terpilih' : 'Sentuh untuk Memilih' }}</span>
                        </div>
                    </div>

                </div>
                @endforeach

            </div>

            <!-- Sticky Bottom Confirmation Dock -->
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-[#0B0F19]/95 backdrop-blur-xl border-t border-white/10 z-50 flex items-center justify-center">
                <div class="w-full max-w-5xl flex flex-col sm:flex-row items-center justify-between gap-4">
                    
                    <!-- Left Selected Details Indicator -->
                    <div class="flex items-center gap-3 text-left w-full sm:w-auto">
                        <div class="w-10 h-10 rounded-2xl bg-[#F5BD23]/20 border border-[#F5BD23]/40 text-[#F5BD23] flex items-center justify-center text-lg font-black shrink-0">
                            📸
                        </div>
                        <div>
                            <span class="text-[10px] font-mono uppercase text-slate-400 tracking-wider block">Template Terpilih:</span>
                            <div class="flex items-center gap-2 flex-wrap">
                                <strong id="summary-template-name" class="text-sm sm:text-base font-black text-white truncate max-w-[130px] sm:max-w-xs block sm:inline">
                                    {{ $templates[0]['name'] ?? 'Classic 4–Grid' }}
                                </strong>
                                <span id="summary-template-slots" class="text-xs font-mono font-bold px-2 py-0.5 rounded-md bg-amber-400/20 text-amber-300 border border-amber-400/30">
                                    {{ $templates[0]['slots'] ?? 4 }} Pose
                                </span>
                                <span id="summary-frame-color" class="text-xs font-mono font-bold px-2 py-0.5 rounded-md bg-purple-500/20 text-purple-300 border border-purple-400/30">
                                    Frame: {{ $colors[0]['name'] ?? 'Bawaan' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
                        <button type="button" onclick="openPreviewModalForSelected()"
                                class="py-3 sm:py-3.5 px-3 sm:px-5 rounded-full bg-white/10 hover:bg-white/20 active:scale-95 border border-white/20 text-white font-bold text-[11px] sm:text-xs uppercase tracking-wider transition flex items-center justify-center gap-1.5 sm:gap-2 flex-1 sm:flex-none shadow-md">
                            <span>👁️</span>
                            <span class="hidden md:inline">Lihat Desain Frame</span>
                            <span class="md:hidden">Pratinjau</span>
                        </button>
                        <a href="{{ route('booth.index') }}" 
                           class="py-3 sm:py-3.5 px-3 sm:px-5 rounded-full bg-white/5 hover:bg-white/10 active:scale-95 border border-white/10 text-slate-300 hover:text-white font-bold text-[11px] sm:text-xs uppercase tracking-wider transition text-center flex-1 sm:flex-none">
                            Batal
                        </a>
                        <button type="submit" 
                                class="py-3 sm:py-3.5 px-4 sm:px-8 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-[11px] sm:text-xs uppercase tracking-widest shadow-xl shadow-amber-500/30 transition flex items-center justify-center gap-1 sm:gap-2 flex-1 sm:flex-none">
                            <span class="hidden sm:inline">Lanjut ke Kamera</span>
                            <span class="sm:hidden">Lanjut</span>
                            <span>→</span>
                        </button>
                    </div>

                </div>
            </div>

        </form>

    </main>

    <!-- Modal: Pratinjau Desain Frame Lengkap -->
    <div id="template-preview-modal" class="fixed inset-0 bg-black/85 backdrop-blur-md flex items-center justify-center p-4 z-50 hidden animate-in fade-in duration-200">
        <div class="bg-[#0F172A] border border-white/20 rounded-3xl max-w-md w-full p-5 sm:p-6 text-white shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-white/10">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-[#F5BD23]/20 border border-[#F5BD23]/40 text-[#F5BD23] flex items-center justify-center font-bold text-lg">
                        🖼️
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white" id="tmpl-modal-title">
                            Desain Frame Photobooth
                        </h3>
                        <p class="text-[11px] text-slate-400 font-mono" id="tmpl-modal-specs">
                            4 Pose • 1200 x 1800 px
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeTemplatePreviewModal()" 
                        class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition">
                    ✕
                </button>
            </div>

            <!-- Modal Canvas Preview Container -->
            <div class="flex-1 overflow-y-auto flex items-center justify-center p-3 bg-slate-950/80 rounded-2xl border border-white/10">
                <canvas id="template-modal-canvas" width="600" height="900" class="max-h-[50vh] sm:max-h-[55vh] w-auto rounded-xl shadow-2xl border border-white/10 object-contain"></canvas>
            </div>

            <!-- Modal Footer Info & Actions -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between text-xs text-slate-300 px-1">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F5BD23]"></span>
                        <span id="tmpl-modal-color-label">Warna Frame: Bawaan</span>
                    </span>
                    <span class="text-slate-400 font-mono text-[11px]">Pratinjau Hasil Cetak</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeTemplatePreviewModal()" 
                            class="flex-1 py-3 rounded-full bg-white/10 hover:bg-white/20 active:scale-95 text-xs font-bold text-white transition">
                        Tutup Pratinjau
                    </button>
                    <button type="button" id="tmpl-modal-select-btn" onclick="selectFromModal()" 
                            class="flex-1 py-3 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/25">
                        Pilih Template Ini ✓
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        const ALL_TEMPLATES = @json($templates);
        const ALL_COLORS = @json($colors);
        let currentFrameColor = "{{ $selectedColor ?? 'original' }}";

        function selectFrameColor(colorId, colorName, bgColor, textColor) {
            currentFrameColor = colorId;
            document.getElementById('selected-frame-color').value = colorId;
            document.getElementById('active-color-name').innerText = colorName;

            // Highlight active button
            document.querySelectorAll('.color-swatch-btn').forEach(btn => {
                btn.classList.remove('border-[#F5BD23]', 'ring-2', 'ring-[#F5BD23]/40', 'bg-white/15');
                btn.classList.add('border-white/10', 'bg-white/5');
            });
            const activeBtn = document.getElementById('color-btn-' + colorId);
            if (activeBtn) {
                activeBtn.classList.remove('border-white/10', 'bg-white/5');
                activeBtn.classList.add('border-[#F5BD23]', 'ring-2', 'ring-[#F5BD23]/40', 'bg-white/15');
            }

            // Dynamically tint mockup containers if color is not 'original'
            document.querySelectorAll('.mockup-box').forEach(box => {
                if (bgColor && bgColor !== 'null') {
                    box.style.backgroundColor = bgColor;
                } else {
                    box.style.backgroundColor = box.getAttribute('data-original-bg');
                }
            });

            // Update summary dock text
            const summaryCol = document.getElementById('summary-frame-color');
            if (summaryCol) summaryCol.innerText = 'Frame: ' + colorName;
        }

        function onCustomColorPick(hex) {
            selectFrameColor('custom', 'Kustom (' + hex + ')', hex, '#FFFFFF');
            document.getElementById('selected-frame-color').value = hex;
        }

        function selectTemplateCard(id) {
            document.getElementById('selected-template-id').value = id;

            // Reset all cards
            document.querySelectorAll('.template-card').forEach(card => {
                card.classList.remove('border-[#F5BD23]', 'ring-4', 'ring-[#F5BD23]/20', 'bg-[#172033]');
                card.classList.add('border-white/10');
                
                const cardId = card.id.replace('card-', '');
                const statusBadge = document.getElementById('badge-status-' + cardId);
                if (statusBadge) {
                    statusBadge.className = 'py-2 px-3 rounded-xl text-center text-xs font-bold transition flex items-center justify-center gap-1.5 bg-white/5 text-slate-400 group-hover:text-white';
                    statusBadge.innerHTML = '<span>Sentuh untuk Memilih</span>';
                }
            });

            // Set active card
            const activeCard = document.getElementById('card-' + id);
            if (activeCard) {
                activeCard.classList.remove('border-white/10');
                activeCard.classList.add('border-[#F5BD23]', 'ring-4', 'ring-[#F5BD23]/20', 'bg-[#172033]');

                const statusBadge = document.getElementById('badge-status-' + id);
                if (statusBadge) {
                    statusBadge.className = 'py-2 px-3 rounded-xl text-center text-xs font-bold transition flex items-center justify-center gap-1.5 bg-[#F5BD23] text-slate-950 font-black';
                    statusBadge.innerHTML = '<span>✓ Terpilih</span>';
                }

                // Update summary bottom dock
                const name = activeCard.getAttribute('data-name');
                const slots = activeCard.getAttribute('data-slots');
                document.getElementById('summary-template-name').innerText = name;
                document.getElementById('summary-template-slots').innerText = `${slots} Pose`;
            }
        }

        function filterCategory(cat) {
            // Update tabs styling
            const tabs = ['all', '8_slots', '6_slots', '4_slots', 'other_slots'];
            tabs.forEach(t => {
                const el = document.getElementById('tab-' + t);
                if (!el) return;
                if (t === cat) {
                    el.className = 'filter-tab px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider transition whitespace-nowrap bg-[#F5BD23] text-slate-950 shadow-lg shadow-amber-500/20';
                } else {
                    el.className = 'filter-tab px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition whitespace-nowrap bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10';
                }
            });

            // Filter cards
            const cards = document.querySelectorAll('.template-card');
            cards.forEach(card => {
                const cardCat = card.getAttribute('data-category');
                if (cat === 'all' || cardCat === cat) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // ========================================================
        // TEMPLATE FULL FRAME PREVIEW MODAL
        // ========================================================
        let modalActiveTemplateId = null;

        function openTemplatePreviewModal(templateId) {
            modalActiveTemplateId = templateId;
            const tmpl = ALL_TEMPLATES.find(t => t.id === templateId);
            if (!tmpl) return;

            document.getElementById('tmpl-modal-title').innerText = tmpl.name;
            document.getElementById('tmpl-modal-specs').innerText = `${tmpl.slots} Pose • 1200 x 1800 px • ${tmpl.category || 'Kolase'}`;
            
            const col = ALL_COLORS.find(c => c.id === currentFrameColor) || { name: (currentFrameColor.startsWith('#') ? 'Kustom' : 'Bawaan') };
            document.getElementById('tmpl-modal-color-label').innerText = `Warna Frame: ${col.name}`;

            const canvas = document.getElementById('template-modal-canvas');
            if (canvas) {
                drawTemplateMockup(canvas, tmpl, currentFrameColor);
            }

            document.getElementById('template-preview-modal').classList.remove('hidden');
        }

        function closeTemplatePreviewModal() {
            document.getElementById('template-preview-modal').classList.add('hidden');
        }

        function openPreviewModalForSelected() {
            const currentSelected = document.getElementById('selected-template-id').value;
            if (currentSelected) {
                openTemplatePreviewModal(currentSelected);
            }
        }

        function selectFromModal() {
            if (modalActiveTemplateId) {
                selectTemplateCard(modalActiveTemplateId);
            }
            closeTemplatePreviewModal();
        }

        function drawTemplateMockup(canvas, tmpl, colorId) {
            const ctx = canvas.getContext('2d');
            canvas.width = 600;
            canvas.height = 900;

            ctx.save();
            ctx.scale(0.5, 0.5); // Work in 1200x1800 space

            let bg = tmpl.bg_color || '#FFFFFF';
            let textCol = tmpl.text_color || '#1E293B';
            let borderCol = tmpl.accent || '#F5BD23';

            if (colorId && colorId !== 'original') {
                const found = ALL_COLORS.find(c => c.id === colorId);
                if (found) {
                    if (found.bg) bg = found.bg;
                    if (found.text) textCol = found.text;
                    if (found.border) borderCol = found.border;
                } else if (colorId.startsWith('#')) {
                    bg = colorId;
                    const c = colorId.replace('#', '');
                    const r = parseInt(c.substr(0,2), 16) || 0;
                    const g = parseInt(c.substr(2,2), 16) || 0;
                    const b = parseInt(c.substr(4,2), 16) || 0;
                    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
                    textCol = brightness > 140 ? '#1E293B' : '#F8FAFC';
                    borderCol = brightness > 140 ? '#CBD5E1' : '#475569';
                }
            }

            // 1. Draw Background
            ctx.fillStyle = bg;
            ctx.fillRect(0, 0, 1200, 1800);

            // 2. Compute slots
            const slots = tmpl.slots || 4;
            const tId = tmpl.id;
            let slotRects = [];

            if (tId === 'korean-8-strip' && slots === 8) {
                const stripW = 500, topY = 110, photoH = 340, gapY = 20;
                for (let i = 0; i < 4; i++) slotRects.push({ x: 60, y: topY + i * (photoH + gapY), w: stripW, h: photoH });
                for (let i = 0; i < 4; i++) slotRects.push({ x: 640, y: topY + i * (photoH + gapY), w: stripW, h: photoH });
            } else if (slots === 8) {
                const marginX = 50, topY = 110, gapX = 30, gapY = 22;
                const w = (1200 - (marginX * 2) - gapX) / 2, h = 345;
                for (let i = 0; i < 8; i++) {
                    const col = i % 2, row = Math.floor(i / 2);
                    slotRects.push({ x: marginX + col * (w + gapX), y: topY + row * (h + gapY), w, h });
                }
            } else if (slots === 6) {
                const marginX = (tId === 'filmstrip-6-retro') ? 95 : 55, topY = 120, gapX = 30, gapY = 28;
                const w = (1200 - (marginX * 2) - gapX) / 2, h = 460;
                for (let i = 0; i < 6; i++) {
                    const col = i % 2, row = Math.floor(i / 2);
                    slotRects.push({ x: marginX + col * (w + gapX), y: topY + row * (h + gapY), w, h });
                }
            } else if (slots === 3) {
                const marginX = 75, topY = 120, gapY = 32, w = 1200 - (marginX * 2), h = 465;
                for (let i = 0; i < 3; i++) slotRects.push({ x: marginX, y: topY + i * (h + gapY), w, h });
            } else if (slots === 2) {
                const marginX = 80, topY = 140, gapY = 40, w = 1200 - (marginX * 2), h = 700;
                for (let i = 0; i < 2; i++) slotRects.push({ x: marginX, y: topY + i * (h + gapY), w, h });
            } else if (slots === 1) {
                slotRects.push({ x: 100, y: 120, w: 1000, h: 1250 });
            } else {
                if (tId === 'korean-4-strip') {
                    const marginX = 180, topY = 100, gapY = 24, w = 1200 - (marginX * 2), h = 360;
                    for (let i = 0; i < 4; i++) slotRects.push({ x: marginX, y: topY + i * (h + gapY), w, h });
                } else {
                    const marginX = 55, topY = 70, gapX = 30, gapY = 30;
                    const w = (1200 - (marginX * 2) - gapX) / 2, h = 720;
                    for (let i = 0; i < 4; i++) {
                        const col = i % 2, row = Math.floor(i / 2);
                        slotRects.push({ x: marginX + col * (w + gapX), y: topY + row * (h + gapY), w, h });
                    }
                }
            }

            // 3. Draw Placeholders
            slotRects.forEach((r, idx) => {
                const grad = ctx.createLinearGradient(r.x, r.y, r.x + r.w, r.y + r.h);
                grad.addColorStop(0, '#1E293B');
                grad.addColorStop(1, '#0F172A');
                ctx.fillStyle = grad;
                ctx.fillRect(r.x, r.y, r.w, r.h);

                ctx.strokeStyle = borderCol;
                ctx.lineWidth = 3;
                ctx.strokeRect(r.x, r.y, r.w, r.h);

                ctx.fillStyle = '#F5BD23';
                ctx.font = 'bold 32px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('📸', r.x + r.w / 2, r.y + r.h / 2 - 10);

                ctx.fillStyle = '#E2E8F0';
                ctx.font = 'bold 22px monospace';
                ctx.fillText(`POSE #${idx + 1}`, r.x + r.w / 2, r.y + r.h / 2 + 30);
            });

            // 4. Layout Specific Decorations
            if (tId === 'korean-8-strip') {
                ctx.setLineDash([12, 10]);
                ctx.strokeStyle = borderCol;
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(600, 30);
                ctx.lineTo(600, 1770);
                ctx.stroke();
                ctx.setLineDash([]);

                ctx.fillStyle = textCol;
                ctx.font = 'bold 18px monospace';
                ctx.textAlign = 'center';
                ctx.fillText('✂ CUT HERE ✂', 600, 50);
                ctx.fillText('✂ CUT HERE ✂', 600, 1760);

                ctx.font = 'bold 28px sans-serif';
                ctx.fillText('🌸 LIFE 4 CUTS', 310, 75);
                ctx.fillText('🌸 LIFE 4 CUTS', 890, 75);
            } else if (tId === 'filmstrip-6-retro') {
                ctx.fillStyle = '#27272A';
                for (let y = 30; y < 1780; y += 45) {
                    ctx.fillRect(25, y, 35, 24);
                    ctx.fillRect(1140, y, 35, 24);
                }
                ctx.fillStyle = '#EAB308';
                ctx.font = 'bold 26px monospace';
                ctx.textAlign = 'left';
                ctx.fillText('KODAK ULTRA 400', 100, 70);
                ctx.textAlign = 'right';
                ctx.fillText('35MM COLOR FILM', 1100, 70);
            } else if (tId === 'vintage-newspaper-6') {
                ctx.fillStyle = textCol;
                ctx.font = 'bold 54px serif';
                ctx.textAlign = 'center';
                ctx.fillText('THE DAILY POTRET', 600, 70);
                ctx.strokeStyle = textCol;
                ctx.lineWidth = 2;
                ctx.strokeRect(60, 85, 1080, 2);
            } else if (tId === 'dark-elegance-6') {
                ctx.strokeStyle = '#F5BD23';
                ctx.lineWidth = 3;
                ctx.strokeRect(25, 25, 1150, 1750);
                ctx.font = 'bold 36px serif';
                ctx.fillStyle = '#F5BD23';
                ctx.textAlign = 'center';
                ctx.fillText('POTRET NOIR ATELIER', 600, 75);
            } else {
                ctx.fillStyle = textCol;
                ctx.font = 'bold 36px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(tmpl.name || 'POTRET DIRI • SELF STUDIO', 600, (slots === 4 && tId !== 'korean-4-strip') ? 48 : 70);

                ctx.font = 'bold 22px monospace';
                ctx.fillStyle = borderCol;
                ctx.fillText(`KOLASE ${slots} POSE • POTRET DIRI`, 600, 1750);
            }

            ctx.restore();
        }

        // Initialize on load with selected template & frame color
        window.addEventListener('DOMContentLoaded', () => {
            const currentSelected = document.getElementById('selected-template-id').value;
            if (currentSelected) {
                selectTemplateCard(currentSelected);
            }
            if (currentFrameColor && currentFrameColor !== 'original') {
                const found = ALL_COLORS.find(c => c.id === currentFrameColor);
                if (found) {
                    selectFrameColor(found.id, found.name, found.bg, found.text);
                } else if (currentFrameColor.startsWith('#')) {
                    onCustomColorPick(currentFrameColor);
                }
            }
        });

        // Keydown listener for modal closing
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeTemplatePreviewModal();
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="h-full bg-[#09090B]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LumaBooth Studio - Sesi Foto & Print Station</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Standalone Offline QR Code Generator -->
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fredoka:wght@600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .font-kiosk {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Screen flash animation */
        @keyframes flash-anim {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }
        .flash-active {
            animation: flash-anim 0.35s cubic-bezier(0.1, 0.9, 0.2, 1) forwards;
        }

        /* Pulse ring for LumaBooth Shutter */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 0.2; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .shutter-pulse {
            animation: pulse-ring 2s infinite ease-in-out;
        }

        /* Filter Presets */
        .filter-original { filter: none; }
        .filter-beauty { filter: contrast(106%) brightness(112%) saturate(114%); }
        .filter-peach { filter: sepia(16%) saturate(145%) hue-rotate(345deg) brightness(108%); }
        .filter-bw { filter: grayscale(100%) contrast(130%) brightness(105%); }
        .filter-vintage { filter: sepia(50%) contrast(110%) brightness(95%) saturate(130%); }
        .filter-warm { filter: sepia(25%) saturate(150%) brightness(105%) hue-rotate(-12deg); }
        .filter-cyber { filter: contrast(130%) saturate(170%) hue-rotate(170deg); }

        /* Draggable Sticker Styling */
        .sticker-item {
            position: absolute;
            user-select: none;
            touch-action: none;
            cursor: grab;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transform: translate(-50%, -50%);
            transition: outline 0.15s ease-out;
            z-index: 30;
        }
        .sticker-item:active {
            cursor: grabbing;
        }
        .sticker-item.selected {
            outline: 2px dashed #F5BD23;
            outline-offset: 4px;
            border-radius: 8px;
        }
        .sticker-ctrl-btn {
            position: absolute;
            top: -12px;
            right: -12px;
            width: 22px;
            height: 22px;
            border-radius: 9999px;
            background-color: #EF4444;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.6);
            cursor: pointer;
            z-index: 35;
        }
        .sticker-resize-btn {
            position: absolute;
            bottom: -12px;
            right: -12px;
            width: 22px;
            height: 22px;
            border-radius: 9999px;
            background-color: #F5BD23;
            color: #09090B;
            font-size: 11px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.6);
            cursor: pointer;
            z-index: 35;
        }

        /* Print formatting - Only photostrip is printed */
        @media print {
            body * { visibility: hidden !important; }
            #print-container, #print-container * { visibility: visible !important; }
            #print-container {
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                background: #fff !important;
            }
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between items-center bg-[#09090B] text-slate-100 antialiased selection:bg-[#F5BD23] selection:text-slate-950 overflow-x-hidden">

    <!-- Flash overlay for camera snap (LumaBooth Strobe) -->
    <div id="camera-flash" class="fixed inset-0 bg-white pointer-events-none z-[120] opacity-0"></div>

    <!-- Hidden Video & Canvas Elements for Capture & Collage Rendering -->
    <video id="live-video" autoplay playsinline class="hidden"></video>
    <canvas id="capture-canvas" width="1200" height="900" class="hidden"></canvas>
    <canvas id="collage-canvas" width="1200" height="1800" class="hidden"></canvas>

    <!-- Hidden Print Target -->
    <div id="print-container" class="hidden">
        <img id="print-img" src="" alt="Print Photostrip" style="max-height: 100vh; max-width: 100vw; object-fit: contain;">
    </div>

    <!-- Toast Notification Banner -->
    <div id="toast-banner" class="fixed top-6 left-1/2 -translate-x-1/2 z-[110] bg-slate-900/95 text-white px-6 py-3 rounded-full border border-amber-400/40 shadow-2xl flex items-center gap-3 text-xs font-bold transition-all duration-300 -translate-y-20 opacity-0 pointer-events-none">
        <span id="toast-icon">✨</span>
        <span id="toast-message">Pesan notifikasi</span>
    </div>

    <!-- ========================================================================= -->
    <!-- TOP STUDIO NAVIGATION BAR (LumaBooth Dark Kiosk Header)                   -->
    <!-- ========================================================================= -->
    <header class="w-full max-w-7xl px-4 sm:px-6 py-3.5 flex items-center justify-between z-30 border-b border-white/10 bg-[#09090B]/90 backdrop-blur-md">
        <!-- Brand & Live Indicator -->
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-2xl bg-[#F5BD23] text-slate-950 flex items-center justify-center font-black text-lg shadow-lg shadow-amber-500/20">
                📸
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-extrabold text-sm sm:text-base tracking-wide text-white">LumaBooth</span>
                    <span class="text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full bg-white/10 text-amber-300 font-mono">
                        Studio Kiosk
                    </span>
                </div>
                <div class="flex items-center gap-1.5 text-[11px] text-emerald-400 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Live Camera Active</span>
                </div>
            </div>
        </div>

        <!-- Session Badge & Header Timer -->
        <div class="hidden sm:flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-mono">
            <span class="text-slate-400">SESI:</span>
            <span class="text-amber-400 font-bold tracking-wider">{{ $booking->booking_code }}</span>
            <span class="text-slate-600">•</span>
            <span class="text-slate-300 truncate max-w-[120px]">{{ $booking->customer_name }}</span>
            <span class="text-slate-600">•</span>
            <span id="header-timer-pill" class="text-amber-300 font-bold flex items-center gap-1.5 transition-colors">
                <span>⏱️</span>
                <span id="header-time-display" class="font-mono font-black tracking-wider">{{ sprintf('%02d:00', $selectedDuration ?? 5) }}</span>
            </span>
        </div>

        <!-- Controls (Sound, Mirror, Switch, Exit) -->
        <div class="flex items-center gap-1.5 sm:gap-2">
            <!-- Frame Overlay Toggle (LumaBooth Live View Frame) -->
            <button type="button" onclick="toggleLiveFrameOverlay()" id="btn-frame-overlay" title="Tampilkan/Sembunyikan Desain Frame Kamera"
                    class="p-2 sm:p-2.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 active:scale-95 border border-amber-500/40 text-xs text-amber-300 flex items-center gap-1.5 transition">
                <span>🖼️</span>
                <span class="hidden md:inline text-[11px] font-bold" id="frame-overlay-label">Frame: Aktif</span>
            </button>

            <!-- Preview Desain Frame Button -->
            <button type="button" onclick="openFramePreviewModal()" id="btn-preview-frame" title="Lihat Desain Frame Lengkap"
                    class="p-2 sm:p-2.5 rounded-xl bg-white/5 hover:bg-white/10 active:scale-95 border border-white/10 text-xs text-slate-300 flex items-center gap-1.5 transition">
                <span>👁️</span>
                <span class="hidden lg:inline text-[11px] font-bold">Desain Frame</span>
            </button>

            <!-- Sound Toggle -->
            <button type="button" onclick="toggleAudioVoice()" id="btn-sound" title="Toggle Voice/Sound"
                    class="p-2 sm:p-2.5 rounded-xl bg-white/5 hover:bg-white/10 active:scale-95 border border-white/10 text-xs text-slate-300 flex items-center gap-1.5 transition">
                <span id="sound-icon">🔊</span>
                <span class="hidden md:inline text-[11px] font-bold" id="sound-label">Suara: Aktif</span>
            </button>

            <!-- Mirror Toggle -->
            <button type="button" onclick="toggleCameraMirror()" id="btn-mirror" title="Mirror/Flip Kamera"
                    class="p-2 sm:p-2.5 rounded-xl bg-white/5 hover:bg-white/10 active:scale-95 border border-white/10 text-xs text-slate-300 flex items-center gap-1.5 transition">
                <span>🪞</span>
                <span class="hidden md:inline text-[11px] font-bold">Mirror</span>
            </button>

            <!-- Camera Switcher -->
            <button type="button" onclick="cycleCameraDevices()" id="btn-switch-cam" title="Ganti Kamera"
                    class="p-2 sm:p-2.5 rounded-xl bg-white/5 hover:bg-white/10 active:scale-95 border border-white/10 text-xs text-slate-300 flex items-center gap-1.5 transition">
                <span>🔄</span>
                <span class="hidden md:inline text-[11px] font-bold">Kamera</span>
            </button>

            <!-- Exit / Cancel Button -->
            <button type="button" onclick="openExitConfirmModal()" title="Batalkan Sesi"
                    class="p-2 sm:p-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 active:scale-95 border border-rose-500/20 text-xs text-rose-300 transition">
                <span>✕</span>
            </button>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- STAGE 1: LUMABOOTH STUDIO CAPTURE (AUTOMATIC 4-SHOT SEQUENCE)             -->
    <!-- ========================================================================= -->
    <section id="screen-capture" class="w-full flex-1 flex flex-col items-center justify-between p-4 sm:p-6 relative max-w-6xl">
        
        <!-- Top Sub-Bar: Pose Steps Progress Indicator & Studio Timer -->
        <div class="w-full flex flex-wrap items-center justify-between gap-3 max-w-4xl pt-1 pb-3">
            <div class="flex items-center gap-2 text-xs font-mono tracking-wider text-slate-400">
                <span>MODE: <strong class="text-white">{{ strtoupper($template['name'] ?? 'PHOTO BOOTH') }} ({{ $template['slots'] ?? 4 }} POSES)</strong></span>
            </div>

            <!-- Session Countdown Timer Pill (Synchronized with Admin Setting) -->
            <div id="session-timer-pill" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/15 border border-amber-400/40 text-amber-300 font-mono font-bold text-xs shadow-lg shadow-amber-500/10 transition-all duration-300">
                <span id="session-timer-dot" class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-[11px] uppercase tracking-wider">Sisa Waktu:</span>
                <span id="session-time-display" class="font-black text-sm text-white tracking-widest">{{ sprintf('%02d:00', $selectedDuration ?? 5) }}</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-mono text-slate-400">PROGRES:</span>
                <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 font-mono font-black text-xs">
                    POSE <span id="pose-counter-num">1</span> / {{ $template['slots'] ?? 4 }}
                </span>
            </div>
        </div>

        <!-- Main Center Viewfinder Stage (Authentic LumaBooth Dark Studio) -->
        <div class="relative w-full max-w-4xl aspect-[4/3] max-h-[55vh] sm:max-h-[65vh] rounded-3xl overflow-hidden bg-slate-950 border-2 border-white/15 shadow-2xl flex items-center justify-center my-auto ring-1 ring-white/5">
            
            <!-- Live View Video Stream -->
            <video id="viewfinder-video" autoplay playsinline class="w-full h-full object-cover transform -scale-x-100 transition-transform duration-300"></video>

            <!-- Subtle Grid / Rule of Thirds Guide (Toggleable) -->
            <div class="absolute inset-0 pointer-events-none grid grid-cols-3 grid-rows-3 opacity-20 z-10">
                <div class="border-r border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div class="border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div class="border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div></div>
            </div>

            <!-- Live Frame Overlay (LumaBooth "Show Template in Live View") -->
            <div id="live-frame-overlay" class="absolute inset-0 pointer-events-none z-15 flex flex-col justify-between p-3 sm:p-5 transition-all duration-300">
                <!-- Top Frame Bar -->
                <div id="live-frame-top" class="flex items-center justify-between px-3.5 py-1.5 rounded-xl bg-black/50 backdrop-blur-md border border-white/15 text-white shadow-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span id="live-frame-title" class="text-xs font-black tracking-wider uppercase">
                            {{ $template['name'] ?? 'Potret Diri Photobooth' }}
                        </span>
                    </div>
                    <span id="live-frame-badge" class="px-2.5 py-0.5 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30 text-[10px] font-mono font-bold">
                        POSE <span id="live-pose-num">1</span> / {{ $template['slots'] ?? 4 }}
                    </span>
                </div>

                <!-- Center Cutout Slot Guide / Frame Border Graphic -->
                <div id="live-frame-border" class="flex-1 my-2 border-2 border-dashed border-amber-400/60 rounded-2xl relative flex items-center justify-center transition-all duration-300 shadow-[inset_0_0_30px_rgba(0,0,0,0.5)]">
                    <!-- Corner Brackets -->
                    <div id="live-frame-corner-tl" class="absolute top-2 left-2 text-xs font-bold text-amber-300/80 font-mono">┌</div>
                    <div id="live-frame-corner-tr" class="absolute top-2 right-2 text-xs font-bold text-amber-300/80 font-mono">┐</div>
                    <div id="live-frame-corner-bl" class="absolute bottom-2 left-2 text-xs font-bold text-amber-300/80 font-mono">└</div>
                    <div id="live-frame-corner-br" class="absolute bottom-2 right-2 text-xs font-bold text-amber-300/80 font-mono">┘</div>

                    <!-- Filmsprocket elements if retro filmstrip -->
                    <div id="live-frame-sprockets" class="hidden absolute inset-y-0 left-0 right-0 flex justify-between px-1 pointer-events-none">
                        <div class="flex flex-col justify-around py-2">
                            @for ($i = 0; $i < 6; $i++) <span class="w-2 h-2.5 bg-black/70 border border-white/20 rounded-sm"></span> @endfor
                        </div>
                        <div class="flex flex-col justify-around py-2">
                            @for ($i = 0; $i < 6; $i++) <span class="w-2 h-2.5 bg-black/70 border border-white/20 rounded-sm"></span> @endfor
                        </div>
                    </div>

                    <!-- Center Pose Hint watermark -->
                    <div id="live-frame-watermark" class="text-center space-y-1 opacity-60">
                        <span class="text-2xl block">📸</span>
                        <span id="live-frame-watermark-text" class="text-[11px] font-mono font-bold text-white uppercase tracking-wider drop-shadow-md">
                            Area Pose Slot #<span id="live-slot-hint-num">1</span>
                        </span>
                    </div>
                </div>

                <!-- Bottom Frame Bar -->
                <div id="live-frame-bottom" class="flex items-center justify-between px-3.5 py-1.5 rounded-xl bg-black/50 backdrop-blur-md border border-white/15 text-white shadow-lg text-[10px] font-mono">
                    <span id="live-frame-footer-brand" class="text-slate-300 font-bold truncate">POTRET DIRI STUDIO</span>
                    <span id="live-frame-color-tag" class="text-amber-300 font-bold">FRAME: {{ strtoupper($selectedColor ?? 'ORIGINAL') }}</span>
                </div>
            </div>

            <!-- Pre-Start Trigger Overlay (LumaBooth "Touch Screen to Start") -->
            <div id="touch-start-overlay" class="absolute inset-0 bg-black/65 backdrop-blur-sm flex flex-col items-center justify-center gap-5 z-30 p-6 text-center animate-in fade-in duration-300">
                <div class="space-y-2">
                    <span class="px-3.5 py-1 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30 text-[11px] font-black uppercase tracking-widest">
                        Ready To Shoot
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">Siap Untuk Berpose?</h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-md mx-auto leading-relaxed">
                        Kamera akan mengambil {{ $template['slots'] ?? 4 }} jepretan otomatis dengan jeda waktu untuk berganti gaya. Sentuh tombol untuk memulai!
                    </p>

                    <!-- Studio Session Info Badge (Admin Configured Duration & Retake) -->
                    <div class="flex flex-wrap items-center justify-center gap-2 pt-1 text-xs font-mono">
                        <span class="px-3.5 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 font-bold flex items-center gap-1.5">
                            <span>⏱️ Durasi Studio:</span>
                            <strong class="text-white">{{ $selectedDuration ?? 5 }} Menit</strong>
                        </span>
                        @if($retakeEnabled ?? true)
                        <span class="px-3.5 py-1 rounded-full bg-white/10 border border-white/15 text-slate-300 flex items-center gap-1.5">
                            <span>↺ Foto Ulang:</span>
                            <strong class="text-amber-300">{{ ($retakeLimit ?? 'unlimited') === 'unlimited' ? 'Bebas' : "Maks {$retakeLimit}x" }}</strong>
                        </span>
                        @else
                        <span class="px-3.5 py-1 rounded-full bg-rose-500/10 border border-rose-500/30 text-rose-300 flex items-center gap-1.5">
                            <span>↺ Foto Ulang:</span>
                            <strong>Nonaktif</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Frame Preview Badge & Button -->
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 shadow-lg">
                    <div class="w-8 h-10 rounded-lg bg-amber-400/20 border border-amber-400/40 text-amber-300 flex items-center justify-center text-xs font-mono font-black">
                        {{ $template['slots'] ?? 4 }}P
                    </div>
                    <div class="text-left">
                        <span class="text-[10px] font-mono text-amber-300 block uppercase font-bold">Desain Frame:</span>
                        <strong class="text-xs text-white block truncate max-w-[160px] sm:max-w-xs">{{ $template['name'] ?? 'Template Photobooth' }}</strong>
                    </div>
                    <button type="button" onclick="event.stopPropagation(); openFramePreviewModal()" 
                            class="ml-2 px-3 py-1.5 rounded-xl bg-[#F5BD23] hover:bg-amber-300 active:scale-95 text-slate-950 font-black text-xs transition flex items-center gap-1 shadow-md">
                        <span>👁️</span>
                        <span>Lihat Desain</span>
                    </button>
                </div>

                <!-- Big LumaBooth Glowing Shutter Button -->
                <div class="relative flex items-center justify-center cursor-pointer" onclick="startAutomaticPhotoSequence()">
                    <div class="absolute w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-[#F5BD23] shutter-pulse"></div>
                    <button type="button" 
                            class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 flex flex-col items-center justify-center shadow-2xl shadow-amber-500/50 transition transform">
                        <span class="text-2xl sm:text-3xl">📸</span>
                        <span class="text-[9px] sm:text-[10px] font-black tracking-wider uppercase mt-0.5 sm:mt-1">START</span>
                    </button>
                </div>

                <div class="text-[11px] text-slate-400 flex items-center gap-1.5 font-mono">
                    <span>💡 Tekan Spasi (Spacebar) atau Sentuh Layar untuk Memulai</span>
                </div>
            </div>

            <!-- Radial Countdown Overlay (LumaBooth Signature 3, 2, 1, SMILE!) -->
            <div id="countdown-container" class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex flex-col items-center justify-center transition-all z-25 hidden">
                <div class="relative w-44 h-44 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="42" stroke="rgba(255,255,255,0.15)" stroke-width="8" fill="none"/>
                        <circle id="countdown-circle" cx="50" cy="50" r="42" stroke="#F5BD23" stroke-width="8" stroke-dasharray="264" stroke-dashoffset="0" stroke-linecap="round" fill="none" class="transition-all duration-1000 ease-linear"/>
                    </svg>
                    <span id="countdown-digit" class="absolute font-black text-7xl text-[#F5BD23] drop-shadow-[0_10px_20px_rgba(0,0,0,0.8)]">3</span>
                </div>
                <div class="mt-6 text-center space-y-1">
                    <p id="countdown-label" class="text-white text-lg sm:text-xl font-black tracking-widest uppercase animate-pulse">BERSIAP POSE!</p>
                    <p id="countdown-sub" class="text-amber-300 text-xs font-mono font-bold">Pose 1 dari {{ $template['slots'] ?? 4 }}</p>
                </div>
            </div>

            <!-- Intermission Flashback Overlay (LumaBooth Post-Shot Review & Retake) -->
            <div id="flashback-overlay" class="absolute inset-0 bg-black/75 backdrop-blur-md flex flex-col items-center justify-center gap-4 z-28 p-6 text-center hidden animate-in fade-in duration-200">
                <div class="relative max-h-[60%] aspect-[4/3] rounded-2xl overflow-hidden border-2 border-[#F5BD23] shadow-2xl">
                    <img id="flashback-img" src="" class="w-full h-full object-cover">
                    <div class="absolute top-2 left-2 px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur text-[10px] font-mono font-bold text-amber-300">
                        ✓ Pose <span id="flashback-pose-num">1</span> Saved!
                    </div>
                </div>

                <div class="space-y-1">
                    <h3 class="text-xl font-black text-white">Bagus Sekali! ✨</h3>
                    <p id="flashback-timer-text" class="text-xs text-slate-300 font-mono">Pose berikutnya dimulai dalam 3 detik...</p>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    @if($retakeEnabled ?? true)
                    <button type="button" id="flashback-retake-btn" onclick="retakeCurrentPoseImmediately()" 
                            class="px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 active:scale-95 border border-white/20 text-xs font-bold text-white flex items-center gap-1.5 transition">
                        <span>↺</span>
                        <span id="flashback-retake-label">Foto Ulang Pose Ini</span>
                    </button>
                    @endif
                    <button type="button" onclick="skipIntermissionImmediately()" 
                            class="px-6 py-2.5 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 text-xs font-black tracking-wider uppercase transition shadow-lg shadow-amber-500/30">
                        <span>Lanjut Sekarang →</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Bottom Photo Thumbnails Strip (LumaBooth Slot Dock) -->
        <div class="w-full max-w-4xl flex items-center justify-center gap-2 sm:gap-3 pt-4 pb-1 overflow-x-auto px-2">
            @for ($i = 0; $i < ($template['slots'] ?? 4); $i++)
            <div id="thumb-slot-{{ $i }}" class="flex-1 min-w-[50px] max-w-[110px] aspect-square rounded-2xl bg-white/5 border-2 border-white/10 flex flex-col items-center justify-center text-xs font-mono text-slate-400 overflow-hidden relative shadow-md transition-all duration-300">
                <span class="text-base font-bold">{{ $i + 1 }}</span>
                <span class="text-[9px] text-slate-500">Pose {{ $i + 1 }}</span>
            </div>
            @endfor
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- STAGE 2: LUMABOOTH "DEVELOPING PHOTOS" TRANSITION SCREEN                  -->
    <!-- ========================================================================= -->
    <section id="screen-processing" class="w-full flex-1 flex flex-col items-center justify-center p-6 hidden text-center space-y-6">
        <div class="relative w-28 h-28 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full border-4 border-amber-400/20 animate-ping"></div>
            <div class="w-24 h-24 rounded-full border-4 border-[#F5BD23] border-t-transparent animate-spin"></div>
            <span class="absolute text-4xl">🎞️</span>
        </div>

        <div class="space-y-2 max-w-sm">
            <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Mengembangkan Hasil Foto...</h2>
            <p class="text-xs text-slate-400 leading-relaxed">
                Menyusun bingkai kolase, menyempurnakan resolusi HD & menyiapkan link galeri online...
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="w-full max-w-md bg-white/10 h-3 rounded-full overflow-hidden p-0.5 border border-white/10">
            <div id="processing-bar" class="bg-[#F5BD23] h-full rounded-full w-0 transition-all duration-300 ease-out"></div>
        </div>
        <p id="processing-pct" class="text-xs font-mono font-bold text-amber-300">0%</p>
    </section>

    <!-- ========================================================================= -->
    <!-- STAGE 3: LUMABOOTH SHARING & PRINT STATION SCREEN                         -->
    <!-- ========================================================================= -->
    <section id="screen-sharing" class="w-full flex-1 flex flex-col items-center justify-between p-4 sm:p-6 hidden max-w-7xl">
        
        <!-- Sharing Screen Top Action Bar -->
        <div class="w-full flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-white/10">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-black uppercase tracking-widest text-slate-300">STUDIO SHARING & PRINT STATION</span>
            </div>

            <!-- Auto-Finish Countdown Timer & Finish Button -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <div class="px-3.5 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-mono text-slate-300 flex items-center gap-2">
                    <span>⏱️ Selesai otomatis:</span>
                    <strong id="auto-finish-timer" class="text-amber-400">60s</strong>
                </div>

                <button type="button" onclick="finishSessionWithCelebration()" 
                        class="px-5 sm:px-6 py-2.5 rounded-full bg-emerald-500 hover:bg-emerald-400 active:scale-95 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition flex items-center gap-1.5">
                    <span>SELESAI SESI</span>
                    <span>✓</span>
                </button>
            </div>
        </div>

        <!-- Main Sharing Grid (2 Columns: Left Preview & Right LumaBooth Action Panel) -->
        <main class="w-full my-auto py-4 grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
            
            <!-- ======================================================== -->
            <!-- KOLOM KIRI: Layout Preview, Mode Tabs, & Filters (7 Kolom)-->
            <!-- ======================================================== -->
            <div class="lg:col-span-7 bg-white/5 backdrop-blur-md rounded-3xl p-4 sm:p-7 border border-white/10 space-y-5 shadow-2xl">
                
                <!-- LumaBooth Preview Switcher Tabs -->
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <div class="flex items-center gap-3 sm:gap-4 text-xs font-extrabold">
                        <!-- Tab 1: Print Layout (Default) -->
                        <button type="button" onclick="switchPreviewTab('print')" id="tab-print" 
                                class="flex items-center gap-1.5 pb-2 text-[#F5BD23] border-b-2 border-[#F5BD23] font-black transition">
                            <span>🖶</span>
                            <span>Print Layout</span>
                        </button>

                        <!-- Tab 2: Single Photos -->
                        <button type="button" onclick="switchPreviewTab('single')" id="tab-single" 
                                class="flex items-center gap-1.5 pb-2 text-slate-400 hover:text-white border-b-2 border-transparent transition">
                            <span>📷</span>
                            <span>Foto Satuan</span>
                        </button>

                        <!-- Tab 3: Animated GIF Loop -->
                        <button type="button" onclick="switchPreviewTab('gif')" id="tab-gif" 
                                class="flex items-center gap-1.5 pb-2 text-slate-400 hover:text-white border-b-2 border-transparent transition">
                            <span>🎬</span>
                            <span>Animasi GIF</span>
                        </button>
                    </div>

                    <span class="text-[10px] font-mono text-slate-400 bg-white/5 px-2.5 py-1 rounded-full border border-white/5">
                        HD 300 DPI
                    </span>
                </div>

                <!-- Main Featured Preview Box -->
                <div class="relative bg-slate-950 rounded-2xl p-3 sm:p-6 flex items-center justify-center min-h-[280px] sm:min-h-[420px] overflow-hidden border border-white/10 shadow-inner" id="preview-stage-container">
                    
                    <!-- 1. Print Photostrip Composite with interactive sticker layer -->
                    <div id="print-wrapper" class="relative inline-block select-none max-h-[340px] sm:max-h-[400px]">
                        <img id="preview-print-img" src="" alt="Print Layout Preview" 
                             class="max-h-[340px] sm:max-h-[400px] w-auto max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300 filter-original select-none pointer-events-none">
                        
                        <!-- Interactive Draggable Stickers Layer -->
                        <div id="stickers-overlay" class="absolute inset-0 pointer-events-auto overflow-hidden rounded-xl"></div>
                    </div>

                    <!-- 2. Single Photo Preview -->
                    <img id="preview-single-img" src="" alt="Single Photo Preview" 
                         class="max-h-[400px] w-auto max-w-full object-contain rounded-xl shadow-2xl hidden transition-all duration-300 filter-original">

                    <!-- 3. Animated GIF Preview -->
                    <img id="preview-gif-img" src="" alt="Animated GIF Preview" 
                         class="max-h-[400px] w-auto max-w-full object-contain rounded-xl shadow-2xl hidden transition-all duration-300 filter-original">

                    <!-- Single Photo Counter Badge -->
                    <div id="single-photo-badge" class="absolute bottom-4 right-4 px-3 py-1 rounded-full bg-black/70 backdrop-blur border border-white/10 text-white text-xs font-mono font-bold hidden">
                        Pose <span id="single-photo-num">1</span> / {{ $template['slots'] ?? 4 }}
                    </div>
                </div>

                <!-- Single Photo Selector Strip (Active in Single Photo Tab) -->
                <div id="single-photo-strip" class="flex items-center gap-2 overflow-x-auto pb-1 hidden">
                    @for ($i = 0; $i < ($template['slots'] ?? 4); $i++)
                    <div onclick="selectSinglePhoto({{ $i }})" id="single-box-{{ $i }}" class="w-16 h-16 shrink-0 rounded-xl overflow-hidden cursor-pointer border-2 {{ $i === 0 ? 'border-[#F5BD23] ring-2 ring-amber-400/40' : 'border-transparent hover:border-slate-400' }} transition">
                        <img id="single-thumb-{{ $i }}" src="" class="w-full h-full object-cover">
                    </div>
                    @endfor
                </div>

                <!-- LumaBooth Live Photo Filters Bar -->
                <div class="space-y-2 pt-1 border-t border-white/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                            <span>✦</span>
                            <span>FILTER FOTO STUDIO (LumaBooth Style)</span>
                        </span>
                        <span id="active-filter-label" class="text-[11px] font-mono text-amber-400 font-bold">Original</span>
                    </div>

                    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-center text-xs custom-scrollbar">
                        <button type="button" onclick="applyPhotoFilter('filter-original', 'Original')" id="filter-btn-original" 
                                class="px-3 py-2 rounded-xl bg-white/10 border-2 border-[#F5BD23] hover:bg-white/20 font-bold text-white transition flex flex-col items-center gap-1 shrink-0 min-w-[72px]">
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-300"></span>
                            <span class="text-[10px]">Original</span>
                        </button>

                        <button type="button" onclick="applyPhotoFilter('filter-beauty', 'Beauty Glow ✨')" id="filter-btn-beauty" 
                                class="px-3 py-2 rounded-xl bg-white/5 border-2 border-transparent hover:bg-white/10 font-bold text-slate-300 transition flex flex-col items-center gap-1 shrink-0 min-w-[72px]">
                            <span class="w-3.5 h-3.5 rounded-full bg-pink-300 shadow"></span>
                            <span class="text-[10px]">Beauty ✨</span>
                        </button>

                        <button type="button" onclick="applyPhotoFilter('filter-peach', 'Peach Blush 🍑')" id="filter-btn-peach" 
                                class="px-3 py-2 rounded-xl bg-white/5 border-2 border-transparent hover:bg-white/10 font-bold text-slate-300 transition flex flex-col items-center gap-1 shrink-0 min-w-[72px]">
                            <span class="w-3.5 h-3.5 rounded-full bg-orange-300 shadow"></span>
                            <span class="text-[10px]">Peach 🍑</span>
                        </button>

                        <button type="button" onclick="applyPhotoFilter('filter-bw', 'Glamour B&W')" id="filter-btn-bw" 
                                class="px-3 py-2 rounded-xl bg-white/5 border-2 border-transparent hover:bg-white/10 font-bold text-slate-300 transition flex flex-col items-center gap-1 shrink-0 min-w-[72px]">
                            <span class="w-3.5 h-3.5 rounded-full bg-zinc-900 border border-white/50"></span>
                            <span class="text-[10px]">Glam B&W</span>
                        </button>

                        <button type="button" onclick="applyPhotoFilter('filter-vintage', 'Vintage Warm')" id="filter-btn-vintage" 
                                class="px-3 py-2 rounded-xl bg-white/5 border-2 border-transparent hover:bg-white/10 font-bold text-slate-300 transition flex flex-col items-center gap-1 shrink-0 min-w-[72px]">
                            <span class="w-3.5 h-3.5 rounded-full bg-amber-700"></span>
                            <span class="text-[10px]">Vintage</span>
                        </button>

                        <button type="button" onclick="applyPhotoFilter('filter-warm', 'Golden Hour')" id="filter-btn-warm" 
                                class="px-3 py-2 rounded-xl bg-white/5 border-2 border-transparent hover:bg-white/10 font-bold text-slate-300 transition flex flex-col items-center gap-1 shrink-0 min-w-[72px]">
                            <span class="w-3.5 h-3.5 rounded-full bg-yellow-500"></span>
                            <span class="text-[10px]">Golden</span>
                        </button>

                        <button type="button" onclick="applyPhotoFilter('filter-cyber', 'Cyber Neon')" id="filter-btn-cyber" 
                                class="px-3 py-2 rounded-xl bg-white/5 border-2 border-transparent hover:bg-white/10 font-bold text-slate-300 transition flex flex-col items-center gap-1 shrink-0 min-w-[72px]">
                            <span class="w-3.5 h-3.5 rounded-full bg-cyan-400"></span>
                            <span class="text-[10px]">Cyber</span>
                        </button>
                    </div>
                </div>

                <!-- LumaBooth Frame Color Swatches Bar -->
                <div class="space-y-2 pt-3 border-t border-white/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                            <span>🎨</span>
                            <span>WARNA FRAME BINGKAI (Frame Color)</span>
                        </span>
                        <span id="active-frame-color-label" class="text-[11px] font-mono text-amber-400 font-bold">
                            {{ $selectedColor ?? 'Bawaan Template' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
                        @foreach ($colors as $c)
                        @php $isColActive = (($selectedColor ?? 'original') === $c['id']); @endphp
                        <button type="button" 
                                onclick="applyFrameColor('{{ $c['id'] }}', '{{ $c['name'] }}', '{{ $c['bg'] }}', '{{ $c['text'] }}', '{{ $c['border'] }}')"
                                id="btn-frame-col-{{ $c['id'] }}"
                                title="{{ $c['name'] }}"
                                class="frame-col-btn px-3 py-2 rounded-xl bg-white/5 border-2 {{ $isColActive ? 'border-[#F5BD23] bg-white/15' : 'border-transparent' }} hover:bg-white/10 transition flex items-center gap-2 shrink-0 group">
                            <span class="w-3.5 h-3.5 rounded-full shadow border border-white/30 shrink-0 flex items-center justify-center text-[8px]" style="background-color: {{ $c['hex'] }};">
                                @if ($c['id'] === 'original') ✦ @endif
                            </span>
                            <span class="text-[11px] font-bold text-slate-300 group-hover:text-white whitespace-nowrap">{{ $c['name'] }}</span>
                        </button>
                        @endforeach

                        <!-- Custom Color Picker -->
                        <label class="px-3 py-2 rounded-xl bg-white/5 border-2 border-transparent hover:bg-white/10 transition flex items-center gap-2 shrink-0 cursor-pointer text-slate-300 hover:text-white"
                               title="Pilih warna bebas / kustom">
                            <input type="color" onchange="onCustomSessionColor(this.value)" class="w-3.5 h-3.5 rounded-full border-0 p-0 cursor-pointer bg-transparent">
                            <span class="text-[11px] font-bold whitespace-nowrap">Bebas</span>
                        </label>
                    </div>
                </div>

                <!-- LumaBooth Digital Props & Stickers Bar -->
                <div class="space-y-2 pt-3 border-t border-white/10">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                            <span>✨</span>
                            <span>STIKER & DIGITAL PROPS (Sentuh untuk Tempel)</span>
                        </span>
                        <button type="button" onclick="clearAllStickers()" id="btn-clear-stickers" class="text-[11px] font-mono text-rose-400 hover:text-rose-300 font-bold transition hidden">
                            ✕ Hapus Semua (<span id="sticker-count-badge">0</span>)
                        </button>
                    </div>

                    <!-- Category Pills -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-[10px] font-extrabold custom-scrollbar">
                        <button type="button" onclick="switchStickerCat('all')" id="stk-cat-all" class="stk-cat-btn px-2.5 py-1 rounded-lg bg-[#F5BD23] text-slate-950 whitespace-nowrap shadow-sm">Semua</button>
                        <button type="button" onclick="switchStickerCat('props')" id="stk-cat-props" class="stk-cat-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 whitespace-nowrap border border-white/10">👑 Topi & Props</button>
                        <button type="button" onclick="switchStickerCat('glasses')" id="stk-cat-glasses" class="stk-cat-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 whitespace-nowrap border border-white/10">🕶️ Kacamata</button>
                        <button type="button" onclick="switchStickerCat('cute')" id="stk-cat-cute" class="stk-cat-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 whitespace-nowrap border border-white/10">💖 Hati & Efek</button>
                        <button type="button" onclick="switchStickerCat('badge')" id="stk-cat-badge" class="stk-cat-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 whitespace-nowrap border border-white/10">💬 Teks Badge</button>
                    </div>

                    <!-- Stickers List Tray -->
                    <div id="stickers-tray" class="flex items-center gap-2 overflow-x-auto py-1 custom-scrollbar">
                        <!-- Injected via renderStickersTray() -->
                    </div>

                    <p class="text-[10px] text-slate-400 font-mono">
                        💡 Tips: Sentuh stiker untuk menempelkan ke foto. Anda bisa menggeser (drag), mengubah ukuran (+/-), dan menghapus (✕) stiker di pratinjau!
                    </p>
                </div>

            </div>

            <!-- ======================================================== -->
            <!-- KOLOM KANAN: LumaBooth Action & Sharing Panel (5 Kolom)  -->
            <!-- ======================================================== -->
            <div class="lg:col-span-5 space-y-5">
                
                <!-- Card 1: QR Code Card (Scan with Phone) -->
                <div class="bg-white rounded-3xl p-6 text-slate-900 shadow-2xl border border-white/20 space-y-4 text-center">
                    <div class="space-y-1">
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 font-mono font-bold text-[10px] uppercase tracking-wider">
                            Instant Mobile Download
                        </span>
                        <h3 class="text-lg sm:text-xl font-black text-slate-950 tracking-tight">Scan Untuk Simpan di HP</h3>
                        <p class="text-xs text-slate-500 max-w-xs mx-auto">
                            Buka kamera HP Anda dan arahkan ke kode QR untuk mengunduh semua foto & GIF.
                        </p>
                    </div>

                    <!-- QR Code Frame -->
                    <div class="bg-slate-100 rounded-2xl p-4 w-44 h-44 mx-auto flex items-center justify-center border border-slate-200 shadow-inner">
                        <div id="download-qrcode" class="p-1 bg-white rounded-xl shadow-sm"></div>
                    </div>

                    <!-- Copy Direct Link Button -->
                    <button type="button" onclick="copySessionLink()" 
                            class="w-full py-2.5 px-4 rounded-full bg-slate-100 hover:bg-slate-200 active:scale-98 text-slate-800 font-bold text-xs flex items-center justify-center gap-2 border border-slate-300 transition">
                        <span>🔗</span>
                        <span>Salin Tautan Galeri</span>
                    </button>
                </div>

                <!-- Card 2: Print Station (Copies Stepper & Print Button) -->
                <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 shadow-2xl space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-base font-black text-white">Cetak Foto (Print)</h4>
                            <p class="text-xs text-slate-400">Kertas Foto Glossy 4R</p>
                        </div>

                        <!-- Stepper [-] [1] [+] -->
                        <div class="flex items-center gap-2 bg-slate-900 px-3 py-1.5 rounded-full border border-white/10 font-mono">
                            <button type="button" onclick="adjustPrintCopies(-1)" class="text-amber-400 hover:text-amber-300 font-black text-base px-1 leading-none">-</button>
                            <span id="print-copies-val" class="text-xs font-bold text-white px-2">1</span>
                            <button type="button" onclick="adjustPrintCopies(1)" class="text-amber-400 hover:text-amber-300 font-black text-base px-1 leading-none">+</button>
                        </div>
                    </div>

                    <button type="button" onclick="triggerLumaBoothPrint()" 
                            class="w-full py-4 px-6 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm uppercase tracking-wider shadow-xl shadow-amber-500/30 transition flex items-center justify-center gap-2">
                        <span>🖶</span>
                        <span>CETAK SEKARANG (<span id="btn-print-copies">1</span> LEMBAR)</span>
                    </button>
                </div>

                <!-- Card 3: Digital Sharing Suite (WhatsApp, Email, Download) -->
                <div class="bg-white/5 backdrop-blur-md rounded-3xl p-5 border border-white/10 shadow-2xl space-y-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Bagikan Hasil Sesi:</span>

                    <div class="grid grid-cols-3 gap-2.5 text-xs font-bold">
                        <!-- WhatsApp Share -->
                        <button type="button" onclick="openWhatsAppModal()" 
                                class="p-3 rounded-2xl bg-emerald-500/10 hover:bg-emerald-500/20 active:scale-95 border border-emerald-500/20 text-emerald-400 flex flex-col items-center justify-center gap-1.5 transition">
                            <span class="text-lg">💬</span>
                            <span class="text-[11px]">WhatsApp</span>
                        </button>

                        <!-- Email Share -->
                        <button type="button" onclick="openEmailModal()" 
                                class="p-3 rounded-2xl bg-sky-500/10 hover:bg-sky-500/20 active:scale-95 border border-sky-500/20 text-sky-400 flex flex-col items-center justify-center gap-1.5 transition">
                            <span class="text-lg">✉️</span>
                            <span class="text-[11px]">Email</span>
                        </button>

                        <!-- Direct HD Download -->
                        <button type="button" onclick="downloadCollageDirectly()" 
                                class="p-3 rounded-2xl bg-white/10 hover:bg-white/20 active:scale-95 border border-white/20 text-white flex flex-col items-center justify-center gap-1.5 transition">
                            <span class="text-lg">📥</span>
                            <span class="text-[11px]">Unduh HD</span>
                        </button>
                    </div>

                    <!-- Retake Action -->
                    <div class="pt-2 border-t border-white/10 flex items-center justify-between" id="sharing-retake-container">
                        @if($retakeEnabled ?? true)
                        <button type="button" id="btn-open-retake" onclick="openRetakeModal()" 
                                class="text-xs font-bold text-slate-400 hover:text-amber-300 flex items-center gap-1.5 transition">
                            <span>↺</span>
                            <span id="btn-retake-label">Foto Ulang (Retake)</span>
                            <span id="btn-retake-counter-badge" class="px-2 py-0.5 rounded-full bg-white/10 text-[10px] text-amber-300 font-mono font-bold">
                                {{ ($retakeLimit ?? 'unlimited') === 'unlimited' ? 'Bebas' : "Sisa {$retakeLimit}x" }}
                            </span>
                        </button>
                        @else
                        <div class="text-xs text-slate-500 flex items-center gap-1.5">
                            <span>↺</span>
                            <span>Foto Ulang Dinonaktifkan</span>
                        </div>
                        @endif

                        <span class="text-[11px] text-slate-500 font-mono">Tersimpan Permanen</span>
                    </div>
                </div>

            </div>

        </main>

    </section>

    <!-- ========================================================================= -->
    <!-- MODALS & DIALOGS (WhatsApp, Email, Retake, Exit, Celebration, Frame Preview)-->
    <!-- ========================================================================= -->

    <!-- Modal 0: Lihat Desain Frame Lengkap -->
    <div id="frame-preview-modal" class="fixed inset-0 bg-black/85 backdrop-blur-md flex items-center justify-center p-4 z-50 hidden animate-in fade-in duration-200">
        <div class="bg-[#0F172A] border border-white/20 rounded-3xl max-w-md w-full p-5 sm:p-6 text-white shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-white/10">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-[#F5BD23]/20 border border-[#F5BD23]/40 text-[#F5BD23] flex items-center justify-center font-bold text-lg">
                        🖼️
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white" id="modal-frame-name">
                            {{ $template['name'] ?? 'Desain Frame' }}
                        </h3>
                        <p class="text-[11px] text-slate-400 font-mono" id="modal-frame-specs">
                            {{ $template['slots'] ?? 4 }} Pose • 1200 x 1800 px • {{ strtoupper($template['category'] ?? 'Kolase') }}
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeFramePreviewModal()" 
                        class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition">
                    ✕
                </button>
            </div>

            <!-- Modal Canvas Preview Container -->
            <div class="flex-1 overflow-y-auto flex items-center justify-center p-3 bg-slate-950/80 rounded-2xl border border-white/10">
                <canvas id="modal-preview-canvas" width="600" height="900" class="max-h-[50vh] sm:max-h-[55vh] w-auto rounded-xl shadow-2xl border border-white/10 object-contain"></canvas>
            </div>

            <!-- Modal Footer Info & Actions -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between text-xs text-slate-300 px-1">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F5BD23]"></span>
                        <span id="modal-frame-color-label">Warna Frame: {{ $selectedColor ?? 'Bawaan' }}</span>
                    </span>
                    <span class="text-slate-400 font-mono text-[11px]">Pratinjau Tata Letak</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeFramePreviewModal()" 
                            class="flex-1 py-3 rounded-full bg-white/10 hover:bg-white/20 active:scale-95 text-xs font-bold text-white transition">
                        Tutup Pratinjau
                    </button>
                    <button type="button" onclick="closeFramePreviewModal(); startAutomaticPhotoSequence();" 
                            class="flex-1 py-3 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/25">
                        Mulai Pose 📸
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal 1: WhatsApp Share -->
    <div id="whatsapp-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-slate-900 border border-white/10 rounded-3xl max-w-sm w-full p-6 text-center space-y-4 text-white shadow-2xl">
            <div class="w-14 h-14 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl mx-auto">
                💬
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-black">Kirim ke WhatsApp</h3>
                <p class="text-xs text-slate-400">Masukkan nomor WhatsApp Anda untuk menerima tautan galeri foto secara instan.</p>
            </div>
            <div class="space-y-2 text-left">
                <label class="text-[11px] font-mono text-slate-400 uppercase">Nomor WhatsApp:</label>
                <input type="tel" id="wa-phone-input" placeholder="Contoh: 08123456789" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-white/20 text-white font-mono text-sm focus:outline-none focus:border-emerald-400">
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="button" onclick="closeWhatsAppModal()" class="flex-1 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold text-slate-300">Batal</button>
                <button type="button" onclick="submitWhatsAppShare()" class="flex-1 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-xs font-black text-slate-950 uppercase tracking-wider">Kirim Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Email Share -->
    <div id="email-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-slate-900 border border-white/10 rounded-3xl max-w-sm w-full p-6 text-center space-y-4 text-white shadow-2xl">
            <div class="w-14 h-14 rounded-full bg-sky-500/20 text-sky-400 flex items-center justify-center text-3xl mx-auto">
                ✉️
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-black">Kirim ke Email</h3>
                <p class="text-xs text-slate-400">Masukkan alamat email Anda untuk menerima salinan digital sesi ini.</p>
            </div>
            <div class="space-y-2 text-left">
                <label class="text-[11px] font-mono text-slate-400 uppercase">Alamat Email:</label>
                <input type="email" id="email-addr-input" placeholder="nama@email.com" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-white/20 text-white font-mono text-sm focus:outline-none focus:border-sky-400">
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="button" onclick="closeEmailModal()" class="flex-1 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold text-slate-300">Batal</button>
                <button type="button" onclick="submitEmailShare()" class="flex-1 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-xs font-black text-slate-950 uppercase tracking-wider">Kirim Salinan</button>
            </div>
        </div>
    </div>

    <!-- Modal 3: Retake Options -->
    <div id="retake-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-slate-900 border border-white/10 rounded-3xl max-w-md w-full p-6 text-center space-y-5 text-white shadow-2xl">
            <div class="w-14 h-14 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-3xl mx-auto">
                ↺
            </div>
            <div class="space-y-1">
                <h3 class="text-xl font-black">Foto Ulang (Retake)</h3>
                <p class="text-xs text-slate-400" id="retake-modal-subtext">Pilih opsi foto ulang yang Anda inginkan:</p>
                <div id="retake-modal-quota-badge" class="inline-block px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 font-mono font-bold text-xs mt-1">
                    {{ ($retakeLimit ?? 'unlimited') === 'unlimited' ? 'Foto ulang tanpa batas' : "Kuota foto ulang: sisa {$retakeLimit} kali" }}
                </div>
            </div>

            <div class="space-y-3" id="retake-modal-options">
                <button type="button" id="btn-retake-all" onclick="retakeEntireSession()" 
                        class="w-full p-4 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 text-left flex items-center justify-between transition">
                    <div>
                        <div class="font-bold text-sm text-white">Ulangi Seluruh {{ $template['slots'] ?? 4 }} Pose</div>
                        <div class="text-xs text-slate-400">Mulai kembali dari Pose 1 sampai Pose {{ $template['slots'] ?? 4 }}.</div>
                    </div>
                    <span class="text-lg font-bold text-amber-400">→</span>
                </button>

                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-left space-y-2">
                    <div class="font-bold text-sm text-white">Ulangi 1 Pose Tertentu Saja:</div>
                    <div class="grid grid-cols-4 gap-2 text-center text-xs font-mono font-bold">
                        @for ($i = 0; $i < ($template['slots'] ?? 4); $i++)
                        <button type="button" onclick="retakeSpecificPose({{ $i }})" class="py-2.5 rounded-xl bg-white/10 hover:bg-amber-400 hover:text-slate-950 transition">Pose {{ $i + 1 }}</button>
                        @endfor
                    </div>
                </div>
            </div>

            <button type="button" onclick="closeRetakeModal()" class="w-full py-3 rounded-full bg-white/10 hover:bg-white/20 text-xs font-bold text-slate-300">Batal</button>
        </div>
    </div>

    <!-- Modal 4: Exit Confirm -->
    <div id="exit-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-slate-900 border border-white/10 rounded-3xl max-w-sm w-full p-6 text-center space-y-4 text-white shadow-2xl">
            <div class="w-12 h-12 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-2xl mx-auto">
                ⚠️
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-black">Batalkan Sesi Foto?</h3>
                <p class="text-xs text-slate-400">Kemajuan sesi Anda saat ini akan dibatalkan dan kembali ke layar awal.</p>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="button" onclick="closeExitConfirmModal()" class="flex-1 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold text-slate-300">Lanjut Sesi</button>
                <a href="{{ route('booth.index') }}" class="flex-1 py-3 rounded-xl bg-rose-500 hover:bg-rose-400 text-xs font-black text-white text-center uppercase tracking-wider">Ya, Keluar</a>
            </div>
        </div>
    </div>

    <!-- Modal 5: Celebration / Done Completion -->
    <div id="celebration-modal" class="fixed inset-0 bg-black/90 backdrop-blur-md flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-slate-900 border-2 border-amber-400/40 rounded-3xl max-w-md w-full p-8 text-center space-y-5 text-white shadow-2xl animate-in zoom-in-95 duration-300">
            <div class="text-6xl animate-bounce">🎉 📸 ✨</div>
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Terima Kasih!</h2>
                <p class="text-sm text-amber-300 font-semibold">Sesi foto Anda telah berhasil diselesaikan.</p>
                <p class="text-xs text-slate-400 max-w-xs mx-auto leading-relaxed">
                    Jangan lupa mengambil hasil cetak Anda pada tray printer & unduh foto digital lewat scan HP Anda!
                </p>
            </div>

            <div class="pt-4 border-t border-white/10">
                <a href="{{ route('booth.index') }}" 
                   class="inline-flex items-center justify-center gap-2 w-full py-4 px-6 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-xs tracking-wider uppercase shadow-xl shadow-amber-500/30 transition">
                    <span>KEMBALI KE STANDBY KIOSK</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- JAVASCRIPT: LUMABOOTH COMPLETE LOGIC (AUDIO, CAMERA, FILTERS, SYNC)       -->
    <!-- ========================================================================= -->
    <script>
        const BOOKING_CODE = "{{ $booking->booking_code }}";
        const GALLERY_URL = "{{ route('gallery.show', $booking->booking_code) }}";
        const SAVE_URL = "{{ route('booth.save', $booking->booking_code) }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const FRAME_DESIGN = "{{ $booking->frame_design ?? 'korean_pastel' }}";
        const TOTAL_POSES = {{ $template['slots'] ?? 4 }};
        const TEMPLATE_CONFIG = @json($template ?? []);

        // Studio Admin Settings (Synchronized from Admin Panel)
        const SESSION_DURATION_MINUTES = {{ $selectedDuration ?? 5 }};
        const RETAKE_ENABLED = {{ ($retakeEnabled ?? true) ? 'true' : 'false' }};
        const RETAKE_LIMIT = "{{ $retakeLimit ?? 'unlimited' }}"; // 'unlimited', '3', '5'
        let retakesUsed = 0;
        let sessionRemainingSeconds = SESSION_DURATION_MINUTES * 60;
        let sessionTimerInterval = null;
        let hasWarnedOneMinute = false;
        let hasWarnedThirtySeconds = false;

        // Global State
        let audioCtx = null;
        let isVoiceEnabled = true;
        let isCameraMirrored = true;
        let currentVideoDeviceIndex = 0;
        let videoDevices = [];
        let isLiveFrameOverlayActive = localStorage.getItem('booth_live_frame_overlay') !== '0'; // Default TRUE (Aktif)

        const defaultSamplePool = [
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80'
        ];

        let capturedPhotos = [];
        for (let i = 0; i < TOTAL_POSES; i++) {
            capturedPhotos.push(defaultSamplePool[i % defaultSamplePool.length]);
        }

        let currentPoseIndex = 0;
        let activeSinglePhotoIndex = 0;
        let gifInterval = null;
        let activeFilterClass = 'filter-original';

        const ALL_FRAME_COLORS = @json($colors ?? []);
        let activeFrameColor = {
            id: "{{ $selectedColor ?? 'original' }}",
            bg: null,
            text: null,
            border: null
        };
        if (activeFrameColor.id && activeFrameColor.id !== 'original') {
            const foundPreset = ALL_FRAME_COLORS.find(c => c.id === activeFrameColor.id);
            if (foundPreset) {
                activeFrameColor.bg = foundPreset.bg;
                activeFrameColor.text = foundPreset.text;
                activeFrameColor.border = foundPreset.border;
            } else if (activeFrameColor.id.startsWith('#')) {
                activeFrameColor.bg = activeFrameColor.id;
                const c = activeFrameColor.id.replace('#', '');
                const r = parseInt(c.substr(0,2), 16) || 0;
                const g = parseInt(c.substr(2,2), 16) || 0;
                const b = parseInt(c.substr(4,2), 16) || 0;
                const brightness = (r * 299 + g * 587 + b * 114) / 1000;
                activeFrameColor.text = brightness > 140 ? '#1E293B' : '#F8FAFC';
                activeFrameColor.border = brightness > 140 ? '#CBD5E1' : '#475569';
            }
        }

        let autoFinishSeconds = 60;
        let autoFinishTimerInterval = null;
        let printCopies = 1;
        let intermissionTimeout = null;

        // ========================================================
        // 1. AUDIO SYNTHESIZER (Web Audio API & Web Speech API)
        // ========================================================
        function initAudioContext() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
        }

        // Play authentic countdown beep tones
        function playCountdownBeep(freq = 880, duration = 0.12) {
            try {
                initAudioContext();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.35, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + duration);
            } catch(e) {}
        }

        // Play celebratory chime fanfare (C5 - E5 - G5 - C6)
        function playCelebrationChime() {
            try {
                initAudioContext();
                const now = audioCtx.currentTime;
                const notes = [
                    { freq: 523.25, time: 0.00, dur: 0.16 }, // C5
                    { freq: 659.25, time: 0.13, dur: 0.16 }, // E5
                    { freq: 783.99, time: 0.26, dur: 0.20 }, // G5
                    { freq: 1046.50, time: 0.42, dur: 0.55 }  // C6
                ];
                notes.forEach(n => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(n.freq, now + n.time);
                    gain.gain.setValueAtTime(0.28, now + n.time);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + n.time + n.dur);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start(now + n.time);
                    osc.stop(now + n.time + n.dur);
                });
            } catch(e) {}
        }

        // Synthesize camera shutter mechanism sound (click-whir)
        function playCameraShutterSound() {
            try {
                initAudioContext();
                const now = audioCtx.currentTime;
                // Noise burst for mechanical shutter
                const bufferSize = audioCtx.sampleRate * 0.15;
                const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
                const data = buffer.getChannelData(0);
                for (let i = 0; i < bufferSize; i++) {
                    data[i] = Math.random() * 2 - 1;
                }
                const noise = audioCtx.createBufferSource();
                noise.buffer = buffer;

                const filter = audioCtx.createBiquadFilter();
                filter.type = 'bandpass';
                filter.frequency.setValueAtTime(1400, now);
                filter.Q.setValueAtTime(2, now);

                const gain = audioCtx.createGain();
                gain.gain.setValueAtTime(0.6, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.14);

                noise.connect(filter);
                filter.connect(gain);
                gain.connect(audioCtx.destination);

                noise.start(now);
                noise.stop(now + 0.15);

                // Add mechanical click ping
                playCountdownBeep(1600, 0.08);
            } catch(e) {}
        }

        // Optional speech synthesis voice
        function speakVoice(text) {
            if (!isVoiceEnabled || !('speechSynthesis' in window)) return;
            try {
                window.speechSynthesis.cancel();
                const utter = new SpeechSynthesisUtterance(text);
                utter.rate = 1.1;
                utter.pitch = 1.0;
                window.speechSynthesis.speak(utter);
            } catch(e) {}
        }

        function toggleAudioVoice() {
            isVoiceEnabled = !isVoiceEnabled;
            const icon = document.getElementById('sound-icon');
            const label = document.getElementById('sound-label');
            if (isVoiceEnabled) {
                icon.innerText = '🔊';
                label.innerText = 'Suara: Aktif';
                showToast('🔊 Suara Pemandu LumaBooth Diaktifkan');
                playCountdownBeep(880, 0.15);
            } else {
                icon.innerText = '🔇';
                label.innerText = 'Suara: Senyap';
                showToast('🔇 Suara Pemandu Dinonaktifkan');
            }
        }

        // ========================================================
        // 1B. STUDIO SESSION TIMER & RETAKE SETTINGS (ADMIN SYNC)
        // ========================================================
        function formatMMSS(totalSeconds) {
            const m = Math.floor(Math.max(0, totalSeconds) / 60);
            const s = Math.max(0, totalSeconds) % 60;
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        function startStudioSessionTimer() {
            if (sessionTimerInterval) clearInterval(sessionTimerInterval);

            updateSessionTimerDisplay();

            sessionTimerInterval = setInterval(() => {
                sessionRemainingSeconds--;
                updateSessionTimerDisplay();

                // 60-second audio & voice warning
                if (sessionRemainingSeconds === 60 && !hasWarnedOneMinute) {
                    hasWarnedOneMinute = true;
                    playCountdownBeep(520, 0.35);
                    speakVoice('One minute remaining');
                    showToast('⏱️ Perhatian: Sisa waktu sesi Anda tinggal 1 menit!', '⚠️');
                }

                // 30-second reminder
                if (sessionRemainingSeconds === 30 && !hasWarnedThirtySeconds) {
                    hasWarnedThirtySeconds = true;
                    playCountdownBeep(660, 0.25);
                    showToast('⏱️ Sisa waktu sesi Anda tinggal 30 detik!', '⚠️');
                }

                // Final 5-second countdown alert beeps
                if (sessionRemainingSeconds <= 5 && sessionRemainingSeconds > 0) {
                    playCountdownBeep(880, 0.1);
                }

                // Time expired
                if (sessionRemainingSeconds <= 0) {
                    clearInterval(sessionTimerInterval);
                    onSessionTimeExpired();
                }
            }, 1000);
        }

        function updateSessionTimerDisplay() {
            const formatted = formatMMSS(sessionRemainingSeconds);
            const displayEl = document.getElementById('session-time-display');
            const headerDisplayEl = document.getElementById('header-time-display');
            const pillEl = document.getElementById('session-timer-pill');
            const dotEl = document.getElementById('session-timer-dot');
            const headerPillEl = document.getElementById('header-timer-pill');

            if (displayEl) displayEl.innerText = formatted;
            if (headerDisplayEl) headerDisplayEl.innerText = formatted;

            // Visual high-urgency alert when <= 60 seconds
            if (sessionRemainingSeconds <= 60) {
                if (pillEl) {
                    pillEl.className = 'flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/25 border border-rose-500 text-rose-300 font-mono font-bold text-xs shadow-lg shadow-rose-500/20 animate-pulse transition-all duration-300';
                }
                if (dotEl) {
                    dotEl.className = 'w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping';
                }
                if (headerPillEl) {
                    headerPillEl.classList.remove('text-amber-300');
                    headerPillEl.classList.add('text-rose-400', 'animate-pulse');
                }
            } else {
                if (pillEl) {
                    pillEl.className = 'flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/15 border border-amber-400/40 text-amber-300 font-mono font-bold text-xs shadow-lg shadow-amber-500/10 transition-all duration-300';
                }
                if (dotEl) {
                    dotEl.className = 'w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse';
                }
                if (headerPillEl) {
                    headerPillEl.classList.remove('text-rose-400', 'animate-pulse');
                    headerPillEl.classList.add('text-amber-300');
                }
            }
        }

        function onSessionTimeExpired() {
            showToast('⏱️ Waktu sesi studio telah selesai!', '⌛');
            playCelebrationChime();

            const captureScreen = document.getElementById('screen-capture');
            const sharingScreen = document.getElementById('screen-sharing');

            if (captureScreen && !captureScreen.classList.contains('hidden')) {
                finishShootingAndDevelopPhotos();
            } else if (sharingScreen && !sharingScreen.classList.contains('hidden')) {
                finishSessionWithCelebration();
            } else {
                finishSessionWithCelebration();
            }
        }

        // ========================================================
        // RETAKE MANAGEMENT & LIMIT ENFORCEMENT
        // ========================================================
        function canRetake() {
            if (!RETAKE_ENABLED) return false;
            if (RETAKE_LIMIT === 'unlimited') return true;
            const max = parseInt(RETAKE_LIMIT, 10) || 0;
            return retakesUsed < max;
        }

        function getRemainingRetakes() {
            if (!RETAKE_ENABLED) return 0;
            if (RETAKE_LIMIT === 'unlimited') return 'unlimited';
            const max = parseInt(RETAKE_LIMIT, 10) || 0;
            return Math.max(0, max - retakesUsed);
        }

        function recordRetake() {
            retakesUsed++;
            updateRetakeUI();
        }

        function updateRetakeUI() {
            const remaining = getRemainingRetakes();
            const allow = canRetake();

            // 1. Flashback Overlay Retake Button
            const fbBtn = document.getElementById('flashback-retake-btn');
            if (fbBtn) {
                if (!allow) {
                    fbBtn.classList.add('opacity-40', 'pointer-events-none', 'cursor-not-allowed');
                    fbBtn.title = 'Batas foto ulang telah habis';
                } else {
                    fbBtn.classList.remove('opacity-40', 'pointer-events-none', 'cursor-not-allowed');
                }
            }

            // 2. Sharing Screen Retake Button & Badge
            const shareBtn = document.getElementById('btn-open-retake');
            const counterBadge = document.getElementById('btn-retake-counter-badge');
            if (shareBtn) {
                if (!allow) {
                    shareBtn.classList.add('opacity-40', 'pointer-events-none', 'cursor-not-allowed');
                    if (counterBadge) counterBadge.innerText = 'Habis';
                } else {
                    shareBtn.classList.remove('opacity-40', 'pointer-events-none', 'cursor-not-allowed');
                    if (counterBadge) {
                        counterBadge.innerText = remaining === 'unlimited' ? 'Bebas' : `Sisa ${remaining}x`;
                    }
                }
            }

            // 3. Retake Modal Quota Badge & Options
            const modalBadge = document.getElementById('retake-modal-quota-badge');
            const btnRetakeAll = document.getElementById('btn-retake-all');
            if (modalBadge) {
                if (!allow) {
                    modalBadge.className = 'inline-block px-3 py-1 rounded-full bg-rose-500/20 border border-rose-400/30 text-rose-300 font-mono font-bold text-xs mt-1';
                    modalBadge.innerText = 'Batas foto ulang telah habis (0 tersisa)';
                } else {
                    modalBadge.className = 'inline-block px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 font-mono font-bold text-xs mt-1';
                    modalBadge.innerText = remaining === 'unlimited' ? 'Foto ulang tanpa batas' : `Kuota foto ulang: sisa ${remaining} kali`;
                }
            }
            if (btnRetakeAll) {
                if (!allow) {
                    btnRetakeAll.classList.add('opacity-40', 'pointer-events-none');
                } else {
                    btnRetakeAll.classList.remove('opacity-40', 'pointer-events-none');
                }
            }
        }

        // ========================================================
        // 2. CAMERA INITIALIZATION & MANAGEMENT
        // ========================================================
        async function initCamera() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                videoDevices = devices.filter(d => d.kind === 'videoinput');
                
                await startCameraStream();
            } catch(err) {
                console.log("No physical camera detected, studio fallback mode active.", err);
            }
        }

        async function startCameraStream() {
            try {
                const constraints = {
                    video: {
                        width: { ideal: 1920 },
                        height: { ideal: 1080 },
                        facingMode: "user"
                    },
                    audio: false
                };

                if (videoDevices.length > 0 && videoDevices[currentVideoDeviceIndex]) {
                    constraints.video.deviceId = { exact: videoDevices[currentVideoDeviceIndex].deviceId };
                }

                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                const videoEl = document.getElementById('viewfinder-video');
                const liveEl = document.getElementById('live-video');
                videoEl.srcObject = stream;
                liveEl.srcObject = stream;
            } catch(err) {
                console.log("Camera stream fallback active.");
            }
        }

        function toggleCameraMirror() {
            isCameraMirrored = !isCameraMirrored;
            const videoEl = document.getElementById('viewfinder-video');
            if (isCameraMirrored) {
                videoEl.classList.add('-scale-x-100');
                showToast('🪞 Tampilan Cermin (Mirrored)');
            } else {
                videoEl.classList.remove('-scale-x-100');
                showToast('📷 Tampilan Normal (Un-mirrored)');
            }
        }

        async function cycleCameraDevices() {
            if (videoDevices.length <= 1) {
                showToast('Hanya 1 kamera terdeteksi pada perangkat ini.');
                return;
            }
            currentVideoDeviceIndex = (currentVideoDeviceIndex + 1) % videoDevices.length;
            await startCameraStream();
            showToast(`Berganti ke Kamera ${currentVideoDeviceIndex + 1}`);
        }

        // Spacebar trigger support for external photobooth buttons
        window.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && !document.getElementById('touch-start-overlay').classList.contains('hidden')) {
                e.preventDefault();
                startAutomaticPhotoSequence();
            }
        });

        // ========================================================
        // 3. 4-SHOT AUTOMATIC CAPTURE SEQUENCE (LumaBooth Flow)
        // ========================================================
        function startAutomaticPhotoSequence() {
            initAudioContext();
            document.getElementById('touch-start-overlay').classList.add('hidden');
            currentPoseIndex = 0;
            executeSinglePoseCountdown();
        }

        function executeSinglePoseCountdown() {
            if (currentPoseIndex >= TOTAL_POSES) {
                finishShootingAndDevelopPhotos();
                return;
            }

            // Update Header & Counter
            document.getElementById('pose-counter-num').innerText = currentPoseIndex + 1;
            document.getElementById('countdown-sub').innerText = `Pose ${currentPoseIndex + 1} dari ${TOTAL_POSES}`;

            // Active Slot Highlight
            highlightActiveThumbSlot(currentPoseIndex);
            updateLiveFrameOverlayPose(currentPoseIndex);

            const countdownContainer = document.getElementById('countdown-container');
            const digitEl = document.getElementById('countdown-digit');
            const circleEl = document.getElementById('countdown-circle');
            const labelEl = document.getElementById('countdown-label');

            countdownContainer.classList.remove('hidden');
            document.getElementById('flashback-overlay').classList.add('hidden');

            let count = 3;
            digitEl.innerText = count;
            labelEl.innerText = 'BERSIAP POSE!';
            circleEl.style.strokeDashoffset = '0';
            playCountdownBeep(880, 0.15);
            speakVoice('Three');

            const timer = setInterval(() => {
                count--;
                if (count === 2) {
                    digitEl.innerText = '2';
                    circleEl.style.strokeDashoffset = '88';
                    labelEl.innerText = 'PASANG GAYA!';
                    playCountdownBeep(880, 0.15);
                    speakVoice('Two');
                } else if (count === 1) {
                    digitEl.innerText = '1';
                    circleEl.style.strokeDashoffset = '176';
                    labelEl.innerText = 'SENYUM! 😃';
                    playCountdownBeep(980, 0.18);
                    speakVoice('One');
                } else {
                    clearInterval(timer);
                    digitEl.innerText = '📸';
                    circleEl.style.strokeDashoffset = '264';
                    labelEl.innerText = 'CHEESE!';
                    playCountdownBeep(1320, 0.25);
                    speakVoice('Smile');

                    setTimeout(() => {
                        snapPhoto(currentPoseIndex);
                        countdownContainer.classList.add('hidden');
                        
                        // LumaBooth Intermission Flashback (~2.5s)
                        showIntermissionFlashback(currentPoseIndex);
                    }, 350);
                }
            }, 1000);
        }

        function snapPhoto(slotIndex) {
            // 1. Authentic Strobe Flash Animation
            const flash = document.getElementById('camera-flash');
            flash.classList.remove('flash-active');
            void flash.offsetWidth;
            flash.classList.add('flash-active');

            // 2. Camera Mechanical Shutter Audio
            playCameraShutterSound();

            // 3. Capture Frame from Viewfinder Video to Canvas
            const video = document.getElementById('viewfinder-video');
            const canvas = document.getElementById('capture-canvas');
            const ctx = canvas.getContext('2d');
            
            try {
                if (video && video.videoWidth > 0) {
                    ctx.save();
                    if (isCameraMirrored) {
                        ctx.translate(canvas.width, 0);
                        ctx.scale(-1, 1);
                    }
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    ctx.restore();
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                    capturedPhotos[slotIndex] = dataUrl;
                }
            } catch(e) {
                console.log("Canvas snapshot fallback active.");
            }

            // 4. Update Bottom Thumbnail Slot
            updateThumbSlotElement(slotIndex);
        }

        function updateThumbSlotElement(slotIndex) {
            const slotEl = document.getElementById(`thumb-slot-${slotIndex}`);
            if (slotEl && capturedPhotos[slotIndex]) {
                slotEl.innerHTML = `
                    <img src="${capturedPhotos[slotIndex]}" class="w-full h-full object-cover">
                    <span class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-emerald-500 text-slate-950 font-black text-[10px] flex items-center justify-center shadow">✓</span>
                `;
                slotEl.className = 'flex-1 max-w-[120px] aspect-square rounded-2xl bg-white/10 border-2 border-[#F5BD23] flex items-center justify-center overflow-hidden relative shadow-lg ring-2 ring-amber-400/40 animate-in zoom-in-90 duration-300';
            }
        }

        function highlightActiveThumbSlot(slotIndex) {
            for (let i = 0; i < TOTAL_POSES; i++) {
                const el = document.getElementById(`thumb-slot-${i}`);
                if (!el) continue;
                if (i === slotIndex) {
                    el.classList.add('border-amber-400', 'ring-2', 'ring-amber-400/50');
                } else {
                    el.classList.remove('border-amber-400', 'ring-2', 'ring-amber-400/50');
                }
            }
        }

        // Intermission Flashback Screen (Shows taken photo with Retake option)
        function showIntermissionFlashback(slotIndex) {
            const overlay = document.getElementById('flashback-overlay');
            const img = document.getElementById('flashback-img');
            const poseNum = document.getElementById('flashback-pose-num');
            const timerText = document.getElementById('flashback-timer-text');

            img.src = capturedPhotos[slotIndex];
            poseNum.innerText = slotIndex + 1;
            overlay.classList.remove('hidden');
            updateRetakeUI();

            let countdownRem = 3;
            timerText.innerText = slotIndex < (TOTAL_POSES - 1) 
                ? `Pose berikutnya dimulai dalam ${countdownRem} detik...` 
                : `Menyelesaikan ${TOTAL_POSES} pose dalam ${countdownRem} detik...`;

            intermissionTimeout = setInterval(() => {
                countdownRem--;
                if (countdownRem > 0) {
                    timerText.innerText = slotIndex < (TOTAL_POSES - 1) 
                        ? `Pose berikutnya dimulai dalam ${countdownRem} detik...` 
                        : `Menyelesaikan ${TOTAL_POSES} pose dalam ${countdownRem} detik...`;
                } else {
                    clearInterval(intermissionTimeout);
                    overlay.classList.add('hidden');
                    currentPoseIndex++;
                    executeSinglePoseCountdown();
                }
            }, 1000);
        }

        function retakeCurrentPoseImmediately() {
            if (!canRetake()) {
                showToast('⚠️ Batas kuota foto ulang telah habis.', '⚠️');
                return;
            }
            recordRetake();
            if (intermissionTimeout) clearInterval(intermissionTimeout);
            document.getElementById('flashback-overlay').classList.add('hidden');
            showToast(`↺ Mengulang Pose ${currentPoseIndex + 1}`);
            executeSinglePoseCountdown();
        }

        function skipIntermissionImmediately() {
            if (intermissionTimeout) clearInterval(intermissionTimeout);
            document.getElementById('flashback-overlay').classList.add('hidden');
            currentPoseIndex++;
            executeSinglePoseCountdown();
        }

        // ========================================================
        // 4. DEVELOPING / PROCESSING SCREEN & BACKEND AUTO-SYNC
        // ========================================================
        function finishShootingAndDevelopPhotos() {
            document.getElementById('screen-capture').classList.add('hidden');
            document.getElementById('screen-processing').classList.remove('hidden');

            const bar = document.getElementById('processing-bar');
            const pct = document.getElementById('processing-pct');

            let progress = 0;
            const progInterval = setInterval(() => {
                progress += 5;
                if (progress <= 100) {
                    bar.style.width = `${progress}%`;
                    pct.innerText = `${progress}%`;
                } else {
                    clearInterval(progInterval);
                    
                    // Render composite collage and silently sync to backend
                    renderFrameComposite();
                    syncSessionToBackend();

                    // Smooth reveal sharing screen
                    setTimeout(() => {
                        document.getElementById('screen-processing').classList.add('hidden');
                        document.getElementById('screen-sharing').classList.remove('hidden');
                        initSharingScreen();
                    }, 400);
                }
            }, 75);
        }

        // Save session to Laravel storage & database silently via AJAX
        async function syncSessionToBackend() {
            try {
                const canvas = document.getElementById('collage-canvas');
                const collageDataUrl = canvas.toDataURL('image/jpeg', 0.95);

                const response = await fetch(SAVE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        photos: capturedPhotos,
                        collage_image: collageDataUrl,
                        frame_id: FRAME_DESIGN
                    })
                });

                const result = await response.json();
                if (result.success) {
                    console.log("Session successfully persisted to server storage!");
                }
            } catch(e) {
                console.log("Background sync completed with offline backup.");
            }
        }

        // ========================================================
        // 5. LUMABOOTH SHARING & REVIEW STATION
        // ========================================================
        function initSharingScreen() {
            // Generate QR Code with Standalone Library
            generateSharingQrCode(GALLERY_URL);

            // Populate single photo thumbnails
            for (let i = 0; i < TOTAL_POSES; i++) {
                const el = document.getElementById(`single-thumb-${i}`);
                if (el && capturedPhotos[i]) {
                    el.src = capturedPhotos[i];
                }
            }

            // Set initial views
            selectSinglePhoto(0);
            switchPreviewTab('print');

            // Start Auto-Finish Timer (LumaBooth Kiosk timeout)
            startAutoFinishTimer();
        }

        function switchPreviewTab(tab) {
            if (gifInterval) clearInterval(gifInterval);

            const tabPrint = document.getElementById('tab-print');
            const tabSingle = document.getElementById('tab-single');
            const tabGif = document.getElementById('tab-gif');

            const printImg = document.getElementById('preview-print-img');
            const singleImg = document.getElementById('preview-single-img');
            const gifImg = document.getElementById('preview-gif-img');
            const singleBadge = document.getElementById('single-photo-badge');
            const singleStrip = document.getElementById('single-photo-strip');

            // Reset tab styling
            [tabPrint, tabSingle, tabGif].forEach(t => {
                t.className = 'flex items-center gap-1.5 pb-2 text-slate-400 hover:text-white border-b-2 border-transparent transition';
            });
            printImg.classList.add('hidden');
            singleImg.classList.add('hidden');
            gifImg.classList.add('hidden');
            singleBadge.classList.add('hidden');
            singleStrip.classList.add('hidden');

            if (tab === 'print') {
                tabPrint.className = 'flex items-center gap-1.5 pb-2 text-[#F5BD23] border-b-2 border-[#F5BD23] font-black transition';
                printImg.classList.remove('hidden');
            } else if (tab === 'single') {
                tabSingle.className = 'flex items-center gap-1.5 pb-2 text-[#F5BD23] border-b-2 border-[#F5BD23] font-black transition';
                singleImg.classList.remove('hidden');
                singleBadge.classList.remove('hidden');
                singleStrip.classList.remove('hidden');
                singleImg.src = capturedPhotos[activeSinglePhotoIndex];
            } else if (tab === 'gif') {
                tabGif.className = 'flex items-center gap-1.5 pb-2 text-[#F5BD23] border-b-2 border-[#F5BD23] font-black transition';
                gifImg.classList.remove('hidden');

                // High speed continuous boomerang/GIF loop
                let gifIdx = 0;
                gifImg.src = capturedPhotos[0];
                gifInterval = setInterval(() => {
                    gifIdx = (gifIdx + 1) % TOTAL_POSES;
                    gifImg.src = capturedPhotos[gifIdx];
                }, 380);
            }
        }

        function selectSinglePhoto(index) {
            activeSinglePhotoIndex = index;
            document.getElementById('preview-single-img').src = capturedPhotos[index];
            document.getElementById('single-photo-num').innerText = index + 1;

            // Highlight border
            for (let i = 0; i < TOTAL_POSES; i++) {
                const box = document.getElementById(`single-box-${i}`);
                if (!box) continue;
                if (i === index) {
                    box.className = 'w-16 h-16 shrink-0 rounded-xl overflow-hidden cursor-pointer border-2 border-[#F5BD23] ring-2 ring-amber-400/40 transition';
                } else {
                    box.className = 'w-16 h-16 shrink-0 rounded-xl overflow-hidden cursor-pointer border-2 border-transparent hover:border-slate-400 transition';
                }
            }
        }

        // ========================================================
        // 4B. LUMABOOTH DIGITAL PROPS & STICKERS ENGINE
        // ========================================================
        let activeStickers = [];
        let baseStripCanvas = null;

        const ALL_STICKERS_DEF = [
            // Topi & Props
            { id: 'crown', label: 'Mahkota', char: '👑', type: 'emoji', size: 68, cat: 'props' },
            { id: 'bunny_ears', label: 'Kelinci', char: '🐰', type: 'emoji', size: 68, cat: 'props' },
            { id: 'cat_ears', label: 'Kucing', char: '🐱', type: 'emoji', size: 68, cat: 'props' },
            { id: 'bear', label: 'Teddy Bear', char: '🧸', type: 'emoji', size: 68, cat: 'props' },
            { id: 'party_hat', label: 'Topi Pesta', char: '🥳', type: 'emoji', size: 68, cat: 'props' },
            { id: 'angel', label: 'Malaikat Halo', char: '😇', type: 'emoji', size: 68, cat: 'props' },
            { id: 'cherry', label: 'Ceri', char: '🍒', type: 'emoji', size: 65, cat: 'props' },
            { id: 'butterfly', label: 'Kupu-kupu', char: '🦋', type: 'emoji', size: 65, cat: 'props' },
            // Kacamata & Aksesoris
            { id: 'sunglasses', label: 'Kacamata Hitam', char: '🕶️', type: 'emoji', size: 70, cat: 'glasses' },
            { id: 'star_glasses', label: 'Bintang', char: '🤩', type: 'emoji', size: 70, cat: 'glasses' },
            { id: 'nerd_glasses', label: 'Kacamata Bulat', char: '👓', type: 'emoji', size: 70, cat: 'glasses' },
            { id: 'cool_swag', label: 'Swag', char: '😎', type: 'emoji', size: 70, cat: 'glasses' },
            { id: 'pixel_deal', label: 'Pixel Glasses', char: '👾', type: 'emoji', size: 65, cat: 'glasses' },
            // Hati & Efek
            { id: 'sparkles', label: 'Kilau Sparkle', char: '✨', type: 'emoji', size: 65, cat: 'cute' },
            { id: 'sparkling_heart', label: 'Sparkle Heart', char: '💖', type: 'emoji', size: 65, cat: 'cute' },
            { id: 'red_heart', label: 'Love', char: '❤️', type: 'emoji', size: 65, cat: 'cute' },
            { id: 'pink_ribbon', label: 'Pita Pink', char: '🎀', type: 'emoji', size: 65, cat: 'cute' },
            { id: 'flower_blossom', label: 'Sakura', char: '🌸', type: 'emoji', size: 65, cat: 'cute' },
            { id: 'sunflower', label: 'Bunga Matahari', char: '🌻', type: 'emoji', size: 65, cat: 'cute' },
            { id: 'fire', label: 'Api Semangat', char: '🔥', type: 'emoji', size: 65, cat: 'cute' },
            { id: 'star_dazzle', label: 'Bintang', char: '⭐', type: 'emoji', size: 65, cat: 'cute' },
            // K-Photobooth Text Badges
            { id: 'badge_besties', label: 'BESTIES ♡', text: 'BESTIES ♡', type: 'badge', bg: '#F43F5E', color: '#FFFFFF', size: 36, cat: 'badge' },
            { id: 'badge_love', label: 'FOREVER LOVE', text: 'FOREVER ♡', type: 'badge', bg: '#EC4899', color: '#FFFFFF', size: 36, cat: 'badge' },
            { id: 'badge_daebak', label: 'DAEBAK (대박)', text: '대박! ✨', type: 'badge', bg: '#8B5CF6', color: '#FFFFFF', size: 36, cat: 'badge' },
            { id: 'badge_kpop', label: 'K-HEART (하트)', text: '하트 🫰', type: 'badge', bg: '#3B82F6', color: '#FFFFFF', size: 36, cat: 'badge' },
            { id: 'badge_vibe', label: 'GOOD VIBES', text: 'GOOD VIBES ✦', type: 'badge', bg: '#10B981', color: '#FFFFFF', size: 36, cat: 'badge' },
            { id: 'badge_birthday', label: 'HAPPY B-DAY', text: 'HAPPY B-DAY 🎂', type: 'badge', bg: '#F59E0B', color: '#09090B', size: 36, cat: 'badge' }
        ];

        function renderStickersTray(category = 'all') {
            const tray = document.getElementById('stickers-tray');
            if (!tray) return;

            const filtered = category === 'all' 
                ? ALL_STICKERS_DEF 
                : ALL_STICKERS_DEF.filter(s => s.cat === category);

            tray.innerHTML = filtered.map(s => {
                if (s.type === 'emoji') {
                    return `
                        <button type="button" onclick="addSticker('${s.id}')"
                                class="shrink-0 flex flex-col items-center justify-center p-2 rounded-xl bg-white/5 hover:bg-white/15 active:scale-95 border border-white/10 hover:border-amber-400/50 transition group"
                                title="${s.label}">
                            <span class="text-2xl group-hover:scale-125 transition-transform duration-150">${s.char}</span>
                            <span class="text-[9px] text-slate-400 font-mono mt-1 whitespace-nowrap max-w-[55px] truncate">${s.label}</span>
                        </button>
                    `;
                } else {
                    return `
                        <button type="button" onclick="addSticker('${s.id}')"
                                class="shrink-0 flex flex-col items-center justify-center px-3 py-2 rounded-xl bg-white/5 hover:bg-white/15 active:scale-95 border border-white/10 hover:border-amber-400/50 transition group"
                                title="${s.label}">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black whitespace-nowrap shadow-sm group-hover:scale-105 transition-transform" style="background-color: ${s.def ? s.def.bg : s.bg}; color: ${s.def ? s.def.color : s.color};">
                                ${s.text}
                            </span>
                            <span class="text-[9px] text-slate-400 font-mono mt-1 whitespace-nowrap max-w-[65px] truncate">${s.label}</span>
                        </button>
                    `;
                }
            }).join('');
        }

        function switchStickerCat(cat) {
            const catKeys = ['all', 'props', 'glasses', 'cute', 'badge'];
            catKeys.forEach(k => {
                const btn = document.getElementById(`stk-cat-${k}`);
                if (!btn) return;
                if (k === cat) {
                    btn.className = 'stk-cat-btn px-2.5 py-1 rounded-lg bg-[#F5BD23] text-slate-950 whitespace-nowrap shadow-sm font-black';
                } else {
                    btn.className = 'stk-cat-btn px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 whitespace-nowrap border border-white/10 font-medium';
                }
            });
            renderStickersTray(cat);
        }

        function addSticker(stickerId) {
            const item = ALL_STICKERS_DEF.find(s => s.id === stickerId);
            if (!item) return;

            const offsetX = (Math.random() * 0.16) - 0.08;
            const offsetY = (Math.random() * 0.16) - 0.08;

            const newSticker = {
                id: Date.now() + Math.floor(Math.random() * 1000),
                def: item,
                x: Math.max(0.15, Math.min(0.85, 0.5 + offsetX)),
                y: Math.max(0.15, Math.min(0.85, 0.45 + offsetY)),
                scale: 1.0
            };

            activeStickers.push(newSticker);
            renderActiveStickersDOM();
            bakeStickersToCanvas();
            updateStickerCountUI();
            showToast(`Stiker ${item.label} ditambahkan!`, item.char || '✨');
        }

        function deleteSticker(id) {
            activeStickers = activeStickers.filter(s => s.id !== id);
            renderActiveStickersDOM();
            bakeStickersToCanvas();
            updateStickerCountUI();
        }

        function adjustStickerScale(id, delta) {
            const s = activeStickers.find(item => item.id === id);
            if (!s) return;
            s.scale = Math.max(0.6, Math.min(2.5, +(s.scale + delta).toFixed(1)));
            renderActiveStickersDOM();
            bakeStickersToCanvas();
        }

        function clearAllStickers() {
            if (activeStickers.length === 0) return;
            activeStickers = [];
            renderActiveStickersDOM();
            bakeStickersToCanvas();
            updateStickerCountUI();
            showToast('Semua stiker telah dihapus', '🗑️');
        }

        function updateStickerCountUI() {
            const countEl = document.getElementById('sticker-count-badge');
            const clearBtn = document.getElementById('btn-clear-stickers');
            if (countEl) countEl.innerText = activeStickers.length;
            if (clearBtn) {
                if (activeStickers.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
        }

        function renderActiveStickersDOM() {
            const overlay = document.getElementById('stickers-overlay');
            if (!overlay) return;

            overlay.innerHTML = '';

            activeStickers.forEach(s => {
                const itemEl = document.createElement('div');
                itemEl.className = 'sticker-item group';
                itemEl.style.left = `${(s.x * 100).toFixed(2)}%`;
                itemEl.style.top = `${(s.y * 100).toFixed(2)}%`;

                let visualHtml = '';
                if (s.def.type === 'emoji') {
                    const fontPx = Math.round(38 * s.scale);
                    visualHtml = `<span style="font-size: ${fontPx}px; line-height: 1; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));">${s.def.char}</span>`;
                } else {
                    const fontPx = Math.round(12 * s.scale);
                    const padX = Math.round(10 * s.scale);
                    const padY = Math.round(4 * s.scale);
                    visualHtml = `
                        <span class="rounded-full font-black whitespace-nowrap shadow-lg border border-white/60 tracking-wider uppercase"
                              style="background-color: ${s.def.bg}; color: ${s.def.color}; font-size: ${fontPx}px; padding: ${padY}px ${padX}px;">
                            ${s.def.text}
                        </span>
                    `;
                }

                itemEl.innerHTML = `
                    ${visualHtml}
                    <button type="button" class="sticker-ctrl-btn" title="Hapus Stiker"
                            onpointerdown="event.stopPropagation()" onclick="event.stopPropagation(); deleteSticker(${s.id})">✕</button>
                    <button type="button" class="sticker-resize-btn" title="Perbesar (+)"
                            onpointerdown="event.stopPropagation()" onclick="event.stopPropagation(); adjustStickerScale(${s.id}, 0.2)">+</button>
                `;

                // Interactive Pointer Dragging
                let isDragging = false;
                let startClientX = 0, startClientY = 0;
                let initX = s.x, initY = s.y;

                itemEl.addEventListener('pointerdown', (e) => {
                    if (e.target.closest('.sticker-ctrl-btn') || e.target.closest('.sticker-resize-btn')) return;
                    isDragging = true;
                    startClientX = e.clientX;
                    startClientY = e.clientY;
                    initX = s.x;
                    initY = s.y;
                    itemEl.setPointerCapture(e.pointerId);
                    itemEl.classList.add('selected');
                });

                itemEl.addEventListener('pointermove', (e) => {
                    if (!isDragging) return;
                    const rect = overlay.getBoundingClientRect();
                    if (!rect.width || !rect.height) return;
                    const dx = (e.clientX - startClientX) / rect.width;
                    const dy = (e.clientY - startClientY) / rect.height;
                    s.x = Math.max(0.04, Math.min(0.96, initX + dx));
                    s.y = Math.max(0.04, Math.min(0.96, initY + dy));
                    itemEl.style.left = `${(s.x * 100).toFixed(2)}%`;
                    itemEl.style.top = `${(s.y * 100).toFixed(2)}%`;
                });

                const endDrag = (e) => {
                    if (!isDragging) return;
                    isDragging = false;
                    try { itemEl.releasePointerCapture(e.pointerId); } catch(err) {}
                    itemEl.classList.remove('selected');
                    bakeStickersToCanvas();
                };

                itemEl.addEventListener('pointerup', endDrag);
                itemEl.addEventListener('pointercancel', endDrag);

                overlay.appendChild(itemEl);
            });
        }

        function bakeStickersToCanvas() {
            const canvas = document.getElementById('collage-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            if (baseStripCanvas) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(baseStripCanvas, 0, 0);
            }

            activeStickers.forEach(s => {
                const cx = s.x * canvas.width;
                const cy = s.y * canvas.height;

                ctx.save();
                ctx.translate(cx, cy);

                if (s.def.type === 'emoji') {
                    const fontSize = Math.round((s.def.size || 68) * (canvas.width / 400) * s.scale);
                    ctx.font = `${fontSize}px "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", sans-serif`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.4)';
                    ctx.shadowBlur = 12;
                    ctx.shadowOffsetX = 2;
                    ctx.shadowOffsetY = 6;
                    ctx.fillText(s.def.char, 0, 0);
                } else if (s.def.type === 'badge') {
                    const fontSize = Math.round((s.def.size || 34) * (canvas.width / 400) * s.scale);
                    ctx.font = `bold ${fontSize}px sans-serif`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    const metrics = ctx.measureText(s.def.text);
                    const padX = 26 * s.scale;
                    const padY = 14 * s.scale;
                    const boxW = metrics.width + padX * 2;
                    const boxH = fontSize + padY * 2;
                    const radius = boxH / 2;

                    ctx.shadowColor = 'rgba(0, 0, 0, 0.45)';
                    ctx.shadowBlur = 12;
                    ctx.shadowOffsetX = 0;
                    ctx.shadowOffsetY = 6;

                    ctx.fillStyle = s.def.bg;
                    ctx.beginPath();
                    if (ctx.roundRect) {
                        ctx.roundRect(-boxW / 2, -boxH / 2, boxW, boxH, radius);
                    } else {
                        ctx.rect(-boxW / 2, -boxH / 2, boxW, boxH);
                    }
                    ctx.fill();

                    ctx.strokeStyle = '#FFFFFF';
                    ctx.lineWidth = 4 * s.scale;
                    ctx.stroke();

                    ctx.shadowColor = 'transparent';
                    ctx.fillStyle = s.def.color;
                    ctx.fillText(s.def.text, 0, 0);
                }

                ctx.restore();
            });

            const bakedUrl = canvas.toDataURL('image/jpeg', 0.95);
            const printImg = document.getElementById('print-img');
            if (printImg) printImg.src = bakedUrl;
        }

        // Apply Realtime Photo Filter (LumaBooth Style)
        function applyPhotoFilter(filterClass, filterLabel) {
            activeFilterClass = filterClass;
            document.getElementById('active-filter-label').innerText = filterLabel;

            // Apply CSS class to preview images
            const images = ['preview-print-img', 'preview-single-img', 'preview-gif-img'];
            images.forEach(id => {
                const el = document.getElementById(id);
                el.className = el.className.replace(/filter-\w+/g, '');
                el.classList.add(filterClass);
            });

            // Update active filter button
            const filterButtons = ['original', 'beauty', 'peach', 'bw', 'vintage', 'warm', 'cyber'];
            filterButtons.forEach(f => {
                const btn = document.getElementById(`filter-btn-${f}`);
                if (!btn) return;
                if (filterClass === `filter-${f}`) {
                    btn.classList.add('border-[#F5BD23]', 'bg-white/10');
                    btn.classList.remove('border-transparent');
                } else {
                    btn.classList.remove('border-[#F5BD23]', 'bg-white/10');
                    btn.classList.add('border-transparent');
                }
            });

            // Redraw composite canvas with chosen filter for printing/download
            renderFrameComposite();
            showToast(`Filter: ${filterLabel}`);
        }

        // Apply Realtime Frame Color
        function applyFrameColor(colId, colName, bg, text, border) {
            activeFrameColor = {
                id: colId,
                bg: (bg && bg !== 'null') ? bg : null,
                text: (text && text !== 'null') ? text : null,
                border: (border && border !== 'null') ? border : null
            };

            const labelEl = document.getElementById('active-frame-color-label');
            if (labelEl) labelEl.innerText = colName;

            document.querySelectorAll('.frame-col-btn').forEach(btn => {
                btn.classList.remove('border-[#F5BD23]', 'bg-white/15');
                btn.classList.add('border-transparent');
            });
            const activeBtn = document.getElementById('btn-frame-col-' + colId);
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent');
                activeBtn.classList.add('border-[#F5BD23]', 'bg-white/15');
            }

            renderFrameComposite();
            showToast(`Warna Frame: ${colName}`, '🎨');
        }

        function onCustomSessionColor(hex) {
            const c = hex.replace('#', '');
            const r = parseInt(c.substr(0,2), 16) || 0;
            const g = parseInt(c.substr(2,2), 16) || 0;
            const b = parseInt(c.substr(4,2), 16) || 0;
            const brightness = (r * 299 + g * 587 + b * 114) / 1000;
            const textColor = brightness > 140 ? '#1E293B' : '#F8FAFC';
            const borderColor = brightness > 140 ? '#CBD5E1' : '#475569';
            applyFrameColor('custom', `Kustom (${hex})`, hex, textColor, borderColor);
        }

        // Render Canvas Composite Photostrip (Dynamic 8, 6, 4, 3, 2, 1 Photobooth Layouts)
        function renderFrameComposite() {
            const canvas = document.getElementById('collage-canvas');
            const ctx = canvas.getContext('2d');
            const tId = TEMPLATE_CONFIG.id || 'classic-4-grid';
            const slots = TOTAL_POSES || 4;

            canvas.width = 1200;
            canvas.height = 1800;

            // 1. Background Fill & Colors
            const bgColor = activeFrameColor.bg || TEMPLATE_CONFIG.bg_color || '#FFFFFF';
            const textColor = activeFrameColor.text || TEMPLATE_CONFIG.text_color || '#1E293B';
            const accentColor = activeFrameColor.border || TEMPLATE_CONFIG.accent || '#F5BD23';
            const tagline = TEMPLATE_CONFIG.tagline || 'POTRET DIRI • SELF STUDIO';
            const footer = TEMPLATE_CONFIG.footer || 'UNLIMITED FUN • SWEET MEMORIES';

            ctx.fillStyle = bgColor;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // 2. Set Filter on context if supported
            let canvasFilter = 'none';
            if (activeFilterClass === 'filter-beauty') canvasFilter = 'contrast(106%) brightness(112%) saturate(114%)';
            else if (activeFilterClass === 'filter-peach') canvasFilter = 'sepia(16%) saturate(145%) hue-rotate(345deg) brightness(108%)';
            else if (activeFilterClass === 'filter-bw') canvasFilter = 'grayscale(100%) contrast(130%) brightness(105%)';
            else if (activeFilterClass === 'filter-vintage') canvasFilter = 'sepia(50%) contrast(110%) saturate(130%)';
            else if (activeFilterClass === 'filter-warm') canvasFilter = 'sepia(25%) saturate(150%) hue-rotate(-12deg)';
            else if (activeFilterClass === 'filter-cyber') canvasFilter = 'contrast(130%) saturate(170%) hue-rotate(170deg)';

            // 3. Compute Photo Slot Coordinates Based on Layout
            let slotRects = [];

            if (tId === 'korean-8-strip' && slots === 8) {
                // Two Korean 4-Strips side by side (Duo Strip)
                const stripW = 500;
                const topY = 110;
                const photoH = 340;
                const gapY = 20;

                // Left Strip (Poses 0..3)
                for (let i = 0; i < 4; i++) {
                    slotRects.push({
                        x: 60,
                        y: topY + i * (photoH + gapY),
                        w: stripW,
                        h: photoH
                    });
                }
                // Right Strip (Poses 4..7)
                for (let i = 0; i < 4; i++) {
                    slotRects.push({
                        x: 640,
                        y: topY + i * (photoH + gapY),
                        w: stripW,
                        h: photoH
                    });
                }

            } else if (slots === 8) {
                // 8 Slots 2x4 Grid (Party 8-Grid)
                const marginX = 50;
                const topY = 110;
                const gapX = 30;
                const gapY = 22;
                const w = (canvas.width - (marginX * 2) - gapX) / 2;
                const h = 345;

                for (let i = 0; i < 8; i++) {
                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    slotRects.push({
                        x: marginX + col * (w + gapX),
                        y: topY + row * (h + gapY),
                        w: w,
                        h: h
                    });
                }

            } else if (slots === 6) {
                // 6 Slots 2x3 Grid (Korean 6, Filmstrip 6, Studio 6, Luxury 6, Newspaper 6)
                const marginX = (tId === 'filmstrip-6-retro') ? 95 : 55;
                const topY = 120;
                const gapX = 30;
                const gapY = 28;
                const w = (canvas.width - (marginX * 2) - gapX) / 2;
                const h = 460;

                for (let i = 0; i < 6; i++) {
                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    slotRects.push({
                        x: marginX + col * (w + gapX),
                        y: topY + row * (h + gapY),
                        w: w,
                        h: h
                    });
                }

            } else if (slots === 3) {
                // 3 Slots Cinematic Strip (1x3 Panoramic)
                const marginX = 75;
                const topY = 120;
                const gapY = 32;
                const w = canvas.width - (marginX * 2);
                const h = 465;

                for (let i = 0; i < 3; i++) {
                    slotRects.push({
                        x: marginX,
                        y: topY + i * (h + gapY),
                        w: w,
                        h: h
                    });
                }

            } else if (slots === 2) {
                // 2 Slots Duo Bestie (1x2 Large Portrait)
                const marginX = 80;
                const topY = 140;
                const gapY = 40;
                const w = canvas.width - (marginX * 2);
                const h = 700;

                for (let i = 0; i < 2; i++) {
                    slotRects.push({
                        x: marginX,
                        y: topY + i * (h + gapY),
                        w: w,
                        h: h
                    });
                }

            } else if (slots === 1) {
                // 1 Slot Polaroid Nostalgia Wide
                slotRects.push({
                    x: 100,
                    y: 120,
                    w: 1000,
                    h: 1250
                });

            } else {
                // Default 4 Slots (2x2 Grid or 1x4 Strip)
                if (tId === 'korean-4-strip') {
                    const marginX = 180;
                    const topY = 100;
                    const gapY = 24;
                    const w = canvas.width - (marginX * 2);
                    const h = 360;
                    for (let i = 0; i < 4; i++) {
                        slotRects.push({
                            x: marginX,
                            y: topY + i * (h + gapY),
                            w: w,
                            h: h
                        });
                    }
                } else {
                    const marginX = 55;
                    const topY = 70;
                    const gapX = 30;
                    const gapY = 30;
                    const w = (canvas.width - (marginX * 2) - gapX) / 2;
                    const h = 720;

                    for (let i = 0; i < 4; i++) {
                        const col = i % 2;
                        const row = Math.floor(i / 2);
                        slotRects.push({
                            x: marginX + col * (w + gapX),
                            y: topY + row * (h + gapY),
                            w: w,
                            h: h
                        });
                    }
                }
            }

            // 4. Draw Layout Specific Overlays & Frame Decorations
            function drawFrameDecorations() {
                if (tId === 'korean-8-strip') {
                    // Center Perforation Line
                    ctx.save();
                    ctx.setLineDash([12, 10]);
                    ctx.strokeStyle = '#F472B6';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(600, 30);
                    ctx.lineTo(600, 1770);
                    ctx.stroke();
                    ctx.restore();

                    // Scissors & Cut indicator
                    ctx.fillStyle = '#DB2777';
                    ctx.font = 'bold 18px monospace';
                    ctx.textAlign = 'center';
                    ctx.fillText('✂ CUT HERE ✂', 600, 50);
                    ctx.fillText('✂ CUT HERE ✂', 600, 1760);

                    // Strip 1 Headers & Footers
                    ctx.fillStyle = '#9D174D';
                    ctx.font = 'bold 28px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText('🌸 LIFE 4 CUTS', 310, 75);
                    ctx.fillText('🌸 LIFE 4 CUTS', 890, 75);

                    ctx.font = 'bold 18px sans-serif';
                    ctx.fillStyle = '#BE185D';
                    ctx.fillText(`${BOOKING_CODE} • K-PHOTOBOOTH`, 310, 1690);
                    ctx.fillText(`${BOOKING_CODE} • K-PHOTOBOOTH`, 890, 1690);

                    ctx.font = '14px sans-serif';
                    ctx.fillStyle = '#F472B6';
                    const dateStr = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                    ctx.fillText(`POTRET DIRI STUDIO • ${dateStr}`, 310, 1720);
                    ctx.fillText(`POTRET DIRI STUDIO • ${dateStr}`, 890, 1720);

                } else if (tId === 'filmstrip-6-retro') {
                    // Sprocket Holes along left and right
                    ctx.fillStyle = '#27272A';
                    for (let y = 30; y < 1780; y += 45) {
                        // Left Sprocket
                        ctx.fillRect(25, y, 35, 24);
                        // Right Sprocket
                        ctx.fillRect(1140, y, 35, 24);
                    }

                    // Kodak Yellow Headers
                    ctx.fillStyle = '#EAB308';
                    ctx.font = 'bold 26px monospace';
                    ctx.textAlign = 'left';
                    ctx.fillText('KODAK ULTRA 400', 100, 70);
                    ctx.textAlign = 'right';
                    ctx.fillText('35MM COLOR FILM', 1100, 70);

                    // Yellow Frame Indexes
                    ctx.font = 'bold 18px monospace';
                    slotRects.forEach((r, i) => {
                        ctx.fillStyle = '#EAB308';
                        ctx.textAlign = 'left';
                        ctx.fillText(`FRAME #${i + 1}A`, r.x, r.y - 8);
                    });

                    // Footer
                    ctx.fillStyle = '#EAB308';
                    ctx.font = 'bold 22px monospace';
                    ctx.textAlign = 'center';
                    ctx.fillText(`POTRET DIRI ANALOG ARCHIVE • ${BOOKING_CODE}`, canvas.width / 2, canvas.height - 70);
                    ctx.font = '16px monospace';
                    ctx.fillStyle = '#71717A';
                    ctx.fillText('SAFETY FILM • DEVELOPED TODAY', canvas.width / 2, canvas.height - 40);

                } else if (tId === 'vintage-newspaper-6') {
                    // Newspaper Header Masthead
                    ctx.fillStyle = '#1C1917';
                    ctx.font = 'bold 54px serif';
                    ctx.textAlign = 'center';
                    ctx.fillText('THE DAILY POTRET', canvas.width / 2, 70);

                    ctx.strokeStyle = '#1C1917';
                    ctx.lineWidth = 3;
                    ctx.beginPath();
                    ctx.moveTo(60, 85);
                    ctx.lineTo(1140, 85);
                    ctx.stroke();

                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(60, 92);
                    ctx.lineTo(1140, 92);
                    ctx.stroke();

                    ctx.font = 'italic 16px serif';
                    ctx.fillStyle = '#44403C';
                    ctx.fillText('SPECIAL EDITION • PHOTO ARCHIVE ISSUE NO. 26 • ALL RIGHTS RESERVED', canvas.width / 2, 108);

                    // Footer
                    ctx.strokeStyle = '#1C1917';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(60, canvas.height - 110);
                    ctx.lineTo(1140, canvas.height - 110);
                    ctx.stroke();

                    ctx.font = 'bold 24px serif';
                    ctx.fillStyle = '#1C1917';
                    ctx.fillText('POTRET DIRI STUDIO • UNFORGETTABLE HEADLINES', canvas.width / 2, canvas.height - 70);
                    ctx.font = 'italic 16px serif';
                    ctx.fillStyle = '#57534E';
                    ctx.fillText(`SESSION: ${BOOKING_CODE} • CAPTURED TODAY`, canvas.width / 2, canvas.height - 40);

                } else if (tId === 'dark-elegance-6') {
                    // Gold Double Borders
                    ctx.strokeStyle = '#F5BD23';
                    ctx.lineWidth = 3;
                    ctx.strokeRect(25, 25, canvas.width - 50, canvas.height - 50);
                    ctx.lineWidth = 1;
                    ctx.strokeRect(33, 33, canvas.width - 66, canvas.height - 66);

                    // Header
                    ctx.fillStyle = '#F5BD23';
                    ctx.font = 'bold 36px serif';
                    ctx.textAlign = 'center';
                    ctx.fillText('POTRET NOIR ATELIER', canvas.width / 2, 75);

                    // Footer
                    ctx.font = '22px serif';
                    ctx.fillText('PREMIUM STUDIO EDITION', canvas.width / 2, canvas.height - 75);
                    ctx.font = '14px monospace';
                    ctx.fillStyle = '#94A3B8';
                    ctx.fillText(`${BOOKING_CODE} • LUXURY COLLECTION`, canvas.width / 2, canvas.height - 48);

                } else if (tId === 'polaroid-wide') {
                    // Handwritten Polaroid Footer
                    ctx.fillStyle = '#44403C';
                    ctx.font = 'italic bold 36px serif';
                    ctx.textAlign = 'center';
                    ctx.fillText('Soulful Moments', canvas.width / 2, 1500);

                    ctx.font = '20px monospace';
                    ctx.fillStyle = '#78716C';
                    ctx.fillText(`POTRET DIRI • ${BOOKING_CODE} • EST. 2026`, canvas.width / 2, 1550);

                } else {
                    // Universal Header & Footer
                    ctx.fillStyle = textColor;
                    ctx.font = 'bold 36px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(tagline, canvas.width / 2, (slots === 4 && tId !== 'korean-4-strip') ? 48 : 70);

                    ctx.fillStyle = textColor;
                    ctx.font = 'bold 24px sans-serif';
                    ctx.fillText(footer, canvas.width / 2, canvas.height - 75);

                    ctx.font = 'bold 16px monospace';
                    ctx.fillStyle = accentColor;
                    ctx.fillText(`${BOOKING_CODE} • ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })} • LUMABOOTH KIOSK`, canvas.width / 2, canvas.height - 45);
                }

                // If template has custom overlay PNG, draw it over the entire composite
                if (TEMPLATE_CONFIG && TEMPLATE_CONFIG.overlay_url) {
                    const overlayImg = new Image();
                    overlayImg.crossOrigin = 'anonymous';
                    overlayImg.onload = () => {
                        ctx.drawImage(overlayImg, 0, 0, canvas.width, canvas.height);
                        if (baseStripCanvas) {
                            baseStripCanvas.getContext('2d').drawImage(canvas, 0, 0);
                        }
                        const updatedDataUrl = canvas.toDataURL('image/jpeg', 0.95);
                        const printImg = document.getElementById('preview-print-img');
                        if (printImg) printImg.src = updatedDataUrl;
                        bakeStickersToCanvas();
                    };
                    overlayImg.src = TEMPLATE_CONFIG.overlay_url;
                }
            }

            // 5. Load and Draw all Photos
            let loadedCount = 0;
            const totalToLoad = Math.min(slots, slotRects.length);

            capturedPhotos.slice(0, totalToLoad).forEach((src, idx) => {
                const rect = slotRects[idx];
                const img = new Image();
                img.crossOrigin = 'anonymous';

                img.onload = () => {
                    ctx.save();
                    ctx.filter = canvasFilter;

                    // Draw image centered & covering slot aspect ratio
                    const targetAspect = rect.w / rect.h;
                    const imgAspect = img.width / img.height;
                    let sx = 0, sy = 0, sw = img.width, sh = img.height;

                    if (imgAspect > targetAspect) {
                        sw = img.height * targetAspect;
                        sx = (img.width - sw) / 2;
                    } else {
                        sh = img.width / targetAspect;
                        sy = (img.height - sh) / 2;
                    }

                    ctx.drawImage(img, sx, sy, sw, sh, rect.x, rect.y, rect.w, rect.h);
                    ctx.restore();

                    // Optional Border around each photo
                    ctx.strokeStyle = (activeFrameColor && activeFrameColor.id && activeFrameColor.id !== 'original' && activeFrameColor.border) ? activeFrameColor.border :
                                      ((tId === 'dark-elegance-6') ? '#F5BD23' : 
                                      ((tId === 'filmstrip-6-retro') ? '#3F3F46' : 
                                      ((tId === 'korean-8-strip' || tId === 'korean-6-grid') ? '#FECDD3' : '#E2E8F0')));
                    ctx.lineWidth = 3;
                    ctx.strokeRect(rect.x, rect.y, rect.w, rect.h);

                    loadedCount++;
                    if (loadedCount === totalToLoad) {
                        // All images drawn, now draw text & brand decorations
                        drawFrameDecorations();

                        // Cache base photostrip without stickers for ultra-fast sticker manipulation
                        if (!baseStripCanvas) {
                            baseStripCanvas = document.createElement('canvas');
                        }
                        baseStripCanvas.width = canvas.width;
                        baseStripCanvas.height = canvas.height;
                        baseStripCanvas.getContext('2d').drawImage(canvas, 0, 0);

                        const frameDataUrl = canvas.toDataURL('image/jpeg', 0.95);
                        document.getElementById('preview-print-img').src = frameDataUrl;

                        // Bake active stickers onto high-res canvas and update print-img
                        bakeStickersToCanvas();
                    }
                };

                img.onerror = () => {
                    loadedCount++;
                    if (loadedCount === totalToLoad) {
                        drawFrameDecorations();

                        if (!baseStripCanvas) {
                            baseStripCanvas = document.createElement('canvas');
                        }
                        baseStripCanvas.width = canvas.width;
                        baseStripCanvas.height = canvas.height;
                        baseStripCanvas.getContext('2d').drawImage(canvas, 0, 0);

                        const frameDataUrl = canvas.toDataURL('image/jpeg', 0.95);
                        document.getElementById('preview-print-img').src = frameDataUrl;

                        bakeStickersToCanvas();
                    }
                };

                img.src = src;
            });
        }

        // ========================================================
        // 5B. LIVE FRAME OVERLAY & TEMPLATE PREVIEW MODAL
        // ========================================================
        function toggleLiveFrameOverlay() {
            isLiveFrameOverlayActive = !isLiveFrameOverlayActive;
            localStorage.setItem('booth_live_frame_overlay', isLiveFrameOverlayActive ? '1' : '0');
            applyLiveFrameOverlayState();
            showToast(isLiveFrameOverlayActive ? 'Desain Frame Kamera: Aktif' : 'Desain Frame Kamera: Nonaktif', '🖼️');
        }

        function applyLiveFrameOverlayState() {
            const overlay = document.getElementById('live-frame-overlay');
            const label = document.getElementById('frame-overlay-label');
            const btn = document.getElementById('btn-frame-overlay');
            
            if (isLiveFrameOverlayActive) {
                if (overlay) overlay.classList.remove('hidden');
                if (label) label.innerText = 'Frame: Aktif';
                if (btn) {
                    btn.className = 'p-2.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 active:scale-95 border border-amber-500/40 text-xs text-amber-300 flex items-center gap-1.5 transition';
                }
            } else {
                if (overlay) overlay.classList.add('hidden');
                if (label) label.innerText = 'Frame: Nonaktif';
                if (btn) {
                    btn.className = 'p-2.5 rounded-xl bg-white/5 hover:bg-white/10 active:scale-95 border border-white/10 text-xs text-slate-400 flex items-center gap-1.5 transition';
                }
            }
        }

        function updateLiveFrameOverlayPose(poseIdx) {
            const poseNum = poseIdx + 1;
            const numEl = document.getElementById('live-pose-num');
            if (numEl) numEl.innerText = poseNum;

            const slotHint = document.getElementById('live-slot-hint-num');
            if (slotHint) slotHint.innerText = poseNum;

            const tId = TEMPLATE_CONFIG.id || 'classic-4-grid';
            const borderEl = document.getElementById('live-frame-border');
            const sprocketsEl = document.getElementById('live-frame-sprockets');
            const cornerTL = document.getElementById('live-frame-corner-tl');
            const cornerTR = document.getElementById('live-frame-corner-tr');
            const cornerBL = document.getElementById('live-frame-corner-bl');
            const cornerBR = document.getElementById('live-frame-corner-br');
            
            if (borderEl) {
                if (activeFrameColor && activeFrameColor.border && activeFrameColor.id !== 'original') {
                    borderEl.style.borderColor = activeFrameColor.border;
                } else if (tId === 'filmstrip-6-retro') {
                    borderEl.style.borderColor = '#EAB308';
                } else if (tId.includes('korean')) {
                    borderEl.style.borderColor = '#F472B6';
                } else if (tId === 'dark-elegance-6') {
                    borderEl.style.borderColor = '#F5BD23';
                } else if (tId === 'vintage-newspaper-6') {
                    borderEl.style.borderColor = '#78716C';
                } else {
                    borderEl.style.borderColor = '#F5BD23';
                }
            }

            if (sprocketsEl) {
                if (tId === 'filmstrip-6-retro') {
                    sprocketsEl.classList.remove('hidden');
                } else {
                    sprocketsEl.classList.add('hidden');
                }
            }

            if (tId.includes('korean')) {
                if (cornerTL) cornerTL.innerText = '♡';
                if (cornerTR) cornerTR.innerText = '♡';
                if (cornerBL) cornerBL.innerText = '♡';
                if (cornerBR) cornerBR.innerText = '♡';
            } else if (tId === 'y2k-cyber-4') {
                if (cornerTL) cornerTL.innerText = '◤';
                if (cornerTR) cornerTR.innerText = '◥';
                if (cornerBL) cornerBL.innerText = '◣';
                if (cornerBR) cornerBR.innerText = '◢';
            } else {
                if (cornerTL) cornerTL.innerText = '┌';
                if (cornerTR) cornerTR.innerText = '┐';
                if (cornerBL) cornerBL.innerText = '└';
                if (cornerBR) cornerBR.innerText = '┘';
            }
        }

        // Open Full Frame Design Preview Modal
        function openFramePreviewModal() {
            const modal = document.getElementById('frame-preview-modal');
            if (!modal) return;
            modal.classList.remove('hidden');

            const colorLabel = document.getElementById('modal-frame-color-label');
            if (colorLabel && activeFrameColor) {
                const found = ALL_FRAME_COLORS.find(c => c.id === activeFrameColor.id);
                colorLabel.innerText = `Warna Frame: ${found ? found.name : (activeFrameColor.id || 'Bawaan')}`;
            }

            const canvas = document.getElementById('modal-preview-canvas');
            if (canvas) {
                drawTemplatePreview(canvas, TEMPLATE_CONFIG.id || 'classic-4-grid', TOTAL_POSES, activeFrameColor);
            }
        }

        function closeFramePreviewModal() {
            const modal = document.getElementById('frame-preview-modal');
            if (modal) modal.classList.add('hidden');
        }

        // Draw Full High-Res Preview Mockup on Canvas
        function drawTemplatePreview(canvas, tId, slots, frameColorObj) {
            const ctx = canvas.getContext('2d');
            canvas.width = 600;
            canvas.height = 900;

            ctx.save();
            ctx.scale(0.5, 0.5); // Work in 1200x1800 coordinate space

            const bg = (frameColorObj && frameColorObj.bg) ? frameColorObj.bg : (TEMPLATE_CONFIG.bg_color || '#FFFFFF');
            const textCol = (frameColorObj && frameColorObj.text) ? frameColorObj.text : (TEMPLATE_CONFIG.text_color || '#1E293B');
            const borderCol = (frameColorObj && frameColorObj.border) ? frameColorObj.border : (TEMPLATE_CONFIG.accent || '#F5BD23');

            // 1. Background
            ctx.fillStyle = bg;
            ctx.fillRect(0, 0, 1200, 1800);

            // 2. Slot Coordinates
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

            // 4. Draw Specific Layout Branding
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
                ctx.fillText(TEMPLATE_CONFIG.name || 'POTRET DIRI • SELF STUDIO', 600, (slots === 4 && tId !== 'korean-4-strip') ? 48 : 70);

                ctx.font = 'bold 22px monospace';
                ctx.fillStyle = borderCol;
                ctx.fillText(`${BOOKING_CODE} • LUMABOOTH STUDIO`, 600, 1750);
            }

            ctx.restore();

            if (TEMPLATE_CONFIG && TEMPLATE_CONFIG.overlay_url) {
                const overlayImg = new Image();
                overlayImg.crossOrigin = 'anonymous';
                overlayImg.onload = () => {
                    ctx.drawImage(overlayImg, 0, 0, canvas.width, canvas.height);
                };
                overlayImg.src = TEMPLATE_CONFIG.overlay_url;
            }
        }

        // ========================================================
        // 6. QR CODE GENERATION & DIGITAL SHARING
        // ========================================================
        function generateSharingQrCode(url) {
            const container = document.getElementById('download-qrcode');
            container.innerHTML = '';
            if (typeof QRCode !== 'undefined') {
                new QRCode(container, {
                    text: url,
                    width: 155,
                    height: 155,
                    colorDark: "#09090B",
                    colorLight: "#FFFFFF",
                    correctLevel: QRCode.CorrectLevel.M
                });
            }
        }

        function copySessionLink() {
            navigator.clipboard.writeText(GALLERY_URL).then(() => {
                showToast('📋 Tautan galeri berhasil disalin!');
            }).catch(() => {
                showToast('Tautan: ' + GALLERY_URL);
            });
        }

        // Stepper for print copies
        function adjustPrintCopies(delta) {
            printCopies = Math.max(1, Math.min(4, printCopies + delta));
            document.getElementById('print-copies-val').innerText = printCopies;
            document.getElementById('btn-print-copies').innerText = printCopies;
        }

        function triggerLumaBoothPrint() {
            showToast(`🖶 Mengirim ${printCopies} lembar ke printer photobooth...`);
            setTimeout(() => {
                window.print();
            }, 600);
        }

        function downloadCollageDirectly() {
            const canvas = document.getElementById('collage-canvas');
            const a = document.createElement('a');
            a.download = `PotretDiri-${BOOKING_CODE}.jpg`;
            a.href = canvas.toDataURL('image/jpeg', 0.95);
            a.click();
            showToast('📥 Mengunduh file foto resolusi tinggi...');
        }

        // WhatsApp Modal
        function openWhatsAppModal() {
            document.getElementById('whatsapp-modal').classList.remove('hidden');
        }
        function closeWhatsAppModal() {
            document.getElementById('whatsapp-modal').classList.add('hidden');
        }
        function submitWhatsAppShare() {
            const phone = document.getElementById('wa-phone-input').value.trim();
            if (!phone) {
                alert('Silakan masukkan nomor WhatsApp Anda.');
                return;
            }
            let cleanPhone = phone.replace(/\D/g, '');
            if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);

            const msg = encodeURIComponent(`Halo! Ini hasil foto seru saya di Potret Diri Studio (${BOOKING_CODE}). Lihat dan download di sini: ${GALLERY_URL}`);
            const waUrl = `https://wa.me/${cleanPhone}?text=${msg}`;
            window.open(waUrl, '_blank');
            closeWhatsAppModal();
            showToast('💬 Mengalihkan ke WhatsApp...');
        }

        // Email Modal
        function openEmailModal() {
            document.getElementById('email-modal').classList.remove('hidden');
        }
        function closeEmailModal() {
            document.getElementById('email-modal').classList.add('hidden');
        }
        function submitEmailShare() {
            const email = document.getElementById('email-addr-input').value.trim();
            if (!email) {
                alert('Silakan masukkan alamat email Anda.');
                return;
            }
            closeEmailModal();
            showToast(`✉️ Salinan digital dikirim ke ${email}`);
        }

        // Retake Modal
        function openRetakeModal() {
            updateRetakeUI();
            document.getElementById('retake-modal').classList.remove('hidden');
        }
        function closeRetakeModal() {
            document.getElementById('retake-modal').classList.add('hidden');
        }
        function retakeEntireSession() {
            if (!canRetake()) {
                showToast('⚠️ Batas kuota foto ulang telah habis.', '⚠️');
                closeRetakeModal();
                return;
            }
            recordRetake();
            closeRetakeModal();
            document.getElementById('screen-sharing').classList.add('hidden');
            document.getElementById('screen-capture').classList.remove('hidden');
            startAutomaticPhotoSequence();
        }
        function retakeSpecificPose(poseIdx) {
            if (!canRetake()) {
                showToast('⚠️ Batas kuota foto ulang telah habis.', '⚠️');
                closeRetakeModal();
                return;
            }
            recordRetake();
            closeRetakeModal();
            document.getElementById('screen-sharing').classList.add('hidden');
            document.getElementById('screen-capture').classList.remove('hidden');
            currentPoseIndex = poseIdx;
            executeSinglePoseCountdown();
        }

        // Exit / Cancel Session Modal
        function openExitConfirmModal() {
            document.getElementById('exit-modal').classList.remove('hidden');
        }
        function closeExitConfirmModal() {
            document.getElementById('exit-modal').classList.add('hidden');
        }

        // Celebration & Done Session
        function finishSessionWithCelebration() {
            if (autoFinishTimerInterval) clearInterval(autoFinishTimerInterval);
            if (sessionTimerInterval) clearInterval(sessionTimerInterval);
            document.getElementById('celebration-modal').classList.remove('hidden');
            playCelebrationChime();
        }

        // Auto-Finish Timer (LumaBooth Kiosk timeout - resets on touch)
        function startAutoFinishTimer() {
            autoFinishSeconds = 60;
            const timerEl = document.getElementById('auto-finish-timer');
            if (autoFinishTimerInterval) clearInterval(autoFinishTimerInterval);

            autoFinishTimerInterval = setInterval(() => {
                autoFinishSeconds--;
                if (timerEl) timerEl.innerText = `${autoFinishSeconds}s`;
                if (autoFinishSeconds <= 0) {
                    clearInterval(autoFinishTimerInterval);
                    finishSessionWithCelebration();
                }
            }, 1000);

            // Reset timer on user interaction
            ['click', 'touchstart'].forEach(evt => {
                document.addEventListener(evt, () => {
                    autoFinishSeconds = 60;
                }, { passive: true });
            });
        }

        // Toast Feedback Helper
        function showToast(message, icon = '✨') {
            const banner = document.getElementById('toast-banner');
            document.getElementById('toast-message').innerText = message;
            document.getElementById('toast-icon').innerText = icon;
            banner.classList.remove('-translate-y-20', 'opacity-0');
            banner.classList.add('translate-y-0', 'opacity-100');

            setTimeout(() => {
                banner.classList.remove('translate-y-0', 'opacity-100');
                banner.classList.add('-translate-y-20', 'opacity-0');
            }, 3000);
        }

        // Initialize on DOM load
        window.addEventListener('DOMContentLoaded', () => {
            initCamera();
            applyLiveFrameOverlayState();
            updateLiveFrameOverlayPose(0);
            renderStickersTray('all');
            startStudioSessionTimer();
            updateRetakeUI();
        });

        // Keydown listener for modal closing
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeFramePreviewModal();
            }
        });
    </script>
</body>
</html>

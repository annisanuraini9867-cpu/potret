<!DOCTYPE html>
<html lang="id" class="h-full bg-[#E5E7EB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sesi Foto & Preview - Potret Diri</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Standalone Offline QR Code Generator -->
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fredoka:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            user-select: none;
            -webkit-user-select: none;
        }

        /* Screen flash animation */
        @keyframes flash-anim {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }
        .flash-active {
            animation: flash-anim 0.4s ease-out forwards;
        }

        /* Print formatting */
        @media print {
            body * { visibility: hidden; }
            #print-container, #print-container * { visibility: visible; }
            #print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100vw;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between items-center bg-[#E5E7EB] text-slate-800 antialiased selection:bg-[#F5BD23] selection:text-slate-900 overflow-x-hidden">

    <!-- Flash overlay for camera snap -->
    <div id="camera-flash" class="fixed inset-0 bg-white pointer-events-none z-[100] opacity-0"></div>

    <!-- Hidden Video & Canvas Elements for Camera Stream & Collage Rendering -->
    <video id="live-video" autoplay playsinline class="hidden"></video>
    <canvas id="capture-canvas" width="1200" height="900" class="hidden"></canvas>
    <canvas id="collage-canvas" width="1200" height="1800" class="hidden"></canvas>

    <!-- Hidden Print Target -->
    <div id="print-container" class="hidden">
        <img id="print-img" src="" alt="Print Photo" style="max-height: 100vh; max-width: 100vw; object-fit: contain;">
    </div>

    <!-- ========================================================================= -->
    <!-- STAGE 1: LIVE CAMERA CAPTURE (AUTOMATIC 4-SHOT STUDIO SEQUENCE)           -->
    <!-- ========================================================================= -->
    <section id="screen-capture" class="w-full min-h-screen flex flex-col items-center justify-between p-6 bg-slate-950 text-white relative">
        
        <!-- Top Bar with Progress & Camera Info -->
        <div class="w-full max-w-5xl flex items-center justify-between z-20">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
                <span class="font-black text-xs uppercase tracking-widest text-slate-300">STUDIO LIVE CAPTURE</span>
                <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-amber-300 text-[10px] font-mono font-bold">
                    {{ $booking->booking_code }}
                </span>
            </div>

            <div class="flex items-center gap-2 text-xs font-mono text-slate-400">
                <span>POSE <strong id="pose-counter" class="text-amber-400 text-sm">1</strong> / 4</span>
            </div>
        </div>

        <!-- Main Center Viewfinder View -->
        <div class="relative w-full max-w-3xl aspect-[4/3] rounded-3xl overflow-hidden bg-slate-900 border-2 border-white/10 shadow-2xl flex items-center justify-center my-auto">
            
            <!-- Video Preview Stream -->
            <video id="viewfinder-video" autoplay playsinline class="w-full h-full object-cover transform -scale-x-100"></video>

            <!-- Radial Countdown Overlay (3, 2, 1, SMILE!) -->
            <div id="countdown-container" class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex flex-col items-center justify-center transition-all z-20 hidden">
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="42" stroke="rgba(255,255,255,0.15)" stroke-width="8" fill="none"/>
                        <circle id="countdown-circle" cx="50" cy="50" r="42" stroke="#F5BD23" stroke-width="8" stroke-dasharray="264" stroke-dashoffset="0" stroke-linecap="round" fill="none" class="transition-all duration-1000 ease-linear"/>
                    </svg>
                    <span id="countdown-digit" class="absolute font-black text-6xl text-[#F5BD23] drop-shadow-2xl">3</span>
                </div>
                <p id="countdown-label" class="text-white text-base font-black tracking-widest uppercase mt-4 animate-pulse">BERSIAP POSE!</p>
            </div>

            <!-- Pre-Start Trigger Overlay (if camera requires touch start) -->
            <div id="touch-start-overlay" class="absolute inset-0 bg-black/75 backdrop-blur-md flex flex-col items-center justify-center gap-4 z-30 p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-[#F5BD23] text-slate-950 flex items-center justify-center text-4xl shadow-xl shadow-amber-500/30">
                    📸
                </div>
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-white">Studio Siap!</h2>
                    <p class="text-xs text-slate-300 max-w-xs">Kamera akan mengambil 4 jepretan otomatis dengan jeda pose 3 detik per foto.</p>
                </div>
                <button type="button" onclick="startAutomaticPhotoSequence()" 
                        class="py-4 px-10 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-sm uppercase tracking-wider shadow-2xl shadow-amber-500/40 transition">
                    Mulai Berpose Sekarang →
                </button>
            </div>
        </div>

        <!-- Bottom 4 Photo Thumbnails Strip -->
        <div class="w-full max-w-3xl flex items-center justify-center gap-3 z-20 pt-3">
            <div id="thumb-slot-0" class="w-20 aspect-square rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xs font-mono text-slate-500 overflow-hidden">
                <span>1</span>
            </div>
            <div id="thumb-slot-1" class="w-20 aspect-square rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xs font-mono text-slate-500 overflow-hidden">
                <span>2</span>
            </div>
            <div id="thumb-slot-2" class="w-20 aspect-square rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xs font-mono text-slate-500 overflow-hidden">
                <span>3</span>
            </div>
            <div id="thumb-slot-3" class="w-20 aspect-square rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xs font-mono text-slate-500 overflow-hidden">
                <span>4</span>
            </div>
        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- STAGE 2: EXACT PREVIEW & PRINT STATION SCREEN (media_1788252918434.png)  -->
    <!-- ========================================================================= -->
    <section id="screen-preview" class="w-full min-h-screen flex flex-col justify-between items-center py-6 px-4 hidden">
        
        <!-- Header Spacer -->
        <header class="w-full max-w-6xl flex items-center justify-between py-1"></header>

        <!-- Main 2-Columns Layout Grid (Exact Match to Image 1) -->
        <main class="w-full max-w-6xl my-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- ======================================================== -->
            <!-- KOLOM KIRI: Tab Preview, Main Stage & Thumbnails (8 Kolom)-->
            <!-- ======================================================== -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
                
                <!-- Top Tabs Bar (Exact Match) -->
                <div class="flex items-center gap-6 border-b border-slate-100 pb-3 text-xs sm:text-sm font-bold">
                    <button type="button" onclick="switchPreviewTab('original')" id="tab-original" 
                            class="flex items-center gap-2 pb-2 text-slate-900 border-b-2 border-slate-900 font-extrabold transition">
                        <span>📷</span>
                        <span>Original Photos</span>
                    </button>

                    <button type="button" onclick="switchPreviewTab('frame')" id="tab-frame" 
                            class="flex items-center gap-2 pb-2 text-slate-400 hover:text-slate-700 border-b-2 border-transparent transition">
                        <span>⛶</span>
                        <span>Frame Preview</span>
                    </button>

                    <button type="button" onclick="switchPreviewTab('gif')" id="tab-gif" 
                            class="flex items-center gap-2 pb-2 text-slate-400 hover:text-slate-700 border-b-2 border-transparent transition">
                        <span>▶</span>
                        <span>GIF Preview</span>
                    </button>
                </div>

                <!-- Main Featured Preview Area (Warm Cream #F5EEDB Background) -->
                <div class="relative bg-[#F5EEDB] rounded-3xl p-6 sm:p-10 flex items-center justify-center aspect-[4/3] overflow-hidden shadow-inner">
                    
                    <!-- 1. Original Photo Display -->
                    <img id="main-featured-img" 
                         src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80" 
                         alt="Main Photo Preview" 
                         class="max-h-[380px] w-auto max-w-full object-contain rounded-xl shadow-lg transition-all duration-300">

                    <!-- 2. Frame Composite Canvas/Image Display -->
                    <img id="frame-composite-img" 
                         src="" 
                         alt="Frame Preview" 
                         class="max-h-[380px] w-auto max-w-full object-contain rounded-xl shadow-lg hidden transition-all duration-300">

                    <!-- 3. GIF Animation Preview Display -->
                    <img id="gif-animation-img" 
                         src="" 
                         alt="GIF Animation" 
                         class="max-h-[380px] w-auto max-w-full object-contain rounded-xl shadow-lg hidden transition-all duration-300">

                    <!-- Bottom Right Badge: Photo X of 4 (Exact Match) -->
                    <div id="photo-counter-badge" class="absolute bottom-4 right-4 px-3.5 py-1 rounded-full bg-white text-slate-800 text-xs font-mono font-bold shadow-md">
                        Photo 1 of 4
                    </div>
                </div>

                <!-- Bottom 4 Thumbnails Strip (Exact Match) -->
                <div class="grid grid-cols-4 gap-4">
                    <div onclick="selectFeaturedPhoto(0)" id="thumb-box-0" 
                         class="thumbnail-card aspect-square rounded-2xl overflow-hidden cursor-pointer border-2 border-[#F5BD23] ring-2 ring-amber-200/50 shadow-sm transition-all">
                        <img id="thumb-img-0" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                    </div>

                    <div onclick="selectFeaturedPhoto(1)" id="thumb-box-1" 
                         class="thumbnail-card aspect-square rounded-2xl overflow-hidden cursor-pointer border-2 border-transparent hover:border-slate-300 shadow-sm transition-all">
                        <img id="thumb-img-1" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                    </div>

                    <div onclick="selectFeaturedPhoto(2)" id="thumb-box-2" 
                         class="thumbnail-card aspect-square rounded-2xl overflow-hidden cursor-pointer border-2 border-transparent hover:border-slate-300 shadow-sm transition-all">
                        <img id="thumb-img-2" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=300&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                    </div>

                    <div onclick="selectFeaturedPhoto(3)" id="thumb-box-3" 
                         class="thumbnail-card aspect-square rounded-2xl overflow-hidden cursor-pointer border-2 border-transparent hover:border-slate-300 shadow-sm transition-all">
                        <img id="thumb-img-3" src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=300&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                    </div>
                </div>

            </div>

            <!-- ======================================================== -->
            <!-- KOLOM KANAN: Print & Save Card + Finish Session (4 Kolom)-->
            <!-- ======================================================== -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Card 1: Print & Save (Warm Light Cream Background #FAF4E8) -->
                <div class="bg-[#FAF4E8] rounded-3xl p-7 shadow-sm border border-amber-200/50 space-y-6">
                    
                    <h3 class="text-xl font-extrabold text-slate-900">Print & Save</h3>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <!-- Print Photo Button (Yellow) -->
                        <button type="button" onclick="triggerPrintPhoto()" 
                                class="w-full py-4 px-6 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-sm tracking-wide shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                            <span>🖶</span>
                            <span>Print Photo</span>
                        </button>

                        <!-- Download Digital Button (White) -->
                        <button type="button" onclick="openDownloadModal()" 
                                class="w-full py-3.5 px-6 rounded-full bg-white hover:bg-slate-50 active:scale-[0.99] text-slate-900 font-extrabold text-xs tracking-wide border border-slate-200 shadow-sm transition-all flex items-center justify-center gap-2">
                            <span>📥</span>
                            <span>Download Digital</span>
                        </button>
                    </div>

                    <!-- 4 Quick Action Icons Grid (2x2) -->
                    <div class="grid grid-cols-2 gap-4 pt-2 text-center text-xs font-bold text-slate-700">
                        <button type="button" onclick="alert('Foto ditambahkan ke daftar favorit!')" 
                                class="flex flex-col items-center justify-center gap-1.5 p-3 rounded-2xl hover:bg-white/60 transition">
                            <span class="text-base text-slate-600">♡</span>
                            <span class="text-[11px]">Favorite</span>
                        </button>

                        <button type="button" onclick="copySessionLink()" 
                                class="flex flex-col items-center justify-center gap-1.5 p-3 rounded-2xl hover:bg-white/60 transition">
                            <span class="text-base text-slate-600">↗</span>
                            <span class="text-[11px]">Share</span>
                        </button>

                        <button type="button" onclick="retakeActivePhoto()" 
                                class="flex flex-col items-center justify-center gap-1.5 p-3 rounded-2xl hover:bg-white/60 transition">
                            <span class="text-base text-slate-600">↺</span>
                            <span class="text-[11px]">Retake</span>
                        </button>

                        <button type="button" onclick="alert('Foto dihapus.')" 
                                class="flex flex-col items-center justify-center gap-1.5 p-3 rounded-2xl hover:bg-white/60 transition text-rose-700">
                            <span class="text-base">🗑</span>
                            <span class="text-[11px]">Delete</span>
                        </button>
                    </div>

                </div>

                <!-- Card 2: Finish Session Box (Soft Light Gray) -->
                <div class="bg-white/80 rounded-3xl p-6 shadow-sm border border-slate-200 text-center space-y-3">
                    <p class="text-xs text-stone-600 font-semibold">Happy with your choices?</p>
                    
                    <a href="{{ route('booth.index') }}" 
                       class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-full bg-[#6B5508] hover:bg-[#584606] text-white font-bold text-xs tracking-wider uppercase shadow-md transition">
                        <span>Finish Session</span>
                        <span>✓</span>
                    </a>
                </div>

            </div>

        </main>

        <!-- Footer Space -->
        <footer class="w-full text-center py-2"></footer>

    </section>

    <!-- ========================================================================= -->
    <!-- MODAL "DOWNLOAD YOUR SESSION" (media_1788252936361.png)                   -->
    <!-- ========================================================================= -->
    <div id="download-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full p-7 sm:p-8 text-center space-y-5 relative shadow-2xl animate-in zoom-in duration-200 border border-slate-100">
            
            <!-- Close Button -->
            <button type="button" onclick="closeDownloadModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-900 rounded-full text-lg leading-none">
                ✕
            </button>

            <!-- Title & Description -->
            <div class="space-y-1 pt-1">
                <h3 class="text-xl font-black text-slate-900">Download Your Session</h3>
                <p class="text-xs text-slate-500 max-w-xs mx-auto">
                    Scan the code below to save your digital memories.
                </p>
            </div>

            <!-- QR Code Stand Box (Warm Cream #FAF4E8 Background) -->
            <div class="bg-[#FAF4E8] rounded-3xl p-5 aspect-square max-w-[190px] mx-auto flex items-center justify-center border border-amber-100 shadow-inner">
                <div id="download-qrcode" class="p-2 bg-white rounded-2xl shadow-md"></div>
            </div>

            <!-- File Options Checklist (Exact Match to Image 2) -->
            <div class="bg-slate-50 rounded-2xl p-3.5 text-xs space-y-2.5 text-left border border-slate-100">
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                        <input type="checkbox" checked class="w-4 h-4 rounded text-[#F5BD23] focus:ring-[#F5BD23]">
                        <span>Individual Photos (High-Res)</span>
                    </label>
                    <span class="text-slate-400 font-mono text-[11px]">4 Items</span>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                        <input type="checkbox" checked class="w-4 h-4 rounded text-[#F5BD23] focus:ring-[#F5BD23]">
                        <span>Print Ready Template</span>
                    </label>
                    <span class="text-slate-400 font-mono text-[11px]">2.4 MB</span>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                        <input type="checkbox" checked class="w-4 h-4 rounded text-[#F5BD23] focus:ring-[#F5BD23]">
                        <span>Animated Session GIF</span>
                    </label>
                    <span class="text-slate-400 font-mono text-[11px]">12.5 MB</span>
                </div>
            </div>

            <!-- 24-Hour Expiry Notice -->
            <div class="text-[11px] text-slate-400 flex items-center justify-center gap-1.5">
                <span>🕒</span>
                <span>Link available for 24 hours</span>
            </div>

            <!-- Copy Direct Link Button (Yellow) -->
            <div>
                <button type="button" onclick="copySessionLink()" 
                        class="w-full py-3.5 px-6 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition-all">
                    Copy Direct Link
                </button>
            </div>

        </div>
    </div>

    <!-- Audio Synthesis & Camera Script -->
    <script>
        const BOOKING_CODE = "{{ $booking->booking_code }}";
        const GALLERY_URL = "{{ route('gallery.show', $booking->booking_code) }}";
        
        let audioCtx = null;
        let capturedPhotos = [
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=800&auto=format&fit=crop&q=80'
        ];
        let currentPoseIndex = 0;
        let activeFeaturedIndex = 0;
        let gifInterval = null;

        // Initialize Web Audio API for Studio Attendant Sound
        function playTone(freq, type, duration) {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = type;
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + duration);
            } catch(e) {}
        }

        // Initialize Camera on page load
        window.addEventListener('DOMContentLoaded', async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: { ideal: 1920 }, height: { ideal: 1080 }, facingMode: "user" },
                    audio: false
                });
                const videoEl = document.getElementById('viewfinder-video');
                const liveEl = document.getElementById('live-video');
                videoEl.srcObject = stream;
                liveEl.srcObject = stream;
            } catch (err) {
                console.log("No physical camera detected, studio fallback mode active.");
            }
        });

        // 4-Shot Automatic Sequence
        function startAutomaticPhotoSequence() {
            document.getElementById('touch-start-overlay').classList.add('hidden');
            currentPoseIndex = 0;
            executeSinglePoseCountdown();
        }

        function executeSinglePoseCountdown() {
            if (currentPoseIndex >= 4) {
                finishShootingAndShowPreview();
                return;
            }

            document.getElementById('pose-counter').innerText = currentPoseIndex + 1;
            const countdownContainer = document.getElementById('countdown-container');
            const digitEl = document.getElementById('countdown-digit');
            const circleEl = document.getElementById('countdown-circle');
            const labelEl = document.getElementById('countdown-label');

            countdownContainer.classList.remove('hidden');
            let count = 3;
            digitEl.innerText = count;
            labelEl.innerText = `POSE ${currentPoseIndex + 1}: BERSIAP!`;
            circleEl.style.strokeDashoffset = '0';
            playTone(880, 'sine', 0.15);

            const timer = setInterval(() => {
                count--;
                if (count > 0) {
                    digitEl.innerText = count;
                    circleEl.style.strokeDashoffset = `${((3 - count) / 3) * 264}`;
                    playTone(880, 'sine', 0.15);
                } else {
                    clearInterval(timer);
                    digitEl.innerText = '📸';
                    labelEl.innerText = 'SMILE!';
                    playTone(1320, 'triangle', 0.35);

                    setTimeout(() => {
                        snapPhoto(currentPoseIndex);
                        currentPoseIndex++;
                        countdownContainer.classList.add('hidden');
                        
                        // 3 seconds intermission before next shot
                        if (currentPoseIndex < 4) {
                            setTimeout(() => {
                                executeSinglePoseCountdown();
                            }, 2500);
                        } else {
                            setTimeout(() => {
                                finishShootingAndShowPreview();
                            }, 1200);
                        }
                    }, 400);
                }
            }, 1000);
        }

        function snapPhoto(slotIndex) {
            // Flash effect
            const flash = document.getElementById('camera-flash');
            flash.classList.remove('flash-active');
            void flash.offsetWidth;
            flash.classList.add('flash-active');

            // Capture from live video or canvas
            const video = document.getElementById('viewfinder-video');
            const canvas = document.getElementById('capture-canvas');
            const ctx = canvas.getContext('2d');
            
            try {
                if (video.videoWidth > 0) {
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                    capturedPhotos[slotIndex] = dataUrl;
                }
            } catch(e) {}

            // Update bottom live thumbnail
            const slotEl = document.getElementById(`thumb-slot-${slotIndex}`);
            if (slotEl) {
                slotEl.innerHTML = `<img src="${capturedPhotos[slotIndex]}" class="w-full h-full object-cover">`;
                slotEl.className = 'w-20 aspect-square rounded-2xl bg-white/10 border-2 border-[#F5BD23] flex items-center justify-center overflow-hidden shadow-lg animate-in zoom-in';
            }
        }

        // Transition from Shooting to Preview & Print Screen
        function finishShootingAndShowPreview() {
            document.getElementById('screen-capture').classList.add('hidden');
            document.getElementById('screen-preview').classList.remove('hidden');

            // Populate all 4 thumbnails
            for (let i = 0; i < 4; i++) {
                const thumbImg = document.getElementById(`thumb-img-${i}`);
                if (thumbImg && capturedPhotos[i]) {
                    thumbImg.src = capturedPhotos[i];
                }
            }

            // Set main featured photo
            selectFeaturedPhoto(0);

            // Generate collage for Frame Preview
            renderFrameComposite();

            // Generate Standalone QR Code in Download Modal
            generateDownloadQrCode(GALLERY_URL);
        }

        // ========================================================
        // PREVIEW SCREEN LOGIC (media_1788252918434.png)
        // ========================================================
        function selectFeaturedPhoto(index) {
            activeFeaturedIndex = index;
            document.getElementById('main-featured-img').src = capturedPhotos[index];
            document.getElementById('photo-counter-badge').innerText = `Photo ${index + 1} of 4`;

            // Update thumbnail borders
            document.querySelectorAll('.thumbnail-card').forEach((card, idx) => {
                if (idx === index) {
                    card.className = 'thumbnail-card aspect-square rounded-2xl overflow-hidden cursor-pointer border-2 border-[#F5BD23] ring-2 ring-amber-200/50 shadow-sm transition-all';
                } else {
                    card.className = 'thumbnail-card aspect-square rounded-2xl overflow-hidden cursor-pointer border-2 border-transparent hover:border-slate-300 shadow-sm transition-all';
                }
            });

            switchPreviewTab('original');
        }

        function switchPreviewTab(tab) {
            if (gifInterval) clearInterval(gifInterval);

            const tabOriginal = document.getElementById('tab-original');
            const tabFrame = document.getElementById('tab-frame');
            const tabGif = document.getElementById('tab-gif');

            const mainImg = document.getElementById('main-featured-img');
            const frameImg = document.getElementById('frame-composite-img');
            const gifImg = document.getElementById('gif-animation-img');
            const counterBadge = document.getElementById('photo-counter-badge');

            // Reset tab styles
            [tabOriginal, tabFrame, tabGif].forEach(t => {
                t.className = 'flex items-center gap-2 pb-2 text-slate-400 hover:text-slate-700 border-b-2 border-transparent transition';
            });
            mainImg.classList.add('hidden');
            frameImg.classList.add('hidden');
            gifImg.classList.add('hidden');

            if (tab === 'original') {
                tabOriginal.className = 'flex items-center gap-2 pb-2 text-slate-900 border-b-2 border-slate-900 font-extrabold transition';
                mainImg.classList.remove('hidden');
                counterBadge.classList.remove('hidden');
                mainImg.src = capturedPhotos[activeFeaturedIndex];
            } else if (tab === 'frame') {
                tabFrame.className = 'flex items-center gap-2 pb-2 text-slate-900 border-b-2 border-slate-900 font-extrabold transition';
                frameImg.classList.remove('hidden');
                counterBadge.classList.add('hidden');
            } else if (tab === 'gif') {
                tabGif.className = 'flex items-center gap-2 pb-2 text-slate-900 border-b-2 border-slate-900 font-extrabold transition';
                gifImg.classList.remove('hidden');
                counterBadge.classList.add('hidden');

                // Animate GIF Loop
                let gIdx = 0;
                gifImg.src = capturedPhotos[0];
                gifInterval = setInterval(() => {
                    gIdx = (gIdx + 1) % 4;
                    gifImg.src = capturedPhotos[gIdx];
                }, 400);
            }
        }

        // Render Frame Collage Composite
        function renderFrameComposite() {
            const canvas = document.getElementById('collage-canvas');
            const ctx = canvas.getContext('2d');
            
            // White frame background
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // 2x2 Grid
            const margin = 50;
            const gap = 30;
            const w = (canvas.width - (margin * 2) - gap) / 2;
            const h = (canvas.height - (margin * 2) - gap - 150) / 2;

            let loaded = 0;
            capturedPhotos.forEach((src, idx) => {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => {
                    const row = Math.floor(idx / 2);
                    const col = idx % 2;
                    const x = margin + col * (w + gap);
                    const y = margin + row * (h + gap);
                    ctx.drawImage(img, x, y, w, h);
                    loaded++;
                    if (loaded === 4) {
                        // Add bottom studio branding
                        ctx.fillStyle = '#0F172A';
                        ctx.font = 'bold 36px sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('POTRET DIRI • SELF STUDIO', canvas.width / 2, canvas.height - 80);
                        ctx.font = '20px sans-serif';
                        ctx.fillStyle = '#64748B';
                        ctx.fillText('MEMORIES IN FRAME • ' + new Date().toLocaleDateString('id-ID'), canvas.width / 2, canvas.height - 45);

                        const frameDataUrl = canvas.toDataURL('image/jpeg', 0.95);
                        document.getElementById('frame-composite-img').src = frameDataUrl;
                        document.getElementById('print-img').src = frameDataUrl;
                    }
                };
                img.src = src;
            });
        }

        // Print Trigger
        function triggerPrintPhoto() {
            window.print();
        }

        // Retake Active Photo Slot
        function retakeActivePhoto() {
            document.getElementById('screen-preview').classList.add('hidden');
            document.getElementById('screen-capture').classList.remove('hidden');
            currentPoseIndex = activeFeaturedIndex;
            executeSinglePoseCountdown();
        }

        // Modal "Download Your Session"
        function openDownloadModal() {
            document.getElementById('download-modal').classList.remove('hidden');
        }

        function closeDownloadModal() {
            document.getElementById('download-modal').classList.add('hidden');
        }

        function generateDownloadQrCode(url) {
            const qrContainer = document.getElementById('download-qrcode');
            qrContainer.innerHTML = '';
            if (typeof QRCode !== 'undefined') {
                new QRCode(qrContainer, {
                    text: url,
                    width: 140,
                    height: 140,
                    colorDark : "#0F172A",
                    colorLight : "#FAF4E8",
                    correctLevel : QRCode.CorrectLevel.M
                });
            }
        }

        function copySessionLink() {
            navigator.clipboard.writeText(GALLERY_URL).then(() => {
                alert('Tautan galeri berhasil disalin: ' + GALLERY_URL);
            }).catch(() => {
                alert('Tautan galeri: ' + GALLERY_URL);
            });
        }
    </script>
</body>
</html>

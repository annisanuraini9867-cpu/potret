@extends('layouts.app', ['title' => 'POTRET - Software Self-Photo Booth Kiosk & Langganan Premium'])

@section('content')
<div class="space-y-16 py-2">

    <!-- 1. Top Alert / Sticky Promo Banner -->
    <div id="top-promo-banner" class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-4 sm:p-5 border-2 border-amber-400/80 shadow-xl">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-center sm:text-left">
                <span class="px-3 py-1 rounded-full bg-rose-600 text-white text-xs font-black uppercase tracking-wider animate-pulse flex items-center gap-1 shrink-0">
                    🔥 FLASH SALE PROMO
                </span>
                <p class="text-xs sm:text-sm font-bold text-slate-100">
                    Diskon hingga <span class="text-amber-400 font-black">40% Semua Paket</span> + Gratis 50+ Template Frame Viral & Trial 14 Hari! Gunakan Kode: 
                    <span class="bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded border border-amber-400/40 font-mono font-black">PREMIUMBOOTH</span>
                </p>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <!-- Countdown Ticker -->
                <div class="flex items-center gap-1.5 text-xs font-bold bg-black/40 px-3 py-1.5 rounded-xl border border-white/10">
                    <span class="text-slate-400">Berakhir dalam:</span>
                    <span id="promo-countdown" class="text-amber-400 font-mono font-black">11:48:32</span>
                </div>

                <a href="#harga" class="px-4 py-1.5 rounded-xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-xs transition-all shadow-md shadow-amber-400/20 hover:scale-105 whitespace-nowrap">
                    Klaim Promo Diskon →
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Hero Section: SaaS Positioning for Photobooth App -->
    <section class="relative overflow-hidden rounded-3xl bg-[#F5BD23] text-slate-950 p-8 sm:p-14 shadow-2xl border-4 border-amber-300/60">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left Column: Copywriting & CTAs -->
            <div class="lg:col-span-7 space-y-6 z-10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/10 backdrop-blur-md text-xs font-black text-slate-900 border border-black/10">
                    <span>✨ SOFTWARE PHOTO BOOTH KIOSK ALL-IN-ONE #1</span>
                </div>

                <!-- Big Main Logo Banner -->
                <div class="max-w-xs sm:max-w-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="POTRET" class="w-full h-auto drop-shadow-md">
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-950 leading-tight">
                    Otomatiskan Studio Photo Booth Anda Tanpa Operator, <span class="text-blue-900 underline decoration-wavy decoration-blue-700">Raup Cuan Puluhan Juta</span>!
                </h1>

                <p class="text-slate-900 font-medium text-sm sm:text-base leading-relaxed max-w-xl">
                    Aplikasi kiosk mandiri lengkap untuk wirausaha photo booth & pemilik studio foto. Pengunjung bayar via QRIS otomatis, berpose dengan wireless remote shutter, pilih bingkai kolase 6 foto kekinian, cetak instan & unduh galeri cloud QR.
                </p>

                <!-- Key Value Badges -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 pt-1 text-xs font-black text-slate-900">
                    <div class="flex items-center gap-1.5 bg-black/5 px-2.5 py-2 rounded-xl border border-black/5">
                        <span class="text-emerald-700 font-black">✓</span> 100% Self-Service Kiosk
                    </div>
                    <div class="flex items-center gap-1.5 bg-black/5 px-2.5 py-2 rounded-xl border border-black/5">
                        <span class="text-emerald-700 font-black">✓</span> QRIS Otomatis Realtime
                    </div>
                    <div class="flex items-center gap-1.5 bg-black/5 px-2.5 py-2 rounded-xl border border-black/5">
                        <span class="text-emerald-700 font-black">✓</span> 50+ Frame Kolase 6 Foto
                    </div>
                </div>

                <!-- CTAs -->
                <div class="flex flex-wrap gap-3.5 pt-3">
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-[#F5BD23] font-black text-sm shadow-xl shadow-slate-900/30 transition-all hover:scale-105 flex items-center gap-2">
                        <span>🚀 Mulai Coba Gratis 14 Hari</span>
                    </a>

                    <a href="{{ route('booth.index') }}" 
                       class="px-7 py-4 rounded-2xl bg-[#1D4ED8] hover:bg-[#1E40AF] text-white font-black text-sm shadow-xl shadow-blue-700/20 transition-all hover:scale-105 flex items-center gap-2">
                        <span>📸 Live Demo Kiosk Booth</span>
                    </a>

                    <a href="#harga" 
                       class="px-6 py-4 rounded-2xl bg-white/80 hover:bg-white text-slate-900 font-bold text-sm backdrop-blur transition-all border border-white/50">
                        Lihat Daftar Harga →
                    </a>
                </div>

                <div class="flex items-center gap-4 text-xs font-semibold text-slate-800 pt-1">
                    <span class="flex items-center gap-1">⭐ <strong>4.9/5</strong> dari 150+ Mitra Studio</span>
                    <span>•</span>
                    <span>Tanpa Kartu Kredit</span>
                    <span>•</span>
                    <span>Setup Cepat 5 Menit</span>
                </div>
            </div>

            <!-- Right Column: Visual Mockup Showcase -->
            <div class="lg:col-span-5 relative z-10">
                <div class="relative mx-auto max-w-sm">
                    <!-- Kiosk Screen Preview Frame -->
                    <div class="bg-slate-900 p-4 rounded-3xl shadow-2xl border-4 border-slate-800 space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-800 text-xs">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            </div>
                            <span class="text-[10px] font-mono text-slate-400 font-bold">POTRET KIOSK v2.4</span>
                        </div>

                        <!-- Mini Screen UI -->
                        <div class="relative bg-[#F8C435] rounded-2xl p-4 text-center space-y-3 overflow-hidden">
                            <span class="inline-block text-[10px] font-black uppercase tracking-wider px-2 py-0.5 bg-black/10 rounded-full text-slate-900">
                                6-PHOTO COLLAGE STUDIO
                            </span>
                            
                            <h4 class="text-base font-black text-slate-950">
                                Siap Berpose Seru?
                            </h4>

                            <!-- Collage Preview Grid Mockup -->
                            <div class="grid grid-cols-2 gap-1.5 max-w-[200px] mx-auto p-2 bg-white rounded-xl shadow-md border border-slate-200">
                                <div class="aspect-square bg-slate-200 rounded flex items-center justify-center text-xs">📸 1</div>
                                <div class="aspect-square bg-slate-100 rounded flex items-center justify-center text-xs">✨ 2</div>
                                <div class="aspect-square bg-slate-100 rounded flex items-center justify-center text-xs">✌️ 3</div>
                                <div class="aspect-square bg-slate-200 rounded flex items-center justify-center text-xs">🎉 4</div>
                                <div class="aspect-square bg-slate-200 rounded flex items-center justify-center text-xs">😎 5</div>
                                <div class="aspect-square bg-slate-100 rounded flex items-center justify-center text-xs">🤍 6</div>
                            </div>

                            <div class="pt-1">
                                <span class="px-4 py-2 rounded-xl bg-slate-950 text-[#F5BD23] font-black text-xs inline-block shadow-md">
                                    START PHOTO SESSION →
                                </span>
                            </div>
                        </div>

                        <!-- Live Status Indicator -->
                        <div class="flex items-center justify-between text-[11px] text-slate-300 font-medium px-1">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                Kiosk Mode: <strong class="text-white">Online & Siap Pakai</strong>
                            </span>
                            <span class="text-amber-400 font-bold">Auto QRIS Active</span>
                        </div>
                    </div>

                    <!-- Floating Badge Card 1 -->
                    <div class="absolute -top-4 -left-4 bg-white/95 backdrop-blur p-3 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-2.5 animate-bounce" style="animation-duration: 4s;">
                        <span class="text-2xl">💰</span>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Omset Mitra Rata-Rata</p>
                            <p class="text-xs font-black text-slate-900">Rp 15.000.000+ /bln</p>
                        </div>
                    </div>

                    <!-- Floating Badge Card 2 -->
                    <div class="absolute -bottom-4 -right-4 bg-white/95 backdrop-blur p-3 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-2.5">
                        <span class="text-2xl">⚡</span>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Proses Cetak & QR</p>
                            <p class="text-xs font-black text-slate-900">Cuma 15 Detik Instan!</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Decorative Camera Aperture Artwork in Background -->
        <div class="absolute -bottom-16 -right-16 w-96 h-96 opacity-10 pointer-events-none">
            <svg viewBox="0 0 100 100" fill="currentColor" class="w-full h-full text-slate-900">
                <circle cx="50" cy="50" r="48" stroke="currentColor" stroke-width="4" fill="none"/>
                <polygon points="50,10 70,30 50,50 30,30" />
                <polygon points="90,50 70,70 50,50 70,30" />
                <polygon points="50,90 30,70 50,50 70,70" />
                <polygon points="10,50 30,30 50,50 30,70" />
            </svg>
        </div>
    </section>

    <!-- 3. Iklan Promo Card: "Special Launching Package Bundle" -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-950 text-white p-8 sm:p-12 border-2 border-amber-400/60 shadow-2xl">
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-8 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-black border border-amber-400/40">
                    🎁 PROMO BUNDLE LAUNCHING PREMIUM BOOTH
                </div>
                
                <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                    Langganan Sekarang & Ambil <span class="text-amber-400">Bonus Starter Kit Senilai Rp 2.500.000</span> Gratis!
                </h2>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl font-medium">
                    Khusus 20 pendaftar pertama bulan ini. Dapatkan seluruh perlengkapan digital dan dukungan teknis agar booth foto Anda langsung siap mendatangkan omset sejak hari pertama pemasangan.
                </p>

                <!-- Bonus list -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div class="flex items-start gap-2.5 bg-white/5 p-3 rounded-2xl border border-white/10">
                        <span class="text-lg">🎨</span>
                        <div>
                            <h4 class="text-xs font-black text-amber-300">50+ Template Frame Kolase Viral</h4>
                            <p class="text-[11px] text-slate-400 font-medium">Korean Pastel, Retro Film 35mm, Y2K, Minimalist Gold (Senilai Rp 750.000)</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 bg-white/5 p-3 rounded-2xl border border-white/10">
                        <span class="text-lg">💳</span>
                        <div>
                            <h4 class="text-xs font-black text-amber-300">Free Setup QRIS Otomatis</h4>
                            <p class="text-[11px] text-slate-400 font-medium">Integrasi pembayaran langsung tanpa biaya aktivasi gateway (Senilai Rp 500.000)</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 bg-white/5 p-3 rounded-2xl border border-white/10">
                        <span class="text-lg">📖</span>
                        <div>
                            <h4 class="text-xs font-black text-amber-300">E-Book Blueprint Bisnis Booth</h4>
                            <p class="text-[11px] text-slate-400 font-medium">Panduan hardware terbaik, pricing tiket, & trik viral TikTok (Senilai Rp 350.000)</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 bg-white/5 p-3 rounded-2xl border border-white/10">
                        <span class="text-lg">👨‍💻</span>
                        <div>
                            <h4 class="text-xs font-black text-amber-300">1-on-1 Remote Setup Engineering</h4>
                            <p class="text-[11px] text-slate-400 font-medium">Didampingi teknisi sampai kiosk, kamera, dan printer siap (Senilai Rp 900.000)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20 text-center space-y-4">
                <span class="text-xs font-black tracking-widest text-amber-400 uppercase">PENUTUPAN PROMO</span>
                
                <!-- Dynamic countdown clock -->
                <div class="grid grid-cols-3 gap-2 text-slate-900">
                    <div class="bg-white rounded-2xl p-2.5">
                        <span id="countdown-hours" class="block text-2xl font-black font-mono">11</span>
                        <span class="text-[9px] font-black uppercase text-slate-500">Jam</span>
                    </div>
                    <div class="bg-white rounded-2xl p-2.5">
                        <span id="countdown-minutes" class="block text-2xl font-black font-mono">48</span>
                        <span class="text-[9px] font-black uppercase text-slate-500">Menit</span>
                    </div>
                    <div class="bg-white rounded-2xl p-2.5">
                        <span id="countdown-seconds" class="block text-2xl font-black font-mono">32</span>
                        <span class="text-[9px] font-black uppercase text-slate-500">Detik</span>
                    </div>
                </div>

                <div class="pt-2">
                    <p class="text-xs text-slate-300 font-medium mb-3">Sisa Kuota Promo Hari Ini: <span class="font-black text-rose-400">Tersisa 3 Studio</span></p>
                    <a href="#harga" class="block w-full py-3.5 px-4 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amber-500/20 transition-all hover:scale-105">
                        Ambil Promo Sekarang →
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- 4. Pricing Plans Section: Harga Berlangganan Software -->
    <section id="harga" class="space-y-10 scroll-mt-24">
        <div class="text-center space-y-3 max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-black">
                🏷️ HARGA BERLANGGANAN APLIKASI
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Pilih Paket Investasi Studio Anda</h2>
            <p class="text-slate-600 text-sm leading-relaxed">
                Biaya software sangat terjangkau dibanding potensi omset jutaan rupiah per hari. Semua paket sudah termasuk update berkala dan tanpa biaya tersembunyi.
            </p>

            <!-- Billing Toggle: Monthly vs Yearly (Save 25%) -->
            <div class="inline-flex items-center gap-3 p-1.5 bg-slate-200/80 rounded-2xl mt-4">
                <button type="button" id="btn-billing-monthly" onclick="setBilling('monthly')" 
                        class="px-5 py-2 rounded-xl text-xs font-black transition-all bg-white text-slate-950 shadow-sm">
                    Bayar Bulanan
                </button>
                <button type="button" id="btn-billing-yearly" onclick="setBilling('yearly')" 
                        class="px-5 py-2 rounded-xl text-xs font-black transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
                    <span>Bayar Tahunan</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-black uppercase">Hemat 25%</span>
                </button>
            </div>
        </div>

        <!-- 3 Pricing Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            
            <!-- Plan 1: Starter Booth -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-7 sm:p-8 flex flex-col justify-between hover:border-slate-400 hover:shadow-xl transition-all duration-300">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-black">
                            Untuk 1 Booth Pop-up
                        </span>
                        <h3 class="font-black text-2xl text-slate-900">Starter Booth</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Cocok untuk booth kafe kecil, pop-up event berkala, atau studio foto yang baru mulai merintis.
                        </p>
                    </div>

                    <!-- Price Display -->
                    <div class="pt-2 border-t border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="price-monthly text-3xl font-black text-slate-900">Rp 149.000</span>
                            <span class="price-yearly hidden text-3xl font-black text-slate-900">Rp 111.750</span>
                            <span class="text-xs text-slate-500 font-bold">/ bulan</span>
                        </div>
                        <p class="price-monthly text-xs text-rose-500 font-bold line-through">Rp 249.000 / bln</p>
                        <p class="price-yearly hidden text-xs text-emerald-600 font-bold">Ditagih Rp 1.341.000 / tahun (Hemat Rp 447.000)</p>
                    </div>

                    <!-- Feature List -->
                    <ul class="text-xs space-y-3 text-slate-700 pt-2 border-t border-slate-100 font-medium">
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> <strong>1 Kiosk Booth</strong> Aktif
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Wireless Remote Shutter & Countdown
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> 10 Template Frame Kolase Dasar
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Unduh Foto via QR Code Cloud (30 Hari)
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Cetak Otomatis Printer 4R / Kolase
                        </li>
                        <li class="flex items-center gap-2.5 text-slate-400">
                            <span class="text-slate-300">✕</span> <span>Custom Branding / White-Label Studio</span>
                        </li>
                        <li class="flex items-center gap-2.5 text-slate-400">
                            <span class="text-slate-300">✕</span> <span>Integrasi QRIS Otomatis Realtime</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-8">
                    <a id="btn-link-starter" href="{{ route('register', ['plan' => 'starter', 'billing' => 'monthly']) }}" 
                       class="block w-full py-4 px-4 rounded-2xl text-center font-black text-xs bg-slate-100 hover:bg-slate-200 text-slate-900 transition duration-200 shadow-sm">
                        Pilih Starter Booth →
                    </a>
                </div>
            </div>

            <!-- Plan 2: Studio Pro (BEST VALUE & RECOMMENDED) -->
            <div class="relative bg-white rounded-3xl border-4 border-[#F5BD23] p-7 sm:p-8 flex flex-col justify-between shadow-2xl shadow-amber-500/10 hover:-translate-y-1 transition-all duration-300">
                <!-- Most Popular Badge -->
                <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full bg-[#F5BD23] text-slate-950 text-xs font-black shadow-md uppercase tracking-wider flex items-center gap-1.5">
                    <span>⭐</span>
                    <span>PALING POPULER & BEST VALUE</span>
                </div>

                <div class="space-y-6 pt-2">
                    <div class="space-y-2">
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-black">
                            Rekomendasi Pengusaha Studio
                        </span>
                        <h3 class="font-black text-2xl text-slate-900">Studio Pro</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Pilihan utama pemilik studio self-photo modern. Otomatisasi total tanpa operator, siap cetak & terima bayaran non-stop.
                        </p>
                    </div>

                    <!-- Price Display -->
                    <div class="pt-2 border-t border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="price-monthly text-3xl font-black text-[#1D4ED8]">Rp 250.000</span>
                            <span class="price-yearly hidden text-3xl font-black text-[#1D4ED8]">Rp 187.500</span>
                            <span class="text-xs text-slate-500 font-bold">/ bulan</span>
                        </div>
                        <p class="price-monthly text-xs text-rose-500 font-bold line-through">Rp 450.000 / bln</p>
                        <p class="price-yearly hidden text-xs text-emerald-600 font-bold">Ditagih Rp 2.250.000 / tahun (Hemat Rp 750.000)</p>
                    </div>

                    <!-- Feature List -->
                    <ul class="text-xs space-y-3 text-slate-800 pt-2 border-t border-slate-100 font-medium">
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> <strong>Unlimited Sesi Foto & Tamu</strong>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> <strong>Integrasi QRIS Dinamis Otomatis</strong>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> <strong>50+ Desain Frame Kolase Viral</strong> (Korea, Retro, Y2K)
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> <strong>White-Label Studio</strong> (Logo & Branding Anda Sendiri)
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Cetak Instan Kompatibel Seluruh Printer Foto
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Konsol Pemantauan & Analitik Omset Realtime
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Dukungan Prioritas WhatsApp 24/7
                        </li>
                    </ul>
                </div>

                <div class="pt-8">
                    <a id="btn-link-pro" href="{{ route('register', ['plan' => 'pro', 'billing' => 'monthly']) }}" 
                       class="block w-full py-4 px-4 rounded-2xl text-center font-black text-sm bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 transition duration-200 shadow-lg shadow-amber-500/25 hover:scale-[1.02]">
                        Langganan Studio Pro Sekarang →
                    </a>
                </div>
            </div>

            <!-- Plan 3: Business Multi-Booth -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-7 sm:p-8 flex flex-col justify-between hover:border-slate-400 hover:shadow-xl transition-all duration-300">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-900 text-xs font-black">
                            Multi-Cabang & Franchise
                        </span>
                        <h3 class="font-black text-2xl text-slate-900">Business Multi-Booth</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Solusi lengkap bagi pemilik banyak cabang kiosk di mall, jaringan franchise studio, atau rental event skala besar.
                        </p>
                    </div>

                    <!-- Price Display -->
                    <div class="pt-2 border-t border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="price-monthly text-3xl font-black text-slate-900">Rp 499.000</span>
                            <span class="price-yearly hidden text-3xl font-black text-slate-900">Rp 374.250</span>
                            <span class="text-xs text-slate-500 font-bold">/ bulan</span>
                        </div>
                        <p class="price-monthly text-xs text-rose-500 font-bold line-through">Rp 899.000 / bln</p>
                        <p class="price-yearly hidden text-xs text-emerald-600 font-bold">Ditagih Rp 4.491.000 / tahun (Hemat Rp 1.497.000)</p>
                    </div>

                    <!-- Feature List -->
                    <ul class="text-xs space-y-3 text-slate-700 pt-2 border-t border-slate-100 font-medium">
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> <strong>Hingga 5 Kiosk Booth</strong> Aktif Sekaligus
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Multi-Cabang & Manajemen Kasir/Operator
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Custom Frame Builder (Upload Desain Sendiri)
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> High-Speed Cloud Storage Selamanya
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Kustom Domain Studio (studioanda.com)
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span> Dedicated Account Manager & Remote Setup
                        </li>
                    </ul>
                </div>

                <div class="pt-8">
                    <a id="btn-link-business" href="{{ route('register', ['plan' => 'business', 'billing' => 'monthly']) }}" 
                       class="block w-full py-4 px-4 rounded-2xl text-center font-black text-xs bg-slate-900 hover:bg-slate-800 text-white transition duration-200 shadow-md">
                        Pilih Multi-Booth →
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- 5. Kalkulator Simulasi Cuan / ROI -->
    <section id="kalkulator" class="bg-gradient-to-br from-amber-500/10 via-white to-blue-500/10 rounded-3xl p-8 sm:p-12 border-2 border-amber-300/80 shadow-md scroll-mt-24 space-y-8">
        <div class="text-center space-y-2 max-w-2xl mx-auto">
            <span class="px-3 py-1 rounded-full bg-amber-200 text-amber-950 text-xs font-black uppercase tracking-wider">
                🧮 SIMULASI KEUNTUNGAN (ROI CALCULATOR)
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Hitung Potensi Cuan Studio Anda Tiap Bulan</h2>
            <p class="text-xs sm:text-sm text-slate-600 font-medium">
                Geser slider di bawah untuk melihat bagaimana software seharga Rp 250.000/bln mampu menghasilkan omset belasan hingga puluhan juta rupiah.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center max-w-4xl mx-auto">
            
            <!-- Sliders Inputs -->
            <div class="lg:col-span-6 space-y-6 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm">
                <!-- Slider 1: Sesi per hari -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-800">
                        <span>Estimasi Sesi Foto Per Hari:</span>
                        <span id="label-sessions" class="text-base font-black text-[#1D4ED8] bg-blue-50 px-2.5 py-1 rounded-lg">15 Sesi</span>
                    </div>
                    <input type="range" id="slider-sessions" min="3" max="60" value="15" step="1" 
                           class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#1D4ED8]"
                           oninput="calculateRoi()">
                    <div class="flex justify-between text-[10px] text-slate-400 font-semibold">
                        <span>3 Sesi (Sepi)</span>
                        <span>15 Sesi (Rata-rata)</span>
                        <span>60 Sesi (Ramai Mall)</span>
                    </div>
                </div>

                <!-- Slider 2: Tarif per sesi -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-800">
                        <span>Tarif Tiket Foto Per Sesi:</span>
                        <span id="label-price" class="text-base font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Rp 35.000</span>
                    </div>
                    <input type="range" id="slider-price" min="15000" max="100000" value="35000" step="5000" 
                           class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600"
                           oninput="calculateRoi()">
                    <div class="flex justify-between text-[10px] text-slate-400 font-semibold">
                        <span>Rp 15.000</span>
                        <span>Rp 35.000 (Standar)</span>
                        <span>Rp 100.000 (Premium)</span>
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl text-[11px] text-slate-500 leading-relaxed border border-slate-100 flex items-start gap-2">
                    <span class="text-blue-600 font-bold">💡</span>
                    <span>Dengan sistem self-service mandiri, kiosk Anda dapat melayani tamu tanpa henti selama jam buka toko tanpa butuh upah lembur operator.</span>
                </div>
            </div>

            <!-- Results Output -->
            <div class="lg:col-span-6 bg-slate-900 text-white p-7 sm:p-8 rounded-3xl shadow-xl space-y-5 border-2 border-slate-800">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Estimasi Omset Harian</span>
                    <span id="res-daily" class="text-lg font-black text-amber-400">Rp 525.000</span>
                </div>

                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Estimasi Omset Bulanan</span>
                    <span id="res-monthly" class="text-xl font-black text-white">Rp 15.750.000</span>
                </div>

                <div class="flex items-center justify-between border-b border-slate-800 pb-3 text-slate-400 text-xs">
                    <span>Biaya Software POTRET (Pro):</span>
                    <span class="font-bold text-rose-400">- Rp 250.000</span>
                </div>

                <div class="pt-2">
                    <span class="text-xs text-slate-400 uppercase font-black tracking-widest block">POTENSI PROFIT BERSIH:</span>
                    <div class="flex items-baseline gap-2 pt-1">
                        <span id="res-profit" class="text-3xl sm:text-4xl font-black text-emerald-400">Rp 15.500.000</span>
                        <span class="text-xs text-slate-400 font-bold">/ bulan</span>
                    </div>
                    <p id="res-roi" class="text-xs text-amber-300 font-bold mt-1">ROI: Balik modal 62x lipat dari biaya software!</p>
                </div>

                <div class="pt-2">
                    <a href="{{ route('register', ['plan' => 'pro']) }}" 
                       class="block w-full py-3.5 px-4 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-xs text-center transition-all shadow-md hover:scale-105">
                        Mulai Hasilkan Cuan Studio Sekarang →
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- 6. Fitur Unggulan Software (Kenapa Harus Berlangganan?) -->
    <section id="fitur" class="space-y-8 scroll-mt-24">
        <div class="text-center space-y-2 max-w-2xl mx-auto">
            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-900 text-xs font-black uppercase tracking-wider">
                ⚡ TEKNOLOGI PHOTO BOOTH TERCANGGIH
            </span>
            <h2 class="text-3xl font-black text-slate-900">Dirancang Khusus untuk Bisnis Mandiri Anda</h2>
            <p class="text-slate-500 text-sm">Semua fitur yang Anda butuhkan untuk menjalankan photobooth otomatis ada dalam satu software.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Feature 1 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-3 hover:border-amber-400 transition">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-2xl text-amber-700">
                    🤖
                </div>
                <h3 class="font-black text-lg text-slate-900">100% Self-Service Kiosk</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    Tamu memilih jumlah cetakan, bayar via QRIS, berpose mandiri dengan remote wireless shutter, hingga cetak foto tanpa perlu bantuan operator sama sekali.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-3 hover:border-blue-400 transition">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl text-blue-700">
                    🖼️
                </div>
                <h3 class="font-black text-lg text-slate-900">Kolase 6 Foto Estetik Kekinian</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    Koleksi frame 6 slot foto viral: Korean Pastel, 35mm Retro Filmstrip, Minimalist Noir, dan Gold Edition. Tersedia opsi upload template brand kustom Anda.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-3 hover:border-emerald-400 transition">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl text-emerald-700">
                    💳
                </div>
                <h3 class="font-black text-lg text-slate-900">Pembayaran QRIS Dinamis Instan</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    QRIS otomatis terintegrasi. Sesi foto otomatis terbuka setelah pembayaran selesai. Dana langsung masuk ke rekening bank / e-wallet studio Anda.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-3 hover:border-purple-400 transition">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center text-2xl text-purple-700">
                    🖨️
                </div>
                <h3 class="font-black text-lg text-slate-900">Cetak Cepat ke Semua Printer</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    Kompatibel dengan printer foto dye-sublimation (DNP RX1HS, DS620, Citizen, Canon Selphy) maupun printer inkjet (Epson L8050) dalam ukuran 4R atau strip foto.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-3 hover:border-rose-400 transition">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center text-2xl text-rose-700">
                    ☁️
                </div>
                <h3 class="font-black text-lg text-slate-900">Galeri Cloud QR Instan</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    Pengunjung langsung mendapatkan QR code di layar setelah sesi selesai untuk mengunduh semua file foto dan kolase resolusi tinggi ke smartphone mereka.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-3 hover:border-cyan-400 transition">
                <div class="w-12 h-12 rounded-2xl bg-cyan-100 flex items-center justify-center text-2xl text-cyan-700">
                    📊
                </div>
                <h3 class="font-black text-lg text-slate-900">Konsol Admin & Hotspot PIN</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">
                    Akses dashboard admin tersembunyi dengan PIN keypad 6-digit. Pantau omset harian, status kiosk online/offline, dan atur template dari mana saja.
                </p>
            </div>

        </div>
    </section>

    <!-- 7. Tabel Perbandingan Fitur Paket -->
    <section class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-sm space-y-6">
        <div class="text-center space-y-2 max-w-xl mx-auto">
            <h3 class="text-2xl font-black text-slate-900">Komparasi Detail Paket Langganan</h3>
            <p class="text-xs text-slate-500 font-medium">Bandingkan fitur spesifik untuk menemukan paket yang paling tepat untuk skala bisnis Anda.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b-2 border-slate-200">
                        <th class="py-4 px-4 font-black text-slate-900 text-sm">Fitur & Kapabilitas</th>
                        <th class="py-4 px-4 font-black text-slate-700 text-center">Starter</th>
                        <th class="py-4 px-4 font-black text-[#1D4ED8] text-center bg-blue-50/50 rounded-t-2xl">Studio Pro ⭐</th>
                        <th class="py-4 px-4 font-black text-slate-900 text-center">Business</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr>
                        <td class="py-3.5 px-4 font-bold">Jumlah Kiosk Booth Aktif</td>
                        <td class="py-3.5 px-4 text-center">1 Booth</td>
                        <td class="py-3.5 px-4 text-center font-bold text-[#1D4ED8] bg-blue-50/30">1 Booth Utama</td>
                        <td class="py-3.5 px-4 text-center font-bold">Hingga 5 Booth</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-4 font-bold">Batas Sesi Foto Pengunjung</td>
                        <td class="py-3.5 px-4 text-center">300 Sesi / bln</td>
                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600 bg-blue-50/30">Unlimited Bebas</td>
                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600">Unlimited Bebas</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-4 font-bold">Integrasi QRIS Otomatis</td>
                        <td class="py-3.5 px-4 text-center text-slate-300">✕</td>
                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600 bg-blue-50/30">✓ (Dinamis Instan)</td>
                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600">✓ (Dinamis Instan)</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-4 font-bold">Koleksi Template Frame Kolase 6 Foto</td>
                        <td class="py-3.5 px-4 text-center">10 Frame Dasar</td>
                        <td class="py-3.5 px-4 text-center font-bold text-[#1D4ED8] bg-blue-50/30">50+ Frame Viral</td>
                        <td class="py-3.5 px-4 text-center font-bold">50+ Frame + Custom Upload</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-4 font-bold">White-Label & Logo Studio Kustom</td>
                        <td class="py-3.5 px-4 text-center text-slate-300">✕</td>
                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600 bg-blue-50/30">✓ Ya</td>
                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600">✓ Ya (Total Branding)</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-4 font-bold">Penyimpanan Cloud Galeri Tamu</td>
                        <td class="py-3.5 px-4 text-center">30 Hari</td>
                        <td class="py-3.5 px-4 text-center font-bold text-[#1D4ED8] bg-blue-50/30">90 Hari</td>
                        <td class="py-3.5 px-4 text-center font-bold">Selamanya (Unlimited)</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-4 font-bold">Dukungan Teknis & Customer Support</td>
                        <td class="py-3.5 px-4 text-center">Email / Komunitas</td>
                        <td class="py-3.5 px-4 text-center font-bold text-emerald-600 bg-blue-50/30">WhatsApp Prioritas 24/7</td>
                        <td class="py-3.5 px-4 text-center font-bold">Dedicated Account Manager</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- 8. Testimoni Pemilik Studio & Social Proof -->
    <section class="space-y-8">
        <div class="text-center space-y-2 max-w-xl mx-auto">
            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-black uppercase tracking-wider">
                💬 KATA MITRA STUDIO KAMI
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Dipercaya Oleh 150+ Pengusaha Photo Booth</h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium">Lihat bagaimana software POTRET mengubah operasional studio foto mereka.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Testimonial 1 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="text-amber-400 text-sm">★★★★★</div>
                    <p class="text-xs text-slate-700 leading-relaxed italic font-medium">
                        "Sistem self-service tanpa operator bikin saya hemat biaya gaji staf sekitar Rp 3.500.000 per bulan. Tamu muda-mudi suka banget karena privasi mereka terjaga saat berpose!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-900 font-black flex items-center justify-center text-sm">
                        RN
                    </div>
                    <div>
                        <h4 class="font-black text-xs text-slate-900">Rian Pratama</h4>
                        <p class="text-[10px] text-slate-500 font-semibold">Owner Nostalgia Photo Club, Bandung</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="text-amber-400 text-sm">★★★★★</div>
                    <p class="text-xs text-slate-700 leading-relaxed italic font-medium">
                        "Fitur QRIS otomatisnya bener-bener game changer. Pengunjung tinggal scan bayar, kiosk langsung jalan sendiri. Omset harian saya pantau dari kasur lewat dashboard admin!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-900 font-black flex items-center justify-center text-sm">
                        AS
                    </div>
                    <div>
                        <h4 class="font-black text-xs text-slate-900">Amanda Safira</h4>
                        <p class="text-[10px] text-slate-500 font-semibold">Founder SnapSpot Studio, Jakarta Selatan</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="text-amber-400 text-sm">★★★★★</div>
                    <p class="text-xs text-slate-700 leading-relaxed italic font-medium">
                        "Paket Studio Pro seharga 250rb sebulan langsung balik modal di hari pertama operasi. Template frame 6 fotonya estetik banget, pelanggan sering upload ke story IG!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-900 font-black flex items-center justify-center text-sm">
                        DK
                    </div>
                    <div>
                        <h4 class="font-black text-xs text-slate-900">Dimas Kurniawan</h4>
                        <p class="text-[10px] text-slate-500 font-semibold">Co-Owner Kilas Cerita Booth, Surabaya</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. FAQ Section (Pertanyaan Umum) -->
    <section class="bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200 space-y-6">
        <div class="text-center space-y-2 max-w-xl mx-auto">
            <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-800 text-xs font-black uppercase tracking-wider">
                ❓ FAQ
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium">Informasi lengkap seputar software, hardware, dan sistem pembayaran.</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-3 text-xs">
            
            <details class="group bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm transition">
                <summary class="font-black text-slate-900 cursor-pointer flex justify-between items-center select-none text-sm">
                    <span>Hardware apa saja yang saya perlukan untuk menjalankan booth?</span>
                    <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="pt-3 text-slate-600 leading-relaxed font-medium">
                    Anda hanya memerlukan: 
                    (1) Laptop atau Mini PC dengan sistem operasi Windows 10/11, 
                    (2) Layar monitor (layar sentuh lebih disarankan untuk pengalaman kiosk maksimal), 
                    (3) Kamera (bisa DSLR Canon/Nikon/Sony atau Webcam Full HD), 
                    (4) Printer foto (printer dye-sublimation seperti DNP RX1HS/DS620/Canon Selphy atau printer inkjet seperti Epson L8050), dan 
                    (5) Shutter remote nirkabel (wireless remote). Tim teknisi kami siap memandu Anda memilih hardware terbaik sesuai budget.
                </div>
            </details>

            <details class="group bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm transition">
                <summary class="font-black text-slate-900 cursor-pointer flex justify-between items-center select-none text-sm">
                    <span>Apakah saya bisa mencoba software ini secara gratis?</span>
                    <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="pt-3 text-slate-600 leading-relaxed font-medium">
                    Tentu saja! Kami memberikan masa uji coba gratis selama 14 hari penuh tanpa perlu kartu kredit. Anda juga dapat mencoba demo interaktif antarmuka kiosk langsung di browser Anda melalui tombol "Demo Kiosk".
                </div>
            </details>

            <details class="group bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm transition">
                <summary class="font-black text-slate-900 cursor-pointer flex justify-between items-center select-none text-sm">
                    <span>Bagaimana cara kerja pembayaran QRIS otomatis?</span>
                    <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="pt-3 text-slate-600 leading-relaxed font-medium">
                    Pada paket Studio Pro dan Business, sistem menyediakan QRIS dinamis otomatis untuk setiap sesi pemotretan. Saat pengunjung scan dan bayar melalui Gopay, OVO, Dana, BCA, atau mobile banking lainnya, sistem mendeteksi pembayaran secara realtime dan langsung membuka sesi pemotretan. Dana langsung masuk ke rekening studio Anda.
                </div>
            </details>

            <details class="group bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm transition">
                <summary class="font-black text-slate-900 cursor-pointer flex justify-between items-center select-none text-sm">
                    <span>Apakah saya bisa berhenti berlangganan kapan saja?</span>
                    <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="pt-3 text-slate-600 leading-relaxed font-medium">
                    Ya, tidak ada kontrak yang mengikat. Anda dapat menghentikan atau mengubah paket langganan kapan saja melalui konsol admin studio Anda tanpa dikenakan denda apapun.
                </div>
            </details>

            <details class="group bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm transition">
                <summary class="font-black text-slate-900 cursor-pointer flex justify-between items-center select-none text-sm">
                    <span>Bagaimana jika saya bingung melakukan setup pertama kali?</span>
                    <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="pt-3 text-slate-600 leading-relaxed font-medium">
                    Jangan khawatir! Tim teknisi kami akan mendampingi Anda secara 1-on-1 melalui remote session (AnyDesk / TeamViewer) dan WhatsApp call sampai aplikasi terhubung dengan kamera, printer, dan siap digunakan untuk melayani pengunjung.
                </div>
            </details>

        </div>
    </section>

    <!-- 10. Akses Tamu: Ambil Foto Sesi yang Sudah Selesai -->
    <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <div class="max-w-xl mx-auto text-center space-y-3">
            <span class="text-3xl">📥</span>
            <h3 class="text-xl font-black text-slate-900">Pelanggan Studio? Ingin Mengunduh Hasil Foto?</h3>
            <p class="text-xs text-slate-500 font-medium">
                Jika Anda adalah tamu yang baru saja berfoto di salah satu booth mitra kami, masukkan kode booking unik Anda untuk mengunduh kolase foto digital Anda.
            </p>
            <form action="{{ route('gallery.search') }}" method="POST" class="flex flex-col sm:flex-row gap-2 pt-2">
                @csrf
                <input type="text" name="booking_code" placeholder="Contoh: PTD-20260901-XXXXX" required class="flex-1 px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-[#F5BD23] text-xs focus:outline-none uppercase font-mono font-bold">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-black text-xs transition">
                    Lihat & Unduh Foto
                </button>
            </form>
        </div>
    </section>

    <!-- 11. Bottom Floating / Sticky Promo Bar -->
    <div id="floating-promo-bar" class="fixed bottom-4 left-4 right-4 max-w-4xl mx-auto z-40 bg-slate-900/95 backdrop-blur-md text-white p-3.5 sm:p-4 rounded-2xl shadow-2xl border-2 border-[#F5BD23] flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl hidden sm:inline">🚀</span>
            <div>
                <p class="text-xs sm:text-sm font-black text-white flex items-center gap-2">
                    <span>Mulai Bisnis Photo Booth Anda Hari Ini!</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-[#F5BD23] text-slate-950 uppercase">Trial 14 Hari</span>
                </p>
                <p class="text-[11px] text-slate-300 font-medium hidden sm:block">
                    Klaim diskon 40% dan bonus template frame eksklusif sebelum kupon promo habis.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('register', ['plan' => 'pro']) }}" 
               class="px-5 py-2.5 rounded-xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition hover:scale-105 whitespace-nowrap">
                Daftar & Berlangganan →
            </a>
            <button type="button" onclick="document.getElementById('floating-promo-bar').style.display='none'" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-400 hover:text-white flex items-center justify-center text-xs transition">
                ✕
            </button>
        </div>
    </div>

</div>

<!-- Interactive Scripts for Billing Toggle, ROI Calculator, and Countdown -->
<script>
    let currentBilling = 'monthly';

    function setBilling(cycle) {
        currentBilling = cycle;
        const btnMonthly = document.getElementById('btn-billing-monthly');
        const btnYearly = document.getElementById('btn-billing-yearly');
        const monthlyPrices = document.querySelectorAll('.price-monthly');
        const yearlyPrices = document.querySelectorAll('.price-yearly');

        const linkStarter = document.getElementById('btn-link-starter');
        const linkPro = document.getElementById('btn-link-pro');
        const linkBusiness = document.getElementById('btn-link-business');

        if (cycle === 'yearly') {
            btnYearly.className = 'px-5 py-2 rounded-xl text-xs font-black transition-all bg-white text-slate-950 shadow-sm flex items-center gap-1.5';
            btnMonthly.className = 'px-5 py-2 rounded-xl text-xs font-black transition-all text-slate-600 hover:text-slate-900';
            
            monthlyPrices.forEach(el => el.classList.add('hidden'));
            yearlyPrices.forEach(el => el.classList.remove('hidden'));

            linkStarter.href = "{{ route('register') }}?plan=starter&billing=yearly";
            linkPro.href = "{{ route('register') }}?plan=pro&billing=yearly";
            linkBusiness.href = "{{ route('register') }}?plan=business&billing=yearly";
        } else {
            btnMonthly.className = 'px-5 py-2 rounded-xl text-xs font-black transition-all bg-white text-slate-950 shadow-sm';
            btnYearly.className = 'px-5 py-2 rounded-xl text-xs font-black transition-all text-slate-600 hover:text-slate-900 flex items-center gap-1.5';
            
            yearlyPrices.forEach(el => el.classList.add('hidden'));
            monthlyPrices.forEach(el => el.classList.remove('hidden'));

            linkStarter.href = "{{ route('register') }}?plan=starter&billing=monthly";
            linkPro.href = "{{ route('register') }}?plan=pro&billing=monthly";
            linkBusiness.href = "{{ route('register') }}?plan=business&billing=monthly";
        }
    }

    function calculateRoi() {
        const sessions = parseInt(document.getElementById('slider-sessions').value, 10);
        const price = parseInt(document.getElementById('slider-price').value, 10);

        document.getElementById('label-sessions').innerText = sessions + ' Sesi';
        document.getElementById('label-price').innerText = 'Rp ' + price.toLocaleString('id-ID');

        const dailyRevenue = sessions * price;
        const monthlyRevenue = dailyRevenue * 30;
        const softwareCost = 250000;
        const netProfit = monthlyRevenue - softwareCost;
        const roiMultiple = Math.round(monthlyRevenue / softwareCost);

        document.getElementById('res-daily').innerText = 'Rp ' + dailyRevenue.toLocaleString('id-ID');
        document.getElementById('res-monthly').innerText = 'Rp ' + monthlyRevenue.toLocaleString('id-ID');
        document.getElementById('res-profit').innerText = 'Rp ' + netProfit.toLocaleString('id-ID');
        document.getElementById('res-roi').innerText = `ROI: Balik modal ${roiMultiple}x lipat dari biaya software!`;
    }

    // Dynamic countdown timer simulation
    let secondsLeft = 11 * 3600 + 48 * 60 + 32;
    setInterval(() => {
        if (secondsLeft > 0) {
            secondsLeft--;
            const h = Math.floor(secondsLeft / 3600);
            const m = Math.floor((secondsLeft % 3600) / 60);
            const s = secondsLeft % 60;

            const pad = (n) => String(n).padStart(2, '0');
            const timeStr = `${pad(h)}:${pad(m)}:${pad(s)}`;

            const bannerEl = document.getElementById('promo-countdown');
            if (bannerEl) bannerEl.innerText = timeStr;

            const cdH = document.getElementById('countdown-hours');
            const cdM = document.getElementById('countdown-minutes');
            const cdS = document.getElementById('countdown-seconds');

            if (cdH) cdH.innerText = pad(h);
            if (cdM) cdM.innerText = pad(m);
            if (cdS) cdS.innerText = pad(s);
        }
    }, 1000);

    // Initial calculation on load
    document.addEventListener('DOMContentLoaded', () => {
        calculateRoi();
    });
</script>
@endsection

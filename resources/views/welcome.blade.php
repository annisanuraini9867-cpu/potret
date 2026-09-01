@extends('layouts.app', ['title' => 'Potret Diri - Studio Self-Photo Booth Modern'])

@section('content')
<div class="space-y-16 py-4">

    <!-- Hero Section with Custom Yellow & Royal Blue Theme -->
    <section class="relative overflow-hidden rounded-3xl bg-[#F5BD23] text-slate-950 p-8 sm:p-14 shadow-2xl border-4 border-amber-300/60">
        <div class="relative z-10 max-w-2xl space-y-6">
            
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/10 backdrop-blur-md text-xs font-black text-slate-900 border border-black/10">
                ✨ SELF-PHOTO BOOTH STUDIO MODERN
            </div>

            <!-- Big Main Logo Banner -->
            <div class="max-w-sm sm:max-w-md pt-2">
                <img src="{{ asset('images/logo.png') }}" alt="Potret Diri" class="w-full h-auto drop-shadow-md">
            </div>

            <p class="text-slate-900 font-medium text-base sm:text-lg leading-relaxed max-w-xl">
                Studio self-photo booth kekinian tanpa fotografer! Bebas berekspresi dengan wireless remote shutter, lighting profesional, beragam bingkai kolase 6 foto, dan download digital instan.
            </p>

            <div class="flex flex-wrap gap-3.5 pt-2">
                <a href="{{ route('booth.index') }}" 
                   class="px-7 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-[#F5BD23] font-black text-sm shadow-xl shadow-slate-900/30 transition-all hover:scale-105 flex items-center gap-2.5">
                    <span class="text-lg">📸</span>
                    <span>Masuk ke Photo Booth (Kiosk)</span>
                </a>

                <a href="{{ route('bookings.create') }}" 
                   class="px-7 py-4 rounded-2xl bg-[#1D4ED8] hover:bg-[#1E40AF] text-white font-black text-sm shadow-xl shadow-blue-700/20 transition-all hover:scale-105">
                    📅 Pesan Jadwal Sesi
                </a>

                <a href="{{ route('gallery.index') }}" 
                   class="px-6 py-4 rounded-2xl bg-white/70 hover:bg-white text-slate-900 font-bold text-sm backdrop-blur transition-all border border-white/40">
                    🔍 Cek & Unduh Foto
                </a>
            </div>
        </div>

        <!-- Decorative Camera Aperture Artwork in Background -->
        <div class="absolute -bottom-16 -right-16 w-96 h-96 opacity-15 pointer-events-none">
            <svg viewBox="0 0 100 100" fill="currentColor" class="w-full h-full text-slate-900">
                <circle cx="50" cy="50" r="48" stroke="currentColor" stroke-width="4" fill="none"/>
                <polygon points="50,10 70,30 50,50 30,30" />
                <polygon points="90,50 70,70 50,50 70,30" />
                <polygon points="50,90 30,70 50,50 70,70" />
                <polygon points="10,50 30,30 50,50 30,70" />
            </svg>
        </div>
    </section>

    <!-- Quick Search Gallery Box -->
    <section class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <div class="max-w-xl mx-auto text-center space-y-3">
            <span class="text-3xl">📥</span>
            <h2 class="text-2xl font-black text-slate-900">Sudah Selesai Sesi Foto?</h2>
            <p class="text-xs sm:text-sm text-slate-500">Masukkan kode unik booking Anda untuk melihat pratinjau hasil foto dan mengunduh seluruh file kolase 6 foto.</p>
            <form action="{{ route('gallery.search') }}" method="POST" class="flex flex-col sm:flex-row gap-2 pt-2">
                @csrf
                <input type="text" name="booking_code" placeholder="Contoh: PTD-20260901-XXXXX" required class="flex-1 px-4 py-3.5 rounded-2xl border-2 border-slate-200 focus:border-[#F5BD23] text-sm focus:outline-none uppercase font-mono font-bold">
                <button type="submit" class="px-7 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-black text-sm transition">
                    Lihat Galeri
                </button>
            </form>
        </div>
    </section>

    <!-- Package Catalogue Section -->
    <section id="packages" class="space-y-8">
        <div class="text-center space-y-2 max-w-2xl mx-auto">
            <h2 class="text-3xl font-black text-slate-900">Pilihan Paket Sesi Foto</h2>
            <p class="text-slate-500 text-sm">Pilih paket yang paling pas untuk Anda. Tanpa fotografer, bebas bergaya dengan remote shutter!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($packages as $pkg)
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 flex flex-col justify-between hover:border-[#F5BD23] hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <h3 class="font-black text-lg text-slate-900">{{ $pkg->name }}</h3>
                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-black">{{ $pkg->duration_minutes }} Mnt</span>
                    </div>
                    
                    <div class="text-2xl font-black text-[#1D4ED8]">
                        Rp {{ number_format($pkg->price, 0, ',', '.') }}
                    </div>

                    <p class="text-xs text-slate-500 leading-relaxed min-h-[60px]">
                        {{ $pkg->description }}
                    </p>

                    <ul class="text-xs space-y-2 text-slate-600 pt-2 border-t border-slate-100 font-medium">
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-500 font-bold">✓</span> Maksimal {{ $pkg->max_persons }} Orang
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-500 font-bold">✓</span> Unlimited Shots & Remote Shutter
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-500 font-bold">✓</span> Desain Kolase 6 Foto Bebas Pilih
                        </li>
                    </ul>
                </div>

                <div class="pt-6">
                    <a href="{{ route('bookings.create', ['package_id' => $pkg->id]) }}" class="block w-full py-3 px-4 rounded-2xl text-center font-black text-xs bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 transition duration-200 shadow-sm">
                        Pesan Paket Ini →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="bg-amber-50 rounded-3xl p-8 sm:p-12 border border-amber-200 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="space-y-2">
            <div class="text-3xl">🤫</div>
            <h3 class="font-black text-slate-900 text-base">100% Privat & Bebas Gaya</h3>
            <p class="text-xs text-slate-600 leading-relaxed font-medium">Ruangan studio sepenuhnya tertutup tanpa fotografer, cocok untuk yang ingin berekspresi tanpa canggung.</p>
        </div>
        <div class="space-y-2">
            <div class="text-3xl">🖼</div>
            <h3 class="font-black text-slate-900 text-base">Kolase 6 Foto Estetik</h3>
            <p class="text-xs text-slate-600 leading-relaxed font-medium">Pilih aneka bingkai foto 6 slot (Korean Pastel, 35mm Retro Filmstrip, Minimalist, Gold, dll) sebelum mulai sesi jepret.</p>
        </div>
        <div class="space-y-2">
            <div class="text-3xl">⚡</div>
            <h3 class="font-black text-slate-900 text-base">Cetak 4R & Softcopy HD</h3>
            <p class="text-xs text-slate-600 leading-relaxed font-medium">Selesai berfoto, kolase siap diunduh beresolusi tinggi dan langsung dapat dicetak dengan printer studio.</p>
        </div>
    </section>

</div>
@endsection

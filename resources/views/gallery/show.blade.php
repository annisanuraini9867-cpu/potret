@extends('layouts.app', ['title' => 'Galeri Foto Sesi - ' . $booking->booking_code])

@section('content')
<div class="space-y-8">
    <!-- Header Galeri -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Halo, {{ $booking->customer_name }}! 👋</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold font-mono bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ $booking->booking_code }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Paket: <strong class="text-slate-700">{{ $booking->package->name }}</strong> | Tanggal: <strong class="text-slate-700">{{ $booking->booking_date->format('d M Y') }}</strong> ({{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB)
            </p>
        </div>

        @if($booking->photos->isNotEmpty())
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('booth.session', $booking->booking_code) }}" class="inline-flex items-center gap-1.5 px-4 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow transition">
                <span>📸 Sesi Foto Ulang</span>
            </a>
            <a href="{{ route('gallery.downloadZip', $booking->booking_code) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow transition">
                <span>📦 Unduh Semua (ZIP)</span>
                <span class="text-[10px] bg-emerald-800 px-2 py-0.5 rounded-full">{{ $booking->photos->count() }} Foto</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Featured Collage Showcase if Available -->
    @php
        $collage = $booking->photos->firstWhere('is_collage', true);
        $individualPhotos = $booking->photos->where('is_collage', false);
    @endphp

    @if($collage)
    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-center gap-8">
        <div class="w-full md:w-5/12 max-w-xs rounded-2xl overflow-hidden shadow-2xl border-4 border-white/20">
            <img src="{{ $collage->url }}" alt="Kolase 6 Foto" class="w-full h-auto">
        </div>
        <div class="flex-1 space-y-4 text-center md:text-left">
            <span class="px-3 py-1 rounded-full bg-rose-500 text-white text-xs font-black uppercase tracking-wider">
                ⭐ Hasil Cetak Kolase 6 Foto
            </span>
            <h2 class="text-2xl sm:text-3xl font-black">Bingkai Kolase Pilihan Anda</h2>
            <p class="text-xs sm:text-sm text-indigo-200 leading-relaxed">
                Ini adalah karya kolase 6 foto beresolusi tinggi yang digabungkan ke dalam bingkai pilihan Anda saat sesi Photo Booth. Siap untuk dicetak ukuran 4R atau dibagikan ke media sosial!
            </p>
            <div class="flex flex-wrap justify-center md:justify-start gap-3 pt-2">
                <a href="{{ $collage->url }}" download="{{ $collage->file_name }}" class="px-5 py-3 rounded-xl bg-white text-slate-900 font-extrabold text-xs shadow hover:bg-slate-100 transition flex items-center gap-2">
                    <span>⬇ Unduh Kolase HD</span>
                </a>
                <a href="{{ $collage->url }}" target="_blank" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs backdrop-blur border border-white/20 transition">
                    🔍 Lihat Ukuran Penuh
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Grid Foto Satuan -->
    @if($individualPhotos->isNotEmpty())
        <div class="space-y-3">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span>📷</span>
                <span>Foto Jepretan Satuan ({{ $individualPhotos->count() }} Foto)</span>
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($individualPhotos as $index => $photo)
                <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 aspect-square flex flex-col">
                    <img src="{{ $photo->url }}" alt="{{ $photo->file_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-3">
                        <span class="text-[11px] font-bold text-white/80">Slot #{{ $loop->iteration }}</span>
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ $photo->url }}" download="{{ $photo->file_name }}" class="px-2.5 py-1 rounded-lg bg-white text-slate-900 font-bold text-xs shadow hover:bg-slate-100 transition">
                                ⬇ Unduh
                            </a>
                            <a href="{{ $photo->url }}" target="_blank" class="px-2 py-1 rounded-lg bg-white/20 text-white text-xs backdrop-blur-sm hover:bg-white/30 transition">
                                🔍
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @elseif(!$collage)
        <!-- Empty State jika foto belum ada -->
        <div class="bg-white rounded-3xl border border-slate-200 p-12 sm:p-16 text-center max-w-xl mx-auto space-y-4 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 text-3xl flex items-center justify-center mx-auto">
                📸
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-slate-800">Sesi Foto Belum Dimulai</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda belum melakukan sesi foto untuk booking ini. Klik tombol di bawah untuk memilih frame kolase 6 foto dan mulai berfoto!
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('booth.session', $booking->booking_code) }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs shadow-lg shadow-rose-600/30 transition hover:scale-105">
                    <span>Mulai Sesi Photo Booth Sekarang</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

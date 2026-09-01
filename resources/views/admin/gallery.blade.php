@extends('layouts.admin', ['title' => 'Galeri Foto - Potret Diri'])

@section('content')
<div class="space-y-8">

    <!-- Top 3 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Stat 1: Total Penyimpanan -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-3">
            <span class="text-xs font-bold text-slate-500">Total Penyimpanan</span>
            <div class="text-3xl font-black text-slate-900">142.8 <span class="text-xl font-bold text-slate-500">GB</span></div>
            
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-[#F5BD23] rounded-full" style="width: 71%;"></div>
            </div>
            
            <span class="text-[11px] text-slate-400 flex items-center gap-1">
                <span>🕒</span> Sisa 57.2 GB dari 200 GB
            </span>
        </div>

        <!-- Stat 2: Total Foto Sesi -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-3">
            <span class="text-xs font-bold text-slate-500">Total Foto Sesi</span>
            <div class="text-3xl font-black text-slate-900">12,408 <span class="text-xl font-bold text-slate-500">Pics</span></div>
            
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-900 font-bold text-[10px]">+240 hari ini</span>
                <span class="text-[11px] text-slate-400">Sejak 08:00 WIB</span>
            </div>
        </div>

        <!-- Stat 3: Auto-Cleanup -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-3">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Auto–Cleanup</h4>
                    <p class="text-xs text-slate-400">Hapus file > 14 hari otomatis</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#F5BD23]"></div>
                </label>
            </div>
            
            <div class="pt-2 flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px]">AKTIF</span>
                <span class="text-[11px] text-slate-400">Membantu menjaga kuota penyimpanan.</span>
            </div>
        </div>

    </div>

    <!-- Filter Tabs (Semua Sesi, Minggu 4, Minggu 3, dll) -->
    <div class="flex flex-wrap items-center gap-2">
        <button type="button" class="px-5 py-2.5 rounded-full bg-[#F5BD23] text-slate-950 font-black text-xs shadow-sm">
            Semua Sesi
        </button>
        <button type="button" class="px-5 py-2.5 rounded-full bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs shadow-sm border border-slate-200 transition">
            Minggu 4 - Oktober
        </button>
        <button type="button" class="px-5 py-2.5 rounded-full bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs shadow-sm border border-slate-200 transition">
            Minggu 3 - Oktober
        </button>
        <button type="button" class="px-5 py-2.5 rounded-full bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs shadow-sm border border-slate-200 transition">
            Minggu 2 - Oktober
        </button>
        <button type="button" class="p-2.5 rounded-full bg-white hover:bg-slate-100 text-slate-700 text-xs shadow-sm border border-slate-200 transition" title="Pilih Tanggal">
            📅
        </button>
    </div>

    <!-- Photo Grid (Portrait Photography Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 sm:gap-6">
        @php
            $samplePortraits = [
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=80',
            ];
        @endphp

        @foreach($samplePortraits as $idx => $imgUrl)
        <div class="group relative rounded-2xl overflow-hidden bg-slate-900 aspect-[3/4] shadow-sm hover:shadow-xl transition-all duration-300 hover:scale-[1.02]">
            <img src="{{ $imgUrl }}" alt="Photo {{ $idx + 1 }}" class="w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 p-3">
                <a href="{{ $imgUrl }}" target="_blank" class="p-2 rounded-xl bg-white text-slate-900 font-bold text-xs shadow">
                    🔍 Buka
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Muat Lebih Banyak Button -->
    <div class="flex flex-col items-center justify-center pt-4 space-y-2">
        <button type="button" class="w-10 h-10 rounded-full bg-white hover:bg-slate-100 border border-slate-300 text-slate-600 flex items-center justify-center text-sm shadow transition">
            ⌄
        </button>
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">MUAT LEBIH BANYAK</span>
    </div>

</div>
@endsection

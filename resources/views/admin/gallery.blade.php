@extends('layouts.admin', ['title' => 'Galeri Foto - Potret Diri'])

@section('content')
<div class="space-y-8">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Galeri Foto</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Koleksi hasil jepretan foto studio dan kolase yang tersimpan di sistem</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('booth.index') }}" target="_blank" 
               class="px-4 py-2.5 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition flex items-center gap-1.5">
                <span>+</span>
                <span>Tambah / Mulai Sesi Foto</span>
            </a>
        </div>
    </div>

    <!-- Storage Usage Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">KAPASITAS PENYIMPANAN CLOUD</span>
                <h3 class="text-xl font-black text-slate-900">{{ $usedStorageMB ?? 0 }} MB / 200 GB</h3>
            </div>
            <div class="text-xs font-bold text-slate-500">
                Total File: <span class="text-slate-900 font-extrabold">{{ $totalPhotosCount ?? 0 }} Foto</span>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
            <div class="bg-[#F5BD23] h-full rounded-full transition-all duration-500" style="width: {{ max(1, min(100, (($usedStorageMB ?? 0) / 204800) * 100)) }}%"></div>
        </div>
    </div>

    <!-- Photos Grid or Empty State -->
    @if(isset($photos) && $photos->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($photos as $photo)
            <div class="bg-white rounded-3xl p-3 shadow-sm border border-slate-100 space-y-3 group hover:shadow-md transition">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 relative">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                         alt="{{ $photo->file_name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    @if($photo->is_collage)
                        <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-[#F5BD23] text-slate-950 text-[9px] font-black shadow">
                            KOLASE
                        </span>
                    @endif
                </div>
                <div class="px-1 text-xs">
                    <div class="font-bold text-slate-900 truncate">{{ $photo->file_name }}</div>
                    <div class="text-[10px] text-slate-400 font-mono">{{ $photo->booking ? $photo->booking->booking_code : 'Manual' }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $photos->links() }}
        </div>
    @else
        <!-- Empty State Galeri -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 space-y-4 max-w-xl mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto text-3xl">
                🖼
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-900">Galeri Foto Masih Kosong</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Seluruh foto satuan dan hasil cetak kolase dari sesi pemotretan photo booth akan otomatis tersimpan di sini secara real-time.</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('booth.index') }}" target="_blank" 
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition">
                    <span>+</span>
                    <span>Mulai Sesi Foto Pertama</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

@extends('layouts.admin', ['title' => 'Galeri Foto per Sesi - Potret Diri'])

@section('content')
<div class="space-y-8">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Galeri Foto</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Koleksi foto dikelompokkan secara rapi berdasarkan setiap sesi pemotretan</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('booth.index') }}" target="_blank" 
               class="px-5 py-2.5 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition flex items-center gap-1.5">
                <span>+</span>
                <span>Tambah / Mulai Sesi Foto</span>
            </a>
        </div>
    </div>

    <!-- Storage Usage & Filter Search Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            
            <!-- Left Info -->
            <div class="md:col-span-6 space-y-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">KAPASITAS PENYIMPANAN CLOUD</span>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-2xl font-black text-slate-900">{{ $usedStorageMB ?? 0 }} MB / 200 GB</h3>
                    <span class="text-xs font-bold text-slate-500">• {{ $totalSessionsWithPhotos ?? 0 }} Sesi ({{ $totalPhotosCount ?? 0 }} Foto)</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-[#F5BD23] h-full rounded-full transition-all duration-500" style="width: {{ max(1, min(100, (($usedStorageMB ?? 0) / 204800) * 100)) }}%"></div>
                </div>
            </div>

            <!-- Right Search Bar -->
            <div class="md:col-span-6">
                <form action="{{ route('admin.gallery') }}" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari kode booking atau nama pelanggan..." 
                               class="w-full pl-10 pr-4 py-3 bg-slate-100 border border-transparent rounded-2xl text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#F5BD23] focus:outline-none">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            🔍
                        </div>
                    </div>
                    <button type="submit" class="px-5 py-3 rounded-2xl bg-[#18181B] hover:bg-slate-800 text-white font-bold text-xs transition">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.gallery') }}" class="px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs flex items-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

        </div>
    </div>

    <!-- Grouped Photos by Session -->
    @if(isset($sessions) && $sessions->count() > 0)
        <div class="space-y-8">
            @foreach($sessions as $session)
            <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-5 transition hover:border-slate-200">
                
                <!-- Session Header Card -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-xl flex-shrink-0">
                            📸
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-black text-slate-900 text-base">
                                    {{ $session->customer_name ?? ($session->user ? $session->user->name : 'Pengunjung Kiosk') }}
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100/70 text-amber-900 font-mono font-black text-[11px]">
                                    {{ $session->booking_code }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-400 mt-1">
                                <span>📅 {{ $session->booking_date ? $session->booking_date->format('d M Y') : date('d M Y') }} ({{ substr($session->start_time ?? '00:00', 0, 5) }} WIB)</span>
                                <span>•</span>
                                <span class="text-slate-600 font-bold">Template: {{ ucwords(str_replace('-', ' ', $session->frame_design ?? 'Classic 4-Grid')) }}</span>
                                <span>•</span>
                                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md text-[10px]">{{ $session->photos->count() }} File Foto</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions per Session -->
                    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                        <a href="{{ route('gallery.show', $session->booking_code) }}" target="_blank" 
                           class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1.5">
                            <span>👁</span>
                            <span>Lihat Galeri Sesi</span>
                        </a>

                        <a href="{{ route('gallery.downloadZip', $session->booking_code) }}" 
                           class="px-3.5 py-2 rounded-xl bg-[#18181B] hover:bg-slate-800 text-white font-bold text-xs transition flex items-center gap-1.5">
                            <span>⬇</span>
                            <span>Unduh ZIP</span>
                        </a>
                    </div>
                </div>

                <!-- Photos in This Session Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 pt-1">
                    @foreach($session->photos as $photo)
                    <div class="group relative rounded-2xl overflow-hidden bg-slate-100 border {{ $photo->is_collage ? 'border-amber-400 ring-2 ring-amber-400/30' : 'border-slate-200' }} aspect-[3/4] shadow-sm hover:shadow-md transition">
                        
                        <img src="{{ asset('storage/' . $photo->file_path) }}" 
                             alt="{{ $photo->file_name }}" 
                             onclick="openLightbox('{{ asset('storage/' . $photo->file_path) }}', '{{ $photo->file_name }}')"
                             class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform duration-300">
                        
                        <!-- Collage / Single Badge -->
                        @if($photo->is_collage)
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#F5BD23] text-slate-950 text-[9px] font-black shadow-md flex items-center gap-1">
                                <span>⭐</span>
                                <span>KOLASE CETAK</span>
                            </div>
                        @else
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-sm text-white text-[9px] font-bold">
                                {{ preg_match('/Slot-(\d+)/', $photo->file_name, $m) ? 'Pose #' . $m[1] : 'Foto' }}
                            </div>
                        @endif

                        <!-- Hover Overlay Download Button -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 pointer-events-none group-hover:pointer-events-auto">
                            <a href="{{ asset('storage/' . $photo->file_path) }}" download="{{ $photo->file_name }}" 
                               class="p-2 rounded-xl bg-white text-slate-900 text-xs font-bold shadow hover:bg-slate-100 transition">
                                ⬇ Simpan
                            </a>
                        </div>

                    </div>
                    @endforeach
                </div>

            </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $sessions->links() }}
        </div>
    @else
        <!-- Empty State Galeri -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 space-y-4 max-w-xl mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto text-3xl">
                🖼
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-900">Belum Ada Sesi Foto Tersimpan</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Setiap sesi pemotretan dari layar kiosk akan otomatis membentuk grup album sesi tersendiri beserta foto kolase dan foto satuan.</p>
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

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 hidden flex items-center justify-center p-4" onclick="closeLightbox()">
    <div class="relative max-w-3xl w-full flex flex-col items-center" onclick="event.stopPropagation()">
        <button type="button" onclick="closeLightbox()" class="absolute -top-12 right-0 text-white hover:text-amber-400 text-2xl font-bold">✕ Tutup</button>
        <img id="lightbox-img" src="" alt="Preview" class="max-h-[80vh] w-auto rounded-2xl shadow-2xl object-contain">
        <p id="lightbox-caption" class="text-white text-xs font-mono mt-3"></p>
    </div>
</div>

<script>
    function openLightbox(url, caption) {
        document.getElementById('lightbox-img').src = url;
        document.getElementById('lightbox-caption').innerText = caption;
        document.getElementById('lightbox-modal').classList.remove('hidden');
    }
    function closeLightbox() {
        document.getElementById('lightbox-modal').classList.add('hidden');
    }
</script>
@endsection

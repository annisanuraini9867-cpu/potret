@extends('layouts.admin', ['title' => 'Galeri Foto per Sesi - Potret Diri'])

@section('content')
<div class="space-y-8">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Galeri Foto</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar sesi foto studio dalam kartu ringkas sejajar 5 kolom</p>
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

    <!-- 5 Cards Across (Sejajar 5 Sesi Foto) -->
    @if(isset($sessions) && $sessions->count() > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-400">RIWAYAT SESI FOTO TERBARU</h3>
                <span class="text-xs font-bold text-slate-500">Menampilkan 5 Sesi per Baris</span>
            </div>

            <!-- Grid 5 Kolom Sejajar (Responsive: 1 -> 2 -> 3 -> 5) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                @foreach($sessions as $session)
                @php
                    $coverPhoto = $session->photos->firstWhere('is_collage', true) ?? $session->photos->first();
                @endphp
                <div class="bg-white rounded-3xl p-3.5 shadow-sm border border-slate-100 hover:border-amber-300 hover:shadow-lg transition-all duration-300 flex flex-col justify-between space-y-3 group">
                    
                    <!-- Thumbnail Preview Card -->
                    <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 cursor-pointer"
                         onclick="openLightbox('{{ asset('storage/' . ($coverPhoto ? $coverPhoto->file_path : '')) }}', '{{ $session->customer_name ?? 'Sesi' }} ({{ $session->booking_code }})')">
                        
                        @if($coverPhoto)
                            <img src="{{ asset('storage/' . $coverPhoto->file_path) }}" 
                                 alt="{{ $session->booking_code }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-3xl text-slate-300">
                                📸
                            </div>
                        @endif

                        <!-- Top Left: Photo Count Badge -->
                        <div class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-lg bg-black/75 backdrop-blur-sm text-white text-[10px] font-black shadow flex items-center gap-1">
                            <span>📷</span>
                            <span>{{ $session->photos->count() }} Foto</span>
                        </div>

                        <!-- Top Right: Template Badge -->
                        <div class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-lg bg-[#F5BD23] text-slate-950 text-[9px] font-black shadow truncate max-w-[90px]">
                            {{ ucwords(str_replace(['classic-', 'cinematic-', 'polaroid-', 'passport-'], '', $session->frame_design ?? 'Grid')) }}
                        </div>

                        <!-- Hover Icon Overlay -->
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                            <span class="w-10 h-10 rounded-full bg-white/90 text-slate-900 flex items-center justify-center text-sm shadow-lg font-bold">
                                👁
                            </span>
                        </div>
                    </div>

                    <!-- Session Metadata -->
                    <div class="space-y-1 px-1">
                        <div class="font-black text-slate-900 text-xs truncate" title="{{ $session->customer_name ?? 'Pengunjung Kiosk' }}">
                            {{ $session->customer_name ?? ($session->user ? $session->user->name : 'Pengunjung Kiosk') }}
                        </div>
                        <div class="font-mono text-[10px] font-bold text-amber-600 truncate">
                            {{ $session->booking_code }}
                        </div>
                        <div class="text-[10px] text-slate-400 font-medium truncate">
                            {{ $session->booking_date ? $session->booking_date->format('d M Y') : date('d M Y') }} • {{ substr($session->start_time ?? '00:00', 0, 5) }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-1.5 pt-1 border-t border-slate-100 text-xs">
                        <a href="{{ route('gallery.show', $session->booking_code) }}" target="_blank" 
                           class="py-2 px-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] transition flex items-center justify-center gap-1 text-center">
                            <span>👁</span>
                            <span>Buka</span>
                        </a>

                        <a href="{{ route('gallery.downloadZip', $session->booking_code) }}" 
                           class="py-2 px-2 rounded-xl bg-[#18181B] hover:bg-slate-800 text-white font-bold text-[11px] transition flex items-center justify-center gap-1 text-center">
                            <span>⬇</span>
                            <span>ZIP</span>
                        </a>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="pt-4">
                {{ $sessions->links() }}
            </div>
        </div>
    @else
        <!-- Empty State Galeri -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 space-y-4 max-w-xl mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto text-3xl">
                🖼
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-900">Belum Ada Sesi Foto Tersimpan</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Setiap sesi pemotretan dari layar kiosk akan otomatis membentuk 1 kartu album sesi ringkas di galeri ini.</p>
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

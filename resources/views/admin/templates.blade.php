@extends('layouts.admin', ['title' => 'Kelola Template - Potret Diri'])

@section('content')
<div class="space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Kelola Template & Kolase</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelola desain layout foto booth dengan pilihan 8, 6, 4, 3, 2, dan 1 kolase untuk hasil cetak studio.
            </p>
        </div>

        <button type="button" onclick="openUploadTemplateModal()" 
                class="w-full sm:w-auto justify-center px-6 py-3.5 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition flex items-center gap-2">
            <span>⬆</span>
            <span>Unggah Template Baru</span>
        </button>
    </div>


    <!-- Search & Filter Controls -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white dark:bg-slate-900 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" id="admin-template-search" 
                   oninput="handleAdminSearch(this.value)" 
                   placeholder="Cari nama template, jumlah pose, dll..." 
                   class="w-full pl-10 pr-9 py-2.5 rounded-xl text-xs font-medium bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#F5BD23] focus:border-transparent transition">
            <button type="button" id="admin-search-clear" onclick="clearAdminSearch()" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hidden"
                    title="Hapus pencarian">
                ✕
            </button>
        </div>

        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-mono">
            <span>Menampilkan:</span>
            <span id="admin-visible-count" class="font-bold text-slate-900 dark:text-white">{{ count($templates) }}</span>
            <span>dari {{ count($templates) }} template</span>
        </div>
    </div>

    <!-- Category Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
        <button type="button" onclick="filterAdminCategory('all')" id="tab-admin-all" 
                class="admin-cat-tab px-4 py-2 rounded-xl text-xs font-black bg-slate-900 dark:bg-amber-400 text-white dark:text-slate-950 shadow-sm transition whitespace-nowrap">
            Semua ({{ count($templates) }})
        </button>
        <button type="button" onclick="filterAdminCategory('custom')" id="tab-admin-custom" 
                class="admin-cat-tab px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition whitespace-nowrap">
            ✨ Kustom ({{ count(array_filter($templates, fn($t) => !empty($t['is_custom']))) }})
        </button>
        <button type="button" onclick="filterAdminCategory('8_slots')" id="tab-admin-8_slots" 
                class="admin-cat-tab px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition whitespace-nowrap">
            🎉 8 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) == 8)) }})
        </button>
        <button type="button" onclick="filterAdminCategory('6_slots')" id="tab-admin-6_slots" 
                class="admin-cat-tab px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition whitespace-nowrap">
            🌸 6 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) == 6)) }})
        </button>
        <button type="button" onclick="filterAdminCategory('4_slots')" id="tab-admin-4_slots" 
                class="admin-cat-tab px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition whitespace-nowrap">
            ⭐ 4 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) == 4)) }})
        </button>
        <button type="button" onclick="filterAdminCategory('other_slots')" id="tab-admin-other_slots" 
                class="admin-cat-tab px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition whitespace-nowrap">
            🎬 3, 2 & 1 Kolase ({{ count(array_filter($templates, fn($t) => ($t['slots'] ?? 0) < 4)) }})
        </button>
    </div>

    <!-- Template Cards Grid -->
    <div id="admin-templates-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        <!-- 1. Dropzone Upload Card (Dashed Border) -->
        <div id="admin-upload-card" onclick="openUploadTemplateModal()" 
             class="border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-[#F5BD23] dark:hover:border-[#F5BD23] bg-white/60 dark:bg-slate-900/60 hover:bg-white dark:hover:bg-slate-900 rounded-3xl p-6 flex flex-col items-center justify-center text-center cursor-pointer transition min-h-[300px] space-y-3 group shadow-sm hover:shadow-md">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 group-hover:bg-amber-50 dark:group-hover:bg-amber-950/40 text-slate-400 dark:text-slate-500 group-hover:text-amber-600 dark:group-hover:text-amber-400 flex items-center justify-center text-2xl transition">
                🖼
            </div>
            <div>
                <h4 class="font-extrabold text-sm text-slate-800 dark:text-white">Unggah Template Baru</h4>
                <p class="text-[11px] text-slate-400 dark:text-slate-400 mt-0.5">PNG Transparan (Maks 10MB)</p>
            </div>
            <span class="text-[10px] font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/50 group-hover:text-amber-900 dark:group-hover:text-amber-300 transition">
                + Tambah Layout
            </span>
        </div>

        <!-- Dynamic Template Cards -->
        @foreach ($templates as $tmpl)
        @php
            $isDefault = $tmpl['is_default'] ?? false;
            $cat = $tmpl['category'] ?? '4_slots';
            $slots = $tmpl['slots'] ?? 4;
            $isCustom = !empty($tmpl['is_custom']);
        @endphp
        <div data-category="{{ $cat }}" 
             data-custom="{{ $isCustom ? '1' : '0' }}"
             data-name="{{ strtolower($tmpl['name']) }}"
             data-desc="{{ strtolower($tmpl['description'] ?? '') }}"
             data-slots="{{ $slots }}"
             class="admin-tmpl-card bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md overflow-hidden flex flex-col justify-between transition group">
            
            <!-- Card Preview Box -->
            <div class="relative p-5 aspect-[4/3] flex items-center justify-center overflow-hidden transition-colors"
                 style="background-color: {{ $tmpl['bg_color'] ?? '#F8FAFC' }};">
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10 flex-wrap">
                    @if ($isDefault)
                    <span class="px-2.5 py-0.5 rounded-full bg-[#F5BD23] text-slate-950 font-black text-[10px] shadow-sm">
                        ★ Default Kiosk
                    </span>
                    @endif
                    @if ($isCustom)
                    <span class="px-2 py-0.5 rounded-full bg-amber-500 text-slate-950 font-black text-[10px] shadow-sm">
                        ✨ Kustom
                    </span>
                    @endif
                    <span class="px-2 py-0.5 rounded-full bg-slate-900/80 backdrop-blur text-white font-mono font-bold text-[10px]">
                        {{ $slots }} Pose
                    </span>
                </div>

                <span class="absolute top-3 right-3 text-[10px] font-mono text-slate-500 bg-white/70 dark:bg-slate-900/70 backdrop-blur px-2 py-0.5 rounded-full">
                    {{ $tmpl['size'] ?? '1200 x 1800 px' }}
                </span>

                @if (!empty($tmpl['overlay_url']))
                    <!-- Custom Template Overlay Image -->
                    <div class="w-20 h-28 bg-white/95 rounded-md shadow-md p-1 border border-slate-300 flex items-center justify-center relative overflow-hidden group/img">
                        <img src="{{ $tmpl['overlay_url'] }}" alt="{{ $tmpl['name'] }}" class="w-full h-full object-contain">
                        <span class="absolute bottom-1 right-1 px-1 py-0.5 rounded bg-amber-500 text-slate-950 text-[7px] font-black uppercase shadow">
                            PNG
                        </span>
                    </div>
                @else
                    <!-- Schematic Mini Mockup Grid -->
                    <div class="w-20 h-28 bg-white/90 rounded-md shadow-md p-1 border border-slate-300 flex flex-col justify-between">
                        <span class="text-[5px] font-bold text-center block text-slate-600 truncate">{{ $tmpl['name'] }}</span>
                        
                        @if ($slots == 8)
                            <div class="grid grid-cols-2 gap-0.5 flex-1 my-0.5">
                                @for ($s=0; $s<8; $s++) <span class="bg-slate-200 rounded-[1px]"></span> @endfor
                            </div>
                        @elseif ($slots == 6)
                            <div class="grid grid-cols-2 gap-0.5 flex-1 my-0.5">
                                @for ($s=0; $s<6; $s++) <span class="bg-slate-200 rounded-[1px]"></span> @endfor
                            </div>
                        @elseif ($slots == 3)
                            <div class="grid grid-rows-3 gap-0.5 flex-1 my-0.5">
                                @for ($s=0; $s<3; $s++) <span class="bg-slate-200 rounded-[1px]"></span> @endfor
                            </div>
                        @elseif ($slots == 2)
                            <div class="grid grid-rows-2 gap-0.5 flex-1 my-0.5">
                                @for ($s=0; $s<2; $s++) <span class="bg-slate-200 rounded-[1px]"></span> @endfor
                            </div>
                        @elseif ($slots == 1)
                            <div class="bg-slate-200 rounded-[1px] flex-1 my-0.5"></div>
                        @else
                            <div class="grid grid-cols-2 gap-0.5 flex-1 my-0.5">
                                @for ($s=0; $s<4; $s++) <span class="bg-slate-200 rounded-[1px]"></span> @endfor
                            </div>
                        @endif

                        <span class="text-[4px] font-mono text-center block text-slate-400">POTRET DIRI</span>
                    </div>
                @endif
            </div>

            <!-- Card Bottom Body -->
            <div class="p-5 space-y-3 flex-1 flex flex-col justify-between bg-white dark:bg-slate-900">
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate">
                            {{ $tmpl['name'] }}
                        </h4>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                        {{ $tmpl['description'] ?? 'Template kolase photobooth premium.' }}
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
                    @if ($isDefault)
                    <button type="button" disabled 
                            class="flex-1 py-2 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/50 text-amber-900 dark:text-amber-300 font-bold text-xs cursor-default text-center">
                        ✓ Aktif Default
                    </button>
                    @else
                    <form action="{{ route('admin.templates.setDefault') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ $tmpl['id'] }}">
                        <button type="submit" 
                                class="w-full py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 text-slate-700 dark:text-slate-200 font-bold text-xs transition">
                            Set Default
                        </button>
                    </form>
                    @endif

                    <button type="button" onclick="openAdminPreviewModal('{{ $tmpl['id'] }}')" 
                            title="Lihat Desain Frame Lengkap"
                            class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-amber-50 dark:hover:bg-slate-800 hover:border-amber-300 dark:hover:border-amber-500 hover:text-amber-700 dark:hover:text-amber-400 text-slate-600 dark:text-slate-300 text-xs transition">
                        👁
                    </button>

                    @if ($isCustom)
                    <form action="{{ route('admin.templates.delete', $tmpl['id']) }}" method="POST" data-confirm="Apakah Anda yakin ingin menghapus template kustom {{ $tmpl['name'] }}?" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                title="Hapus Template Kustom"
                                class="p-2 rounded-xl border border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-900/30 text-red-500 text-xs transition">
                            🗑️
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
        @endforeach

        <!-- Empty Search State -->
        <div id="admin-no-templates" class="hidden col-span-full py-16 text-center space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-2xl mx-auto">
                🔍
            </div>
            <h4 class="font-extrabold text-base text-slate-800 dark:text-white">Tidak Ada Template yang Cocok</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                Tidak ditemukan template dengan kata kunci tersebut. Coba kata kunci lain atau pilih tab Semua.
            </p>
            <button type="button" onclick="clearAdminSearch()" class="px-4 py-2 rounded-xl bg-[#F5BD23] text-slate-950 font-bold text-xs hover:bg-amber-400 transition">
                Reset Pencarian
            </button>
        </div>

    </div>

    <!-- Modal: Pratinjau Desain Frame Admin -->
    <div id="admin-preview-modal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden animate-in fade-in duration-200">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-md w-full p-5 sm:p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-lg">
                        🖼️
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 dark:text-white" id="admin-modal-title">
                            Desain Frame Photobooth
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-mono" id="admin-modal-specs">
                            4 Pose • 1200 x 1800 px
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeAdminPreviewModal()" 
                        class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition">
                    ✕
                </button>
            </div>

            <!-- Modal Canvas Preview Container -->
            <div class="flex-1 overflow-y-auto flex items-center justify-center p-3 bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800">
                <canvas id="admin-modal-canvas" width="600" height="900" class="max-h-[50vh] sm:max-h-[55vh] w-auto rounded-xl shadow-2xl object-contain"></canvas>
            </div>

            <!-- Modal Footer Info & Actions -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-300 px-1">
                    <span class="font-mono text-[11px] text-slate-500 dark:text-slate-400">Resolusi Cetak: 1200 x 1800 px (300 DPI)</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs">✓ Siap Digunakan</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeAdminPreviewModal()" 
                            class="w-full py-3 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 text-xs font-bold text-slate-700 dark:text-slate-200 transition text-center">
                        Tutup Pratinjau
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Unggah Template Kolase Kustom -->
    <div id="admin-upload-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 dark:border-slate-800 relative animate-in fade-in zoom-in-95 space-y-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">Unggah Template Kolase Baru</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Desain overlay frame kustom resolusi tinggi untuk bilik kiosk</p>
                </div>
                <button type="button" onclick="closeUploadTemplateModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center font-bold text-base">&times;</button>
            </div>

            <form action="{{ route('admin.templates.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Nama Desain Template</label>
                    <input type="text" name="name" placeholder="Contoh: Romantic Floral 4–Cut" required class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl border border-transparent dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-[#F5BD23] focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Jumlah Slot Foto</label>
                        <select name="slots" required class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl border border-transparent dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-[#F5BD23] focus:outline-none">
                            <option value="8">8 Kolase (Party / Twin Strip)</option>
                            <option value="6">6 Kolase (2x3 Grid / Filmstrip)</option>
                            <option value="4" selected>4 Kolase (Classic / Korean)</option>
                            <option value="3">3 Kolase (Panoramic Strip)</option>
                            <option value="2">2 Kolase (Duo Portrait)</option>
                            <option value="1">1 Kolase (Polaroid / Poster)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Warna Background</label>
                        <input type="color" name="bg_color" value="#FFFFFF" class="w-full h-10 rounded-xl p-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-pointer">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">File Overlay Transparan (PNG)</label>
                    <input type="file" name="overlay" accept="image/png" required class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-800 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#F5BD23] file:text-slate-950 cursor-pointer">
                    <span class="text-[10px] text-slate-400 mt-1 block">Rekomendasi resolusi: 1200 x 1800 px, format PNG transparan, maks. 10MB.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Deskripsi Singkat (Opsional)</label>
                    <input type="text" name="description" placeholder="Deskripsi untuk tampilan katalog..." class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl border border-transparent dark:border-slate-700 text-xs text-slate-800 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-[#F5BD23] focus:outline-none">
                </div>

                <div class="pt-3 flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeUploadTemplateModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition text-center">
                        Batal
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition flex items-center justify-center gap-1.5">
                        <span>⬆</span>
                        <span>Simpan & Aktifkan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const ALL_TEMPLATES = @json($templates);

    function openUploadTemplateModal() {
        document.getElementById('admin-upload-modal').classList.remove('hidden');
    }

    function closeUploadTemplateModal() {
        document.getElementById('admin-upload-modal').classList.add('hidden');
    }

    function openAdminPreviewModal(templateId) {
        const tmpl = ALL_TEMPLATES.find(t => t.id === templateId);
        if (!tmpl) return;

        document.getElementById('admin-modal-title').innerText = tmpl.name;
        document.getElementById('admin-modal-specs').innerText = `${tmpl.slots} Pose • ${tmpl.size || '1200 x 1800 px'} • ${tmpl.category || 'Kolase'}`;

        const canvas = document.getElementById('admin-modal-canvas');
        if (canvas) {
            drawAdminTemplateMockup(canvas, tmpl);
        }

        document.getElementById('admin-preview-modal').classList.remove('hidden');
    }

    function closeAdminPreviewModal() {
        document.getElementById('admin-preview-modal').classList.add('hidden');
    }

    function drawAdminTemplateMockup(canvas, tmpl) {
        const ctx = canvas.getContext('2d');
        canvas.width = 600;
        canvas.height = 900;

        ctx.save();
        ctx.scale(0.5, 0.5); // Work in 1200x1800 space

        const bg = tmpl.bg_color || '#FFFFFF';
        const textCol = tmpl.text_color || '#1E293B';
        const borderCol = tmpl.accent || '#F5BD23';

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

        // 5. Draw Custom Overlay if present
        if (tmpl.overlay_url) {
            const overlayImg = new Image();
            overlayImg.crossOrigin = 'anonymous';
            overlayImg.onload = () => {
                ctx.drawImage(overlayImg, 0, 0, canvas.width, canvas.height);
            };
            overlayImg.src = tmpl.overlay_url;
        }
    }

    let currentAdminCategory = 'all';
    let currentAdminSearch = '';

    function handleAdminSearch(val) {
        currentAdminSearch = (val || '').trim().toLowerCase();
        const clearBtn = document.getElementById('admin-search-clear');
        if (clearBtn) {
            if (currentAdminSearch.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }
        applyAdminFilters();
    }

    function clearAdminSearch() {
        const searchInput = document.getElementById('admin-template-search');
        if (searchInput) searchInput.value = '';
        currentAdminSearch = '';
        const clearBtn = document.getElementById('admin-search-clear');
        if (clearBtn) clearBtn.classList.add('hidden');
        applyAdminFilters();
    }

    function filterAdminCategory(cat) {
        currentAdminCategory = cat;
        applyAdminFilters();
    }

    function applyAdminFilters() {
        const tabs = ['all', 'custom', '8_slots', '6_slots', '4_slots', 'other_slots'];
        tabs.forEach(t => {
            const el = document.getElementById('tab-admin-' + t);
            if (!el) return;
            if (t === currentAdminCategory) {
                el.className = 'admin-cat-tab px-4 py-2 rounded-xl text-xs font-black bg-slate-900 dark:bg-amber-400 text-white dark:text-slate-950 shadow-sm transition whitespace-nowrap';
            } else {
                el.className = 'admin-cat-tab px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition whitespace-nowrap';
            }
        });

        const cards = document.querySelectorAll('.admin-tmpl-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            const isCustom = card.getAttribute('data-custom') === '1';
            const name = card.getAttribute('data-name') || '';
            const desc = card.getAttribute('data-desc') || '';
            const slots = card.getAttribute('data-slots') || '';

            let matchesCat = false;
            if (currentAdminCategory === 'all') {
                matchesCat = true;
            } else if (currentAdminCategory === 'custom') {
                matchesCat = isCustom;
            } else {
                matchesCat = (cardCat === currentAdminCategory);
            }

            let matchesSearch = true;
            if (currentAdminSearch) {
                const searchSlotMatch = currentAdminSearch.replace(/[^0-9]/g, '');
                matchesSearch = name.includes(currentAdminSearch) ||
                                desc.includes(currentAdminSearch) ||
                                (isCustom && 'kustom custom'.includes(currentAdminSearch)) ||
                                (searchSlotMatch && slots === searchSlotMatch);
            }

            if (matchesCat && matchesSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const uploadCard = document.getElementById('admin-upload-card');
        if (uploadCard) {
            if ((currentAdminCategory === 'all' || currentAdminCategory === 'custom') && !currentAdminSearch) {
                uploadCard.style.display = 'flex';
            } else {
                uploadCard.style.display = 'none';
            }
        }

        const countEl = document.getElementById('admin-visible-count');
        if (countEl) countEl.innerText = visibleCount;

        const noTemplatesEl = document.getElementById('admin-no-templates');
        if (noTemplatesEl) {
            if (visibleCount === 0) {
                noTemplatesEl.classList.remove('hidden');
            } else {
                noTemplatesEl.classList.add('hidden');
            }
        }
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAdminPreviewModal();
            closeUploadTemplateModal();
        }
    });
</script>
@endsection

@extends('layouts.admin', ['title' => 'Kelola Template - Potret Diri'])

@section('content')
<div class="space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Kelola Template</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Unggah dan kelola overlay grid (PNG transparan) untuk hasil cetak foto pelanggan Anda.
            </p>
        </div>

        <button type="button" onclick="alert('Pilih file PNG transparan untuk mengunggah template frame baru.')" 
                class="px-6 py-3.5 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition flex items-center gap-2">
            <span>⬆</span>
            <span>Unggah Template Baru</span>
        </button>
    </div>

    <!-- Template Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        
        <!-- 1. Dropzone Upload Card (Dashed Border) -->
        <div onclick="alert('Silakan pilih file PNG transparan untuk mengunggah.')" 
             class="border-2 border-dashed border-slate-300 hover:border-[#F5BD23] bg-white/60 hover:bg-white rounded-3xl p-8 flex flex-col items-center justify-center text-center cursor-pointer transition min-h-[260px] space-y-2 group">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 group-hover:bg-amber-50 text-slate-400 group-hover:text-amber-600 flex items-center justify-center text-xl transition">
                🖼
            </div>
            <h4 class="font-extrabold text-sm text-slate-800">Letakkan File di Sini</h4>
            <p class="text-[11px] text-slate-400">PNG Transparan (Maks 10MB)</p>
        </div>

        <!-- 2. Classic 4-Grid Card (Default) -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="relative p-6 bg-slate-50 aspect-[4/3] flex items-center justify-center">
                <span class="absolute top-3 left-3 px-2 py-0.5 rounded-md bg-[#F5BD23] text-slate-950 font-black text-[10px]">
                    Default
                </span>
                <div class="w-24 h-24 border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center text-slate-400 text-xs font-bold rotate-45">
                    ◇
                </div>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Classic 4–Grid</h4>
                    <span class="text-[11px] text-slate-400 font-mono">1200 x 1800 px</span>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <button type="button" class="py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50">Edit</button>
                    <button type="button" class="py-2 rounded-xl bg-white border border-rose-200 text-rose-600 font-bold text-xs hover:bg-rose-50">Hapus</button>
                </div>
            </div>
        </div>

        <!-- 3. Cinematic Strip Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="p-6 bg-slate-50 aspect-[4/3] flex items-center justify-center">
                <div class="w-24 h-24 border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center text-slate-400 text-xs font-bold rotate-45">
                    ◇
                </div>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Cinematic Strip</h4>
                    <span class="text-[11px] text-slate-400 font-mono">600 x 1800 px</span>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button type="button" class="flex-1 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200">Set Default</button>
                    <button type="button" class="p-2 rounded-xl border border-slate-200 text-slate-600 text-xs">✏</button>
                    <button type="button" class="p-2 rounded-xl border border-rose-200 text-rose-600 text-xs">🗑</button>
                </div>
            </div>
        </div>

        <!-- 4. Polaroid Wide Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="p-6 bg-slate-50 aspect-[4/3] flex items-center justify-center">
                <div class="w-28 h-20 border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center text-slate-400 text-xs font-bold">
                    [ POTRET DIRI STUDIO ]
                </div>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Polaroid Wide</h4>
                    <span class="text-[11px] text-slate-400 font-mono">1600 x 1600 px</span>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button type="button" class="flex-1 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200">Set Default</button>
                    <button type="button" class="p-2 rounded-xl border border-slate-200 text-slate-600 text-xs">✏</button>
                    <button type="button" class="p-2 rounded-xl border border-rose-200 text-rose-600 text-xs">🗑</button>
                </div>
            </div>
        </div>

        <!-- 5. Passport Trio Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="p-6 bg-slate-50 aspect-[4/3] flex items-center justify-center">
                <div class="w-24 h-24 border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center text-slate-400 text-xs font-bold rotate-45">
                    ◇
                </div>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Passport Trio</h4>
                    <span class="text-[11px] text-slate-400 font-mono">1800 x 1200 px</span>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button type="button" class="flex-1 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200">Set Default</button>
                    <button type="button" class="p-2 rounded-xl border border-slate-200 text-slate-600 text-xs">✏</button>
                    <button type="button" class="p-2 rounded-xl border border-rose-200 text-rose-600 text-xs">🗑</button>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

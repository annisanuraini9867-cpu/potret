@extends('layouts.admin', ['title' => 'Status Sistem - Potret Diri'])

@section('content')
<div class="space-y-8">
    
    <!-- Top 3 Hardware Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Card 1: USB Hub Connectivity -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-[#F5BD23]"></div>
            
            <div class="flex justify-between items-start pl-2">
                <div>
                    <span class="text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase block">USB HUB CONNECTIVITY</span>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">Terhubung</h3>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg">
                    🔌
                </div>
            </div>

            <div class="pl-2 pt-3">
                <span class="text-xs font-bold text-slate-600 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    <span>4/4 Ports Aktif</span>
                </span>
            </div>
        </div>

        <!-- Card 2: CPU Temperature -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase block">CPU TEMPERATURE</span>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">42°C</h3>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                    🌡
                </div>
            </div>

            <div class="space-y-1.5 pt-3">
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-[#F5BD23] rounded-full" style="width: 42%;"></div>
                </div>
                <span class="text-[11px] font-mono text-slate-400 block">Optimal - Stabil</span>
            </div>
        </div>

        <!-- Card 3: Sinkronisasi Otomatis (Dashed Border) -->
        <div class="border-2 border-dashed border-slate-300 bg-white/70 hover:bg-white rounded-3xl p-6 shadow-sm flex flex-col items-center justify-center text-center space-y-1 transition cursor-pointer" onclick="alert('Sinkronisasi data cloud berhasil diperbarui!')">
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-sm">
                🔄
            </div>
            <h4 class="font-extrabold text-sm text-slate-900">Sinkronisasi Otomatis</h4>
            <p class="text-[11px] text-slate-400">Terakhir: 2 menit yang lalu</p>
        </div>

    </div>

    <!-- Main Grid (2 Columns): Konfigurasi Kamera (Left 7) & Pengaturan Printer + Antrean (Right 5) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI: Konfigurasi Kamera (7 Kolom) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-6">
            
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <span>📷</span>
                    <span>Konfigurasi Kamera</span>
                </h3>
                <span class="px-3 py-1 rounded-full bg-sky-100 text-sky-800 font-mono font-bold text-[10px] tracking-wider uppercase">
                    LIVE PREVIEW
                </span>
            </div>

            <!-- Camera Viewfinder Box -->
            <div class="relative rounded-2xl overflow-hidden bg-slate-950 aspect-video shadow-inner flex items-center justify-center group">
                <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&auto=format&fit=crop&q=80" alt="Camera Studio Feed" class="w-full h-full object-cover opacity-85">
                
                <!-- Center Button: Ambil Foto Uji Coba -->
                <button type="button" onclick="alert('Mengambil foto uji coba dari kamera studio...')" 
                        class="absolute px-5 py-2.5 rounded-full bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-xs shadow-xl flex items-center gap-2 transition active:scale-95">
                    <span>📸</span>
                    <span>Ambil Foto Uji Coba</span>
                </button>

                <!-- Bottom-Left Badge: FPS & Resolution -->
                <div class="absolute bottom-3 left-3 px-2.5 py-1 bg-black/75 backdrop-blur-sm rounded-lg text-[10px] font-mono text-slate-300">
                    FPS: 30 | 1920×1080
                </div>
            </div>

            <!-- Device Selector -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">PILIH PERANGKAT</label>
                <div class="relative">
                    <select class="w-full px-4 py-3.5 bg-slate-100/90 rounded-2xl border border-transparent font-bold text-slate-800 text-xs focus:bg-white focus:border-[#F5BD23] focus:outline-none appearance-none cursor-pointer">
                        <option>Sony Alpha A7 IV - USB 3.0</option>
                        <option>Canon EOS R6 Mark II - USB 3.0</option>
                        <option>Nikon Z6 II - USB 3.0</option>
                        <option>Logitech Brio 4K Ultra HD</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500 text-xs">
                        ⌄
                    </div>
                </div>
            </div>

            <!-- 2x2 Grid Camera Settings (ISO, SHUTTER, APERTURE, FOCUS MODE) -->
            <div class="grid grid-cols-2 gap-4 text-xs">
                
                <!-- ISO -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">ISO</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 bg-slate-100/90 rounded-2xl border border-transparent font-bold text-slate-800 focus:bg-white focus:border-[#F5BD23] focus:outline-none appearance-none cursor-pointer">
                            <option>100</option>
                            <option selected>200</option>
                            <option>400</option>
                            <option>800</option>
                            <option>1600</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">⌄</div>
                    </div>
                </div>

                <!-- SHUTTER -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">SHUTTER</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 bg-slate-100/90 rounded-2xl border border-transparent font-bold text-slate-800 focus:bg-white focus:border-[#F5BD23] focus:outline-none appearance-none cursor-pointer">
                            <option>1/60</option>
                            <option selected>1/125</option>
                            <option>1/250</option>
                            <option>1/500</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">⌄</div>
                    </div>
                </div>

                <!-- APERTURE -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">APERTURE</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 bg-slate-100/90 rounded-2xl border border-transparent font-bold text-slate-800 focus:bg-white focus:border-[#F5BD23] focus:outline-none appearance-none cursor-pointer">
                            <option>f/2.8</option>
                            <option selected>f/4.0</option>
                            <option>f/5.6</option>
                            <option>f/8.0</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">⌄</div>
                    </div>
                </div>

                <!-- FOCUS MODE -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">FOCUS MODE</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 bg-slate-100/90 rounded-2xl border border-transparent font-bold text-slate-800 focus:bg-white focus:border-[#F5BD23] focus:outline-none appearance-none cursor-pointer">
                            <option selected>Auto-Focus</option>
                            <option>Manual Focus</option>
                            <option>Eye-AF Continuous</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">⌄</div>
                    </div>
                </div>

            </div>

        </div>

        <!-- KOLOM KANAN: Pengaturan Printer & Antrean Cetak (5 Kolom) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Card 1: Pengaturan Printer -->
            <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-5">
                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                    <span>🖨</span>
                    <span>Pengaturan Printer</span>
                </h3>

                <!-- Alert: Kertas Hampir Habis -->
                <div class="p-4 bg-rose-50/70 border border-rose-200/80 rounded-2xl flex items-start gap-3 text-xs">
                    <div class="w-6 h-6 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center flex-shrink-0 font-bold">
                        ⚠
                    </div>
                    <div>
                        <h5 class="font-black text-rose-900">Kertas Hampir Habis</h5>
                        <p class="text-rose-700 text-[11px] mt-0.5">Tersisa kurang dari 15 lembar</p>
                    </div>
                </div>

                <!-- Pemotongan Otomatis Switch -->
                <div class="flex justify-between items-center pt-1">
                    <div>
                        <h5 class="font-extrabold text-xs text-slate-900">Pemotongan Otomatis</h5>
                        <p class="text-[11px] text-slate-400">Potong kertas segera setelah cetak</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                    </label>
                </div>

                <!-- Buttons: Cetak Halaman Uji & Gear -->
                <div class="pt-2 flex items-center gap-2.5">
                    <button type="button" onclick="alert('Mengirim perintah uji cetak ke printer photo booth...')" 
                            class="flex-1 py-3.5 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition">
                        Cetak Halaman Uji
                    </button>
                    <button type="button" onclick="alert('Membuka dialog konfigurasi driver printer DNP/Epson.')" 
                            class="p-3.5 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-sm shadow-sm transition">
                        ⚙
                    </button>
                </div>
            </div>

            <!-- Card 2: Antrean Cetak -->
            <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
                
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>🖹</span>
                        <span>Antrean Cetak</span>
                    </h3>
                    <span class="text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">
                        3 TUGAS AKTIF
                    </span>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="pb-2.5">ID</th>
                                <th class="pb-2.5">DOKUMEN</th>
                                <th class="pb-2.5 text-right">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($printJobs as $job)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 font-mono font-bold text-slate-900">{{ $job['id'] }}</td>
                                <td class="py-3 pr-2">
                                    <div class="font-bold text-slate-800 truncate max-w-[140px]">{{ $job['document'] }}</div>
                                    <div class="text-[10px] font-mono text-slate-400">{{ $job['size'] }}</div>
                                </td>
                                <td class="py-3 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black {{ $job['status_class'] }}">
                                        {{ $job['status_label'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection

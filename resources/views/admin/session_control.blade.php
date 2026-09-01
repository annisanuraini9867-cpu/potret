@extends('layouts.admin', ['title' => 'Kontrol Sesi - Potret Diri'])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- KOLOM KIRI: Durasi & Pengaturan Foto Ulang (8 Kolom) -->
    <div class="lg:col-span-8 space-y-6">
        
        <form action="{{ route('admin.session-control.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Card 1: Durasi Sesi -->
            <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-6">
                <div class="space-y-1">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span>⏱</span>
                        <span>Durasi Sesi</span>
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Tentukan berapa lama pelanggan dapat menggunakan studio dalam satu sesi.
                    </p>
                </div>

                <!-- 4 Selectable Duration Boxes -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach([5, 10, 15, 20] as $dur)
                    <label class="group cursor-pointer">
                        <input type="radio" name="duration" value="{{ $dur }}" {{ $selectedDuration == $dur ? 'checked' : '' }} class="sr-only peer">
                        <div class="p-6 rounded-2xl border-2 text-center transition-all peer-checked:bg-[#F5BD23] peer-checked:border-slate-900 peer-checked:shadow-md border-slate-200 hover:border-slate-300">
                            <div class="text-2xl font-black text-slate-900">{{ $dur }}</div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase mt-0.5 peer-checked:text-slate-900">Menit</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Card 2: Pengaturan Foto Ulang -->
            <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-6">
                
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span>🔄</span>
                        <span>Pengaturan Foto Ulang</span>
                    </h3>
                    
                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="retake_enabled" value="1" {{ $retakeEnabled ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#F5BD23]"></div>
                    </label>
                </div>

                <!-- Description Banner -->
                <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-2xl text-xs text-indigo-900 font-medium">
                    Izinkan pelanggan mengambil foto ulang jika hasil kurang memuaskan.
                </div>

                <!-- Radio Options: Batas Foto Ulang -->
                <div class="space-y-2 pt-1">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">BATAS FOTO ULANG</span>
                    
                    <div class="flex flex-wrap items-center gap-6 text-xs font-bold text-slate-700 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="retake_limit" value="unlimited" {{ $retakeLimit == 'unlimited' ? 'checked' : '' }} class="text-[#F5BD23] focus:ring-[#F5BD23]">
                            <span>Tidak Ada Batas</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="retake_limit" value="3" {{ $retakeLimit == '3' ? 'checked' : '' }} class="text-[#F5BD23] focus:ring-[#F5BD23]">
                            <span>Maksimal 3 Kali</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="retake_limit" value="5" {{ $retakeLimit == '5' ? 'checked' : '' }} class="text-[#F5BD23] focus:ring-[#F5BD23]">
                            <span>Maksimal 5 Kali</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex justify-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="py-3 px-8 rounded-2xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition">
                        Batalkan
                    </a>
                    <button type="submit" 
                            class="py-3 px-8 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 transition-all flex items-center gap-2">
                        <span>💾</span>
                        <span>Simpan Pengaturan</span>
                    </button>
                </div>
            </div>

        </form>

    </div>

    <!-- KOLOM KANAN: Studio Preview & Log Aktivitas Real-Time (4 Kolom) -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Studio Preview Box -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex justify-between items-center text-xs font-mono font-bold tracking-wider text-slate-400">
                <span>STUDIO PREVIEW</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            </div>

            <div class="relative rounded-2xl overflow-hidden bg-slate-900 aspect-video">
                <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&auto=format&fit=crop&q=80" alt="Studio Room" class="w-full h-full object-cover">
                <div class="absolute bottom-2 right-2 px-2 py-0.5 bg-black/80 backdrop-blur rounded text-[9px] font-mono text-white">
                    LIVE: STUDIO 01
                </div>
            </div>

            <div class="space-y-2 pt-1 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Kamera Status:</span>
                    <span class="font-bold text-emerald-600 uppercase">CONNECTED</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Sistem Studio:</span>
                    <span class="font-bold text-slate-800">ONLINE</span>
                </div>
            </div>
        </div>

        <!-- Log Aktivitas Real-Time Timeline -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs font-mono font-bold tracking-wider text-slate-500">
                    <span>⏱</span>
                    <span>LOG AKTIVITAS</span>
                </div>
                <a href="{{ route('booth.index') }}" target="_blank" class="text-[11px] font-bold text-[#F5BD23] hover:underline">
                    + Mulai Sesi
                </a>
            </div>

            <div class="space-y-4 text-xs">
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                    @foreach($recentActivities as $act)
                    <div class="relative pl-4 border-l-2 border-[#F5BD23] space-y-0.5">
                        <div class="font-bold text-slate-800">
                            {{ $act->customer_name ?? 'Pengunjung' }} — Sesi {{ $act->package ? $act->package->name : 'Photo Booth' }}
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono">
                            {{ $act->booking_code }} • {{ $act->created_at ? $act->created_at->diffForHumans() : 'Baru saja' }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Empty State Log Aktivitas -->
                    <div class="py-6 text-center space-y-2">
                        <p class="text-xs font-bold text-slate-600">Belum Ada Aktivitas Sesi</p>
                        <p class="text-[11px] text-slate-400">Riwayat sesi foto akan tercatat otomatis di sini.</p>
                        <div class="pt-1">
                            <a href="{{ route('booth.index') }}" target="_blank" 
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold hover:bg-amber-100 transition">
                                <span>+</span>
                                <span>Mulai Sesi Sekarang</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <a href="{{ route('admin.bookings.index') }}" class="block text-center w-full py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-[11px] transition">
                Lihat Semua Riwayat Booking
            </a>
        </div>

    </div>

</div>
@endsection

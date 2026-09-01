@extends('layouts.admin', ['title' => 'Ringkasan Studio - Potret Diri'])

@section('content')
<div class="space-y-8">

    <!-- Top Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Ringkasan Studio</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau aktivitas harian dan status perangkat Anda</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <span>📥</span>
                <span>Laporan</span>
            </button>

            <button type="button" onclick="location.reload()" class="px-4 py-2.5 rounded-full bg-[#18181B] hover:bg-slate-800 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                <span>🔄</span>
                <span>Update Data</span>
            </button>
        </div>
    </div>

    <!-- 3 Top Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Metric 1: Pendapatan Hari Ini -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    💵
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1">
                    <span>📈</span> +12%
                </span>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">PENDAPATAN HARI INI</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900">Rp {{ number_format($todayEarnings, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Metric 2: Total Sesi -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    📅
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1">
                    <span>📈</span> +5%
                </span>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">TOTAL SESI</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalSessions }} Sesi</span>
            </div>
        </div>

        <!-- Metric 3: Total Cetakan -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                    🖨
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-1">
                    <span>📉</span> -2%
                </span>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">TOTAL CETAKAN</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalPrints }} Foto</span>
            </div>
        </div>

    </div>

    <!-- Bottom Section: Status Kios (Left 4 cols) & Sesi Terbaru (Right 8 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI (4 Kolom) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Status Kios Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-extrabold text-sm text-slate-900">Status Kios</h3>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Online</span>
                    </span>
                </div>

                <!-- Open / Close Toggle Buttons (Interactive Sesuai Logika Buka / Tutup) -->
                <div class="space-y-2">
                    <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 rounded-2xl">
                        <form action="{{ route('admin.kiosk.status') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="status" value="buka">
                            <button type="submit" 
                                    class="w-full py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center justify-center gap-1.5 {{ ($kioskStatus ?? 'buka') === 'buka' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/30' : 'text-slate-500 hover:text-slate-900' }}">
                                <span>🔓</span> Buka
                            </button>
                        </form>

                        <form action="{{ route('admin.kiosk.status') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="status" value="tutup">
                            <button type="submit" 
                                    class="w-full py-2.5 rounded-xl font-extrabold text-xs transition-all flex items-center justify-center gap-1.5 {{ ($kioskStatus ?? 'buka') === 'tutup' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/30' : 'text-slate-500 hover:text-slate-900' }}">
                                <span>🔒</span> Tutup
                            </button>
                        </form>
                    </div>

                    <!-- Mode Indicator Note -->
                    <div class="p-2.5 rounded-xl text-[11px] font-semibold {{ ($kioskStatus ?? 'buka') === 'buka' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60' : 'bg-rose-50 text-rose-800 border border-rose-200/60' }}">
                        @if(($kioskStatus ?? 'buka') === 'buka')
                            <span>🔓 <strong>Mode Buka Aktif:</strong> Sesi foto langsung mulai tanpa perlu pembayaran.</span>
                        @else
                            <span>🔒 <strong>Mode Tutup Aktif:</strong> Sesi terkunci, wajib bayar QRIS sebelum foto.</span>
                        @endif
                    </div>
                </div>

                <!-- Hardware Device Status Rows -->
                <div class="space-y-3 pt-2 text-xs">
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="font-bold text-slate-700">Kamera</span>
                        <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 font-black text-[11px] flex items-center gap-1">
                            <span>✓</span> Aktif
                        </span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="font-bold text-slate-700">Printer</span>
                        <span class="px-2 py-0.5 rounded-lg bg-amber-50 text-amber-700 font-black text-[11px] flex items-center gap-1">
                            <span>⚠</span> Stok Rendah
                        </span>
                    </div>
                </div>
            </div>

            <!-- Studio Live Camera Preview Card -->
            <div class="relative rounded-3xl overflow-hidden bg-slate-950 aspect-[4/3] shadow-lg border border-slate-200 group">
                <img src="https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&auto=format&fit=crop&q=80" alt="Studio Live" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 rounded-full bg-rose-600 text-white font-black text-[10px] tracking-wider uppercase flex items-center gap-1 shadow">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                        <span>LIVE</span>
                    </span>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: Sesi Terbaru (8 Kolom) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Sesi Terbaru</h3>
                    <p class="text-xs text-slate-400">Data transaksi studio terakhir</p>
                </div>

                <div class="relative w-full sm:w-64">
                    <input type="text" placeholder="Cari sesi..." class="w-full pl-9 pr-4 py-2 bg-slate-100 border-none rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#F5BD23] focus:outline-none">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <th class="pb-3">WAKTU</th>
                            <th class="pb-3">CETAKAN</th>
                            <th class="pb-3">HARGA</th>
                            <th class="pb-3">STATUS</th>
                            <th class="pb-3 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentBookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 pr-2">
                                <div class="font-bold text-slate-900">{{ substr($b->start_time, 0, 5) }} WIB</div>
                                <div class="text-[10px] text-slate-400">{{ $b->booking_date->format('d M Y') }}</div>
                            </td>
                            <td class="py-3.5 font-bold text-slate-700">
                                {{ $b->photos->count() > 0 ? $b->photos->count() : 4 }}
                            </td>
                            <td class="py-3.5 font-extrabold text-slate-900">
                                Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5">
                                @if($b->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700">● Berhasil</span>
                                @elseif($b->status === 'confirmed' || $b->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-700">● Diproses</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700">● Gagal</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="text-slate-400 hover:text-slate-900 font-black text-sm px-2 py-1 rounded-lg">
                                    ···
                                </a>
                            </td>
                        </tr>
                        @empty
                        <!-- Mock Rows if empty -->
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5">
                                <div class="font-bold text-slate-900">14:22 WIB</div>
                                <div class="text-[10px] text-slate-400">24 Okt 2026</div>
                            </td>
                            <td class="py-3.5 font-bold text-slate-700">4</td>
                            <td class="py-3.5 font-extrabold text-slate-900">Rp 80.000</td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700">● Berhasil</span>
                            </td>
                            <td class="py-3.5 text-right"><span class="text-slate-400 font-bold">···</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5">
                                <div class="font-bold text-slate-900">13:50 WIB</div>
                                <div class="text-[10px] text-slate-400">24 Okt 2026</div>
                            </td>
                            <td class="py-3.5 font-bold text-slate-700">1</td>
                            <td class="py-3.5 font-extrabold text-slate-900">Rp 20.000</td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-700">● Diproses</span>
                            </td>
                            <td class="py-3.5 text-right"><span class="text-slate-400 font-bold">···</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5">
                                <div class="font-bold text-slate-900">13:12 WIB</div>
                                <div class="text-[10px] text-slate-400">24 Okt 2026</div>
                            </td>
                            <td class="py-3.5 font-bold text-slate-700">3</td>
                            <td class="py-3.5 font-extrabold text-slate-900">Rp 60.000</td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700">● Gagal</span>
                            </td>
                            <td class="py-3.5 text-right"><span class="text-slate-400 font-bold">···</span></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>
@endsection

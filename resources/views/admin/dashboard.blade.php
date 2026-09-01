@extends('layouts.admin', ['title' => 'Ringkasan Studio - Potret Diri'])

@section('content')
<div class="space-y-8">

    <!-- Top Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Ringkasan Studio</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau aktivitas harian dan status perangkat Anda secara real-time</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('booth.index') }}" target="_blank" class="px-4 py-2.5 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition flex items-center gap-1.5">
                <span>📸</span>
                <span>+ Mulai Sesi Kiosk</span>
            </a>

            <button type="button" onclick="refreshRealtimeStats()" class="px-4 py-2.5 rounded-full bg-[#18181B] hover:bg-slate-800 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                <span id="refresh-spinner" class="inline-block">🔄</span>
                <span>Update Data</span>
            </button>
        </div>
    </div>

    <!-- 3 Top Metric Cards (Real-Time Live) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Metric 1: Pendapatan Hari Ini -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    💵
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1">
                    <span>⚡</span> Live DB
                </span>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">PENDAPATAN HARI INI</span>
                <span id="metric-today-earnings" class="text-2xl sm:text-3xl font-black text-slate-900">
                    Rp {{ number_format($todayEarnings, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Metric 2: Total Sesi -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    📅
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center gap-1">
                    <span>⚡</span> Live DB
                </span>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">TOTAL SESI</span>
                <span id="metric-total-sessions" class="text-2xl sm:text-3xl font-black text-slate-900">
                    {{ $totalSessions }} Sesi
                </span>
            </div>
        </div>

        <!-- Metric 3: Total Cetakan -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                    🖨
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-1">
                    <span>⚡</span> Live DB
                </span>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">TOTAL CETAKAN</span>
                <span id="metric-total-prints" class="text-2xl sm:text-3xl font-black text-slate-900">
                    {{ $totalPrints }} Foto
                </span>
            </div>
        </div>

    </div>

    <!-- Bottom Section: Status Kios (Left 4 cols) & Sesi Terbaru (Right 8 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI (4 Kolom) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Status Kios Card (Buka vs Tutup) -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-5">
                <div class="flex justify-between items-center">
                    <h3 class="font-extrabold text-sm text-slate-900">Status Kios</h3>
                    <span class="flex items-center gap-1.5 text-xs font-bold {{ ($kioskStatus ?? 'buka') === 'buka' ? 'text-emerald-600' : 'text-amber-600' }}">
                        <span class="w-2 h-2 rounded-full {{ ($kioskStatus ?? 'buka') === 'buka' ? 'bg-emerald-500' : 'bg-amber-500' }} animate-pulse"></span>
                        <span>{{ ($kioskStatus ?? 'buka') === 'buka' ? 'Buka (Gratis)' : 'Tutup (Terkunci)' }}</span>
                    </span>
                </div>

                <!-- Open / Close Toggle Buttons -->
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
                        <span class="font-bold text-slate-700">Kamera Live</span>
                        <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 font-black text-[11px] flex items-center gap-1">
                            <span>✓</span> Siap Digunakan
                        </span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="font-bold text-slate-700">Printer 4R</span>
                        <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 font-black text-[11px] flex items-center gap-1">
                            <span>✓</span> Online
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
                        <span>LIVE VIEW</span>
                    </span>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: Sesi Terbaru Real-Time (8 Kolom) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Sesi Terbaru</h3>
                    <p class="text-xs text-slate-400">Data sesi dan transaksi realtime dari database</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('booth.index') }}" target="_blank" 
                       class="px-3.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-black text-xs border border-amber-200 transition flex items-center gap-1">
                        <span>+</span>
                        <span>Tambah Sesi</span>
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <th class="pb-3">WAKTU & KODE</th>
                            <th class="pb-3">PELANGGAN</th>
                            <th class="pb-3">HARGA</th>
                            <th class="pb-3">STATUS</th>
                            <th class="pb-3 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="recent-bookings-tbody" class="divide-y divide-slate-100">
                        @forelse($recentBookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 pr-2">
                                <div class="font-bold text-slate-900">{{ substr($b->start_time ?? '00:00', 0, 5) }} WIB</div>
                                <div class="font-mono text-[10px] text-amber-600 font-bold">{{ $b->booking_code }}</div>
                            </td>
                            <td class="py-3.5 font-bold text-slate-700">
                                <div>{{ $b->customer_name ?? ($b->user ? $b->user->name : 'Pengunjung Kiosk') }}</div>
                                <div class="text-[10px] text-slate-400 font-normal">{{ $b->package ? $b->package->name : 'Kiosk Session' }}</div>
                            </td>
                            <td class="py-3.5 font-extrabold text-slate-900">
                                Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5">
                                @if($b->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700">● Selesai</span>
                                @elseif($b->status === 'confirmed' || $b->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-700">● Diproses</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700">● Dibatalkan</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="text-slate-400 hover:text-slate-900 font-black text-sm px-2 py-1 rounded-lg">
                                    ···
                                </a>
                            </td>
                        </tr>
                        @empty
                        <!-- Empty State Realtime -->
                        <tr>
                            <td colspan="5" class="py-12 text-center space-y-3">
                                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto text-2xl">
                                    📅
                                </div>
                                <div class="space-y-1">
                                    <p class="font-black text-slate-800 text-sm">Belum Ada Sesi Foto Terbaru</p>
                                    <p class="text-xs text-slate-400 max-w-sm mx-auto">Data transaksi dan sesi photo booth akan otomatis muncul secara real-time begitu pengunjung memulai sesi foto.</p>
                                </div>
                                <div class="pt-2">
                                    <a href="{{ route('booth.index') }}" target="_blank" 
                                       class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-95 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition">
                                        <span>+</span>
                                        <span>Tambah / Mulai Sesi Baru</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>

<!-- Realtime Polling Script -->
<script>
    const STATS_API_URL = "{{ route('admin.api.realtime-stats') }}";

    async function refreshRealtimeStats() {
        const spinner = document.getElementById('refresh-spinner');
        if (spinner) spinner.classList.add('animate-spin');

        try {
            const res = await fetch(STATS_API_URL, {
                headers: { 'Accept': 'application/json' }
            });
            if (res.ok) {
                const data = await res.json();
                document.getElementById('metric-today-earnings').innerText = data.today_earnings_formatted;
                document.getElementById('metric-total-sessions').innerText = `${data.total_sessions} Sesi`;
                document.getElementById('metric-total-prints').innerText = `${data.total_prints} Foto`;
            }
        } catch (e) {
            console.log('Auto-refresh paused');
        } finally {
            if (spinner) spinner.classList.remove('animate-spin');
        }
    }

    // Auto poll stats every 10 seconds
    setInterval(refreshRealtimeStats, 10000);
</script>
@endsection

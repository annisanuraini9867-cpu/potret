@extends('layouts.app', ['title' => 'Riwayat Pesanan Saya'])

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Riwayat Pesanan Saya</h1>
            <p class="text-slate-500 text-xs mt-1">Daftar seluruh sesi foto yang pernah Anda pesan di Potret Diri Studio.</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow hover:bg-indigo-700 transition">
            + Pesan Sesi Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Kode Booking</th>
                        <th class="px-6 py-4">Paket</th>
                        <th class="px-6 py-4">Jadwal Sesi</th>
                        <th class="px-6 py-4">Total Biaya</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                            {{ $b->booking_code }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            {{ $b->package->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $b->booking_date->format('d M Y') }}</div>
                            <div class="text-[11px] text-slate-400">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            Rp {{ number_format($b->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                {{ $b->status == 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $b->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $b->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $b->status == 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                                {{ $b->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('gallery.show', $b->booking_code) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold transition">
                                <span>Galeri Foto</span>
                                @if($b->photos->count() > 0)
                                    <span class="text-[10px] bg-indigo-200 text-indigo-900 px-1.5 py-0.2 rounded-full">{{ $b->photos->count() }}</span>
                                @endif
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            Belum ada riwayat pemesanan. <a href="{{ route('bookings.create') }}" class="text-indigo-600 font-bold hover:underline">Pesan sesi pertama Anda!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

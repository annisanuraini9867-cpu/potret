@extends('layouts.admin', ['title' => 'Kelola Reservasi - Admin', 'headerTitle' => 'Manajemen Reservasi & Foto Sesi'])

@section('content')
<div class="space-y-6">

    <!-- Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Reservasi</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wider">Menunggu (Pending)</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-blue-500 uppercase tracking-wider">Dikonfirmasi</span>
            <div class="text-2xl font-black text-blue-600 mt-1">{{ $stats['confirmed'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Selesai (Done)</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['completed'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider">Sesi Hari Ini</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $stats['today'] }}</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode, Nama, Email, No. WA..." class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="w-36">
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="w-36">
                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'date']))
                <a href="{{ route('admin.bookings.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-semibold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table of Bookings -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Kode Booking</th>
                        <th class="px-5 py-3.5">Pelanggan</th>
                        <th class="px-5 py-3.5">Paket & Biaya</th>
                        <th class="px-5 py-3.5">Jadwal Sesi</th>
                        <th class="px-5 py-3.5">Foto</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-4 font-mono font-bold text-indigo-600">
                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="hover:underline">
                                {{ $b->booking_code }}
                            </a>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-900">{{ $b->customer_name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $b->customer_phone }} | {{ $b->customer_email }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-800">{{ $b->package->name }}</div>
                            <div class="text-[11px] text-indigo-600 font-bold">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-bold {{ $b->booking_date->isToday() ? 'text-indigo-600' : 'text-slate-800' }}">
                                {{ $b->booking_date->format('d M Y') }}
                            </div>
                            <div class="text-[11px] text-slate-400">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB</div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $b->photos->count() > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-400' }}">
                                📷 {{ $b->photos->count() }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                {{ $b->status == 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $b->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $b->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $b->status == 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                                {{ $b->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right space-x-2">
                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition">
                                Detail & Upload Foto
                            </a>
                            <form action="{{ route('admin.bookings.destroy', $b->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus reservasi ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                    🗑
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                            Tidak ada data reservasi yang sesuai dengan filter.
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

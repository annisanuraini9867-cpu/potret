@extends('layouts.admin', ['title' => 'Kelola Paket Studio', 'headerTitle' => 'Katalog Paket Foto Studio'])

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Daftar Paket Sesi Foto</h2>
            <p class="text-slate-500 text-xs">Atur paket, harga, durasi sesi, dan kapasitas orang per sesi.</p>
        </div>
        <a href="{{ route('admin.packages.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow transition">
            + Tambah Paket Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Nama Paket</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Durasi</th>
                    <th class="px-6 py-4">Maks Orang</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Total Booking</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($packages as $pkg)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-slate-900">
                        {{ $pkg->name }}
                        <div class="text-[11px] font-normal text-slate-400">{{ $pkg->description }}</div>
                    </td>
                    <td class="px-6 py-4 font-bold text-indigo-600 text-sm">
                        Rp {{ number_format($pkg->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        {{ $pkg->duration_minutes }} Menit
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        {{ $pkg->max_persons }} Orang
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $pkg->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $pkg->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-700">
                        {{ $pkg->bookings_count }}x dipesan
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                🗑
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.admin', ['title' => 'Tambah Paket Baru', 'headerTitle' => 'Tambah Paket Sesi Foto'])

@section('content')
<div class="max-w-2xl bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
    <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Formulir Paket Baru</h2>

    <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Paket</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Family & Kids Studio" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', 100000) }}" min="0" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Durasi (Menit)</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" min="5" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Maks. Orang</label>
                <input type="number" name="max_persons" value="{{ old('max_persons', 2) }}" min="1" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi & Fasilitas</label>
            <textarea name="description" rows="3" placeholder="Jelaskan fasilitas paket..." class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-bold text-slate-700">Aktifkan paket ini di formulir pemesanan</span>
            </label>
        </div>

        <div class="flex gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow transition">
                Simpan Paket
            </button>
            <a href="{{ route('admin.packages.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

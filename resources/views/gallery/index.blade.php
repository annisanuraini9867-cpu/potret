@extends('layouts.app', ['title' => 'Ambil & Unduh Foto Sesi Anda'])

@section('content')
<div class="max-w-xl mx-auto py-10">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xl text-center space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-3xl">
            🖼
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-black text-slate-900">Temukan Galeri Foto Anda</h1>
            <p class="text-slate-500 text-xs sm:text-sm">Masukkan kode booking unik yang Anda dapatkan saat melakukan reservasi untuk mengakses seluruh softcopy hasil jepretan studio.</p>
        </div>

        <form action="{{ route('gallery.search') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1 text-left">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kode Booking</label>
                <input type="text" name="booking_code" value="{{ old('booking_code') }}" placeholder="Contoh: PTD-20260901-XXXXX" required class="w-full px-4 py-3.5 border border-slate-300 rounded-xl text-sm font-mono uppercase tracking-wider focus:ring-2 focus:ring-indigo-500 focus:outline-none text-center font-bold">
            </div>

            <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-lg shadow-indigo-600/30 transition">
                Buka Galeri Foto →
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-xs text-slate-400">
            Lupa kode booking Anda? Hubungi admin studio dengan menyertakan nama dan nomor WhatsApp yang digunakan saat reservasi.
        </div>
    </div>
</div>
@endsection

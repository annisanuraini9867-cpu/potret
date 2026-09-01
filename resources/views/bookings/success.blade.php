@extends('layouts.app', ['title' => 'Pemesanan Berhasil - ' . $booking->booking_code])

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden text-center p-8 sm:p-10 space-y-6">
        
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-3xl font-black">
            ✓
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Reservasi Berhasil Dibuat!</h1>
            <p class="text-sm text-slate-500">Terima kasih atas pemesanan Anda di Potret Diri Studio. Mohon simpan kode booking berikut.</p>
        </div>

        <!-- Kode Booking Box -->
        <div class="bg-slate-900 text-white p-6 rounded-2xl space-y-2">
            <span class="text-xs uppercase tracking-widest text-slate-400 font-semibold">Kode Booking Anda</span>
            <div class="text-3xl font-black tracking-widest text-indigo-400 font-mono select-all">
                {{ $booking->booking_code }}
            </div>
            <p class="text-[11px] text-slate-400">Tunjukkan kode ini saat tiba di studio atau gunakan untuk mengunduh foto Anda.</p>
        </div>

        <!-- Summary Details -->
        <div class="bg-slate-50 rounded-2xl p-6 text-left text-xs sm:text-sm space-y-3 border border-slate-100">
            <div class="flex justify-between border-b border-slate-200/60 pb-2">
                <span class="text-slate-500">Nama Pelanggan</span>
                <span class="font-bold text-slate-800">{{ $booking->customer_name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200/60 pb-2">
                <span class="text-slate-500">Paket Sesi</span>
                <span class="font-bold text-slate-800">{{ $booking->package->name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200/60 pb-2">
                <span class="text-slate-500">Tanggal Sesi</span>
                <span class="font-bold text-slate-800">{{ $booking->booking_date->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200/60 pb-2">
                <span class="text-slate-500">Waktu Sesi</span>
                <span class="font-bold text-slate-800">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</span>
            </div>
            <div class="flex justify-between pt-1">
                <span class="text-slate-500 font-semibold">Total Biaya</span>
                <span class="font-black text-indigo-600 text-base">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Action Links -->
        <div class="space-y-3 pt-2">
            <a href="{{ route('booth.session', $booking->booking_code) }}" class="block w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-sm shadow-xl shadow-amber-500/20 transition hover:scale-[1.01] flex items-center justify-center gap-2">
                <span>📸 Mulai Sesi Foto Sekarang (Pilih Frame & Buka Booth)</span>
                <span>→</span>
            </a>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('gallery.show', $booking->booking_code) }}" class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition">
                    Lihat Galeri Foto
                </a>
                <a href="{{ route('home') }}" class="py-3 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

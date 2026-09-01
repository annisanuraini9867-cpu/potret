@extends('layouts.app', ['title' => 'Formulir Pemesanan Sesi Foto'])

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-3xl font-extrabold text-slate-900">Reservasi Sesi Self-Photo Booth</h1>
        <p class="text-slate-500 text-sm mt-1">Pilih paket foto terbaik, tentukan jadwal tanpa bentrok, dan nikmati waktu berfotomu!</p>
    </div>

    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Langkah 1: Pilih Paket -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                <span class="w-7 h-7 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center">1</span>
                <h2 class="font-bold text-base text-slate-900">Pilih Paket Sesi Foto</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($packages as $pkg)
                <label class="relative flex flex-col p-5 border-2 rounded-2xl cursor-pointer hover:border-indigo-500 transition-all bg-white">
                    <input type="radio" name="package_id" value="{{ $pkg->id }}" class="peer sr-only" {{ old('package_id', $selectedPackageId) == $pkg->id ? 'checked' : '' }} required>
                    <div class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 absolute inset-0 rounded-2xl border-2 pointer-events-none border-transparent transition"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <span class="font-bold text-slate-900 text-base">{{ $pkg->name }}</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $pkg->duration_minutes }} mnt</span>
                    </div>
                    <span class="text-indigo-600 font-extrabold text-xl mt-2 relative z-10">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-500 mt-2 relative z-10 leading-relaxed">{{ $pkg->description }}</span>
                    <div class="text-[11px] font-medium text-slate-400 mt-3 pt-2 border-t border-slate-100 relative z-10 flex items-center gap-3">
                        <span>👥 Maks: {{ $pkg->max_persons }} Orang</span>
                        <span>📸 Softcopy All</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Langkah 2: Tanggal & Waktu -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                <span class="w-7 h-7 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                <h2 class="font-bold text-base text-slate-900">Pilih Jadwal Sesi</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Sesi</label>
                    <input type="date" name="booking_date" min="{{ date('Y-m-d') }}" value="{{ old('booking_date', date('Y-m-d')) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none font-medium">
                    <span class="text-[11px] text-slate-400 mt-1 block">Jadwal dibuka dari hari ini ke depan.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Mulai (WIB)</label>
                    <input type="time" name="start_time" value="{{ old('start_time', '10:00') }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none font-medium">
                    <span class="text-[11px] text-slate-400 mt-1 block">Studio beroperasi pukul 09:00 - 21:00 WIB.</span>
                </div>
            </div>
        </div>

        <!-- Langkah 3: Informasi Kontak Pelanggan -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                <span class="w-7 h-7 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center">3</span>
                <h2 class="font-bold text-base text-slate-900">Data Pelanggan & Catatan</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" placeholder="Nama Anda" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" placeholder="email@domain.com" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan Khusus (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Bawa properti toga wisuda, minta background abu-abu..." class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] text-slate-950 font-black text-base shadow-xl shadow-amber-500/20 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                <span>Konfirmasi Reservasi & Dapatkan Kode Booking</span>
                <span>→</span>
            </button>
            <p class="text-center text-xs text-slate-400 mt-3">Sistem kami akan memverifikasi ketersediaan slot waktu secara real-time.</p>
        </div>
    </form>
</div>
@endsection

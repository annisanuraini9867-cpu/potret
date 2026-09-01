@extends('layouts.admin', ['title' => 'Detail Booking ' . $booking->booking_code, 'headerTitle' => 'Detail Reservasi & Unggah Foto'])

@section('content')
<div class="space-y-6">

    <!-- Top Card: Booking Summary & Status Update -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-black text-slate-900 font-mono">{{ $booking->booking_code }}</h2>
                <span class="px-3 py-1 text-xs font-bold uppercase rounded-full 
                    {{ $booking->status == 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $booking->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                    {{ $booking->status == 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                    {{ $booking->status }}
                </span>
            </div>
            <div class="text-xs text-slate-500 space-x-3">
                <span>Pelanggan: <strong class="text-slate-800">{{ $booking->customer_name }}</strong> ({{ $booking->customer_phone }})</span>
                <span>•</span>
                <span>Paket: <strong class="text-slate-800">{{ $booking->package->name }}</strong></span>
                <span>•</span>
                <span>Tanggal: <strong class="text-slate-800">{{ $booking->booking_date->format('d M Y') }}</strong> ({{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB)</span>
            </div>
        </div>

        <!-- Form Update Status Cepat & Link Booth -->
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('booth.session', $booking->booking_code) }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow transition flex items-center gap-1.5">
                <span>📸 Buka Ruang Sesi Booth ↗</span>
            </a>

            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-xl border border-slate-200">
                @csrf
                @method('PATCH')
                <span class="text-xs font-bold text-slate-600 pl-2">Status:</span>
                <select name="status" class="border border-slate-300 text-xs rounded-lg px-2.5 py-1.5 bg-white font-semibold">
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3.5 py-1.5 rounded-lg font-bold transition">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    <!-- Upload Box & Gallery Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form Upload Multi-Foto -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4 h-fit">
            <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <span>📤</span>
                <span>Unggah Foto Sesi Studio</span>
            </h3>
            
            <form action="{{ route('admin.photos.store', $booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-indigo-500 transition cursor-pointer bg-slate-50">
                    <span class="text-3xl block mb-2">📸</span>
                    <label class="block text-xs font-bold text-indigo-600 cursor-pointer">
                        Pilih File Foto (Bisa Banyak)
                        <input type="file" name="photos[]" multiple accept="image/*" required class="hidden" onchange="document.getElementById('file-chosen').innerText = this.files.length + ' file dipilih'">
                    </label>
                    <span id="file-chosen" class="text-[11px] text-slate-500 mt-1 block">Format JPG, PNG, WEBP (Maks 15MB/foto)</span>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs transition">
                    Unggah ke Galeri Pelanggan
                </button>
            </form>

            <div class="p-3 bg-slate-50 rounded-xl text-[11px] text-slate-500">
                💡 <em>Tip: Setelah semua foto berhasil diunggah, ubah status booking menjadi <strong>Completed</strong> agar pelanggan menerima notifikasi siap unduh.</em>
            </div>
        </div>

        <!-- Galeri Foto Terunggah -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                    <span>🖼</span>
                    <span>Galeri Foto Terunggah ({{ $booking->photos->count() }})</span>
                </h3>
                <a href="{{ route('gallery.show', $booking->booking_code) }}" target="_blank" class="text-xs text-indigo-600 font-bold hover:underline">
                    Preview Tampilan Pelanggan ↗
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @forelse($booking->photos as $photo)
                <div class="group relative rounded-xl overflow-hidden border border-slate-200 aspect-square bg-slate-100">
                    <img src="{{ $photo->url }}" alt="{{ $photo->file_name }}" class="w-full h-full object-cover">
                    
                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-2">
                        <span class="text-[10px] text-white/80 truncate">{{ $photo->file_name }}</span>
                        <form action="{{ route('admin.photos.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-1 bg-rose-600 text-white font-bold text-[10px] rounded-lg shadow hover:bg-rose-700 transition">
                                🗑 Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400 text-xs">
                    Belum ada foto yang diunggah untuk reservasi ini.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

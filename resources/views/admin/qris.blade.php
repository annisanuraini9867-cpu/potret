@extends('layouts.admin', ['title' => 'Pengaturan QRIS - Potret Diri'])

@section('content')
<div class="space-y-8">
    
    <div class="space-y-1">
        <h1 class="text-3xl font-black text-slate-900">Pengaturan QRIS</h1>
        <p class="text-xs sm:text-sm text-slate-500">Atur pembayaran dan harga layanan studio Anda.</p>
    </div>

    <form action="{{ route('admin.qris.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI: Penyedia & Konfigurasi Harga (7 Kolom) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Card 1: Penyedia QRIS -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>💳</span>
                        <span>Penyedia QRIS</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="space-y-1.5">
                            <label class="font-bold text-slate-500 uppercase text-[10px]">PAYMENT GATEWAY</label>
                            <select name="payment_gateway" class="w-full px-4 py-3 bg-slate-100/90 rounded-xl border border-transparent font-bold text-slate-800 focus:bg-white focus:border-[#F5BD23] focus:outline-none">
                                <option value="Gopay Merchant" {{ $paymentGateway == 'Gopay Merchant' ? 'selected' : '' }}>Gopay Merchant</option>
                                <option value="Midtrans Snap" {{ $paymentGateway == 'Midtrans Snap' ? 'selected' : '' }}>Midtrans Snap</option>
                                <option value="Xendit Payment" {{ $paymentGateway == 'Xendit Payment' ? 'selected' : '' }}>Xendit Payment</option>
                                <option value="DOKU Merchant" {{ $paymentGateway == 'DOKU Merchant' ? 'selected' : '' }}>DOKU Merchant</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-bold text-slate-500 uppercase text-[10px]">MERCHANT ID / NMID</label>
                            <input type="text" name="merchant_id" value="{{ $merchantId }}" class="w-full px-4 py-3 bg-slate-100/90 rounded-xl border border-transparent font-mono font-bold text-slate-800 focus:bg-white focus:border-[#F5BD23] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Card 2: Konfigurasi Harga -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-5">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>🏷</span>
                        <span>Konfigurasi Harga</span>
                    </h3>

                    <!-- Harga Per Lembar Cetak -->
                    <div class="space-y-1.5">
                        <label class="font-bold text-slate-500 uppercase text-[10px]">HARGA PER LEMBAR CETAK (IDR)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-slate-700 text-sm">Rp</span>
                            <input type="number" name="price_per_print" value="{{ $pricePerPrint }}" class="w-full pl-12 pr-4 py-3.5 bg-slate-100/90 rounded-xl border border-transparent font-black text-slate-900 text-base focus:bg-white focus:border-[#F5BD23] focus:outline-none">
                        </div>
                    </div>

                    <!-- Pilihan Paket Cetak -->
                    <div class="space-y-2 pt-1">
                        <span class="font-bold text-slate-500 uppercase text-[10px]">PILIHAN PAKET YANG TERSEDIA</span>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach([1, 2, 3, 4] as $pkgCount)
                            <button type="button" onclick="selectPkgCount({{ $pkgCount }})" id="btn-pkg-{{ $pkgCount }}" 
                                    class="py-2.5 rounded-xl border-2 font-black text-xs transition {{ $selectedPackageCount == $pkgCount ? 'border-[#F5BD23] bg-[#F5BD23]/20 text-slate-950' : 'border-slate-200 bg-white text-slate-600' }}">
                                {{ $pkgCount }} Cetak
                            </button>
                            @endforeach
                            <input type="hidden" name="package_count" id="input-package-count" value="{{ $selectedPackageCount }}">
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="p-4 bg-amber-50/70 border-l-4 border-[#F5BD23] rounded-r-2xl text-xs text-amber-950 leading-relaxed font-medium">
                        <strong>Info:</strong> Sistem otomatis membuat QRIS sesuai pilihan pelanggan. Harga yang diubah akan langsung aktif di kiosk.
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: Rekening Penampung & Info Pencairan (5 Kolom) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Status Rekening Penampung Card -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Status Rekening Penampung</h3>

                    <div class="p-4 bg-slate-100 rounded-2xl flex items-center gap-3.5 border border-slate-200">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center">
                            BCA
                        </div>
                        <div>
                            <div class="font-extrabold text-sm text-slate-900">BCA – Tabungan Utama</div>
                            <div class="font-mono text-xs text-slate-500">882–0192–331</div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-xs pt-1">
                        <span class="font-bold text-emerald-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>TERVERIFIKASI</span>
                        </span>
                        <a href="javascript:alert('Fitur ubah rekening penampung hubungi support.')" class="font-bold text-[#1D4ED8] hover:underline">
                            Ubah Rekening
                        </a>
                    </div>
                </div>

                <!-- Informasi Pencairan Dana Timeline -->
                <div class="bg-white rounded-3xl p-7 shadow-sm border border-slate-100 space-y-5">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Informasi Pencairan Dana</h3>

                    <div class="space-y-4 text-xs">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-sm flex-shrink-0">
                                📱
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-900">Pembayaran Pelanggan</h5>
                                <p class="text-slate-400 text-[11px]">Pelanggan scan QRIS di Kiosk.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm flex-shrink-0">
                                🏦
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-900">Proses Provider</h5>
                                <p class="text-slate-400 text-[11px]">Dana diproses oleh Payment Gateway.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm flex-shrink-0">
                                💵
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-900">Pencairan H+1</h5>
                                <p class="text-slate-400 text-[11px]">Dana cair ke rekening Anda besok harinya.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100 text-[10px] text-slate-400">
                        *Biaya MDR QRIS standar 0.7% sesuai regulasi BI.
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-4 px-6 rounded-2xl bg-[#F5BD23] hover:bg-[#E5AC10] active:scale-[0.99] text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                    <span>💾</span>
                    <span>Simpan Pengaturan</span>
                </button>

            </div>

        </div>

    </form>

</div>

<script>
    function selectPkgCount(cnt) {
        document.getElementById('input-package-count').value = cnt;
        for (let i = 1; i <= 4; i++) {
            const b = document.getElementById('btn-pkg-' + i);
            if (i === cnt) {
                b.className = 'py-2.5 rounded-xl border-2 font-black text-xs transition border-[#F5BD23] bg-[#F5BD23]/20 text-slate-950';
            } else {
                b.className = 'py-2.5 rounded-xl border-2 font-black text-xs transition border-slate-200 bg-white text-slate-600';
            }
        }
    }
</script>
@endsection

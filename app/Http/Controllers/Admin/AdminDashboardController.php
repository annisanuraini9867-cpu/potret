<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photo;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    /**
     * 1. Beranda (Ringkasan Studio Real-Time) - Sesuai Gambar 1
     */
    public function index()
    {
        $user = Auth::user();
        
        // Real-Time Metrics from Database
        $todayEarnings = (int) Booking::whereDate('booking_date', date('Y-m-d'))
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalSessions = Booking::count();
        $totalPrints = Photo::count();

        $recentBookings = Booking::with(['package', 'user', 'photos'])
            ->latest()
            ->take(10)
            ->get();

        $kioskStatus = cache('kiosk_status', session('kiosk_status', 'buka'));

        return view('admin.dashboard', compact(
            'user',
            'todayEarnings',
            'totalSessions',
            'totalPrints',
            'recentBookings',
            'kioskStatus'
        ));
    }

    /**
     * Real-time API endpoint for dashboard live polling
     */
    public function realtimeStats()
    {
        $todayEarnings = (int) Booking::whereDate('booking_date', date('Y-m-d'))
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalSessions = Booking::count();
        $totalPrints = Photo::count();
        $kioskStatus = cache('kiosk_status', session('kiosk_status', 'buka'));

        return response()->json([
            'today_earnings' => $todayEarnings,
            'today_earnings_formatted' => 'Rp ' . number_format($todayEarnings, 0, ',', '.'),
            'total_sessions' => $totalSessions,
            'total_prints'   => $totalPrints,
            'kiosk_status'   => $kioskStatus,
        ]);
    }

    /**
     * Update Status Kios (Buka / Tutup)
     * - Buka: Sesi foto gratis tanpa pembayaran
     * - Tutup: Sesi foto terkunci wajib pembayaran QRIS
     */
    public function updateKioskStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:buka,tutup',
        ]);

        $status = $request->input('status');
        cache(['kiosk_status' => $status], now()->addDays(30));
        session(['kiosk_status' => $status]);

        $msg = ($status === 'buka') 
            ? 'Status Kios berhasil diubah menjadi BUKA (Mode Bebas — Sesi foto langsung mulai tanpa perlu pembayaran).'
            : 'Status Kios berhasil diubah menjadi TUTUP (Mode Terkunci — Sesi foto terkunci dan memerlukan pembayaran QRIS terlebih dahulu).';

        return back()->with('success', $msg);
    }

    /**
     * 2. Kontrol Sesi - Sesuai Gambar 2
     */
    public function sessionControl()
    {
        $user = Auth::user();
        $selectedDuration = session('studio_session_duration', 5);
        $retakeEnabled = session('studio_retake_enabled', true);
        $retakeLimit = session('studio_retake_limit', 'unlimited');

        $recentActivities = Booking::with(['user', 'package'])->latest()->take(6)->get();

        return view('admin.session_control', compact(
            'user',
            'selectedDuration',
            'retakeEnabled',
            'retakeLimit',
            'recentActivities'
        ));
    }

    public function updateSessionControl(Request $request)
    {
        $request->validate([
            'duration' => 'required|integer',
            'retake_limit' => 'required|string',
        ]);

        session([
            'studio_session_duration' => $request->duration,
            'studio_retake_enabled'   => $request->has('retake_enabled'),
            'studio_retake_limit'     => $request->retake_limit,
        ]);

        return back()->with('success', 'Pengaturan sesi studio berhasil diperbarui!');
    }

    /**
     * 3. Galeri Foto - Sesuai Gambar 3
     */
    public function gallery()
    {
        $user = Auth::user();
        $photos = Photo::with('booking')->latest()->paginate(16);
        $totalPhotosCount = Photo::count();
        
        // Calculate real storage size in MB / GB
        $totalBytes = Photo::sum('file_size');
        $usedStorageMB = round($totalBytes / (1024 * 1024), 2);
        $usedStorageGB = round($totalBytes / (1024 * 1024 * 1024), 2);

        return view('admin.gallery', compact('user', 'photos', 'totalPhotosCount', 'usedStorageMB', 'usedStorageGB'));
    }

    /**
     * 4. Pengaturan QRIS - Sesuai Gambar 4
     */
    public function qris()
    {
        $user = Auth::user();
        $paymentGateway = session('qris_gateway', 'Gopay Merchant');
        $merchantId = session('qris_merchant_id', 'MID-92834012');
        $pricePerPrint = session('qris_price_per_print', 20000);
        $selectedPackageCount = session('qris_package_count', 1);

        $totalQrisTxn = Booking::where('status', 'completed')->where('total_amount', '>', 0)->count();
        $totalQrisVolume = Booking::where('status', 'completed')->where('total_amount', '>', 0)->sum('total_amount');

        return view('admin.qris', compact(
            'user',
            'paymentGateway',
            'merchantId',
            'pricePerPrint',
            'selectedPackageCount',
            'totalQrisTxn',
            'totalQrisVolume'
        ));
    }

    public function updateQris(Request $request)
    {
        $request->validate([
            'payment_gateway' => 'required|string',
            'merchant_id'     => 'required|string',
            'price_per_print' => 'required|numeric',
        ]);

        session([
            'qris_gateway'         => $request->payment_gateway,
            'qris_merchant_id'     => $request->merchant_id,
            'qris_price_per_print' => $request->price_per_print,
            'qris_package_count'   => $request->package_count ?? 1,
        ]);

        return back()->with('success', 'Konfigurasi QRIS & Harga berhasil disimpan!');
    }

    /**
     * 5. Kelola Template Foto - Sesuai Gambar 5
     */
    public function templates()
    {
        $user = Auth::user();

        $templates = [
            [
                'id' => 'classic-4-grid',
                'name' => 'Classic 4–Grid',
                'size' => '1200 x 1800 px',
                'is_default' => true,
            ],
            [
                'id' => 'cinematic-strip',
                'name' => 'Cinematic Strip',
                'size' => '600 x 1800 px',
                'is_default' => false,
            ],
            [
                'id' => 'polaroid-wide',
                'name' => 'Polaroid Wide',
                'size' => '1600 x 1600 px',
                'is_default' => false,
            ],
            [
                'id' => 'passport-trio',
                'name' => 'Passport Trio',
                'size' => '1800 x 1200 px',
                'is_default' => false,
            ],
        ];

        return view('admin.templates', compact('user', 'templates'));
    }

    /**
     * 6. Status Sistem - Sesuai Gambar
     */
    public function status()
    {
        $user = Auth::user();
        
        $printJobs = Photo::where('is_collage', true)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id'           => '#PD-' . $p->id,
                    'document'     => $p->file_name ?? 'Collage_Print.jpg',
                    'size'         => round(($p->file_size ?: 2400000) / (1024 * 1024), 1) . ' MB',
                    'status'       => 'completed',
                    'status_label' => '✓ Selesai',
                    'status_class' => 'bg-emerald-50 text-emerald-700',
                ];
            })
            ->toArray();

        return view('admin.status', compact('user', 'printJobs'));
    }
}

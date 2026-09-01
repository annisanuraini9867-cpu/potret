<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photo;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * 1. Beranda (Ringkasan Studio) - Sesuai Gambar 1
     */
    public function index()
    {
        $user = Auth::user();
        
        // Metrics
        $todayEarnings = Booking::whereDate('booking_date', date('Y-m-d'))
            ->where('status', 'completed')
            ->sum('total_amount');
        if ($todayEarnings == 0) $todayEarnings = 100000; // Demo fallback

        $totalSessions = Booking::count();
        if ($totalSessions == 0) $totalSessions = 50; // Demo fallback

        $totalPrints = Photo::count() * 4;
        if ($totalPrints == 0) $totalPrints = 520; // Demo fallback

        $recentBookings = Booking::with('package')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'todayEarnings',
            'totalSessions',
            'totalPrints',
            'recentBookings'
        ));
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

        return view('admin.session_control', compact(
            'user',
            'selectedDuration',
            'retakeEnabled',
            'retakeLimit'
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
        $photos = Photo::with('booking')->latest()->take(20)->get();

        return view('admin.gallery', compact('user', 'photos'));
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

        return view('admin.qris', compact(
            'user',
            'paymentGateway',
            'merchantId',
            'pricePerPrint',
            'selectedPackageCount'
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
        
        $printJobs = [
            [
                'id' => '#PD-9921',
                'document' => 'Session_Final_01.jpg',
                'size' => '2.4 MB',
                'status' => 'printing',
                'status_label' => '● Mencetak',
                'status_class' => 'bg-sky-100 text-sky-800',
            ],
            [
                'id' => '#PD-9920',
                'document' => 'Session_Preview_04.jpg',
                'size' => '1.8 MB',
                'status' => 'pending',
                'status_label' => 'Menunggu',
                'status_class' => 'bg-slate-100 text-slate-600',
            ],
            [
                'id' => '#PD-9919',
                'document' => 'Full_Grid_Test.pdf',
                'size' => '4.2 MB',
                'status' => 'completed',
                'status_label' => '✓ Selesai',
                'status_class' => 'bg-emerald-50 text-emerald-700',
            ],
        ];

        return view('admin.status', compact('user', 'printJobs'));
    }
}

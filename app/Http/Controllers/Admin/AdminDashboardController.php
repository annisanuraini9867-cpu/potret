<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photo;
use App\Models\Package;
use App\Models\StudioSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $kioskStatus = StudioSetting::get('kiosk_status', cache('kiosk_status', session('kiosk_status', 'buka')));

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
        $kioskStatus = StudioSetting::get('kiosk_status', cache('kiosk_status', session('kiosk_status', 'buka')));

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
        StudioSetting::set('kiosk_status', $status);
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
        $selectedDuration = (int) StudioSetting::get('session_duration', session('studio_session_duration', 5));
        $retakeEnabled = (bool)(int) StudioSetting::get('retake_enabled', session('studio_retake_enabled', 1));
        $retakeLimit = (string) StudioSetting::get('retake_limit', session('studio_retake_limit', 'unlimited'));

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

        $duration = (int) $request->duration;
        $retakeEnabled = $request->has('retake_enabled') ? '1' : '0';
        $retakeLimit = (string) $request->retake_limit;

        StudioSetting::set('session_duration', $duration);
        StudioSetting::set('retake_enabled', $retakeEnabled);
        StudioSetting::set('retake_limit', $retakeLimit);

        session([
            'studio_session_duration' => $duration,
            'studio_retake_enabled'   => (bool)(int)$retakeEnabled,
            'studio_retake_limit'     => $retakeLimit,
        ]);

        return back()->with('success', 'Pengaturan sesi studio berhasil disimpan secara permanen!');
    }

    /**
     * 3. Galeri Foto - Dikelompokkan Berdasarkan Sesi Foto (Booking)
     */
    public function gallery(Request $request)
    {
        $user = Auth::user();

        // Ambil sesi (Booking) yang memiliki foto, diurutkan dari yang terbaru
        $sessionQuery = Booking::with(['photos' => function ($q) {
            $q->orderByDesc('is_collage')->latest('id');
        }, 'user', 'package'])->has('photos');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $sessionQuery->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $sessions = $sessionQuery->latest('booking_date')->latest('start_time')->latest('id')->paginate(10);
        $totalSessionsWithPhotos = Booking::has('photos')->count();
        $totalPhotosCount = Photo::count();
        
        // Calculate real storage size in MB / GB
        $totalBytes = Photo::sum('file_size');
        $usedStorageMB = round($totalBytes / (1024 * 1024), 2);
        $usedStorageGB = round($totalBytes / (1024 * 1024 * 1024), 2);

        return view('admin.gallery', compact(
            'user', 
            'sessions', 
            'totalSessionsWithPhotos', 
            'totalPhotosCount', 
            'usedStorageMB', 
            'usedStorageGB'
        ));
    }

    /**
     * 4. Pengaturan QRIS - Sesuai Gambar 4
     */
    public function qris()
    {
        $user = Auth::user();
        $paymentGateway = StudioSetting::get('qris_gateway', session('qris_gateway', 'Gopay Merchant'));
        $merchantId = StudioSetting::get('qris_merchant_id', session('qris_merchant_id', 'MID-92834012'));
        $pricePerPrint = (int) StudioSetting::get('qris_price_per_print', session('qris_price_per_print', 20000));
        $selectedPackageCount = (int) StudioSetting::get('qris_package_count', session('qris_package_count', 1));

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
            'price_per_print' => 'required|numeric|min:0',
        ]);

        StudioSetting::set('qris_gateway', $request->payment_gateway);
        StudioSetting::set('qris_merchant_id', $request->merchant_id);
        StudioSetting::set('qris_price_per_print', $request->price_per_print);
        StudioSetting::set('qris_package_count', $request->package_count ?? 1);

        session([
            'qris_gateway'         => $request->payment_gateway,
            'qris_merchant_id'     => $request->merchant_id,
            'qris_price_per_print' => $request->price_per_print,
            'qris_package_count'   => $request->package_count ?? 1,
        ]);

        return back()->with('success', 'Konfigurasi QRIS & Harga berhasil disimpan secara permanen!');
    }

    /**
     * 5. Kelola Template Foto - Sesuai Gambar 5
     */
    public function templates()
    {
        $user = Auth::user();

        $all = \App\Http\Controllers\BoothController::getAllTemplates();
        $templates = array_values($all);
        $activeTemplateId = StudioSetting::get('default_template_id', session('booth_session.template_id', 'classic-4-grid'));
        foreach ($templates as &$tmpl) {
            $tmpl['is_default'] = ($tmpl['id'] === $activeTemplateId);
            $tmpl['size'] = $tmpl['aspect'] ?? '1200 x 1800 px';
        }

        return view('admin.templates', compact('user', 'templates'));
    }

    public function setDefaultTemplate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|string',
        ]);

        StudioSetting::set('default_template_id', $request->template_id);
        session(['booth_session.template_id' => $request->template_id]);

        return back()->with('success', 'Template default berhasil diubah dan disimpan!');
    }

    /**
     * Unggah Template Kolase Kustom (Overlay PNG Transparan)
     */
    public function uploadTemplate(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'slots'       => 'required|integer|in:1,2,3,4,6,8',
            'overlay'     => 'required|image|mimes:png|max:10240',
            'bg_color'    => 'nullable|string',
            'text_color'  => 'nullable|string',
            'description' => 'nullable|string|max:255',
        ]);

        $file = $request->file('overlay');
        $slug = Str::slug($request->name) . '-' . time();
        $filename = "{$slug}.png";
        $path = $file->storeAs('templates/overlays', $filename, 'public');

        $customTemplates = json_decode(StudioSetting::get('custom_templates', '[]'), true) ?: [];
        $newTemplate = [
            'id'             => 'custom-' . $slug,
            'name'           => $request->name,
            'category'       => ($request->slots == 8 ? '8_slots' : ($request->slots == 6 ? '6_slots' : ($request->slots == 4 ? '4_slots' : 'other_slots'))),
            'category_label' => $request->slots . ' Kolase',
            'frames'         => $request->slots . ' Frames',
            'slots'          => (int) $request->slots,
            'badge'          => '✨ Kustom',
            'aspect'         => '1200 x 1800 px',
            'bg_color'       => $request->bg_color ?: '#FFFFFF',
            'text_color'     => $request->text_color ?: '#0F172A',
            'accent'         => '#F5BD23',
            'card_bg'        => 'bg-amber-500/10 border-amber-500/30 text-slate-900',
            'description'    => $request->description ?: 'Template kolase kustom unggahan studio.',
            'overlay_url'    => asset('storage/' . $path),
            'is_custom'      => true,
        ];

        $customTemplates[] = $newTemplate;
        StudioSetting::set('custom_templates', json_encode($customTemplates));

        return back()->with('success', "Template kustom '{$request->name}' berhasil diunggah dan siap digunakan di bilik kiosk!");
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

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Photo;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BoothController extends Controller
{
    /**
     * Mendapatkan daftar 6 desain frame kolase 6 foto
     */
    public static function getFrameDesigns(): array
    {
        return [
            'korean_pastel' => [
                'id'          => 'korean_pastel',
                'name'        => 'Korean Pastel Pink',
                'badge'       => '🌸 K-Photobooth',
                'description' => 'Gaya manis ala studio foto Korea (Life Four Cuts). Warna pink pastel lembut dengan ornamen hati dan tanggal cantik.',
                'bg_color'    => '#FDF2F8',
                'card_bg'     => 'bg-pink-100/70 border-pink-300 text-pink-900',
                'text_color'  => '#9D174D',
                'accent'      => '#F472B6',
                'tagline'     => 'POTRET DIRI • SELF STUDIO',
                'footer'      => 'Happy Moments ♡ Love Yourself',
                'slots'       => 6,
            ],
            'filmstrip_35mm' => [
                'id'          => 'filmstrip_35mm',
                'name'        => '35mm Classic Filmstrip',
                'badge'       => '🎞 Vintage Analog',
                'description' => 'Sensasi gulungan roll film 35mm retro dengan lubang sproket film di kedua sisi dan nomor frame analog.',
                'bg_color'    => '#18181B',
                'card_bg'     => 'bg-zinc-900 border-zinc-700 text-zinc-100',
                'text_color'  => '#E4E4E7',
                'accent'      => '#EAB308',
                'tagline'     => 'KODAK STYLE • 35MM EXPOSURE',
                'footer'      => 'ISO 400 • MEMORIES IN GRAIN',
                'slots'       => 6,
            ],
            'studio_minimal_white' => [
                'id'          => 'studio_minimal_white',
                'name'        => 'Minimalist Studio White',
                'badge'       => '🤍 Modern Clean',
                'description' => 'Desain monokrom putih bersih dengan tipografi serif elegan, batas presisi, dan barcode studio estetik.',
                'bg_color'    => '#FFFFFF',
                'card_bg'     => 'bg-white border-slate-300 text-slate-900',
                'text_color'  => '#0F172A',
                'accent'      => '#475569',
                'tagline'     => 'POTRET DIRI • ATELIER',
                'footer'      => 'NO FILTER NEEDED • AUTHENTIC SELF',
                'slots'       => 6,
            ],
            'dark_elegance_gold' => [
                'id'          => 'dark_elegance_gold',
                'name'        => 'Dark Elegance & Gold',
                'badge'       => '✨ Luxury Glam',
                'description' => 'Latar belakang hitam mewah dipadu border aksen emas berkilau dan kaligrafi premium.',
                'bg_color'    => '#09090B',
                'card_bg'     => 'bg-zinc-950 border-amber-500/50 text-amber-100',
                'text_color'  => '#FDE047',
                'accent'      => '#F59E0B',
                'tagline'     => 'POTRET DIRI • SIGNATURE COLLECTION',
                'footer'      => 'GOLDEN MOMENTS • TIMELESS BEAUTY',
                'slots'       => 6,
            ],
            'y2k_cyber' => [
                'id'          => 'y2k_cyber',
                'name'        => 'Y2K Cyber Hologram',
                'badge'       => '⚡ Futuristic Glow',
                'description' => 'Gaya cyberpunk dengan gradasi warna neon cyan dan magenta menyala yang futuristik.',
                'bg_color'    => '#050816',
                'card_bg'     => 'bg-slate-950 border-cyan-500/60 text-cyan-200',
                'text_color'  => '#22D3EE',
                'accent'      => '#F43F5E',
                'tagline'     => 'CYBER STUDIO • NIGHT CITY EXP',
                'footer'      => 'FUTURE IS NOW • SYSTEM ONLINE',
                'slots'       => 6,
            ],
            'vintage_newspaper' => [
                'id'          => 'vintage_newspaper',
                'name'        => 'Vintage Newspaper',
                'badge'       => '📷 Vintage Warm',
                'description' => 'Kertas foto koran vintage klasik warna krem hangat dengan tipografi koran jadul.',
                'bg_color'    => '#FEF9C3',
                'card_bg'     => 'bg-amber-50 border-amber-200 text-stone-900',
                'text_color'  => '#44403C',
                'accent'      => '#D97706',
                'tagline'     => 'POTRET DIRI • THE DAILY PRESS',
                'footer'      => 'KEEPSAKE • CAPTURED FOREVER',
                'slots'       => 6,
            ],
        ];
    }

    /**
     * Halaman Utama Kiosk (Standby Screen)
     */
    public function index()
    {
        $kioskStatus = cache('kiosk_status', session('kiosk_status', 'buka'));
        return view('booth.index', compact('kioskStatus'));
    }

    /**
     * 1. Pilih Template Foto - Sesuai Gambar 1
     */
    public function selectTemplate()
    {
        $templates = [
            [
                'id'          => 'classic-4-grid',
                'name'        => 'Classic 4–Grid',
                'frames'      => '4 Frames (Square)',
                'badge'       => 'Default',
                'aspect'      => '1200 x 1800 px',
                'description' => 'Tata letak 4 foto persegi klasik dengan border putih elegan.',
                'img'         => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'id'          => 'cinematic-strip',
                'name'        => 'Cinematic Strip',
                'frames'      => '3 Frames (Panoramic)',
                'badge'       => 'Popular',
                'aspect'      => '600 x 1800 px',
                'description' => 'Format strip memanjang ala photobooth bioskop.',
                'img'         => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'id'          => 'polaroid-wide',
                'name'        => 'Polaroid Wide',
                'frames'      => '1 Frame (Nostalgic)',
                'badge'       => 'Vintage',
                'aspect'      => '1600 x 1600 px',
                'description' => 'Frame polaroid tunggal ukuran besar dengan ruang tulisan tangan.',
                'img'         => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'id'          => 'passport-trio',
                'name'        => 'Passport Trio',
                'frames'      => '3 Frames (Portrait)',
                'badge'       => 'Studio',
                'aspect'      => '1800 x 1200 px',
                'description' => 'Tiga foto portrait berdampingan dengan tipografi modern.',
                'img'         => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80',
            ],
        ];

        $selectedTemplate = session('booth_session.template_id', 'classic-4-grid');

        return view('booth.select_template', compact('templates', 'selectedTemplate'));
    }

    public function postTemplate(Request $request)
    {
        $templateId = $request->input('template_id', 'classic-4-grid');
        
        session([
            'booth_session.template_id' => $templateId,
        ]);

        $kioskStatus = cache('kiosk_status', session('kiosk_status', 'buka'));

        // 1. Status BUKA = Sesi foto tidak perlu pembayaran, langsung mulai ke kamera!
        if ($kioskStatus === 'buka') {
            $user = User::where('role', 'customer')->first() ?? User::first();
            $package = Package::first();
            $bookingCode = 'PTD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            Booking::create([
                'user_id'        => $user ? $user->id : 1,
                'customer_name'  => 'Pengunjung Kiosk (Mode Buka)',
                'customer_email' => 'kiosk@potretdiri.com',
                'customer_phone' => '08123456789',
                'package_id'     => $package ? $package->id : 1,
                'booking_code'   => $bookingCode,
                'booking_date'   => date('Y-m-d'),
                'start_time'     => date('H:i:s'),
                'end_time'       => date('H:i:s', strtotime('+15 minutes')),
                'total_amount'   => 0,
                'status'         => 'confirmed',
                'frame_design'   => $templateId,
            ]);

            session(['booth_session.booking_code' => $bookingCode]);

            return redirect()->route('booth.session', $bookingCode);
        }

        // 2. Status TUTUP = Sesi terkunci, wajib pembayaran dahulu sebelum mulai sesi foto
        return redirect()->route('booth.start.copies');
    }

    /**
     * 2. Pilihan Jumlah Cetakan - Sesuai Gambar 2
     */
    public function selectCopies()
    {
        $selectedCopies = session('booth_session.copies', 1);

        $copyOptions = [
            [
                'key'         => '1',
                'title'       => '1 Lembar',
                'price'       => 20000,
                'price_label' => 'Rp 20.000 / unit',
                'icon'        => '📄',
            ],
            [
                'key'         => '2',
                'title'       => '2 Lembar',
                'price'       => 40000,
                'price_label' => 'Rp 40.000 / unit',
                'icon'        => '📑',
            ],
            [
                'key'         => '3',
                'title'       => '3 Lembar',
                'price'       => 60000,
                'price_label' => 'Rp 60.000 / unit',
                'icon'        => '⧉',
            ],
            [
                'key'         => 'digital',
                'title'       => 'Hanya Digital',
                'price'       => 25000,
                'price_label' => 'Rp 25.000 (Semua File)',
                'icon'        => '☁',
            ],
        ];

        return view('booth.select_copies', compact('copyOptions', 'selectedCopies'));
    }

    public function postCopies(Request $request)
    {
        $copies = $request->input('copies', '1');

        $priceMap = [
            '1'       => 20000,
            '2'       => 40000,
            '3'       => 60000,
            'digital' => 25000,
        ];

        $totalPrice = $priceMap[$copies] ?? 20000;
        $txnId = 'PD-' . rand(1000000, 9999999) . 'X';

        session([
            'booth_session.copies'     => $copies,
            'booth_session.price'      => $totalPrice,
            'booth_session.txn_id'     => $txnId,
            'booth_session.order_id'   => '#PD-' . date('Y') . '-' . rand(1000, 9999),
        ]);

        return redirect()->route('booth.start.payment');
    }

    /**
     * 3. Selesaikan Pembayaran (QRIS Dinamis) - Sesuai Gambar 3
     */
    public function paymentQris()
    {
        $session = session('booth_session', [
            'template_id' => 'classic-4-grid',
            'copies'      => '1',
            'price'       => 35000,
            'txn_id'      => 'PD-8829310X',
            'order_id'    => '#PD-' . date('Y') . '-8892',
        ]);

        return view('booth.payment_qris', compact('session'));
    }

    public function confirmPayment(Request $request)
    {
        $session = session('booth_session', []);
        
        // Buat booking aktif di database jika belum ada
        $user = User::where('role', 'customer')->first() ?? User::first();
        $package = Package::first();

        $bookingCode = 'PTD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        
        Booking::create([
            'user_id'        => $user ? $user->id : 1,
            'customer_name'  => $user ? $user->name : 'Pengunjung Kiosk QRIS',
            'customer_email' => $user ? $user->email : 'kiosk@potretdiri.com',
            'customer_phone' => $user ? ($user->phone ?? '08123456789') : '08123456789',
            'package_id'     => $package ? $package->id : 1,
            'booking_code'   => $bookingCode,
            'booking_date'   => date('Y-m-d'),
            'start_time'     => date('H:i:s'),
            'end_time'       => date('H:i:s', strtotime('+15 minutes')),
            'total_amount'   => $session['price'] ?? 20000,
            'status'         => 'confirmed',
            'frame_design'   => $session['template_id'] ?? 'classic-4-grid',
        ]);

        session(['booth_session.booking_code' => $bookingCode]);

        return redirect()->route('booth.start.success');
    }

    /**
     * 4. Pembayaran Berhasil! - Sesuai Gambar 4
     */
    public function paymentSuccess()
    {
        $session = session('booth_session', [
            'order_id'     => '#PD-' . date('Y') . '-8892',
            'template_id'  => 'classic-4-grid',
            'booking_code' => 'PTD-DEMO-BOOTH',
        ]);

        $templateNames = [
            'classic-4-grid'  => 'Classic 4–Grid 4R',
            'cinematic-strip' => 'Lumina Cinema 4R',
            'polaroid-wide'   => 'Polaroid Nostalgia Wide',
            'passport-trio'   => 'Passport Trio Portrait',
        ];

        $packageName = $templateNames[$session['template_id'] ?? 'classic-4-grid'] ?? 'Lumina Cinema 4R';
        $bookingCode = $session['booking_code'] ?? 'PTD-DEMO-BOOTH';

        return view('booth.payment_success', compact('session', 'packageName', 'bookingCode'));
    }

    /**
     * Cari Kode Booking Kiosk
     */
    public function search(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string',
        ]);

        $code = trim($request->booking_code);

        // Jika demo code
        if (strtoupper($code) === 'PTD-DEMO-BOOTH') {
            return redirect()->route('booth.session', 'PTD-DEMO-BOOTH');
        }

        $booking = Booking::where('booking_code', $code)->first();

        if (!$booking) {
            return back()->with('error', 'Kode reservasi tidak ditemukan. Pastikan Anda memasukkan kode yang benar.');
        }

        return redirect()->route('booth.session', $booking->booking_code);
    }

    /**
     * Halaman Sesi Kamera Touchscreen Photo Booth
     */
    public function session(string $booking_code)
    {
        if (strtoupper($booking_code) === 'PTD-DEMO-BOOTH') {
            $booking = (object)[
                'booking_code' => 'PTD-DEMO-BOOTH',
                'customer_name'=> 'Pengunjung Demo Booth',
                'package_name' => 'Paket Studio Kiosk (6 Pose)',
                'frame_design' => 'korean_pastel',
                'status'       => 'confirmed',
            ];
        } else {
            $booking = Booking::with(['user', 'package'])
                ->where('booking_code', $booking_code)
                ->firstOrFail();
            
            $booking = (object)[
                'booking_code' => $booking->booking_code,
                'customer_name'=> $booking->user ? $booking->user->name : 'Pelanggan Studio',
                'package_name' => $booking->package ? $booking->package->name : 'Paket Photo Booth',
                'frame_design' => $booking->frame_design ?? 'korean_pastel',
                'status'       => $booking->status,
            ];
        }

        $frames = self::getFrameDesigns();

        return view('booth.session', compact('booking', 'frames'));
    }

    /**
     * Menyimpan hasil sesi foto
     */
    public function saveSession(Request $request, string $booking_code)
    {
        $request->validate([
            'photos'        => 'required|array|min:1',
            'collage_image' => 'required|string',
            'frame_id'      => 'required|string',
        ]);

        // Tangani mode demo
        if (strtoupper($booking_code) === 'PTD-DEMO-BOOTH') {
            $demoBooking = Booking::where('booking_code', 'PTD-DEMO-BOOTH')->first();
            if (!$demoBooking) {
                $user = User::first();
                $package = Package::first();
                $demoBooking = Booking::create([
                    'user_id'      => $user ? $user->id : 1,
                    'package_id'   => $package ? $package->id : 1,
                    'booking_code' => 'PTD-DEMO-BOOTH',
                    'booking_date' => date('Y-m-d'),
                    'start_time'   => date('H:i:s'),
                    'end_time'     => date('H:i:s', strtotime('+15 minutes')),
                    'total_amount' => 50000,
                    'status'       => 'confirmed',
                ]);
            }
            $booking = $demoBooking;
        } else {
            $booking = Booking::where('booking_code', $booking_code)->firstOrFail();
        }

        $storageFolder = "galleries/{$booking->booking_code}";

        // 1. Simpan foto kolase komposit utama
        $collageData = $request->collage_image;
        if (preg_match('/^data:image\/(\w+);base64,/', $collageData, $type)) {
            $collageData = substr($collageData, strpos($collageData, ',') + 1);
            $type = strtolower($type[1]);
            $collageData = base64_decode($collageData);

            $collageFileName = "Collage-6-{$booking->booking_code}-" . time() . ".{$type}";
            $collagePath = "{$storageFolder}/{$collageFileName}";
            Storage::disk('public')->put($collagePath, $collageData);

            Photo::create([
                'booking_id' => $booking->id,
                'file_path'  => $collagePath,
                'file_name'  => $collageFileName,
                'file_size'  => strlen($collageData),
                'is_collage' => true,
            ]);
        }

        // 2. Simpan 6 foto satuan
        foreach ($request->photos as $index => $photoData) {
            if (preg_match('/^data:image\/(\w+);base64,/', $photoData, $type)) {
                $photoData = substr($photoData, strpos($photoData, ',') + 1);
                $type = strtolower($type[1]);
                $photoData = base64_decode($photoData);

                $singleFileName = "Slot-" . ($index + 1) . "-{$booking->booking_code}.{$type}";
                $singlePath = "{$storageFolder}/{$singleFileName}";
                Storage::disk('public')->put($singlePath, $photoData);

                Photo::create([
                    'booking_id' => $booking->id,
                    'file_path'  => $singlePath,
                    'file_name'  => $singleFileName,
                    'file_size'  => strlen($photoData),
                    'is_collage' => false,
                ]);
            }
        }

        // 3. Update status booking menjadi completed & catat frame yang dipilih
        $booking->update([
            'frame_design' => $request->frame_id,
            'status'       => 'completed',
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Sesi foto berhasil disimpan! Seluruh 6 foto dan kolase siap diunduh.',
            'redirect_url' => route('gallery.show', $booking->booking_code),
        ]);
    }
}

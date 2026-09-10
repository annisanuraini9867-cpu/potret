<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Photo;
use App\Models\Package;
use App\Models\User;
use App\Models\StudioSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BoothController extends Controller
{
    /**
     * Mendapatkan daftar lengkap seluruh template frame foto kolase (8, 6, 4, 3, 2, 1 slot)
     */
    public static function getAllTemplates(): array
    {
        return [
            // ==========================================
            // KATEGORI 8 KOLASE (8 POSES / SLOTS)
            // ==========================================
            'party-8-grid' => [
                'id'             => 'party-8-grid',
                'name'           => 'Mega 8–Shot Party Grid',
                'category'       => '8_slots',
                'category_label' => '8 Kolase',
                'frames'         => '8 Frames (2x4 Grid)',
                'slots'          => 8,
                'badge'          => '🎉 8 Poses',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#0F172A',
                'text_color'     => '#F8FAFC',
                'accent'         => '#F5BD23',
                'card_bg'        => 'bg-slate-900 border-amber-500/50 text-slate-100',
                'description'    => 'Kolase 8 foto berjejer 2 kolom x 4 baris. Sangat cocok untuk pesta bareng geng atau banyak ekspresi seru!',
                'tagline'        => 'POTRET DIRI • PARTY SQUAD',
                'footer'         => '8 MEMORIES • UNLIMITED FUN',
            ],
            'korean-8-strip' => [
                'id'             => 'korean-8-strip',
                'name'           => 'Korean Double 4–Strip (8 Shots)',
                'category'       => '8_slots',
                'category_label' => '8 Kolase',
                'frames'         => '8 Frames (Twin 4-Strips)',
                'slots'          => 8,
                'badge'          => '🌸 K-Duo',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#FDF2F8',
                'text_color'     => '#9D174D',
                'accent'         => '#F472B6',
                'card_bg'        => 'bg-pink-100/80 border-pink-300 text-pink-950',
                'description'    => 'Dua strip foto ala Korea berdampingan (masing-masing 4 pose, total 8 pose unik). Bisa dipotong jadi 2 strip!',
                'tagline'        => 'LIFE 4 CUTS • TWIN STRIP',
                'footer'         => 'K-PHOTOBOOTH • HAPPY MOMENTS',
            ],

            // ==========================================
            // KATEGORI 6 KOLASE (6 POSES / SLOTS)
            // ==========================================
            'korean-6-grid' => [
                'id'             => 'korean-6-grid',
                'name'           => 'Korean Pastel 6–Grid',
                'category'       => '6_slots',
                'category_label' => '6 Kolase',
                'frames'         => '6 Frames (2x3 Grid)',
                'slots'          => 6,
                'badge'          => '🌸 K-Photobooth',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#FFF1F2',
                'text_color'     => '#BE123C',
                'accent'         => '#FB7185',
                'card_bg'        => 'bg-rose-50 border-rose-200 text-rose-950',
                'description'    => 'Format 6 foto 2x3 terpopuler ala studio Korea. Warna pastel lembut dengan ornamen hati dan tanggal cantik.',
                'tagline'        => 'POTRET DIRI • KOREAN ATELIER',
                'footer'         => 'LOVE YOURSELF • SWEET MEMORIES',
            ],
            'filmstrip-6-retro' => [
                'id'             => 'filmstrip-6-retro',
                'name'           => '35mm Analog Filmstrip 6',
                'category'       => '6_slots',
                'category_label' => '6 Kolase',
                'frames'         => '6 Frames (2x3 Sprocket)',
                'slots'          => 6,
                'badge'          => '🎞 Vintage Film',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#18181B',
                'text_color'     => '#F4F4F5',
                'accent'         => '#EAB308',
                'card_bg'        => 'bg-zinc-900 border-zinc-700 text-zinc-100',
                'description'    => 'Sensasi roll film 35mm retro klasik dengan lubang sproket film di kedua sisi dan nomor frame analog.',
                'tagline'        => 'KODAK STYLE • 35MM ROLL',
                'footer'         => 'ISO 400 • ANALOG MEMORIES',
            ],
            'studio-minimal-6' => [
                'id'             => 'studio-minimal-6',
                'name'           => 'Studio Minimalist White 6',
                'category'       => '6_slots',
                'category_label' => '6 Kolase',
                'frames'         => '6 Frames (3x2 Landscape)',
                'slots'          => 6,
                'badge'          => '🤍 Modern Clean',
                'aspect'         => '1800 x 1200 px',
                'bg_color'       => '#FFFFFF',
                'text_color'     => '#0F172A',
                'accent'         => '#475569',
                'card_bg'        => 'bg-white border-slate-300 text-slate-900',
                'description'    => 'Desain monokrom putih bersih dengan tipografi serif elegan dan batas presisi.',
                'tagline'        => 'POTRET DIRI • ATELIER',
                'footer'         => 'NO FILTER NEEDED • AUTHENTIC SELF',
            ],
            'dark-elegance-6' => [
                'id'             => 'dark-elegance-6',
                'name'           => 'Dark Elegance & Gold 6',
                'category'       => '6_slots',
                'category_label' => '6 Kolase',
                'frames'         => '6 Frames (2x3 Grid)',
                'slots'          => 6,
                'badge'          => '✨ Luxury Glam',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#09090B',
                'text_color'     => '#FDE047',
                'accent'         => '#F59E0B',
                'card_bg'        => 'bg-zinc-950 border-amber-500/50 text-amber-100',
                'description'    => 'Latar belakang hitam mewah dipadu border aksen emas berkilau dan kaligrafi premium.',
                'tagline'        => 'POTRET DIRI • SIGNATURE COLLECTION',
                'footer'         => 'GOLDEN MOMENTS • TIMELESS BEAUTY',
            ],
            'vintage-newspaper-6' => [
                'id'             => 'vintage-newspaper-6',
                'name'           => 'Vintage Newspaper 6',
                'category'       => '6_slots',
                'category_label' => '6 Kolase',
                'frames'         => '6 Frames (2x3 Grid)',
                'slots'          => 6,
                'badge'          => '📷 Vintage Warm',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#FEF9C3',
                'text_color'     => '#44403C',
                'accent'         => '#D97706',
                'card_bg'        => 'bg-amber-50 border-amber-200 text-stone-900',
                'description'    => 'Kertas foto koran vintage klasik warna krem hangat dengan tipografi koran jadul.',
                'tagline'        => 'POTRET DIRI • THE DAILY PRESS',
                'footer'         => 'KEEPSAKE • CAPTURED FOREVER',
            ],

            // ==========================================
            // KATEGORI 4 KOLASE (4 POSES / SLOTS)
            // ==========================================
            'classic-4-grid' => [
                'id'             => 'classic-4-grid',
                'name'           => 'Classic 4–Grid Standard',
                'category'       => '4_slots',
                'category_label' => '4 Kolase',
                'frames'         => '4 Frames (2x2 Square)',
                'slots'          => 4,
                'badge'          => '⭐ Default',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#FFFFFF',
                'text_color'     => '#0F172A',
                'accent'         => '#F5BD23',
                'card_bg'        => 'bg-white border-slate-300 text-slate-900',
                'description'    => 'Tata letak 4 foto persegi klasik dengan border putih elegan dan proporsi sempurna 4R.',
                'tagline'        => 'POTRET DIRI • SELF STUDIO',
                'footer'         => 'TIMELESS MOMENTS IN FRAME',
            ],
            'korean-4-strip' => [
                'id'             => 'korean-4-strip',
                'name'           => 'Korean Single 4–Strip',
                'category'       => '4_slots',
                'category_label' => '4 Kolase',
                'frames'         => '4 Frames (1x4 Vertical)',
                'slots'          => 4,
                'badge'          => '📸 K-Strip',
                'aspect'         => '600 x 1800 px',
                'bg_color'       => '#FEF3C7',
                'text_color'     => '#92400E',
                'accent'         => '#F59E0B',
                'card_bg'        => 'bg-amber-50 border-amber-300 text-amber-900',
                'description'    => 'Strip vertikal 4 pose memanjang ala photobooth box Korea dengan barcode estetik di bawah.',
                'tagline'        => 'LIFE 4 CUTS • SINGLE STRIP',
                'footer'         => 'SEOUL PHOTO • SNAP & CHILL',
            ],
            'y2k-cyber-4' => [
                'id'             => 'y2k-cyber-4',
                'name'           => 'Y2K Cyber Hologram 4',
                'category'       => '4_slots',
                'category_label' => '4 Kolase',
                'frames'         => '4 Frames (2x2 Pop)',
                'slots'          => 4,
                'badge'          => '⚡ Futuristic',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#050816',
                'text_color'     => '#22D3EE',
                'accent'         => '#F43F5E',
                'card_bg'        => 'bg-slate-950 border-cyan-500/60 text-cyan-200',
                'description' => 'Gaya cyberpunk dengan gradasi warna neon cyan dan magenta menyala yang futuristik.',
                'tagline'        => 'CYBER STUDIO • NIGHT CITY',
                'footer'         => 'FUTURE IS NOW • SYSTEM ONLINE',
            ],

            // ==========================================
            // KATEGORI LAINNYA (3, 2, 1 SLOTS)
            // ==========================================
            'cinematic-strip' => [
                'id'             => 'cinematic-strip',
                'name'           => 'Cinematic 3–Strip Panorama',
                'category'       => 'other_slots',
                'category_label' => '3 & 2 Kolase',
                'frames'         => '3 Frames (Panoramic)',
                'slots'          => 3,
                'badge'          => '🎬 Cinema',
                'aspect'         => '600 x 1800 px',
                'bg_color'       => '#09090B',
                'text_color'     => '#E4E4E7',
                'accent'         => '#EAB308',
                'card_bg'        => 'bg-zinc-900 border-zinc-700 text-zinc-100',
                'description'    => 'Format strip 3 foto melebar sinematik ala photobooth bioskop.',
                'tagline'        => 'CINEMA ARCHIVE • 35MM',
                'footer'         => 'STILL MOMENTS • DIRECTOR CUT',
            ],
            'duo-bestie-2' => [
                'id'             => 'duo-bestie-2',
                'name'           => 'Duo Bestie Split (2 Frames)',
                'category'       => 'other_slots',
                'category_label' => '3 & 2 Kolase',
                'frames'         => '2 Frames (1x2 Portrait)',
                'slots'          => 2,
                'badge'          => '👯 Duo',
                'aspect'         => '1200 x 1800 px',
                'bg_color'       => '#F8FAFC',
                'text_color'     => '#1E293B',
                'accent'         => '#3B82F6',
                'card_bg'        => 'bg-blue-50 border-blue-200 text-blue-900',
                'description'    => 'Dua foto portrait besar bertumpuk, sangat cocok untuk pasangan atau sahabat sejati.',
                'tagline'        => 'BEST FRIENDS • DUO PORTRAIT',
                'footer'         => 'TOGETHER FOREVER • BESTIE MOMENTS',
            ],
            'polaroid-wide' => [
                'id'             => 'polaroid-wide',
                'name'           => 'Polaroid Nostalgia Wide',
                'category'       => 'other_slots',
                'category_label' => 'Polaroid (1)',
                'frames'         => '1 Frame (Big Solo)',
                'slots'          => 1,
                'badge'          => '📷 Polaroid',
                'aspect'         => '1400 x 1600 px',
                'bg_color'       => '#FAF7EE',
                'text_color'     => '#44403C',
                'accent'         => '#D97706',
                'card_bg'        => 'bg-amber-50 border-amber-200 text-stone-900',
                'description'    => 'Frame polaroid tunggal ukuran besar dengan ruang tulisan tangan bernuansa vintage.',
                'tagline'        => 'POLAROID MEMOIRS • 1990S',
                'footer'         => 'AUTHENTIC MOMENT • CAPTURED FOREVER',
            ],
        ];

        // Gabungkan template kustom dari database StudioSetting jika ada
        try {
            $customList = json_decode(StudioSetting::get('custom_templates', '[]'), true) ?: [];
            foreach ($customList as $ct) {
                if (!empty($ct['id'])) {
                    $templates[$ct['id']] = $ct;
                }
            }
        } catch (\Throwable $e) {
        }

        return $templates;
    }

    /**
     * Mencari konfigurasi template berdasarkan ID atau alias lamanya
     */
    public static function findTemplate(?string $id): array
    {
        $all = self::getAllTemplates();
        if ($id && isset($all[$id])) {
            return $all[$id];
        }

        $aliases = [
            'korean_pastel'         => 'korean-6-grid',
            'filmstrip_35mm'        => 'filmstrip-6-retro',
            'studio_minimal_white'  => 'studio-minimal-6',
            'dark_elegance_gold'    => 'dark-elegance-6',
            'y2k_cyber'             => 'y2k-cyber-4',
            'vintage_newspaper'     => 'vintage-newspaper-6',
            'passport-trio'         => 'cinematic-strip',
        ];

        if ($id && isset($aliases[$id]) && isset($all[$aliases[$id]])) {
            return $all[$aliases[$id]];
        }

        return $all['classic-4-grid'] ?? reset($all);
    }

    /**
     * Mendapatkan daftar frame untuk kompatibilitas view
     */
    public static function getFrameDesigns(): array
    {
        return self::getAllTemplates();
    }

    /**
     * Pilihan Palet Warna Frame
     */
    public static function getFrameColors(): array
    {
        return [
            'original' => [
                'id'       => 'original',
                'name'     => 'Bawaan Template',
                'hex'      => '#FFFFFF',
                'bg'       => null,
                'text'     => null,
                'border'   => '#E2E8F0',
                'badge'    => 'Default',
                'preview'  => 'bg-gradient-to-tr from-slate-200 to-white',
            ],
            'white' => [
                'id'       => 'white',
                'name'     => 'Studio White',
                'hex'      => '#FFFFFF',
                'bg'       => '#FFFFFF',
                'text'     => '#0F172A',
                'border'   => '#CBD5E1',
                'badge'    => 'Pure White',
                'preview'  => 'bg-white',
            ],
            'black' => [
                'id'       => 'black',
                'name'     => 'Noir Black',
                'hex'      => '#09090B',
                'bg'       => '#09090B',
                'text'     => '#F8FAFC',
                'border'   => '#27272A',
                'badge'    => 'Obsidian',
                'preview'  => 'bg-zinc-950',
            ],
            'pink' => [
                'id'       => 'pink',
                'name'     => 'Baby Pink',
                'hex'      => '#FCE7F3',
                'bg'       => '#FCE7F3',
                'text'     => '#831843',
                'border'   => '#F472B6',
                'badge'    => 'Pastel Pink',
                'preview'  => 'bg-pink-100',
            ],
            'lavender' => [
                'id'       => 'lavender',
                'name'     => 'Soft Lilac',
                'hex'      => '#EDE9FE',
                'bg'       => '#EDE9FE',
                'text'     => '#5B21B6',
                'border'   => '#A78BFA',
                'badge'    => 'Lavender',
                'preview'  => 'bg-purple-100',
            ],
            'sage' => [
                'id'       => 'sage',
                'name'     => 'Matcha Sage',
                'hex'      => '#DCFCE7',
                'bg'       => '#DCFCE7',
                'text'     => '#14532D',
                'border'   => '#86EFAC',
                'badge'    => 'Sage Green',
                'preview'  => 'bg-emerald-100',
            ],
            'blue' => [
                'id'       => 'blue',
                'name'     => 'Baby Sky',
                'hex'      => '#E0F2FE',
                'bg'       => '#E0F2FE',
                'text'     => '#075985',
                'border'   => '#7DD3FC',
                'badge'    => 'Sky Blue',
                'preview'  => 'bg-sky-100',
            ],
            'butter' => [
                'id'       => 'butter',
                'name'     => 'Butter Cream',
                'hex'      => '#FEF9C3',
                'bg'       => '#FEF9C3',
                'text'     => '#713F12',
                'border'   => '#FDE047',
                'badge'    => 'Butter Yellow',
                'preview'  => 'bg-yellow-100',
            ],
            'sepia' => [
                'id'       => 'sepia',
                'name'     => 'Vintage Kraft',
                'hex'      => '#F5EBE1',
                'bg'       => '#F5EBE1',
                'text'     => '#44403C',
                'border'   => '#D6C7B2',
                'badge'    => 'Vintage Paper',
                'preview'  => 'bg-amber-100/70',
            ],
            'wine' => [
                'id'       => 'wine',
                'name'     => 'Deep Burgundy',
                'hex'      => '#4C0519',
                'bg'       => '#4C0519',
                'text'     => '#FFF1F2',
                'border'   => '#BE123C',
                'badge'    => 'Wine Red',
                'preview'  => 'bg-rose-950',
            ],
            'gold' => [
                'id'       => 'gold',
                'name'     => 'Royal Gold & Dark',
                'hex'      => '#FEF3C7',
                'bg'       => '#1E1B18',
                'text'     => '#F5BD23',
                'border'   => '#F5BD23',
                'badge'    => 'Gold Luxury',
                'preview'  => 'bg-stone-900 border border-amber-400',
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
     * 1. Pilih Template Foto - Dengan Pilihan 8, 6, 4, 3, 2, 1 Kolase
     */
    public function selectTemplate()
    {
        $templates = array_values(self::getAllTemplates());
        $colors = array_values(self::getFrameColors());
        $selectedTemplate = session('booth_session.template_id', 'classic-4-grid');
        $selectedColor = session('booth_session.frame_color', 'original');

        return view('booth.select_template', compact('templates', 'colors', 'selectedTemplate', 'selectedColor'));
    }

    public function postTemplate(Request $request)
    {
        $templateId = $request->input('template_id', 'classic-4-grid');
        $frameColor = $request->input('frame_color', 'original');
        
        session([
            'booth_session.template_id' => $templateId,
            'booth_session.frame_color' => $frameColor,
        ]);

        $kioskStatus = StudioSetting::get('kiosk_status', cache('kiosk_status', session('kiosk_status', 'buka')));

        // 1. Status BUKA = Sesi foto tidak perlu pembayaran, langsung mulai ke kamera!
        if ($kioskStatus === 'buka') {
            $user = User::where('role', 'customer')->first() ?? User::first();
            $package = Package::first();
            $bookingCode = 'PTD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $durationMinutes = (int) StudioSetting::get('session_duration', session('studio_session_duration', 5));

            Booking::create([
                'user_id'        => $user ? $user->id : 1,
                'customer_name'  => 'Pengunjung Kiosk (Mode Buka)',
                'customer_email' => 'kiosk@potretdiri.com',
                'customer_phone' => '08123456789',
                'package_id'     => $package ? $package->id : 1,
                'booking_code'   => $bookingCode,
                'booking_date'   => date('Y-m-d'),
                'start_time'     => date('H:i:s'),
                'end_time'       => date('H:i:s', strtotime("+{$durationMinutes} minutes")),
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
        $basePrice = (int) StudioSetting::get('qris_price_per_print', session('qris_price_per_print', 20000));

        $copyOptions = [
            [
                'key'         => '1',
                'title'       => '1 Lembar',
                'price'       => $basePrice,
                'price_label' => 'Rp ' . number_format($basePrice, 0, ',', '.') . ' / unit',
                'icon'        => '📄',
            ],
            [
                'key'         => '2',
                'title'       => '2 Lembar',
                'price'       => $basePrice * 2,
                'price_label' => 'Rp ' . number_format($basePrice * 2, 0, ',', '.') . ' / unit',
                'icon'        => '📑',
            ],
            [
                'key'         => '3',
                'title'       => '3 Lembar',
                'price'       => $basePrice * 3,
                'price_label' => 'Rp ' . number_format($basePrice * 3, 0, ',', '.') . ' / unit',
                'icon'        => '⧉',
            ],
            [
                'key'         => 'digital',
                'title'       => 'Hanya Digital',
                'price'       => max(10000, (int) round($basePrice * 1.25)),
                'price_label' => 'Rp ' . number_format(max(10000, (int) round($basePrice * 1.25)), 0, ',', '.') . ' (Semua File)',
                'icon'        => '☁',
            ],
        ];

        return view('booth.select_copies', compact('copyOptions', 'selectedCopies'));
    }

    public function postCopies(Request $request)
    {
        $copies = $request->input('copies', '1');
        $basePrice = (int) StudioSetting::get('qris_price_per_print', session('qris_price_per_print', 20000));

        $priceMap = [
            '1'       => $basePrice,
            '2'       => $basePrice * 2,
            '3'       => $basePrice * 3,
            'digital' => max(10000, (int) round($basePrice * 1.25)),
        ];

        $totalPrice = $priceMap[$copies] ?? $basePrice;
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
            $defaultTmplId = session('booth_session.template_id', 'classic-4-grid');
            $booking = (object)[
                'booking_code' => 'PTD-DEMO-BOOTH',
                'customer_name'=> 'Pengunjung Demo Booth',
                'package_name' => 'Paket Studio Kiosk (Demo)',
                'frame_design' => $defaultTmplId,
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
                'frame_design' => $booking->frame_design ?? session('booth_session.template_id', 'classic-4-grid'),
                'status'       => $booking->status,
            ];
        }

        $frames = self::getFrameDesigns();
        $template = self::findTemplate($booking->frame_design);
        $colors = array_values(self::getFrameColors());
        $selectedColor = session('booth_session.frame_color', 'original');

        $selectedDuration = (int) StudioSetting::get('session_duration', session('studio_session_duration', 5));
        $retakeEnabled = (bool)(int) StudioSetting::get('retake_enabled', session('studio_retake_enabled', 1));
        $retakeLimit = (string) StudioSetting::get('retake_limit', session('studio_retake_limit', 'unlimited'));

        return view('booth.session', compact(
            'booking',
            'frames',
            'template',
            'colors',
            'selectedColor',
            'selectedDuration',
            'retakeEnabled',
            'retakeLimit'
        ));
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

        // Bersihkan foto lama jika ini adalah sesi retake
        $oldPhotos = Photo::where('booking_id', $booking->id)->get();
        foreach ($oldPhotos as $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto->file_path);
            $oldPhoto->delete();
        }

        // 1. Simpan foto kolase komposit utama
        $collageData = $request->collage_image;
        if (preg_match('/^data:image\/(\w+);base64,/', $collageData, $type)) {
            $collageData = substr($collageData, strpos($collageData, ',') + 1);
            $type = strtolower($type[1]);
            $collageData = base64_decode($collageData);

            $collageFileName = "Collage-{$booking->booking_code}-" . time() . ".{$type}";
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

    /**
     * Verifikasi PIN Admin dari Layar Kiosk Standby
     */
    public function verifyAdminPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
        ]);

        $pin = $request->pin;

        // Cari admin yang cocok dengan PIN ini
        $admin = User::where('role', 'admin')->where('admin_pin', $pin)->first();

        // Fallback jika default PIN 123456
        if (!$admin && $pin === '123456') {
            $admin = User::where('role', 'admin')->first();
        }

        if ($admin) {
            \Illuminate\Support\Facades\Auth::login($admin);
            return response()->json([
                'success'      => true,
                'message'      => 'PIN terverifikasi! Mengalihkan ke panel admin...',
                'redirect_url' => route('admin.dashboard'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'PIN Admin salah. Silakan masukkan 6 digit PIN yang benar.',
        ], 422);
    }
}

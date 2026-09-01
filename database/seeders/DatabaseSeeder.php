<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@potretdiri.com'],
            [
                'name' => 'Admin Potret Diri',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        // 2. Buat User Pelanggan Contoh
        $customer = User::firstOrCreate(
            ['email' => 'customer@potretdiri.com'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '089876543210',
            ]
        );

        // 3. Paket-paket Sesi Foto
        $packages = [
            [
                'name' => 'Single Portrait',
                'slug' => 'single-portrait',
                'price' => 75000,
                'duration_minutes' => 20,
                'max_persons' => 1,
                'description' => 'Sesi foto solo profesional tanpa fotografer. Bebas berekspresi dengan remote shutter nirkabel. Termasuk semua softcopy & 1 cetak foto fisik.',
                'is_active' => true,
            ],
            [
                'name' => 'Couple & Bestie Session',
                'slug' => 'couple-bestie-session',
                'price' => 120000,
                'duration_minutes' => 30,
                'max_persons' => 2,
                'description' => 'Paket favorit untuk pasangan atau sahabat! Termasuk akses seluruh properti studio, filter warna/BW, semua digital softcopy, dan 2 lembar cetak kolase.',
                'is_active' => true,
            ],
            [
                'name' => 'Group & Squad Fun',
                'slug' => 'group-squad-fun',
                'price' => 180000,
                'duration_minutes' => 45,
                'max_persons' => 5,
                'description' => 'Cocok untuk seru-seruan bareng teman geng atau keluarga kecil. Akses properti lengkap, background berganti, semua file digital via cloud drive & 4 lembar cetak.',
                'is_active' => true,
            ],
            [
                'name' => 'Graduation / Special Moment',
                'slug' => 'graduation-special-moment',
                'price' => 250000,
                'duration_minutes' => 60,
                'max_persons' => 6,
                'description' => 'Paket eksklusif untuk momen wisuda, ulang tahun, atau prewedding casual. Durasi lebih leluasa 60 menit, full softcopy HD, dan paket cetak premium.',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkgData) {
            Package::updateOrCreate(
                ['slug' => $pkgData['slug']],
                $pkgData
            );
        }

        // 4. Buat Reservasi Contoh Siap Digunakan untuk Tes Photo Booth
        $firstPackage = Package::first();
        \App\Models\Booking::updateOrCreate(
            ['booking_code' => 'PTD-DEMO-BOOTH'],
            [
                'user_id'        => $customer->id,
                'customer_name'  => 'Andi Pratama (Demo)',
                'customer_email' => 'customer@potretdiri.com',
                'customer_phone' => '089876543210',
                'package_id'     => $firstPackage->id,
                'booking_date'   => date('Y-m-d'),
                'start_time'     => '10:00:00',
                'end_time'       => '10:30:00',
                'status'         => 'confirmed',
                'total_amount'   => $firstPackage->price,
                'notes'          => 'Booking siap pakai untuk mencoba sesi live photo booth.',
            ]
        );
    }
}

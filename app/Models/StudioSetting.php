<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Nilai default awal sistem POTRET
     */
    public static array $defaults = [
        'kiosk_status'         => 'buka',
        'session_duration'     => 5,
        'retake_enabled'       => '1',
        'retake_limit'         => 'unlimited',
        'qris_gateway'         => 'Gopay Merchant',
        'qris_merchant_id'     => 'MID-92834012',
        'qris_price_per_print' => 20000,
        'qris_package_count'   => 1,
        'default_template_id'  => 'classic-4-grid',
    ];

    /**
     * Ambil nilai pengaturan studio berdasarkan key dengan fallback otomatis
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $setting = static::where('key', $key)->first();
            if ($setting !== null && $setting->value !== null) {
                return $setting->value;
            }
        } catch (\Throwable $e) {
            // Fallback aman jika tabel belum siap
        }

        if ($default !== null) {
            return $default;
        }

        return static::$defaults[$key] ?? null;
    }

    /**
     * Simpan atau perbarui nilai pengaturan ke database
     */
    public static function set(string $key, mixed $value): static
    {
        $val = is_bool($value) ? ($value ? '1' : '0') : (string)$value;

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $val]
        );
    }
}

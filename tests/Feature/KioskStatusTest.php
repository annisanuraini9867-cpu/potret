<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class KioskStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_toggle_kiosk_status_to_buka_and_tutup(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Set to TUTUP
        $response = $this->actingAs($admin)->post(route('admin.kiosk.status'), [
            'status' => 'tutup',
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('tutup', Cache::get('kiosk_status'));

        // 2. Set to BUKA
        $response = $this->actingAs($admin)->post(route('admin.kiosk.status'), [
            'status' => 'buka',
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('buka', Cache::get('kiosk_status'));
    }

    public function test_when_kiosk_is_buka_selecting_template_goes_directly_to_photo_session_without_payment(): void
    {
        Cache::put('kiosk_status', 'buka');

        $response = $this->post(route('booth.start.postTemplate'), [
            'template_id' => 'classic-4-grid',
        ]);

        // Langsung redirect ke halaman sesi live kamera (tanpa bayar)
        $bookingCode = session('booth_session.booking_code');
        $this->assertNotEmpty($bookingCode);
        $response->assertRedirect(route('booth.session', $bookingCode));

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $bookingCode,
            'total_amount' => 0,
            'status'       => 'confirmed',
        ]);
    }

    public function test_when_kiosk_is_tutup_selecting_template_goes_to_payment_copies_selection(): void
    {
        Cache::put('kiosk_status', 'tutup');

        $response = $this->post(route('booth.start.postTemplate'), [
            'template_id' => 'classic-4-grid',
        ]);

        // Sesi terkunci, diarahkan ke pemilihan cetak & QRIS
        $response->assertRedirect(route('booth.start.copies'));
    }
}

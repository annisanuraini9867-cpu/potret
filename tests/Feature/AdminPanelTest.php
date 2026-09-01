<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::factory()->create([
            'role'        => 'admin',
            'studio_name' => 'Studio Mandiri Pro',
            'admin_pin'   => '123456',
        ]);
    }

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Studio');
        $response->assertSee('PENDAPATAN HARI INI');
        $response->assertSee('Status Kios');
        $response->assertSee('Sesi Terbaru');
    }

    public function test_admin_session_control_page_and_update(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.session-control'));
        $response->assertStatus(200);
        $response->assertSee('Durasi Sesi');
        $response->assertSee('Pengaturan Foto Ulang');
        $response->assertSee('STUDIO PREVIEW');

        $updateResponse = $this->actingAs($this->admin)->post(route('admin.session-control.update'), [
            'duration'       => 15,
            'retake_enabled' => 1,
            'retake_limit'   => '3',
        ]);

        $updateResponse->assertRedirect();
        $this->assertEquals(15, session('studio_session_duration'));
        $this->assertEquals('3', session('studio_retake_limit'));
    }

    public function test_admin_gallery_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.gallery'));
        $response->assertStatus(200);
        $response->assertSee('Total Penyimpanan');
        $response->assertSee('Total Foto Sesi');
        $response->assertSee('Semua Sesi');
    }

    public function test_admin_qris_settings_page_and_update(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.qris'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan QRIS');
        $response->assertSee('Penyedia QRIS');
        $response->assertSee('BCA – Tabungan Utama');

        $updateResponse = $this->actingAs($this->admin)->post(route('admin.qris.update'), [
            'payment_gateway' => 'Midtrans Snap',
            'merchant_id'     => 'MID-12345678',
            'price_per_print' => 25000,
            'package_count'   => 2,
        ]);

        $updateResponse->assertRedirect();
        $this->assertEquals('Midtrans Snap', session('qris_gateway'));
        $this->assertEquals(25000, session('qris_price_per_print'));
    }

    public function test_admin_templates_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.templates'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Template');
        $response->assertSee('Classic 4–Grid');
        $response->assertSee('Cinematic Strip');
        $response->assertSee('Polaroid Wide');
        $response->assertSee('Passport Trio');
    }

    public function test_admin_status_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.status'));
        $response->assertStatus(200);
        $response->assertSee('USB HUB CONNECTIVITY');
        $response->assertSee('Terhubung');
        $response->assertSee('CPU TEMPERATURE');
        $response->assertSee('Konfigurasi Kamera');
        $response->assertSee('Pengaturan Printer');
        $response->assertSee('Antrean Cetak');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoothFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_step1_select_template_renders_and_submits(): void
    {
        $response = $this->get(route('booth.start.template'));
        $response->assertStatus(200);
        $response->assertSee('Pilih Template Foto');
        $response->assertSee('Classic 4–Grid');
        $response->assertSee('Cinematic Strip');
        $response->assertSee('Polaroid Wide');
        $response->assertSee('Passport Trio');

        $postResponse = $this->post(route('booth.start.postTemplate'), [
            'template_id' => 'cinematic-strip',
        ]);

        $postResponse->assertRedirect(route('booth.start.copies'));
        $this->assertEquals('cinematic-strip', session('booth_session.template_id'));
    }

    public function test_step2_select_copies_renders_and_submits(): void
    {
        $response = $this->withSession([
            'booth_session' => ['template_id' => 'cinematic-strip']
        ])->get(route('booth.start.copies'));

        $response->assertStatus(200);
        $response->assertSee('Berapa banyak yang ingin Anda cetak?');
        $response->assertSee('1 Lembar');
        $response->assertSee('2 Lembar');
        $response->assertSee('3 Lembar');
        $response->assertSee('Hanya Digital');

        $postResponse = $this->withSession([
            'booth_session' => ['template_id' => 'cinematic-strip']
        ])->post(route('booth.start.postCopies'), [
            'copies' => '2',
        ]);

        $postResponse->assertRedirect(route('booth.start.payment'));
        $this->assertEquals(40000, session('booth_session.price'));
        $this->assertEquals('2', session('booth_session.copies'));
    }

    public function test_step3_payment_qris_page_renders(): void
    {
        $response = $this->withSession([
            'booth_session' => [
                'template_id' => 'cinematic-strip',
                'copies'      => '2',
                'price'       => 40000,
                'txn_id'      => 'PD-8829310X',
                'order_id'    => '#PD-2026-8892',
            ]
        ])->get(route('booth.start.payment'));

        $response->assertStatus(200);
        $response->assertSee('Selesaikan Pembayaran');
        $response->assertSee('40.000');
        $response->assertSee('PD-8829310X');
        $response->assertSee('POTRET DIRI STUDIO');
    }

    public function test_step4_confirm_payment_creates_booking_and_redirects_to_success(): void
    {
        $response = $this->withSession([
            'booth_session' => [
                'template_id' => 'cinematic-strip',
                'copies'      => '2',
                'price'       => 40000,
                'txn_id'      => 'PD-8829310X',
                'order_id'    => '#PD-2026-8892',
            ]
        ])->post(route('booth.start.confirmPayment'));

        $response->assertRedirect(route('booth.start.success'));

        $this->assertDatabaseHas('bookings', [
            'total_amount' => 40000,
            'frame_design' => 'cinematic-strip',
            'status'       => 'confirmed',
        ]);
    }

    public function test_step5_payment_success_page_renders(): void
    {
        $response = $this->withSession([
            'booth_session' => [
                'order_id'     => '#PD-2026-8892',
                'template_id'  => 'cinematic-strip',
                'booking_code' => 'PTD-DEMO-BOOTH',
            ]
        ])->get(route('booth.start.success'));

        $response->assertStatus(200);
        $response->assertSee('Pembayaran Berhasil!');
        $response->assertSee('#PD-2026-8892');
        $response->assertSee('Lumina Cinema 4R');
        $response->assertSee('Mulai Sesi Foto');
    }
}

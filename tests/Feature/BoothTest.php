<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BoothTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_booth_index_page_can_be_rendered(): void
    {
        $response = $this->get(route('booth.index'));
        $response->assertStatus(200);
        $response->assertSee('START PHOTO');
        $response->assertSee('Konsol Admin');
    }

    public function test_booth_search_redirects_to_session(): void
    {
        $package = Package::first();
        $booking = Booking::create([
            'customer_name'  => 'Dian Sastro',
            'customer_email' => 'dian@example.com',
            'customer_phone' => '0877777777',
            'package_id'     => $package->id,
            'booking_date'   => date('Y-m-d'),
            'start_time'     => '14:00:00',
            'end_time'       => '14:30:00',
            'status'         => 'confirmed',
            'total_amount'   => $package->price,
        ]);

        $response = $this->post(route('booth.search'), [
            'booking_code' => $booking->booking_code,
        ]);

        $response->assertRedirect(route('booth.session', $booking->booking_code));
    }

    public function test_booth_session_page_displays_all_six_frame_options(): void
    {
        $package = Package::first();
        $booking = Booking::create([
            'customer_name'  => 'Reza Rahadian',
            'customer_email' => 'reza@example.com',
            'customer_phone' => '0888888888',
            'package_id'     => $package->id,
            'booking_date'   => date('Y-m-d'),
            'start_time'     => '16:00:00',
            'end_time'       => '16:30:00',
            'status'         => 'confirmed',
            'total_amount'   => $package->price,
        ]);

        $response = $this->get(route('booth.session', $booking->booking_code));
        $response->assertStatus(200);

        // Verifikasi elemen Preview & Print Station muncul di halaman
        $response->assertSee('Original Photos');
        $response->assertSee('Frame Preview');
        $response->assertSee('GIF Preview');
        $response->assertSee('Print Photo');
        $response->assertSee('Download Digital');
        $response->assertSee('Finish Session');
    }

    public function test_save_booth_session_stores_six_photos_and_one_composite_collage(): void
    {
        Storage::fake('public');

        $package = Package::first();
        $booking = Booking::create([
            'customer_name'  => 'Ayu Laksmi',
            'customer_email' => 'ayu@example.com',
            'customer_phone' => '0899999999',
            'package_id'     => $package->id,
            'booking_date'   => date('Y-m-d'),
            'start_time'     => '17:00:00',
            'end_time'       => '17:30:00',
            'status'         => 'pending',
            'total_amount'   => $package->price,
        ]);

        // Mock 1px transparent PNG base64
        $fakeBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $photosArray = [
            $fakeBase64,
            $fakeBase64,
            $fakeBase64,
            $fakeBase64,
            $fakeBase64,
            $fakeBase64,
        ];

        $response = $this->postJson(route('booth.save', $booking->booking_code), [
            'frame_id'      => 'korean_pastel',
            'collage_image' => $fakeBase64,
            'photos'        => $photosArray,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // Verifikasi database
        $freshBooking = $booking->fresh();
        $this->assertEquals('completed', $freshBooking->status);
        $this->assertEquals('korean_pastel', $freshBooking->frame_design);

        // Harus ada 7 foto total: 1 kolase + 6 foto satuan
        $this->assertEquals(7, Photo::where('booking_id', $booking->id)->count());
        $this->assertEquals(1, Photo::where('booking_id', $booking->id)->where('is_collage', true)->count());
        $this->assertEquals(6, Photo::where('booking_id', $booking->id)->where('is_collage', false)->count());
    }
}

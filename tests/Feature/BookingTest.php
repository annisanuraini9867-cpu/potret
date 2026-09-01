<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_shows_photo_packages(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Single Portrait');
        $response->assertSee('Potret Diri');
    }

    public function test_customer_can_create_a_booking_successfully(): void
    {
        $package = Package::first();
        $today = date('Y-m-d');

        $response = $this->post(route('bookings.store'), [
            'package_id'     => $package->id,
            'customer_name'  => 'Budi Setiawan',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081299998888',
            'booking_date'   => $today,
            'start_time'     => '14:00',
            'notes'          => 'Tolong siapkan kursi tinggi.',
        ]);

        $booking = Booking::where('customer_email', 'budi@example.com')->first();

        $this->assertNotNull($booking);
        $this->assertEquals('pending', $booking->status);
        $this->assertStringStartsWith('PTD-', $booking->booking_code);

        $response->assertRedirect(route('bookings.success', $booking->booking_code));
    }

    public function test_schedule_clash_prevention(): void
    {
        $package = Package::where('duration_minutes', 30)->first() ?? Package::first();
        $today = date('Y-m-d');

        // 1. Buat booking pertama pada jam 15:00 - 15:30
        Booking::create([
            'customer_name'  => 'Client 1',
            'customer_email' => 'c1@example.com',
            'customer_phone' => '0811111111',
            'package_id'     => $package->id,
            'booking_date'   => $today,
            'start_time'     => '15:00:00',
            'end_time'       => '15:30:00',
            'status'         => 'confirmed',
            'total_amount'   => $package->price,
        ]);

        // 2. Coba booking kedua pada jam 15:15 (overlap/bentrok)
        $response = $this->from(route('bookings.create'))->post(route('bookings.store'), [
            'package_id'     => $package->id,
            'customer_name'  => 'Client 2',
            'customer_email' => 'c2@example.com',
            'customer_phone' => '0822222222',
            'booking_date'   => $today,
            'start_time'     => '15:15',
        ]);

        $response->assertRedirect(route('bookings.create'));
        $response->assertSessionHasErrors('start_time');

        // Pastikan Client 2 tidak tersimpan
        $this->assertDatabaseMissing('bookings', [
            'customer_email' => 'c2@example.com',
        ]);
    }

    public function test_admin_can_update_booking_status(): void
    {
        $admin = User::where('role', 'admin')->first();
        $package = Package::first();
        $today = date('Y-m-d');

        $booking = Booking::create([
            'customer_name'  => 'Siti Rahma',
            'customer_email' => 'siti@example.com',
            'customer_phone' => '0833333333',
            'package_id'     => $package->id,
            'booking_date'   => $today,
            'start_time'     => '10:00:00',
            'end_time'       => '10:30:00',
            'status'         => 'pending',
            'total_amount'   => $package->price,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.bookings.updateStatus', $booking->id), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertEquals('confirmed', $booking->fresh()->status);
    }

    public function test_admin_can_upload_and_delete_photos(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->first();
        $package = Package::first();

        $booking = Booking::create([
            'customer_name'  => 'Rina Kusuma',
            'customer_email' => 'rina@example.com',
            'customer_phone' => '0844444444',
            'package_id'     => $package->id,
            'booking_date'   => date('Y-m-d'),
            'start_time'     => '11:00:00',
            'end_time'       => '11:30:00',
            'status'         => 'confirmed',
            'total_amount'   => $package->price,
        ]);

        $file = UploadedFile::fake()->image('session1.jpg');

        $response = $this->actingAs($admin)->post(route('admin.photos.store', $booking->id), [
            'photos' => [$file],
        ]);

        $response->assertRedirect();
        $photo = Photo::where('booking_id', $booking->id)->first();
        $this->assertNotNull($photo);
        Storage::disk('public')->assertExists($photo->file_path);

        // Test delete photo
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.photos.destroy', $photo->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing($photo->file_path);
    }

    public function test_customer_can_search_and_view_gallery(): void
    {
        $package = Package::first();
        $booking = Booking::create([
            'customer_name'  => 'Galih Permana',
            'customer_email' => 'galih@example.com',
            'customer_phone' => '0855555555',
            'package_id'     => $package->id,
            'booking_date'   => date('Y-m-d'),
            'start_time'     => '13:00:00',
            'end_time'       => '13:30:00',
            'status'         => 'completed',
            'total_amount'   => $package->price,
        ]);

        // Test Search POST
        $searchResponse = $this->post(route('gallery.search'), [
            'booking_code' => $booking->booking_code,
        ]);
        $searchResponse->assertRedirect(route('gallery.show', $booking->booking_code));

        // Test Gallery Show View
        $galleryView = $this->get(route('gallery.show', $booking->booking_code));
        $galleryView->assertStatus(200);
        $galleryView->assertSee('Galih Permana');
    }
}

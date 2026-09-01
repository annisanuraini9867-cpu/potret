<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_step1_account_page_can_be_rendered(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('Create Admin Account');
        $response->assertSee('Continue to Studio Info');
    }

    public function test_step1_form_submission_stores_session_and_redirects(): void
    {
        $response = $this->post(route('onboarding.postStep1'), [
            'name'     => 'Budi Studio Owner',
            'email'    => 'budi@studiomandiri.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('onboarding.step2'));
        $this->assertEquals('budi@studiomandiri.com', session('onboarding_data.email'));
    }

    public function test_step2_studio_info_page_renders_with_session_data(): void
    {
        $response = $this->withSession([
            'onboarding_data' => [
                'name'     => 'Budi Studio Owner',
                'email'    => 'budi@studiomandiri.com',
                'password' => 'secret123',
            ]
        ])->get(route('onboarding.step2'));

        $response->assertStatus(200);
        $response->assertSee('Informasi Studio');
        $response->assertSee('Lanjut ke Pembayaran');
    }

    public function test_step2_submission_stores_studio_info_and_redirects_to_step3(): void
    {
        $response = $this->withSession([
            'onboarding_data' => [
                'name'     => 'Budi Studio Owner',
                'email'    => 'budi@studiomandiri.com',
                'password' => 'secret123',
            ]
        ])->post(route('onboarding.postStep2'), [
            'studio_name'    => 'Lensa Cerah Studio',
            'studio_address' => 'Jl. Senopati No. 123, Kebayoran Baru',
            'studio_city'    => 'Jakarta Selatan',
            'booth_type'     => 'Self-Photo Studio (Box Room)',
        ]);

        $response->assertRedirect(route('onboarding.step3'));
        $this->assertEquals('Lensa Cerah Studio', session('onboarding_data.studio_name'));
    }

    public function test_step3_payment_summary_page_displays_order_details(): void
    {
        $response = $this->withSession([
            'onboarding_data' => [
                'name'           => 'Budi Studio Owner',
                'email'          => 'budi@studiomandiri.com',
                'password'       => 'secret123',
                'studio_name'    => 'Lensa Cerah Studio',
                'studio_address' => 'Jl. Senopati No. 123, Kebayoran Baru',
                'studio_city'    => 'Jakarta Selatan',
                'booth_type'     => 'Self-Photo Studio (Box Room)',
            ]
        ])->get(route('onboarding.step3'));

        $response->assertStatus(200);
        $response->assertSee('Metode Pembayaran');
        $response->assertSee('Ringkasan Pesanan');
        $response->assertSee('Lensa Cerah Studio');
        $response->assertSee('277.500');
    }

    public function test_step3_payment_creates_admin_user_and_redirects_to_success(): void
    {
        $response = $this->withSession([
            'onboarding_data' => [
                'name'           => 'Budi Studio Owner',
                'email'          => 'budi@studiomandiri.com',
                'password'       => 'secret123',
                'studio_name'    => 'Lensa Cerah Studio',
                'studio_address' => 'Jl. Senopati No. 123, Kebayoran Baru',
                'studio_city'    => 'Jakarta Selatan',
                'booth_type'     => 'Self-Photo Studio (Box Room)',
            ]
        ])->post(route('onboarding.postStep3'), [
            'payment_method' => 'Virtual Account BCA',
        ]);

        $response->assertRedirect(route('onboarding.success'));

        // Verifikasi User dibuat dengan role admin dan info studio
        $this->assertDatabaseHas('users', [
            'email'       => 'budi@studiomandiri.com',
            'role'        => 'admin',
            'studio_name' => 'Lensa Cerah Studio',
        ]);

        // Verifikasi user langsung terautentikasi
        $this->assertAuthenticated();
    }

    public function test_set_pin_page_and_saving_6_digit_pin(): void
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'admin_pin' => '123456',
        ]);

        $response = $this->actingAs($admin)->get(route('onboarding.setPin'));
        $response->assertStatus(200);
        $response->assertSee('Buat PIN Admin');
        $response->assertSeeText('SIMPAN & LANJUTKAN');

        $submitResponse = $this->actingAs($admin)->post(route('onboarding.postSetPin'), [
            'pin' => '987654',
        ]);

        $submitResponse->assertRedirect(route('admin.dashboard'));

        $this->assertEquals('987654', $admin->fresh()->admin_pin);
    }
}

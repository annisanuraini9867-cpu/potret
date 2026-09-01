<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPinVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_pin_verification_succeeds_and_logs_in_admin(): void
    {
        $admin = User::where('role', 'admin')->first();
        $admin->update(['admin_pin' => '654321']);

        $response = $this->postJson(route('booth.verify-admin-pin'), [
            'pin' => '654321',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'      => true,
            'redirect_url' => route('admin.dashboard'),
        ]);

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_pin_verification_fails_with_wrong_pin(): void
    {
        $admin = User::where('role', 'admin')->first();
        $admin->update(['admin_pin' => '654321']);

        $response = $this->postJson(route('booth.verify-admin-pin'), [
            'pin' => '000000',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);

        $this->assertGuest();
    }
}

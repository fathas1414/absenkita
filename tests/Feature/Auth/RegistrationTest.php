<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'nim_or_nik' => '123456789012',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(
            route('dashboard', absolute: false)
        );

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'nim_or_nik' => '123456789012',
            'email' => 'test@example.com',
            'role' => 'anggota',
        ]);
    }
}

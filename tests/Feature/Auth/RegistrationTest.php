<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_registration_screen_can_be_rendered(): void
    {
        try {
            $response = $this->get('/register');
            $response->assertStatus(200);
        } catch (\Throwable $e) {
            // Dump the exception to see the real error
            dump($e->getMessage());
            $this->fail('Test failed with an exception: ' . $e->getMessage());
        }
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'nim' => '1234567890',
            'nama' => 'Test User',
            'jenis_kelamin' => 'L',
            'no_telp' => '081234567890',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.index', absolute: false));
    }
}

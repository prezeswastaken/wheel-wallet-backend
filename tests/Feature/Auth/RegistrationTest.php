<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use App\Models\User;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegister(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertAuthenticated();
    }

    public function testUserCanNotRegisterPasswordsDoNotMatch(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'wrong_password'
        ]);

        $response->assertInvalid('password');
    }

    public function testUserCanNotRegisterInvalidName(): void
    {
        $response = $this->post('/register', [
            'name' => Str::random(256),
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertInvalid('name');
    }

    public function testUserCanNotRegisterEmailAlreadyExists(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertInvalid('email');
    }
}

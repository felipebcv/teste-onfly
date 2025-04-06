<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_token()
    {
        // Arrange
        $password = 'secret';
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make($password),
        ]);

        // Act
        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => $password,
        ]);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token'
                 ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('secret'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Invalid credentials'
                 ]);
    }
}

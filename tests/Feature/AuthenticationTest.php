<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{

    use RefreshDatabase;

    public function test_registration(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/auth/register', $userData);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            'status_code'
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
//
    public function test_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                'token',
                'token_type',
                ],


            'status_code'
        ]);
    }

    public function test_logout(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password'),
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $token = $loginResponse->json()['data']['token'];

        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'User Logged Out Successfully',
            'status_code' => 200,
        ]);
    }
}

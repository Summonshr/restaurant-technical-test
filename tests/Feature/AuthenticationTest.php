<?php

namespace Tests\Feature;

use App\Models\CustomUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginIsSuccessfullWithValidCredentials()
    {
        $password = fake()->password;

        $user = CustomUser::factory()->create([
            'password' => $password,
        ]);

        $requestData = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/login', $requestData);

        $response->assertStatus(200);

        $response->assertJsonStructure(['token']);
    }

    public function testLoginIsNotSuccessfullWithValidCredentials()
    {
        $requestData = [
            'email' => fake()->safeEmail,
            'password' => fake()->password,
        ];

        $response = $this->postJson('/api/login', $requestData);

        $response->assertStatus(401);

        $response->assertJson(['message' => 'Invalid credentials']);
    }
}

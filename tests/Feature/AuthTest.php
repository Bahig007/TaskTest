<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password')
        ]);

        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /** @test */
    public function users_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function admins_can_login_with_valid_credentials()
    {
        $admin = Admin::factory()->create([
            'password' => bcrypt($password = 'adminpassword')
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /** @test */
    public function admins_cannot_login_with_invalid_credentials()
    {
        $admin = Admin::factory()->create();

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthorized']);
    }
}
<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper function to perform login and retrieve token
     */
    private function loginAndGetToken($url, $email, $password)
    {
        $response = $this->postJson($url, [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);

        return $response->json('token');
    }

    /** @test */
    public function users_can_login_with_valid_credentials()
    {
        // Create a user with valid credentials
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password') // Ensure password is hashed
        ]);

        // Login and retrieve token
        $token = $this->loginAndGetToken('/api/user/login', $user->email, $password);

        // Test protected route using the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/user/me');

        $response->assertStatus(200); // Assert status is OK
    }

    /** @test */
    public function users_cannot_login_with_invalid_credentials()
    {
        // Create a user (but without setting the password)
        $user = User::factory()->create();

        // Attempt login with wrong credentials
        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => 'wrongpassword', // Incorrect password
        ]);

        // Assert unauthorized status and correct message
        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function admins_can_login_with_valid_credentials()
    {
        // Create an admin with valid credentials
        $admin = Admin::factory()->create([
            'password' => bcrypt($password = 'adminpassword')
        ]);

        // Login and retrieve token for admin
        $token = $this->loginAndGetToken('/api/admin/login', $admin->email, $password);

        // Test protected route using the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/admin/me');

        $response->assertStatus(200); // Assert status is OK
    }

    /** @test */
    public function admins_cannot_login_with_invalid_credentials()
    {
        // Create an admin (but don't set password)
        $admin = Admin::factory()->create();

        // Attempt login with wrong credentials
        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'wrongpassword', // Incorrect password
        ]);

        // Assert unauthorized status and correct message
        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthorized']);
    }
}
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
      
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password') 
        ]);

       
        $token = $this->loginAndGetToken('/api/user/login', $user->email, $password);

       
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/user/me');

        $response->assertStatus(200); 
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

        
        $token = $this->loginAndGetToken('/api/admin/login', $admin->email, $password);

       
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/admin/me');

        $response->assertStatus(200); 
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
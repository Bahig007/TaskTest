<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function required_fields_are_validated_for_task_creation()
    {
        $user = User::factory()->create();
        $invalidData = [
            'title' => '',
            'due_date' => '',
            'status' => 'pending',
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/user/tasks', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'due_date']);
    }

    /** @test */
    public function unauthenticated_requests_are_denied_for_task_creation()
    {
        $invalidData = [
            'title' => 'New Task',
            'due_date' => '2025-02-01',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/user/tasks', $invalidData);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthenticated']);
    }
}
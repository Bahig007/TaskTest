<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_create_own_tasks()
    {
        $user = User::factory()->create();
        $taskData = [
            'title' => 'New Task',
            'due_date' => '2025-02-01',
            'status' => 'pending',
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/user/tasks', $taskData);

        $response->assertStatus(201)
                 ->assertJson(['title' => 'New Task']);
    }

    /** @test */
    public function users_cannot_create_tasks_for_other_users()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $taskData = [
            'title' => 'Task for another user',
            'due_date' => '2025-02-01',
            'status' => 'pending',
        ];

        $response = $this->actingAs($anotherUser, 'api')->postJson('/api/user/tasks', $taskData);

        $response->assertStatus(403)
                 ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function users_can_update_their_own_tasks()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $taskData = ['title' => 'Updated Task'];

        $response = $this->actingAs($user, 'api')->putJson('/api/user/tasks/'.$task->id, $taskData);

        $response->assertStatus(200)
                 ->assertJson(['title' => 'Updated Task']);
    }

    /** @test */
    public function users_cannot_update_other_users_tasks()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->actingAs($user, 'api')->putJson('/api/user/tasks/'.$task->id, ['title' => 'Updated Task']);

        $response->assertStatus(403)
                 ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function users_can_delete_their_own_tasks()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->deleteJson('/api/user/tasks/'.$task->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Task deleted successfully']);
    }

    /** @test */
    public function users_cannot_delete_other_users_tasks()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->actingAs($user, 'api')->deleteJson('/api/user/tasks/'.$task->id);

        $response->assertStatus(403)
                 ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function admins_can_view_all_tasks()
    {
        $admin = Admin::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/tasks');

        $response->assertStatus(200)
                 ->assertJsonStructure([['id', 'title', 'status']]);
    }

    /** @test */
    public function admins_can_update_any_task()
    {
        $admin = Admin::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($admin, 'api')->putJson('/api/admin/tasks/'.$task->id, ['title' => 'Updated by Admin']);

        $response->assertStatus(200)
                 ->assertJson(['title' => 'Updated by Admin']);
    }

    /** @test */
    public function admins_can_delete_any_task()
    {
        $admin = Admin::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($admin, 'api')->deleteJson('/api/admin/tasks/'.$task->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Task deleted successfully']);
    }
}
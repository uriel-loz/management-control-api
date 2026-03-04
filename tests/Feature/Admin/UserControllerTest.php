<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create(['name' => 'Admin']);
        $permission = Permission::factory()->create(['slug' => 'users.index']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/admin/users')
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'email', 'phone', 'type']
                ]
            ]);
    }

    public function test_user_without_permission_cannot_list_users(): void
    {
        $user = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create(['name' => 'Limited']);
        $user->update(['role_id' => $role->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/admin/users')
            ->assertStatus(403);
    }

    public function test_can_create_user(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['slug' => 'users.create']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $targetRole = Role::factory()->create(['name' => 'User Role']);

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => 'SecurePass123!@#',
            'role_id' => $targetRole->id,
        ];

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/admin/users', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_can_update_user(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['slug' => 'users.update']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $userToUpdate = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => $userToUpdate->email,
            'phone' => $userToUpdate->phone,
            'password' => 'NewSecurePass123!@#',
            'role_id' => $userToUpdate->role_id,
        ];

        $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/admin/users/{$userToUpdate->id}", $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Updated Name'
        ]);
    }

    public function test_can_delete_user(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['slug' => 'users.delete']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $userToDelete = User::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/admin/users/{$userToDelete->id}")
            ->assertStatus(200);

        $this->assertSoftDeleted('users', ['id' => $userToDelete->id]);
    }

    public function test_unauthenticated_user_cannot_access_users(): void
    {
        $this->getJson('/api/v1/admin/users')
            ->assertStatus(401);
    }
}

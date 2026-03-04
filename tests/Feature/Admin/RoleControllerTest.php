<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_admin_can_list_roles(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create(['name' => 'Admin']);
        $permission = Permission::factory()->create(['slug' => 'roles.index']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/admin/roles')
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'name']
                ]
            ]);
    }

    public function test_can_create_role(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['slug' => 'roles.create']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $data = [
            'role' => 'New Role',
        ];

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/admin/roles', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('roles', ['name' => 'New Role']);
    }

    public function test_can_update_role_permissions(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['slug' => 'roles.update']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $roleToUpdate = Role::factory()->create(['name' => 'Test Role']);
        $newPermission = Permission::factory()->create();

        $data = [
            'modules' => [$newPermission->id],
        ];

        $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/admin/roles/{$roleToUpdate->id}", $data)
            ->assertStatus(200);

        $this->assertTrue($roleToUpdate->fresh()->permissions->contains($newPermission->id));
    }

    public function test_can_delete_role(): void
    {
        $admin = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['slug' => 'roles.delete']);
        $role->permissions()->attach($permission->id);
        $admin->update(['role_id' => $role->id]);

        $roleToDelete = Role::factory()->create(['name' => 'Delete Me']);

        $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/admin/roles/{$roleToDelete->id}")
            ->assertStatus(200);

        $this->assertSoftDeleted('roles', ['id' => $roleToDelete->id]);
    }

    public function test_user_without_permission_cannot_create_role(): void
    {
        $user = User::factory()->create(['is_customer' => false]);
        $role = Role::factory()->create(['name' => 'Limited']);
        $user->update(['role_id' => $role->id]);

        $data = [
            'role' => 'Unauthorized Role',
        ];

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/admin/roles', $data)
            ->assertStatus(403);
    }
}

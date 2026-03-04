<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RoleService;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RoleService $roleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->roleService = new RoleService();
    }

    public function test_can_get_all_roles(): void
    {
        Role::factory()->count(3)->create();

        $roles = $this->roleService->showAll();

        $this->assertCount(3, $roles);
    }

    public function test_can_create_role(): void
    {
        $data = [
            'role' => 'Test Role',
        ];

        $role = $this->roleService->createOrUpdateRole($data);

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
        $this->assertEquals('Test Role', $role->name);
    }

    public function test_can_update_role_permissions(): void
    {
        $role = Role::factory()->create(['name' => 'Test Role']);
        $permission1 = Permission::factory()->create();
        $permission2 = Permission::factory()->create();

        $data = [
            'id' => $role->id,
            'modules' => [$permission1->id, $permission2->id],
        ];

        $updatedRole = $this->roleService->createOrUpdateRole($data);

        $this->assertCount(2, $updatedRole->permissions);
        $this->assertTrue($updatedRole->permissions->contains($permission1->id));
        $this->assertTrue($updatedRole->permissions->contains($permission2->id));
    }

    public function test_can_sync_role_permissions(): void
    {
        $role = Role::factory()->create();
        $permission1 = Permission::factory()->create();
        $permission2 = Permission::factory()->create();
        $permission3 = Permission::factory()->create();

        // First attach permissions 1 and 2
        $role->permissions()->attach([$permission1->id, $permission2->id]);

        // Then sync with permission 2 and 3
        $data = [
            'id' => $role->id,
            'modules' => [$permission2->id, $permission3->id],
        ];

        $this->roleService->createOrUpdateRole($data);

        $role->refresh();
        $this->assertCount(2, $role->permissions);
        $this->assertFalse($role->permissions->contains($permission1->id));
        $this->assertTrue($role->permissions->contains($permission2->id));
        $this->assertTrue($role->permissions->contains($permission3->id));
    }

    public function test_can_delete_role(): void
    {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();
        $role->permissions()->attach($permission->id);

        $this->roleService->deleteRole($role->id);

        $this->assertSoftDeleted('roles', ['id' => $role->id]);
        $this->assertEquals(0, $role->fresh()->permissions()->count());
    }

    public function test_delete_nonexistent_role_throws_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->roleService->deleteRole('non-existent-id');
    }

    public function test_update_nonexistent_role_throws_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $data = [
            'id' => 'non-existent-id',
            'modules' => [],
        ];

        $this->roleService->createOrUpdateRole($data);
    }

    public function test_shows_all_roles_with_permissions_count(): void
    {
        $role = Role::factory()->create();
        Permission::factory()->count(3)->create()->each(function($permission) use ($role) {
            $role->permissions()->attach($permission->id);
        });

        $roles = $this->roleService->showAll();

        $this->assertNotNull($roles->first()->permissions);
    }
}

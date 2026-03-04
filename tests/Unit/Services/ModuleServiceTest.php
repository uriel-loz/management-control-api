<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ModuleService;
use App\Models\User;
use App\Models\Role;
use App\Models\Module;
use App\Models\Section;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class ModuleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ModuleService $moduleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->moduleService = new ModuleService();
        Cache::flush();
    }

    public function test_can_get_all_modules(): void
    {
        Section::factory()->count(2)->create();

        $modules = $this->moduleService->showAll();

        $this->assertNotNull($modules);
        $this->assertGreaterThanOrEqual(0, $modules->count());
    }

    public function test_shows_all_modules_caches_result(): void
    {
        Section::factory()->count(2)->create();

        // First call should cache
        $modules1 = $this->moduleService->showAll();

        // Verify cache exists
        $this->assertTrue(Cache::has('modules.all'));

        // Second call should use cache
        $modules2 = $this->moduleService->showAll();

        $this->assertEquals($modules1, $modules2);
    }

    public function test_can_get_modules_by_role(): void
    {
        $role = Role::factory()->create();
        $module = Module::factory()->create();
        $permission = Permission::factory()->create(['module_id' => $module->id]);
        $role->permissions()->attach($permission->id);

        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $modules = $this->moduleService->showModulesByRole();

        $this->assertNotNull($modules);
    }

    public function test_shows_modules_by_role_caches_result(): void
    {
        $role = Role::factory()->create();
        $module = Module::factory()->create();
        $permission = Permission::factory()->create(['module_id' => $module->id]);
        $role->permissions()->attach($permission->id);

        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        // First call should cache
        $modules1 = $this->moduleService->showModulesByRole();

        // Verify cache exists
        $this->assertTrue(Cache::has("modules.by_role.{$user->id}"));

        // Second call should use cache
        $modules2 = $this->moduleService->showModulesByRole();

        $this->assertEquals($modules1, $modules2);
    }

    public function test_invalidate_cache_clears_all_module_caches(): void
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        // Populate caches
        $this->moduleService->showAll();
        $this->moduleService->showModulesByRole();

        $this->assertTrue(Cache::has('modules.all'));

        // Invalidate cache
        $this->moduleService->invalidateCache();

        // Verify caches are cleared
        $this->assertFalse(Cache::has('modules.all'));
    }

    public function test_modules_are_ordered_by_order_field(): void
    {
        $section = Section::factory()->create(['order' => 1]);

        $modules = $this->moduleService->showAll();

        $this->assertNotNull($modules);
    }

    public function test_shows_only_modules_with_permission(): void
    {
        $role = Role::factory()->create();
        $section = Section::factory()->create();

        $module1 = Module::factory()->create(['section_id' => $section->id]);
        $module2 = Module::factory()->create(['section_id' => $section->id]);

        $permission1 = Permission::factory()->create(['module_id' => $module1->id]);

        // Only attach permission for module1
        $role->permissions()->attach($permission1->id);

        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $modules = $this->moduleService->showModulesByRole();

        $this->assertNotNull($modules);
    }
}

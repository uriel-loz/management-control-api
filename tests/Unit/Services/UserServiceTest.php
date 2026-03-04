<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\UserService;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->userService = new UserService();
    }

    public function test_can_get_all_users(): void
    {
        $role = Role::factory()->create();
        User::factory()->count(3)->create(['role_id' => $role->id]);

        $users = $this->userService->showAll();

        $this->assertCount(3, $users);
    }

    public function test_can_create_user(): void
    {
        $role = Role::factory()->create();

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'password' => 'SecurePass123!@#',
            'role_id' => $role->id,
        ];

        $this->userService->createOrUpdateUser($data);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_password_is_hashed_when_creating_user(): void
    {
        $role = Role::factory()->create();

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'password' => 'SecurePass123!@#',
            'role_id' => $role->id,
        ];

        $this->userService->createOrUpdateUser($data);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('SecurePass123!@#', $user->password));
        $this->assertNotEquals('SecurePass123!@#', $user->password);
    }

    public function test_can_update_user(): void
    {
        $role = Role::factory()->create();
        $user = User::factory()->create([
            'name' => 'Original Name',
            'role_id' => $role->id,
        ]);

        $data = [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => $user->email,
            'phone' => $user->phone,
            'password' => 'NewSecurePass123!@#',
            'role_id' => $user->role_id,
        ];

        $this->userService->createOrUpdateUser($data);

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
    }

    public function test_can_delete_user(): void
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->userService->deleteUser($user->id);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_shows_all_users_with_pagination(): void
    {
        $role = Role::factory()->create();
        User::factory()->count(15)->create(['role_id' => $role->id]);

        $users = $this->userService->showAll();

        $this->assertNotNull($users);
        $this->assertGreaterThan(0, $users->count());
    }
}

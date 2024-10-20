<?php

namespace Modules\Permissions\tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Permissions\App\Models\Permissions;
use Modules\Permissions\App\Models\Role;
use Modules\User\Database\Factories\Models\UserFactory;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        // Create an admin user and generate a JWT token with admin role and permissions
        $this->adminUser = UserFactory::new()->create();
        $admin_role = Role::create(['name' => 'admin']);
        foreach (Permissions::adminPermissions() as $perm) {
            Permissions::firstOrCreate(['name' => $perm]);
        }
        $admin_role->syncPermissions(Permissions::adminPermissions());
        $this->adminUser->assignRole($admin_role->name);
        $this->token = JWTAuth::fromUser($this->adminUser);
    }

    protected function actingAsUser($user)
    {
        $this->actingAs($user, 'api'); // Assuming 'api' guard is used
        $this->token = JWTAuth::fromUser($user);
    }

    /** @test */
    public function it_can_create_a_role()
    {
        $this->actingAsUser($this->adminUser); // Act as the admin user

        $response = $this->postJson('/api/roles', [
            'name' => 'employee'
        ], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', ['name' => 'employee']); // Corrected the assertion to check for 'employee'
    }

    /** @test */
    public function it_can_update_a_role()
    {
        $role = Role::create(['name' => 'user']);

        $this->actingAsUser($this->adminUser); // Act as the admin user

        $response = $this->putJson("/api/roles/{$role->id}", [
            'name' => 'updated-user'
        ], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', ['name' => 'updated-user']);
    }

    /** @test */
    public function it_can_delete_a_role()
    {
        $role = Role::create(['name' => 'user']);

        $this->actingAsUser($this->adminUser); // Act as the admin user

        $response = $this->deleteJson("/api/roles/{$role->id}", [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', ['name' => 'user']);
    }

    /** @test */
    public function it_can_assign_role_to_user()
    {
        $this->actingAsUser($this->adminUser); // Act as the admin user

        $role = Role::create(['name' => 'user']);
        $user = UserFactory::new()->create();
        $user->assignRole($role->name);
        $this->assertTrue($user->fresh()->hasRole($role->name));
    }

    /** @test */
    public function it_can_list_all_roles()
    {
        $this->actingAsUser($this->adminUser); // Act as the admin user

        Role::create(['name' => 'sales']);
        Role::create(['name' => 'editor']);

        $response = $this->getJson('/api/roles', [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
    }
}

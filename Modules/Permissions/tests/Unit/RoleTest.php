<?php

namespace Modules\Permissions\tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Permissions\App\Models\Permissions;
use Modules\Permissions\App\Models\Role;
use Modules\User\Database\Factories\Models\UserFactory;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $token;


    public function setUp(): void
    {
        parent::setUp();

        // Create a user and generate a JWT token and take role admin with all permissions
        $this->user = UserFactory::new()->create();
        $admin_role = Role::create(['name' => 'admin']);
        foreach (Permissions::adminPermissions() as $key => $perm) {
			Permissions::firstOrCreate(['name' => $perm]);
		}
        $admin_role->syncPermissions(Permissions::adminPermissions());
        $this->user->assignRole($admin_role->name);
        $this->token = JWTAuth::fromUser($this->user);
    }

    /** @test */
    public function it_can_create_a_role()
    {
        $response = $this->postJson('/api/roles', [
            'name' => 'employee'
        ], [
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
    }

    /** @test */
    public function it_can_update_a_role()
    {
        $role = Role::create(['name' => 'user']);

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

        $response = $this->deleteJson("/api/roles/{$role->id}", [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', ['name' => 'user']);
    }

    /** @test */
    public function it_can_assign_role_to_user()
    {
        $role = Role::create(['name' => 'user']);
        $user = UserFactory::new()->create();
        $user->assignRole($role->name);
        $this->assertTrue($user->fresh()->hasRole($role->name));
    }

    /** @test */
    public function it_can_list_all_roles()
    {
        Role::create(['name' => 'sales']);
        Role::create(['name' => 'editor']);

        $response = $this->getJson('/api/roles', [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
    }
}

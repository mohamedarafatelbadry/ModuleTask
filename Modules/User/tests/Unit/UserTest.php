<?php

namespace Modules\User\tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Permissions\App\Models\Permissions;
use Modules\Permissions\App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Modules\User\Database\Factories\Models\UserFactory;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        // Create a user and generate a JWT token and assign admin role with permissions
        $this->user = UserFactory::new()->create();
        $admin_role = Role::create(['name' => 'admin']);
        // Assuming you have a method to get all admin permissions
        foreach (Permissions::adminPermissions() as $perm) {
            Permissions::firstOrCreate(['name' => $perm]);
        }
        $admin_role->syncPermissions(Permissions::adminPermissions());
        $this->user->assignRole($admin_role->name);
        $this->token = JWTAuth::fromUser($this->user);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = UserFactory::new()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated User'
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'Updated User']);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = UserFactory::new()->create();

        $response = $this->deleteJson("/api/users/{$user->id}", [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_restore_a_soft_deleted_user()
    {
        $user = UserFactory::new()->create();
        $user->delete();

        $response = $this->postJson("/api/users/restore/{$user->id}", [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /** @test */
    public function it_can_force_delete_a_user()
    {
        $user = UserFactory::new()->create();
        $user->delete();

        $response = $this->deleteJson("/api/users/force-delete/{$user->id}", [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_list_all_users()
    {
        UserFactory::new()->create(['name' => 'User One']);
        UserFactory::new()->create(['name' => 'User Two']);

        $response = $this->getJson('/api/users', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
    }
}

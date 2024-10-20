<?php

namespace Modules\User\tests\Pest;

use Tests\Pest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Permissions\App\Models\Permissions;
use Modules\Permissions\App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Modules\User\Database\Factories\Models\UserFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create an admin user and generate a JWT token
    $this->adminUser = UserFactory::new()->create();
    $admin_role = Role::create(['name' => 'admin']);
    foreach (Permissions::adminPermissions() as $perm) {
        Permissions::firstOrCreate(['name' => $perm]);
    }
    $admin_role->syncPermissions(Permissions::adminPermissions());
    $this->adminUser->assignRole($admin_role->name);
    $this->token = JWTAuth::fromUser($this->adminUser);
});

it('can create a user')->test(function () {
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
});

it('can update a user')->test(function () {
    $user = UserFactory::new()->create();

    $response = $this->putJson("/api/users/{$user->id}", [
        'name' => 'Updated User'
    ], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['name' => 'Updated User']);
});

it('can delete a user')->test(function () {
    $user = UserFactory::new()->create();

    $response = $this->deleteJson("/api/users/{$user->id}", [], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(200);
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});

it('can restore a soft deleted user')->test(function () {
    $user = UserFactory::new()->create();
    $user->delete();

    $response = $this->postJson("/api/users/restore/{$user->id}", [], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['email' => $user->email]);
});

it('can force delete a user')->test(function () {
    $user = UserFactory::new()->create();
    $user->delete();

    $response = $this->deleteJson("/api/users/force-delete/{$user->id}", [], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('can list all users')->test(function () {
    UserFactory::new()->create(['name' => 'User One']);
    UserFactory::new()->create(['name' => 'User Two']);

    $response = $this->getJson('/api/users', [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(200);
});

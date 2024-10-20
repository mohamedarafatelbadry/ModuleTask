<?php

namespace Modules\Permissions\tests\Pest;

use Tests\Pest;
use Modules\Permissions\App\Models\Permissions;
use Modules\Permissions\App\Models\Role;
use Modules\User\Database\Factories\Models\UserFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create an admin user and generate a JWT token with admin role and permissions
    $this->adminUser = UserFactory::new()->create();
    $adminRole = Role::create(['name' => 'admin']);
    foreach (Permissions::adminPermissions() as $perm) {
        Permissions::firstOrCreate(['name' => $perm]);
    }
    $adminRole->syncPermissions(Permissions::adminPermissions());
    $this->adminUser->assignRole($adminRole->name);
    $this->token = JWTAuth::fromUser($this->adminUser);
});


it('can create a role', function () {


    $response = $this->postJson('/api/roles', [
        'name' => 'employee'
    ], [
        'Authorization' => 'Bearer ' . $this->token
    ]);

    $response->actingAs($this->adminUser); // Act as the admin user

    $response->assertStatus(200);
    $this->assertDatabaseHas('roles', ['name' => 'employee']);
});

it('can update a role', function () {
    $role = Role::create(['name' => 'user']);

    $response = $this->putJson("/api/roles/{$role->id}", [
        'name' => 'updated-user'
    ], [
        'Authorization' => 'Bearer ' . $this->token
    ]);

    $response->actingAs($this->adminUser); // Act as the admin user

    $response->assertStatus(200);
    $this->assertDatabaseHas('roles', ['name' => 'updated-user']);
});

it('can delete a role', function () {
    $role = Role::create(['name' => 'user']);

    $response = $this->deleteJson("/api/roles/{$role->id}", [], [
        'Authorization' => 'Bearer ' . $this->token
    ]);

    $response->actingAs($this->adminUser); // Act as the admin user

    $response->assertStatus(200);
    $this->assertDatabaseMissing('roles', ['name' => 'user']);
});

it('can assign role to user', function () {
    $role = Role::create(['name' => 'user']);
    $user = UserFactory::new()->create();
    $user->assignRole($role->name);
    expect($user->fresh()->hasRole($role->name))->toBeTrue();
});

it('can list all roles', function () {
    Role::create(['name' => 'sales']);
    Role::create(['name' => 'editor']);

    $response = $this->getJson('/api/roles', [
        'Authorization' => 'Bearer ' . $this->token
    ]);
    $response->actingAs($this->adminUser); // Act as the admin user

    $response->assertStatus(200);
});

<?php

namespace Modules\User\tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Modules\User\Database\Factories\Models\UserFactory;

class UserTest extends TestCase
{

    // test for cruds operations only without authintication or authorization
    use RefreshDatabase; // Use RefreshDatabase to reset the database for each test

    /** @test create a user*/
    public function it_can_create_a_user()
    {
        $user = UserFactory::new()->create(); // Use the factory to create a user

        $this->assertDatabaseHas('users', [
            'email' => $user->email, // Assert that the user exists in the database
        ]);
    }

    /** @test read user*/
    public function it_can_read_a_user()
    {
        $user = UserFactory::new()->create(); // Use the factory to create a user

        $foundUser = User::find($user->id); // Find the user by ID

        $this->assertEquals($user->name, $foundUser->name); // Assert that the names match
    }

    /** @test update user */
    public function it_can_update_a_user()
    {
        $user = UserFactory::new()->create(); // Use the factory to create a user

        $user->update(['name' => 'Updated User']); // Update the user's name

        $this->assertDatabaseHas('users', [
            'name' => 'Updated User', // Assert that the updated name exists in the database
        ]);
    }

    /** @test soft delete user*/
    public function it_can_soft_delete_a_user()
    {
        $user = UserFactory::new()->create(); // Use the factory to create a user

        $user->delete(); // Soft delete the user

        $this->assertSoftDeleted($user); // Assert that the user is soft deleted
    }

    /** @test restore user*/
    public function it_can_restore_a_soft_deleted_user()
    {
        $user = UserFactory::new()->create(); // Use the factory to create a user

        $user->delete(); // Soft delete the user
        $user->restore(); // Restore the soft deleted user

        $this->assertDatabaseHas('users', [
            'email' => $user->email, // Assert that the user still exists in the database
        ]);
    }

    /** @test force delete user*/
    public function it_can_force_delete_a_user()
    {
        $user = UserFactory::new()->create(); // Use the factory to create a user

        $user->delete(); // Soft delete the user
        $user->forceDelete(); // Force delete the user

        $this->assertDatabaseMissing('users', ['id' => $user->id]);// Assert that the user is permanently deleted
    }
}

<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\App\Models\User;
use Modules\User\Database\Factories\Models\UserFactory;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(['name' => 'admin', 'email' => 'admin@admin.com', 'password' => bcrypt(123456789) ,'email_verified_at' => now()]);
        $admin->assignRole('admin');

        UserFactory::times(10)->create();
        // $this->call([]);
    }
}

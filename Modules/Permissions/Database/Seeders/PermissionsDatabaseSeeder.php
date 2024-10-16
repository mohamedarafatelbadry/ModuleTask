<?php

namespace Modules\Permissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permissions\App\Models\Permissions;
use Modules\Permissions\App\Models\Role;

class PermissionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles_array = ['admin', 'user'];
		foreach (Permissions::adminPermissions() as $key => $perm) {
			Permissions::firstOrCreate(['name' => $perm]);
		}
		foreach ($roles_array as $role) {
			$roles = Role::firstOrCreate(['name' => $role]);

			if ($roles->name == 'admin') {
				// assign all permissions
				$roles->syncPermissions(Permissions::adminPermissions());
			}

            	$this->command->info('Admin granted all the permissions');
		}
    }
}

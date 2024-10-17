<?php

namespace Modules\Permissions\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Permissions extends \Spatie\Permission\Models\Permission
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'guard_name'];

	public static function adminPermissions() {
		return [

			'view_users',
			'add_users',
			'edit_users',
			'delete_users',
            'restore_users',
            'forceDelete_users',

            'view_permissions',

			'view_roles',
			'add_roles',
			'edit_roles',
			'delete_roles',

		];
	}

}

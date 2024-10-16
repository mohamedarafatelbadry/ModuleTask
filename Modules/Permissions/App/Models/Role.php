<?php
namespace  Modules\Permissions\App\Models;

class Role extends \Spatie\Permission\Models\Role {
	protected $fillable = ['name', 'guard_name'];
}

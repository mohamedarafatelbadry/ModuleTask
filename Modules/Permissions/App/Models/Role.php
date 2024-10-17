<?php
namespace  Modules\Permissions\App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Role extends \Spatie\Permission\Models\Role
{
    use HasUuids;
	protected $fillable = ['name', 'guard_name'];
}

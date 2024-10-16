<?php

namespace Modules\Permissions\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\App\Models\User;

class PermissionsPolicy
{
    use HandlesAuthorization;

    protected $user;

    // Constructor to inject the user
    public function __construct()
    {
        $this->user = User::find(auth()->user()->id);
    }

	public function viewAny() {
        return $this->user->can('view_permissions');
	}

	public function view() {
        return $this->user->can('view_permissions');
	}
}

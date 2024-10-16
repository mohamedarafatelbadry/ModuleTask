<?php

namespace Modules\Permissions\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\App\Models\User;

class RolesPolicy
{
    use HandlesAuthorization;

    protected $user;

    // Constructor to inject the user
    public function __construct()
    {
        $this->user = User::find(auth()->user()->id);
    }

	public function viewAny() {
		return $this->user->can('view_roles');
	}

	public function view() {
        return $this->user->can('view_roles');
	}

	public function create() {
		return $this->user->can('add_roles');
	}

	public function show() {
		return $this->user->can('view_roles');
	}

	public function update() {
		return $this->user->can('edit_roles');
	}

	public function delete() {
		return $this->user->can('delete_roles');
	}

}

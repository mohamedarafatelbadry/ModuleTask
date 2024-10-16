<?php

namespace Modules\User\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;
    protected $user;

    // Constructor to inject the user
    public function __construct()
    {
        $this->user = User::find(auth()->user()->id);
    }

    // Check if the user can view any users
    public function viewAny() {
        return $this->user->can('view_users');
    }

    // Check if the user can view a specific user
    public function view() {
        return $this->user->can('view_users');
    }

    // Check if the user can create a user
    public function create() {
        return $this->user->can('add_users');
    }

    // Check if the user can show a specific user
    public function show() {
        return $this->user->can('view_users');
    }

    // Check if the user can update a specific user
    public function update() {
        return $this->user->can('edit_users');
    }

    // Check if the user can delete a specific user
    public function delete() {
        return $this->user->can('delete_users');
    }

    // Check if the user can force delete a specific user
    public function forceDelete() {
        return $this->user->can('forceDelete_users');
    }

    // Check if the user can restore a specific user
    public function restore() {
        return $this->user->can('restore_users');
    }
}

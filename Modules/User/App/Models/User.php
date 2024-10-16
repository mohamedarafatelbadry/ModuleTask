<?php

namespace Modules\User\App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable implements JWTSubject {
    use HasFactory, Notifiable, HasRoles , HasUuids , SoftDeletes;

    protected $dates = ['deleted_at'];
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
        'name' => 'string',
        'email' => 'string',
		'email_verified_at' => 'datetime',
        'password' => 'hashed',
	];


	public function isAdmin() {
		foreach ($this->roles()->get() as $role) {
			if ($role->name == 'admin') {
				return true;
			}
		}
		return false;
	}

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}

<?php
namespace Modules\User\App\Http\Controllers\Api;

class UserController extends \Lynx\Base\Api
{
    protected $entity = \Modules\User\App\Models\User::class;
    protected $resourcesJson = \Modules\User\App\resources\UserResource::class;
    protected $policy = \Modules\User\App\Policies\UserPolicy::class;
    protected $guard = 'api';
    protected $spatieQueryBuilder = true;
    protected $paginateIndex = true;
    protected $withTrashed = true;
    protected $FullJsonInStore = false;  // TRUE,FALSE
    protected $FullJsonInUpdate = false;  // TRUE,FALSE
    protected $FullJsonInDestroy = false;  // TRUE,FALSE

    /**
     * can handel custom query when retrive data on index,indexGuest
     * @param $entity model
     * @return query by Model , Entity
     */
    public function query($entity): Object
    {
        return $entity->withTrashed();
    }

    /**
     * this method append data when store or update data
     * @return array
     */
    public function append(): array
    {
        $data = [
            'password' => bcrypt(request('password')),
        ];
        return $data;
    }

    /**
     * @param $id integer if you want to use in update rules
     * @param string $type (store,update)
     * @return array by (store,update) type using $type variable
     */
    public function rules(string $type, mixed $id = null): array
    {
        return $type == 'store' ?
        [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ] :
        [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:6|confirmed',
        ];
    }

    /**
     * this method can set your attribute names with validation rules
     * @return array
     */
    public function niceName()
    {
        return [];
    }

    /*
     * this method use or append or change data before store
     * @return array
     */
    public function beforeStore(array $data): array
    {
        // $data['title'] = 'replace data';
        return $data;
    }

    /**
     * this method can use or append store data
     * @return array
     */
    public function afterStore($entity): void
    {
        if (request('role')) {
			$entity->assignRole(request('role'));
		}
    }

    /**
     * this method use or append or delete data beforeUpdate
     * @return array
     */
    public function beforeUpdate($entity): void
    {
        // if (!empty($data->file)) {
        //     \Storage::delete($data->file);
        // }
    }

    /**
     * this method use or append data after Update
     * @return array
     */
    public function afterUpdate($entity): void
    {
        if (request('role')) {
			($entity->roles->first()) ? $entity->removeRole($entity->roles->first()) : '';
			$entity->assignRole(request('role'));
		}
    }

    /**
     * this method use or append data when Show data
     * @return array
     */
    public function beforeShow($entity): Object
    {
        return $entity->where('name', '!=', null);
    }

    /**
     * this method use or append data when Show data
     * @return array
     */
    public function afterShow($entity): Object
    {
        return new \Modules\User\App\resources\UserResource($entity);
    }

    /**
     * you can do something in this method before delete record
     * @param object $entity
     * @return void
     */
    public function beforeDestroy($entity): void
    {
        // if (!empty($entity->file)) {
        //     \Storage::delete($entity->file);
        // }
    }

    /**
     * you can do something in this method after delete record
     * @param object $entity
     * @return void
     */
    public function afterDestroy($entity): void
    {
        // do something
        // $entity->file
    }
}

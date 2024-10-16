<?php
namespace Modules\Permissions\App\Http\Controllers\Api;

class RolesController extends \Lynx\Base\Api
{
    protected $entity = \Modules\Permissions\App\Models\Role::class;
    protected $resourcesJson = \Modules\Permissions\App\resources\RolesResource::class;
    protected $policy = \Modules\Permissions\App\Policies\RolesPolicy::class;
    protected $guard = 'api';
    protected $spatieQueryBuilder = true;
    protected $paginateIndex = true;
    protected $withTrashed = false;
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
        return $entity->orderBy('id', 'asc');
    }

    /**
     * this method append data when store or update data
     * @return array
     */
    public function append(): array
    {
        // $data = [
        //     'user_id' => auth('api')->id(),
        // ];
        // $file = lynx()->uploadFile('file', 'test');
        // if (!empty($file)) {
        //     $data['file'] = $file;
        // }
        // return $data;
        return [];
    }

    /**
     * @param $id integer if you want to use in update rules
     * @param string $type (store,update)
     * @return array by (store,update) type using $type variable
     */
    public function rules(string $type, mixed $id = null): array
    {
        return $type == 'store' ? [
            'name' => 'required|string|unique:roles,name',
        ] : [
            'name' => 'sometimes|string|unique:roles,name,' . $id,
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
        $this->syncPermissions($entity);
    }

    /**
     * this method use or append or delete data beforeUpdate
     * @return array
     */
    public function beforeUpdate($entity): void
    {

    }

    /**
     * this method use or append data after Update
     * @return array
     */
    public function afterUpdate($entity): void
    {
        $this->syncPermissions($entity);
    }

    public function syncPermissions($entity): void
    {
        $permissions = request()->get('permissions', []);
		$entity->syncPermissions($permissions);
     }
    /**
     * this method use or append data when Show data
     * @return array
     */
    public function beforeShow($entity): Object
    {
        return $entity->where('title', '=', null);
    }

    /**
     * this method use or append data when Show data
     * @return array
     */
    public function afterShow($entity): Object
    {
        return new \Modules\Permissions\App\resources\RolesResource($entity);
    }

    /**
     * you can do something in this method before delete record
     * @param object $entity
     * @return void
     */
    public function beforeDestroy($entity): void
    {

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

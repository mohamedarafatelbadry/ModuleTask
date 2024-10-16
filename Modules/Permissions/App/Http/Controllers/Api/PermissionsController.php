<?php
namespace Modules\Permissions\App\Http\Controllers\Api;

class PermissionsController extends \Lynx\Base\Api
{
    protected $entity = \Modules\Permissions\App\Models\Permissions::class;
    protected $resourcesJson = \Modules\Permissions\App\resources\PermissionsResource::class;
    protected $policy = \Modules\Permissions\App\Policies\PermissionsPolicy::class;
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
        return [];
    }

    /**
     * @param $id integer if you want to use in update rules
     * @param string $type (store,update)
     * @return array by (store,update) type using $type variable
     */
    public function rules(string $type, mixed $id = null): array
    {
        return $type == 'store' ? [] : [];
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
        return $data;
    }

    /**
     * this method can use or append store data
     * @return array
     */
    public function afterStore($entity): void
    {

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
        return new \Modules\Permissions\App\resources\PermissionsResource($entity);
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

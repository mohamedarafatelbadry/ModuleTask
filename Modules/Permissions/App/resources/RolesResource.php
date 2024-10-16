<?php

namespace Modules\Permissions\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
       return [
            'name' => $this->name,
            'permissions' => PermissionsResource::collection($this->permissions),
        ];
    }
}

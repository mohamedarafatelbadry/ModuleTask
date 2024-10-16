<?php

namespace Modules\Permissions\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name
        ];
    }
}

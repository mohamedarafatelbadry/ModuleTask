<?php

namespace Modules\User\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'role'       => $this->roles()->first()->name ?? 'No Role',
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
        ];
    }
}

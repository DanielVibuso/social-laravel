<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dataResource =
        [
            'id' => $this->id,
            'name' => $this->name,
            'profile' => $this->profile?->name,
            'profile_id' => $this->profile_id,
            'email' => $this->email,
            'permissions' => $this->getPermissions(),
        ];

        return $dataResource;
    }
}

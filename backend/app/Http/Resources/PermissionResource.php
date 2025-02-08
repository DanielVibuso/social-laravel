<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $arr = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];

        if ($this->is_associated) {
            $arr = array_merge($arr, ['is_associated' => $this->is_associated]);
        }

        return $arr;
    }
}

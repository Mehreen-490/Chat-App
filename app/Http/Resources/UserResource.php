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
        return [
            "id" => data_get($this, 'id'),
            "name" => data_get($this, 'name'),
            "email" => data_get($this, 'email'),
            "is_active" => data_get($this, 'is_active'),
            "profile" => data_get($this, 'profile'),
            "created_at" => data_get($this, 'created_at'),
            "updated_at" => data_get($this, 'updated_at'),




        ];
    }
}

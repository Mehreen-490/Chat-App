<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            "creator" => new UserResource($this->creator),
            "updated_at" => data_get($this, 'updated_at'),
            "created_at" => data_get($this, 'updated_at'),
        ];
    }
}

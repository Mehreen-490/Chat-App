<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attachment = Str::after(data_get($this, 'attachment'), 'message_attachments/');
        return [
            "id" => data_get($this, 'id'),
            "message" => data_get($this, 'message'),
            "attachment" => $attachment,
            "sender_id" => data_get($this, 'sender_id'),
            "channel_id" => data_get($this, 'channel_id'),
            "company_id" => data_get($this, 'company_id'),
            "updated_at" => data_get($this, 'updated_at'),
            "created_at" => data_get($this, 'created_at'),
        ];
    }
}

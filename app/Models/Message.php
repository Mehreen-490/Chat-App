<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'message',
        'attachment',
        'channel_id',
        'company_id',
        'sender_id',
    ];


    public static function store($request, $attachmentPath)
    {
        return Message::create([
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'channel_id' => $request->channel_id,
            'company_id' => $request->company_id,
            'sender_id' => $request->sender_id,
        ]);
    }
}

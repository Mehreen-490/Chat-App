<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'name',
        'type',
        'creator_id',
        'company_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'channel_user')->withTimestamps();
    }


    public static function  store($user, $request)
    {
        return self::create([
            'name' => $request->name,
            'type' => $request->type,
            'creator_id' => $user->id,
            'company_id' => $request->company_id,
        ]);
    }

    public static function edit($channel, $newName)
    {
        $channel->update([
            'name' => $newName
        ]);
        return $channel;
    }
}

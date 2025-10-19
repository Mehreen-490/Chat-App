<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Token extends Model
{
    protected $fillable = [
        'token',
        'token_type',
        'user_id'
    ];

    public static function store($user, $token_type)
    {
        $token = Token::create([
            'user_id' => data_get($user, 'id'),
            'token' => 'token-' . Str::random(50) . time(),
            'token_type' => $token_type,
        ]);

        return $token;
    }

    public static function remove($user, $token_type)
    {
        Token::where('user_id', data_get($user, 'id'))
            ->where('token_type', $token_type)
            ->delete();
    }
}

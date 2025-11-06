<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'creator_id')->withTrashed();
    }
    public function channels()
    {
        return $this->belongsToMany(Channel::class);
    }

    public static function store($data)
    {
        $user = new User();
        $user->name =  $data->name;
        $user->email =  $data->email;
        $user->password =  $data->password;
        $user->save();

        return $user;
    }
    public static function updateName($user, $newName)
    {
        $user->update([
            'name' => $newName
        ]);
        return $user;
    }
    public static function resetPassword($user, $password)
    {
        $user->update([
            'password' => $password
        ]);
        return $user;
    }

    public static function edit($request, $profile)
    {
        $user = $request->user();

        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($profile) {
            $data['profile'] = $profile;
        }
        if (!empty($data)) {
            $user->update($data);
        }
        return $user;
    }
}

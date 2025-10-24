<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'creator_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public static function store($user, $compnay_name)
    {
        return self::create([
            'name' => $compnay_name,
            'creator_id' => $user->id
        ]);
    }

    public static function updateCompanyName($company, $newName)
    {
        $company->update([
            'name' => $newName
        ]);

        return $company;
    }



    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')->withTimestamps();
    }
}

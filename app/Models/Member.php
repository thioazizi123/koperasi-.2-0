<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'date_of_birth',
        'gender',
        'occupation',
        'address',
        'join_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
    ];

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function financings()
    {
        return $this->hasMany(Financing::class);
    }

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'nik',
        'date_of_birth',
        'gender',
        'occupation',
        'address',
        'join_date',
        'member_no',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($member) {
            $member->member_no = sprintf('%03d/IKAB/%s/%s', $member->id, $member->join_date->format('m'), $member->join_date->format('Y'));
            $member->save();
        });
    }

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

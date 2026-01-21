<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financing extends Model
{
    protected $fillable = [
        'member_id',
        'type',
        'amount',
        'duration',
        'status',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}

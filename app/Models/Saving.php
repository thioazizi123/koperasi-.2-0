<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    protected $fillable = [
        'member_id',
        'type',
        'amount',
        'transaction_date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

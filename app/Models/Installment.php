<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = [
        'financing_id',
        'installment_number',
        'amount',
        'due_date',
        'is_paid',
        'paid_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public function financing()
    {
        return $this->belongsTo(Financing::class);
    }
}

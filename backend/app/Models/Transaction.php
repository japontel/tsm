<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'account_number_from',
        'account_number_type_from',
        'account_number_to',
        'account_number_type_to',
        'trace_number',
        'amount',
        'type',
        'description',
        'reference',
        'creation_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'creation_date' => 'datetime',
    ];
}

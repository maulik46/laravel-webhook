<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount',
        'currency',
        'status',
        'metadata',
    ];
}

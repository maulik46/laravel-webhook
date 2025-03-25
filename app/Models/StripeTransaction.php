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

    /**
     * Stripe validation rules
    */
    public static function stripeRules(): array
    {
        return [
            'type' => 'required|string',
            'data.object.id' => 'required|string',
            'data.object.amount' => 'required|numeric',
            'data.object.currency' => 'required|string',
        ];
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class StripeTransactionRepository
{
    public function create(array $data)
    {
        return DB::table('stripe_transactions')->insert($data);
    }
}
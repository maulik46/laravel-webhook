<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class WebhookLogRepository
{
    public function create(array $data)
    {
        return DB::table('webhook_logs')->insert($data);
    }
}

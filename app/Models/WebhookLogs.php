<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLogs extends Model
{
    protected $fillable = [
        'source',
        'event_type',
        'payload',
        'status',
        'error_message',
    ];
}

<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/webhook', [WebhookController::class, 'handle']);
});

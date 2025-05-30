<?php

use Illuminate\Support\Facades\Route;
use Quotes\Controllers\QuoteController;

Route::prefix('api/quotes')->group(function () {
    Route::get('/', [QuoteController::class, 'all']);
    Route::get('/random', [QuoteController::class, 'random']);
    Route::get('/{id}', [QuoteController::class, 'show']);
});

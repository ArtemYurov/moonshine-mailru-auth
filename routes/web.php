<?php

declare(strict_types=1);

use ArtemYurov\MailRuAuth\Http\Controllers\MailRuController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->controller(MailRuController::class)
    ->prefix('mailru')
    ->name('mailru.')
    ->group(function (): void {
        Route::post('/auth', 'auth')->name('auth');
        Route::get('/callback', 'callback')->name('callback');
    });

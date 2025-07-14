<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\OrderController;


// Rotas protegidas via JWT
Route::middleware('auth:api')->group(function () {
   Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/me', 'me');
            Route::post('/logout', 'logout');
        });
    });
     Route::prefix('travellings')->group(function () {
        Route::controller(TravelController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::delete('/{id}','destroy');

        });
    });

    Route::prefix('order') ->group(function () {
         Route::controller(OrderController::class)->group(function () {
            Route::get('','index');
            Route::put('change','changeStatus');
            Route::delete('clear','clear');
         });
    });
});

Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        // Rotas públicas
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/check','checkToken');
        Route::post('/recovery','recovery');
        Route::put('/change-password','changePassword');
    });
});




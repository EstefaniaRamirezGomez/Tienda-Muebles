<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

Route::get('/productos', [ProductApiController::class, 'index']);
Route::get('/productos/{producto}', [ProductApiController::class, 'show']);

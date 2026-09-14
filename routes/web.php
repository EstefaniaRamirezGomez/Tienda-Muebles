<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\ProductoController;

Route::prefix('productos')->name('productos.')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name('index');
    Route::get('/buscar', [ProductoController::class, 'buscar'])->name('buscar');
    Route::get('/destacados', [ProductoController::class, 'destacados'])->name('destacados');
    Route::get('/comparar', [ProductoController::class, 'comparar'])->name('comparar');
    Route::get('/{producto}', [ProductoController::class, 'show'])->name('show');
});

Route::prefix('ambientes')->name('ambientes.')->group(function () {
    Route::get('/', [AmbienteController::class, 'index'])->name('index');
    Route::get('/{ambiente}', [AmbienteController::class, 'productos'])->name('productos');
});

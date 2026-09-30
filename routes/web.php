<?php

use App\Http\Controllers\Admin\AdminInicioController;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

// Página principal
Route::get('/', [InicioController::class, 'index'])->name('inicio');

// Productos
Route::prefix('productos')->name('productos.')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name('index');
    Route::get('/buscar', [ProductoController::class, 'buscar'])->name('buscar');
    Route::get('/destacados', [ProductoController::class, 'destacados'])->name('destacados');
    Route::get('/comparar', [ProductoController::class, 'comparar'])->name('comparar');
    Route::get('/{producto}', [ProductoController::class, 'show'])->name('show');
});

// Ambientes
Route::prefix('ambientes')->name('ambientes.')->group(function () {
    Route::get('/', [AmbienteController::class, 'index'])->name('index');
    Route::get('/{ambiente}', [AmbienteController::class, 'productos'])->name('productos');
});

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'mostrarFormulario'])->name('login');
    Route::post('/login', [LoginController::class, 'iniciarSesion']);
    Route::get('/registro', [RegisterController::class, 'mostrarFormulario'])->name('register');
    Route::post('/registro', [RegisterController::class, 'registrar']);
});

Route::post('/logout', [LoginController::class, 'cerrarSesion'])
    ->middleware('auth')
    ->name('logout');

// Sección administrador: solo usuarios con rol admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminInicioController::class, 'index'])->name('inicio');
});

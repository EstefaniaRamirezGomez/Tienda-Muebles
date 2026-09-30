<?php

use App\Http\Controllers\Admin\AdminInicioController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarritoController;

// Página principal
Route::get('/', [InicioController::class, 'index'])
    ->name('inicio');

// ===============================
// PRODUCTOS - USUARIO
// ===============================
Route::prefix('productos')
    ->name('productos.')
    ->group(function () {

        Route::get('/', [ProductoController::class, 'index'])
            ->name('index');

        Route::get('/buscar', [ProductoController::class, 'buscar'])
            ->name('buscar');

        Route::get('/destacados', [ProductoController::class, 'destacados'])
            ->name('destacados');

        Route::get('/comparar', [ProductoController::class, 'comparar'])
            ->name('comparar');

        Route::get('/{producto}', [ProductoController::class, 'show'])
            ->name('show');
    });

// ===============================
// AMBIENTES
// ===============================
Route::prefix('ambientes')
    ->name('ambientes.')
    ->group(function () {

        Route::get('/', [AmbienteController::class, 'index'])
            ->name('index');

        Route::get('/{ambiente}', [AmbienteController::class, 'productos'])
            ->name('productos');
    });

// ===============================
// AUTENTICACIÓN
// ===============================
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'mostrarFormulario'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'iniciarSesion']);

    Route::get('/registro', [RegisterController::class, 'mostrarFormulario'])
        ->name('register');

    Route::post('/registro', [RegisterController::class, 'registrar']);
});

// Cerrar sesión
Route::post('/logout', [LoginController::class, 'cerrarSesion'])
    ->middleware('auth')
    ->name('logout');

// ===============================
// CARRITO
// ===============================
Route::middleware('auth')
    ->prefix('carrito')
    ->name('carrito.')
    ->group(function () {

        // Ver carrito
        Route::get('/', [CarritoController::class, 'index'])
            ->name('index');

        // Agregar producto al carrito
        Route::post('/agregar/{producto}', [CarritoController::class, 'agregar'])
            ->name('agregar');

        // Actualizar cantidad de un producto
        Route::put('/actualizar/{producto}', [CarritoController::class, 'actualizar'])
            ->name('actualizar');

        // Eliminar un producto del carrito
        Route::delete('/eliminar/{producto}', [CarritoController::class, 'eliminar'])
            ->name('eliminar');

        // Vaciar carrito completo
        Route::delete('/vaciar', [CarritoController::class, 'vaciar'])
            ->name('vaciar');
    });

// ===============================
// ADMINISTRADOR
// ===============================
// Solo pueden entrar usuarios autenticados con rol admin
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Panel principal del administrador
        Route::get('/', [AdminInicioController::class, 'index'])
            ->name('inicio');

        // ===============================
        // PRODUCTOS - ADMINISTRADOR
        // ===============================

        // Listar productos
        Route::get('/productos', [AdminProductoController::class, 'index'])
            ->name('productos.index');

        // Formulario para crear producto
        Route::get('/productos/crear', [AdminProductoController::class, 'create'])
            ->name('productos.create');

        // Guardar producto nuevo
        Route::post('/productos', [AdminProductoController::class, 'store'])
            ->name('productos.store');

        // Formulario para editar producto
        Route::get('/productos/{producto}/editar', [AdminProductoController::class, 'edit'])
            ->name('productos.edit');

        // Guardar cambios del producto
        Route::put('/productos/{producto}', [AdminProductoController::class, 'update'])
            ->name('productos.update');

        // Eliminar producto
        Route::delete('/productos/{producto}', [AdminProductoController::class, 'destroy'])
            ->name('productos.destroy');
    });
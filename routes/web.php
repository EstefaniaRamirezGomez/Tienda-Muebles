<?php

use App\Http\Controllers\Admin\AdminInicioController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\Admin\PedidoController as AdminPedidoController;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoAliadoController;
use App\Http\Controllers\ReporteProductoController;
use Illuminate\Support\Facades\Route;

// ===============================
// PÁGINA PRINCIPAL
// ===============================

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
// PRODUCTOS ALIADOS (servicio del equipo anterior)
// ===============================

Route::get('/productos-aliados', [ProductoAliadoController::class, 'index'])
    ->name('aliados.index');

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

        // Actualizar cantidad
        Route::put('/actualizar/{producto}', [CarritoController::class, 'actualizar'])
            ->name('actualizar');

        // Eliminar producto
        Route::delete('/eliminar/{producto}', [CarritoController::class, 'eliminar'])
            ->name('eliminar');

        // Vaciar carrito
        Route::delete('/vaciar', [CarritoController::class, 'vaciar'])
            ->name('vaciar');
    });

// ===============================
// PEDIDOS - CLIENTE
// ===============================

Route::middleware('auth')
    ->prefix('pedidos')
    ->name('pedidos.')
    ->group(function () {

        // Historial de pedidos
        Route::get('/', [PedidoController::class, 'index'])
            ->name('index');

        // Formulario para finalizar compra
        Route::get('/crear', [PedidoController::class, 'create'])
            ->name('create');

        // Confirmar compra simulada
        Route::post('/', [PedidoController::class, 'store'])
            ->name('store');

        // Consultar detalles y seguimiento
        Route::get('/{pedido}', [PedidoController::class, 'show'])
            ->name('show');
    });

// ===============================
// ADMINISTRADOR
// ===============================

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminInicioController::class, 'index'])
            ->name('inicio');

        // ===============================
        // PRODUCTOS
        // ===============================

        Route::get('/productos', [AdminProductoController::class, 'index'])
            ->name('productos.index');

        Route::get('/productos/crear', [AdminProductoController::class, 'create'])
            ->name('productos.create');

        Route::post('/productos', [AdminProductoController::class, 'store'])
            ->name('productos.store');

        Route::get('/productos/{producto}/editar', [AdminProductoController::class, 'edit'])
            ->name('productos.edit');

        Route::put('/productos/{producto}', [AdminProductoController::class, 'update'])
            ->name('productos.update');

        Route::delete('/productos/{producto}', [AdminProductoController::class, 'destroy'])
            ->name('productos.destroy');

        // ===============================
        // PEDIDOS - ADMINISTRADOR
        // ===============================

        // Ver todos los pedidos
        Route::get('/pedidos', [AdminPedidoController::class, 'index'])
            ->name('pedidos.index');

        // Ver detalle de un pedido
        Route::get('/pedidos/{pedido}', [AdminPedidoController::class, 'show'])
            ->name('pedidos.show');

        // Actualizar estado del envío
        Route::patch('/pedidos/{pedido}/estado', [
            AdminPedidoController::class,
            'actualizarEstado'
        ])->name('pedidos.estado');
    });

// ===============================
// REPORTES DE INVENTARIO
// ===============================

Route::get('/admin/reportes/productos', [
    ReporteProductoController::class,
    'descargar'
])
    ->middleware(['auth', 'admin'])
    ->name('admin.reportes.productos');

// ===============================
// CAMBIO DE IDIOMA
// ===============================

Route::get('/idioma/{idioma}', function (string $idioma) {

    abort_unless(in_array($idioma, ['es', 'en'], true), 404);

    session(['idioma' => $idioma]);

    return redirect()->back();

})->name('idioma.cambiar');

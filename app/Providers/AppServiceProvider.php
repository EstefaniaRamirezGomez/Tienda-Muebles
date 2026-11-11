<?php

namespace App\Providers;

use App\Contracts\ReporteProductoInterface;
use App\Models\Producto;
use App\Observers\ProductoObserver;
use App\Services\ReporteProductosCsv;
use App\Services\ReporteProductosJson;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar servicios y dependencias.
     */
    public function register(): void
    {
        // Inversión de dependencias para los reportes:
        // el controlador solo conoce la interfaz y aquí se decide
        // qué clase concreta usar según el formato pedido (?formato=json|csv).
        $this->app->bind(ReporteProductoInterface::class, function ($app) {
            return $app['request']->query('formato') === 'json'
                ? $app->make(ReporteProductosJson::class)
                : $app->make(ReporteProductosCsv::class);
        });
    }

    /**
     * Inicializar servicios de la aplicación.
     */
    public function boot(): void
    {
        // Registrar el Observer que traduce
        // automáticamente los productos al guardarlos.
        Producto::observe(ProductoObserver::class);
    }
}

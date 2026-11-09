<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\ReporteProductoInterface;
use App\Services\ReporteProductosCsv;

use App\Models\Producto;
use App\Observers\ProductoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar servicios y dependencias.
     */
    public function register(): void
    {
        // Mantener la inyección de dependencias
        // para la generación de reportes.
        $this->app->bind(
            ReporteProductoInterface::class,
            ReporteProductosCsv::class
        );
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

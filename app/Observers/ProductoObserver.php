<?php

namespace App\Observers;

use App\Models\Producto;
use App\Services\TraduccionService;

class ProductoObserver
{
    public function __construct(
        private TraduccionService $traductor
    ) {
    }

    /**
     * Se ejecuta después de crear o actualizar
     * un producto mediante Eloquent.
     */
    public function saved(Producto $producto): void
    {
        $cambios = [];

        // Traducir el nombre si es nuevo o cambió.
        if (
            $producto->wasRecentlyCreated ||
            $producto->wasChanged('nombre')
        ) {
            $nombreTraducido = $this->traductor->traducir(
                $producto->nombre,
                'es',
                'en'
            );

            // No sobrescribir con null si falla la API.
            if ($nombreTraducido !== null) {
                $cambios['nombre_en'] = $nombreTraducido;
            }
        }

        // Traducir la descripción si es nueva o cambió.
        if (
            $producto->wasRecentlyCreated ||
            $producto->wasChanged('descripcion')
        ) {
            $descripcionTraducida = $this->traductor->traducir(
                $producto->descripcion,
                'es',
                'en'
            );

            if ($descripcionTraducida !== null) {
                $cambios['descripcion_en'] = $descripcionTraducida;
            }
        }

        if (!empty($cambios)) {
            // Evita volver a disparar el Observer.
            $producto->updateQuietly($cambios);
        }
    }
}

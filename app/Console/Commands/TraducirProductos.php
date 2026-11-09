<?php

namespace App\Console\Commands;

use App\Models\Producto;
use App\Services\TraduccionService;
use Illuminate\Console\Command;

class TraducirProductos extends Command
{
    protected $signature = 'productos:traducir';

    protected $description =
        'Traduce los productos existentes al inglés';

    public function handle(
        TraduccionService $traductor
    ): int {
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $cambios = [];

            if (empty($producto->nombre_en)) {
                $nombre = $traductor->traducir(
                    $producto->nombre
                );

                if ($nombre !== null) {
                    $cambios['nombre_en'] = $nombre;
                }
            }

            if (
                !empty($producto->descripcion) &&
                empty($producto->descripcion_en)
            ) {
                $descripcion = $traductor->traducir(
                    $producto->descripcion
                );

                if ($descripcion !== null) {
                    $cambios['descripcion_en'] =
                        $descripcion;
                }
            }

            if (!empty($cambios)) {
                $producto->updateQuietly($cambios);

                $this->info(
                    "Producto {$producto->id}: traducido"
                );
            } else {
                $this->line(
                    "Producto {$producto->id}: sin cambios"
                );
            }
        }

        $this->info('Proceso finalizado.');

        return self::SUCCESS;
    }
}


<?php

namespace App\Services;

use App\Contracts\ReporteProductoInterface;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ReporteProductosCsv implements ReporteProductoInterface
{
    public function generar(Collection $productos): Response
    {
        $archivo = fopen('php://temp', 'w+');

        if ($archivo === false) {
            throw new \RuntimeException(
                'No se pudo generar el reporte CSV.'
            );
        }

        try {
            // Encabezados del archivo CSV.
            fputcsv($archivo, [
                'ID',
                'Nombre',
                'Precio',
                'Stock',
                'Categoria',
            ]);

            foreach ($productos as $producto) {
                fputcsv($archivo, [
                    $producto->id,
                    $producto->nombre,
                    $producto->precio,
                    $producto->stock,
                    $producto->categoria?->nombre ?? '',
                ]);
            }

            rewind($archivo);

            $contenido = stream_get_contents($archivo);

            if ($contenido === false) {
                throw new \RuntimeException(
                    'No se pudo leer el reporte CSV.'
                );
            }
        } finally {
            fclose($archivo);
        }

        return response($contenido, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' =>
                'attachment; filename="reporte_productos.csv"',
        ]);
    }
}

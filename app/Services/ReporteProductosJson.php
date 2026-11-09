<?php

namespace App\Services;

use App\Contracts\ReporteProductoInterface;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ReporteProductosJson implements ReporteProductoInterface
{
    public function generar(Collection $productos): Response
    {
        $datos = $productos->map(function ($producto) {
            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'stock' => $producto->stock,
                'categoria' =>
                    $producto->categoria?->nombre,
            ];
        })->values()->all();

        return response()->json([
            'total' => count($datos),
            'productos' => $datos,
        ], 200, [
            'Content-Disposition' =>
                'attachment; filename="reporte_productos.json"',
        ]);
    }
}

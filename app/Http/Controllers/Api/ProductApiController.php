<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    public function index(): JsonResponse
    {
        $productos = Producto::with(['categoria', 'ambiente'])
            ->where('stock', '>', 0)
            ->get();

        return response()->json([
            'total' => $productos->count(),
            'productos' => $productos->map(fn (Producto $producto) => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'stock' => $producto->stock,
                'categoria' => $producto->categoria?->nombre,
                'ambiente' => $producto->ambiente?->nombre,
                'url' => route('productos.show', $producto->id),
            ]),
        ]);
    }

    public function show(Producto $producto): JsonResponse
    {
        $producto->load(['categoria', 'ambiente']);

        return response()->json([
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'precio' => $producto->precio,
            'stock' => $producto->stock,
            'categoria' => $producto->categoria?->nombre,
            'ambiente' => $producto->ambiente?->nombre,
            'url' => route('productos.show', $producto->id),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with(['categoria', 'ambiente'])
            ->orderBy('nombre')
            ->get();

        return view('productos.index', compact('productos'));
    }

    public function show(Producto $producto): View
    {
        $producto->load(['categoria', 'ambiente', 'especificacion', 'resenas.usuario']);

        return view('productos.show', compact('producto'));
    }

    public function buscar(Request $request): View
    {
        $termino = $request->query('q', '');

        $productos = Producto::with(['categoria', 'ambiente'])
            ->when($termino !== '', fn ($query) => $query->where('nombre', 'like', "%{$termino}%"))
            ->orderBy('nombre')
            ->get();

        return view('productos.index', [
            'productos' => $productos,
            'termino' => $termino,
        ]);
    }

    public function destacados(): View
    {
        $masVendidos = DetallePedido::selectRaw('producto_id, SUM(cantidad) as unidades_vendidas')
            ->groupBy('producto_id')
            ->orderByDesc('unidades_vendidas')
            ->with('producto')
            ->take(5)
            ->get();

        $mejorCalificados = Producto::withAvg('resenas', 'calificacion')
            ->whereHas('resenas')
            ->orderByDesc('resenas_avg_calificacion')
            ->take(5)
            ->get();

        return view('productos.destacados', compact('masVendidos', 'mejorCalificados'));
    }

   public function comparar(Request $request): View
{
    $crudo = $request->query('ids', []);

    $ids = is_array($crudo)
        ? collect($crudo)
        : collect(explode(',', (string) $crudo));

    $ids = $ids->filter()->map(fn ($id) => (int) $id)->unique()->take(3);

    $productos = Producto::with(['especificacion', 'categoria'])
        ->whereIn('id', $ids)
        ->get();

    $totalSolicitados = $productos->count();

    // Solo se compara dentro de la misma categoría (un sofá contra una
    // lámpara no aporta nada). Se toma la categoría del primer producto
    // y se descarta cualquier otro que no coincida.
    if ($productos->isNotEmpty()) {
        $categoriaId = $productos->first()->categoria_id;
        $productos = $productos->filter(fn ($p) => $p->categoria_id === $categoriaId)->values();
    }

    $huboDescartados = $productos->count() < $totalSolicitados;

    return view('productos.comparar', compact('productos', 'huboDescartados'));
}
}
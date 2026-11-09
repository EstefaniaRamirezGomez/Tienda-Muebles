<?php

namespace App\Services;

use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ProductoService
{
    /**
     * Mostrar catálogo paginado.
     */
    public function listar(int $porPagina = 9): LengthAwarePaginator
    {
        return Producto::with(['categoria', 'ambiente'])
            ->orderBy('nombre')
            ->paginate($porPagina);
    }

    /**
     * Buscar productos por nombre.
     */
    public function buscar(
        string $termino,
        int $porPagina = 9
    ): LengthAwarePaginator {
        return Producto::with(['categoria', 'ambiente'])
            ->when(
                $termino !== '',
                fn ($query) => $query->where(
                    'nombre',
                    'like',
                    "%{$termino}%"
                )
            )
            ->orderBy('nombre')
            ->paginate($porPagina)
            ->withQueryString();
    }

    /**
     * Obtener información detallada de un producto.
     */
    public function obtenerDetalle(Producto $producto): Producto
    {
        return $producto->load([
            'categoria',
            'ambiente',
            'especificacion',
            'resenas.usuario',
        ]);
    }

    /**
     * Obtener los productos más vendidos.
     */
    public function masVendidos(int $limite = 5): EloquentCollection
    {
        return DetallePedido::selectRaw(
                'producto_id, SUM(cantidad) as unidades_vendidas'
            )
            ->groupBy('producto_id')
            ->orderByDesc('unidades_vendidas')
            ->with('producto')
            ->take($limite)
            ->get();
    }

    /**
     * Obtener los productos mejor calificados.
     */
    public function mejorCalificados(int $limite = 5): EloquentCollection
    {
        return Producto::withAvg('resenas', 'calificacion')
            ->whereHas('resenas')
            ->orderByDesc('resenas_avg_calificacion')
            ->take($limite)
            ->get();
    }

    /**
     * Comparar entre 2 y 3 productos.
     *
     * Se permiten productos de diferentes categorías.
     * Se cargan sus especificaciones técnicas y se
     * conserva el orden en que fueron seleccionados.
     */
    public function comparar(array $ids): array
    {
        // Validar IDs y eliminar duplicados.
        $idsValidos = collect($ids)
            ->filter(fn ($id) => filter_var(
                $id,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            ) !== false)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->take(3)
            ->values();

        // Consultar los productos seleccionados.
        $productos = Producto::with([
                'especificacion',
                'categoria',
                'ambiente',
            ])
            ->whereIn('id', $idsValidos->all())
            ->get();

        // Mantener el orden original de selección.
        $productosPorId = $productos->keyBy('id');

        $productosOrdenados = $idsValidos
            ->map(fn ($id) => $productosPorId->get($id))
            ->filter()
            ->values();

        // Detectar IDs que no correspondan a productos existentes.
        $huboDescartados =
            $productosOrdenados->count() < $idsValidos->count();

        return [
            'productos' => $productosOrdenados,
            'huboDescartados' => $huboDescartados,
        ];
    }
}

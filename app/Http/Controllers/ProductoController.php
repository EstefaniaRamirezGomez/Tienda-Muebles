<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function __construct(
        private readonly ProductoService $productoService
    ) {
    }

    /**
     * Mostrar catálogo paginado.
     */
    public function index(): View
    {
        $productos = $this->productoService->listar();

        return view('productos.index', compact('productos'));
    }

    /**
     * Mostrar información detallada de un producto.
     */
    public function show(Producto $producto): View
    {
        $producto = $this->productoService->obtenerDetalle($producto);

        return view('productos.show', compact('producto'));
    }

    /**
     * Buscar productos por nombre.
     */
    public function buscar(Request $request): View
    {
        $termino = trim((string) $request->query('q', ''));

        $productos = $this->productoService->buscar($termino);

        return view('productos.index', [
            'productos' => $productos,
            'termino' => $termino,
        ]);
    }

    /**
     * Mostrar productos destacados.
     */
    public function destacados(): View
    {
        $masVendidos = $this->productoService->masVendidos();

        $mejorCalificados = $this->productoService->mejorCalificados();

        return view('productos.destacados', compact(
            'masVendidos',
            'mejorCalificados'
        ));
    }

    /**
     * Comparar hasta tres productos de la misma categoría.
     */
    public function comparar(Request $request): View
    {
        $crudo = $request->query('ids', []);

        $ids = is_array($crudo)
            ? $crudo
            : explode(',', (string) $crudo);

        $resultado = $this->productoService->comparar($ids);

        return view('productos.comparar', [
            'productos' => $resultado['productos'],
            'huboDescartados' => $resultado['huboDescartados'],
        ]);
    }
}

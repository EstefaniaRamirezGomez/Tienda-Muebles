<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ambiente;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    // Mostrar todos los productos en el panel administrador
    public function index(): View
    {
        $productos = Producto::with(['categoria', 'ambiente'])
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.index', compact('productos'));
    }

    // Mostrar formulario para crear producto
    public function create(): View
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $ambientes = Ambiente::orderBy('nombre')->get();

        return view('admin.productos.create', compact(
            'categorias',
            'ambientes'
        ));
    }

    // Guardar producto nuevo
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|url|max:1000',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'ambiente_id' => 'required|exists:ambientes,id',
        ]);

        Producto::create($datos);

        return redirect()
            ->route('admin.productos.index')
            ->with('exito', 'Producto creado correctamente.');
    }

    // Mostrar formulario para editar producto
    public function edit(Producto $producto): View
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $ambientes = Ambiente::orderBy('nombre')->get();

        return view('admin.productos.edit', compact(
            'producto',
            'categorias',
            'ambientes'
        ));
    }

    // Actualizar producto
    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|url|max:1000',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'ambiente_id' => 'required|exists:ambientes,id',
        ]);

        $producto->update($datos);

        return redirect()
            ->route('admin.productos.index')
            ->with('exito', 'Producto actualizado correctamente.');
    }

    // Eliminar producto
    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()
            ->route('admin.productos.index')
            ->with('exito', 'Producto eliminado correctamente.');
    }
}
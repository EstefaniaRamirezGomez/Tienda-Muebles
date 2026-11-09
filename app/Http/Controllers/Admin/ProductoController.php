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

    // Reglas de validación para crear y actualizar
    private function reglasValidacion(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|url|max:1000',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'ambiente_id' => 'required|exists:ambientes,id',
        ];
    }

    // Nombres de los campos según el idioma
    private function atributosValidacion(): array
    {
        $ingles = app()->getLocale() === 'en';

        return $ingles
            ? [
                'nombre' => 'product name',
                'descripcion' => 'description',
                'imagen' => 'image URL',
                'precio' => 'price',
                'stock' => 'stock',
                'categoria_id' => 'category',
                'ambiente_id' => 'room',
            ]
            : [
                'nombre' => 'nombre del producto',
                'descripcion' => 'descripción',
                'imagen' => 'URL de la imagen',
                'precio' => 'precio',
                'stock' => 'stock',
                'categoria_id' => 'categoría',
                'ambiente_id' => 'ambiente',
            ];
    }

    // Mensajes personalizados de validación
    private function mensajesValidacion(): array
    {
        $ingles = app()->getLocale() === 'en';

        if ($ingles) {
            return [
                'required' => 'The :attribute field is required.',
                'string' => 'The :attribute must be a text value.',
                'numeric' => 'The :attribute must be a number.',
                'integer' => 'The :attribute must be an integer.',
                'url' => 'The :attribute must be a valid URL.',
                'min.numeric' => 'The :attribute must be at least :min.',
                'min.integer' => 'The :attribute must be at least :min.',
                'max.string' => 'The :attribute may not exceed :max characters.',
                'exists' => 'The selected :attribute is invalid.',
            ];
        }

        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser un texto.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'url' => 'El campo :attribute debe contener una URL válida.',
            'min.numeric' => 'El campo :attribute debe ser como mínimo :min.',
            'min.integer' => 'El campo :attribute debe ser como mínimo :min.',
            'max.string' => 'El campo :attribute no puede superar los :max caracteres.',
            'exists' => 'El valor seleccionado para :attribute no es válido.',
        ];
    }

    // Guardar producto nuevo
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate(
            $this->reglasValidacion(),
            $this->mensajesValidacion(),
            $this->atributosValidacion()
        );

        Producto::create($datos);

        return redirect()
            ->route('admin.productos.index')
            ->with(
                'exito',
                app()->getLocale() === 'en'
                    ? 'Product created successfully.'
                    : 'Producto creado correctamente.'
            );
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
    public function update(
        Request $request,
        Producto $producto
    ): RedirectResponse {
        $datos = $request->validate(
            $this->reglasValidacion(),
            $this->mensajesValidacion(),
            $this->atributosValidacion()
        );

        $producto->update($datos);

        return redirect()
            ->route('admin.productos.index')
            ->with(
                'exito',
                app()->getLocale() === 'en'
                    ? 'Product updated successfully.'
                    : 'Producto actualizado correctamente.'
            );
    }

    // Eliminar producto
    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()
            ->route('admin.productos.index')
            ->with(
                'exito',
                app()->getLocale() === 'en'
                    ? 'Product deleted successfully.'
                    : 'Producto eliminado correctamente.'
            );
    }
}

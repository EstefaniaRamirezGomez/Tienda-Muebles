<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarritoController extends Controller
{
    /**
     * Mostrar el carrito.
     */
    public function index(): View
    {
        $carrito = session()->get('carrito', []);

        $total = collect($carrito)->sum(function ($item) {
            return $item['precio'] * $item['cantidad'];
        });

        return view('carrito.index', compact('carrito', 'total'));
    }

    /**
     * Agregar un producto al carrito.
     */
    public function agregar(
        Request $request,
        Producto $producto
    ): RedirectResponse {

        // Validar cantidad
        $request->validate([
            'cantidad' => [
                'required',
                'integer',
                'min:1',
                'max:' . $producto->stock,
            ],
        ]);

        // Obtener carrito actual de la sesión
        $carrito = session()->get('carrito', []);

        // Cantidad que el usuario quiere agregar
        $cantidad = (int) $request->cantidad;

        // Si el producto ya existe en el carrito
        if (isset($carrito[$producto->id])) {

            $nuevaCantidad =
                $carrito[$producto->id]['cantidad'] + $cantidad;

            // No permitir superar el stock
            if ($nuevaCantidad > $producto->stock) {
                return back()->with(
                    'error',
                    __('tienda.ctrl_carrito.not_enough_stock_available')
                );
            }

            $carrito[$producto->id]['cantidad'] = $nuevaCantidad;

        } else {

            // Agregar producto nuevo
            $carrito[$producto->id] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => (float) $producto->precio,
                'imagen' => $producto->imagen,
                'cantidad' => $cantidad,
                'stock' => $producto->stock,
            ];
        }

        // Guardar carrito en sesión
        session()->put('carrito', $carrito);

        return redirect()
            ->route('carrito.index')
            ->with(
                'exito',
                __('tienda.ctrl_carrito.product_added_to_cart_successfully')
            );
    }

    /**
     * Actualizar la cantidad de un producto.
     */
    public function actualizar(
        Request $request,
        Producto $producto
    ): RedirectResponse {

        $request->validate([
            'cantidad' => [
                'required',
                'integer',
                'min:1',
                'max:' . $producto->stock,
            ],
        ]);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$producto->id])) {

            $carrito[$producto->id]['cantidad'] =
                (int) $request->cantidad;

            session()->put('carrito', $carrito);
        }

        return redirect()
            ->route('carrito.index')
            ->with(
                'exito',
                __('tienda.ctrl_carrito.quantity_updated_successfully')
            );
    }

    /**
     * Eliminar un producto del carrito.
     */
    public function eliminar(Producto $producto): RedirectResponse
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$producto->id])) {

            unset($carrito[$producto->id]);

            session()->put('carrito', $carrito);
        }

        return redirect()
            ->route('carrito.index')
            ->with(
                'exito',
                __('tienda.ctrl_carrito.product_removed_from_cart')
            );
    }

    /**
     * Vaciar todo el carrito.
     */
    public function vaciar(): RedirectResponse
    {
        session()->forget('carrito');

        return redirect()
            ->route('carrito.index')
            ->with(
                'exito',
                __('tienda.ctrl_carrito.shopping_cart_emptied_successfully')
            );
    }
}

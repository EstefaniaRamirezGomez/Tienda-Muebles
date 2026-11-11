<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PedidoController extends Controller
{
    // Historial de pedidos del usuario autenticado
    public function index(): View
    {
        $pedidos = Pedido::with('detalles.producto')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    // Mostrar formulario de confirmación de compra
    public function create(Request $request): View|RedirectResponse
    {
        $carrito = $request->session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()
                ->route('carrito.index')
                ->with(
                    'error',
                    __('tienda.ctrl_pedido.your_cart_is_empty')
                );
        }

        $ids = array_keys($carrito);

        $productos = Producto::whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($carrito as $id => $item) {
            $producto = $productos->get($id);

            if (!$producto) {
                continue;
            }

            $cantidad = (int) ($item['cantidad'] ?? 0);

            if ($cantidad <= 0) {
                continue;
            }

            $subtotal = round(
                $cantidad * (float) $producto->precio,
                2
            );

            $items[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        if (empty($items)) {
            return redirect()
                ->route('carrito.index')
                ->with(
                    'error',
                    __('tienda.ctrl_pedido.there_are_no_valid_products_to')
                );
        }

        return view('pedidos.create', compact('items', 'total'));
    }

    // Confirmar compra simulada
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate(
            [
                'nombre_receptor' => 'required|string|max:150',
                'direccion_envio' => 'required|string|max:255',
                'ciudad_envio' => 'required|string|max:100',
                'telefono_contacto' => 'required|string|max:30',
            ]
        );

        $carrito = $request->session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()
                ->route('carrito.index')
                ->with(
                    'error',
                    __('tienda.ctrl_pedido.your_cart_is_empty')
                );
        }

        // Toda la compra se procesa dentro de una transacción.
        // Si algo falla, no se guarda un pedido incompleto.
        $pedido = DB::transaction(function () use ($carrito, $datos) {

            $ids = array_keys($carrito);

            // Bloqueamos los productos durante la compra para
            // evitar que dos clientes compren el mismo stock.
            $productos = Producto::whereIn('id', $ids)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lineas = [];
            $total = 0;

            foreach ($carrito as $id => $item) {

                $producto = $productos->get($id);

                if (!$producto) {
                    throw ValidationException::withMessages([
                        'carrito' => __('tienda.ctrl_pedido.one_of_the_products_is_no'),
                    ]);
                }

                $cantidad = (int) ($item['cantidad'] ?? 0);

                if ($cantidad <= 0) {
                    throw ValidationException::withMessages([
                        'carrito' => __('tienda.ctrl_pedido.there_is_an_invalid_quantity_in'),
                    ]);
                }

                if ($producto->stock < $cantidad) {
                    throw ValidationException::withMessages([
                        'carrito' => __('tienda.ctrl_pedido.not_enough_stock_for', ['producto' => $producto->nombre_localizado]),
                    ]);
                }

                // El precio se toma de la base de datos,
                // no del carrito almacenado en la sesión.
                $precio = (float) $producto->precio;

                $subtotal = round($precio * $cantidad, 2);

                $lineas[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                ];

                $total += $subtotal;
            }

            if (empty($lineas)) {
                throw ValidationException::withMessages([
                    'carrito' => __('tienda.ctrl_pedido.there_are_no_products_to_confirm'),
                ]);
            }

            // Crear el pedido
            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'fecha' => now()->toDateString(),
                'estado' => 'pendiente',
                'total' => round($total, 2),
                'nombre_receptor' => $datos['nombre_receptor'],
                'direccion_envio' => $datos['direccion_envio'],
                'ciudad_envio' => $datos['ciudad_envio'],
                'telefono_contacto' => $datos['telefono_contacto'],
            ]);

            // Guardar detalles y descontar inventario
            foreach ($lineas as $linea) {

                $pedido->detalles()->create([
                    'producto_id' => $linea['producto']->id,
                    'cantidad' => $linea['cantidad'],
                    'precio_unitario' => $linea['precio'],
                ]);

                $linea['producto']->decrement(
                    'stock',
                    $linea['cantidad']
                );
            }

            return $pedido;
        }, 3);

        // Solo vaciar el carrito después de guardar la compra.
        $request->session()->forget('carrito');

        return redirect()
            ->route('pedidos.show', $pedido)
            ->with(
                'exito',
                __('tienda.ctrl_pedido.order_confirmed_successfully_this_is_a')
            );
    }

    // Mostrar detalles y seguimiento del pedido
    public function show(Pedido $pedido): View
    {
        // Impedir que un cliente consulte pedidos de otros.
        abort_unless(
            $pedido->user_id === auth()->id(),
            403
        );

        $pedido->load('detalles.producto');

        return view('pedidos.show', compact('pedido'));
    }

}

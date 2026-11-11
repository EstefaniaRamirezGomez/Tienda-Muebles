<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    /**
     * Mostrar todos los pedidos de la tienda.
     */
    public function index(): View
    {
        $pedidos = Pedido::with('usuario')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    /**
     * Mostrar los detalles de un pedido.
     */
    public function show(Pedido $pedido): View
    {
        $pedido->load([
            'usuario',
            'detalles.producto',
        ]);

        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Actualizar el estado del pedido.
     */
    public function actualizarEstado(
        Request $request,
        Pedido $pedido
    ): RedirectResponse {


        // Estados permitidos.
        $estadosPermitidos = [
            'pendiente',
            'preparando',
            'enviado',
            'entregado',
        ];

        // Validar el estado recibido.
        $datos = $request->validate(
            [
                'estado' => [
                    'required',
                    Rule::in($estadosPermitidos),
                ],
            ],
            [
                'estado.required' => __('tienda.ctrl_admin_pedido.please_select_an_order_status'),

                'estado.in' => __('tienda.ctrl_admin_pedido.the_selected_order_status_is_invalid'),
            ]
        );

        $estadoNuevo = $datos['estado'];

        // Evitar modificar pedidos cancelados.
        if ($pedido->estado === 'cancelado') {
            return back()->with(
                'error',
                __('tienda.ctrl_admin_pedido.cancelled_orders_cannot_be_modified')
            );
        }

        // Evitar modificar pedidos entregados.
        if ($pedido->estado === 'entregado') {
            return back()->with(
                'error',
                __('tienda.ctrl_admin_pedido.delivered_orders_cannot_be_modified')
            );
        }

        // Orden de avance del pedido.
        $ordenEstados = [
            'pendiente' => 0,
            'preparando' => 1,
            'enviado' => 2,
            'entregado' => 3,
        ];

        // Compatibilidad con el nombre alternativo.
        $estadoActual = $pedido->estado === 'en_preparacion'
            ? 'preparando'
            : $pedido->estado;

        // No permitir estados actuales desconocidos.
        if (!array_key_exists($estadoActual, $ordenEstados)) {
            return back()->with(
                'error',
                __('tienda.ctrl_admin_pedido.the_current_order_status_is_not')
            );
        }

        // No permitir retroceder en el seguimiento.
        if (
            $ordenEstados[$estadoNuevo] <
            $ordenEstados[$estadoActual]
        ) {
            return back()->with(
                'error',
                __('tienda.ctrl_admin_pedido.an_order_cannot_return_to_a')
            );
        }

        // No permitir saltar etapas del seguimiento.
        if (
            $ordenEstados[$estadoNuevo] >
            $ordenEstados[$estadoActual] + 1
        ) {
            return back()->with(
                'error',
                __('tienda.ctrl_admin_pedido.you_must_complete_the_previous_shipping')
            );
        }

        // Si ya tiene ese estado, no es necesario actualizar.
        if ($estadoActual === $estadoNuevo) {
            return back()->with(
                'exito',
                __('tienda.ctrl_admin_pedido.the_order_already_has_this_status')
            );
        }

        // Guardar el nuevo estado.
        $pedido->actualizarEstado($estadoNuevo);

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with(
                'exito',
                __('tienda.ctrl_admin_pedido.order_status_updated_successfully')
            );
    }
}

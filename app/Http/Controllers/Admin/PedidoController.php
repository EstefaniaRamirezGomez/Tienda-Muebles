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

        $ingles = app()->getLocale() === 'en';

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
                'estado.required' => $ingles
                    ? 'Please select an order status.'
                    : 'Debes seleccionar un estado para el pedido.',

                'estado.in' => $ingles
                    ? 'The selected order status is invalid.'
                    : 'El estado seleccionado no es válido.',
            ]
        );

        $estadoNuevo = $datos['estado'];

        // Evitar modificar pedidos cancelados.
        if ($pedido->estado === 'cancelado') {
            return back()->with(
                'error',
                $ingles
                    ? 'Cancelled orders cannot be modified.'
                    : 'No se pueden modificar pedidos cancelados.'
            );
        }

        // Evitar modificar pedidos entregados.
        if ($pedido->estado === 'entregado') {
            return back()->with(
                'error',
                $ingles
                    ? 'Delivered orders cannot be modified.'
                    : 'No se pueden modificar pedidos entregados.'
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
                $ingles
                    ? 'The current order status is not recognized.'
                    : 'El estado actual del pedido no es reconocido.'
            );
        }

        // No permitir retroceder en el seguimiento.
        if (
            $ordenEstados[$estadoNuevo] <
            $ordenEstados[$estadoActual]
        ) {
            return back()->with(
                'error',
                $ingles
                    ? 'An order cannot return to a previous status.'
                    : 'Un pedido no puede regresar a un estado anterior.'
            );
        }

        // No permitir saltar etapas del seguimiento.
        if (
            $ordenEstados[$estadoNuevo] >
            $ordenEstados[$estadoActual] + 1
        ) {
            return back()->with(
                'error',
                $ingles
                    ? 'You must complete the previous shipping stage first.'
                    : 'Debes completar primero la etapa anterior del envío.'
            );
        }

        // Si ya tiene ese estado, no es necesario actualizar.
        if ($estadoActual === $estadoNuevo) {
            return back()->with(
                'exito',
                $ingles
                    ? 'The order already has this status.'
                    : 'El pedido ya tiene este estado.'
            );
        }

        // Guardar el nuevo estado.
        $pedido->actualizarEstado($estadoNuevo);

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with(
                'exito',
                $ingles
                    ? 'Order status updated successfully.'
                    : 'Estado del pedido actualizado correctamente.'
            );
    }
}

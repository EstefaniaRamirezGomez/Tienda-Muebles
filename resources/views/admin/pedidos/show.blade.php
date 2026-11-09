@extends('layouts.admin')

@section('titulo', app()->getLocale() === 'en'
    ? 'Order Details'
    : 'Detalle del pedido'
)

@section('contenido')

@php
    $ingles = app()->getLocale() === 'en';

    $estados = [
        'pendiente' => [
            'es' => 'Pendiente',
            'en' => 'Pending',
            'color' => '#92400e',
            'fondo' => '#fef3c7',
        ],
        'preparando' => [
            'es' => 'En preparación',
            'en' => 'Preparing',
            'color' => '#1d4ed8',
            'fondo' => '#dbeafe',
        ],
        'en_preparacion' => [
            'es' => 'En preparación',
            'en' => 'Preparing',
            'color' => '#1d4ed8',
            'fondo' => '#dbeafe',
        ],
        'enviado' => [
            'es' => 'Enviado',
            'en' => 'Shipped',
            'color' => '#6d28d9',
            'fondo' => '#ede9fe',
        ],
        'entregado' => [
            'es' => 'Entregado',
            'en' => 'Delivered',
            'color' => '#166534',
            'fondo' => '#dcfce7',
        ],
        'cancelado' => [
            'es' => 'Cancelado',
            'en' => 'Cancelled',
            'color' => '#991b1b',
            'fondo' => '#fee2e2',
        ],
    ];

    $estado = $estados[$pedido->estado] ?? [
        'es' => ucfirst($pedido->estado),
        'en' => ucfirst($pedido->estado),
        'color' => '#374151',
        'fondo' => '#f3f4f6',
    ];

    $ordenEstados = [
        'pendiente' => 0,
        'preparando' => 1,
        'enviado' => 2,
        'entregado' => 3,
    ];

    $estadoNormalizado = $pedido->estado === 'en_preparacion'
        ? 'preparando'
        : $pedido->estado;

    $posicionActual = $ordenEstados[$estadoNormalizado] ?? null;

    $siguienteEstado = $posicionActual !== null
        && $posicionActual < 3
        ? array_search($posicionActual + 1, $ordenEstados, true)
        : null;
@endphp

<div style="max-width: 1100px; margin: 0 auto;">

    {{-- VOLVER AL LISTADO --}}
    <a
        href="{{ route('admin.pedidos.index') }}"
        style="
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #2563eb;
            text-decoration: none;
        "
    >
        {{ $ingles ? '← Back to Orders' : '← Volver a pedidos' }}
    </a>

    {{-- ENCABEZADO --}}
    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    ">

        <div>
            <h1 style="margin-bottom: 0.5rem;">
                {{ $ingles ? 'Order' : 'Pedido' }}
                #{{ $pedido->id }}
            </h1>

            <p style="color: #6b7280;">
                {{ $ingles ? 'Order date:' : 'Fecha del pedido:' }}
                {{ $pedido->fecha?->format('d/m/Y') ?? '-' }}
            </p>
        </div>

        <span style="
            display: inline-block;
            background: {{ $estado['fondo'] }};
            color: {{ $estado['color'] }};
            padding: 0.65rem 1rem;
            border-radius: 20px;
            font-weight: bold;
        ">
            {{ $estado[$ingles ? 'en' : 'es'] }}
        </span>

    </div>

    {{-- MENSAJES --}}
    @if (session('exito'))
        <div style="
            background: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        ">
            {{ session('exito') }}
        </div>
    @endif

    @if (session('error'))
        <div style="
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        ">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        ">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- INFORMACIÓN DEL CLIENTE --}}
    <section style="
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    ">

        <h2>
            {{ $ingles ? 'Customer Information' : 'Información del cliente' }}
        </h2>

        <p>
            <strong>{{ $ingles ? 'Name:' : 'Nombre:' }}</strong>
            {{ $pedido->usuario?->name ?? '-' }}
        </p>

        <p>
            <strong>{{ $ingles ? 'Email:' : 'Correo electrónico:' }}</strong>
            {{ $pedido->usuario?->email ?? '-' }}
        </p>

    </section>

    {{-- DATOS DE ENTREGA --}}
    <section style="
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    ">

        <h2>
            {{ $ingles ? 'Delivery Information' : 'Información de entrega' }}
        </h2>

        <p>
            <strong>{{ $ingles ? 'Recipient:' : 'Destinatario:' }}</strong>
            {{ $pedido->nombre_receptor ?? '-' }}
        </p>

        <p>
            <strong>{{ $ingles ? 'Address:' : 'Dirección:' }}</strong>
            {{ $pedido->direccion_envio ?? '-' }}
        </p>

        <p>
            <strong>{{ $ingles ? 'City:' : 'Ciudad:' }}</strong>
            {{ $pedido->ciudad_envio ?? '-' }}
        </p>

        <p>
            <strong>{{ $ingles ? 'Contact Phone:' : 'Teléfono de contacto:' }}</strong>
            {{ $pedido->telefono_contacto ?? '-' }}
        </p>

    </section>

    {{-- PRODUCTOS COMPRADOS --}}
    <section style="
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        overflow-x: auto;
    ">

        <h2>
            {{ $ingles ? 'Purchased Products' : 'Productos comprados' }}
        </h2>

        <table style="
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        ">

            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb;">

                    <th style="padding: 0.8rem; text-align: left;">
                        {{ $ingles ? 'Product' : 'Producto' }}
                    </th>

                    <th style="padding: 0.8rem; text-align: center;">
                        {{ $ingles ? 'Quantity' : 'Cantidad' }}
                    </th>

                    <th style="padding: 0.8rem; text-align: right;">
                        {{ $ingles ? 'Unit Price' : 'Precio unitario' }}
                    </th>

                    <th style="padding: 0.8rem; text-align: right;">
                        {{ $ingles ? 'Subtotal' : 'Subtotal' }}
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse ($pedido->detalles as $detalle)

                    <tr style="border-bottom: 1px solid #e5e7eb;">

                        <td style="padding: 0.8rem;">
                            {{ $detalle->producto?->nombre_localizado
                                ?? ($ingles ? 'Unavailable product' : 'Producto no disponible') }}
                        </td>

                        <td style="padding: 0.8rem; text-align: center;">
                            {{ $detalle->cantidad }}
                        </td>

                        <td style="padding: 0.8rem; text-align: right;">
                            ${{ number_format((float) $detalle->precio_unitario, 0, ',', '.') }}
                        </td>

                        <td style="padding: 0.8rem; text-align: right;">
                            ${{ number_format($detalle->subtotal(), 0, ',', '.') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" style="
                            padding: 1.5rem;
                            text-align: center;
                            color: #6b7280;
                        ">
                            {{ $ingles
                                ? 'No products found for this order.'
                                : 'No se encontraron productos para este pedido.' }}
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div style="
            text-align: right;
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 1.5rem;
        ">
            {{ $ingles ? 'Order Total:' : 'Total del pedido:' }}
            ${{ number_format((float) $pedido->total, 0, ',', '.') }} COP
        </div>

    </section>

    {{-- ACTUALIZACIÓN DEL ESTADO --}}
    <section style="
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    ">

        <h2>
            {{ $ingles ? 'Delivery Management' : 'Gestión de entrega' }}
        </h2>

        <p style="color: #6b7280;">
            {{ $ingles
                ? 'Order statuses must advance in sequence.'
                : 'Los estados del pedido deben avanzar en orden.' }}
        </p>

        @if ($siguienteEstado !== null)

            @php
                $siguienteEtiqueta = $estados[$siguienteEstado][$ingles ? 'en' : 'es'];
            @endphp

            <p>
                <strong>
                    {{ $ingles ? 'Next status:' : 'Siguiente estado:' }}
                </strong>

                {{ $siguienteEtiqueta }}
            </p>

            <form
                action="{{ route('admin.pedidos.estado', $pedido) }}"
                method="POST"
                onsubmit="return confirm(@js($ingles ? 'Are you sure you want to update this order status?' : '¿Seguro que deseas actualizar el estado de este pedido?'));"
            >

                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="estado"
                    value="{{ $siguienteEstado }}"
                >

                <button
                    type="submit"
                    style="
                        background: #2563eb;
                        color: #fff;
                        border: none;
                        border-radius: 7px;
                        padding: 0.85rem 1.5rem;
                        font-size: 1rem;
                        font-weight: bold;
                        cursor: pointer;
                    "
                >
                    {{ $ingles ? 'Advance to' : 'Avanzar a' }}
                    {{ $siguienteEtiqueta }}
                </button>

            </form>

        @elseif ($pedido->estado === 'entregado')

            <div style="
                background: #dcfce7;
                color: #166534;
                padding: 1rem;
                border-radius: 8px;
            ">
                {{ $ingles
                    ? 'This order has been delivered successfully. No further status changes are available.'
                    : 'Este pedido fue entregado correctamente. No hay más cambios de estado disponibles.' }}
            </div>

        @elseif ($pedido->estado === 'cancelado')

            <div style="
                background: #fee2e2;
                color: #991b1b;
                padding: 1rem;
                border-radius: 8px;
            ">
                {{ $ingles
                    ? 'This order has been cancelled and cannot be modified.'
                    : 'Este pedido fue cancelado y no puede modificarse.' }}
            </div>

        @else

            <p style="color: #991b1b;">
                {{ $ingles
                    ? 'The current order status is not recognized.'
                    : 'El estado actual del pedido no es reconocido.' }}
            </p>

        @endif

    </section>

    {{-- AVISO DE SIMULACIÓN --}}
    <div style="
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 2rem;
    ">

        <strong>
            {{ $ingles ? 'Demonstration Mode' : 'Modo demostración' }}
        </strong>

        <p style="margin-bottom: 0;">
            {{ $ingles
                ? 'Shipping statuses are simulated. Updating an order does not contact a real delivery company.'
                : 'Los estados de envío son simulados. Actualizar un pedido no contacta a ninguna empresa transportadora real.' }}
        </p>

    </div>

</div>

@endsection

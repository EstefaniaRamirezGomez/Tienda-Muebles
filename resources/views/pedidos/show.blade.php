@extends('layouts.app')

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

    $estadoActual = $estados[$pedido->estado] ?? [
        'es' => ucfirst($pedido->estado),
        'en' => ucfirst($pedido->estado),
        'color' => '#374151',
        'fondo' => '#f3f4f6',
    ];

    $pasos = [
        'pendiente',
        'preparando',
        'enviado',
        'entregado',
    ];

    $estadoNormalizado = $pedido->estado === 'en_preparacion'
        ? 'preparando'
        : $pedido->estado;

    $pasoActual = array_search($estadoNormalizado, $pasos, true);
@endphp

<div style="
    max-width: 1050px;
    margin: 2rem auto;
    padding: 0 1rem;
">

    {{-- MENSAJE DE CONFIRMACIÓN --}}
    @if (session('exito'))
        <div style="
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        ">
            {{ session('exito') }}
        </div>
    @endif

    {{-- ENCABEZADO --}}
    <h1>
        {{ $ingles ? 'Order Details' : 'Detalle del pedido' }}
        #{{ $pedido->id }}
    </h1>

    <p>
        {{ $ingles ? 'Order date:' : 'Fecha del pedido:' }}
        {{ $pedido->fecha->format('d/m/Y') }}
    </p>

    {{-- ESTADO ACTUAL --}}
    <section style="
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    ">

        <h2>
            {{ $ingles ? 'Shipping Status' : 'Estado del envío' }}
        </h2>

        <p>
            <span style="
                display: inline-block;
                background: {{ $estadoActual['fondo'] }};
                color: {{ $estadoActual['color'] }};
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-weight: bold;
            ">
                {{ $estadoActual[$ingles ? 'en' : 'es'] }}
            </span>
        </p>

        @if ($pedido->estado === 'cancelado')

            <p>
                {{ $ingles
                    ? 'This order has been cancelled.'
                    : 'Este pedido ha sido cancelado.' }}
            </p>

        @else

            {{-- SEGUIMIENTO DEL PEDIDO --}}
            <div style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.75rem;
                margin-top: 1.5rem;
            ">

                @foreach ($pasos as $indice => $paso)

                    @php
                        $completado = $pasoActual !== false
                            && $indice <= $pasoActual;

                        $etiquetas = [
                            'pendiente' => $ingles ? 'Received' : 'Recibido',
                            'preparando' => $ingles ? 'Preparing' : 'Preparando',
                            'enviado' => $ingles ? 'Shipped' : 'Enviado',
                            'entregado' => $ingles ? 'Delivered' : 'Entregado',
                        ];
                    @endphp

                    <div style="
                        text-align: center;
                        padding: 1rem 0.5rem;
                        border-radius: 8px;
                        border: 1px solid {{ $completado ? '#93c5fd' : '#e5e7eb' }};
                        background: {{ $completado ? '#eff6ff' : '#f9fafb' }};
                        color: {{ $completado ? '#1d4ed8' : '#6b7280' }};
                    ">

                        <div style="font-size: 1.4rem; margin-bottom: 0.4rem;">
                            {{ $completado ? '✓' : '○' }}
                        </div>

                        <strong>
                            {{ $etiquetas[$paso] }}
                        </strong>

                    </div>

                @endforeach

            </div>

            <p style="
                font-size: 0.85rem;
                color: #6b7280;
                margin-top: 1rem;
            ">
                {{ $ingles
                    ? 'Shipping progress is simulated and updated by the store administrator.'
                    : 'El seguimiento del envío es simulado y lo actualiza el administrador de la tienda.' }}
            </p>

        @endif

    </section>

    {{-- PRODUCTOS DEL PEDIDO --}}
    <section style="
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    ">

        <h2>
            {{ $ingles ? 'Purchased Products' : 'Productos comprados' }}
        </h2>

        <div style="overflow-x: auto;">

            <table style="width: 100%; border-collapse: collapse;">

                <thead>
                    <tr style="border-bottom: 2px solid #ddd;">

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

                    @foreach ($pedido->detalles as $detalle)

                        <tr style="border-bottom: 1px solid #eee;">

                            <td style="padding: 0.8rem;">
                                {{ $detalle->producto?->nombre_localizado
                                    ?? ($ingles ? 'Product unavailable' : 'Producto no disponible') }}
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

                    @endforeach

                </tbody>

            </table>

        </div>

        <div style="
            text-align: right;
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 1.5rem;
        ">
            {{ $ingles ? 'Total:' : 'Total:' }}
            ${{ number_format((float) $pedido->total, 0, ',', '.') }} COP
        </div>

    </section>

    {{-- INFORMACIÓN DE ENTREGA --}}
    <section style="
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    ">

        <h2>
            {{ $ingles ? 'Delivery Information' : 'Información de entrega' }}
        </h2>

        <p>
            <strong>{{ $ingles ? 'Recipient:' : 'Destinatario:' }}</strong>
            {{ $pedido->nombre_receptor }}
        </p>

        <p>
            <strong>{{ $ingles ? 'Address:' : 'Dirección:' }}</strong>
            {{ $pedido->direccion_envio }}
        </p>

        <p>
            <strong>{{ $ingles ? 'City:' : 'Ciudad:' }}</strong>
            {{ $pedido->ciudad_envio }}
        </p>

        <p>
            <strong>{{ $ingles ? 'Phone:' : 'Teléfono:' }}</strong>
            {{ $pedido->telefono_contacto }}
        </p>

    </section>

    {{-- ACCIONES --}}
    <div style="
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    ">

        <a
            href="{{ route('pedidos.index') }}"
            style="
                display: inline-block;
                background: #2563eb;
                color: #fff;
                padding: 0.8rem 1.2rem;
                border-radius: 7px;
                text-decoration: none;
            "
        >
            {{ $ingles ? 'My Orders' : 'Mis pedidos' }}
        </a>

        <a
            href="{{ route('productos.index') }}"
            style="
                display: inline-block;
                border: 1px solid #ccc;
                color: #374151;
                padding: 0.8rem 1.2rem;
                border-radius: 7px;
                text-decoration: none;
            "
        >
            {{ $ingles ? 'Continue Shopping' : 'Seguir comprando' }}
        </a>

    </div>

</div>

@endsection

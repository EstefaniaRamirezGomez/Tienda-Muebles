@extends('layouts.app')

@section('titulo', __('tienda.pedidos_show.order_details')
)

@section('contenido')

@php

    $estados = [
        'pendiente' => [
            'etiqueta' => __('tienda.estados.pendiente'),
            'color' => '#92400e',
            'fondo' => '#fef3c7',
        ],
        'preparando' => [
            'etiqueta' => __('tienda.estados.preparando'),
            'color' => '#1d4ed8',
            'fondo' => '#dbeafe',
        ],
        'en_preparacion' => [
            'etiqueta' => __('tienda.estados.en_preparacion'),
            'color' => '#1d4ed8',
            'fondo' => '#dbeafe',
        ],
        'enviado' => [
            'etiqueta' => __('tienda.estados.enviado'),
            'color' => '#6d28d9',
            'fondo' => '#ede9fe',
        ],
        'entregado' => [
            'etiqueta' => __('tienda.estados.entregado'),
            'color' => '#166534',
            'fondo' => '#dcfce7',
        ],
        'cancelado' => [
            'etiqueta' => __('tienda.estados.cancelado'),
            'color' => '#991b1b',
            'fondo' => '#fee2e2',
        ],
    ];

    $estadoActual = $estados[$pedido->estado] ?? [
        'etiqueta' => ucfirst($pedido->estado),
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

    @if (session('error'))
        <div class="mensaje-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- ENCABEZADO --}}
    <h1>
        {{ __('tienda.pedidos_show.order_details') }}
        #{{ $pedido->id }}
    </h1>

    <p>
        {{ __('tienda.pedidos_show.order_date') }}
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
            {{ __('tienda.pedidos_show.shipping_status') }}
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
                {{ $estadoActual['etiqueta'] }}
            </span>
        </p>

        @if ($pedido->estado === 'cancelado')

            <p>
                {{ __('tienda.pedidos_show.this_order_has_been_cancelled') }}
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
                            'pendiente' => __('tienda.pedidos_show.received'),
                            'preparando' => __('tienda.pedidos_show.preparing'),
                            'enviado' => __('tienda.pedidos_show.shipped'),
                            'entregado' => __('tienda.pedidos_show.delivered'),
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
                {{ __('tienda.pedidos_show.shipping_progress_is_simulated_and_updated') }}
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
            {{ __('tienda.pedidos_show.purchased_products') }}
        </h2>

        <div style="overflow-x: auto;">

            <table style="width: 100%; border-collapse: collapse;">

                <thead>
                    <tr style="border-bottom: 2px solid #ddd;">

                        <th style="padding: 0.8rem; text-align: left;">
                            {{ __('tienda.pedidos_show.product') }}
                        </th>

                        <th style="padding: 0.8rem; text-align: center;">
                            {{ __('tienda.pedidos_show.quantity') }}
                        </th>

                        <th style="padding: 0.8rem; text-align: right;">
                            {{ __('tienda.pedidos_show.unit_price') }}
                        </th>

                        <th style="padding: 0.8rem; text-align: right;">
                            {{ __('tienda.pedidos_show.subtotal') }}
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @foreach ($pedido->detalles as $detalle)

                        <tr style="border-bottom: 1px solid #eee;">

                            <td style="padding: 0.8rem;">
                                {{ $detalle->producto?->nombre_localizado
                                    ?? (__('tienda.pedidos_show.product_unavailable')) }}
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
            {{ __('tienda.pedidos_show.total') }}
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
            {{ __('tienda.pedidos_show.delivery_information') }}
        </h2>

        <p>
            <strong>{{ __('tienda.pedidos_show.recipient') }}</strong>
            {{ $pedido->nombre_receptor }}
        </p>

        <p>
            <strong>{{ __('tienda.pedidos_show.address') }}</strong>
            {{ $pedido->direccion_envio }}
        </p>

        <p>
            <strong>{{ __('tienda.pedidos_show.city') }}</strong>
            {{ $pedido->ciudad_envio }}
        </p>

        <p>
            <strong>{{ __('tienda.pedidos_show.phone') }}</strong>
            {{ $pedido->telefono_contacto }}
        </p>

    </section>

    {{-- CERTIFICADO BLOCKCHAIN --}}
    @include('certificados._seccion')

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
            {{ __('tienda.pedidos_show.my_orders') }}
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
            {{ __('tienda.pedidos_show.continue_shopping') }}
        </a>

    </div>

</div>

@endsection

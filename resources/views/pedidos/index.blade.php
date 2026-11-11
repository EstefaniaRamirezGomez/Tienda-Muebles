@extends('layouts.app')

@section('titulo', __('tienda.pedidos_index.my_orders')
)

@section('contenido')

@php

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
@endphp

<div style="
    max-width: 1100px;
    margin: 2rem auto;
    padding: 0 1rem;
">

    <h1>
        {{ __('tienda.pedidos_index.my_orders') }}
    </h1>

    <p>
        {{ __('tienda.pedidos_index.check_your_purchase_history_and_track') }}
    </p>

    @if ($pedidos->isEmpty())

        {{-- SIN PEDIDOS --}}
        <div style="
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 3rem 1.5rem;
            text-align: center;
            margin-top: 2rem;
        ">

            <div style="font-size: 3rem; margin-bottom: 1rem;">
                🛒
            </div>

            <h2>
                {{ __('tienda.pedidos_index.you_have_no_orders_yet') }}
            </h2>

            <p>
                {{ __('tienda.pedidos_index.explore_our_catalog_and_place_your') }}
            </p>

            <a
                href="{{ route('productos.index') }}"
                style="
                    display: inline-block;
                    margin-top: 1rem;
                    background: #2563eb;
                    color: white;
                    padding: 0.85rem 1.5rem;
                    border-radius: 7px;
                    text-decoration: none;
                "
            >
                {{ __('tienda.pedidos_index.view_products') }}
            </a>

        </div>

    @else

        {{-- LISTADO DE PEDIDOS --}}
        <div style="
            display: grid;
            gap: 1.25rem;
            margin-top: 2rem;
        ">

            @foreach ($pedidos as $pedido)

                @php
                    $estado = $estados[$pedido->estado] ?? [
                        'es' => ucfirst($pedido->estado),
                        'en' => ucfirst($pedido->estado),
                        'color' => '#374151',
                        'fondo' => '#f3f4f6',
                    ];

                    $cantidadProductos = $pedido->detalles->sum('cantidad');
                @endphp

                <article style="
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    padding: 1.5rem;
                ">

                    <div style="
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        flex-wrap: wrap;
                        gap: 1rem;
                    ">

                        <div>
                            <h2 style="margin-top: 0;">
                                {{ __('tienda.pedidos_index.order') }}
                                #{{ $pedido->id }}
                            </h2>

                            <p style="color: #6b7280;">
                                {{ __('tienda.pedidos_index.date') }}
                                {{ $pedido->fecha->format('d/m/Y') }}
                            </p>
                        </div>

                        <span style="
                            display: inline-block;
                            padding: 0.5rem 1rem;
                            border-radius: 20px;
                            background: {{ $estado['fondo'] }};
                            color: {{ $estado['color'] }};
                            font-weight: bold;
                        ">
                            {{ $estado[__('tienda.pedidos_index.en')] }}
                        </span>

                    </div>

                    <div style="
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                        gap: 1rem;
                        margin: 1rem 0;
                        padding: 1rem 0;
                        border-top: 1px solid #eee;
                        border-bottom: 1px solid #eee;
                    ">

                        <div>
                            <div style="color: #6b7280; font-size: 0.9rem;">
                                {{ __('tienda.pedidos_index.items') }}
                            </div>

                            <strong>
                                {{ $cantidadProductos }}
                            </strong>
                        </div>

                        <div>
                            <div style="color: #6b7280; font-size: 0.9rem;">
                                {{ __('tienda.pedidos_index.delivery_city') }}
                            </div>

                            <strong>
                                {{ $pedido->ciudad_envio ?? '-' }}
                            </strong>
                        </div>

                        <div>
                            <div style="color: #6b7280; font-size: 0.9rem;">
                                {{ __('tienda.pedidos_index.order_total') }}
                            </div>

                            <strong>
                                ${{ number_format((float) $pedido->total, 0, ',', '.') }} COP
                            </strong>
                        </div>

                    </div>

                    <a
                        href="{{ route('pedidos.show', $pedido) }}"
                        style="
                            display: inline-block;
                            background: #2563eb;
                            color: white;
                            padding: 0.75rem 1.2rem;
                            border-radius: 7px;
                            text-decoration: none;
                        "
                    >
                        {{ __('tienda.pedidos_index.view_details') }}
                    </a>

                </article>

            @endforeach

        </div>

    @endif

</div>

@endsection

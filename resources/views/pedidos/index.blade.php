@extends('layouts.app')

@section('titulo', app()->getLocale() === 'en'
    ? 'My Orders'
    : 'Mis pedidos'
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
@endphp

<div style="
    max-width: 1100px;
    margin: 2rem auto;
    padding: 0 1rem;
">

    <h1>
        {{ $ingles ? 'My Orders' : 'Mis pedidos' }}
    </h1>

    <p>
        {{ $ingles
            ? 'Check your purchase history and track your orders.'
            : 'Consulta tu historial de compras y el seguimiento de tus pedidos.' }}
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
                {{ $ingles
                    ? 'You have no orders yet'
                    : 'Todavía no tienes pedidos' }}
            </h2>

            <p>
                {{ $ingles
                    ? 'Explore our catalog and place your first order.'
                    : 'Explora nuestro catálogo y realiza tu primera compra.' }}
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
                {{ $ingles ? 'View Products' : 'Ver productos' }}
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
                                {{ $ingles ? 'Order' : 'Pedido' }}
                                #{{ $pedido->id }}
                            </h2>

                            <p style="color: #6b7280;">
                                {{ $ingles ? 'Date:' : 'Fecha:' }}
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
                            {{ $estado[$ingles ? 'en' : 'es'] }}
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
                                {{ $ingles ? 'Items' : 'Artículos' }}
                            </div>

                            <strong>
                                {{ $cantidadProductos }}
                            </strong>
                        </div>

                        <div>
                            <div style="color: #6b7280; font-size: 0.9rem;">
                                {{ $ingles ? 'Delivery City' : 'Ciudad de entrega' }}
                            </div>

                            <strong>
                                {{ $pedido->ciudad_envio ?? '-' }}
                            </strong>
                        </div>

                        <div>
                            <div style="color: #6b7280; font-size: 0.9rem;">
                                {{ $ingles ? 'Order Total' : 'Total del pedido' }}
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
                        {{ $ingles ? 'View Details' : 'Ver detalles' }}
                    </a>

                </article>

            @endforeach

        </div>

    @endif

</div>

@endsection

@extends('layouts.admin')

@section('titulo', __('tienda.admin_pedidos_show.order_details')
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

    $estado = $estados[$pedido->estado] ?? [
        'etiqueta' => ucfirst($pedido->estado),
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
        {{ __('tienda.admin_pedidos_show.back_to_orders') }}
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
                {{ __('tienda.admin_pedidos_show.order') }}
                #{{ $pedido->id }}
            </h1>

            <p style="color: #6b7280;">
                {{ __('tienda.admin_pedidos_show.order_date') }}
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
            {{ $estado['etiqueta'] }}
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
            {{ __('tienda.admin_pedidos_show.customer_information') }}
        </h2>

        <p>
            <strong>{{ __('tienda.admin_pedidos_show.name') }}</strong>
            {{ $pedido->usuario?->name ?? '-' }}
        </p>

        <p>
            <strong>{{ __('tienda.admin_pedidos_show.email') }}</strong>
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
            {{ __('tienda.admin_pedidos_show.delivery_information') }}
        </h2>

        <p>
            <strong>{{ __('tienda.admin_pedidos_show.recipient') }}</strong>
            {{ $pedido->nombre_receptor ?? '-' }}
        </p>

        <p>
            <strong>{{ __('tienda.admin_pedidos_show.address') }}</strong>
            {{ $pedido->direccion_envio ?? '-' }}
        </p>

        <p>
            <strong>{{ __('tienda.admin_pedidos_show.city') }}</strong>
            {{ $pedido->ciudad_envio ?? '-' }}
        </p>

        <p>
            <strong>{{ __('tienda.admin_pedidos_show.contact_phone') }}</strong>
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
            {{ __('tienda.admin_pedidos_show.purchased_products') }}
        </h2>

        <table style="
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        ">

            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb;">

                    <th style="padding: 0.8rem; text-align: left;">
                        {{ __('tienda.admin_pedidos_show.product') }}
                    </th>

                    <th style="padding: 0.8rem; text-align: center;">
                        {{ __('tienda.admin_pedidos_show.quantity') }}
                    </th>

                    <th style="padding: 0.8rem; text-align: right;">
                        {{ __('tienda.admin_pedidos_show.unit_price') }}
                    </th>

                    <th style="padding: 0.8rem; text-align: right;">
                        {{ __('tienda.admin_pedidos_show.subtotal') }}
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse ($pedido->detalles as $detalle)

                    <tr style="border-bottom: 1px solid #e5e7eb;">

                        <td style="padding: 0.8rem;">
                            {{ $detalle->producto?->nombre_localizado
                                ?? (__('tienda.admin_pedidos_show.unavailable_product')) }}
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
                            {{ __('tienda.admin_pedidos_show.no_products_found_for_this_order') }}
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
            {{ __('tienda.admin_pedidos_show.order_total') }}
            ${{ number_format((float) $pedido->total, 0, ',', '.') }} COP
        </div>

    </section>

    {{-- CERTIFICADO BLOCKCHAIN --}}
    @include('certificados._seccion')

    {{-- ACTUALIZACIÓN DEL ESTADO --}}
    <section style="
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    ">

        <h2>
            {{ __('tienda.admin_pedidos_show.delivery_management') }}
        </h2>

        <p style="color: #6b7280;">
            {{ __('tienda.admin_pedidos_show.order_statuses_must_advance_in_sequence') }}
        </p>

        @if ($siguienteEstado !== null)

            @php
                $siguienteEtiqueta = $estados[$siguienteEstado]['etiqueta'];
            @endphp

            <p>
                <strong>
                    {{ __('tienda.admin_pedidos_show.next_status') }}
                </strong>

                {{ $siguienteEtiqueta }}
            </p>

            <form
                action="{{ route('admin.pedidos.estado', $pedido) }}"
                method="POST"
                onsubmit="return confirm(@js(__('tienda.admin_pedidos_show.are_you_sure_you_want_to')));"
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
                    {{ __('tienda.admin_pedidos_show.advance_to') }}
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
                {{ __('tienda.admin_pedidos_show.this_order_has_been_delivered_successfully') }}
            </div>

        @elseif ($pedido->estado === 'cancelado')

            <div style="
                background: #fee2e2;
                color: #991b1b;
                padding: 1rem;
                border-radius: 8px;
            ">
                {{ __('tienda.admin_pedidos_show.this_order_has_been_cancelled_and') }}
            </div>

        @else

            <p style="color: #991b1b;">
                {{ __('tienda.admin_pedidos_show.the_current_order_status_is_not') }}
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
            {{ __('tienda.admin_pedidos_show.demonstration_mode') }}
        </strong>

        <p style="margin-bottom: 0;">
            {{ __('tienda.admin_pedidos_show.shipping_statuses_are_simulated_updating_an') }}
        </p>

    </div>

</div>

@endsection

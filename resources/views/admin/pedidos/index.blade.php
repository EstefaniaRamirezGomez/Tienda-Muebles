@extends('layouts.admin')

@section('titulo', __('tienda.admin_pedidos_index.order_management')
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

<div style="max-width: 1200px; margin: 0 auto;">

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
                {{ __('tienda.admin_pedidos_index.order_management') }}
            </h1>

            <p style="color: #6b7280;">
                {{ __('tienda.admin_pedidos_index.review_customer_orders_and_manage_their') }}
            </p>
        </div>

        <div style="
            background: #eff6ff;
            color: #1d4ed8;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            font-weight: bold;
        ">
            {{ __('tienda.admin_pedidos_index.total_orders') }}
            {{ $pedidos->total() }}
        </div>

    </div>

    {{-- MENSAJES --}}
    @if (session('exito'))
        <div style="
            background: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
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
            margin-bottom: 1rem;
        ">
            {{ session('error') }}
        </div>
    @endif

    {{-- LISTADO --}}
    <div style="
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        overflow-x: auto;
    ">

        @if ($pedidos->isEmpty())

            <div style="text-align: center; padding: 3rem 1rem;">

                <h2>
                    {{ __('tienda.admin_pedidos_index.no_orders_registered') }}
                </h2>

                <p style="color: #6b7280;">
                    {{ __('tienda.admin_pedidos_index.customer_orders_will_appear_here_once') }}
                </p>

            </div>

        @else

            <table style="
                width: 100%;
                border-collapse: collapse;
                min-width: 750px;
            ">

                <thead>
                    <tr style="
                        background: #f9fafb;
                        border-bottom: 2px solid #e5e7eb;
                    ">

                        <th style="padding: 0.9rem; text-align: left;">
                            {{ __('tienda.admin_pedidos_index.order') }}
                        </th>

                        <th style="padding: 0.9rem; text-align: left;">
                            {{ __('tienda.admin_pedidos_index.customer') }}
                        </th>

                        <th style="padding: 0.9rem; text-align: left;">
                            {{ __('tienda.admin_pedidos_index.date') }}
                        </th>

                        <th style="padding: 0.9rem; text-align: right;">
                            {{ __('tienda.admin_pedidos_index.total') }}
                        </th>

                        <th style="padding: 0.9rem; text-align: center;">
                            {{ __('tienda.admin_pedidos_index.status') }}
                        </th>

                        <th style="padding: 0.9rem; text-align: center;">
                            {{ __('tienda.admin_pedidos_index.actions') }}
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @foreach ($pedidos as $pedido)

                        @php
                            $estado = $estados[$pedido->estado] ?? [
                                'es' => ucfirst($pedido->estado),
                                'en' => ucfirst($pedido->estado),
                                'color' => '#374151',
                                'fondo' => '#f3f4f6',
                            ];
                        @endphp

                        <tr style="border-bottom: 1px solid #e5e7eb;">

                            {{-- NÚMERO --}}
                            <td style="padding: 1rem;">
                                <strong>#{{ $pedido->id }}</strong>
                            </td>

                            {{-- CLIENTE --}}
                            <td style="padding: 1rem;">
                                <strong>
                                    {{ $pedido->usuario?->name
                                        ?? (__('tienda.admin_pedidos_index.unknown_customer')) }}
                                </strong>

                                @if ($pedido->usuario?->email)
                                    <div style="
                                        font-size: 0.85rem;
                                        color: #6b7280;
                                        margin-top: 0.25rem;
                                    ">
                                        {{ $pedido->usuario->email }}
                                    </div>
                                @endif
                            </td>

                            {{-- FECHA --}}
                            <td style="padding: 1rem;">
                                {{ $pedido->fecha?->format('d/m/Y') ?? '-' }}
                            </td>

                            {{-- TOTAL --}}
                            <td style="padding: 1rem; text-align: right;">
                                <strong>
                                    ${{ number_format((float) $pedido->total, 0, ',', '.') }}
                                </strong>
                                <small>COP</small>
                            </td>

                            {{-- ESTADO --}}
                            <td style="padding: 1rem; text-align: center;">
                                <span style="
                                    display: inline-block;
                                    padding: 0.45rem 0.8rem;
                                    border-radius: 20px;
                                    background: {{ $estado['fondo'] }};
                                    color: {{ $estado['color'] }};
                                    font-size: 0.85rem;
                                    font-weight: bold;
                                    white-space: nowrap;
                                ">
                                    {{ $estado[__('tienda.admin_pedidos_index.en')] }}
                                </span>
                            </td>

                            {{-- ACCIONES --}}
                            <td style="padding: 1rem; text-align: center;">
                                <a
                                    href="{{ route('admin.pedidos.show', $pedido) }}"
                                    style="
                                        display: inline-block;
                                        background: #2563eb;
                                        color: white;
                                        padding: 0.6rem 1rem;
                                        border-radius: 6px;
                                        text-decoration: none;
                                        white-space: nowrap;
                                    "
                                >
                                    {{ __('tienda.admin_pedidos_index.view_details') }}
                                </a>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @endif

    </div>

    {{-- PAGINACIÓN --}}
    @if ($pedidos->hasPages())

        <div style="margin-top: 1.5rem;">
            {{ $pedidos->links() }}
        </div>

    @endif

</div>

@endsection

@extends('layouts.app')

@section('titulo', app()->getLocale() === 'en'
    ? 'Checkout'
    : 'Finalizar compra'
)

@section('contenido')

@php
    $ingles = app()->getLocale() === 'en';
@endphp

<div style="max-width: 1050px; margin: 2rem auto; padding: 0 1rem;">

    <h1>
        {{ $ingles ? 'Checkout' : 'Finalizar compra' }}
    </h1>

    <p>
        {{ $ingles
            ? 'Review your order and enter your delivery information.'
            : 'Revisa tu pedido e ingresa los datos de entrega.' }}
    </p>

    {{-- MENSAJES DE ERROR --}}
    @if ($errors->any())
        <div style="
            background: #fdecea;
            color: #842029;
            border: 1px solid #f5c2c7;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        ">
            <strong>
                {{ $ingles
                    ? 'Please review the following errors:'
                    : 'Revisa los siguientes errores:' }}
            </strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- RESUMEN DEL PEDIDO --}}
    <section style="
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    ">

        <h2>
            {{ $ingles ? 'Order Summary' : 'Resumen del pedido' }}
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
                            {{ $ingles ? 'Subtotal' : 'Subtotal' }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($items as $item)
                        <tr style="border-bottom: 1px solid #eee;">

                            <td style="padding: 0.8rem;">
                                {{ $item['producto']->nombre_localizado }}
                            </td>

                            <td style="padding: 0.8rem; text-align: center;">
                                {{ $item['cantidad'] }}
                            </td>

                            <td style="padding: 0.8rem; text-align: right;">
                                ${{ number_format($item['subtotal'], 0, ',', '.') }}
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
            ${{ number_format($total, 0, ',', '.') }} COP
        </div>

    </section>

    {{-- DATOS DE ENTREGA --}}
    <section style="
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1.5rem;
    ">

        <h2>
            {{ $ingles ? 'Delivery Information' : 'Datos de entrega' }}
        </h2>

        <form action="{{ route('pedidos.store') }}" method="POST">

            @csrf

            <div style="margin-bottom: 1rem;">

                <label for="nombre_receptor" style="display: block; margin-bottom: 0.4rem;">
                    {{ $ingles ? 'Recipient Name' : 'Nombre de quien recibe' }}
                </label>

                <input
                    type="text"
                    id="nombre_receptor"
                    name="nombre_receptor"
                    value="{{ old('nombre_receptor', auth()->user()->name ?? '') }}"
                    maxlength="150"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;"
                >

            </div>

            <div style="margin-bottom: 1rem;">

                <label for="direccion_envio" style="display: block; margin-bottom: 0.4rem;">
                    {{ $ingles ? 'Delivery Address' : 'Dirección de entrega' }}
                </label>

                <input
                    type="text"
                    id="direccion_envio"
                    name="direccion_envio"
                    value="{{ old('direccion_envio') }}"
                    maxlength="255"
                    required
                    placeholder="{{ $ingles
                        ? 'Street, number, apartment, additional details'
                        : 'Calle, número, apartamento, indicaciones adicionales' }}"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;"
                >

            </div>

            <div style="margin-bottom: 1rem;">

                <label for="ciudad_envio" style="display: block; margin-bottom: 0.4rem;">
                    {{ $ingles ? 'City' : 'Ciudad' }}
                </label>

                <input
                    type="text"
                    id="ciudad_envio"
                    name="ciudad_envio"
                    value="{{ old('ciudad_envio') }}"
                    maxlength="100"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;"
                >

            </div>

            <div style="margin-bottom: 1.5rem;">

                <label for="telefono_contacto" style="display: block; margin-bottom: 0.4rem;">
                    {{ $ingles ? 'Contact Phone' : 'Teléfono de contacto' }}
                </label>

                <input
                    type="tel"
                    id="telefono_contacto"
                    name="telefono_contacto"
                    value="{{ old('telefono_contacto') }}"
                    maxlength="30"
                    required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;"
                >

            </div>

            {{-- AVISO DE SIMULACIÓN --}}
            <div style="
                background: #eff6ff;
                border: 1px solid #bfdbfe;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1.5rem;
            ">
                <strong>
                    {{ $ingles ? 'Simulated Purchase' : 'Compra simulada' }}
                </strong>

                <p style="margin-bottom: 0;">
                    {{ $ingles
                        ? 'No real payment will be processed. This order is created for demonstration purposes only.'
                        : 'No se realizará ningún cobro real. Este pedido se genera únicamente con fines de demostración.' }}
                </p>
            </div>

            <div style="
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
                align-items: center;
            ">

                <button
                    type="submit"
                    style="
                        background: #2563eb;
                        color: #fff;
                        border: none;
                        border-radius: 7px;
                        padding: 0.85rem 1.5rem;
                        font-size: 1rem;
                        cursor: pointer;
                    "
                >
                    {{ $ingles ? 'Confirm Order' : 'Confirmar pedido' }}
                </button>

                <a
                    href="{{ route('carrito.index') }}"
                    style="color: #374151; text-decoration: underline;"
                >
                    {{ $ingles ? 'Back to Cart' : 'Volver al carrito' }}
                </a>

            </div>

        </form>

    </section>

</div>

@endsection

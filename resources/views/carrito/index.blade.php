@extends('layouts.app')

@section('titulo', __('tienda.carrito_index.shopping_cart'))

@section('contenido')

    @php

        // Consultar los productos para obtener sus nombres traducidos.
        $idsProductos = collect($carrito ?? [])
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $productosTraducidos = \App\Models\Producto::whereIn('id', $idsProductos)
            ->get()
            ->keyBy('id');
    @endphp

    <h2>
        {{ __('tienda.carrito_index.shopping_cart') }}
    </h2>

    {{-- MENSAJES --}}
    @if (session('exito'))
        <div class="mensaje-exito">
            {{ session('exito') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mensaje-error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mensaje-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARRITO VACÍO --}}
    @if (empty($carrito))

        <div class="tarjeta">

            <p class="vacio">
                {{ __('tienda.carrito_index.your_shopping_cart_is_empty') }}
            </p>

            <a
                href="{{ route('productos.index') }}"
                class="boton"
            >
                {{ __('tienda.carrito_index.view_products') }}
            </a>

        </div>

    @else

        {{-- TABLA DE PRODUCTOS --}}
        <div class="tarjeta" style="overflow-x:auto;">

            <table>

                <thead>
                    <tr>
                        <th>
                            {{ __('tienda.carrito_index.product') }}
                        </th>

                        <th>
                            {{ __('tienda.carrito_index.price') }}
                        </th>

                        <th>
                            {{ __('tienda.carrito_index.quantity') }}
                        </th>

                        <th>
                            {{ __('tienda.carrito_index.subtotal') }}
                        </th>

                        <th>
                            {{ __('tienda.carrito_index.actions') }}
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($carrito as $item)

                        @php
                            $productoActual = $productosTraducidos->get($item['id']);

                            $nombreProducto = $productoActual
                                ? $productoActual->nombre_localizado
                                : $item['nombre'];
                        @endphp

                        <tr>

                            {{-- PRODUCTO --}}
                            <td>

                                <div style="display:flex; align-items:center; gap:1rem;">

                                    <img
                                        src="{{ $item['imagen'] ?? 'https://placehold.co/100x80?text=No+image' }}"
                                        alt="{{ $nombreProducto }}"
                                        style="
                                            width:80px;
                                            height:60px;
                                            object-fit:cover;
                                            border-radius:6px;
                                        "
                                    >

                                    <div>
                                        <strong>
                                            {{ $nombreProducto }}
                                        </strong>
                                    </div>

                                </div>

                            </td>

                            {{-- PRECIO --}}
                            <td>
                                ${{ number_format($item['precio'], 0, ',', '.') }}
                            </td>

                            {{-- CANTIDAD --}}
                            <td>

                                <form
                                    action="{{ route('carrito.actualizar', $item['id']) }}"
                                    method="POST"
                                    style="display:flex; gap:0.5rem; align-items:center;"
                                >

                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="number"
                                        name="cantidad"
                                        value="{{ $item['cantidad'] }}"
                                        min="1"
                                        max="{{ $productoActual?->stock ?? $item['stock'] }}"
                                        aria-label="{{ __('tienda.carrito_index.quantity') }}"
                                        style="width:70px;"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="boton boton-secundario"
                                    >
                                        {{ __('tienda.carrito_index.update') }}
                                    </button>

                                </form>

                            </td>

                            {{-- SUBTOTAL --}}
                            <td>
                                ${{ number_format(
                                    $item['precio'] * $item['cantidad'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            {{-- ACCIONES --}}
                            <td>

                                <form
                                    action="{{ route('carrito.eliminar', $item['id']) }}"
                                    method="POST"
                                    onsubmit="return confirm(@js(__('tienda.carrito_index.remove_this_product_from_your_cart')));"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="boton boton-eliminar"
                                    >
                                        {{ __('tienda.carrito_index.remove') }}
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- TOTAL DEL CARRITO --}}
        <div
            class="tarjeta"
            style="
                margin-top:1rem;
                display:flex;
                justify-content:space-between;
                align-items:center;
                gap:1rem;
                flex-wrap:wrap;
            "
        >

            <div>

                <h3 style="margin:0;">
                    {{ __('tienda.carrito_index.total') }}
                </h3>

                <p
                    class="precio"
                    style="
                        font-size:1.5rem;
                        margin:0.5rem 0 0;
                    "
                >
                    ${{ number_format($total, 0, ',', '.') }} COP
                </p>

                <small style="color:#6b7280;">
                    {{ __('tienda.carrito_index.final_prices_will_be_verified_when') }}
                </small>

            </div>

            <div style="
                display:flex;
                gap:0.5rem;
                flex-wrap:wrap;
                align-items:center;
            ">

                {{-- SEGUIR COMPRANDO --}}
                <a
                    href="{{ route('productos.index') }}"
                    class="boton boton-secundario"
                >
                    {{ __('tienda.carrito_index.continue_shopping') }}
                </a>

                {{-- VACIAR CARRITO --}}
                <form
                    action="{{ route('carrito.vaciar') }}"
                    method="POST"
                    onsubmit="return confirm(@js(__('tienda.carrito_index.are_you_sure_you_want_to')));"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="boton boton-eliminar"
                    >
                        {{ __('tienda.carrito_index.empty_cart') }}
                    </button>

                </form>

                {{-- FINALIZAR COMPRA --}}
                <a
                    href="{{ route('pedidos.create') }}"
                    class="boton"
                    style="
                        background:#16a34a;
                        color:#fff;
                        font-weight:bold;
                        text-decoration:none;
                        display:inline-block;
                    "
                >
                    {{ __('tienda.carrito_index.checkout') }}
                </a>

            </div>

        </div>

    @endif

@endsection

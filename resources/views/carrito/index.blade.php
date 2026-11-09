@extends('layouts.app')

@section('titulo', app()->getLocale() === 'en' ? 'Shopping Cart' : 'Carrito de compras')

@section('contenido')

    @php
        $ingles = app()->getLocale() === 'en';

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
        {{ $ingles ? 'Shopping Cart' : 'Carrito de compras' }}
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
                {{ $ingles
                    ? 'Your shopping cart is empty.'
                    : 'Tu carrito está vacío.' }}
            </p>

            <a
                href="{{ route('productos.index') }}"
                class="boton"
            >
                {{ $ingles ? 'View Products' : 'Ver productos' }}
            </a>

        </div>

    @else

        {{-- TABLA DE PRODUCTOS --}}
        <div class="tarjeta" style="overflow-x:auto;">

            <table>

                <thead>
                    <tr>
                        <th>
                            {{ $ingles ? 'Product' : 'Producto' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Price' : 'Precio' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Quantity' : 'Cantidad' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Subtotal' : 'Subtotal' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Actions' : 'Acciones' }}
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
                                        aria-label="{{ $ingles ? 'Quantity' : 'Cantidad' }}"
                                        style="width:70px;"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="boton boton-secundario"
                                    >
                                        {{ $ingles ? 'Update' : 'Actualizar' }}
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
                                    onsubmit="return confirm(@js($ingles ? 'Remove this product from your cart?' : '¿Eliminar este producto del carrito?'));"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="boton boton-eliminar"
                                    >
                                        {{ $ingles ? 'Remove' : 'Eliminar' }}
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
                    {{ $ingles ? 'Total' : 'Total' }}
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
                    {{ $ingles
                        ? 'Final prices will be verified when confirming your order.'
                        : 'Los precios finales se verificarán al confirmar el pedido.' }}
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
                    {{ $ingles ? 'Continue Shopping' : 'Seguir comprando' }}
                </a>

                {{-- VACIAR CARRITO --}}
                <form
                    action="{{ route('carrito.vaciar') }}"
                    method="POST"
                    onsubmit="return confirm(@js($ingles ? 'Are you sure you want to empty your entire cart?' : '¿Seguro que deseas vaciar todo el carrito?'));"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="boton boton-eliminar"
                    >
                        {{ $ingles ? 'Empty Cart' : 'Vaciar carrito' }}
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
                    {{ $ingles ? 'Checkout' : 'Finalizar compra' }}
                </a>

            </div>

        </div>

    @endif

@endsection

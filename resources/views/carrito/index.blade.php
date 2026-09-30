@extends('layouts.app')

@section('titulo', 'Carrito de compras')

@section('contenido')

    <h2>Carrito de compras</h2>

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

    @if (empty($carrito))

        <div class="tarjeta">
            <p class="vacio">
                Tu carrito está vacío.
            </p>

            <a
                href="{{ route('productos.index') }}"
                class="boton"
            >
                Ver productos
            </a>
        </div>

    @else

        <div class="tarjeta">

            <table>

                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($carrito as $item)

                        <tr>

                            <td>
                                <div style="display:flex; align-items:center; gap:1rem;">

                                    <img
                                        src="{{ $item['imagen'] ?? 'https://placehold.co/100x80?text=Sin+imagen' }}"
                                        alt="{{ $item['nombre'] }}"
                                        style="
                                            width:80px;
                                            height:60px;
                                            object-fit:cover;
                                            border-radius:6px;
                                        "
                                    >

                                    <div>
                                        <strong>
                                            {{ $item['nombre'] }}
                                        </strong>
                                    </div>

                                </div>
                            </td>

                            <td>
                                ${{ number_format($item['precio'], 0, ',', '.') }}
                            </td>

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
                                        max="{{ $item['stock'] }}"
                                        style="width:70px;"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="boton boton-secundario"
                                    >
                                        Actualizar
                                    </button>

                                </form>

                            </td>

                            <td>
                                ${{ number_format(
                                    $item['precio'] * $item['cantidad'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td>

                                <form
                                    action="{{ route('carrito.eliminar', $item['id']) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Eliminar este producto del carrito?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="boton boton-eliminar"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        <div
            class="tarjeta"
            style="
                margin-top:1rem;
                display:flex;
                justify-content:space-between;
                align-items:center;
                gap:1rem;
            "
        >

            <div>

                <h3 style="margin:0;">
                    Total
                </h3>

                <p
                    class="precio"
                    style="
                        font-size:1.5rem;
                        margin:0.5rem 0 0;
                    "
                >
                    ${{ number_format($total, 0, ',', '.') }}
                </p>

            </div>

            <div style="display:flex; gap:0.5rem;">

                <a
                    href="{{ route('productos.index') }}"
                    class="boton boton-secundario"
                >
                    Seguir comprando
                </a>

                <form
                    action="{{ route('carrito.vaciar') }}"
                    method="POST"
                    onsubmit="return confirm('¿Seguro que deseas vaciar todo el carrito?');"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="boton boton-eliminar"
                    >
                        Vaciar carrito
                    </button>

                </form>

            </div>

        </div>

    @endif

@endsection
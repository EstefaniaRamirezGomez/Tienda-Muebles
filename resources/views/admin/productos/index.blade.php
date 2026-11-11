@extends('layouts.admin')

@section('titulo', __('tienda.admin_productos_index.manage_products')
)

@section('contenido')


    {{-- ENCABEZADO --}}
    <div class="encabezado-admin">

        <div>

            <h2>
                {{ __('tienda.admin_productos_index.manage_products') }}
            </h2>

            <p>
                {{ __('tienda.admin_productos_index.from_here_you_can_create_edit') }}
            </p>

        </div>

        {{-- CREAR PRODUCTO --}}
        <a
            href="{{ route('admin.productos.create') }}"
            class="boton"
        >
            + {{ __('tienda.admin_productos_index.new_product') }}
        </a>

    </div>

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

    {{-- SIN PRODUCTOS --}}
    @if ($productos->isEmpty())

        <p class="vacio">
            {{ __('tienda.admin_productos_index.no_products_have_been_registered_yet') }}
        </p>

    @else

        {{-- TABLA DE PRODUCTOS --}}
        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>
                            {{ __('tienda.admin_productos_index.image') }}
                        </th>

                        <th>
                            {{ __('tienda.admin_productos_index.product') }}
                        </th>

                        <th>
                            {{ __('tienda.admin_productos_index.category') }}
                        </th>

                        <th>
                            {{ __('tienda.admin_productos_index.room') }}
                        </th>

                        <th>
                            {{ __('tienda.admin_productos_index.price') }}
                        </th>

                        <th>
                            {{ __('tienda.admin_productos_index.stock') }}
                        </th>

                        <th>
                            {{ __('tienda.admin_productos_index.actions') }}
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($productos as $producto)

                        @php
                            // Nombre localizado del producto.
                            $nombreProducto = $producto->nombre_localizado;

                            // Categoría localizada.
                            $categoriaTraducida = $producto->categoria?->nombre_localizado ?? __('tienda.admin_productos_index.no_category');

                            // Ambiente localizado.
                            $ambienteTraducido = $producto->ambiente?->nombre_localizado ?? __('tienda.admin_productos_index.no_room');
                        @endphp

                        <tr>

                            {{-- IMAGEN --}}
                            <td>

                                <img
                                    src="{{ $producto->imagen ?: 'https://placehold.co/100x80?text=No+image' }}"
                                    alt="{{ $nombreProducto }}"
                                    width="80"
                                    loading="lazy"
                                >

                            </td>

                            {{-- PRODUCTO --}}
                            <td>
                                {{ $nombreProducto }}
                            </td>

                            {{-- CATEGORÍA --}}
                            <td>
                                {{ $categoriaTraducida }}
                            </td>

                            {{-- AMBIENTE --}}
                            <td>
                                {{ $ambienteTraducido }}
                            </td>

                            {{-- PRECIO --}}
                            <td>
                                ${{ number_format(
                                    $producto->precio,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            {{-- STOCK --}}
                            <td>
                                {{ $producto->stock }}
                            </td>

                            {{-- ACCIONES --}}
                            <td>

                                {{-- EDITAR --}}
                                <a
                                    href="{{ route('admin.productos.edit', $producto) }}"
                                    class="boton"
                                >
                                    {{ __('tienda.admin_productos_index.edit') }}
                                </a>

                                {{-- ELIMINAR --}}
                                <form
                                    action="{{ route('admin.productos.destroy', $producto) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm(@js(__('tienda.admin_productos_index.are_you_sure_you_want_to')));"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="boton boton-eliminar"
                                    >
                                        {{ __('tienda.admin_productos_index.delete') }}
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

@endsection

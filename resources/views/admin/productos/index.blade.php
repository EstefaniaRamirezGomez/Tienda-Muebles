@extends('layouts.admin')

@section('titulo', app()->getLocale() === 'en'
    ? 'Manage Products'
    : 'Administrar productos'
)

@section('contenido')

    @php
        $ingles = app()->getLocale() === 'en';

        // Normalizar nombres de categorías y ambientes.
        $normalizar = function ($valor) {
            return str_replace(
                [' ', '-'],
                '_',
                \Illuminate\Support\Str::ascii(
                    mb_strtolower(trim((string) $valor))
                )
            );
        };

        // Traducir categorías y ambientes usando messages.php.
        $traducirRelacion = function ($nombre, $tipo, $alternativa) use ($normalizar) {
            if (!$nombre) {
                return $alternativa;
            }

            $clave = 'messages.' . $tipo . '.' . $normalizar($nombre);

            return \Illuminate\Support\Facades\Lang::has($clave)
                ? __($clave)
                : $nombre;
        };

        // Mensaje de confirmación según el idioma.
        $mensajeEliminar = $ingles
            ? 'Are you sure you want to delete this product? This action cannot be undone.'
            : '¿Seguro que deseas eliminar este producto? Esta acción no se puede deshacer.';
    @endphp

    {{-- ENCABEZADO --}}
    <div class="encabezado-admin">

        <div>

            <h2>
                {{ $ingles ? 'Manage Products' : 'Administrar productos' }}
            </h2>

            <p>
                {{ $ingles
                    ? 'From here you can create, edit, and delete products in the store.'
                    : 'Desde aquí puedes crear, modificar y eliminar los productos de la tienda.' }}
            </p>

        </div>

        {{-- CREAR PRODUCTO --}}
        <a
            href="{{ route('admin.productos.create') }}"
            class="boton"
        >
            + {{ $ingles ? 'New Product' : 'Nuevo producto' }}
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
            {{ $ingles
                ? 'No products have been registered yet.'
                : 'No hay productos registrados.' }}
        </p>

    @else

        {{-- TABLA DE PRODUCTOS --}}
        <div class="tabla-contenedor">

            <table>

                <thead>

                    <tr>

                        <th>
                            {{ $ingles ? 'Image' : 'Imagen' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Product' : 'Producto' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Category' : 'Categoría' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Room' : 'Ambiente' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Price' : 'Precio' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Stock' : 'Stock' }}
                        </th>

                        <th>
                            {{ $ingles ? 'Actions' : 'Acciones' }}
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($productos as $producto)

                        @php
                            // Nombre localizado del producto.
                            $nombreProducto = $producto->nombre_localizado;

                            // Categoría localizada.
                            $categoriaTraducida = $traducirRelacion(
                                $producto->categoria?->nombre,
                                'categories',
                                $ingles ? 'No category' : 'Sin categoría'
                            );

                            // Ambiente localizado.
                            $ambienteTraducido = $traducirRelacion(
                                $producto->ambiente?->nombre,
                                'rooms',
                                $ingles ? 'No room' : 'Sin ambiente'
                            );
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
                                    {{ $ingles ? 'Edit' : 'Editar' }}
                                </a>

                                {{-- ELIMINAR --}}
                                <form
                                    action="{{ route('admin.productos.destroy', $producto) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm(@js($mensajeEliminar));"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="boton boton-eliminar"
                                    >
                                        {{ $ingles ? 'Delete' : 'Eliminar' }}
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

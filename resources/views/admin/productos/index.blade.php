@extends('layouts.admin')

@section('titulo', 'Administrar productos')

@section('contenido')

    <div class="encabezado-admin">
        <div>
            <h2>Administrar productos</h2>

            <p>
                Desde aquí puedes crear, modificar y eliminar los productos de la tienda.
            </p>
        </div>

        <a
            href="{{ route('admin.productos.create') }}"
            class="boton"
        >
            + Nuevo producto
        </a>
    </div>

    @if (session('exito'))
        <div class="mensaje-exito">
            {{ session('exito') }}
        </div>
    @endif

    @if ($productos->isEmpty())

        <p>No hay productos registrados.</p>

    @else

        <div class="tabla-contenedor">

            <table>

                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Ambiente</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($productos as $producto)

                        <tr>

                            <td>
                                <img
                                    src="{{ $producto->imagen ?? 'https://placehold.co/100x80?text=Sin+imagen' }}"
                                    alt="{{ $producto->nombre }}"
                                    width="80"
                                >
                            </td>

                            <td>
                                {{ $producto->nombre }}
                            </td>

                            <td>
                                {{ $producto->categoria->nombre }}
                            </td>

                            <td>
                                {{ $producto->ambiente->nombre }}
                            </td>

                            <td>
                                ${{ number_format($producto->precio, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $producto->stock }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('admin.productos.edit', $producto) }}"
                                    class="boton"
                                >
                                    Editar
                                </a>

                                <form
                                    action="{{ route('admin.productos.destroy', $producto) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este producto? Esta acción no se puede deshacer.');"
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

    @endif

@endsection
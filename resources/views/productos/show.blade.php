@extends('layouts.app')

@php
    // Nombre y descripción traducidos desde el modelo
    $nombreProducto = $producto->nombre_localizado;
    $descripcionProducto = $producto->descripcion_localizada;

    $nombreCategoria = $producto->categoria?->nombre_localizado;
    $nombreAmbiente = $producto->ambiente?->nombre_localizado;
@endphp

@section('titulo', $nombreProducto)

@section('contenido')

    {{-- Volver al catálogo --}}
    <a href="{{ route('productos.index') }}">
        &larr;
        {{ __('tienda.productos_show.back_to_catalog') }}
    </a>

    {{-- Mensajes del sistema --}}
    @if (session('exito'))
        <div class="mensaje-exito" style="margin-top:1rem;">
            {{ session('exito') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mensaje-error" style="margin-top:1rem;">
            {{ session('error') }}
        </div>
    @endif

    <div class="tarjeta" style="margin-top:1rem;">

        {{-- Categoría y ambiente --}}
        <div style="margin-bottom:1rem;">

            <span class="etiqueta">
                {{ $nombreCategoria ?: (__('tienda.productos_show.uncategorized')) }}
            </span>

            <span class="etiqueta">
                {{ $nombreAmbiente ?: (__('tienda.productos_show.no_room_assigned')) }}
            </span>

        </div>

        {{-- Imagen del producto --}}
        <div style="text-align:center; margin-bottom:1.5rem;">

            <img
                src="{{ $producto->imagen ?: 'https://placehold.co/600x400?text=No+image' }}"
                alt="{{ $nombreProducto }}"
                style="
                    width:100%;
                    max-width:550px;
                    max-height:400px;
                    object-fit:cover;
                    border-radius:10px;
                "
            >

        </div>

        {{-- Información general --}}
        <h2>
            {{ $nombreProducto }}
        </h2>

        <p>
            {{ $descripcionProducto }}
        </p>

        <p class="precio" style="font-size:1.4rem;">
            ${{ number_format($producto->precio, 0, ',', '.') }}
        </p>

        <p>
            <strong>
                {{ __('tienda.productos_show.available_stock') }}
            </strong>

            {{ $producto->stock }}
        </p>

        <p>
            <strong>
                {{ __('tienda.productos_show.average_rating') }}
            </strong>

            <span class="estrella">
                ★ {{ $producto->calificacionPromedio() }} / 5
            </span>
        </p>

        {{-- Carrito de compras --}}
        @auth

            @if ($producto->stock > 0)

                <form
                    action="{{ route('carrito.agregar', $producto) }}"
                    method="POST"
                    style="
                        margin-top:1rem;
                        margin-bottom:1.5rem;
                        display:flex;
                        gap:0.5rem;
                        align-items:center;
                        flex-wrap:wrap;
                    "
                >
                    @csrf

                    <label for="cantidad">
                        {{ __('tienda.productos_show.quantity') }}
                    </label>

                    <input
                        type="number"
                        id="cantidad"
                        name="cantidad"
                        value="{{ old('cantidad', 1) }}"
                        min="1"
                        max="{{ $producto->stock }}"
                        style="
                            width:80px;
                            padding:0.55rem;
                            border:1px solid #CFC5BB;
                            border-radius:6px;
                        "
                        required
                    >

                    <button type="submit" class="boton">
                        {{ __('tienda.productos_show.add_to_cart') }}
                    </button>

                </form>

            @else

                <p class="vacio">
                    {{ __('tienda.productos_show.this_product_is_out_of_stock') }}
                </p>

            @endif

        @else

            <div style="margin:1rem 0 1.5rem;">

                <a
                    href="{{ route('login') }}"
                    class="boton"
                >
                    {{ __('tienda.productos_show.log_in_to_add_to_cart') }}
                </a>

            </div>

        @endauth

        {{-- Ficha técnica --}}
        @if ($producto->especificacion)

            <h3>
                {{ __('tienda.productos_show.technical_specifications') }}
            </h3>

            <table>

                <tr>
                    <th>
                        {{ __('tienda.productos_show.material') }}
                    </th>

                    <td>
                        {{ $producto->especificacion->material_localizado }}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ __('tienda.productos_show.color') }}
                    </th>

                    <td>
                        {{ $producto->especificacion->color_localizado }}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ __('tienda.productos_show.dimensions_w_x_h_x_d') }}
                    </th>

                    <td>
                        {{ $producto->especificacion->ancho_cm }}
                        x
                        {{ $producto->especificacion->alto_cm }}
                        x
                        {{ $producto->especificacion->profundidad_cm }}
                        cm
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ __('tienda.productos_show.weight') }}
                    </th>

                    <td>
                        {{ $producto->especificacion->peso_kg }} kg
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ __('tienda.productos_show.assembly_required') }}
                    </th>

                    <td>
                        {{ $producto->especificacion->requiere_ensamblaje ? __('tienda.comun.si') : __('tienda.comun.no') }}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ __('tienda.productos_show.warranty') }}
                    </th>

                    <td>
                        {{ trans_choice('tienda.comun.meses', $producto->especificacion->garantia_meses, ['cantidad' => $producto->especificacion->garantia_meses]) }}
                    </td>
                </tr>

            </table>

            <a
                class="boton boton-secundario"
                style="margin-top:0.8rem;"
                href="{{ route('productos.index') }}"
            >
                {{ __('tienda.productos_show.compare_with_other_products') }}
            </a>

        @endif

        {{-- Reseñas --}}
        <h3 style="margin-top:2rem;">
            {{ __('tienda.productos_show.customer_reviews') }}
        </h3>

        @forelse ($producto->resenas as $resena)

            <div
                style="
                    padding:1rem;
                    margin-bottom:0.8rem;
                    border:1px solid #e5d9d1;
                    border-radius:8px;
                    background:#fff;
                "
            >

                <strong>
                    {{ $resena->usuario?->name
                        ?? (__('tienda.productos_show.user_unavailable')) }}
                </strong>

                <span class="estrella" style="margin-left:0.5rem;">
                    {{ str_repeat('★', max(0, min(5, (int) $resena->calificacion))) }}
                </span>

                <p style="margin-top:0.5rem;">
                    {{ $resena->comentario }}
                </p>

            </div>

        @empty

            <p class="vacio">
                {{ __('tienda.productos_show.this_product_has_no_reviews_yet') }}
            </p>

        @endforelse

    </div>

@endsection

@extends('layouts.app')

@section('titulo', __('tienda.productos_index.catalog'))

@section('contenido')


    <h2>
        {{ __('tienda.productos_index.product_catalog') }}
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

    {{-- BUSCADOR --}}
    <form
        action="{{ route('productos.buscar') }}"
        method="GET"
        style="margin-bottom:1rem;"
    >
        <input
            type="search"
            name="q"
            value="{{ $termino ?? '' }}"
            placeholder="{{ __('tienda.productos_index.search_by_name_e_g_sofa') }}"
            aria-label="{{ __('tienda.productos_index.search_products') }}"
            style="width:280px;"
        >

        <button type="submit" class="boton">
            {{ __('tienda.productos_index.search') }}
        </button>

        @if (!empty($termino))
            <a
                href="{{ route('productos.index') }}"
                class="boton boton-secundario"
            >
                {{ __('tienda.productos_index.clear') }}
            </a>
        @endif
    </form>

    {{-- MENSAJE CUANDO NO HAY PRODUCTOS --}}
    @if ($productos->isEmpty())

        <p class="vacio">
            @if (!empty($termino))
                {{ __('tienda.productos_index.no_products_found_for') }}
                "{{ $termino }}".
            @else
                {{ __('tienda.productos_index.there_are_no_products_available_in') }}
            @endif
        </p>

    @else

        {{-- COMPARACIÓN DE PRODUCTOS --}}
        <div id="form-comparar">

            <p class="vacio">
                {{ __('tienda.productos_index.select_2_or_3_products_to') }}
            </p>

            <div class="grid">

                @foreach ($productos as $producto)

                    @php
                        $nombreTraducido = $producto->nombre_localizado;

                        $categoriaTraducida = $producto->categoria?->nombre_localizado ?? __('tienda.productos_index.no_category');

                        $ambienteTraducido = $producto->ambiente?->nombre_localizado ?? __('tienda.productos_index.no_room');
                    @endphp

                    <div class="producto-card">

                        {{-- SELECCIONAR PARA COMPARAR --}}
                        <label
                            style="
                                display:flex;
                                align-items:center;
                                gap:0.4rem;
                                margin-bottom:0.5rem;
                                font-weight:600;
                            "
                        >
                            <input
                                type="checkbox"
                                name="ids[]"
                                value="{{ $producto->id }}"
                                class="check-comparar"
                            >

                            {{ __('tienda.productos_index.compare') }}
                        </label>

                        {{-- IMAGEN --}}
                        <img
                            src="{{ $producto->imagen ?: 'https://placehold.co/400x300?text=Sin+imagen' }}"
                            alt="{{ $nombreTraducido }}"
                            loading="lazy"
                            style="
                                width:100%;
                                height:140px;
                                object-fit:cover;
                                border-radius:6px;
                                margin-bottom:0.5rem;
                            "
                        >

                        {{-- CATEGORÍA --}}
                        <span class="etiqueta">
                            {{ $categoriaTraducida }}
                        </span>

                        {{-- AMBIENTE --}}
                        <span class="etiqueta">
                            {{ $ambienteTraducido }}
                        </span>

                        {{-- NOMBRE TRADUCIDO --}}
                        <h3>
                            {{ $nombreTraducido }}
                        </h3>

                        {{-- PRECIO --}}
                        <p class="precio">
                            ${{ number_format($producto->precio, 0, ',', '.') }}
                        </p>

                        {{-- STOCK --}}
                        <p style="font-size:0.85rem;">
                            {{ __('tienda.productos_index.available_stock') }}
                            {{ $producto->stock }}
                        </p>

                        {{-- VER DETALLE --}}
                        <a
                            href="{{ route('productos.show', $producto) }}"
                            class="boton"
                        >
                            {{ __('tienda.productos_index.view_details') }}
                        </a>

                        {{-- CARRITO --}}
                        @auth

                            @if ($producto->stock > 0)

                                <form
                                    action="{{ route('carrito.agregar', $producto) }}"
                                    method="POST"
                                    style="margin-top:0.5rem;"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="cantidad"
                                        value="1"
                                    >

                                    <button
                                        type="submit"
                                        class="boton"
                                    >
                                        {{ __('tienda.productos_index.add_to_cart') }}
                                    </button>
                                </form>

                            @else

                                <p class="vacio">
                                    {{ __('tienda.productos_index.out_of_stock') }}
                                </p>

                            @endif

                        @else

                            <a
                                href="{{ route('login') }}"
                                class="boton boton-secundario"
                                style="margin-top:0.5rem;"
                            >
                                {{ __('tienda.productos_index.log_in_to_purchase') }}
                            </a>

                        @endauth

                    </div>

                @endforeach

            </div>

            {{-- BOTÓN DE COMPARACIÓN --}}
            <button
                type="button"
                class="boton"
                style="margin-top:1rem;"
                id="btn-comparar"
                disabled
            >
                {{ __('tienda.productos_index.compare_selected_products') }}
            </button>

        </div>

        {{-- PAGINACIÓN --}}
        @if ($productos->hasPages())

            <nav
                aria-label="{{ __('tienda.productos_index.product_pagination') }}"
                style="
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    flex-wrap:wrap;
                    gap:1rem;
                    margin:2rem 0;
                "
            >

                {{-- PÁGINA ANTERIOR --}}
                @if ($productos->onFirstPage())

                    <span
                        class="boton boton-secundario"
                        style="opacity:0.5;"
                    >
                        {{ __('tienda.productos_index.previous') }}
                    </span>

                @else

                    <a
                        href="{{ $productos->previousPageUrl() }}"
                        class="boton boton-secundario"
                    >
                        {{ __('tienda.productos_index.previous') }}
                    </a>

                @endif

                {{-- PÁGINA ACTUAL --}}
                <span style="font-weight:600;">
                    {{ __('tienda.productos_index.page') }}
                    {{ $productos->currentPage() }}
                    {{ __('tienda.productos_index.of') }}
                    {{ $productos->lastPage() }}
                </span>

                {{-- PÁGINA SIGUIENTE --}}
                @if ($productos->hasMorePages())

                    <a
                        href="{{ $productos->nextPageUrl() }}"
                        class="boton boton-secundario"
                    >
                        {{ __('tienda.productos_index.next') }}
                    </a>

                @else

                    <span
                        class="boton boton-secundario"
                        style="opacity:0.5;"
                    >
                        {{ __('tienda.productos_index.next') }}
                    </span>

                @endif

            </nav>

        @endif

        {{-- INFORMACIÓN DE RESULTADOS --}}
        <p
            style="
                text-align:center;
                font-size:0.85rem;
                margin-bottom:1.5rem;
            "
        >
            {{ __('tienda.productos_index.showing') }}
            {{ $productos->firstItem() }}
            {{ __('tienda.productos_index.to') }}
            {{ $productos->lastItem() }}
            {{ __('tienda.productos_index.of') }}
            {{ $productos->total() }}
            {{ __('tienda.productos_index.products') }}
        </p>

    @endif

    {{-- JAVASCRIPT PARA COMPARACIÓN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const casillas = document.querySelectorAll('.check-comparar');
            const boton = document.getElementById('btn-comparar');

            if (!boton) {
                return;
            }

            function obtenerSeleccionados() {
                return Array.from(
                    document.querySelectorAll('.check-comparar:checked')
                );
            }

            function actualizarBoton() {

                const seleccionados = obtenerSeleccionados();
                const cantidad = seleccionados.length;

                // Permitir entre 2 y 3 productos
                boton.disabled = cantidad < 2 || cantidad > 3;

                casillas.forEach(function (casilla) {

                    // Mantener habilitados los productos marcados
                    if (casilla.checked) {
                        casilla.disabled = false;
                        return;
                    }

                    // Bloquear nuevos productos solo al llegar a 3
                    casilla.disabled = cantidad >= 3;

                });
            }

            casillas.forEach(function (casilla) {
                casilla.addEventListener('change', actualizarBoton);
            });

            boton.addEventListener('click', function () {

                const seleccionados = obtenerSeleccionados();

                if (
                    seleccionados.length < 2 ||
                    seleccionados.length > 3
                ) {
                    return;
                }

                const url = new URL(
                    @json(route('productos.comparar')),
                    window.location.origin
                );

                seleccionados.forEach(function (casilla) {
                    url.searchParams.append('ids[]', casilla.value);
                });

                window.location.href = url.toString();
            });

            actualizarBoton();
        });
    </script>

@endsection

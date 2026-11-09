@extends('layouts.app')

@section('titulo', app()->getLocale() === 'en' ? 'Catalog' : 'Catálogo')

@section('contenido')

    @php
        $ingles = app()->getLocale() === 'en';

        $normalizar = function ($valor) {
            return str_replace(
                [' ', '-'],
                '_',
                \Illuminate\Support\Str::ascii(
                    mb_strtolower(trim($valor))
                )
            );
        };

        $traducirRelacion = function ($nombre, $tipo, $alternativa) use ($normalizar) {
            if (!$nombre) {
                return $alternativa;
            }

            $clave = 'messages.' . $tipo . '.' . $normalizar($nombre);

            return \Illuminate\Support\Facades\Lang::has($clave)
                ? __($clave)
                : $nombre;
        };
    @endphp

    <h2>
        {{ $ingles ? 'Product Catalog' : 'Catálogo de productos' }}
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
            placeholder="{{ $ingles ? 'Search by name... (e.g. sofa)' : 'Buscar por nombre... (ej: sofá)' }}"
            aria-label="{{ $ingles ? 'Search products' : 'Buscar productos' }}"
            style="width:280px;"
        >

        <button type="submit" class="boton">
            {{ $ingles ? 'Search' : 'Buscar' }}
        </button>

        @if (!empty($termino))
            <a
                href="{{ route('productos.index') }}"
                class="boton boton-secundario"
            >
                {{ $ingles ? 'Clear' : 'Limpiar' }}
            </a>
        @endif
    </form>

    {{-- MENSAJE CUANDO NO HAY PRODUCTOS --}}
    @if ($productos->isEmpty())

        <p class="vacio">
            @if (!empty($termino))
                {{ $ingles
                    ? 'No products found for'
                    : 'No se encontraron productos para' }}
                "{{ $termino }}".
            @else
                {{ $ingles
                    ? 'There are no products available in the catalog.'
                    : 'No hay productos disponibles en el catálogo.' }}
            @endif
        </p>

    @else

        {{-- COMPARACIÓN DE PRODUCTOS --}}
        <div id="form-comparar">

            <p class="vacio">
                {{ $ingles
                    ? 'Select 2 or 3 products to compare their specifications.'
                    : 'Marca entre 2 y 3 productos para comparar su ficha técnica.' }}
            </p>

            <div class="grid">

                @foreach ($productos as $producto)

                    @php
                        $nombreTraducido = $producto->nombre_localizado;

                        $categoriaTraducida = $traducirRelacion(
                            $producto->categoria?->nombre,
                            'categories',
                            $ingles ? 'No category' : 'Sin categoría'
                        );

                        $ambienteTraducido = $traducirRelacion(
                            $producto->ambiente?->nombre,
                            'rooms',
                            $ingles ? 'No room' : 'Sin ambiente'
                        );
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

                            {{ $ingles ? 'Compare' : 'Comparar' }}
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
                            {{ $ingles ? 'Available stock:' : 'Stock disponible:' }}
                            {{ $producto->stock }}
                        </p>

                        {{-- VER DETALLE --}}
                        <a
                            href="{{ route('productos.show', $producto) }}"
                            class="boton"
                        >
                            {{ $ingles ? 'View details' : 'Ver detalle' }}
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
                                        {{ $ingles
                                            ? 'Add to cart'
                                            : 'Agregar al carrito' }}
                                    </button>
                                </form>

                            @else

                                <p class="vacio">
                                    {{ $ingles
                                        ? 'Out of stock'
                                        : 'Producto agotado' }}
                                </p>

                            @endif

                        @else

                            <a
                                href="{{ route('login') }}"
                                class="boton boton-secundario"
                                style="margin-top:0.5rem;"
                            >
                                {{ $ingles
                                    ? 'Log in to purchase'
                                    : 'Inicia sesión para comprar' }}
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
                {{ $ingles
                    ? 'Compare selected products'
                    : 'Comparar seleccionados' }}
            </button>

        </div>

        {{-- PAGINACIÓN --}}
        @if ($productos->hasPages())

            <nav
                aria-label="{{ $ingles ? 'Product pagination' : 'Paginación de productos' }}"
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
                        {{ $ingles ? 'Previous' : 'Anterior' }}
                    </span>

                @else

                    <a
                        href="{{ $productos->previousPageUrl() }}"
                        class="boton boton-secundario"
                    >
                        {{ $ingles ? 'Previous' : 'Anterior' }}
                    </a>

                @endif

                {{-- PÁGINA ACTUAL --}}
                <span style="font-weight:600;">
                    {{ $ingles ? 'Page' : 'Página' }}
                    {{ $productos->currentPage() }}
                    {{ $ingles ? 'of' : 'de' }}
                    {{ $productos->lastPage() }}
                </span>

                {{-- PÁGINA SIGUIENTE --}}
                @if ($productos->hasMorePages())

                    <a
                        href="{{ $productos->nextPageUrl() }}"
                        class="boton boton-secundario"
                    >
                        {{ $ingles ? 'Next' : 'Siguiente' }}
                    </a>

                @else

                    <span
                        class="boton boton-secundario"
                        style="opacity:0.5;"
                    >
                        {{ $ingles ? 'Next' : 'Siguiente' }}
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
            {{ $ingles ? 'Showing' : 'Mostrando' }}
            {{ $productos->firstItem() }}
            {{ $ingles ? 'to' : 'a' }}
            {{ $productos->lastItem() }}
            {{ $ingles ? 'of' : 'de' }}
            {{ $productos->total() }}
            {{ $ingles ? 'products' : 'productos' }}
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

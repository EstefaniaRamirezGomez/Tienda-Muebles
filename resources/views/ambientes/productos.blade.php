@extends('layouts.app')


@section('titulo', $ambiente->nombre_localizado)

@section('contenido')

    {{-- Volver a todos los ambientes --}}
    <a href="{{ route('ambientes.index') }}">
        &larr;
        {{ __('tienda.ambientes_productos.all_rooms') }}
    </a>

    {{-- Título --}}
    <h2>
        {{ __('tienda.ambientes_productos.furniture_for') }}
        {{ $ambiente->nombre_localizado }}
    </h2>

    {{-- Sin productos --}}
    @if ($productos->isEmpty())

        <p class="vacio">
            {{ __('tienda.ambientes_productos.there_are_no_products_for_this') }}
        </p>

    @endif

    {{-- Formulario para comparar productos --}}
    <form
        action="{{ route('productos.comparar') }}"
        method="GET"
        id="form-comparar"
    >

        @if ($productos->isNotEmpty())

            <p class="vacio">
                {{ __('tienda.ambientes_productos.select_2_or_3_products_to') }}
            </p>

        @endif

        <div class="grid">

            @foreach ($productos as $producto)

                @php
                    // Nombre traducido desde el modelo Producto
                    $nombreProducto = $producto->nombre_localizado;

                    // Categoría traducida
                    $nombreCategoria = $producto->categoria?->nombre_localizado ?? '';
                @endphp

                <div class="producto-card">

                    {{-- Seleccionar para comparar --}}
                    <label
                        style="
                            display: flex;
                            align-items: center;
                            gap: 0.4rem;
                            margin-bottom: 0.5rem;
                            font-weight: 600;
                        "
                    >

                        <input
                            type="checkbox"
                            name="ids[]"
                            value="{{ $producto->id }}"
                            class="check-comparar"
                        >

                        {{ __('tienda.ambientes_productos.compare') }}

                    </label>

                    {{-- Imagen --}}
                    <img
                        src="{{ $producto->imagen ?: 'https://placehold.co/400x300?text=No+image' }}"
                        alt="{{ $nombreProducto }}"
                        style="
                            width: 100%;
                            height: 140px;
                            object-fit: cover;
                            border-radius: 6px;
                            margin-bottom: 0.5rem;
                        "
                        loading="lazy"
                    >

                    {{-- Categoría --}}
                    @if ($nombreCategoria !== '')

                        <span class="etiqueta">
                            {{ $nombreCategoria }}
                        </span>

                    @endif

                    {{-- Nombre del producto --}}
                    <h3>
                        {{ $nombreProducto }}
                    </h3>

                    {{-- Precio --}}
                    <p class="precio">
                        ${{ number_format($producto->precio, 0, ',', '.') }}
                    </p>

                    {{-- Ver detalle --}}
                    <a
                        href="{{ route('productos.show', $producto) }}"
                        class="boton"
                    >
                        {{ __('tienda.ambientes_productos.view_details') }}
                    </a>

                </div>

            @endforeach

        </div>

        {{-- Botón de comparación --}}
        @if ($productos->isNotEmpty())

            <button
                type="submit"
                class="boton"
                style="margin-top: 1rem;"
                id="btn-comparar"
                disabled
            >
                {{ __('tienda.ambientes_productos.compare_selected_products') }}
            </button>

        @endif

    </form>

    {{-- Lógica para comparar entre 2 y 3 productos --}}
    <script>
        const casillas = document.querySelectorAll(
            '.check-comparar'
        );

        const boton = document.getElementById(
            'btn-comparar'
        );

        function actualizarBoton() {

            const marcados = document.querySelectorAll(
                '.check-comparar:checked'
            ).length;

            if (boton) {
                boton.disabled = marcados < 2;
            }

            casillas.forEach(c => {

                if (!c.checked) {
                    c.disabled = marcados >= 3;
                }

            });
        }

        casillas.forEach(c => {
            c.addEventListener(
                'change',
                actualizarBoton
            );
        });

        actualizarBoton();
    </script>

@endsection

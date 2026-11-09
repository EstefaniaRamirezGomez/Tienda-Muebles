@extends('layouts.app')

@php
    $ingles = app()->getLocale() === 'en';

    // Traducciones de los ambientes
    $traduccionesAmbientes = [
        'cocina' => 'Kitchen',
        'dormitorio' => 'Bedroom',
        'exterior' => 'Outdoor',
        'oficina' => 'Office',
        'sala' => 'Living Room',
        'comedor' => 'Dining Room',
        'baño' => 'Bathroom',
        'terraza' => 'Terrace',
        'jardín' => 'Garden',
        'estudio' => 'Study',
    ];

    // Traducciones de las categorías
    $traduccionesCategorias = [
        'sofás' => 'Sofas',
        'sofá' => 'Sofa',
        'mesas' => 'Tables',
        'mesa' => 'Table',
        'sillas' => 'Chairs',
        'silla' => 'Chair',
        'lámparas' => 'Lamps',
        'lámpara' => 'Lamp',
        'camas' => 'Beds',
        'cama' => 'Bed',
        'escritorios' => 'Desks',
        'escritorio' => 'Desk',
        'decoración' => 'Decoration',
        'muebles' => 'Furniture',
        'accesorios' => 'Accessories',
    ];

    // Nombre localizado del ambiente
    $nombreAmbienteOriginal = $ambiente->nombre;

    $claveAmbiente = mb_strtolower(
        trim($nombreAmbienteOriginal)
    );

    $nombreAmbiente = $ingles
        ? ($traduccionesAmbientes[$claveAmbiente] ?? $nombreAmbienteOriginal)
        : $nombreAmbienteOriginal;
@endphp

@section('titulo', $nombreAmbiente)

@section('contenido')

    {{-- Volver a todos los ambientes --}}
    <a href="{{ route('ambientes.index') }}">
        &larr;
        {{ $ingles ? 'All Rooms' : 'Todos los ambientes' }}
    </a>

    {{-- Título --}}
    <h2>
        {{ $ingles ? 'Furniture for:' : 'Muebles para:' }}
        {{ $nombreAmbiente }}
    </h2>

    {{-- Sin productos --}}
    @if ($productos->isEmpty())

        <p class="vacio">
            {{ $ingles
                ? 'There are no products for this room yet.'
                : 'Todavía no hay productos para este ambiente.' }}
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
                {{ $ingles
                    ? 'Select 2 or 3 products to compare their technical specifications.'
                    : 'Marca entre 2 y 3 productos para comparar su ficha técnica.' }}
            </p>

        @endif

        <div class="grid">

            @foreach ($productos as $producto)

                @php
                    // Nombre traducido desde el modelo Producto
                    $nombreProducto = $producto->nombre_localizado;

                    // Categoría traducida
                    $nombreCategoriaOriginal = $producto->categoria?->nombre ?? '';

                    $claveCategoria = mb_strtolower(
                        trim($nombreCategoriaOriginal)
                    );

                    $nombreCategoria = $ingles
                        ? ($traduccionesCategorias[$claveCategoria] ?? $nombreCategoriaOriginal)
                        : $nombreCategoriaOriginal;
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

                        {{ $ingles ? 'Compare' : 'Comparar' }}

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
                        {{ $ingles ? 'View Details' : 'Ver detalle' }}
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
                {{ $ingles
                    ? 'Compare Selected Products'
                    : 'Comparar seleccionados' }}
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

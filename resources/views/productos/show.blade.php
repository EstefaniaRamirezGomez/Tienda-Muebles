@extends('layouts.app')

@php
    $ingles = app()->getLocale() === 'en';

    // Traducciones de categorías
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

    // Traducciones de ambientes
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

    // Nombre y descripción traducidos desde el modelo
    $nombreProducto = $producto->nombre_localizado;
    $descripcionProducto = $producto->descripcion_localizada;

    // Categoría
    $categoriaOriginal = $producto->categoria?->nombre;

    $claveCategoria = mb_strtolower(
        trim($categoriaOriginal ?? '')
    );

    $nombreCategoria = $ingles
        ? ($traduccionesCategorias[$claveCategoria] ?? $categoriaOriginal)
        : $categoriaOriginal;

    // Ambiente
    $ambienteOriginal = $producto->ambiente?->nombre;

    $claveAmbiente = mb_strtolower(
        trim($ambienteOriginal ?? '')
    );

    $nombreAmbiente = $ingles
        ? ($traduccionesAmbientes[$claveAmbiente] ?? $ambienteOriginal)
        : $ambienteOriginal;
@endphp

@section('titulo', $nombreProducto)

@section('contenido')

    {{-- Volver al catálogo --}}
    <a href="{{ route('productos.index') }}">
        &larr;
        {{ $ingles ? 'Back to Catalog' : 'Volver al catálogo' }}
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
                {{ $nombreCategoria ?: ($ingles ? 'Uncategorized' : 'Sin categoría') }}
            </span>

            <span class="etiqueta">
                {{ $nombreAmbiente ?: ($ingles ? 'No room assigned' : 'Sin ambiente') }}
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
                {{ $ingles ? 'Available Stock:' : 'Stock disponible:' }}
            </strong>

            {{ $producto->stock }}
        </p>

        <p>
            <strong>
                {{ $ingles ? 'Average Rating:' : 'Calificación promedio:' }}
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
                        {{ $ingles ? 'Quantity:' : 'Cantidad:' }}
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
                        {{ $ingles ? 'Add to Cart' : 'Agregar al carrito' }}
                    </button>

                </form>

            @else

                <p class="vacio">
                    {{ $ingles
                        ? 'This product is out of stock.'
                        : 'Este producto está agotado.' }}
                </p>

            @endif

        @else

            <div style="margin:1rem 0 1.5rem;">

                <a
                    href="{{ route('login') }}"
                    class="boton"
                >
                    {{ $ingles
                        ? 'Log in to add to cart'
                        : 'Inicia sesión para agregar al carrito' }}
                </a>

            </div>

        @endauth

        {{-- Ficha técnica --}}
        @if ($producto->especificacion)

            <h3>
                {{ $ingles ? 'Technical Specifications' : 'Ficha técnica' }}
            </h3>

            <table>

                <tr>
                    <th>
                        {{ $ingles ? 'Material' : 'Material' }}
                    </th>

                    <td>
                        {{ $producto->especificacion->material }}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ $ingles ? 'Color' : 'Color' }}
                    </th>

                    <td>
                        {{ $producto->especificacion->color }}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ $ingles
                            ? 'Dimensions (W x H x D)'
                            : 'Dimensiones (A x Al x P)' }}
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
                        {{ $ingles ? 'Weight' : 'Peso' }}
                    </th>

                    <td>
                        {{ $producto->especificacion->peso_kg }} kg
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ $ingles
                            ? 'Assembly Required'
                            : 'Requiere ensamblaje' }}
                    </th>

                    <td>
                        {{ $ingles
                            ? ($producto->especificacion->requiere_ensamblaje ? 'Yes' : 'No')
                            : ($producto->especificacion->requiere_ensamblaje ? 'Sí' : 'No') }}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ $ingles ? 'Warranty' : 'Garantía' }}
                    </th>

                    <td>
                        {{ $producto->especificacion->garantia_meses }}

                        {{ $ingles
                            ? ($producto->especificacion->garantia_meses == 1 ? 'month' : 'months')
                            : ($producto->especificacion->garantia_meses == 1 ? 'mes' : 'meses') }}
                    </td>
                </tr>

            </table>

            <a
                class="boton boton-secundario"
                style="margin-top:0.8rem;"
                href="{{ route('productos.index') }}"
            >
                {{ $ingles
                    ? 'Compare with Other Products'
                    : 'Comparar con otros productos' }}
            </a>

        @endif

        {{-- Reseñas --}}
        <h3 style="margin-top:2rem;">
            {{ $ingles ? 'Customer Reviews' : 'Reseñas de clientes' }}
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
                        ?? ($ingles ? 'User unavailable' : 'Usuario no disponible') }}
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
                {{ $ingles
                    ? 'This product has no reviews yet.'
                    : 'Este producto todavía no tiene reseñas.' }}
            </p>

        @endforelse

    </div>

@endsection

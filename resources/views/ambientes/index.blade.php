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
@endphp

@section('titulo', $ingles ? 'Rooms' : 'Ambientes')

@section('contenido')

    <h2>
        {{ $ingles ? 'Explore by Room' : 'Explora por ambiente' }}
    </h2>

    <div class="grid">

        @foreach ($ambientes as $ambiente)

            @php
                $nombreOriginal = $ambiente->nombre;

                $clave = mb_strtolower(trim($nombreOriginal));

                $nombreTraducido = $ingles
                    ? ($traduccionesAmbientes[$clave] ?? $nombreOriginal)
                    : $nombreOriginal;

                $cantidad = (int) $ambiente->productos_count;
            @endphp

            <div class="producto-card">

                {{-- Nombre del ambiente --}}
                <h3>
                    {{ $nombreTraducido }}
                </h3>

                {{-- Cantidad de productos --}}
                <p>
                    {{ $cantidad }}

                    @if ($ingles)
                        {{ $cantidad === 1 ? 'product' : 'products' }}
                    @else
                        {{ $cantidad === 1 ? 'producto' : 'productos' }}
                    @endif
                </p>

                {{-- Ver productos del ambiente --}}
                <a
                    class="boton"
                    href="{{ route('ambientes.productos', $ambiente) }}"
                >
                    {{ $ingles ? 'View Products' : 'Ver productos' }}
                </a>

            </div>

        @endforeach

    </div>

@endsection

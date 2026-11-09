@extends('layouts.app')

@php
    $ingles = app()->getLocale() === 'en';
@endphp

@section('titulo', $ingles ? 'Featured Products' : 'Destacados')

@section('contenido')

    <h2>
        {{ $ingles ? 'Featured Products' : 'Productos destacados' }}
    </h2>

    <style>
        .destacados-grid {
            display: grid;
            grid-template-columns: repeat(
                auto-fit,
                minmax(220px, 1fr)
            );
            gap: 1.25rem;
            margin-top: 1.25rem;
        }

        .destacado-card {
            background: #ffffff;
            border: 1px solid #e5d9d1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
        }

        .destacado-card:hover {
            transform: translateY(-4px);
        }

        .destacado-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .destacado-contenido {
            padding: 1rem;
        }

        .destacado-contenido h4 {
            margin: 0 0 0.65rem;
            font-size: 1.05rem;
        }

        .destacado-contenido p {
            margin: 0.5rem 0;
        }

        .destacado-contenido .boton {
            display: inline-block;
            margin-top: 0.75rem;
            text-decoration: none;
        }

        .destacado-estadistica {
            font-size: 0.9rem;
            color: #76554a;
            font-weight: 600;
        }

        .destacado-seccion {
            margin-bottom: 1.5rem;
        }
    </style>

    {{-- PRODUCTOS MÁS VENDIDOS --}}
    <section class="tarjeta destacado-seccion">

        <h3>
            🔥 {{ $ingles ? 'Best Sellers' : 'Más vendidos' }}
        </h3>

        <div class="destacados-grid">

            @forelse ($masVendidos as $fila)

                @if ($fila->producto)

                    <article class="destacado-card">

                        {{-- Imagen --}}
                        <img
                            src="{{ $fila->producto->imagen ?: 'https://placehold.co/400x300?text=No+image' }}"
                            alt="{{ $fila->producto->nombre_localizado }}"
                            loading="lazy"
                        >

                        <div class="destacado-contenido">

                            {{-- Nombre traducido --}}
                            <h4>
                                {{ $fila->producto->nombre_localizado }}
                            </h4>

                            {{-- Precio --}}
                            <p class="precio">
                                ${{ number_format($fila->producto->precio, 0, ',', '.') }}
                            </p>

                            {{-- Unidades vendidas --}}
                            <p class="destacado-estadistica">
                                🔥 {{ $fila->unidades_vendidas }}

                                {{ $ingles
                                    ? ((int) $fila->unidades_vendidas === 1
                                        ? 'unit sold'
                                        : 'units sold')
                                    : ((int) $fila->unidades_vendidas === 1
                                        ? 'unidad vendida'
                                        : 'unidades vendidas') }}
                            </p>

                            {{-- Ver detalle --}}
                            <a
                                href="{{ route('productos.show', $fila->producto) }}"
                                class="boton"
                            >
                                {{ $ingles ? 'View Product' : 'Ver producto' }}
                            </a>

                        </div>

                    </article>

                @endif

            @empty

                <p class="vacio">
                    {{ $ingles
                        ? 'There are no registered orders yet.'
                        : 'Todavía no hay pedidos registrados.' }}
                </p>

            @endforelse

        </div>

    </section>

    {{-- PRODUCTOS MEJOR CALIFICADOS --}}
    <section class="tarjeta destacado-seccion">

        <h3>
            ⭐ {{ $ingles ? 'Top Rated' : 'Mejor calificados' }}
        </h3>

        <div class="destacados-grid">

            @forelse ($mejorCalificados as $producto)

                <article class="destacado-card">

                    {{-- Imagen --}}
                    <img
                        src="{{ $producto->imagen ?: 'https://placehold.co/400x300?text=No+image' }}"
                        alt="{{ $producto->nombre_localizado }}"
                        loading="lazy"
                    >

                    <div class="destacado-contenido">

                        {{-- Nombre traducido --}}
                        <h4>
                            {{ $producto->nombre_localizado }}
                        </h4>

                        {{-- Precio --}}
                        <p class="precio">
                            ${{ number_format($producto->precio, 0, ',', '.') }}
                        </p>

                        {{-- Calificación --}}
                        <p class="destacado-estadistica">
                            <span class="estrella">
                                ★ {{ number_format((float) $producto->resenas_avg_calificacion, 1) }}
                            </span>
                            / 5
                        </p>

                        {{-- Ver detalle --}}
                        <a
                            href="{{ route('productos.show', $producto) }}"
                            class="boton"
                        >
                            {{ $ingles ? 'View Product' : 'Ver producto' }}
                        </a>

                    </div>

                </article>

            @empty

                <p class="vacio">
                    {{ $ingles
                        ? 'There are no reviews yet.'
                        : 'Todavía no hay reseñas registradas.' }}
                </p>

            @endforelse

        </div>

    </section>

@endsection

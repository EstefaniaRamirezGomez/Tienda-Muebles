@extends('layouts.app')


@section('titulo', __('tienda.productos_destacados.featured_products'))

@section('contenido')

    <h2>
        {{ __('tienda.productos_destacados.featured_products_2') }}
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
            🔥 {{ __('tienda.productos_destacados.best_sellers') }}
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
                                🔥 {{ trans_choice('tienda.comun.unidades_vendidas', $fila->unidades_vendidas, ['cantidad' => $fila->unidades_vendidas]) }}
                            </p>

                            {{-- Ver detalle --}}
                            <a
                                href="{{ route('productos.show', $fila->producto) }}"
                                class="boton"
                            >
                                {{ __('tienda.productos_destacados.view_product') }}
                            </a>

                        </div>

                    </article>

                @endif

            @empty

                <p class="vacio">
                    {{ __('tienda.productos_destacados.there_are_no_registered_orders_yet') }}
                </p>

            @endforelse

        </div>

    </section>

    {{-- PRODUCTOS MEJOR CALIFICADOS --}}
    <section class="tarjeta destacado-seccion">

        <h3>
            ⭐ {{ __('tienda.productos_destacados.top_rated') }}
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
                            {{ __('tienda.productos_destacados.view_product') }}
                        </a>

                    </div>

                </article>

            @empty

                <p class="vacio">
                    {{ __('tienda.productos_destacados.there_are_no_reviews_yet') }}
                </p>

            @endforelse

        </div>

    </section>

@endsection

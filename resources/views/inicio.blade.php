@extends('layouts.app')

@section('titulo', __('messages.home'))

@section('contenido')

    {{-- BUSCADOR --}}
    <section style="margin-bottom:2rem;">

        <form
            action="{{ route('productos.buscar') }}"
            method="GET"
            style="
                display:flex;
                gap:0.5rem;
                flex-wrap:wrap;
            "
        >

            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="{{ __('messages.search_placeholder') }}"
                aria-label="{{ __('messages.search_placeholder') }}"
                style="
                    flex:1;
                    min-width:200px;
                "
            >

            <button type="submit" class="boton">
                {{ __('messages.search') }}
            </button>

        </form>

    </section>


    {{-- PRESENTACIÓN --}}
    <section
        class="tarjeta"
        style="
            text-align:center;
            padding:3rem 2rem;
        "
    >

        <h2 style="
            font-size:2rem;
            margin-bottom:0.5rem;
        ">
            {{ __('messages.hero_title') }}
        </h2>

        <p style="
            max-width:600px;
            margin:0 auto 1.5rem;
            color:#6f625b;
        ">
            {{ __('messages.hero_description') }}
        </p>

        <div style="
            display:flex;
            justify-content:center;
            gap:0.5rem;
            flex-wrap:wrap;
        ">

            <a
                href="{{ route('productos.index') }}"
                class="boton"
            >
                {{ __('messages.view_catalog') }}
            </a>

            <a
                href="{{ route('ambientes.index') }}"
                class="boton boton-secundario"
            >
                {{ __('messages.explore_rooms') }}
            </a>

        </div>

    </section>


    {{-- PRODUCTOS --}}
    <section style="margin-top:2.5rem;">

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:1rem;
        ">

            <h2>
                {{ __('messages.products') }}
            </h2>

            <a
                href="{{ route('productos.index') }}"
                style="color:var(--terracota-oscuro);"
            >
                {{ __('messages.view_all') }} &rarr;
            </a>

        </div>

        <div class="grid">

            @forelse ($productos as $producto)

                @php
                    $nombreTraducido = $producto->nombre_localizado;
                    $categoriaTraducida = $producto->categoria?->nombre_localizado ?? __('messages.no_category');
                    $ambienteTraducido = $producto->ambiente?->nombre_localizado ?? __('messages.no_room');
                @endphp


                <div class="producto-card">

                    {{-- IMAGEN DEL PRODUCTO --}}
                    <img
                        src="{{ $producto->imagen ?: 'https://placehold.co/400x300?text=Sin+imagen' }}"
                        alt="{{ $nombreTraducido }}"
                        loading="lazy"
                        style="
                            width:100%;
                            height:160px;
                            object-fit:cover;
                            border-radius:6px;
                        "
                    >


                    {{-- CATEGORÍA Y AMBIENTE --}}
                    <p style="margin-top:0.7rem;">

                        <span class="etiqueta">
                            {{ $categoriaTraducida }}
                        </span>

                        <span class="etiqueta">
                            {{ $ambienteTraducido }}
                        </span>

                    </p>


                    {{-- NOMBRE DEL PRODUCTO SEGÚN IDIOMA --}}
                    <h3>
                        {{ $nombreTraducido }}
                    </h3>


                    {{-- PRECIO --}}
                    <p class="precio">
                        ${{ number_format(
                            $producto->precio,
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>


                    {{-- VER DETALLE --}}
                    <a
                        href="{{ route('productos.show', $producto) }}"
                        class="boton"
                    >
                        {{ __('messages.details') }}
                    </a>

                </div>

            @empty

                {{-- SIN PRODUCTOS --}}
                <p class="vacio">
                    {{ __('messages.no_products') }}
                </p>

            @endforelse

        </div>

    </section>

@endsection

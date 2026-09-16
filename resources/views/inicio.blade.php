@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')

    {{-- BUSCADOR --}}
    <section style="margin-bottom:2rem;">
        <form action="{{ route('productos.buscar') }}"
              method="GET"
              style="display:flex; gap:0.5rem;">

            <input
                type="search"
                name="q"
                placeholder="¿Qué estás buscando?"
                style="flex:1;"
            >

            <button type="submit" class="boton">
                Buscar
            </button>
        </form>
    </section>


    {{-- PRESENTACIÓN --}}
    <section class="tarjeta" style="text-align:center; padding:3rem 2rem;">
        <h2 style="font-size:2rem; margin-bottom:0.5rem;">
            Muebles para cada espacio
        </h2>

        <p style="max-width:600px; margin:0 auto 1.5rem; color:#6f625b;">
            Encuentra muebles y decoración para transformar los espacios de tu hogar.
        </p>

        <a href="{{ route('productos.index') }}" class="boton">
            Ver catálogo
        </a>

        <a href="{{ route('ambientes.index') }}" class="boton boton-secundario">
            Explorar ambientes
        </a>
    </section>


    {{-- PRODUCTOS --}}
    <section style="margin-top:2.5rem;">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Productos</h2>

            <a href="{{ route('productos.index') }}"
               style="color:var(--terracota-oscuro);">
                Ver todos →
            </a>
        </div>

        <div class="grid">

            @forelse ($productos as $producto)

                <div class="producto-card">

                    <img
                        src="{{ $producto->imagen ?? 'https://placehold.co/400x300?text=Sin+imagen' }}"
                        alt="{{ $producto->nombre }}"
                        style="width:100%; height:160px; object-fit:cover; border-radius:6px;"
                    >

                    <p style="margin-top:0.7rem;">
                        <span class="etiqueta">
                            {{ $producto->categoria->nombre }}
                        </span>

                        <span class="etiqueta">
                            {{ $producto->ambiente->nombre }}
                        </span>
                    </p>

                    <h3>{{ $producto->nombre }}</h3>

                    <p class="precio">
                        ${{ number_format($producto->precio, 0, ',', '.') }}
                    </p>

                    <a href="{{ route('productos.show', $producto) }}"
                       class="boton">
                        Ver detalle
                    </a>

                </div>

            @empty

                <p class="vacio">
                    No hay productos disponibles.
                </p>

            @endforelse

        </div>
    </section>

@endsection
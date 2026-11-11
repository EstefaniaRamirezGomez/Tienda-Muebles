@extends('layouts.app')


@section('titulo', __('tienda.ambientes_index.rooms'))

@section('contenido')

    <h2>
        {{ __('tienda.ambientes_index.explore_by_room') }}
    </h2>

    <div class="grid">

        @foreach ($ambientes as $ambiente)

            

            <div class="producto-card">

                {{-- Nombre del ambiente --}}
                <h3>
                    {{ $ambiente->nombre_localizado }}
                </h3>

                {{-- Cantidad de productos --}}
                <p>
                    {{ trans_choice('tienda.comun.productos', $ambiente->productos_count, ['cantidad' => $ambiente->productos_count]) }}
                </p>

                {{-- Ver productos del ambiente --}}
                <a
                    class="boton"
                    href="{{ route('ambientes.productos', $ambiente) }}"
                >
                    {{ __('tienda.ambientes_index.view_products') }}
                </a>

            </div>

        @endforeach

    </div>

@endsection

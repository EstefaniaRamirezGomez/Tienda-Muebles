@extends('layouts.app')

@section('titulo', __('tienda.aliados.titulo'))

@section('contenido')

    <h2>{{ __('tienda.aliados.titulo') }}</h2>

    <p>
        {{ __('tienda.aliados.descripcion', ['equipo' => $nombreAliado ?: __('tienda.aliados.equipo_aliado')]) }}
    </p>

    @if ($productos === null)

        <div class="mensaje-error">
            {{ __('tienda.aliados.no_disponible') }}
        </div>

    @elseif (empty($productos))

        <p class="vacio">{{ __('tienda.aliados.sin_productos') }}</p>

    @else

        <div class="grid">

            @foreach ($productos as $producto)

                <div class="producto-card">

                    @if ($producto['imagen'])
                        <img
                            src="{{ $producto['imagen'] }}"
                            alt="{{ $producto['nombre'] }}"
                            loading="lazy"
                            style="width:100%; height:160px; object-fit:cover; border-radius:6px;"
                        >
                    @endif

                    <h3 style="margin-top:0.7rem;">{{ $producto['nombre'] }}</h3>

                    @if ($producto['descripcion'])
                        <p>{{ \Illuminate\Support\Str::limit($producto['descripcion'], 100) }}</p>
                    @endif

                    @if (is_numeric($producto['precio']))
                        <p class="precio">${{ number_format((float) $producto['precio'], 0, ',', '.') }}</p>
                    @elseif ($producto['precio'])
                        <p class="precio">{{ $producto['precio'] }}</p>
                    @endif

                    @if ($producto['url'])
                        <a href="{{ $producto['url'] }}" class="boton" target="_blank" rel="noopener">
                            {{ __('tienda.aliados.ver_en_tienda') }}
                        </a>
                    @endif

                </div>

            @endforeach

        </div>

    @endif

@endsection

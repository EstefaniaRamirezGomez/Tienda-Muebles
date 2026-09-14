@extends('layouts.app')

@section('titulo', 'Destacados')

@section('contenido')
    <h2>Productos destacados</h2>

    <div class="tarjeta">
        <h3>🔥 Más vendidos</h3>
        @forelse ($masVendidos as $fila)
            <p>
                <a href="{{ route('productos.show', $fila->producto_id) }}">{{ $fila->producto->nombre }}</a>
                — {{ $fila->unidades_vendidas }} unidades vendidas
            </p>
        @empty
            <p class="vacio">Todavía no hay pedidos registrados.</p>
        @endforelse
    </div>

    <div class="tarjeta" style="margin-top:1rem;">
        <h3>⭐ Mejor calificados</h3>
        @forelse ($mejorCalificados as $producto)
            <p>
                <a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a>
                — <span class="estrella">★ {{ round($producto->resenas_avg_calificacion, 1) }}</span>
            </p>
        @empty
            <p class="vacio">Todavía no hay reseñas registradas.</p>
        @endforelse
    </div>
@endsection
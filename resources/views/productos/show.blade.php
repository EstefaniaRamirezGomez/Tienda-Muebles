@extends('layouts.app')

@section('titulo', $producto->nombre)

@section('contenido')
    <a href="{{ route('productos.index') }}">&larr; Volver al catálogo</a>

    <div class="tarjeta" style="margin-top:1rem;">
        <span class="etiqueta">{{ $producto->categoria->nombre }}</span>
        <span class="etiqueta">{{ $producto->ambiente->nombre }}</span>

        <img src="{{ $producto->imagen ?? 'https://placehold.co/600x400?text=Sin+imagen' }}"
         alt="{{ $producto->nombre }}"
         style="width:100%; max-width:400px; border-radius:8px; margin:1rem 0;">

        <h2>{{ $producto->nombre }}</h2>
        <p>{{ $producto->descripcion }}</p>
        <p class="precio" style="font-size:1.3rem;">${{ number_format($producto->precio, 0, ',', '.') }}</p>
        <p>Stock disponible: {{ $producto->stock }} · Calificación promedio:
            <span class="estrella">★ {{ $producto->calificacionPromedio() }}</span>
        </p>

        @if ($producto->especificacion)
            <h3>Ficha técnica</h3>
            <table>
                <tr><th>Material</th><td>{{ $producto->especificacion->material }}</td></tr>
                <tr><th>Color</th><td>{{ $producto->especificacion->color }}</td></tr>
                <tr><th>Dimensiones (A x Al x P)</th>
                    <td>{{ $producto->especificacion->ancho_cm }} x {{ $producto->especificacion->alto_cm }} x {{ $producto->especificacion->profundidad_cm }} cm</td></tr>
                <tr><th>Peso</th><td>{{ $producto->especificacion->peso_kg }} kg</td></tr>
                <tr><th>Requiere ensamblaje</th><td>{{ $producto->especificacion->requiere_ensamblaje ? 'Sí' : 'No' }}</td></tr>
                <tr><th>Garantía</th><td>{{ $producto->especificacion->garantia_meses }} meses</td></tr>
            </table>
            <a class="boton boton-secundario" style="margin-top:0.8rem;"
               href="{{ route('productos.comparar', ['ids' => $producto->id]) }}">
                Comparar este producto
            </a>
        @endif

        <h3>Reseñas</h3>
        @forelse ($producto->resenas as $resena)
            <p><strong>{{ $resena->usuario->name }}</strong> — <span class="estrella">{{ str_repeat('★', $resena->calificacion) }}</span><br>
                {{ $resena->comentario }}</p>
        @empty
            <p class="vacio">Todavía no tiene reseñas.</p>
        @endforelse
    </div>
@endsection
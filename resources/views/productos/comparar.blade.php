@extends('layouts.app')

@section('titulo', 'Comparador de muebles')

@section('contenido')
    <h2>★ Comparador de muebles</h2>
    <p>Elige entre 2 y 3 productos y compara su ficha técnica lado a lado.
       Prueba con la URL <code>?ids=1,2,3</code> usando ids reales de tu catálogo.</p>

    @if ($productos->count() < 2)
        <p class="vacio">Selecciona al menos 2 productos para comparar (agrega <code>?ids=1,2</code> a la URL).</p>
    @else
        <table>
            <tr>
                <th>Producto</th>
                @foreach ($productos as $producto)
                    <th>{{ $producto->nombre }}</th>
                @endforeach
            </tr>
            <tr>
                <th>Precio</th>
                @foreach ($productos as $producto)
                    <td>${{ number_format($producto->precio, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <th>Material</th>
                @foreach ($productos as $producto)
                    <td>{{ $producto->especificacion->material ?? '—' }}</td>
                @endforeach
            </tr>
            <tr>
                <th>Color</th>
                @foreach ($productos as $producto)
                    <td>{{ $producto->especificacion->color ?? '—' }}</td>
                @endforeach
            </tr>
            <tr>
                <th>Dimensiones (A x Al x P)</th>
                @foreach ($productos as $producto)
                    <td>
                        @if ($producto->especificacion)
                            {{ $producto->especificacion->ancho_cm }} x {{ $producto->especificacion->alto_cm }} x {{ $producto->especificacion->profundidad_cm }} cm
                        @else
                            —
                        @endif
                    </td>
                @endforeach
            </tr>
            <tr>
                <th>Peso</th>
                @foreach ($productos as $producto)
                    <td>{{ $producto->especificacion->peso_kg ?? '—' }} kg</td>
                @endforeach
            </tr>
            <tr>
                <th>Requiere ensamblaje</th>
                @foreach ($productos as $producto)
                    <td>{{ ($producto->especificacion?->requiere_ensamblaje ?? false) ? 'Sí' : 'No' }}</td>
                @endforeach
            </tr>
            <tr>
                <th>Garantía</th>
                @foreach ($productos as $producto)
                    <td>{{ $producto->especificacion->garantia_meses ?? '—' }} meses</td>
                @endforeach
            </tr>
        </table>
    @endif
@endsection
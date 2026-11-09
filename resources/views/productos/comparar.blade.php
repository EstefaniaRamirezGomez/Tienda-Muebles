@extends('layouts.app')

@section('titulo', app()->getLocale() === 'en'
    ? 'Furniture Comparison'
    : 'Comparador de muebles'
)

@section('contenido')

    @php
        $ingles = app()->getLocale() === 'en';

        // Traducciones básicas para materiales y colores.
        $traducciones = [
            'madera' => 'Wood',
            'metal' => 'Metal',
            'vidrio' => 'Glass',
            'plástico' => 'Plastic',
            'plastico' => 'Plastic',
            'cuero' => 'Leather',
            'tela' => 'Fabric',
            'acero' => 'Steel',
            'aluminio' => 'Aluminum',
            'roble' => 'Oak',
            'pino' => 'Pine',
            'madera maciza' => 'Solid wood',
            'acero inoxidable' => 'Stainless steel',
            'madera y metal' => 'Wood and metal',

            'negro' => 'Black',
            'blanco' => 'White',
            'gris' => 'Gray',
            'azul' => 'Blue',
            'rojo' => 'Red',
            'verde' => 'Green',
            'amarillo' => 'Yellow',
            'marrón' => 'Brown',
            'marron' => 'Brown',
            'beige' => 'Beige',
            'natural' => 'Natural',
            'dorado' => 'Gold',
            'plateado' => 'Silver',
            'rosado' => 'Pink',
            'naranja' => 'Orange',
        ];

        $traducirValor = function ($valor) use ($ingles, $traducciones) {
            if (is_null($valor) || trim((string) $valor) === '') {
                return '—';
            }

            if (!$ingles) {
                return $valor;
            }

            $clave = mb_strtolower(trim((string) $valor));

            return $traducciones[$clave] ?? $valor;
        };
    @endphp

    <h2>
        ★ {{ $ingles ? 'Furniture Comparison' : 'Comparador de muebles' }}
    </h2>

    <p>
        {{ $ingles
            ? 'Select 2 or 3 products and compare their technical specifications side by side.'
            : 'Elige entre 2 y 3 productos y compara su ficha técnica lado a lado.' }}
    </p>

    {{-- VOLVER AL CATÁLOGO --}}
    <div style="margin-bottom:1.5rem;">
        <a
            href="{{ route('productos.index') }}"
            class="boton boton-secundario"
        >
            ← {{ $ingles ? 'Back to Catalog' : 'Volver al catálogo' }}
        </a>
    </div>

    @if ($productos->count() < 2)

        {{-- MENSAJE SI NO HAY SUFICIENTES PRODUCTOS --}}
        <p class="vacio">
            {{ $ingles
                ? 'Select at least 2 products from the catalog to compare.'
                : 'Selecciona al menos 2 productos del catálogo para comparar.' }}
        </p>

    @else

        {{-- TABLA COMPARATIVA --}}
        <div style="overflow-x:auto; margin-bottom:1.5rem;">

            <table>

                {{-- NOMBRES DE PRODUCTOS --}}
                <tr>
                    <th>
                        {{ $ingles ? 'Product' : 'Producto' }}
                    </th>

                    @foreach ($productos as $producto)
                        <th>
                            {{ $producto->nombre_localizado }}
                        </th>
                    @endforeach
                </tr>

                {{-- PRECIO --}}
                <tr>
                    <th>
                        {{ $ingles ? 'Price' : 'Precio' }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            ${{ number_format($producto->precio, 0, ',', '.') }}
                        </td>
                    @endforeach
                </tr>

                {{-- MATERIAL --}}
                <tr>
                    <th>
                        {{ $ingles ? 'Material' : 'Material' }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            {{ $traducirValor($producto->especificacion?->material) }}
                        </td>
                    @endforeach
                </tr>

                {{-- COLOR --}}
                <tr>
                    <th>
                        {{ $ingles ? 'Color' : 'Color' }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            {{ $traducirValor($producto->especificacion?->color) }}
                        </td>
                    @endforeach
                </tr>

                {{-- DIMENSIONES --}}
                <tr>
                    <th>
                        {{ $ingles
                            ? 'Dimensions (W x H x D)'
                            : 'Dimensiones (A x Al x P)' }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            @if ($producto->especificacion)
                                {{ $producto->especificacion->ancho_cm }}
                                x
                                {{ $producto->especificacion->alto_cm }}
                                x
                                {{ $producto->especificacion->profundidad_cm }}
                                cm
                            @else
                                —
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- PESO --}}
                <tr>
                    <th>
                        {{ $ingles ? 'Weight' : 'Peso' }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            @if (!is_null($producto->especificacion?->peso_kg))
                                {{ $producto->especificacion->peso_kg }} kg
                            @else
                                —
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- ENSAMBLAJE --}}
                <tr>
                    <th>
                        {{ $ingles
                            ? 'Assembly Required'
                            : 'Requiere ensamblaje' }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            @if (!$producto->especificacion)
                                —
                            @else
                                {{ $producto->especificacion->requiere_ensamblaje
                                    ? ($ingles ? 'Yes' : 'Sí')
                                    : ($ingles ? 'No' : 'No') }}
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- GARANTÍA --}}
                <tr>
                    <th>
                        {{ $ingles ? 'Warranty' : 'Garantía' }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            @if (!is_null($producto->especificacion?->garantia_meses))
                                {{ $producto->especificacion->garantia_meses }}
                                {{ $ingles ? 'months' : 'meses' }}
                            @else
                                —
                            @endif
                        </td>
                    @endforeach
                </tr>

            </table>

        </div>

        {{-- BOTÓN PARA COMPARAR OTROS PRODUCTOS --}}
        <div style="margin-top:1rem;">
            <a
                href="{{ route('productos.index') }}"
                class="boton"
            >
                {{ $ingles
                    ? 'Compare Other Products'
                    : 'Comparar otros productos' }}
            </a>
        </div>

    @endif

@endsection

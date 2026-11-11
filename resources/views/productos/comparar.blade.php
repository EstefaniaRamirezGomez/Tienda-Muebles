@extends('layouts.app')

@section('titulo', __('tienda.productos_comparar.furniture_comparison')
)

@section('contenido')


    <h2>
        ★ {{ __('tienda.productos_comparar.furniture_comparison') }}
    </h2>

    <p>
        {{ __('tienda.productos_comparar.select_2_or_3_products_and') }}
    </p>

    {{-- VOLVER AL CATÁLOGO --}}
    <div style="margin-bottom:1.5rem;">
        <a
            href="{{ route('productos.index') }}"
            class="boton boton-secundario"
        >
            ← {{ __('tienda.productos_comparar.back_to_catalog') }}
        </a>
    </div>

    @if ($productos->count() < 2)

        {{-- MENSAJE SI NO HAY SUFICIENTES PRODUCTOS --}}
        <p class="vacio">
            {{ __('tienda.productos_comparar.select_at_least_2_products_from') }}
        </p>

    @else

        {{-- TABLA COMPARATIVA --}}
        <div style="overflow-x:auto; margin-bottom:1.5rem;">

            <table>

                {{-- NOMBRES DE PRODUCTOS --}}
                <tr>
                    <th>
                        {{ __('tienda.productos_comparar.product') }}
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
                        {{ __('tienda.productos_comparar.price') }}
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
                        {{ __('tienda.productos_comparar.material') }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            {{ $producto->especificacion?->material_localizado ?? '—' }}
                        </td>
                    @endforeach
                </tr>

                {{-- COLOR --}}
                <tr>
                    <th>
                        {{ __('tienda.productos_comparar.color') }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            {{ $producto->especificacion?->color_localizado ?? '—' }}
                        </td>
                    @endforeach
                </tr>

                {{-- DIMENSIONES --}}
                <tr>
                    <th>
                        {{ __('tienda.productos_comparar.dimensions_w_x_h_x_d') }}
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
                        {{ __('tienda.productos_comparar.weight') }}
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
                        {{ __('tienda.productos_comparar.assembly_required') }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            @if (!$producto->especificacion)
                                —
                            @else
                                {{ $producto->especificacion->requiere_ensamblaje
                                    ? (__('tienda.productos_comparar.yes'))
                                    : (__('tienda.productos_comparar.no')) }}
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- GARANTÍA --}}
                <tr>
                    <th>
                        {{ __('tienda.productos_comparar.warranty') }}
                    </th>

                    @foreach ($productos as $producto)
                        <td>
                            @if (!is_null($producto->especificacion?->garantia_meses))
                                {{ $producto->especificacion->garantia_meses }}
                                {{ __('tienda.productos_comparar.months') }}
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
                {{ __('tienda.productos_comparar.compare_other_products') }}
            </a>
        </div>

    @endif

@endsection

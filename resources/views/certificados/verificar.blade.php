@extends('layouts.app')

{{-- Autor: Equipo Muebles & Decoración --}}

@section('titulo', __('tienda.certificados.titulo_verificacion'))

@section('contenido')

    <h2>🔗 {{ __('tienda.certificados.titulo_verificacion') }}</h2>

    <p>{{ __('tienda.certificados.descripcion_verificacion') }}</p>

    @if (session('exito'))
        <div class="mensaje-exito">{{ session('exito') }}</div>
    @endif

    {{-- RESULTADO DE LA BLOCKCHAIN --}}
    @if ($verificacion === null)

        <div class="mensaje-error">{{ __('tienda.certificados.servicio_no_disponible') }}</div>

    @elseif ($verificacion['valid'] ?? false)

        <div class="tarjeta certificado-resultado certificado-valido">
            <h3>✔ {{ __('tienda.certificados.certificado_valido') }}</h3>

            <table>
                <tr>
                    <th>{{ __('tienda.certificados.serial') }}</th>
                    <td>{{ $verificacion['serial_number'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>{{ __('tienda.certificados.bloque') }}</th>
                    <td>#{{ $verificacion['block_index'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>{{ __('tienda.certificados.hash_bloque') }}</th>
                    <td class="certificado-mono">{{ $verificacion['block_hash'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>{{ __('tienda.certificados.bloques_cadena') }}</th>
                    <td>{{ $verificacion['chain_total_blocks'] ?? '—' }}</td>
                </tr>
                @foreach (($verificacion['metadata'] ?? []) as $campo => $valor)
                    @if (is_scalar($valor) && $campo !== 'serial_number')
                        <tr>
                            <th>{{ \Illuminate\Support\Facades\Lang::has('tienda.certificados.campos.' . $campo) ? __('tienda.certificados.campos.' . $campo) : $campo }}</th>
                            <td>{{ $valor }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>

    @else

        <div class="tarjeta certificado-resultado certificado-invalido">
            <h3>✖ {{ __('tienda.certificados.certificado_invalido') }}</h3>
            <p>{{ __('tienda.certificados.certificado_invalido_texto') }}</p>
        </div>

    @endif

    {{-- COMPARACIÓN CON LOS DATOS DE NUESTRA TIENDA --}}
    @if ($pedido)
        <div class="tarjeta" style="margin-top:1rem;">
            <h3>{{ __('tienda.certificados.pedido_en_tienda', ['id' => $pedido->id]) }}</h3>

            <p>
                {{ __('tienda.certificados.total') }}:
                <span class="precio">${{ number_format($pedido->total, 0, ',', '.') }} COP</span>
            </p>

            <ul>
                @foreach ($pedido->detalles as $detalle)
                    <li>{{ $detalle->cantidad }} × {{ $detalle->producto?->nombre_localizado ?? '—' }}</li>
                @endforeach
            </ul>

            @if ($datosIntactos)
                <p class="certificado-ok">✔ {{ __('tienda.certificados.datos_intactos') }}</p>
            @else
                <p class="certificado-alerta">⚠ {{ __('tienda.certificados.datos_alterados') }}</p>
            @endif
        </div>
    @endif

    {{-- HASH CONSULTADO --}}
    <div class="tarjeta" style="margin-top:1rem;">
        <strong>{{ __('tienda.certificados.hash_certificado') }}</strong>
        <p class="certificado-mono">{{ $hash }}</p>

        @if ($estadoRed)
            <small>
                {{ __('tienda.certificados.estado_red') }}:
                @foreach ($estadoRed as $campo => $valor)
                    @if (is_scalar($valor))
                        {{ $campo }} = {{ is_bool($valor) ? ($valor ? 'true' : 'false') : $valor }}@if (! $loop->last) · @endif
                    @endif
                @endforeach
            </small>
        @endif
    </div>

    <style>
        .certificado-valido { border-left: 5px solid #16a34a; }
        .certificado-invalido { border-left: 5px solid #C62828; }
        .certificado-ok { color: #166534; font-weight: 700; }
        .certificado-alerta { color: #B3261E; font-weight: 700; }
        .certificado-mono { font-family: monospace; font-size: 0.8rem; word-break: break-all; }
    </style>

@endsection

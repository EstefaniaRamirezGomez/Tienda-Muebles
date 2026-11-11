{{-- Autor: Equipo Muebles & Decoración --}}
{{-- Sección del certificado blockchain dentro del detalle de un pedido. Requiere $pedido. --}}
<section class="certificado-seccion">

    <h2>🔗 {{ __('tienda.certificados.titulo_seccion') }}</h2>

    @if ($pedido->estaCertificado())

        <p class="certificado-ok">✔ {{ __('tienda.certificados.pedido_certificado') }}</p>

        <p>
            <strong>{{ __('tienda.certificados.serial') }}:</strong> {{ $pedido->certificado_serial }}<br>
            <strong>{{ __('tienda.certificados.bloque') }}:</strong> #{{ $pedido->certificado_bloque ?? '—' }}<br>
            <strong>{{ __('tienda.certificados.emitido') }}:</strong> {{ $pedido->certificado_emitido_en?->format('d/m/Y H:i') }}
        </p>

        <p class="certificado-hash">{{ $pedido->certificado_hash }}</p>

        <a href="{{ route('certificados.verificar', $pedido->certificado_hash) }}" class="boton">
            {{ __('tienda.certificados.ver_verificacion') }}
        </a>

    @elseif ($pedido->estado === 'cancelado')

        <p class="vacio">{{ __('tienda.certificados.pedido_cancelado') }}</p>

    @else

        <p>{{ __('tienda.certificados.explicacion') }}</p>

        <form action="{{ route('pedidos.certificar', $pedido) }}" method="POST">
            @csrf
            <button type="submit" class="boton">
                {{ __('tienda.certificados.boton_certificar') }}
            </button>
        </form>

    @endif

</section>

<style>
    .certificado-seccion {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .certificado-ok {
        color: #166534;
        font-weight: 700;
    }

    .certificado-hash {
        font-family: monospace;
        font-size: 0.8rem;
        word-break: break-all;
        background: #f5f1ec;
        padding: 0.6rem;
        border-radius: 6px;
    }
</style>

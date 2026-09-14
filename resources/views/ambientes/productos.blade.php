@extends('layouts.app')

@section('titulo', $ambiente->nombre)

@section('contenido')
    <a href="{{ route('ambientes.index') }}">&larr; Todos los ambientes</a>
    <h2>Muebles para: {{ $ambiente->nombre }}</h2>

    @if ($productos->isEmpty())
        <p class="vacio">Todavía no hay productos para este ambiente.</p>
    @endif

    <form action="{{ route('productos.comparar') }}" method="GET" id="form-comparar">
        @if ($productos->isNotEmpty())
            <p class="vacio">Marca entre 2 y 3 productos para comparar su ficha técnica.</p>
        @endif

        <div class="grid">
            @foreach ($productos as $producto)
                <div class="producto-card">
                    <label style="display:flex; align-items:center; gap:0.4rem; margin-bottom:0.5rem; font-weight:600;">
                        <input type="checkbox" name="ids[]" value="{{ $producto->id }}" class="check-comparar">
                        Comparar
                    </label>
                    <img src="{{ $producto->imagen ?? 'https://placehold.co/400x300?text=Sin+imagen' }}"
                         alt="{{ $producto->nombre }}"
                         style="width:100%; height:140px; object-fit:cover; border-radius:6px; margin-bottom:0.5rem;">
                    <span class="etiqueta">{{ $producto->categoria->nombre }}</span>
                    <h3>{{ $producto->nombre }}</h3>
                    <p class="precio">${{ number_format($producto->precio, 0, ',', '.') }}</p>
                    <a href="{{ route('productos.show', $producto) }}" class="boton">Ver detalle</a>
                </div>
            @endforeach
        </div>

        @if ($productos->isNotEmpty())
            <button type="submit" class="boton" style="margin-top:1rem;" id="btn-comparar" disabled>
                Comparar seleccionados
            </button>
        @endif
    </form>

    <script>
        const casillas = document.querySelectorAll('.check-comparar');
        const boton = document.getElementById('btn-comparar');

        function actualizarBoton() {
            const marcados = document.querySelectorAll('.check-comparar:checked').length;
            boton.disabled = marcados < 2;
            casillas.forEach(c => {
                if (!c.checked) c.disabled = marcados >= 3;
            });
        }

        casillas.forEach(c => c.addEventListener('change', actualizarBoton));
    </script>
@endsection
@extends('layouts.app')

@section('titulo', 'Catálogo')

@section('contenido')
    <h2>Catálogo de productos</h2>

    <form action="{{ route('productos.buscar') }}" method="GET" style="margin-bottom:1rem;">
        <input type="search" name="q" value="{{ $termino ?? '' }}" placeholder="Buscar por nombre... (ej: sofá)" style="width:280px;">
        <button type="submit" class="boton">Buscar</button>
        @if (!empty($termino))
            <a href="{{ route('productos.index') }}" class="boton boton-secundario">Limpiar</a>
        @endif
    </form>

    @if (isset($termino) && $productos->isEmpty())
        <p class="vacio">No se encontraron productos para "{{ $termino }}".</p>
    @endif

    <form action="{{ route('productos.comparar') }}" method="GET" id="form-comparar">
<p class="vacio">Marca entre 2 y 3 productos de la misma categoría para comparar su ficha técnica.</p>
        <div class="grid">
            @foreach ($productos as $producto)
                <div class="producto-card">
                    <label style="display:flex; align-items:center; gap:0.4rem; margin-bottom:0.5rem; font-weight:600;">
                        <input type="checkbox" name="ids[]" value="{{ $producto->id }}" class="check-comparar" data-categoria="{{ $producto->categoria_id }}">
                        Comparar
                    </label>
                    <img src="{{ $producto->imagen ?? 'https://placehold.co/400x300?text=Sin+imagen' }}"
                         alt="{{ $producto->nombre }}"
                         style="width:100%; height:140px; object-fit:cover; border-radius:6px; margin-bottom:0.5rem;">
                    <span class="etiqueta">{{ $producto->categoria->nombre }}</span>
                    <span class="etiqueta">{{ $producto->ambiente->nombre }}</span>
                    <h3>{{ $producto->nombre }}</h3>
                    <p class="precio">${{ number_format($producto->precio, 0, ',', '.') }}</p>
                    <a href="{{ route('productos.show', $producto) }}" class="boton">Ver detalle</a>
                </div>
            @endforeach
        </div>

        <button type="submit" class="boton" style="margin-top:1rem;" id="btn-comparar" disabled>
            Comparar seleccionados
        </button>
    </form>

    <script>
    const casillas = document.querySelectorAll('.check-comparar');
    const boton = document.getElementById('btn-comparar');

    function categoriaSeleccionada() {
        const marcada = document.querySelector('.check-comparar:checked');
        return marcada ? marcada.dataset.categoria : null;
    }

    function actualizarBoton() {
        const marcados = document.querySelectorAll('.check-comparar:checked').length;
        const catSel = categoriaSeleccionada();

        boton.disabled = marcados < 2;

        casillas.forEach(c => {
            if (c.checked) return;
            const otraCategoria = catSel !== null && c.dataset.categoria !== catSel;
            const limiteAlcanzado = marcados >= 3;
            c.disabled = otraCategoria || limiteAlcanzado;
        });
    }

    casillas.forEach(c => c.addEventListener('change', actualizarBoton));
</script>
@endsection
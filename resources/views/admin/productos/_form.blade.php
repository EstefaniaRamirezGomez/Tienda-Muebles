@php
    $ingles = app()->getLocale() === 'en';

    // Normaliza los nombres para buscar sus traducciones.
    $normalizar = function ($valor) {
        return str_replace(
            [' ', '-'],
            '_',
            \Illuminate\Support\Str::ascii(
                mb_strtolower(trim((string) $valor))
            )
        );
    };

    // Traduce categorías y ambientes si existe una traducción.
    $traducirRelacion = function ($nombre, $tipo) use ($normalizar) {
        $clave = 'messages.' . $tipo . '.' . $normalizar($nombre);

        return \Illuminate\Support\Facades\Lang::has($clave)
            ? __($clave)
            : $nombre;
    };
@endphp

{{-- NOMBRE DEL PRODUCTO --}}

<div class="campo">

    <label for="nombre">
        {{ $ingles ? 'Product Name' : 'Nombre del producto' }}
    </label>

    <input
        type="text"
        id="nombre"
        name="nombre"
        value="{{ old('nombre', $producto->nombre ?? '') }}"
        required
    >

    @error('nombre')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- DESCRIPCIÓN --}}

<div class="campo">

    <label for="descripcion">
        {{ $ingles ? 'Description' : 'Descripción' }}
    </label>

    <textarea
        id="descripcion"
        name="descripcion"
        rows="5"
        required
    >{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>

    @error('descripcion')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- IMAGEN --}}

<div class="campo">

    <label for="imagen">
        {{ $ingles ? 'Image URL' : 'URL de la imagen' }}
    </label>

    <input
        type="url"
        id="imagen"
        name="imagen"
        value="{{ old('imagen', $producto->imagen ?? '') }}"
        placeholder="https://example.com/image.jpg"
    >

    @error('imagen')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- PRECIO --}}

<div class="campo">

    <label for="precio">
        {{ $ingles ? 'Price' : 'Precio' }}
    </label>

    <input
        type="number"
        id="precio"
        name="precio"
        min="0"
        step="0.01"
        value="{{ old('precio', $producto->precio ?? '') }}"
        required
    >

    @error('precio')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- STOCK --}}

<div class="campo">

    <label for="stock">
        {{ $ingles ? 'Available Stock' : 'Stock disponible' }}
    </label>

    <input
        type="number"
        id="stock"
        name="stock"
        min="0"
        value="{{ old('stock', $producto->stock ?? '') }}"
        required
    >

    @error('stock')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- CATEGORÍA --}}

<div class="campo">

    <label for="categoria_id">
        {{ $ingles ? 'Category' : 'Categoría' }}
    </label>

    <select
        id="categoria_id"
        name="categoria_id"
        required
    >

        <option value="">
            {{ $ingles ? 'Select a category' : 'Seleccione una categoría' }}
        </option>

        @foreach ($categorias as $categoria)

            <option
                value="{{ $categoria->id }}"
                @selected(
                    old(
                        'categoria_id',
                        $producto->categoria_id ?? ''
                    ) == $categoria->id
                )
            >
                {{ $traducirRelacion($categoria->nombre, 'categories') }}
            </option>

        @endforeach

    </select>

    @error('categoria_id')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- AMBIENTE --}}

<div class="campo">

    <label for="ambiente_id">
        {{ $ingles ? 'Room' : 'Ambiente' }}
    </label>

    <select
        id="ambiente_id"
        name="ambiente_id"
        required
    >

        <option value="">
            {{ $ingles ? 'Select a room' : 'Seleccione un ambiente' }}
        </option>

        @foreach ($ambientes as $ambiente)

            <option
                value="{{ $ambiente->id }}"
                @selected(
                    old(
                        'ambiente_id',
                        $producto->ambiente_id ?? ''
                    ) == $ambiente->id
                )
            >
                {{ $traducirRelacion($ambiente->nombre, 'rooms') }}
            </option>

        @endforeach

    </select>

    @error('ambiente_id')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- BOTÓN DE GUARDAR --}}

<button type="submit" class="boton">
    {{ $textoBoton }}
</button>

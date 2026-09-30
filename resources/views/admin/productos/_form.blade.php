<div class="campo">
    <label for="nombre">Nombre del producto</label>

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

<div class="campo">
    <label for="descripcion">Descripción</label>

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

<div class="campo">
    <label for="imagen">URL de la imagen</label>

    <input
        type="url"
        id="imagen"
        name="imagen"
        value="{{ old('imagen', $producto->imagen ?? '') }}"
        placeholder="https://ejemplo.com/imagen.jpg"
    >

    @error('imagen')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="campo">
    <label for="precio">Precio</label>

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

<div class="campo">
    <label for="stock">Stock</label>

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

<div class="campo">
    <label for="categoria_id">Categoría</label>

    <select
        id="categoria_id"
        name="categoria_id"
        required
    >
        <option value="">Seleccione una categoría</option>

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
                {{ $categoria->nombre }}
            </option>
        @endforeach
    </select>

    @error('categoria_id')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="campo">
    <label for="ambiente_id">Ambiente</label>

    <select
        id="ambiente_id"
        name="ambiente_id"
        required
    >
        <option value="">Seleccione un ambiente</option>

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
                {{ $ambiente->nombre }}
            </option>
        @endforeach
    </select>

    @error('ambiente_id')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<button type="submit" class="boton">
    {{ $textoBoton }}
</button>
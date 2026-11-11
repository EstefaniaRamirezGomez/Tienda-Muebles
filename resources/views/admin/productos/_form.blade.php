

{{-- NOMBRE DEL PRODUCTO --}}

<div class="campo">

    <label for="nombre">
        {{ __('tienda.admin_productos__form.product_name') }}
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
        {{ __('tienda.admin_productos__form.description') }}
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
        {{ __('tienda.admin_productos__form.image_url') }}
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
        {{ __('tienda.admin_productos__form.price') }}
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
        {{ __('tienda.admin_productos__form.available_stock') }}
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
        {{ __('tienda.admin_productos__form.category') }}
    </label>

    <select
        id="categoria_id"
        name="categoria_id"
        required
    >

        <option value="">
            {{ __('tienda.admin_productos__form.select_a_category') }}
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
                {{ $categoria->nombre_localizado }}
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
        {{ __('tienda.admin_productos__form.room') }}
    </label>

    <select
        id="ambiente_id"
        name="ambiente_id"
        required
    >

        <option value="">
            {{ __('tienda.admin_productos__form.select_a_room') }}
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
                {{ $ambiente->nombre_localizado }}
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

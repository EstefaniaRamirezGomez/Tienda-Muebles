@extends('layouts.admin')

@section('titulo', __('tienda.admin_productos_edit.edit_product')
)

@section('contenido')


    {{-- ENCABEZADO --}}

    <h2>
        {{ __('tienda.admin_productos_edit.edit_product') }}
    </h2>

    <p>
        {{ __('tienda.admin_productos_edit.update_the_information_of_the_product') }}

        <strong>
            {{ $producto->nombre_localizado }}
        </strong>
    </p>

    {{-- FORMULARIO DE EDICIÓN --}}

    <div class="tarjeta-admin">

        <form
            action="{{ route('admin.productos.update', $producto) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            @include('admin.productos._form', [
                'textoBoton' => __('tienda.admin_productos_edit.save_changes')
            ])

        </form>

    </div>

@endsection

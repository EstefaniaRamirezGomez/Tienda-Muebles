@extends('layouts.admin')

@section('titulo', __('tienda.admin_productos_create.create_product')
)

@section('contenido')


    {{-- ENCABEZADO --}}

    <h2>
        {{ __('tienda.admin_productos_create.create_product') }}
    </h2>

    <p>
        {{ __('tienda.admin_productos_create.add_a_new_product_to_the') }}
    </p>

    {{-- FORMULARIO DE CREACIÓN --}}

    <div class="tarjeta-admin">

        <form
            action="{{ route('admin.productos.store') }}"
            method="POST"
        >

            @csrf

            @include('admin.productos._form', [
                'textoBoton' => __('tienda.admin_productos_create.create_product')
            ])

        </form>

    </div>

@endsection

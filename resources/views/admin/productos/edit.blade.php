@extends('layouts.admin')

@section('titulo', app()->getLocale() === 'en'
    ? 'Edit Product'
    : 'Editar producto'
)

@section('contenido')

    @php
        $ingles = app()->getLocale() === 'en';
    @endphp

    {{-- ENCABEZADO --}}

    <h2>
        {{ $ingles ? 'Edit Product' : 'Editar producto' }}
    </h2>

    <p>
        {{ $ingles
            ? 'Update the information of the product:'
            : 'Modifica la información del producto:' }}

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
                'textoBoton' => $ingles
                    ? 'Save Changes'
                    : 'Guardar cambios'
            ])

        </form>

    </div>

@endsection

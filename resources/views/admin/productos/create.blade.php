@extends('layouts.admin')

@section('titulo', app()->getLocale() === 'en'
    ? 'Create Product'
    : 'Crear producto'
)

@section('contenido')

    @php
        $ingles = app()->getLocale() === 'en';
    @endphp

    {{-- ENCABEZADO --}}

    <h2>
        {{ $ingles ? 'Create Product' : 'Crear producto' }}
    </h2>

    <p>
        {{ $ingles
            ? 'Add a new product to the store catalog.'
            : 'Agrega un nuevo producto al catálogo de la tienda.' }}
    </p>

    {{-- FORMULARIO DE CREACIÓN --}}

    <div class="tarjeta-admin">

        <form
            action="{{ route('admin.productos.store') }}"
            method="POST"
        >

            @csrf

            @include('admin.productos._form', [
                'textoBoton' => $ingles
                    ? 'Create Product'
                    : 'Crear producto'
            ])

        </form>

    </div>

@endsection

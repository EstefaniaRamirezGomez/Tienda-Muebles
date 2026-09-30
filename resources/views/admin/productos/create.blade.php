@extends('layouts.admin')

@section('titulo', 'Crear producto')

@section('contenido')

    <h2>Crear producto</h2>

    <p>
        Agrega un nuevo producto al catálogo de la tienda.
    </p>

    <div class="tarjeta-admin">

        <form
            action="{{ route('admin.productos.store') }}"
            method="POST"
        >

            @csrf

            @include('admin.productos._form', [
                'textoBoton' => 'Crear producto'
            ])

        </form>

    </div>

@endsection
@extends('layouts.admin')

@section('titulo', 'Editar producto')

@section('contenido')

    <h2>Editar producto</h2>

    <p>
        Modifica la información del producto:
        <strong>{{ $producto->nombre }}</strong>
    </p>

    <div class="tarjeta-admin">

        <form
            action="{{ route('admin.productos.update', $producto) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            @include('admin.productos._form', [
                'textoBoton' => 'Guardar cambios'
            ])

        </form>

    </div>

@endsection
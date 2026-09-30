{{-- Autor: Estefanía Ramírez Gómez --}}

@extends('layouts.admin')

@section('titulo', 'Inicio')

@section('contenido')

    <h2>
        Panel de administración
    </h2>

    <p>
        Gestiona la información principal de la tienda.
    </p>

    <div class="metricas">

        <div class="metrica">
            <span>
                Productos
            </span>

            <strong>
                {{ $resumen['productos'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                Categorías
            </span>

            <strong>
                {{ $resumen['categorias'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                Pedidos
            </span>

            <strong>
                {{ $resumen['pedidos'] }}
            </strong>
        </div>

        <div class="metrica">
            <span>
                Clientes
            </span>

            <strong>
                {{ $resumen['clientes'] }}
            </strong>
        </div>

    </div>

    <p class="aviso">
        Utiliza el menú lateral para administrar los productos de la tienda.
    </p>

@endsection
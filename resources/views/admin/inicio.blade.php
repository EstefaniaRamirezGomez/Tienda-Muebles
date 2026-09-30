{{-- Autor: Estefanía Ramírez Gómez --}}
@extends('layouts.admin')

@section('titulo', __('app.admin_inicio'))

@section('contenido')
    <h2>{{ __('app.admin_titulo') }}</h2>
    <p>{{ __('app.admin_bienvenida', ['nombre' => auth()->user()->name]) }}</p>

    <div class="metricas">
        <div class="metrica">
            <span>{{ __('app.admin_productos') }}</span>
            <strong>{{ $resumen['productos'] }}</strong>
        </div>
        <div class="metrica">
            <span>{{ __('app.admin_categorias') }}</span>
            <strong>{{ $resumen['categorias'] }}</strong>
        </div>
        <div class="metrica">
            <span>{{ __('app.admin_pedidos') }}</span>
            <strong>{{ $resumen['pedidos'] }}</strong>
        </div>
        <div class="metrica">
            <span>{{ __('app.admin_clientes') }}</span>
            <strong>{{ $resumen['clientes'] }}</strong>
        </div>
    </div>

    <p class="aviso">{{ __('app.admin_proximamente') }}</p>
@endsection

@extends('layouts.app')

@section('titulo', 'Ambientes')

@section('contenido')
    <h2>Explora por ambiente</h2>
    <div class="grid">
        @foreach ($ambientes as $ambiente)
            <div class="producto-card">
                <h3>{{ $ambiente->nombre }}</h3>
                <p>{{ $ambiente->productos_count }} producto(s)</p>
                <a class="boton" href="{{ route('ambientes.productos', $ambiente) }}">Ver productos</a>
            </div>
        @endforeach
    </div>
@endsection
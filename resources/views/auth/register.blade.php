{{-- Autor: Estefanía Ramírez Gómez --}}
@extends('layouts.app')

@section('titulo', __('app.titulo_registro'))

@section('contenido')
    <div class="tarjeta formulario-auth">
        <h2>{{ __('app.titulo_registro') }}</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name">{{ __('app.nombre') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="email">{{ __('app.correo') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password">{{ __('app.contrasena') }}</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password_confirmation">{{ __('app.confirmar_contrasena') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>

            <button type="submit" class="boton">{{ __('app.crear_cuenta') }}</button>
        </form>

        <p class="pie-auth">
            {{ __('app.con_cuenta') }}
            <a href="{{ route('login') }}">{{ __('app.iniciar_sesion') }}</a>
        </p>
    </div>
@endsection

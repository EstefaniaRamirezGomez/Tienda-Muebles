{{-- Autor: Estefanía Ramírez Gómez --}}
@extends('layouts.app')

@section('titulo', __('app.titulo_login'))

@section('contenido')
    <div class="tarjeta formulario-auth">
        <h2>{{ __('app.titulo_login') }}</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email">{{ __('app.correo') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password">{{ __('app.contrasena') }}</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <label class="check">
                <input type="checkbox" name="recordar"> {{ __('app.recordarme') }}
            </label>

            <button type="submit" class="boton">{{ __('app.entrar') }}</button>
        </form>

        <p class="pie-auth">
            {{ __('app.sin_cuenta') }}
            <a href="{{ route('register') }}">{{ __('app.registrarse') }}</a>
        </p>
    </div>
@endsection

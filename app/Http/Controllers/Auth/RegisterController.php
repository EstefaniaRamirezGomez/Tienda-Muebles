<?php

// Autor: Estefanía Ramírez Gómez

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function mostrarFormulario(): View
    {
        return view('auth.register');
    }

    public function registrar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Todo usuario que se registra desde la tienda es cliente.
        // Los administradores solo se crean desde el seeder o la base de datos.
        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => $datos['password'],
            'rol' => 'cliente',
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('inicio');
    }
}

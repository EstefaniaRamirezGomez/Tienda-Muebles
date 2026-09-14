<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use Illuminate\View\View;

class AmbienteController extends Controller
{
    public function index(): View
    {
        $ambientes = Ambiente::withCount('productos')->orderBy('nombre')->get();

        return view('ambientes.index', compact('ambientes'));
    }

    public function productos(Ambiente $ambiente): View
    {
        $productos = $ambiente->productos()->with('categoria')->orderBy('nombre')->get();

        return view('ambientes.productos', compact('ambiente', 'productos'));
    }
}
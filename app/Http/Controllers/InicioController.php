<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with(['categoria', 'ambiente'])
            ->orderBy('nombre')
            ->take(6)
            ->get();

        return view('inicio', compact('productos'));
    }
}
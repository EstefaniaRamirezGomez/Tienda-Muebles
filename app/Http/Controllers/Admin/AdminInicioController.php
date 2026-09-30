<?php

// Autor: Estefanía Ramírez Gómez

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\View\View;

class AdminInicioController extends Controller
{
    public function index(): View
    {
        $resumen = [
            'productos' => Producto::count(),
            'categorias' => Categoria::count(),
            'pedidos' => Pedido::count(),
            'clientes' => User::where('rol', 'cliente')->count(),
        ];

        return view('admin.inicio', compact('resumen'));
    }
}

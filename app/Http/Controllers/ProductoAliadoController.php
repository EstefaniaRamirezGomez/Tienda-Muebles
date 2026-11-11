<?php

// Autor: Equipo Muebles & Decoración

namespace App\Http\Controllers;

use App\Services\ServicioAliadoService;
use Illuminate\View\View;

class ProductoAliadoController extends Controller
{
    public function __construct(
        private readonly ServicioAliadoService $servicioAliado
    ) {}

    public function index(): View
    {
        $productos = $this->servicioAliado->obtenerProductos();

        return view('aliados.index', [
            'productos' => $productos,
            'nombreAliado' => config('services.aliado.nombre'),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Contracts\ReporteProductoInterface;
use App\Models\Producto;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReporteProductoController extends Controller
{
    public function __construct(
        private readonly ReporteProductoInterface $reporte
    ) {}

    public function descargar(Request $request): Response
    {
        $formato = $request->query('formato', 'csv');

        abort_unless(
            in_array($formato, ['csv', 'json'], true),
            422,
            __('tienda.ctrl_reporte.formato_invalido')
        );

        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->get();

        // La clase concreta (CSV o JSON) la inyecta el contenedor
        // según el binding definido en AppServiceProvider.
        return $this->reporte->generar($productos);
    }
}

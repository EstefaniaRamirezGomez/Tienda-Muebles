<?php

namespace App\Http\Controllers;

use App\Contracts\ReporteProductoInterface;
use App\Models\Producto;
use App\Services\ReporteProductosCsv;
use App\Services\ReporteProductosJson;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReporteProductoController extends Controller
{
    public function __construct(
        private readonly ReporteProductoInterface $reporte
    ) {
    }

    public function descargar(Request $request): Response
    {
        $formato = $request->query('formato', 'csv');

        abort_unless(
            in_array($formato, ['csv', 'json'], true),
            422,
            'Formato de reporte no válido.'
        );

        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->get();

        // La interfaz permite trabajar con ambos generadores.
        $generador = $formato === 'json'
            ? app(ReporteProductosJson::class)
            : $this->reporte;

        return $generador->generar($productos);
    }
}


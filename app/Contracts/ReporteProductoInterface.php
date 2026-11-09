<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

interface ReporteProductoInterface
{
    public function generar(Collection $productos): Response;
}

<?php

// Autor: Equipo Muebles & Decoración

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * Traduce valores guardados en español (categorías, ambientes,
 * materiales, colores) usando los archivos de resources/lang.
 * Si no existe la clave, devuelve el valor original.
 */
trait TraduceValores
{
    protected function traducirValor(?string $valor, string $grupo): ?string
    {
        if ($valor === null || trim($valor) === '') {
            return null;
        }

        $clave = 'messages.'.$grupo.'.'.self::normalizarClave($valor);

        return Lang::has($clave) ? __($clave) : $valor;
    }

    public static function normalizarClave(string $valor): string
    {
        return str_replace(
            [' ', '-'],
            '_',
            Str::ascii(mb_strtolower(trim($valor)))
        );
    }
}

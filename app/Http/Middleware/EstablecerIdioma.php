<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstablecerIdioma
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $idioma = $request->session()->get('idioma', 'es');

        if (!in_array($idioma, ['es', 'en'], true)) {
            $idioma = 'es';
        }

        app()->setLocale($idioma);

        return $next($request);
    }
}

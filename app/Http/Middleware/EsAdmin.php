<?php

// Autor: Estefanía Ramírez Gómez

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->esAdmin()) {
            abort(403, __('app.sin_permiso'));
        }

        return $next($request);
    }
}

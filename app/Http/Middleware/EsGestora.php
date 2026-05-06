<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsGestora
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->rol !== 'gestora') {
            abort(403, 'Acceso restringido a gestoras.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsTecnico
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->rol !== 'tecnico') {
            abort(403, 'Acceso restringido a técnicos.');
        }

        return $next($request);
    }
}
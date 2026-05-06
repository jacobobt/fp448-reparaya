<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias de middlewares de rol — se usan en routes/web.php
        $middleware->alias([
            'admin'   => \App\Http\Middleware\EsAdmin::class,
            'gestora' => \App\Http\Middleware\EsGestora::class,
            'tecnico' => \App\Http\Middleware\EsTecnico::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
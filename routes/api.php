<?php
use App\Http\Controllers\ZonasController;
use Illuminate\Support\Facades\Route;

Route::get('/servicios/zonas', [ZonasController::class, 'incidenciasPorZona']);
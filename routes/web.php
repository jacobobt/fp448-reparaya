<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/mis-avisos', [IncidenciaController::class, 'index'])->name('incidencias.index');
    Route::get('/avisos/nuevo', [IncidenciaController::class, 'create'])->name('incidencias.create');
    Route::post('/avisos', [IncidenciaController::class, 'store'])->name('incidencias.store');
    Route::patch('/avisos/{incidencia}/cancelar', [IncidenciaController::class, 'cancelar'])->name('incidencias.cancelar');
    
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/incidencias', [AdminController::class, 'incidencias'])->name('admin.incidencias.index');
    Route::patch('/admin/incidencias/{incidencia}/estado', [AdminController::class, 'cambiarEstado'])->name('admin.incidencias.estado');
});

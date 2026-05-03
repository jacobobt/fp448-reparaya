<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TecnicoPanelController;

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

    Route::patch('/admin/incidencias/{incidencia}/asignar', [AdminController::class, 'asignarTecnico'])->name('admin.incidencias.asignar');

    Route::get('/admin/especialidades', [AdminController::class, 'especialidades'])->name('admin.especialidades.index');
    Route::get('/admin/especialidades/nueva', [AdminController::class, 'crearEspecialidad'])->name('admin.especialidades.create');
    Route::post('/admin/especialidades', [AdminController::class, 'guardarEspecialidad'])->name('admin.especialidades.store');
    Route::get('/admin/especialidades/{especialidad}/editar', [AdminController::class, 'editarEspecialidad'])->name('admin.especialidades.edit');
    Route::patch('/admin/especialidades/{especialidad}', [AdminController::class, 'actualizarEspecialidad'])->name('admin.especialidades.update');

    Route::get('/admin/tecnicos', [AdminController::class, 'tecnicos'])->name('admin.tecnicos.index');
    Route::get('/admin/tecnicos/nuevo', [AdminController::class, 'crearTecnico'])->name('admin.tecnicos.create');
    Route::post('/admin/tecnicos', [AdminController::class, 'guardarTecnico'])->name('admin.tecnicos.store');
    Route::get('/admin/tecnicos/{tecnico}/editar', [AdminController::class, 'editarTecnico'])->name('admin.tecnicos.edit');
    Route::patch('/admin/tecnicos/{tecnico}', [AdminController::class, 'actualizarTecnico'])->name('admin.tecnicos.update');
    Route::patch('/admin/tecnicos/{tecnico}/disponibilidad', [AdminController::class, 'cambiarDisponibilidadTecnico'])->name('admin.tecnicos.disponibilidad');

    Route::get('/tecnico/agenda', [TecnicoPanelController::class, 'agenda'])->name('tecnico.agenda');
    Route::patch('/tecnico/incidencias/{incidencia}/finalizar', [TecnicoPanelController::class, 'finalizar'])->name('tecnico.incidencias.finalizar');
    
    Route::get('/admin/gestoras', [AdminController::class, 'gestoras'])->name('admin.gestoras.index');
    Route::get('/admin/gestoras/nueva', [AdminController::class, 'crearGestora'])->name('admin.gestoras.create');
    Route::post('/admin/gestoras', [AdminController::class, 'guardarGestora'])->name('admin.gestoras.store');
    Route::get('/admin/gestoras/{gestora}/editar', [AdminController::class, 'editarGestora'])->name('admin.gestoras.edit');
    Route::patch('/admin/gestoras/{gestora}', [AdminController::class, 'actualizarGestora'])->name('admin.gestoras.update');

    Route::get('/admin/zonas', [AdminController::class, 'zonas'])->name('admin.zonas.index');
    Route::get('/admin/zonas/nueva', [AdminController::class, 'crearZona'])->name('admin.zonas.create');
    Route::post('/admin/zonas', [AdminController::class, 'guardarZona'])->name('admin.zonas.store');
    Route::get('/admin/zonas/{zona}/editar', [AdminController::class, 'editarZona'])->name('admin.zonas.edit');
    Route::patch('/admin/zonas/{zona}', [AdminController::class, 'actualizarZona'])->name('admin.zonas.update');

    Route::get('/admin/comunidades', [AdminController::class, 'comunidades'])->name('admin.comunidades.index');
    Route::get('/admin/comunidades/nueva', [AdminController::class, 'crearComunidad'])->name('admin.comunidades.create');
    Route::post('/admin/comunidades', [AdminController::class, 'guardarComunidad'])->name('admin.comunidades.store');
    Route::get('/admin/comunidades/{comunidad}/editar', [AdminController::class, 'editarComunidad'])->name('admin.comunidades.edit');
    Route::patch('/admin/comunidades/{comunidad}', [AdminController::class, 'actualizarComunidad'])->name('admin.comunidades.update');
});

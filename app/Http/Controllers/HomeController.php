<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Tecnico;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', [
            'usuario' => Auth::user(),
            'totalUsuarios' => Usuario::count(),
            'totalTecnicos' => Tecnico::count(),
            'totalIncidencias' => Incidencia::count(),
            'ultimasIncidencias' => Incidencia::with(['cliente', 'especialidad', 'tecnico'])
                ->latest('created_at')
                ->take(5)
                ->get(),
        ]);
    }
}

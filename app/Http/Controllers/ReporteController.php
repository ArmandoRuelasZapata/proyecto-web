<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Quitamos la importación del modelo de MySQL: use App\Models\Reporte;

class ReporteController extends Controller
{
    public function store(Request $request)
    {
        // La app móvil ahora guarda directamente en Firebase.
        // Devolvemos un JSON vacío por si alguna versión vieja de la app hace la petición.
        return response()->json([
            'success' => true,
            'message' => 'La API de Laravel está obsoleta. Los reportes se envían a Firebase.'
        ], 200);
    }

    public function index()
    {
        // El listado ahora se obtiene directamente de Firebase usando JavaScript
        return response()->json([]);
    }

    public function show($id)
    {
        // El detalle ahora se obtiene directamente de Firebase usando JavaScript
        return response()->json([]);
    }

    public function edit($id)
    {
        // Esta vista ya la está manejando tu ReporteWebController
        // La dejamos vacía aquí para no duplicar código
    }

    public function update(Request $request, $id)
    {
        // La actualización ahora se hace directamente en Firebase usando JavaScript
        return response()->json([
            'success' => true,
            'message' => 'Actualizado vía Firebase.'
        ], 200);
    }
}
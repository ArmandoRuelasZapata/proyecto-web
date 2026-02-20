<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;

class ReporteWebController extends Controller
{
    public function index()
    {
        // Solo devolvemos la vista. JS se encarga de traer la lista de Firebase.
        return view('reportes');
    }

    public function show($id) 
    {
        // Quitamos el findOrFail. 
        // Solo devolvemos la vista 'reportes-show' y JS descargará los detalles.
        return view('reportes-show');
    }

    public function edit($id)
    {
        // Igual aquí, solo regresamos la vista 'reportes-edit' vacía para que JS la llene.
        return view('reportes-edit');
    }

    // =====================================================================
    // NOTA IMPORTANTE: 
    // Como ahora la actualización, cambio de estatus y eliminación 
    // se hacen DIRECTAMENTE en Firebase usando JavaScript desde el navegador,
    // estos métodos de Laravel ya no se ejecutarán desde tu panel web.
    // Los dejamos comentados/vacíos para evitar errores de ruteo.
    // =====================================================================

    public function update(Request $request, $id)
    {
        // Ya no se usa. La actualización se hará con JS en la vista reportes-edit.
    }

    public function updateStatus(Request $request, $id)
    {
        // Ya no se usa. El cambio de estatus ya lo programamos con JS en reportes-show.
    }

    public function destroy($id)
    {
        // Ya no se usa. La eliminación ya la programamos con JS en la vista reportes (index).
    }
}
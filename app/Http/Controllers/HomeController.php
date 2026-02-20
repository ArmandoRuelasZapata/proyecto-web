<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase; // <-- Importamos Firebase

class HomeController extends Controller
{
    // ¡ELIMINAMOS EL CONSTRUCTOR VIEJO!
    // Ya no lo necesitamos porque protegimos las rutas con 'firebase.auth' en web.php

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Opcional: Así puedes mandar los datos del usuario logueado a la vista
        $usuario = $request->attributes->get('firebase_user');
        
        return view('home', compact('usuario'));
    }

    public function users()
    {
        // REEMPLAZAMOS User::all() por la lista de Firebase
        $auth = Firebase::auth();
        $firebaseUsers = $auth->listUsers();
        
        $usuarios = [];
        foreach ($firebaseUsers as $user) {
            $usuarios[] = $user;
        }

        return view('users', compact('usuarios'));
    }

    public function crud()
    {
        return view('crud');
    }

    public function reportes()
    {
        return view('reportes');
    }

    public function moderadores()
    {
        return view('moderadores');
    }

    public function cuentasBloqueadas()
    {
        return view('cuentasbloqueadas');
    }

    public function solicitudes()
    {
        return view('solicitudes');
    }
}
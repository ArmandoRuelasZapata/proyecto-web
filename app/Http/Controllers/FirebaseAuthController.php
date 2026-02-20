<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Cookie;

class FirebaseAuthController extends Controller
{
    public function syncSession(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            // Validamos el token puramente con Firebase
            $auth = Firebase::auth();
            $verifiedIdToken = $auth->verifyIdToken($token);

            // Obtenemos el email
            $email = $verifiedIdToken->claims()->get('email');
            
            // Creamos la cookie con el token de Firebase. 
            // 60 minutos es la duración por defecto de los tokens de Firebase.
            $cookie = cookie('firebase_token', $token, 60);

            // Devolvemos la respuesta adjuntando la cookie segura
            return response()->json([
                'status' => 'success',
                'message' => 'Autenticado correctamente con Firebase',
                'user' => ['email' => $email]
            ])->withCookie($cookie);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error de Firebase: ' . $e->getMessage()], 401);
        }
    }

    // EXTRA: Agregamos un método para cerrar sesión (Borrar la cookie)
    public function logout()
    {
        $cookie = Cookie::forget('firebase_token');
        
        // Redirigimos a la página principal o al login y destruimos la cookie
        return redirect('/')->withCookie($cookie); 
    }
}
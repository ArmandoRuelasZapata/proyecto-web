<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class FirebaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Buscamos el token en las COOKIES
        $token = $request->cookie('firebase_token');

        if (!$token) {
            // CAMBIO AQUÍ: Usamos route('login') en lugar de '/login'
            if (!$request->expectsJson()) {
                return redirect()->route('login')->withErrors(['error' => 'Por favor, inicia sesión.']);
            }
            return response()->json(['error' => 'Acceso denegado. Token no proporcionado.'], 401);
        }

        try {
            // 2. Validamos el token con Firebase
            $auth = Firebase::auth();
            $verifiedIdToken = $auth->verifyIdToken($token);

            // 3. Guardamos los datos del usuario en la petición
            $request->attributes->add(['firebase_user' => $verifiedIdToken->claims()->all()]);

            // 4. Dejamos que la petición continúe
            return $next($request);

        } catch (\Exception $e) {
            // Si el token expiró, borramos la cookie
            $cookie = Cookie::forget('firebase_token');
            
            // CAMBIO AQUÍ TAMBIÉN: Usamos route('login') en lugar de '/login'
            if (!$request->expectsJson()) {
                return redirect()->route('login')->withCookie($cookie)->withErrors(['error' => 'Tu sesión ha expirado.']);
            }
            return response()->json(['error' => 'Token inválido o expirado.'], 401)->withCookie($cookie);
        }
    }
}
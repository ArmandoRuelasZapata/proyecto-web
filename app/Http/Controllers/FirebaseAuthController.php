<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http; // Necesario para la comunicación API REST

class FirebaseAuthController extends Controller
{
    /**
     * ID del Segundo Proyecto (Basado en tu configuración proporcionada)
     */
    private $secondProjectId = 'usuarios-798cc'; 

    /**
     * PROYECTO 1: Sincronización de sesión (Mantenido original)
     */
    public function syncSession(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            // Validamos el token puramente con Firebase del Proyecto Principal
            $auth = Firebase::auth();
            $verifiedIdToken = $auth->verifyIdToken($token);

            // Obtenemos el email
            $email = $verifiedIdToken->claims()->get('email');
            
            // Creamos la cookie con el token de Firebase (60 min)
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

    /**
     * PROYECTO 1: Cerrar sesión (Mantenido original)
     */
    public function logout()
    {
        $cookie = Cookie::forget('firebase_token');
        return redirect('/')->withCookie($cookie); 
    }

    /* -------------------------------------------------------------------------- */
    /* PROYECTO 2: GESTIÓN DE USUARIOS VÍA API REST (Sin necesidad de gRPC)        */
    /* -------------------------------------------------------------------------- */

    /**
     * Obtener lista de usuarios del segundo proyecto
     */
    public function cuentasBloqueadas()
    {
        // URL de la API REST de Firestore para el proyecto usuarios-798cc
        $url = "https://firestore.googleapis.com/v1/projects/{$this->secondProjectId}/databases/(default)/documents/usuarios";
        
        try {
            $response = Http::get($url);
            $usuariosBloqueados = [];

            if ($response->successful() && isset($response->json()['documents'])) {
                foreach ($response->json()['documents'] as $doc) {
                    $fields = $doc['fields'] ?? [];
                    
                    // Extraer el UID del nombre completo del documento
                    $namePath = explode('/', $doc['name']);
                    $uid = end($namePath);

                    // Formateamos los datos para la vista
                    $usuariosBloqueados[] = [
                        'uid'    => $uid,
                        'nombre' => $fields['nombre']['stringValue'] ?? ($fields['displayName']['stringValue'] ?? 'Sin nombre'),
                        'correo' => $fields['correo']['stringValue'] ?? ($fields['email']['stringValue'] ?? 'Sin correo'),
                        'estado' => $fields['estado']['stringValue'] ?? 'N/A'
                    ];
                }
            }

            return view('cuentasbloqueadas', compact('usuariosBloqueados'));

        } catch (\Exception $e) {
            return view('cuentasbloqueadas', ['usuariosBloqueados' => []])
                   ->with('error', 'Error al conectar con el segundo proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Bloquear un usuario manualmente por ID en el segundo proyecto
     */
    public function bloquearUsuarioPorId(Request $request)
    {
        $request->validate([
            'uid' => 'required|string'
        ]);

        $uid = $request->input('uid');

        // URL para actualizar campos específicos (PATCH)
        $url = "https://firestore.googleapis.com/v1/projects/{$this->secondProjectId}/databases/(default)/documents/usuarios/{$uid}?updateMask.fieldPaths=estado&updateMask.fieldPaths=fecha_actualizacion";

        try {
            // Enviamos la actualización en formato JSON de Firestore
            $response = Http::patch($url, [
                'fields' => [
                    'estado' => ['stringValue' => 'bloqueado'],
                    'fecha_actualizacion' => ['stringValue' => date('c')] // Formato ISO 8601
                ]
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Usuario ' . $uid . ' bloqueado correctamente.');
            }

            return redirect()->back()->with('error', 'No se pudo actualizar el usuario. Verifique el ID.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error de API REST: ' . $e->getMessage());
        }
    }

    /**
     * MODIFICADO: Cambia el estado a activo en lugar de borrar
     */
    public function eliminarUsuarioBloqueado($id)
    {
        // Usamos PATCH en lugar de DELETE para actualizar el campo estado a 'activo'
        $url = "https://firestore.googleapis.com/v1/projects/{$this->secondProjectId}/databases/(default)/documents/usuarios/{$id}?updateMask.fieldPaths=estado&updateMask.fieldPaths=fecha_actualizacion";

        try {
            // Enviamos el cambio de estado a 'activo'
            $response = Http::patch($url, [
                'fields' => [
                    'estado' => ['stringValue' => 'activo'],
                    'fecha_actualizacion' => ['stringValue' => date('c')]
                ]
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'El usuario ha sido reactivado correctamente.');
            }

            return redirect()->back()->with('error', 'No se pudo actualizar el estado en Firestore.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error en la operación de reactivación: ' . $e->getMessage());
        }
    }
    // ... dentro de FirebaseAuthController ...

/**
 * Mostrar lista de moderadores (Proyecto usuarios-798cc)
 */
public function indexModeradores()
{
    $url = "https://firestore.googleapis.com/v1/projects/{$this->secondProjectId}/databases/(default)/documents/moderadores";
    
    try {
        $response = Http::get($url);
        $moderadores = [];

        if ($response->successful() && isset($response->json()['documents'])) {
            foreach ($response->json()['documents'] as $doc) {
                $fields = $doc['fields'] ?? [];
                $namePath = explode('/', $doc['name']);
                $uid = end($namePath);

                // Creamos un objeto similar al que espera tu vista
                $moderadores[] = (object) [
                    'uid' => $uid,
                    'displayName' => $fields['nombre']['stringValue'] ?? 'Sin nombre',
                    'email' => $fields['correo']['stringValue'] ?? 'Sin correo',
                    'estado' => $fields['estado']['stringValue'] ?? 'activo'
                ];
            }
        }

        return view('moderadores', compact('moderadores'));

    } catch (\Exception $e) {
        return view('moderadores', ['moderadores' => []])->with('error', $e->getMessage());
    }
}

/**
 * Guardar nuevo moderador (Solo Firestore - El usuario debe existir en Auth o crearse manualmente)
 */
/**
 * Registro completo de Moderador: Auth + Firestore
 */
public function storeModerador(Request $request)
{
    $request->validate([
        'name'     => 'required|string',
        'email'    => 'required|email',
        'password' => 'required|min:6|confirmed', // 'confirmed' busca 'password_confirmation'
    ]);

    try {
        // 1. CREAR EN FIREBASE AUTHENTICATION
        // Usamos el SDK principal configurado en el Proyecto 1 (o el que gestione el Auth)
        $auth = Firebase::auth();
        
        $userProperties = [
            'email' => $request->email,
            'emailVerified' => false,
            'password' => $request->password,
            'displayName' => $request->name,
            'disabled' => false,
        ];

        $createdUser = $auth->createUser($userProperties);
        $newUid = $createdUser->uid; // Obtenemos el UID generado automáticamente

        // 2. CREAR EN FIRESTORE (Proyecto usuarios-798cc)
        // Usamos la API REST para insertar en la colección 'moderadores' con el nuevo UID
        $urlDestino = "https://firestore.googleapis.com/v1/projects/{$this->secondProjectId}/databases/(default)/documents/moderadores/{$newUid}";

        $response = Http::patch($urlDestino, [
            'fields' => [
                'nombre' => ['stringValue' => $request->name],
                'correo' => ['stringValue' => $request->email],
                'estado' => ['stringValue' => 'activo'], // Habilitado para la App
                'rol'    => ['stringValue' => 'moderador'],
                'fecha_registro' => ['stringValue' => date('c')]
            ]
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Moderador creado exitosamente en Auth y Firestore.');
        }

        return redirect()->back()->with('error', 'Se creó el acceso pero falló el registro en Firestore.');

    } catch (\Exception $e) {
        // Manejo de errores de Firebase (ej: el correo ya existe)
        return redirect()->back()->with('error', 'Error al registrar: ' . $e->getMessage());
    }
}

/**
 * Eliminar moderador (Igual que con usuarios bloqueados, usamos PATCH para desactivar o DELETE para borrar)
 */
public function destroyModerador($id)
{
    $url = "https://firestore.googleapis.com/v1/projects/{$this->secondProjectId}/databases/(default)/documents/moderadores/{$id}";

    try {
        // En moderadores, usualmente sí se borra el documento si ya no pertenece al equipo
        $response = Http::delete($url);
        return redirect()->back()->with('success', 'Moderador eliminado.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}

}
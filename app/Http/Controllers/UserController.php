<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importamos el contrato de Firebase Auth
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class UserController extends Controller
{
    protected $auth;

    // Inyectamos Firebase Auth a través del constructor
    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Muestra el formulario de creación exclusivo para Administradores.
     */
    public function create()
    {
        return view('auth.create-user'); 
    }

    /**
     * Guarda el nuevo usuario en Firebase Auth.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $userProperties = [
                'email' => $request->email,
                'emailVerified' => false,
                'password' => $request->password,
                'displayName' => $request->name,
                'disabled' => false,
            ];

            $this->auth->createUser($userProperties);

            return redirect()->to('leer-usuarios')->with('success', 'Nuevo administrador creado en Firebase correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de edición obteniendo datos de Firebase.
     */
    public function edit($id)
    {
        try {
            // Buscamos al usuario por su UID en Firebase
            $user = $this->auth->getUser($id);
            return view('edit-blade', compact('user')); 
        } catch (UserNotFound $e) {
            return redirect()->to('leer-usuarios')->with('error', 'El usuario no existe en Firebase.');
        }
    }

    /**
     * Actualiza los datos del usuario en Firebase.
     */
public function update(Request $request, $id)
{
    // 1. Cambiamos la validación para esperar 'display_name'
    $request->validate([
        'display_name' => 'required|string|max:255',
        'email'        => 'required|string|email|max:255',
    ]);

    try {
        $properties = [
            // 2. Tomamos el dato de 'display_name'
            'displayName' => $request->display_name, 
            'email'       => $request->email,
        ];

        $this->auth->updateUser($id, $properties);

        return redirect()->to('leer-usuarios')->with('success', 'Usuario actualizado correctamente en Firebase');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}

    /**
     * Elimina un usuario de Firebase Auth.
     */
    public function destroy($id)
    {
        try {
            $this->auth->deleteUser($id);
            return redirect()->back()->with('status', 'Usuario eliminado de Firebase correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el usuario: ' . $e->getMessage());
        }
    }
}
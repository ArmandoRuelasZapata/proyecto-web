<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReporteWebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ForgotPasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// Rutas Públicas (No requieren login)
// ==========================================
Route::view('/', 'index');
Route::view('contacto', 'contact');
Route::post('guardar-contacto', [ContactController::class, 'store']);

// Vistas de Login y Registro
Route::view('/login', 'auth.login')->name('login'); 
Route::view('/register', 'auth.register')->name('register');

Route::get('password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

// Recuperación de contraseña
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Procesamiento de Login Firebase (Sincronización de sesión)
Route::post('/firebase-login', [FirebaseAuthController::class, 'syncSession'])->name('firebase.login');

// Cerrar sesión
Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');


// ==========================================
// RUTAS PROTEGIDAS (Middleware de Firebase)
// ==========================================
Route::group(['middleware' => ['firebase.auth']], function() {
    
    // Dashboard principal
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Vistas generales y reportes
    Route::get('leer-contactos', [ContactController::class, 'index']);
    Route::get('crud', [HomeController::class, 'crud']);
    Route::get('reportes-vista', [HomeController::class, 'reportes']);
    Route::get('moderadores', [HomeController::class, 'moderadores']);
    Route::get('solicitudes', [HomeController::class, 'solicitudes']);

    // Gestión de Usuarios (Proyecto Principal)
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios/guardar', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('leer-usuarios', [HomeController::class, 'users']);

    // ==========================================
    // SECCIÓN: Cuentas Bloqueadas (Proyecto: usuarios-798cc vía REST)
    // ==========================================
    
    // 1. Listar las cuentas bloqueadas
    Route::get('/cuentasbloqueadas', [FirebaseAuthController::class, 'cuentasBloqueadas'])->name('cuentasbloqueadas');

    // 2. Bloquear un usuario nuevo (Desde el modal)
    Route::post('/bloquear-usuario-id', [FirebaseAuthController::class, 'bloquearUsuarioPorId'])->name('usuario.bloquear');

    // 3. Eliminar el registro (Conectado al método REST del controlador)
    Route::delete('/eliminar-usuario-bloqueado/{id}', [FirebaseAuthController::class, 'eliminarUsuarioBloqueado'])->name('usuario.eliminar_bloqueado');

    // Seccion de MODERADORES
    Route::get('/moderadores', [FirebaseAuthController::class, 'indexModeradores']);
    Route::post('/guardar-moderador', [FirebaseAuthController::class, 'storeModerador']);
    Route::delete('/eliminar-moderador/{id}', [FirebaseAuthController::class, 'destroyModerador']);

    // ==========================================
    // Gestión de Reportes
    // ==========================================
    Route::get('/reportes', [ReporteWebController::class, 'index']);
    Route::get('/reportes/{id}', [ReporteWebController::class, 'show'])->name('reportes.show'); 
    Route::get('/reportes/{id}/edit', [ReporteWebController::class, 'edit'])->name('reportes.edit'); 
    Route::put('/reportes/{id}', [ReporteWebController::class, 'update'])->name('reportes.update'); 
    Route::patch('/reportes/{id}/status', [ReporteWebController::class, 'updateStatus'])->name('reportes.updateStatus');
    Route::delete('/reportes/{id}', [ReporteWebController::class, 'destroy'])->name('reportes.destroy');
});
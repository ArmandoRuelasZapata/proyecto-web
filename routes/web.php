<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReporteWebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\HomeController;

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

// AGREGADO: Rutas para mostrar tus vistas HTML de Login y Registro
// OJO: Si tu archivo login.blade.php está directo en la carpeta views, pon solo 'login'. 
// Si está dentro de la carpeta auth, déjalo como 'auth.login'
Route::view('/login', 'auth.login')->name('login'); 
Route::view('/register', 'auth.register')->name('register');

// Ruta que recibe el token desde tu JavaScript (Login real)
Route::post('/firebase-login', [FirebaseAuthController::class, 'syncSession'])->name('firebase.login');

// AGREGADO: Ruta para cerrar sesión y borrar la cookie de Firebase
Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');


// ==========================================
// RUTAS PROTEGIDAS (Solo Firebase)
// ==========================================
Route::group(['middleware' => ['firebase.auth']], function() {
    
    // Metimos /home aquí adentro para protegerla
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Vistas generales
    Route::get('leer-contactos', [ContactController::class, 'index']);
    Route::get('crud', [HomeController::class, 'crud']);
    Route::get('reportes-vista', [HomeController::class, 'reportes']);
    Route::get('moderadores', [HomeController::class, 'moderadores']);
    Route::get('cuentasbloqueadas', [HomeController::class, 'cuentasbloqueadas']);
    Route::get('solicitudes', [HomeController::class, 'solicitudes']);

    // Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios/guardar', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('leer-usuarios', [HomeController::class, 'users']);

    // Reportes
    Route::get('/reportes', [ReporteWebController::class, 'index']);
    Route::get('/reportes/{id}', [ReporteWebController::class, 'show'])->name('reportes.show'); 
    Route::get('/reportes/{id}/edit', [ReporteWebController::class, 'edit'])->name('reportes.edit'); 
    Route::put('/reportes/{id}', [ReporteWebController::class, 'update'])->name('reportes.update'); 
    Route::patch('/reportes/{id}/status', [ReporteWebController::class, 'updateStatus'])->name('reportes.updateStatus');
    Route::delete('/reportes/{id}', [ReporteWebController::class, 'destroy'])->name('reportes.destroy');
});
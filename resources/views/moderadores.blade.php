@extends('layouts.app')

@section('styles')
<style>
    body { background: #f2f4f7; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
    .page-wrapper { display: flex; flex-grow: 1; }
    
    /* SIDEBAR STYLES (Sincronizado con Cuentas Bloqueadas) */
    .sidebar {
        width: 260px;
        min-height: calc(100vh - 66px);
        position: sticky;
        top: 66px;
        background: #0c8e8a;
        border-right: 1px solid #086b6a;
        padding-top: 10px;
    }
    .sidebar a {
        display: flex; align-items: center; padding: 14px 20px; color: white;
        font-size: 16px; text-decoration: none; transition: 0.2s;
    }
    .sidebar a:hover { background: #086b6a; }
    .sidebar a.active { background: #f2f4f7; color: #333; border-radius: 4px; margin: 0 10px; font-weight: 600; }
    .menu-icon { width: 35px; height: 35px; margin-right: 15px; filter: invert(); object-fit: contain; }
    .active .menu-icon { filter: invert(0); }

    /* CONTENT STYLES */
    .main-content { flex-grow: 1; padding: 30px; }
    .custom-card {
        border: none; border-radius: 15px; background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); overflow: hidden;
    }
    .custom-card-header {
        background-color: #0c8e8a; 
        color: white; padding: 15px 25px; font-size: 1.1rem;
        display: flex; justify-content: space-between; align-items: center;
    }

    /* BUTTONS */
    .btn-add-mod {
        background-color: #ffffff;
        color: #0c8e8a;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    .btn-add-mod:hover {
        background-color: #e8f6f6;
        transform: translateY(-1px);
    }
    
    .table thead { background-color: #e8f6f6; color: #0c8e8a; }
    .action-icon { background: none; border: none; font-size: 1.2rem; transition: 0.2s; padding: 5px 10px; cursor: pointer; }
    .btn-edit { color: #0c8e8a; }
    .btn-delete { color: #dc3545; }
    .btn-edit:hover, .btn-delete:hover { transform: scale(1.1); }

    /* MODAL & FORM CUSTOMS */
    .modal-content { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .form-label-custom { color: #555; font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; }
    .form-control-custom { 
        width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; transition: 0.2s;
    }
    .form-control-custom:focus { border-color: #0c8e8a; box-shadow: 0 0 0 3px rgba(12, 142, 138, 0.1); }
    
    .btn-save-custom {
        background: #0c8e8a; color: white; border: none; padding: 10px 25px;
        border-radius: 8px; font-weight: 600; transition: 0.3s;
    }
    .btn-save-custom:hover { background: #086b6a; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="page-wrapper">
    {{-- SIDEBAR UNIFICADO --}}
    <div class="sidebar">
        <a href="{{ url('home') }}"><img src="{{ asset('img/informe-de-datos.png') }}" class="menu-icon"> Dashboard</a>
        <a href="{{ url('crud') }}"><img src="{{ asset('img/red-mundial.png') }}" class="menu-icon"> Reportes públicos</a>
        <a href="{{ url('reportes') }}"><img src="{{ asset('img/tus reportes.png') }}" class="menu-icon"> Reportes</a>
        <a href="{{ url('moderadores') }}" class="active"><img src="{{ asset('img/proteger.png') }}" class="menu-icon"> Moderadores</a>
        <a href="{{ url('leer-usuarios') }}"><img src="{{ asset('img/admin.png') }}" class="menu-icon"> Administradores</a>
        <a href="{{ url('leer-contactos') }}"><img src="{{ asset('img/contacts.png') }}" class="menu-icon"> Contactos</a>
        <a href="{{ url('cuentasbloqueadas') }}"><img src="{{ asset('img/cuenta-privada.png') }}" class="menu-icon"> Cuentas bloqueadas</a>
        <a href="{{ url('solicitudes') }}"><img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Soporte</a>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="custom-card">
                <div class="custom-card-header">
                    <strong>Moderadores</strong>
                    <button type="button" class="btn-add-mod" data-bs-toggle="modal" data-bs-target="#modalAddModerador">
                        <i class="fa-solid fa-user-plus me-2"></i> Nuevo Moderador
                    </button>
                </div>

                <div class="p-4 border-bottom bg-light">
                    <p class="text-muted mb-0">Personal autorizado para gestionar reportes</p>
                </div>

                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="150">UID</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Estado</th>
                                <th class="text-center" width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($moderadores as $mod)
                            <tr>
                                <td class="text-muted"><small>{{ substr($mod->uid, 0, 12) }}...</small></td>
                                <td class="fw-bold">{{ $mod->displayName }}</td>
                                <td>{{ $mod->email }}</td>
                                <td><span class="badge bg-success">Activo</span></td>
                                <td class="text-center">
                                    <form action="{{ url('eliminar-moderador/'.$mod->uid) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar permanentemente este moderador?')">
                                        @csrf @method('DELETE')
                                        <button class="action-icon btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No hay moderadores registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL AGREGAR (ESTILO MEJORADO) --}}
<div class="modal fade" id="modalAddModerador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="color: #0c8e8a;">Registrar Moderador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('guardar-moderador') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Nombre Completo</label>
                        <input type="text" name="name" class="form-control-custom" placeholder="Ej. Ana Martínez" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control-custom" placeholder="moderador@movidgo.com" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label-custom">Contraseña</label>
                            <input type="password" name="password" class="form-control-custom" placeholder="Mín. 6" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label-custom">Confirmar</label>
                            <input type="password" name="password_confirmation" class="form-control-custom" placeholder="Repetir" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-save-custom">Crear Acceso</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lógica de carga de datos para editar si lo necesitas en el futuro
        const editModal = document.getElementById('modalEditModerador');
        // ... mismo JS de asignación de datos ...
    });
</script>
@endsection
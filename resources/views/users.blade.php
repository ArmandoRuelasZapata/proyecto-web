@extends('layouts.app')

@section('styles')
<style>
    body {
        background: #f2f4f7;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .page-wrapper {
        display: flex;
        flex-grow: 1;
    }

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
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: white;
        font-size: 16px;
        text-decoration: none;
        transition: 0.2s;
    }

    .sidebar a:hover {
        background: #086b6a;
    }

    .sidebar a.active {
        background: #f2f4f7;
        color: #333;
        border-radius: 4px;
        margin: 0 10px;
    }

    .menu-icon {
        width: 35px;
        height: 35px;
        margin-right: 15px;
        filter: invert();
        object-fit: contain;
    }

    .active img {
        filter: invert(0);
    }

    /* CONTENIDO PRINCIPAL */
    .main-content {
        flex-grow: 1;
        padding: 30px;
    }

    .custom-card {
        border: none;
        border-radius: 15px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .custom-card-header {
        background-color: #0c8e8a;
        color: white;
        padding: 15px 25px;
        font-size: 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-add-admin {
        background-color: #ffffff;
        color: #0c8e8a;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-add-admin:hover {
        background-color: #e8f6f6;
        color: #086b6a;
        transform: translateY(-1px);
    }

    .table thead {
        background-color: #e8f6f6;
        color: #0c8e8a;
    }

    .action-btn {
        background: none;
        border: none;
        font-size: 1.1rem;
        transition: color 0.2s;
        padding: 5px 10px;
    }

    .btn-edit { color: #0c8e8a; }
    .btn-edit:hover { color: #086b6a; }
    .btn-delete { color: #dc3545; }
    .btn-delete:hover { color: #a71d2a; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="page-wrapper">

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <a href="{{ url('home') }}">
            <img src="{{ asset('img/informe-de-datos.png') }}" alt="Dashboard" class="menu-icon">
            Dashboard
        </a>
        <a href="{{ url('crud') }}">
            <img src="{{ asset('img/red-mundial.png') }}" alt="Reportes públicos" class="menu-icon">
            Reportes públicos
        </a>
        <a href="{{ url('reportes') }}">
            <img src="{{ asset('img/tus reportes.png') }}" alt="Reportes" class="menu-icon">
            Reportes
        </a>
        <a href="{{ url('moderadores') }}">
            <img src="{{ asset('img/proteger.png') }}" alt="Moderadores" class="menu-icon">
            Moderadores
        </a>
        <a href="{{ url('leer-usuarios') }}" class="active">
            <img src="{{ asset('img/admin.png') }}" alt="Moderadores" class="menu-icon">
            Administradores
        </a>
        <a href="{{ url('leer-contactos') }}">
            <img src="{{ asset('img/contacts.png') }}" alt="Moderadores" class="menu-icon">
            Contactos
        </a>
        <a href="{{ url('cuentasbloqueadas') }}">
            <img src="{{ asset('img/cuenta-privada.png') }}" alt="Cuentas bloqueadas" class="menu-icon">
            Cuentas bloqueadas
        </a>
        <a href="{{ url('solicitudes') }}">
            <img src="{{ asset('img/soporte y contacto.png') }}" alt="Contactos" class="menu-icon">
            Solicitudes
        </a>
    </div>

    {{-- CONTENIDO --}}
    <div class="main-content">
        <div class="container-fluid">
            <div class="custom-card">
                <div class="custom-card-header">
                    <strong>Gestión de Administradores (Firebase)</strong>
                    <a href="{{ route('users.create') }}" class="btn-add-admin">
                        <i class="fa-solid fa-plus me-2"></i> Agregar Administrador
                    </a>
                </div>

                <div class="p-4 border-bottom">
                    <p class="text-muted mb-0">
                        Visualiza, edita o elimina los administradores gestionados a través de Firebase Auth.
                    </p>
                </div>

                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="120">UID</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Último Acceso</th>
                                <th class="text-center" width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $user)
                            <tr>
                                {{-- Firebase usa 'uid'. Mostramos los últimos 6 caracteres para no saturar la tabla --}}
                                <td class="text-muted"><small>...{{ substr($user->uid, -8) }}</small></td>
                                
                                {{-- En Firebase usuario = 'displayName' --}}
                                <td class="fw-bold">{{ $user->displayName ?? 'Usuario sin nombre' }}</td>
                                
                                <td>{{ $user->email }}</td>

                                {{-- Accedemos a los datos de Firebase --}}
                                <td>
                                    {{ $user->metadata->lastLoginAt ? $user->metadata->lastLoginAt->format('d/m/Y H:i') : 'Nunca' }}
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('users.edit', $user->uid) }}" class="action-btn btn-edit" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('users.destroy', $user->uid) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar permanentemente a este usuario de Firebase?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" title="Eliminar">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No se encontraron usuarios en Firebase.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
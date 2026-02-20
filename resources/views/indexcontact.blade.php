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
    /* SIDEBAR ESTILO ORIGINAL */
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
    .sidebar a:hover { background: #086b6a; }
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
    .active img { filter: invert(0); }

    /* CONTENIDO PRINCIPAL */
    .main-content {
        flex-grow: 1; 
        padding: 30px;
    }

    /* ESTILO DE LA TABLA Y CARD */
    .custom-card {
        border: none;
        border-radius: 15px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .custom-card-header {
        background-color: #0c8e8a;
        color: white;
        padding: 15px 25px;
        font-size: 1.1rem;
    }
    .table thead {
        background-color: #e8f6f6;
        color: #0c8e8a;
    }
    .table-responsive {
        padding: 20px;
    }
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
        <a href="{{ url('leer-usuarios') }}">
            <img src="{{ asset('img/admin.png') }}" alt="Moderadores" class="menu-icon">
            Administradores
        </a>
        <a href="{{ url("leer-contactos") }}" class="active">
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

    {{-- CONTENIDO DE LA TABLA --}}
    <div class="main-content">
        <div class="container-fluid">
            <div class="custom-card">
                <div class="custom-card-header">
                    <strong>Contactos guardados</strong>
                </div>
                
                <div class="p-4">
                    <p class="text-muted">
                        Aquí puedes visualizar los mensajes enviados a través del formulario de contacto del sitio.
                    </p>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Prioridad</th>
                                <th>Asunto</th>
                                <th>Mensaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mensajes as $mensaje)
                            <tr>
                                <td class="fw-bold">{{ $mensaje->nombre }}</td>
                                <td>{{ $mensaje->correo }}</td>
                                <td>
                                    @if($mensaje->prioridad == 'alta')
                                        <span class="badge bg-danger text-uppercase">Alta</span>
                                    @elseif($mensaje->prioridad == 'media')
                                        <span class="badge bg-warning text-dark text-uppercase">Media</span>
                                    @else
                                        <span class="badge bg-info text-uppercase">Baja</span>
                                    @endif
                                </td>
                                <td>{{ $mensaje->asunto }}</td>
                                <td class="text-muted small">{{ $mensaje->mensaje }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
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
    .sidebar a:hover { background: #086b6a; }
    .sidebar a.active {
        background: #f2f4f7;
        color: #333;
        border-radius: 4px;
        margin: 0 10px;
    }

    /* === NUEVA ESTRUCTURA CENTRADA === */
    .main-content {
        flex-grow: 1; 
        display: flex;
        align-items: center; 
        justify-content: center; 
        padding: 30px;
    }

    .welcome-container {
        text-align: center;
        animation: fadeIn 0.8s ease-in-out;
    }

    .welcome-logo {
        width: 300px; 
        height: auto;
        margin-bottom: 20px;
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1));
    }

    .welcome-message {
        color: #1d1d1f;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .welcome-message h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .welcome-message p {
        font-size: 1.2rem;
        color: #6e6e73;
    }

    .menu-icon {
        width: 35px;
        height: 35px;
        margin-right: 15px;
        filter: invert();
        object-fit: contain;
    }
    .active img { filter: invert(0); }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')

<div class="page-wrapper">

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <a href="{{ url('home') }}" class="active">
            <img src="{{ asset('img/informe-de-datos.png') }}" alt="Icono Reportes" class="menu-icon">
            Dashboard
        </a>
        <a href="{{ url('crud') }}">
            <img src="{{ asset('img/red-mundial.png') }}" alt="red-mundial" class="menu-icon">
            Reportes públicos
        </a>
        <a href="{{ url('reportes') }}"> 
            <img src="{{ asset('img/tus reportes.png') }}" alt="Icono Reportes" class="menu-icon">
            Reportes
        </a>
        <a href="{{ url('moderadores') }}">
            <img src="{{ asset('img/proteger.png') }}" alt="Icono Reportes" class="menu-icon">
            Moderadores
        </a>
        <a href="{{ url('leer-usuarios') }}">
            <img src="{{ asset('img/admin.png') }}" alt="Moderadores" class="menu-icon">
            Administradores
        </a>
        <a href="{{ url("leer-contactos") }}">
            <img src="{{ asset('img/contacts.png') }}" alt="Moderadores" class="menu-icon">
            Contactos
        </a>
        <a href="{{ url('cuentasbloqueadas') }}">
            <img src="{{ asset('img/cuenta-privada.png') }}" alt="Icono Reportes" class="menu-icon">
            Cuentas bloqueadas
        </a>
        <a href="{{ url('solicitudes') }}">
            <img src="{{ asset('img/soporte y contacto.png') }}" alt="Icono Reportes" class="menu-icon">
            Solicitudes
        </a>
    </div>

    {{-- CONTENIDO PRINCIPAL CENTRADO --}}
    <div class="main-content">
        <div class="welcome-container">
            <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo MovidGO" class="welcome-logo">
            
            <div class="welcome-message">
                <h1>¡Bienvenido!</h1>
                <p>Panel de administración de <strong>MoviDGO</strong>.</p>
                <p class="small text-muted">Has iniciado sesión correctamente.</p>
            </div>
        </div>
    </div>

</div>

@endsection
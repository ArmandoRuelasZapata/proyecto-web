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
        /* La altura se ajusta al contenido o al viewport, ya no es 100vh porque el header está arriba */
        min-height: calc(100vh - 66px); /* 100vh menos la altura aproximada de la navbar */
        position: sticky; 
        top: 66px; 
        background: #0c8e8a; /* Color del header original para consistencia */
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
        background: #f2f4f7; /* Fondo gris claro */
        color: #333; /* Texto oscuro */
        border-radius: 4px;
        margin: 0 10px;
    }

    /* === CONTENT (Contenido Principal) === */
    .main-content {
        flex-grow: 1; 
        padding: 30px;
        padding-left: 20px; 
    }

    .content-box {
        background: #ffffff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding-bottom: 0; /* Ajuste para que el borde inferior se vea limpio */
        overflow-y: auto; /* Para simular el scroll de la imagen de referencia */
        max-height: calc(100vh - 150px); /* Altura máxima para que se vea el scroll, ajustado a la vista */
    }

    /* === Lista de Moderadores (Adaptación del .reportes-list) === */
    .moderadores-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .moderador-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .moderador-item:last-child {
        border-bottom: none;
        padding-bottom: 15px; /* Dejamos un padding al final del último elemento */
    }

    /* Imagen de perfil del moderador */
    .moderador-avatar {
        width: 60px; /* Tamaño de la imagen como en la referencia */
        height: 60px;
        border-radius: 5px; /* Bordes ligeramente redondeados */
        object-fit: cover;
        margin-right: 20px;
    }

    /* ID/Detalles del moderador */
    .moderador-id {
        flex-grow: 1;
        font-size: 1.1em;
        font-weight: 500;
        color: #333;
    }
    
    /* Contenedor de Acciones (Editar/Eliminar) */
    .moderador-actions {
        display: flex;
        gap: 30px; /* Más espacio entre los íconos como en la imagen */
        margin-right: 15px; /* Espacio a la derecha */
    }

    .moderador-actions button {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.3em;
        color: #555;
        padding: 0; /* Eliminar padding por defecto del botón */
        width: 30px; /* Ancho fijo para centrar */
        text-align: center;
        transition: color 0.2s;
    }

    .moderador-actions button:hover {
        color: #0c8e8a;
    }

    /* === Barra de búsqueda (Adaptado para coincidir más con la posición de la imagen) === */
    .main-header {
        display: flex;
        justify-content: flex-end; /* Mueve la barra de búsqueda a la derecha */
        margin-bottom: 20px; /* Espacio antes del título */
        padding-right: 25px; /* Alineación con el padding del content-box */
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 250px; 
    }
    
    /* Estilo para el título del contenido */
    .content-title {
        margin-bottom: 10px;
        padding: 0 25px; /* Alineación con la búsqueda y la lista */
    }
    .menu-icon {
    width: 35px;
    /* Nuevo tamaño más pequeño */
    height: 35px;
    /* Nuevo tamaño más pequeño */
    margin-right: 15px;
    filter: invert();
    object-fit: contain;
}
.active img {
    filter: invert(0);
}

</style>
{{-- Incluir Font Awesome si no está ya en layouts.app --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')

<div class="page-wrapper">

    {{-- SIDEBAR --}}
    <div class="page-wrapper">

    <div class="sidebar">
        <a href="{{ url("home") }}"><img src="{{ asset('img/informe-de-datos.png') }}" alt="Icono Reportes" class="menu-icon">
            Dashboard</a>
        <a href="{{ url("crud") }}">
            <img src="{{ asset('img/red-mundial.png') }}" alt="red-mundial" class="menu-icon">
            Reportes públicos
        </a>
        <a href="{{ url('reportes') }}"> <img src="{{ asset('img/tus reportes.png') }}" alt="Icono Reportes" class="menu-icon">
            Reportes</a>
        <a href="{{ url("moderadores") }}"><img src="{{ asset('img/proteger.png') }}" alt="Icono Reportes" class="menu-icon">
            Moderadores</a>
            <a href="{{ url('leer-usuarios') }}">
            <img src="{{ asset('img/admin.png') }}" alt="Moderadores" class="menu-icon">
            Administradores
        </a>
        <a href="{{ url("leer-contactos") }}">
            <img src="{{ asset('img/contacts.png') }}" alt="Moderadores" class="menu-icon">
            Contactos
        </a>
        <a href="{{ url("cuentasbloqueadas") }}" class="active"><img src="{{ asset('img/cuenta-privada.png') }}" alt="Icono Reportes" class="menu-icon">
            Cuentas bloqueadas</a>
        <a href="{{ url("solicitudes") }}"><img src="{{ asset('img/soporte y contacto.png') }}" alt="Icono Reportes" class="menu-icon">
            Soporte</a>
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="main-content">
        
        {{-- Barra de búsqueda --}}
        <div class="main-header">
            <div class="search-box">
                <input type="text" placeholder="Search Maja" /> {{-- Placeholder de la imagen --}}
            </div>
        </div>
        
        <h2 class="content-title"><strong>Cuentas bloqueadas</strong></h2>

        <div class="content-box">
            
            <ul class="moderadores-list">
                
                {{-- Moderador 1 --}}
                <li class="moderador-item">
                    {{-- Usa una imagen genérica o placeholder. En un entorno real, sería una URL dinámica. --}}
                    <img src="https://via.placeholder.com/60/0c8e8a/FFFFFF?text=P1" alt="Avatar Moderador" class="moderador-avatar" />
                    <div class="moderador-id">8204915</div>
                    <div class="moderador-actions">
                        <button title="Editar"><i class="fa-solid fa-pen"></i></button>
                        <button title="Eliminar"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </li>
                
                {{-- Moderador 2 --}}
                <li class="moderador-item">
                    <img src="https://via.placeholder.com/60/0c8e8a/FFFFFF?text=P2" alt="Avatar Moderador" class="moderador-avatar" />
                    <div class="moderador-id">1059327</div>
                    <div class="moderador-actions">
                        <button title="Editar"><i class="fa-solid fa-pen"></i></button>
                        <button title="Eliminar"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </li>
                
                {{-- Moderador 3 --}}
                <li class="moderador-item">
                    <img src="https://via.placeholder.com/60/0c8e8a/FFFFFF?text=P3" alt="Avatar Moderador" class="moderador-avatar" />
                    <div class="moderador-id">6640218</div>
                    <div class="moderador-actions">
                        <button title="Editar"><i class="fa-solid fa-pen"></i></button>
                        <button title="Eliminar"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </li>
                
                {{-- Moderador 4 --}}
                <li class="moderador-item">
                    <img src="https://via.placeholder.com/60/0c8e8a/FFFFFF?text=P4" alt="Avatar Moderador" class="moderador-avatar" />
                    <div class="moderador-id">3915704</div>
                    <div class="moderador-actions">
                        <button title="Editar"><i class="fa-solid fa-pen"></i></button>
                        <button title="Eliminar"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </li>
                
                {{-- Moderador 5 --}}
                <li class="moderador-item">
                    <img src="https://via.placeholder.com/60/0c8e8a/FFFFFF?text=P5" alt="Avatar Moderador" class="moderador-avatar" />
                    <div class="moderador-id">7002856</div>
                    <div class="moderador-actions">
                        <button title="Editar"><i class="fa-solid fa-pen"></i></button>
                        <button title="Eliminar"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </li>

            </ul>

        </div> {{-- Fin .content-box --}}

    </div> {{-- Fin .main-content --}}

</div> {{-- Fin .page-wrapper --}}

@endsection
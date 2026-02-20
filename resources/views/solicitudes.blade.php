@extends('layouts.app')

@section('styles')
<style>
    /* Aseguramos que el body esté en el color deseado y que el contenido principal sea flexible */
    body {
        background: #f2f4f7;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    /* === Layout de dos columnas después del Header === */
    .page-wrapper {
        display: flex;
        flex-grow: 1; /* Para que ocupe el espacio restante */
    }

    /* === SIDEBAR (Barra de Navegación Vertical) === */
    .sidebar {
        width: 260px;
        /* La altura se ajusta al contenido o al viewport, ya no es 100vh porque el header está arriba */
        min-height: calc(100vh - 66px); /* 100vh menos la altura aproximada de la navbar */
        position: sticky; /* Sticky para que se quede al hacer scroll, o fixed si quieres que se quede pegado */
        top: 66px; /* Bajamos el sidebar justo debajo de la navbar */
        left: 0;
        background: #0c8e8a; /* Color del header original para consistencia */
        border-right: 1px solid #086b6a;
        padding-top: 10px;
    }
    
    .sidebar a {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: white; /* Texto blanco para el color de fondo */
        font-size: 16px;
        text-decoration: none;
        transition: 0.2s;
    }

    .sidebar a:hover {
        background: #086b6a; /* Un color ligeramente más oscuro al pasar el ratón */
    }

    .sidebar a.active {
        background: #f2f4f7; /* Fondo gris claro como en la Imagen 2 */
        color: #333; /* Texto oscuro para el fondo claro */
        border-radius: 4px;
        margin: 0 10px;
    }

    /* === CONTENT (Contenido Principal) === */
    .main-content {
        flex-grow: 1; /* Ocupa el espacio restante */
        padding: 30px;
        padding-left: 20px; /* Ajustamos padding si es necesario */
        display: flex; /* Nuevo: para organizar los bloques de reportes */
        flex-direction: column; /* Nuevo: para que los bloques se apilen verticalmente */
        gap: 30px; /* Espacio entre los bloques de reportes */
    }

    .content-box {
        background: #ffffff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow-y: auto; 
        max-height: 400px; 
    }
    
    .report-section-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    .reportes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .reportes-list-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        cursor: pointer; 
    }

    .reportes-list-item:hover .report-title {
        color: #0c8e8a; 
    }

    .report-icon {
        font-size: 1.8em; 
        color: #777;
        margin-right: 15px;
        width: 30px;
        text-align: center;
    }
    
    .report-details {
        flex-grow: 1;
    }

    .report-title {
        font-weight: 500; 
        color: #333;
        transition: color 0.2s;
    }


    /* === Filtro y búsqueda (Adaptado para coincidir más con la posición de la imagen) === */
    .main-header {
        display: flex;
        justify-content: flex-end; /* Mueve la barra de búsqueda a la derecha */
        margin-bottom: 20px; /* Espacio antes de los bloques de contenido */
    }

    .search-box input {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 250px; /* Ancho fijo para simular el campo de búsqueda de la imagen */
        text-align: right;
    }
    
    /* Nueva clase para simular el nombre del reporte como en la imagen */
    .report-name {
        font-size: 1rem;
        color: #555;
    }
    .content-title {
        margin-bottom: 10px;
        padding: 0 25px; /* Alineación con la búsqueda y la lista */
    }
    .menu-icon {
    width: 35px;
    height: 35px;
    margin-right: 15px;
    object-fit: contain;
    filter: invert();
}
.active img {
    filter: invert(0);
}

</style>
{{-- Añadimos la librería de íconos si no está ya en layouts.app --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')

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
        <a href="{{ url("cuentasbloqueadas") }}"><img src="{{ asset('img/cuenta-privada.png') }}" alt="Icono Reportes" class="menu-icon">
            Cuentas bloqueadas</a>
        <a href="{{ url("solicitudes") }}" class="active"><img src="{{ asset('img/soporte y contacto.png') }}" alt="Icono Reportes" class="menu-icon">
            Solicitudes</a>
    </div>

    <div class="main-content">
        
        {{-- Barra de búsqueda en la parte superior derecha del contenido --}}
        <div class="main-header">
            <div class="search-box">
                <input type="text" placeholder="Search..." /> 
            </div>
        </div>

        <h2 class="content-title"><strong>Solicitudes</strong></h2>

        {{-- Bloque 1: Falla al crear reporte --}}
        <div class="content-box">
            <h2 class="report-section-title">Fallo en crear reporte</h2> 
            
            <ul class="reportes-list">
                
                {{-- Elemento 1: Falla al crear reporte --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #01_17:02_11/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 2: Falla al crear reporte --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #02_22:15_12/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 3: Falla al crear reporte --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #01_17:02_11/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 4: Falla al crear reporte --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #02_22:15_12/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 5: Falla al crear reporte --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #01_17:02_11/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 6: Falla al crear reporte --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #02_22:15_12/11/2025</div>
                    </div>
                </li>

            </ul>

        </div>

        {{-- Bloque 2: Llamar a operador --}}
        <div class="content-box">
            <h2 class="report-section-title">LLamar a un operador</h2> 
            
            <ul class="reportes-list">
                
                {{-- Elemento 1: Llamar a operador --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #01_17:02_11/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 2: Llamar a operador--}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #02_22:15_12/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 3: Llamar a operador --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #01_17:02_11/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 4: Llamar a operador --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #02_22:15_12/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 5: Llamar a operador --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #01_17:02_11/11/2025</div>
                    </div>
                </li>
                
                {{-- Elemento 6: Llamar a operador --}}
                <li class="reportes-list-item">
                    <span class="report-icon"><i class="fa-solid fa-file-lines"></i></span>
                    <div class="report-details">
                        <div class="report-name">Solicitud #02_22:15_12/11/2025</div>
                    </div>
                </li>

            </ul>

        </div>

    </div>

</div>

@endsection
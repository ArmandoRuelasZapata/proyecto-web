@extends('layouts.app')

@section('content')
<div class="container mt-5">
    
    {{-- Contenedor dinámico para alertas de JavaScript --}}
    <div id="alert-container"></div>

    {{-- Pantalla de carga mientras Firebase trae los datos --}}
    <div id="loading-spinner" class="text-center py-5">
        <i class="fa-solid fa-spinner fa-spin fa-3x text-secondary mb-3"></i>
        <h4 class="text-muted">Cargando detalles del reporte...</h4>
    </div>

    {{-- Contenido principal --}}
    <div id="report-content" class="card shadow-sm border-0 d-none">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h2 class="mb-0 h4 text-uppercase fw-bold text-secondary">
                Detalle del Reporte #<span id="rep-header-id"></span>
            </h2>
            
            <div class="d-flex gap-2">
                {{-- El botón editar esta asignado desde JS --}}
                <a href="#" id="btn-edit-report" class="btn btn-warning btn-sm fw-bold shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
                <a href="{{ url('/reportes') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0" id="rep-titulo">Cargando...</h3>
                <span id="rep-badge-estatus" class="badge rounded-pill p-2 px-4 shadow-sm bg-primary">
                    <i class="fa-solid fa-circle-info"></i> <span id="rep-estatus-text">Cargando...</span>
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1 text-muted small text-uppercase">Fecha de recepción</p>
                    <p class="fw-bold">
                        <i class="fa-regular fa-calendar-days text-primary"></i> <span id="rep-fecha">--/--/----</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted small text-uppercase">Tipo de incidencia</p>
                    <p class="fw-bold text-capitalize">
                        <i class="fa-solid fa-triangle-exclamation text-danger"></i> <span id="rep-tipo">---------</span>
                    </p>
                </div>
                <div class="col-md-12">
                    <p class="mb-1 text-muted small text-uppercase">Ubicación</p>
                    <p class="fw-bold">
                        <i class="fa-solid fa-location-dot text-success"></i> <span id="rep-ubicacion">---------</span>
                    </p>
                </div>
            </div>

            <hr class="text-light">

            <div class="row">
                <div class="col-md-7">
                    <p class="fw-bold text-secondary text-uppercase small">Descripción del incidente</p>
                    <div class="p-3 bg-light border rounded mb-4 shadow-sm" style="min-height: 100px;" id="rep-descripcion">
                        Cargando descripción...
                    </div>

                    <p class="fw-bold text-secondary text-uppercase small">Recomendaciones para usuarios</p>
                    <p class="p-3 border-start border-4 border-info bg-light rounded shadow-sm" id="rep-recomendaciones">
                         Cargando recomendaciones...
                    </p>

                    {{-- Contenedor de detalles extra (se oculta si no hay) --}}
                    <div id="rep-extra-container" class="d-none">
                        <p class="fw-bold text-secondary text-uppercase small">Más detalles</p>
                        <p class="text-muted small bg-light p-2 rounded border" id="rep-detalles-extra"></p>
                    </div>
                </div>

                <div class="col-md-5">
                    <p class="fw-bold text-secondary text-uppercase small">Evidencia Fotográfica</p>
                    <div class="text-center bg-light border rounded p-2 shadow-sm" id="rep-img-container">
                        {{-- JavaScript inyectará la imagen o un ícono de "no imagen" --}}
                    </div>
                </div>
            </div>

            <hr class="my-4">

            {{-- Sección de Gestión --}}
            <div class="bg-light p-4 rounded border shadow-sm">
                <h5 class="mb-4 text-center text-uppercase fw-bold text-muted">Panel de Gestión de Estatus</h5>
                
                <div class="btn-group w-100 shadow-sm" role="group">
                    <button type="button" id="btn-status-atencion" class="btn btn-outline-primary py-3 fw-bold" onclick="actualizarEstatus('atencion')">
                        <i class="fa-solid fa-clock"></i> EN ATENCIÓN
                    </button>

                    <button type="button" id="btn-status-revision" class="btn btn-outline-warning py-3 fw-bold" onclick="actualizarEstatus('revision')">
                        <i class="fa-solid fa-magnifying-glass"></i> EN REVISIÓN
                    </button>

                    <button type="button" id="btn-status-finalizado" class="btn btn-outline-success py-3 fw-bold" onclick="actualizarEstatus('finalizado')">
                        <i class="fa-solid fa-check-double"></i> FINALIZADO
                    </button>
                </div>
                <p class="text-center text-muted mt-3 mb-0 small italic">
                    <i class="fa-solid fa-circle-exclamation"></i> Al seleccionar un nuevo estatus, los cambios se reflejarán inmediatamente en la base de datos.
                </p>
            </div>
        </div>

        <div class="card-footer bg-white text-muted d-flex justify-content-between py-3">
            <span class="small"><strong>Folio:</strong> #<span id="rep-footer-id"></span></span>
            <span class="small"><strong>Última actualización:</strong> <span id="rep-updated">--</span></span>
        </div>
    </div>
</div>

<script type="module">
    // 1. Importaciones de Firebase
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getFirestore, doc, getDoc, updateDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

    // 2. Configuración de Firebase reportes
    const firebaseConfig = {
        apiKey: "AIzaSyCfwkyv2JPaHb8u06Ab7VcH2v9QJEwRnmY",
        authDomain: "reportes-proyecto-idor.firebaseapp.com",
        projectId: "reportes-proyecto-idor",
        storageBucket: "reportes-proyecto-idor.firebasestorage.app",
        messagingSenderId: "635696829226",
        appId: "1:635696829226:web:a8b40553eb5b23528b0453",
        measurementId: "G-MTW7NZ53DN"
    };

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);
    const coleccionReportes = "reportes";

    // 3. Extraer el ID del reporte desde la URL
    const urlParts = window.location.pathname.split('/');
    const reportId = urlParts[urlParts.length - 1]; 

    // Referencias DOM
    const alertContainer = document.getElementById('alert-container');
    const docRef = doc(db, coleccionReportes, reportId);

    // Función para mostrar mensajes
    function mostrarAlerta(mensaje, tipo = "success") {
        alertContainer.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                <i class="fa-solid ${tipo === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        setTimeout(() => { alertContainer.innerHTML = ''; }, 4000);
    }

    // Función principal para cargar el documento
    async function cargarDetalleReporte() {
        try {
            const docSnap = await getDoc(docRef);

            if (docSnap.exists()) {
                const data = docSnap.data();
                
                // --- Llenar los datos de texto ---
                document.getElementById('rep-header-id').innerText = reportId.substring().toUpperCase();
                document.getElementById('rep-footer-id').innerText = reportId;
                document.getElementById('rep-titulo').innerText = data.titulo || 'Sin título';
                document.getElementById('rep-ubicacion').innerText = data.ubicacion || 'Sin ubicación';
                
                // Reemplazar guiones bajos por espacios en el tipo
                let tipo = data.tipo_incidencia || 'No especificado';
                document.getElementById('rep-tipo').innerText = tipo.replace(/_/g, ' ');

                document.getElementById('rep-descripcion').innerText = data.descripcion || 'Sin descripción disponible.';
                document.getElementById('rep-recomendaciones').innerText = data.recomendaciones || 'Ninguna';
                
                // Detalles extra
                if(data.detalles_extra && data.detalles_extra.trim() !== '') {
                    document.getElementById('rep-detalles-extra').innerText = data.detalles_extra;
                    document.getElementById('rep-extra-container').classList.remove('d-none');
                }

                //BOTON DE EDICION JS REFERENCIADO
                document.getElementById('btn-edit-report').href = `{{ url('/reportes') }}/${reportId}/edit`;

                // Fechas
                if(data.created_at) {
                    const dateObj = data.created_at.toDate ? data.created_at.toDate() : new Date(data.created_at);
                    document.getElementById('rep-fecha').innerText = dateObj.toLocaleString();
                }
                
                if(data.updated_at) {
                    const updateObj = data.updated_at.toDate ? data.updated_at.toDate() : new Date(data.updated_at);
                    document.getElementById('rep-updated').innerText = updateObj.toLocaleString();
                }

                // Imagen 
                const imgContainer = document.getElementById('rep-img-container');
                if (data.imagen && data.imagen.trim() !== '') {
                    imgContainer.innerHTML = `
                        <img src="${data.imagen}" alt="Evidencia del reporte" 
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 350px; width: 100%; object-fit: cover; cursor: zoom-in;">
                    `;
                } else {
                    imgContainer.innerHTML = `
                        <div class="p-5 text-muted">
                            <i class="fa-solid fa-image-slash fa-3x mb-3 opacity-25"></i><br>
                            No hay evidencia fotográfica disponible.
                        </div>
                    `;
                }

                // Actualizar Estatus
                actualizarInterfazEstatus(data.estatus || 'pendiente');

                // Mostrar contenido y ocultar spinner
                document.getElementById('loading-spinner').classList.add('d-none');
                document.getElementById('report-content').classList.remove('d-none');

            } else {
                mostrarAlerta("No se encontró el reporte en la base de datos.", "danger");
                document.getElementById('loading-spinner').innerHTML = `<h4 class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Reporte no encontrado</h4>`;
            }
        } catch (error) {
            console.error("Error al obtener documento:", error);
            mostrarAlerta("Error de conexión al cargar el reporte.", "danger");
        }
    }

    // Función que actualiza las clases CSS de la tarjeta según el estatus
    function actualizarInterfazEstatus(estatus) {
        const estatusNormalizado = estatus.toLowerCase();
        
        const badge = document.getElementById('rep-badge-estatus');
        const badgeText = document.getElementById('rep-estatus-text');
        
        badge.className = "badge rounded-pill p-2 px-4 shadow-sm"; 
        
        if (estatusNormalizado === 'finalizado') {
            badge.classList.add('bg-success');
            badgeText.innerText = 'Finalizado';
        } else if (estatusNormalizado === 'revision' || estatusNormalizado === 'revisión') {
            badge.classList.add('bg-warning', 'text-dark');
            badgeText.innerText = 'En Revisión';
        } else {
            badge.classList.add('bg-primary');
            badgeText.innerText = 'En Atención';
        }

        // Configurar Botones del Panel (quitar .active a todos, poner al correcto)
        document.getElementById('btn-status-atencion').classList.remove('active');
        document.getElementById('btn-status-revision').classList.remove('active');
        document.getElementById('btn-status-finalizado').classList.remove('active');

        if(estatusNormalizado === 'atencion' || estatusNormalizado === 'atención') {
            document.getElementById('btn-status-atencion').classList.add('active');
        } else if (estatusNormalizado === 'revision' || estatusNormalizado === 'revisión') {
            document.getElementById('btn-status-revision').classList.add('active');
        } else if (estatusNormalizado === 'finalizado') {
            document.getElementById('btn-status-finalizado').classList.add('active');
        }
    }

    // Función para guardar en Firebase
    window.actualizarEstatus = async function(nuevoEstatus) {
        try {
            // Actualizar la interfaz inmediatamente
            actualizarInterfazEstatus(nuevoEstatus);

            // Actualizar en Firebase
            await updateDoc(docRef, {
                estatus: nuevoEstatus,
                updated_at: serverTimestamp() //Hora actual del servidor de Firebase
            });

            mostrarAlerta("Estatus actualizado exitosamente.");
            
            // Actualizar el texto de "Última actualización" al instante
            document.getElementById('rep-updated').innerText = new Date().toLocaleString();

        } catch (error) {
            console.error("Error actualizando estatus:", error);
            mostrarAlerta("Error al actualizar el estatus.", "danger");
            cargarDetalleReporte();
        }
    };

    // Cargar los datos al abrir la página
    window.onload = cargarDetalleReporte;

</script>
@endsection
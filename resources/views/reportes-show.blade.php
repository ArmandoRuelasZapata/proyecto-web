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
                {{-- El botón editar está asignado desde JS --}}
                <a href="#" id="btn-edit-report" class="btn btn-warning btn-sm fw-bold shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
                <a href="{{ url('/reportes') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>

        <div class="card-body p-4">

            {{-- Título + Badge estatus --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0" id="rep-titulo">Cargando...</h3>
                <span id="rep-badge-estatus" class="badge rounded-pill p-2 px-4 shadow-sm bg-primary">
                    <i class="fa-solid fa-circle-info"></i> <span id="rep-estatus-text">Cargando...</span>
                </span>
            </div>

            {{-- Fecha / Tipo / Ubicación --}}
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

            {{-- Descripción / Recomendaciones / Imagen --}}
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

                    {{-- Detalles extra (se oculta si no hay) --}}
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

            {{-- ══════════════════════════════════════════════════════════
                 PANEL DEL MODERADOR DE CAMPO
                 Escucha en tiempo real via onSnapshot los campos:
                 mod_valido | mod_mensaje | mod_sugiere | mod_fecha
                 escritos desde la app React Native por el moderador.
            ══════════════════════════════════════════════════════════ --}}
            <div class="card border-warning shadow-sm mb-4">
                <div class="card-header bg-warning text-dark fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-check"></i>
                    Reporte del Moderador de Campo
                    {{-- Indicador de escucha activa en tiempo real --}}
                    <span class="ms-auto d-flex align-items-center gap-1 text-muted small fw-normal">
                        <span style="width:9px;height:9px;border-radius:50%;background:#856404;
                                     display:inline-block;animation:pulseMod 1.5s ease-in-out infinite;"></span>
                        En vivo
                    </span>
                </div>

                <div class="card-body">

                    {{-- Estado A: esperando mensaje --}}
                    <div id="mod-waiting" class="text-center text-muted py-2">
                        <i class="fa-solid fa-hourglass-start fa-2x mb-2 opacity-50"></i>
                        <p class="small mb-0">Esperando validación del moderador de campo...</p>
                    </div>

                    {{-- Estado B: mensaje recibido (oculto hasta que llegue) --}}
                    <div id="mod-received" class="d-none">
                        <div class="row align-items-start g-3">

                            <div class="col-md-8">
                                <p class="mb-1 text-muted small text-uppercase">Sugerencia de cambio</p>
                                <p class="fw-bold mb-3">
                                    <i class="fa-solid fa-arrow-right-arrow-left text-warning me-1"></i>
                                    Pasar a: <span id="mod-sugiere" class="badge bg-secondary ms-1 fs-6"></span>
                                </p>

                                <p class="mb-1 text-muted small text-uppercase">Mensaje del moderador</p>
                                <div class="p-3 bg-light border-start border-4 border-warning rounded shadow-sm mb-2 fst-italic"
                                     id="mod-mensaje"></div>

                                <p class="text-muted small mb-0">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    Recibido: <span id="mod-fecha">---</span>
                                </p>
                            </div>

                            <div class="col-md-4 d-flex flex-column gap-2">
                                <button class="btn btn-success fw-bold shadow-sm"
                                        onclick="aceptarSugerenciaMod()">
                                    <i class="fa-solid fa-check me-1"></i> Aplicar estatus sugerido
                                </button>
                                <button class="btn btn-outline-secondary btn-sm"
                                        onclick="rechazarSugerenciaMod()">
                                    <i class="fa-solid fa-xmark me-1"></i> Ignorar sugerencia
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            {{-- ══════════════════════════════ FIN PANEL MODERADOR ══════ --}}

            {{-- Sección de Gestión de Estatus (Admin) --}}
            <div class="bg-light p-4 rounded border shadow-sm">
                <h5 class="mb-4 text-center text-uppercase fw-bold text-muted">Panel de Gestión de Estatus</h5>

                <div class="btn-group w-100 shadow-sm" role="group">
                    <button type="button" id="btn-status-atencion"
                            class="btn btn-outline-primary py-3 fw-bold"
                            onclick="actualizarEstatus('atencion')">
                        <i class="fa-solid fa-clock"></i> EN ATENCIÓN
                    </button>
                    <button type="button" id="btn-status-revision"
                            class="btn btn-outline-warning py-3 fw-bold"
                            onclick="actualizarEstatus('revision')">
                        <i class="fa-solid fa-magnifying-glass"></i> EN REVISIÓN
                    </button>
                    <button type="button" id="btn-status-finalizado"
                            class="btn btn-outline-success py-3 fw-bold"
                            onclick="actualizarEstatus('finalizado')">
                        <i class="fa-solid fa-check-double"></i> FINALIZADO
                    </button>
                </div>

                <p class="text-center text-muted mt-3 mb-0 small fst-italic">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    Al seleccionar un nuevo estatus, los cambios se reflejarán inmediatamente en la base de datos.
                </p>
            </div>

        </div>

        <div class="card-footer bg-white text-muted d-flex justify-content-between py-3">
            <span class="small"><strong>Folio:</strong> #<span id="rep-footer-id"></span></span>
            <span class="small"><strong>Última actualización:</strong> <span id="rep-updated">--</span></span>
        </div>
    </div>
</div>

<style>
@keyframes pulseMod {
    0%, 100% { opacity: 1;   transform: scale(1);   }
    50%       { opacity: 0.4; transform: scale(1.5); }
}
</style>

<script type="module">
    // ── Importaciones Firebase ────────────────────────────────────────────────
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getFirestore, doc, getDoc, updateDoc, onSnapshot,
             serverTimestamp, deleteField }
        from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

    // ── Configuración ─────────────────────────────────────────────────────────
    const firebaseConfig = {
        apiKey:            "AIzaSyCfwkyv2JPaHb8u06Ab7VcH2v9QJEwRnmY",
        authDomain:        "reportes-proyecto-idor.firebaseapp.com",
        projectId:         "reportes-proyecto-idor",
        storageBucket:     "reportes-proyecto-idor.firebasestorage.app",
        messagingSenderId: "635696829226",
        appId:             "1:635696829226:web:a8b40553eb5b23528b0453",
        measurementId:     "G-MTW7NZ53DN"
    };

    const app            = initializeApp(firebaseConfig);
    const db             = getFirestore(app);
    const urlParts       = window.location.pathname.split('/');
    const reportId       = urlParts[urlParts.length - 1];
    const docRef         = doc(db, "reportes", reportId);
    const alertContainer = document.getElementById('alert-container');

    let sugerenciaActual = null; // sugerencia vigente del moderador

    // ── Helper: alerta Bootstrap ──────────────────────────────────────────────
    function mostrarAlerta(mensaje, tipo = "success") {
        alertContainer.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                <i class="fa-solid ${tipo === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        setTimeout(() => { alertContainer.innerHTML = ''; }, 4000);
    }

    // ── Badge + botones activos según estatus ─────────────────────────────────
    function actualizarInterfazEstatus(estatus) {
        const e         = (estatus || '').toLowerCase();
        const badge     = document.getElementById('rep-badge-estatus');
        const badgeText = document.getElementById('rep-estatus-text');

        badge.className = "badge rounded-pill p-2 px-4 shadow-sm";

        if (e === 'finalizado') {
            badge.classList.add('bg-success');
            badgeText.innerText = 'Finalizado';
        } else if (e === 'revision' || e === 'revisión') {
            badge.classList.add('bg-warning', 'text-dark');
            badgeText.innerText = 'En Revisión';
        } else {
            badge.classList.add('bg-primary');
            badgeText.innerText = 'En Atención';
        }

        ['atencion','revision','finalizado'].forEach(s =>
            document.getElementById(`btn-status-${s}`).classList.remove('active')
        );
        if (e === 'atencion'  || e === 'atención')  document.getElementById('btn-status-atencion').classList.add('active');
        if (e === 'revision'  || e === 'revisión')  document.getElementById('btn-status-revision').classList.add('active');
        if (e === 'finalizado')                     document.getElementById('btn-status-finalizado').classList.add('active');
    }

    // ── Panel del moderador ───────────────────────────────────────────────────
    function renderPanelModerador(data) {
        const waiting  = document.getElementById('mod-waiting');
        const received = document.getElementById('mod-received');

        if (data.mod_valido) {
            // Normalizar: minúsculas sin tildes para comparar
            sugerenciaActual = (data.mod_sugiere || '')
                .toLowerCase()
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            // Color del badge según sugerencia
            const colores = { revision: 'bg-warning text-dark', atencion: 'bg-primary', finalizado: 'bg-success' };
            const badgeSug = document.getElementById('mod-sugiere');
            badgeSug.className = `badge ms-1 fs-6 ${colores[sugerenciaActual] || 'bg-secondary'}`;
            badgeSug.innerText = data.mod_sugiere || '-';

            document.getElementById('mod-mensaje').innerText = `"${data.mod_mensaje || ''}"`;

            if (data.mod_fecha) {
                const d = data.mod_fecha.toDate ? data.mod_fecha.toDate() : new Date(data.mod_fecha);
                document.getElementById('mod-fecha').innerText = d.toLocaleString('es-MX');
            }

            waiting.classList.add('d-none');
            received.classList.remove('d-none');
        } else {
            sugerenciaActual = null;
            waiting.classList.remove('d-none');
            received.classList.add('d-none');
        }
    }

    // ── Carga inicial ─────────────────────────────────────────────────────────
    async function cargarDetalleReporte() {
        try {
            const docSnap = await getDoc(docRef);

            if (!docSnap.exists()) {
                mostrarAlerta("No se encontró el reporte en la base de datos.", "danger");
                document.getElementById('loading-spinner').innerHTML =
                    `<h4 class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Reporte no encontrado</h4>`;
                return;
            }

            const data = docSnap.data();

            document.getElementById('rep-header-id').innerText      = reportId.substring().toUpperCase();
            document.getElementById('rep-footer-id').innerText      = reportId;
            document.getElementById('rep-titulo').innerText         = data.titulo      || 'Sin título';
            document.getElementById('rep-ubicacion').innerText      = data.ubicacion   || 'Sin ubicación';
            document.getElementById('rep-descripcion').innerText    = data.descripcion    || 'Sin descripción disponible.';
            document.getElementById('rep-recomendaciones').innerText= data.recomendaciones|| 'Ninguna';

            document.getElementById('rep-tipo').innerText =
                (data.tipo_incidencia || 'No especificado').replace(/_/g, ' ');

            if (data.detalles_extra?.trim()) {
                document.getElementById('rep-detalles-extra').innerText = data.detalles_extra;
                document.getElementById('rep-extra-container').classList.remove('d-none');
            }

            document.getElementById('btn-edit-report').href = `{{ url('/reportes') }}/${reportId}/edit`;

            if (data.created_at) {
                const d = data.created_at.toDate ? data.created_at.toDate() : new Date(data.created_at);
                document.getElementById('rep-fecha').innerText = d.toLocaleString();
            }
            if (data.updated_at) {
                const d = data.updated_at.toDate ? data.updated_at.toDate() : new Date(data.updated_at);
                document.getElementById('rep-updated').innerText = d.toLocaleString();
            }

            // Imagen
            const imgContainer = document.getElementById('rep-img-container');
            if (data.imagen?.trim()) {
                imgContainer.innerHTML = `
                    <img src="${data.imagen}" alt="Evidencia del reporte"
                         class="img-fluid rounded shadow-sm"
                         style="max-height:350px;width:100%;object-fit:cover;cursor:zoom-in;">`;
            } else {
                imgContainer.innerHTML = `
                    <div class="p-5 text-muted">
                        <i class="fa-solid fa-image-slash fa-3x mb-3 opacity-25"></i><br>
                        No hay evidencia fotográfica disponible.
                    </div>`;
            }

            actualizarInterfazEstatus(data.estatus || 'pendiente');
            renderPanelModerador(data);

            document.getElementById('loading-spinner').classList.add('d-none');
            document.getElementById('report-content').classList.remove('d-none');

            // ── onSnapshot: actualizaciones en tiempo real ────────────────
            // Solo refresca el badge de estatus y el panel del moderador
            onSnapshot(docRef, (snap) => {
                if (!snap.exists()) return;
                const live = snap.data();
                actualizarInterfazEstatus(live.estatus || 'pendiente');
                renderPanelModerador(live);
                if (live.updated_at) {
                    const d = live.updated_at.toDate ? live.updated_at.toDate() : new Date(live.updated_at);
                    document.getElementById('rep-updated').innerText = d.toLocaleString();
                }
            });

        } catch (error) {
            console.error("Error al obtener documento:", error);
            mostrarAlerta("Error de conexión al cargar el reporte.", "danger");
        }
    }

    // ── Cambiar estatus (botones del admin) ───────────────────────────────────
    window.actualizarEstatus = async function(nuevoEstatus) {
        try {
            actualizarInterfazEstatus(nuevoEstatus); // optimistic UI
            await updateDoc(docRef, {
                estatus:    nuevoEstatus,
                updated_at: serverTimestamp()
            });
            mostrarAlerta("Estatus actualizado exitosamente.");
            document.getElementById('rep-updated').innerText = new Date().toLocaleString();
        } catch (error) {
            console.error("Error actualizando estatus:", error);
            mostrarAlerta("Error al actualizar el estatus.", "danger");
            cargarDetalleReporte();
        }
    };

    // ── Aceptar sugerencia del moderador ─────────────────────────────────────
    // Aplica el estatus sugerido y limpia los campos mod_* del documento
    window.aceptarSugerenciaMod = async function() {
        if (!sugerenciaActual) return;
        if (!confirm(`¿Aplicar el estatus "${sugerenciaActual}" sugerido por el moderador?`)) return;
        try {
            await updateDoc(docRef, {
                estatus:     sugerenciaActual,
                updated_at:  serverTimestamp(),
                mod_valido:  deleteField(),
                mod_mensaje: deleteField(),
                mod_sugiere: deleteField(),
                mod_fecha:   deleteField()
            });
            mostrarAlerta(`Estatus cambiado a "${sugerenciaActual}" según sugerencia del moderador.`);
        } catch (error) {
            console.error(error);
            mostrarAlerta("Error al aplicar la sugerencia.", "danger");
        }
    };

    // ── Ignorar sugerencia del moderador ─────────────────────────────────────
    // Solo elimina los campos mod_* sin tocar el estatus
    window.rechazarSugerenciaMod = async function() {
        if (!confirm("¿Ignorar la sugerencia del moderador? Se eliminará el mensaje.")) return;
        try {
            await updateDoc(docRef, {
                mod_valido:  deleteField(),
                mod_mensaje: deleteField(),
                mod_sugiere: deleteField(),
                mod_fecha:   deleteField()
            });
            mostrarAlerta("Sugerencia del moderador ignorada.", "danger");
        } catch (error) {
            console.error(error);
            mostrarAlerta("Error al ignorar la sugerencia.", "danger");
        }
    };

    window.onload = cargarDetalleReporte;
</script>
@endsection
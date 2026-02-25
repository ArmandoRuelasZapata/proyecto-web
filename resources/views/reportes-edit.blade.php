@extends('layouts.app')

@section('content')
<div class="container mt-5">
    
    {{-- Contenedor de Alertas para JS --}}
    <div id="alert-container"></div>

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h2 class="mb-0 h4 text-uppercase fw-bold text-secondary">Editar Reporte #<span id="rep-header-id">...</span></h2>
            <a href="#" id="btn-cancelar" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </a>
        </div>

        {{-- Pantalla de carga mientras Firebase trae los datos --}}
        <div id="loading-spinner" class="text-center py-5">
            <i class="fa-solid fa-spinner fa-spin fa-3x text-secondary mb-3"></i>
            <h4 class="text-muted">Cargando datos del reporte...</h4>
        </div>

        {{-- Formulario oculto hasta que carguen los datos --}}
        <div class="card-body p-4 d-none" id="form-content">
            {{-- Quitamos el action y method; ahora es controlado por JavaScript --}}
            <form id="edit-report-form">
                <div class="row">
                    {{-- Título --}}
                    <div class="col-md-6 mb-4">
                        <label class="fw-bold text-secondary text-uppercase small">Título del Reporte</label>
                        <input type="text" id="input-titulo" class="form-control" required>
                    </div>

                    {{-- Ubicación --}}
                    <div class="col-md-6 mb-4">
                        <label class="fw-bold text-secondary text-uppercase small">Ubicación</label>
                        <input type="text" id="input-ubicacion" class="form-control" required>
                    </div>

                    {{-- Descripción --}}
                    <div class="col-md-12 mb-4">
                        <label class="fw-bold text-secondary text-uppercase small">Descripción del incidente</label>
                        <textarea id="input-descripcion" class="form-control" rows="4" required></textarea>
                    </div>

                    {{-- Recomendaciones --}}
                    <div class="col-md-12 mb-4">
                        <label class="fw-bold text-secondary text-uppercase small">Recomendaciones para usuarios</label>
                        <textarea id="input-recomendaciones" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" id="btn-guardar" class="btn btn-primary px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk"></i> GUARDAR CAMBIOS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="module">
    // 1. Importaciones de Firebase
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getFirestore, doc, getDoc, updateDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

    // 2. Configuración de tu Firebase
    const firebaseConfig = {
        apiKey: "AIzaSyCfwkyv2JPaHb8u06Ab7VcH2v9QJEwRnmY",
        authDomain: "reportes-proyecto-idor.firebaseapp.com",
        projectId: "reportes-proyecto-idor",
        storageBucket: "reportes-proyecto-idor.firebasestorage.app",
        messagingSenderId: "635696829226",
        appId: "1:635696829226:web:a8b40553eb5b23528b0453"
    };

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);
    const coleccionReportes = "reportes";

    // 3. Extraer el ID de la URL
    const urlParts = window.location.pathname.split('/');
    const reportId = urlParts[urlParts.length - 2]; // Penúltimo segmento

    const docRef = doc(db, coleccionReportes, reportId);
    const form = document.getElementById('edit-report-form');
    const alertContainer = document.getElementById('alert-container');
    const btnGuardar = document.getElementById('btn-guardar');

    // Configurar el botón de cancelar
    const urlRetorno = `{{ url('/reportes') }}/${reportId}`;
    document.getElementById('btn-cancelar').href = urlRetorno;

    // Función para mostrar alertas en pantalla
    function mostrarAlerta(mensaje, tipo = "success") {
        alertContainer.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                <i class="fa-solid ${tipo === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        window.scrollTo(0, 0);
    }

    // 4. Cargar los datos actuales al abrir la página
    async function cargarDatos() {
        try {
            const docSnap = await getDoc(docRef);
            
            if (docSnap.exists()) {
                const data = docSnap.data();
                
                // Rellenar la interfaz
                document.getElementById('rep-header-id').innerText = reportId.substring().toUpperCase();
                document.getElementById('input-titulo').value = data.titulo || '';
                document.getElementById('input-ubicacion').value = data.ubicacion || '';
                document.getElementById('input-descripcion').value = data.descripcion || '';
                document.getElementById('input-recomendaciones').value = data.recomendaciones || '';

                // Ocultar spinner de carga y mostrar el formulario
                document.getElementById('loading-spinner').classList.add('d-none');
                document.getElementById('form-content').classList.remove('d-none');
            } else {
                mostrarAlerta("El reporte no existe.", "danger");
                document.getElementById('loading-spinner').innerHTML = `<h4 class="text-danger">Reporte no encontrado</h4>`;
            }
        } catch (error) {
            console.error("Error al cargar datos:", error);
            mostrarAlerta("Error de conexión al cargar el reporte.", "danger");
        }
    }

    // 5. Enviar los cambios a Firebase al hacer clic en Guardar
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // Evita que la página recargue

        // Cambiar el estado del botón a "Cargando..."
        const textoOriginalBoton = btnGuardar.innerHTML;
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> GUARDANDO...`;

        try {
            // Recoger los datos de los inputs
            const datosActualizados = {
                titulo: document.getElementById('input-titulo').value,
                ubicacion: document.getElementById('input-ubicacion').value,
                descripcion: document.getElementById('input-descripcion').value,
                recomendaciones: document.getElementById('input-recomendaciones').value,
                updated_at: serverTimestamp() // Se actualiza la fecha en Firebase
            };

            // Mandar a guardar
            await updateDoc(docRef, datosActualizados);

            mostrarAlerta("Los datos del reporte han sido actualizados.", "success");
            
            // Redirigir de vuelta a la vista de detalles tras 1.5 segundos
            setTimeout(() => {
                window.location.href = urlRetorno;
            }, 1500);

        } catch (error) {
            console.error("Error al guardar:", error);
            mostrarAlerta("Ocurrió un error al intentar guardar los cambios.", "danger");
            
            // Restaurar el botón si hubo error
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = textoOriginalBoton;
        }
    });

    // Iniciar el proceso de carga al entrar a la página
    window.onload = cargarDatos;

</script>
@endsection
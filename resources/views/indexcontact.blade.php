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
    
    #loading-row {
        text-align: center;
        padding: 20px;
        color: #0c8e8a;
    }

    /* === BOTÓN ELIMINAR === */
    .action-btn {
        background: none;
        border: none;
        font-size: 1.1rem;
        transition: color 0.2s;
        padding: 5px 10px;
        cursor: pointer;
    }
    .btn-delete { color: #dc3545; }
    .btn-delete:hover { color: #a71d2a; }

    /* === MODAL DE CONFIRMACIÓN === */
    .confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }
    .confirm-overlay.active { display: flex; }
    .confirm-box {
        background: #fff;
        border-radius: 12px;
        padding: 28px 30px;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
        text-align: center;
        animation: popIn 0.2s ease;
    }
    @keyframes popIn {
        from { transform: scale(0.85); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }
    .confirm-icon { font-size: 2.2em; margin-bottom: 12px; }
    .confirm-box p { margin: 0 0 20px; color: #333; font-size: 1em; line-height: 1.5; }
    .confirm-actions { display: flex; gap: 10px; justify-content: center; }
    .confirm-btn {
        padding: 9px 22px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        font-size: 0.95em;
        transition: filter 0.2s;
    }
    .confirm-btn:hover { filter: brightness(0.9); }
    .confirm-btn-ok.danger { background: #dc3545; color: white; }
    .confirm-btn-cancel { background: #e9e9e9; color: #555; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="page-wrapper">

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <a href="{{ url('home') }}">
            <img src="{{ asset('img/informe-de-datos.png') }}" class="menu-icon"> Dashboard
        </a>
        <a href="{{ url('crud') }}">
            <img src="{{ asset('img/red-mundial.png') }}" class="menu-icon"> Reportes públicos
        </a>
        <a href="{{ url('reportes') }}"> 
            <img src="{{ asset('img/tus reportes.png') }}" class="menu-icon"> Reportes
        </a>
        <a href="{{ url('moderadores') }}">
            <img src="{{ asset('img/proteger.png') }}" class="menu-icon"> Moderadores
        </a>
        <a href="{{ url('leer-usuarios') }}">
            <img src="{{ asset('img/admin.png') }}" class="menu-icon"> Administradores
        </a>
        <a href="{{ url('leer-contactos') }}" class="active">
            <img src="{{ asset('img/contacts.png') }}" class="menu-icon"> Contactos
        </a>
        <a href="{{ url('cuentasbloqueadas') }}">
            <img src="{{ asset('img/cuenta-privada.png') }}" class="menu-icon"> Cuentas bloqueadas
        </a>
        <a href="{{ url('solicitudes') }}">
            <img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Soporte
        </a>
    </div>

    {{-- CONTENIDO --}}
    <div class="main-content">
        <div class="container-fluid">
            <div class="custom-card">
                <div class="custom-card-header">
                    <strong>Contactos guardados</strong>
                </div>
                
                <div class="p-4 pb-0">
                    <p class="text-muted">Visualizando mensajes en tiempo real.</p>
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
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-contactos">
                            <tr>
                                <td colspan="6" id="loading-row">
                                    <i class="fas fa-spinner fa-spin"></i> Cargando contactos...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación --}}
<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon">🗑️</div>
        <p id="confirm-message">¿Estás seguro de eliminar este contacto?</p>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="confirm-cancel">Cancelar</button>
            <button class="confirm-btn confirm-btn-ok danger" id="confirm-ok">Eliminar</button>
        </div>
    </div>
</div>

{{-- FIREBASE SCRIPTS --}}
<script type="module">
    import { getApps, initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
    import { getFirestore, collection, onSnapshot, doc, deleteDoc } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";

    const firebaseConfig = {
        apiKey: "AIzaSyCha641yRxJBUVWsDD_dKNmqrWb-Cj6JhU",
        authDomain: "contactos2-9b78b.firebaseapp.com",
        projectId: "contactos2-9b78b",
        storageBucket: "contactos2-9b78b.firebasestorage.app",
        messagingSenderId: "509739763203",
        appId: "1:509739763203:web:0e05a89aa23ba0a2ca7036"
    };

    // ─── Guard Firebase ──────────────────────────────────────────────────────
    const app = getApps().length === 0 ? initializeApp(firebaseConfig) : getApps()[0];
    const db  = getFirestore(app);
    const tablaCuerpo = document.getElementById('tabla-contactos');

    // ─── Modal de confirmación ───────────────────────────────────────────────
    let confirmIsOpen = false;

    function showConfirm({ message = "¿Estás seguro?", icon = "🗑️" } = {}) {
        return new Promise((resolve) => {
            if (confirmIsOpen) { resolve(false); return; }
            confirmIsOpen = true;

            const overlay   = document.getElementById("confirm-overlay");
            const msgEl     = document.getElementById("confirm-message");
            const btnOk     = document.getElementById("confirm-ok");
            const btnCancel = document.getElementById("confirm-cancel");

            msgEl.textContent = message;
            overlay.classList.add("active");

            const cleanup = (result) => {
                overlay.classList.remove("active");
                confirmIsOpen = false;
                btnOk.removeEventListener("click", onOk);
                btnCancel.removeEventListener("click", onCancel);
                resolve(result);
            };

            const onOk     = () => cleanup(true);
            const onCancel = () => cleanup(false);

            btnOk.addEventListener("click", onOk);
            btnCancel.addEventListener("click", onCancel);
        });
    }

    // ─── Escucha en tiempo real ──────────────────────────────────────────────
    if (window._contactosUnsubscribe) window._contactosUnsubscribe();

    window._contactosUnsubscribe = onSnapshot(collection(db, "contactos"), (snapshot) => {
        tablaCuerpo.innerHTML = "";

        if (snapshot.empty) {
            tablaCuerpo.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay mensajes registrados.</td></tr>';
            return;
        }

        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const id   = docSnap.id;
            const fila = document.createElement('tr');

            let badgeHTML = '';
            const prioridad = data.prioridad ? data.prioridad.toLowerCase() : 'baja';
            if      (prioridad === 'alta')  badgeHTML = '<span class="badge bg-danger">Alta</span>';
            else if (prioridad === 'media') badgeHTML = '<span class="badge bg-warning text-dark">Media</span>';
            else                            badgeHTML = '<span class="badge bg-info">Baja</span>';

            fila.innerHTML = `
                <td class="fw-bold">${data.nombre || 'N/A'}</td>
                <td>${data.correo || 'N/A'}</td>
                <td>${badgeHTML}</td>
                <td>${data.asunto || 'Sin asunto'}</td>
                <td class="text-muted small">${data.mensaje || ''}</td>
                <td class="text-center">
                    <button type="button"
                            class="action-btn btn-delete btn-eliminar"
                            data-id="${id}"
                            data-nombre="${data.nombre || 'este contacto'}"
                            title="Eliminar">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;
            tablaCuerpo.appendChild(fila);
        });
    });

    // ─── Delegación de eventos ───────────────────────────────────────────────
    tablaCuerpo.addEventListener('click', async (e) => {
        const btn = e.target.closest('.btn-eliminar');
        if (!btn) return;

        const id     = btn.dataset.id;
        const nombre = btn.dataset.nombre;

        const confirmed = await showConfirm({
            message: `¿Eliminar permanentemente el contacto "${nombre}"? Esta acción no se puede deshacer.`
        });

        if (!confirmed) return;

        try {
            await deleteDoc(doc(db, "contactos", id));
        } catch (error) {
            console.error("Error al eliminar:", error);
        }
    });
</script>
@endsection
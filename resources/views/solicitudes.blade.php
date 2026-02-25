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

    /* === SIDEBAR === */
    .sidebar {
        width: 260px;
        min-height: calc(100vh - 66px);
        position: sticky;
        top: 66px;
        left: 0;
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
        object-fit: contain;
        filter: invert();
    }

    .active .menu-icon { filter: invert(0); }

    /* === CONTENT === */
    .main-content {
        flex-grow: 1;
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .content-box {
        background: #ffffff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .content-box h2 {
        margin-bottom: 25px;
        color: #333;
    }

    /* Formulario */
    .form-group { margin-bottom: 15px; }
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: inherit;
        box-sizing: border-box;
    }

    .btn-save {
        background: #0c8e8a;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.2s;
    }

    .btn-save:hover:not(:disabled) { background: #086b6a; }
    .btn-save:disabled { background: #aaa; cursor: not-allowed; }

    /* Lista de Guías */
    .guias-list { list-style: none; padding: 0; margin: 0; }
    .guia-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
    }
    .guia-item:hover { background: #f9f9f9; }
    .guia-info { flex-grow: 1; }
    .guia-titulo { font-weight: bold; color: #0c8e8a; display: block; }
    .guia-extracto { color: #666; font-size: 0.9em; }

    .btn-actions { display: flex; gap: 6px; margin-left: 15px; }

    .btn-delete, .btn-edit {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1em;
        padding: 6px 8px;
        border-radius: 5px;
        transition: background 0.2s, color 0.2s;
    }

    .btn-delete { color: #dc3545; }
    .btn-delete:hover { color: #a71d2a; background: #ffeaec; }
    .btn-edit { color: #0c8e8a; }
    .btn-edit:hover { color: #086b6a; background: #e6f7f7; }

    /* === MODAL DE EDICIÓN === */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }

    .modal-overlay.active { display: flex; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .modal-box {
        background: #fff;
        border-radius: 12px;
        width: 100%;
        max-width: 580px;
        padding: 30px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
        position: relative;
        animation: slideUp 0.25s ease;
    }

    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        border-bottom: 2px solid #e8f5f5;
        padding-bottom: 15px;
    }

    .modal-header h3 { margin: 0; color: #0c8e8a; font-size: 1.2em; }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.4em;
        color: #aaa;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 5px;
        transition: background 0.2s, color 0.2s;
        line-height: 1;
    }
    .modal-close:hover { background: #f0f0f0; color: #333; }

    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #555;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.2s;
    }
    .btn-cancel:hover { background: #ddd; }

    /* === MODAL DE CONFIRMACIÓN / ALERTA === */
    .confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 2000;
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
        to   { transform: scale(1);    opacity: 1; }
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
    .confirm-btn-ok     { background: #0c8e8a; color: white; }
    .confirm-btn-ok.danger { background: #dc3545; }
    .confirm-btn-cancel { background: #e9e9e9; color: #555; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="page-wrapper">
    <div class="sidebar">
        <a href="{{ url('home') }}"><img src="{{ asset('img/informe-de-datos.png') }}" class="menu-icon"> Dashboard</a>
        <a href="{{ url('crud') }}"><img src="{{ asset('img/red-mundial.png') }}" class="menu-icon"> Reportes públicos</a>
        <a href="{{ url('reportes') }}"><img src="{{ asset('img/tus reportes.png') }}" class="menu-icon"> Reportes</a>
        <a href="{{ url('moderadores') }}"><img src="{{ asset('img/proteger.png') }}" class="menu-icon"> Moderadores</a>
        <a href="{{ url('leer-usuarios') }}"><img src="{{ asset('img/admin.png') }}" class="menu-icon"> Administradores</a>
        <a href="{{ url('leer-contactos') }}"><img src="{{ asset('img/contacts.png') }}" class="menu-icon"> Contactos</a>
        <a href="{{ url('cuentasbloqueadas') }}"><img src="{{ asset('img/cuenta-privada.png') }}" class="menu-icon"> Cuentas bloqueadas</a>
        <a href="{{ url('solicitudes') }}" class="active"><img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Soporte</a>
    </div>

    <div class="main-content">
        <div class="content-box">
            <h2><strong>Soporte</strong></h2>
            <h3><i class="fa-solid fa-plus-circle"></i> Crear Nueva Guía de Soporte</h3>
            <hr>
            <div class="form-group">
                <label>Título de la pregunta</label>
                <input type="text" id="guia-titulo" class="form-control" placeholder="Ej: ¿Cómo reportar un accidente?">
            </div>
            <div class="form-group">
                <label>Contenido / Respuesta</label>
                <textarea id="guia-contenido" class="form-control" rows="4" placeholder="Escribe los pasos detallados aquí..."></textarea>
            </div>
            <button id="btn-guardar-guia" class="btn-save">
                <i class="fa-solid fa-paper-plane"></i> Publicar en la App
            </button>
        </div>

        <div class="content-box">
            <h3><i class="fa-solid fa-book-open"></i> Guías Publicadas</h3>
            <ul class="guias-list" id="guias-container">
                <p>Cargando información de Firebase...</p>
            </ul>
        </div>
    </div>
</div>

<!-- MODAL: Editar guía -->
<div class="modal-overlay" id="modal-editar">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square"></i> Editar Guía de Soporte</h3>
            <button class="modal-close" id="btn-cerrar-modal" title="Cerrar">&times;</button>
        </div>
        <input type="hidden" id="edit-guia-id">
        <div class="form-group">
            <label>Título de la pregunta</label>
            <input type="text" id="edit-guia-titulo" class="form-control" placeholder="Título de la guía">
        </div>
        <div class="form-group">
            <label>Contenido / Respuesta</label>
            <textarea id="edit-guia-contenido" class="form-control" rows="5" placeholder="Contenido de la guía..."></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" id="btn-cancelar-edicion">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </button>
            <button class="btn-save" id="btn-guardar-edicion">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
            </button>
        </div>
    </div>
</div>

<!-- MODAL: Confirmación / Alerta -->
<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon" id="confirm-icon">⚠️</div>
        <p id="confirm-message">¿Estás seguro?</p>
        <div class="confirm-actions" id="confirm-actions">
            <!-- Los botones se inyectan dinámicamente según el modo -->
        </div>
    </div>
</div>

<script type="module">
    import { getApps, initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import {
        getFirestore,
        collection,
        addDoc,
        deleteDoc,
        updateDoc,
        doc,
        query,
        orderBy,
        onSnapshot,
        serverTimestamp
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

    // ─── Guard Firebase ──────────────────────────────────────────────────────
    const firebaseConfig = {
        apiKey: "AIzaSyDFQya4MsGuo6vkfnNmepo6mwOd9zzZuJI",
        authDomain: "asistencia-movidgo.firebaseapp.com",
        projectId: "asistencia-movidgo",
        storageBucket: "asistencia-movidgo.firebasestorage.app",
        messagingSenderId: "889539075443",
        appId: "1:889539075443:web:df5d468bd89085b7837906"
    };

    const app = getApps().length === 0 ? initializeApp(firebaseConfig) : getApps()[0];
    const db  = getFirestore(app);

    // ─── Utilidades de Modal ─────────────────────────────────────────────────
    let modalIsOpen = false;

    const overlay    = document.getElementById("confirm-overlay");
    const msgEl      = document.getElementById("confirm-message");
    const iconEl     = document.getElementById("confirm-icon");
    const actionsEl  = document.getElementById("confirm-actions");

    /**
     * showConfirm({ message, icon, danger })
     * Muestra un modal con botones Cancelar + Confirmar.
     * Devuelve Promise<boolean>.
     */
    function showConfirm({ message = "¿Estás seguro?", icon = "⚠️", danger = false } = {}) {
        return new Promise((resolve) => {
            if (modalIsOpen) { resolve(false); return; }
            modalIsOpen = true;

            iconEl.textContent = icon;
            msgEl.textContent  = message;

            actionsEl.innerHTML = `
                <button class="confirm-btn confirm-btn-cancel" id="modal-btn-cancel">Cancelar</button>
                <button class="confirm-btn confirm-btn-ok ${danger ? 'danger' : ''}" id="modal-btn-ok">Confirmar</button>
            `;

            overlay.classList.add("active");

            const cleanup = (result) => {
                overlay.classList.remove("active");
                modalIsOpen = false;
                resolve(result);
            };

            document.getElementById("modal-btn-ok").addEventListener("click",     () => cleanup(true));
            document.getElementById("modal-btn-cancel").addEventListener("click", () => cleanup(false));
        });
    }

    /**
     * showAlert({ message, icon })
     * Muestra un modal informativo con un solo botón "Aceptar".
     * Devuelve Promise<void>.
     */
    function showAlert({ message = "", icon = "✅" } = {}) {
        return new Promise((resolve) => {
            if (modalIsOpen) { resolve(); return; }
            modalIsOpen = true;

            iconEl.textContent = icon;
            msgEl.textContent  = message;

            actionsEl.innerHTML = `
                <button class="confirm-btn confirm-btn-ok" id="modal-btn-ok">Aceptar</button>
            `;

            overlay.classList.add("active");

            document.getElementById("modal-btn-ok").addEventListener("click", () => {
                overlay.classList.remove("active");
                modalIsOpen = false;
                resolve();
            });
        });
    }

    // ─── Modal de edición ────────────────────────────────────────────────────
    const modalEditar        = document.getElementById("modal-editar");
    const btnCerrarModal     = document.getElementById("btn-cerrar-modal");
    const btnCancelarEdicion = document.getElementById("btn-cancelar-edicion");
    const btnGuardarEdicion  = document.getElementById("btn-guardar-edicion");
    const editId             = document.getElementById("edit-guia-id");
    const editTitulo         = document.getElementById("edit-guia-titulo");
    const editContenido      = document.getElementById("edit-guia-contenido");

    const abrirModal = (id, titulo, contenido) => {
        editId.value        = id;
        editTitulo.value    = titulo;
        editContenido.value = contenido;
        modalEditar.classList.add("active");
    };

    const cerrarModal = () => {
        modalEditar.classList.remove("active");
        editId.value = editTitulo.value = editContenido.value = "";
    };

    btnCerrarModal.addEventListener("click", cerrarModal);
    btnCancelarEdicion.addEventListener("click", cerrarModal);
    modalEditar.addEventListener("click", (e) => { if (e.target === modalEditar) cerrarModal(); });

    // ─── Guardar cambios (edición) ───────────────────────────────────────────
    btnGuardarEdicion.addEventListener("click", async () => {
        const id        = editId.value;
        const titulo    = editTitulo.value.trim();
        const contenido = editContenido.value.trim();

        if (!titulo || !contenido) {
            await showAlert({ message: "Por favor, completa todos los campos.", icon: "⚠️" });
            return;
        }

        btnGuardarEdicion.disabled = true;
        btnGuardarEdicion.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';

        try {
            await updateDoc(doc(db, "guias_soporte", id), { titulo, contenido });
            cerrarModal();
        } catch (error) {
            console.error("Error al actualizar:", error);
            await showAlert({ message: "Hubo un error al guardar los cambios.", icon: "❌" });
        } finally {
            btnGuardarEdicion.disabled = false;
            btnGuardarEdicion.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Cambios';
        }
    });

    // ─── Guardar nueva guía ──────────────────────────────────────────────────
    const btnGuardar = document.getElementById("btn-guardar-guia");

    if (!btnGuardar.dataset.listenerRegistered) {
        btnGuardar.dataset.listenerRegistered = "true";

        btnGuardar.addEventListener("click", async () => {
            const titulo    = document.getElementById("guia-titulo").value.trim();
            const contenido = document.getElementById("guia-contenido").value.trim();

            if (!titulo || !contenido) {
                await showAlert({ message: "Por favor, completa todos los campos.", icon: "⚠️" });
                return;
            }

            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';

            try {
                await addDoc(collection(db, "guias_soporte"), {
                    titulo,
                    contenido,
                    fecha_creacion: serverTimestamp()
                });

                document.getElementById("guia-titulo").value    = "";
                document.getElementById("guia-contenido").value = "";
                await showAlert({ message: "¡Guía publicada correctamente!", icon: "✅" });
            } catch (error) {
                console.error("Error al guardar:", error);
                await showAlert({ message: "Hubo un error al guardar en Firebase.", icon: "❌" });
            } finally {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Publicar en la App';
            }
        });
    }

    // ─── Leer guías en tiempo real ───────────────────────────────────────────
    const guiasContainer = document.getElementById("guias-container");

    if (window._soporteUnsubscribe) window._soporteUnsubscribe();

    window._soporteUnsubscribe = onSnapshot(
        query(collection(db, "guias_soporte"), orderBy("fecha_creacion", "desc")),
        (snapshot) => {
            if (snapshot.empty) {
                guiasContainer.innerHTML = "<p>No hay guías disponibles para mostrar.</p>";
                return;
            }

            let html = "";
            snapshot.forEach((docSnap) => {
                const data = docSnap.data();
                const id   = docSnap.id;
                const extracto = data.contenido.length > 120
                    ? data.contenido.substring(0, 120) + "..."
                    : data.contenido;

                const tituloEsc    = data.titulo.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                const contenidoEsc = data.contenido.replace(/'/g, "\\'").replace(/"/g, "&quot;");

                html += `
                    <li class="guia-item">
                        <div class="guia-info">
                            <span class="guia-titulo">${data.titulo}</span>
                            <span class="guia-extracto">${extracto}</span>
                        </div>
                        <div class="btn-actions">
                            <button class="btn-edit"
                                data-id="${id}"
                                data-titulo="${tituloEsc}"
                                data-contenido="${contenidoEsc}"
                                title="Editar guía">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-delete"
                                data-id="${id}"
                                title="Eliminar guía">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </li>
                `;
            });

            guiasContainer.innerHTML = html;

            // Botones editar
            guiasContainer.querySelectorAll(".btn-edit").forEach(btn => {
                btn.addEventListener("click", () => {
                    abrirModal(btn.dataset.id, btn.dataset.titulo, btn.dataset.contenido);
                });
            });

            // Botones eliminar — ahora usando delegación con showConfirm
            guiasContainer.querySelectorAll(".btn-delete").forEach(btn => {
                btn.addEventListener("click", async () => {
                    const id = btn.dataset.id;
                    const confirmed = await showConfirm({
                        message: "¿Estás seguro de que quieres eliminar esta guía de soporte?",
                        icon: "🗑️",
                        danger: true
                    });

                    if (!confirmed) return;

                    try {
                        await deleteDoc(doc(db, "guias_soporte", id));
                    } catch (error) {
                        console.error("Error al eliminar:", error);
                        await showAlert({ message: "Hubo un error al eliminar la guía.", icon: "❌" });
                    }
                });
            });
        },
        (error) => {
            console.error("Error al leer guías:", error);
            guiasContainer.innerHTML = "<p>Error al cargar las guías. Revisa la consola.</p>";
        }
    );
</script>
@endsection
@extends('layouts.app')

@section('styles')
<style>
    body { background: #f2f4f7; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
    .page-wrapper { display: flex; flex-grow: 1; }
    .sidebar { width: 260px; min-height: calc(100vh - 66px); position: sticky; top: 66px; left: 0; background: #0c8e8a; border-right: 1px solid #086b6a; padding-top: 10px; }
    .sidebar a { display: flex; align-items: center; padding: 14px 20px; color: white; font-size: 16px; text-decoration: none; transition: 0.2s; }
    .sidebar a:hover { background: #086b6a; }
    .sidebar a.active { background: #f2f4f7; color: #333; border-radius: 4px; margin: 0 10px; }
    .main-content { flex-grow: 1; padding: 30px; }
    .content-box { background: #ffffff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .reportes-list { list-style: none; padding: 0; }
    .reportes-list-item { display: flex; align-items: center; padding: 12px 15px; border-bottom: 1px solid #eee; }
    .report-details { flex-grow: 1; }
    .report-title { font-weight: bold; margin-bottom: 2px; }
    .report-type { font-size: 0.9em; color: #666; margin-top: 3px; }
    .report-actions { display: flex; gap: 10px; align-items: center; }
    .menu-icon { width: 35px; height: 35px; margin-right: 15px; object-fit: contain; filter: invert(); }
    .active .menu-icon { filter: invert(0); }

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
        <a href="{{ url('crud') }}" class="active"><img src="{{ asset('img/red-mundial.png') }}" class="menu-icon"> Reportes públicos</a>
        <a href="{{ url('reportes') }}"><img src="{{ asset('img/tus reportes.png') }}" class="menu-icon"> Reportes</a>
        <a href="{{ url('moderadores') }}"><img src="{{ asset('img/proteger.png') }}" class="menu-icon"> Moderadores</a>
        <a href="{{ url('leer-usuarios') }}"><img src="{{ asset('img/admin.png') }}" class="menu-icon"> Administradores</a>
        <a href="{{ url('leer-contactos') }}"><img src="{{ asset('img/contacts.png') }}" class="menu-icon"> Contactos</a>
        <a href="{{ url('cuentasbloqueadas') }}"><img src="{{ asset('img/cuenta-privada.png') }}" class="menu-icon"> Cuentas bloqueadas</a>
        <a href="{{ url('solicitudes') }}"><img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Soporte</a>
    </div>

    <div class="main-content">
        <div class="content-box">
            <h2><strong>Reportes Públicos (Favoritos)</strong></h2>
            <ul class="reportes-list" id="lista-publica">
                <p>Cargando reportes públicos...</p>
            </ul>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMACIÓN -->
<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon" id="confirm-icon">⚠️</div>
        <p id="confirm-message">¿Estás seguro?</p>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="confirm-cancel">Cancelar</button>
            <button class="confirm-btn confirm-btn-ok" id="confirm-ok">Confirmar</button>
        </div>
    </div>
</div>

<script type="module">
    import { getApps, initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getFirestore, collection, query, where, orderBy, onSnapshot, doc, updateDoc, deleteDoc } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

    // ─── Guard Firebase ──────────────────────────────────────────────────────
    const firebaseConfig = {
        apiKey: "AIzaSyCfwkyv2JPaHb8u06Ab7VcH2v9QJEwRnmY",
        authDomain: "reportes-proyecto-idor.firebaseapp.com",
        projectId: "reportes-proyecto-idor",
        storageBucket: "reportes-proyecto-idor.firebasestorage.app",
        messagingSenderId: "635696829226",
        appId: "1:635696829226:web:a8b40553eb5b23528b0453"
    };

    const app = getApps().length === 0 ? initializeApp(firebaseConfig) : getApps()[0];
    const db  = getFirestore(app);
    const coleccionPublicos = "reportes_publicos";
    const listaUI = document.getElementById("lista-publica");

    // ─── Badge por tipo de incidencia (igual que en Panel de Reportes) ───────
    function getBadge(tipo) {
        const t = (tipo || '').toLowerCase();
        if (t === 'choque')  return '<span class="badge bg-danger">Choque</span>';
        if (t === 'bloqueo') return '<span class="badge bg-warning text-dark">Bloqueo</span>';
        if (t === 'bache')   return '<span class="badge bg-info text-dark">Bache</span>';
        return `<span class="badge bg-secondary">${tipo || 'General'}</span>`;
    }

    // ─── Modal de confirmación ───────────────────────────────────────────────
    let confirmIsOpen = false;

    function showConfirm({ message = "¿Estás seguro?", icon = "⚠️", danger = false } = {}) {
        return new Promise((resolve) => {
            if (confirmIsOpen) { resolve(false); return; }
            confirmIsOpen = true;

            const overlay   = document.getElementById("confirm-overlay");
            const msgEl     = document.getElementById("confirm-message");
            const iconEl    = document.getElementById("confirm-icon");
            const btnOk     = document.getElementById("confirm-ok");
            const btnCancel = document.getElementById("confirm-cancel");

            msgEl.textContent  = message;
            iconEl.textContent = icon;
            btnOk.classList.toggle("danger", danger);
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

    // ─── Escuchar reportes públicos en tiempo real ───────────────────────────
    if (window._crudUnsubscribe) window._crudUnsubscribe();

    const q = query(
        collection(db, coleccionPublicos),
        where("favorito", "==", true),
        orderBy("created_at", "desc")
    );

    window._crudUnsubscribe = onSnapshot(q, (snapshot) => {
        if (snapshot.empty) {
            listaUI.innerHTML = "<li style='padding:10px;color:#888;'>No hay reportes públicos marcados.</li>";
            return;
        }

        let html = "";

        snapshot.forEach((docSnap) => {
            const reporte    = docSnap.data();
            const id         = docSnap.id;
            const urlDetalle = `{{ url('/reportes') }}/${id}`;
            const badge      = getBadge(reporte.tipo_incidencia);

            html += `
                <li class="reportes-list-item">
                    <div class="report-details"
                         onclick="window.location.href='${urlDetalle}'"
                         style="cursor:pointer;">
                        <strong>Reporte #${id.substring(0, 5).toUpperCase()}</strong>
                        — ${reporte.titulo || 'Sin título'}
                        <div class="report-type">${badge}</div>
                    </div>
                    <div class="report-actions">
                        <button class="btn-quitar-fav" data-id="${id}" title="Quitar de favoritos"
                                style="color:gold; border:none; background:none; font-size:1.2em; cursor:pointer;">
                            <i class="fa-solid fa-star"></i>
                        </button>
                        <a href="${urlDetalle}" title="Ver detalle"
                           style="color:#0c8e8a; font-size:1.2em;">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button class="btn-eliminar-pub" data-id="${id}" title="Eliminar reporte"
                                style="background:none; border:none; color:#dc3545; cursor:pointer; font-size:1.2em;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </li>
            `;
        });

        listaUI.innerHTML = html;
    }, (error) => {
        console.error("Error al escuchar reportes públicos:", error);
        listaUI.innerHTML = "<li style='padding:10px;color:#c00;'>Error al cargar los reportes. Revisa la consola.</li>";
    });

    // ─── Delegación de eventos ───────────────────────────────────────────────
    listaUI.addEventListener("click", async (e) => {
        const btnQuitarFav = e.target.closest(".btn-quitar-fav");
        const btnEliminar  = e.target.closest(".btn-eliminar-pub");

        // --- Quitar de favoritos ---
        if (btnQuitarFav) {
            e.stopPropagation();
            const id = btnQuitarFav.dataset.id;

            const confirmed = await showConfirm({
                message: "¿Quitar este reporte de la lista pública? Dejará de ser visible para los usuarios.",
                icon:    "🔕",
                danger:  false
            });

            if (!confirmed) return;

            try {
                await updateDoc(doc(db, coleccionPublicos, id), { favorito: false });
            } catch (error) {
                console.error("Error al quitar de favoritos:", error);
            }
        }

        // --- Eliminar permanentemente ---
        if (btnEliminar) {
            e.stopPropagation();
            const id = btnEliminar.dataset.id;

            const confirmed = await showConfirm({
                message: "¿Eliminar este reporte permanentemente? Esta acción no se puede deshacer.",
                icon:    "🗑️",
                danger:  true
            });

            if (!confirmed) return;

            try {
                await deleteDoc(doc(db, coleccionPublicos, id));
            } catch (error) {
                console.error("Error al eliminar:", error);
            }
        }
    });
</script>
@endsection
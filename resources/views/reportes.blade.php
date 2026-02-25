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
    text-decoration: none;
    font-size: 16px;
    transition: 0.2s;
}

.sidebar a:hover { background: #086b6a; }

.sidebar a.active {
    background: #f2f4f7;
    color: #333;
    border-radius: 4px;
    margin: 0 10px;
}

.main-content {
    flex-grow: 1;
    padding: 30px;
}

.content-box {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.reportes-list {
    list-style: none;
    padding: 0;
}

.reportes-list-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
}

.report-details { flex-grow: 1; }

.menu-icon {
    width: 35px;
    height: 35px;
    margin-right: 15px;
    filter: invert(1);
}

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

.confirm-box .confirm-icon { font-size: 2.2em; margin-bottom: 12px; }
.confirm-box p { margin: 0 0 20px; color: #333; font-size: 1em; line-height: 1.5; }

.confirm-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

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
.confirm-btn-ok { background: #0c8e8a; color: white; }
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
        <a href="{{ url('reportes') }}" class="active"><img src="{{ asset('img/tus reportes.png') }}" class="menu-icon"> Reportes</a>
        <a href="{{ url('moderadores') }}"><img src="{{ asset('img/proteger.png') }}" class="menu-icon"> Moderadores</a>
        <a href="{{ url('leer-usuarios') }}"><img src="{{ asset('img/admin.png') }}" class="menu-icon"> Administradores</a>
        <a href="{{ url('leer-contactos') }}"><img src="{{ asset('img/contacts.png') }}" class="menu-icon"> Contactos</a>
        <a href="{{ url('cuentasbloqueadas') }}"><img src="{{ asset('img/cuenta-privada.png') }}" class="menu-icon"> Cuentas bloqueadas</a>
        <a href="{{ url('solicitudes') }}"><img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Soporte</a>
    </div>

    <div class="main-content">
        <div id="alert-container"></div>
        <div class="content-box">
            <h2><strong>Panel de Reportes</strong></h2>
            <ul class="reportes-list" id="reportes-list-container">
                <p>Conectando a Firebase...</p>
            </ul>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMACIÓN -->
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
import { getFirestore, collection, doc, deleteDoc, query, orderBy, onSnapshot, setDoc, getDoc, updateDoc } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

const firebaseConfig = {
    apiKey: "AIzaSyCfwkyv2JPaHb8u06Ab7VcH2v9QJEwRnmY",
    authDomain: "reportes-proyecto-idor.firebaseapp.com",
    projectId: "reportes-proyecto-idor",
    storageBucket: "reportes-proyecto-idor.firebasestorage.app",
    messagingSenderId: "635696829226",
    appId: "1:635696829226:web:a8b40553eb5b23528b0453"
};

// ─── Guard Firebase ──────────────────────────────────────────────────────────
const app = getApps().length === 0 ? initializeApp(firebaseConfig) : getApps()[0];
const db  = getFirestore(app);

// ─── Modal de confirmación ───────────────────────────────────────────────────
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
        btnOk.className    = danger
            ? "confirm-btn confirm-btn-ok danger"
            : "confirm-btn confirm-btn-ok";
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

// ─── Badge por tipo de incidencia ────────────────────────────────────────────
function getBadge(tipo) {
    const t = (tipo || '').toLowerCase();
    if (t === 'choque')   return '<span class="badge bg-danger">Choque</span>';
    if (t === 'bloqueo')  return '<span class="badge bg-warning text-dark">Bloqueo</span>';
    if (t === 'bache')    return '<span class="badge bg-info text-dark">Bache</span>';
    // Cualquier otro tipo se muestra tal cual con estilo secundario
    return `<span class="badge bg-secondary">${tipo || 'General'}</span>`;
}

// ─── Lista en tiempo real ────────────────────────────────────────────────────
const listContainer = document.getElementById("reportes-list-container");

if (window._reportesUnsubscribe) window._reportesUnsubscribe();

window._reportesUnsubscribe = onSnapshot(
    query(collection(db, "reportes"), orderBy("created_at", "desc")),
    async (snapshot) => {
        if (snapshot.empty) {
            listContainer.innerHTML = "<p>No hay reportes disponibles.</p>";
            return;
        }

        let html = "";
        const ids = [];

        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const id   = docSnap.id;
            ids.push(id);

            const detailUrl = `{{ url('reportes') }}/${id}`;
            const badge     = getBadge(data.tipo_incidencia);

            html += `
                <li class="reportes-list-item">
                    <div class="report-details"
                         onclick="window.location.href='${detailUrl}'"
                         style="cursor:pointer;">
                        <strong>Reporte #${id.substring(0, 5).toUpperCase()}</strong>
                        — ${data.titulo || 'Sin título'}
                        <div class="report-type">${badge}</div>
                    </div>
                    <div style="display:flex; gap:15px; align-items:center;">
                        <button class="btn-fav-toggle" data-id="${id}" title="Alternar Público"
                                style="background:none; border:none; cursor:pointer; font-size:1.3em; color:#ccc;">
                            <i class="fa-solid fa-star"></i>
                        </button>
                        <a href="${detailUrl}" title="Ver detalle"
                           style="color:#0c8e8a; font-size:1.2em;">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button class="btn-eliminar" data-id="${id}" title="Eliminar"
                                style="background:none; border:none; color:#dc3545; cursor:pointer; font-size:1.3em;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </li>
            `;
        });

        listContainer.innerHTML = html;

        // Pintar estrellas de favoritos
        ids.forEach(id => {
            const btn = listContainer.querySelector(`.btn-fav-toggle[data-id="${id}"]`);
            if (btn) verificarFavorito(id, btn);
        });
    },
    (error) => {
        console.error("Error al escuchar reportes:", error);
        listContainer.innerHTML = "<p>Error al cargar datos. Revisa la consola.</p>";
    }
);

// ─── Verificar favorito ──────────────────────────────────────────────────────
async function verificarFavorito(id, btn) {
    try {
        const snap = await getDoc(doc(db, "reportes_publicos", id));
        if (snap.exists() && snap.data().favorito === true) {
            btn.style.color = "gold";
        }
    } catch (e) {
        console.error("Error verificando favorito:", e);
    }
}

// ─── Delegación de eventos ───────────────────────────────────────────────────
listContainer.addEventListener("click", async (e) => {
    const btnFav      = e.target.closest(".btn-fav-toggle");
    const btnEliminar = e.target.closest(".btn-eliminar");

    // --- Favorito / Público ---
    if (btnFav) {
        e.stopPropagation();
        const id         = btnFav.dataset.id;
        const publicoRef = doc(db, "reportes_publicos", id);

        try {
            const snap       = await getDoc(publicoRef);
            const esFavorito = snap.exists() && snap.data().favorito === true;

            const confirmed = await showConfirm({
                message: esFavorito
                    ? "¿Quitar este reporte de los reportes públicos?"
                    : "¿Hacer este reporte público? Será visible para todos.",
                icon:   esFavorito ? "🔕" : "⭐",
                danger: esFavorito
            });

            if (!confirmed) return;

            if (esFavorito) {
                await updateDoc(publicoRef, { favorito: false });
                btnFav.style.color = "#ccc";
            } else {
                const original = await getDoc(doc(db, "reportes", id));
                await setDoc(publicoRef, { ...original.data(), favorito: true });
                btnFav.style.color = "gold";
            }
        } catch (error) {
            console.error("Error al cambiar favorito:", error);
        }
    }

    // --- Eliminar ---
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
            await deleteDoc(doc(db, "reportes", id));
        } catch (error) {
            console.error("Error al eliminar reporte:", error);
        }
    }
});
</script>
@endsection
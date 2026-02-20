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
    .reportes-list-item { display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee; }
    .report-details { flex-grow: 1; }
    .report-title { font-weight: bold; margin-bottom: 2px; }
    .report-type { font-size: 0.9em; color: #666; }
    .report-actions { display: flex; gap: 10px; align-items: center; }
    .menu-icon { width: 35px; height: 35px; margin-right: 15px; object-fit: contain; filter: invert(); }
    .active .menu-icon { filter: invert(0); }
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
        <a href="{{ url('solicitudes') }}"><img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Solicitudes</a>
    </div>

    <div class="main-content">
        <div class="content-box">
            <h2 class="mb-4"><strong>Reportes Públicos (Favoritos)</strong></h2>
            <ul class="reportes-list" id="lista-publica">
                <p>Cargando reportes públicos...</p>
            </ul>
        </div>
    </div>
</div>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getFirestore, collection, query, where, orderBy, onSnapshot, doc, updateDoc, deleteDoc } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

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
    const coleccionPublicos = "reportes_publicos";
    const listaUI = document.getElementById("lista-publica");

    const q = query(collection(db, coleccionPublicos), where("favorito", "==", true), orderBy("created_at", "desc"));

    // 1. Escuchar los cambios en la base de datos
    onSnapshot(q, (snapshot) => {
        if (snapshot.empty) {
            listaUI.innerHTML = "<li style='padding:10px;color:#888;'>No hay reportes públicos marcados.</li>";
            return;
        }

        let htmlContent = "";

        snapshot.forEach((docSnap) => {
            const reporte = docSnap.data();
            const id = docSnap.id;
            const urlVerDetalle = `{{ url('/reportes') }}/${id}`;

            htmlContent += `
                <li class="reportes-list-item">
                    <div class="report-details">
                        <div class="report-title">${reporte.titulo || 'Sin titulo'}</div>
                        <div class="report-type">${reporte.tipo || 'General'}</div>
                    </div>
                    <div class="report-actions">
                        <button class="btn-quitar-fav" data-id="${id}" title="Quitar de favoritos" style="color:gold; border:none; background:none; font-size:1.3em; cursor:pointer;">
                            <i class="fa-solid fa-star"></i>
                        </button>
                        <a href="${urlVerDetalle}" style="color:#0c8e8a; font-size:1.2em;"><i class="fa-solid fa-eye"></i></a>
                        <button class="btn-eliminar-pub" data-id="${id}" style="color:#297581; border:none; background:none; font-size:1.2em; cursor:pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </li>
            `;
        });

        listaUI.innerHTML = htmlContent;
    });

    // 2. Delegación de eventos para la lista
    listaUI.addEventListener("click", async (e) => {
        const btnQuitarFav = e.target.closest(".btn-quitar-fav");
        const btnEliminarPub = e.target.closest(".btn-eliminar-pub");

        if (btnQuitarFav) {
            const id = btnQuitarFav.dataset.id;
            if (confirm("¿Quitar de la lista pública?")) {
                try {
                    await updateDoc(doc(db, coleccionPublicos, id), { favorito: false });
                } catch (error) {
                    console.error("Error al quitar de favoritos:", error);
                }
            }
        }

        if (btnEliminarPub) {
            const id = btnEliminarPub.dataset.id;
            if (confirm("¿Eliminar reporte permanentemente?")) {
                try {
                    await deleteDoc(doc(db, coleccionPublicos, id));
                } catch (error) {
                    console.error("Error al eliminar:", error);
                }
            }
        }
    });
</script>
@endsection
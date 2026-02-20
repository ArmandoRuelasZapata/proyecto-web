@extends('layouts.app')

@section('styles')
<style>
    body { background: #f2f4f7; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
    .page-wrapper { display: flex; flex-grow: 1; }
    .sidebar { width: 260px; min-height: calc(100vh - 66px); position: sticky; top: 66px; background: #0c8e8a; border-right: 1px solid #086b6a; padding-top: 10px; }
    .sidebar a { display: flex; align-items: center; padding: 14px 20px; color: white; text-decoration: none; }
    .sidebar a:hover { background: #086b6a; }
    .sidebar a.active { background: #f2f4f7; color: #333; border-radius: 4px; margin: 0 10px; }
    .main-content { flex-grow: 1; padding: 30px; }
    .content-box { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .reportes-list { list-style: none; padding: 0; }
    .reportes-list-item { display: flex; align-items: center; padding: 12px 15px; border-bottom: 1px solid #eee; }
    .report-details { flex-grow: 1; }
    .menu-icon { width: 35px; height: 35px; margin-right: 15px; filter: invert(1); }
    .active .menu-icon { filter: invert(0); }
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
        <a href="{{ url('solicitudes') }}"><img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Solicitudes</a>
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

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getFirestore, collection, doc, deleteDoc, query, orderBy, onSnapshot, setDoc, getDoc, updateDoc } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-firestore.js";

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
    const listContainer = document.getElementById("reportes-list-container");

    onSnapshot(query(collection(db, "reportes"), orderBy("created_at", "desc")), (snapshot) => {
        let htmlContent = "";
        const reportesIds = []; // Guardamos los IDs para verificar los favoritos después de renderizar

        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const id = docSnap.id;
            reportesIds.push(id);
            
            htmlContent += `
                <li class="reportes-list-item">
                    <div class="report-details" onclick="window.location.href='{{ url('reportes') }}/${id}'" style="cursor:pointer;">
                        <strong>Reporte #${id.substring(0,5).toUpperCase()}</strong> - ${data.titulo || 'Sin título'}
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn-fav-toggle" data-id="${id}" style="background:none; border:none; cursor:pointer; font-size:1.3em; color:#ccc;">
                            <i class="fa-solid fa-star"></i>
                        </button>
                        <button class="btn-eliminar" data-id="${id}" style="background:none; border:none; color:#dc3545; cursor:pointer; margin-left:10px;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </li>
            `;
        });

        listContainer.innerHTML = htmlContent;

        // Una vez renderizado, verificamos cuáles son favoritos para pintar la estrella
        reportesIds.forEach(id => {
            const btn = listContainer.querySelector(`.btn-fav-toggle[data-id="${id}"]`);
            if(btn) verificarFavorito(id, btn);
        });
    });

    async function verificarFavorito(id, btn) {
        try {
            const snap = await getDoc(doc(db, "reportes_publicos", id));
            if(snap.exists() && snap.data().favorito === true) {
                btn.style.color = "gold";
            }
        } catch (error) {
            console.error("Error al verificar favorito:", error);
        }
    }

    // Delegación de eventos para los botones de Favorito y Eliminar
    listContainer.addEventListener("click", async (e) => {
        const btnFav = e.target.closest(".btn-fav-toggle");
        const btnEliminar = e.target.closest(".btn-eliminar");

        if (btnFav) {
            e.stopPropagation(); // Evita que se abra el enlace del reporte
            const id = btnFav.dataset.id;
            const publicoRef = doc(db, "reportes_publicos", id);
            
            try {
                const snap = await getDoc(publicoRef);
                if(snap.exists() && snap.data().favorito === true) {
                    if(confirm("¿Quitar de reportes públicos?")) {
                        await updateDoc(publicoRef, { favorito: false });
                        alert("Quitado de favoritos.");
                        btnFav.style.color = "#ccc"; // Actualizar UI inmediatamente
                    }
                } else {
                    if(confirm("¿Hacer este reporte público?")) {
                        const original = await getDoc(doc(db, "reportes", id));
                        await setDoc(publicoRef, { ...original.data(), favorito: true });
                        alert("¡Ahora es público! ⭐");
                        btnFav.style.color = "gold"; // Actualizar UI inmediatamente
                    }
                }
            } catch (error) {
                console.error("Error al cambiar estado de favorito:", error);
            }
        }

        if (btnEliminar) {
            e.stopPropagation();
            const id = btnEliminar.dataset.id;
            if(confirm("¿Eliminar este reporte permanentemente?")) {
                try {
                    await deleteDoc(doc(db, "reportes", id));
                } catch (error) {
                    console.error("Error al eliminar reporte:", error);
                }
            }
        }
    });
</script>
@endsection
@extends('layouts.master')

@section('content')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --movidgo-teal: #087D83;
        --apple-bg: #f5f5f7;
        --text-dark: #1d1d1f;
    }

    nav, .navbar { display: none !important; }

    body {
        background-color: #ffffff;
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif;
        color: var(--text-dark);
    }

    .section-contact {
        padding: 100px 0;
        background-color: #ffffff;
    }

    .section-title {
        font-weight: 700;
        font-size: 3rem;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }

    .contact-card {
        border: none;
        border-radius: 30px;
        background: #ffffff;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        padding: 40px;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        color: #86868b;
        margin-left: 5px;
    }

    .form-control {
        border-radius: 12px;
        border: 1px solid #d2d2d7;
        padding: 12px 15px;
        background-color: #f5f5f7;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background-color: #ffffff;
        border-color: var(--movidgo-teal);
        box-shadow: 0 0 0 4px rgba(8, 125, 131, 0.1);
    }

    .btn-apple {
        background-color: var(--movidgo-teal);
        color: white;
        border-radius: 980px;
        padding: 15px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s;
        border: none;
    }

    .btn-apple:hover {
        transform: scale(1.02);
        background-color: #055a5e;
        color: white;
    }

    .btn-apple:disabled {
        background-color: #ccc;
        transform: scale(1);
    }
</style>

<section class="section-contact">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                
                <div class="text-center mb-5">
                    <h1 class="section-title">Estamos para ayudarte</h1>
                    <p class="lead text-muted">Cuéntanos cómo podemos mejorar tu experiencia</p>
                </div>

                <div class="contact-card">
                    <form id="contactForm">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input class="form-control" type="text" id="nombre" required placeholder="Tu nombre">
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input class="form-control" type="email" id="email" required placeholder="ejemplo@correo.com">
                            </div>
                            <div class="col-md-4">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-control" id="prioridad" required>
                                    <option value="" selected disabled>Elige una opción</option>
                                    <option value="alta">Alta - Urgente</option>
                                    <option value="media">Media - Consulta</option>
                                    <option value="baja">Baja - Sugerencia</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="asunto" class="form-label">Asunto</label>
                            <input class="form-control" id="asunto" type="text" required placeholder="¿De qué trata tu mensaje?">
                        </div>

                        <div class="mb-5">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" rows="5" required placeholder="Escribe aquí tus detalles..."></textarea>
                        </div>

                        <button type="submit" id="btnEnviar" class="btn btn-apple w-100 shadow">Enviar Mensaje</button>
                    </form>
                </div>

                <div class="text-center mt-5 text-muted">
                    <p>También puedes escribirnos directamente a <br> <strong>soporte@movidgo.com</strong></p>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script type="module">
    // 1. Importar Firebase
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
    import { getFirestore, collection, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";

    // 2. Configuración
    const firebaseConfig = {
        apiKey: "AIzaSyCha641yRxJBUVWsDD_dKNmqrWb-Cj6JhU",
        authDomain: "contactos2-9b78b.firebaseapp.com",
        projectId: "contactos2-9b78b",
        storageBucket: "contactos2-9b78b.firebasestorage.app",
        messagingSenderId: "509739763203",
        appId: "1:509739763203:web:0e05a89aa23ba0a2ca7036"
    };

    // 3. Inicializar
    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    // 4. Lógica del Formulario
    const contactForm = document.getElementById('contactForm');
    const btnEnviar = document.getElementById('btnEnviar');

    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Bloquear botón para evitar doble envío
        btnEnviar.disabled = true;
        btnEnviar.innerText = "Enviando...";

        // Recolectar datos
        const datos = {
            nombre: document.getElementById('nombre').value,
            correo: document.getElementById('email').value,
            prioridad: document.getElementById('prioridad').value,
            asunto: document.getElementById('asunto').value,
            mensaje: document.getElementById('mensaje').value,
            fecha: serverTimestamp() // Importante para ordenar en el panel
        };

        try {
            // Guardar en la colección "contactos"
            await addDoc(collection(db, "contactos"), datos);

            // Éxito
            Swal.fire({
                icon: 'success',
                title: '¡Enviado!',
                text: 'Tu mensaje ha sido recibido correctamente.',
                confirmButtonColor: '#087D83'
            });

            contactForm.reset();
        } catch (error) {
            console.error("Error al enviar:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No pudimos enviar tu mensaje. Inténtalo más tarde.',
            });
        } finally {
            btnEnviar.disabled = false;
            btnEnviar.innerText = "Enviar Mensaje";
        }
    });

    // Iniciar animaciones
    AOS.init({
        duration: 1000,
        once: true
    });
</script>
@endsection
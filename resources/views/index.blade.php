@extends("layouts.master")

@section("content")
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    :root {
        --movidgo-teal: #087D83;
        --movidgo-dark: #055a5e;
        --apple-bg: #f5f5f7;
        --text-dark: #1d1d1f;
        --text-gray: #86868b;
    }

    nav, header, .navbar { display: none !important; }

    body {
        background-color: #ffffff;
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", sans-serif;
        color: var(--text-dark);
        overflow-x: hidden;
    }

    .section-white { background-color: #ffffff; padding: 80px 0; }
    .section-teal { background-color: var(--movidgo-teal); color: white; padding: 80px 0; }
    .section-gray { background-color: var(--apple-bg); padding: 80px 0; }

    .carousel {
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .section-title {
        font-weight: 700;
        font-size: 3rem;
        letter-spacing: -0.02em;
        margin-bottom: 1.5rem;
    }

    /* CARDS - Ajustes de imagen estática */
    .custom-card {
        border: none;
        border-radius: 22px;
        background: #ffffff;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        overflow: hidden; /* Importante para que la imagen no se salga de las esquinas redondeadas */
    }

    /* ESTO MANTIENE LA IMAGEN ESTÁTICA */
    .custom-card .card-img-top {
        height: 220px; /* Altura fija para todas las imágenes */
        object-fit: cover; /* Recorta la imagen para llenar el espacio sin deformarse */
        object-position: center; /* Centra el recorte */
        width: 100%;
    }

    .custom-card:hover {
        transform: scale(1.03);
        box-shadow: 0 30px 60px rgba(0,0,0,0.12);
    }

    .btn-apple {
        background-color: var(--movidgo-teal);
        color: white;
        border-radius: 980px;
        padding: 12px 30px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .stat-number { font-size: 3.5rem; font-weight: 700; display: block; }
    .stat-label { font-size: 1.1rem; opacity: 0.9; }
</style>

<section class="section-white">
    <div class="container" data-aos="fade-up">
        <div class="text-center mb-5">
            <h1 class="section-title">Muévete e informa sobre el estado de las vias.</h1>
            <p class="lead text-muted">MoviDGO redefine la forma de reportar incidencias de tu ciudad.</p>
        </div>
        
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active"><img src="{{ asset('images/carrucel_1.png') }}" class="d-block w-100"></div>
                <div class="carousel-item"><img src="{{ asset('images/carrucel_2.png') }}" class="d-block w-100"></div>
                <div class="carousel-item"><img src="{{ asset('images/carrucel_3.png') }}" class="d-block w-100"></div>
            </div>
        </div>
    </div>
</section>

<section class="section-gray">
    <div class="container">
        <h2 class="text-center section-title mb-5" data-aos="fade-up">Servicios</h2>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card custom-card">
                    <img src="{{ asset('images/mps.jpeg') }}" class="card-img-top" alt="Mapa">
                    <div class="card-body p-4 text-center">
                        <h5>Mapa Dinámico</h5>
                        <p class="text-muted small">Visualización en tiempo real de cada reporte vial.</p>
                        <a href="#" class="btn-apple mt-3">Explorar</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card custom-card">
                    <img src="{{ asset('images/reporte.png')}}" class="card-img-top" alt="Reporte">
                    <div class="card-body p-4 text-center">
                        <h5>Reportar muy facilmente</h5>
                        <p class="text-muted small">Toma el control e informa incidencias al instante.</p>
                        <a href="#" class="btn-apple mt-3">Explorar</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card custom-card">
                    <img src="{{ asset('images/oficiales (1).jpg')}}" class="card-img-top" alt="Oficiales">
                    <div class="card-body p-4 text-center">
                        <h5>Oficiales Online</h5>
                        <p class="text-muted small">Sincronización directa con las autoridades locales.</p>
                        <a href="#" class="btn-apple mt-3">Explorar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-teal text-center">
    <div class="container" data-aos="zoom-in">
        <div class="row">
            <div class="col-md-4 mb-4">
                <span class="stat-number">+10k</span>
                <span class="stat-label">Usuarios activos</span>
            </div>
            <div class="col-md-4 mb-4">
                <span class="stat-number">24/7</span>
                <span class="stat-label">Monitoreo vial</span>
            </div>
            <div class="col-md-4 mb-4">
                <span class="stat-number">100%</span>
                <span class="stat-label">Gratis</span>
            </div>
        </div>
    </div>
</section>

<section class="section-white text-center">
    <div class="container" data-aos="fade-up">
        <h2 class="section-title">¿Listo para cambiar la forma de reportar incidencias?</h2>
        <p class="lead text-muted mb-4">Únete a la comunidad de MoviDGO.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#" class="btn-apple">Descargar App</a>
            <a href="#" class="btn-apple" style="background: #000;">App Store</a>
        </div>
        <img src="{{ asset('images/mps.jpeg') }}" class="img-fluid mt-5 rounded-4 shadow-lg" style="max-height: 400px; width: 100%; object-fit: cover;">
    </div>
</section>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-in-out'
    });
</script>

@endsection
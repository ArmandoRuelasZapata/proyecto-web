<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sitio web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* (Tus estilos de footer se mantienen igual) */
        .apple-footer {
            background-color: #f5f5f7 !important;
            color: #1d1d1f !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 13px;
            padding: 60px 0 40px 0;
            border-top: 1px solid #d2d2d7;
        }
        .footer-title { font-weight: 600; font-size: 14px; margin-bottom: 15px; color: #1d1d1f; }
        .footer-text { color: #6e6e73; line-height: 1.6; text-align: justify; }
        .footer-list { list-style: none; padding: 0; }
        .footer-list li { margin-bottom: 10px; }
        .footer-list a { color: #424245; text-decoration: none; transition: color 0.2s ease; }
        .footer-list a:hover { color: #087D83; text-decoration: underline; }
        .social-link { display: flex; align-items: center; color: #424245; text-decoration: none; margin-bottom: 12px; transition: opacity 0.2s; }
        .social-link:hover { opacity: 0.7; color: #087D83; }
        .social-icon-img { width: 20px; height: 20px; margin-right: 12px; filter: grayscale(100%); }
        .social-link:hover .social-icon-img { filter: grayscale(0%); }
        .footer-bottom { margin-top: 40px; padding-top: 20px; border-top: 1px solid #d2d2d7; color: #86868b; font-size: 11px; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">MovidGO</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="index.blade" href="{{ url('/') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('contact.blade') }}">Contacto</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    @yield("content")

    <footer class="apple-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 text-center text-md-start">
                    <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo MOVI" width="120" class="mb-3">
                    <p class="footer-text" style="font-size: 11px;">
                        Copyright © 2026 MoviDGO.<br>Todos los derechos reservados.
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Nuestra Misión</h5>
                    <p class="footer-text">
                        Mejorar la seguridad y eficiencia vial proporcionando información actualizada y reportes en tiempo real para una mejor toma de decisiones.
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Enlaces rápidos</h5>
                    <ul class="footer-list">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li><a href="#">Acerca de nosotros</a></li>
                        <li><a href="#">Servicios viales</a></li>
                        <li><a href="{{ url('contacto') }}">Contacto</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Redes Sociales</h5>
                    <div class="d-flex flex-column">
                        <a href="#" class="social-link"><img src="{{ asset('images/facebook.png') }}" class="social-icon-img" alt="FB"> Facebook</a>
                        <a href="#" class="social-link"><img src="{{ asset('images/instagram.png') }}" class="social-icon-img" alt="IG"> Instagram</a>
                        <a href="#" class="social-link"><img src="{{ asset('images/whatsapp.png') }}" class="social-icon-img" alt="WA"> WhatsApp</a>
                        <a href="#" class="social-link"><img src="{{ asset('images/linkedin.png') }}" class="social-icon-img" alt="LI"> LinkedIn</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p>Uso de cookies | Política de privacidad</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>México | MoviDGO Inteligencia Vial</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
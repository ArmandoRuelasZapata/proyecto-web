<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MoviDGO') }}</title>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        nav.navbar {
            background: #0c8e8a !important;
        }

        .navbar-brand span {
            font-weight: bold;
            color: white !important;
        }

        .navbar-brand img {
            width: 45px;
            height: 45px;
            border-radius: 10px;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-size: 16px;
            margin-right: 15px;
            display: flex;
            align-items: center;
        }

        .navbar-nav .nav-link i {
            margin-right: 6px;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
        }

        .dropdown-menu a i {
            margin-right: 8px;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div id="app">

        <nav class="navbar navbar-expand-md shadow-sm fixed-top">
            <div class="container-fluid d-flex align-items-center">

                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('images/logoMOVI.png') }}" class="me-2" alt="Logo">
                    <span>MoviDGO</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav me-auto">
                    </ul>

                    <ul class="navbar-nav ms-auto">

                        {{-- VERIFICAMOS SI EXISTE LA COOKIE DE FIREBASE --}}
                        @if(!request()->cookie('firebase_token'))
                            {{-- SI NO ESTÁ LOGUEADO --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fa-solid fa-right-to-bracket"></i> Login
                                </a>
                            </li>

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fa-solid fa-user-plus"></i> Registrar
                                    </a>
                                </li>
                            @endif

                        @else
                            {{-- SI SÍ ESTÁ LOGUEADO --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user"></i> Administrador
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endif

                    </ul>

                </div>
            </div>
        </nav>

        <main style="margin-top: 80px;">
            @yield('content')
        </main>

        @yield('scripts')

    </div>
</body>

</html>
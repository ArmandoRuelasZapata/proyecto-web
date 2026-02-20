@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-2xl w-full max-w-sm p-8">
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo" class="w-20 h-20">
        </div>

        {{-- Título --}}
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Restablecer Contraseña</h2>

        {{-- Mensajes de estado --}}
        @if (session('status'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-md mb-4 text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-5">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="Correo electrónico"
                    class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-2 focus:ring-teal-400 focus:outline-none @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-teal-400 text-white py-3 rounded-md hover:bg-teal-500 transition-all">
                Enviar enlace de restablecimiento
            </button>

            {{-- Enlace para volver al login --}}
            <p class="text-center text-gray-500 text-sm mt-6">
                ¿Recordaste tu contraseña? 
                <a href="{{ route('login') }}" class="text-teal-500 hover:underline">Iniciar sesión</a>
            </p>
        </form>
    </div>

</body>
</html>
@endsection

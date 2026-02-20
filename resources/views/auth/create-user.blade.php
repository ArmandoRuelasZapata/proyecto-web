@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Estilo Dual</title>
    <style>
        :
        body {
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus {
            border-color: rgba(8, 125, 131, 1);
            box-shadow: 0 0 0 3px rgba(8, 125, 131, 1); 
            outline: none;
        }
        .register-button {
            box-shadow: 0 5px 15px -3px rgba(8, 125, 131, 1); 
        }
        .register-button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .register-button:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(68, 125, 131, 1);
        }
        .text-gray-600 a {
            color: rgba(8, 125, 131, 1); 
        }
    </style>
</head>
<body style="background-color: var(--main-bg);">
<div class="min-h-screen flex items-center justify-center p-4 h-screen">
    <div class="w-full max-w-6xl flex shadow-2xl rounded-2xl overflow-hidden bg-white h-full">
        
        <div class="image-col hidden lg:flex w-1/2 p-12 items-center justify-center relative h-full">
            <div class="relative z-10 text-white text-center">
                <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo MOVI" width="500" height="150" class="mx-auto block">
            </div>
        </div>
        <div class="w-full lg:w-1/2 p-8 sm:p-12 md:p-16 flex flex-col justify-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 text-center">Registro</h2>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                {{-- Nombre --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input id="name" type="text" name="name"
                        value="{{ old('name') }}" required autocomplete="name" autofocus
                        placeholder="Nombre completo"
                        class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-2 focus:ring-teal-400 focus:outline-none @error('name') border-red-500 @enderror">
                    @error('name') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Correo electrónico --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}" required autocomplete="email"
                        placeholder="email@ejemplo.com"
                        class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-2 focus:ring-teal-400 focus:outline-none @error('email') border-red-500 @enderror">
                    @error('email') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Contraseña --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required
                        autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                        class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-2 focus:ring-teal-400 focus:outline-none @error('password') border-red-500 @enderror">
                    @error('password') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Confirmar Contraseña --}}
                <div class="mb-8">
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Repite la contraseña"
                        class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-2 focus:ring-teal-400 focus:outline-none">
                </div>
                {{-- Botón --}}
                <div class="flex items-center justify-end mb-8">
                    <button type="submit" 
                        class="w-full px-6 py-3 font-semibold rounded-xl transition duration-200 ease-in-out shadow-lg register-button"
                        style="background-color: #087D83 !important; color:#ffffff  !important;">
                        Registrarse
                    </button>
                </div>
                <p class="mt-6 text-center text-sm text-gray-600">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="font-medium hover:underline transition-colors duration-200" style="color: #087D83;">
                        Iniciar Sesión
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<script src="https://cdn.tailwindcss.com"></script>
@endsection

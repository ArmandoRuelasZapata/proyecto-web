@extends('layouts.app')

@section('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .login-wrapper {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
@endsection

@section('content')
<div class="login-wrapper w-full flex justify-center items-center py-16 px-4 min-h-screen">

    <div class="bg-white shadow-2xl rounded-2xl w-full max-w-sm p-8 border border-gray-200">
        
        {{-- Logo (Mantiene la estructura de tu imagen) --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo" class="w-20 h-20">
        </div>

        {{-- Formulario de actualización de contraseña --}}
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Token de seguridad requerido por Laravel --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Campo de Correo Electrónico --}}
            <div class="mb-4">
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                    placeholder="Correo electrónico"
                    class="w-full px-4 py-3 rounded-md border border-gray-300 
                    focus:ring-2 focus:ring-[#087D83] focus:outline-none @error('email') border-red-500 @enderror">
                
                @error('email')
                    <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo de Nueva Contraseña --}}
            <div class="mb-4">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    placeholder="Nueva contraseña"
                    class="w-full px-4 py-3 rounded-md border border-gray-300 
                    focus:ring-2 focus:ring-[#087D83] focus:outline-none @error('password') border-red-500 @enderror">
                
                @error('password')
                    <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo para Confirmar Contraseña --}}
            <div class="mb-6">
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="Confirmar contraseña"
                    class="w-full px-4 py-3 rounded-md border border-gray-300 
                    focus:ring-2 focus:ring-[#087D83] focus:outline-none">
            </div>

            {{-- Botón Principal --}}
            <button type="submit"
                class="w-full bg-[#087D83] text-white py-3 rounded-md hover:bg-[#066e72] transition-all font-semibold shadow-md">
                Restablecer contraseña
            </button>
            
            {{-- Enlace opcional para volver al inicio de sesión --}}
            <p class="text-center text-gray-500 text-sm mt-6">
                <a href="{{ route('login') }}" class="text-[#087D83] font-medium hover:underline">Volver a iniciar sesión</a>
            </p>
        </form>

    </div>
</div>
@endsection
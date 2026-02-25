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
        
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo" class="w-20 h-20">
        </div>

        {{-- Título --}}
        <h2 class="text-xl font-bold text-center text-gray-800 mb-6">Restablecer Contraseña</h2>

        {{-- Mensajes de éxito/error (div para Firebase) --}}
        <div id="status-message" class="hidden px-4 py-2 rounded-md mb-4 text-sm text-center"></div>

        {{-- Formulario corregido para Firebase --}}
        <form id="reset-form">
            <div class="mb-5">
                <input id="email" type="email" name="email" required autofocus
                    placeholder="Correo electrónico"
                    class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-2 focus:ring-[#087D83] focus:outline-none">
            </div>

            <button type="submit" id="btn-reset"
                class="w-full bg-[#087D83] text-white py-3 rounded-md hover:bg-[#066e72] transition-all font-semibold shadow-md">
                Enviar enlace
            </button>

            <p class="text-center text-gray-500 text-sm mt-6">
                ¿Recordaste tu contraseña? 
                <a href="{{ route('login') }}" class="text-[#087D83] font-medium hover:underline">Iniciar sesión</a>
            </p>
        </form>

    </div>
</div>
@endsection

@section('scripts')
{{-- Scripts de Firebase --}}
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getAuth, sendPasswordResetEmail } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    // Tu configuración (usa la misma que en el Login)
    const firebaseConfig = {
        apiKey: "AIzaSyBpGZNsDK73vv_MBSYkIg0ak9vFtuP6Zcs",
        authDomain: "proyecto-integrador-ll-65353.firebaseapp.com",
        projectId: "proyecto-integrador-ll-65353",
        messagingSenderId: "215705091104",
        appId: "1:215705091104:web:e4f6e8891711fcd4cb5045"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    const form = document.getElementById('reset-form');
    const btnReset = document.getElementById('btn-reset');
    const statusDiv = document.getElementById('status-message');

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Evita que la página se recargue
        
        const email = document.getElementById('email').value;
        btnReset.disabled = true;
        btnReset.innerText = "Enviando...";

        sendPasswordResetEmail(auth, email)
            .then(() => {
                statusDiv.innerText = "¡Enlace enviado! Revisa tu correo electrónico.";
                statusDiv.classList.remove('hidden', 'bg-red-100', 'text-red-700');
                statusDiv.classList.add('bg-green-100', 'text-green-700');
                
                // Opcional: Redirigir al login después de 3 segundos
                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 3000);
            })
            .catch((error) => {
                console.error("Error: ", error.code);
                statusDiv.innerText = "Error: Correo no encontrado o inválido.";
                statusDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                statusDiv.classList.add('bg-red-100', 'text-red-700');
                
                btnReset.disabled = false;
                btnReset.innerText = "Enviar enlace";
            });
    });
</script>
@endsection
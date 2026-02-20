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
        
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo" class="w-20 h-20">
        </div>

        <div id="error-message" class="hidden mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm text-center"></div>

        <form id="login-form">
            <div class="mb-4">
                <input id="email" type="email" required autofocus
                    placeholder="Correo electrónico"
                    class="w-full px-4 py-3 rounded-md border border-gray-300 
                    focus:ring-2 focus:ring-[#087D83] focus:outline-none">
            </div>

            <div class="mb-4">
                <input id="password" type="password" required placeholder="Contraseña"
                    class="w-full px-4 py-3 rounded-md border border-gray-300 
                    focus:ring-2 focus:ring-[#087D83] focus:outline-none">
            </div>

            <div class="flex justify-end mb-4">
               {{-- <a href="#" class="text-sm text-[#087D83] hover:underline">
                    ¿Olvidaste tu contraseña?
                </a> --}}
            </div>

            <button type="submit" id="submit-btn"
                class="w-full bg-[#087D83] text-white py-3 rounded-md hover:bg-[#066e72] transition-all font-semibold shadow-md">
                Iniciar sesión
            </button>

            <p class="text-center text-gray-500 text-sm mt-6">
                ¿Aún no tienes una cuenta?
                <a href="{{ route('register') }}" class="text-[#087D83] font-medium hover:underline">Registrarse</a>
            </p>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    if (!window.__loginLoaded) {
        window.__loginLoaded = true;

        const firebaseConfig = {
            apiKey: "AIzaSyBpGZNsDK73vv_MBSYkIg0ak9vFtuP6Zcs",
            authDomain: "proyecto-integrador-ll-65353.firebaseapp.com",
            projectId: "proyecto-integrador-ll-65353",
            messagingSenderId: "215705091104",
            appId: "1:215705091104:web:e4f6e8891711fcd4cb5045"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        const form = document.getElementById('login-form');
        const errorDiv = document.getElementById('error-message');
        const submitBtn = document.getElementById('submit-btn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value.trim().toLowerCase();
            const password = document.getElementById('password').value;

            errorDiv.classList.add('hidden');
            errorDiv.innerText = '';

            try {
                submitBtn.innerText = 'Iniciando sesión...';
                submitBtn.disabled = true;

                const userCredential = await signInWithEmailAndPassword(auth, email, password);
                const user = userCredential.user;

                const idToken = await user.getIdToken();

                const response = await fetch('{{ route('firebase.login') }}', {
                    method: 'POST',
                    credentials: 'same-origin', 
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ token: idToken })
                });

                if (response.ok) {
                    window.location.href = '{{ route('home') }}';
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Error al sincronizar sesión con el servidor');
                }

            } catch (error) {
                console.error("Error al iniciar sesión:", error);
                
                let mensaje = 'Ocurrió un error al iniciar sesión.';
                
                if (error.message && error.message.includes('sincronizar')) {
                    mensaje = 'Error conectando con el servidor. Intenta de nuevo.';
                } else if (error.code === 'auth/invalid-credential') {
                    mensaje = 'El correo o la contraseña son incorrectos.';
                } else if (error.code === 'auth/too-many-requests') {
                    mensaje = 'Demasiados intentos fallidos. Intenta más tarde.';
                } else if (error.message) {
                    mensaje = error.message; 
                }
                
                showError(mensaje);
            } finally {
                submitBtn.innerText = 'Iniciar sesión';
                submitBtn.disabled = false;
            }
        });

        function showError(message) {
            errorDiv.innerText = message;
            errorDiv.classList.remove('hidden');
        }
    }
</script>
@endsection
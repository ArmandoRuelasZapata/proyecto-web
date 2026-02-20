@extends('layouts.app')
@section('content')

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body>
<div class="min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-6xl bg-white shadow-xl rounded-2xl flex overflow-hidden">

        <!-- Imagen -->
        <div class="hidden lg:flex w-1/2 items-center justify-center p-16 bg-white">
            <img src="{{ asset('images/logoMOVI.png') }}" alt="Logo"
                 class="max-w-md">
        </div>

        <!-- Formulario -->
        <div class="w-full lg:w-1/2 p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">
                Registro
            </h2>

            <div id="error-message"
                 class="hidden mb-6 p-3 bg-red-100 text-red-700 rounded text-sm text-center"></div>

            <form>
                <!-- Nombre -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre
                    </label>
                    <input id="name"
                           type="text"
                           placeholder="Nombre completo"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none">
                </div>

                <!-- Correo -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input id="email"
                           type="email"
                           placeholder="email@ejemplo.com"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-blue-50">
                </div>

                <!-- Contraseña -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <input id="password"
                           type="password"
                           placeholder="Mínimo 6 caracteres"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-blue-50">
                </div>

                <!-- Confirmar -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Contraseña
                    </label>
                    <input id="password-confirm"
                           type="password"
                           placeholder="Repite la contraseña"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                </div>

                <!-- Botón -->
                <button type="button" id="submit-btn"
                        class="w-full py-3 rounded-lg font-semibold text-white shadow-md transition"
                        style="background:#087D83">
                    Registrarse
                </button>

                <p class="mt-6 text-center text-sm text-gray-600">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}"
                       class="text-teal-600 font-medium hover:underline">
                        Iniciar Sesión
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getAuth, createUserWithEmailAndPassword, updateProfile } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    // 🔒 Evita doble carga del script
    if (!window.__registerLoaded) {
        window.__registerLoaded = true;

        const firebaseConfig = {
            apiKey: "AIzaSyBpGZNsDK73vv_MBSYkIg0ak9vFtuP6Zcs",
            authDomain: "proyecto-integrador-ll-65353.firebaseapp.com",
            projectId: "proyecto-integrador-ll-65353",
            messagingSenderId: "215705091104",
            appId: "1:215705091104:web:e4f6e8891711fcd4cb5045"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        const btn = document.getElementById('submit-btn');
        const errorDiv = document.getElementById('error-message');

        btn.onclick = async () => {
            btn.disabled = true;
            btn.innerText = 'Registrando...';

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim().toLowerCase();
            const pass = document.getElementById('password').value;
            const conf = document.getElementById('password-confirm').value;

            errorDiv.classList.add('hidden');

            if (!name || !email || !pass) {
                showError('Por favor llena todos los campos.');
                reset();
                return;
            }

            if (pass !== conf) {
                showError('Las contraseñas no coinciden.');
                reset();
                return;
            }

            try {
                const cred = await createUserWithEmailAndPassword(auth, email, pass);
                await updateProfile(cred.user, { displayName: name });

                alert('¡Registro exitoso!');
                window.location.href = "{{ route('login') }}";
            } catch (e) {
                let msg = 'Error al registrar.';
                if (e.code === 'auth/email-already-in-use') msg = 'Correo ya registrado.';
                if (e.code === 'auth/weak-password') msg = 'Contraseña muy débil.';
                if (e.code === 'auth/invalid-email') msg = 'Correo inválido.';
                showError(msg);
                reset();
            }
        };

        function showError(msg) {
            errorDiv.innerText = msg;
            errorDiv.classList.remove('hidden');
        }

        function reset() {
            btn.disabled = false;
            btn.innerText = 'Registrarse';
        }
    }
</script>
</body>
</html>

@endsection

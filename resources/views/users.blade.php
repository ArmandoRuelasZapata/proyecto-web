@extends('layouts.app')

@section('styles')
<style>
    body {
        background: #f2f4f7;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .page-wrapper {
        display: flex;
        flex-grow: 1;
    }

    .sidebar {
        width: 260px;
        min-height: calc(100vh - 66px);
        position: sticky;
        top: 66px;
        background: #0c8e8a;
        border-right: 1px solid #086b6a;
        padding-top: 10px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: white;
        font-size: 16px;
        text-decoration: none;
        transition: 0.2s;
    }

    .sidebar a:hover {
        background: #086b6a;
    }

    .sidebar a.active {
        background: #f2f4f7;
        color: #333;
        border-radius: 4px;
        margin: 0 10px;
    }

    .menu-icon {
        width: 35px;
        height: 35px;
        margin-right: 15px;
        filter: invert();
        object-fit: contain;
    }

    .active img {
        filter: invert(0);
    }

    /* CONTENIDO PRINCIPAL */
    .main-content {
        flex-grow: 1;
        padding: 30px;
    }

    .custom-card {
        border: none;
        border-radius: 15px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .custom-card-header {
        background-color: #0c8e8a;
        color: white;
        padding: 15px 25px;
        font-size: 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-add-admin {
        background-color: #ffffff;
        color: #0c8e8a;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-add-admin:hover {
        background-color: #e8f6f6;
        color: #086b6a;
        transform: translateY(-1px);
    }

    .table thead {
        background-color: #e8f6f6;
        color: #0c8e8a;
    }

    .action-btn {
        background: none;
        border: none;
        font-size: 1.1rem;
        transition: color 0.2s;
        padding: 5px 10px;
        cursor: pointer;
    }

    .btn-edit  { color: #0c8e8a; }
    .btn-edit:hover  { color: #086b6a; }
    .btn-delete { color: #dc3545; }
    .btn-delete:hover { color: #a71d2a; }

    /* === MODAL DE CONFIRMACIÓN === */
    .confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }

    .confirm-overlay.active { display: flex; }

    .confirm-box {
        background: #fff;
        border-radius: 12px;
        padding: 28px 30px;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
        text-align: center;
        animation: popIn 0.2s ease;
    }

    @keyframes popIn {
        from { transform: scale(0.85); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }

    .confirm-icon { font-size: 2.2em; margin-bottom: 12px; }

    .confirm-box p { margin: 0 0 20px; color: #333; font-size: 1em; line-height: 1.5; }

    .confirm-actions { display: flex; gap: 10px; justify-content: center; }

    .confirm-btn {
        padding: 9px 22px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        font-size: 0.95em;
        transition: filter 0.2s;
    }

    .confirm-btn:hover { filter: brightness(0.9); }
    .confirm-btn-ok     { background: #0c8e8a; color: white; }
    .confirm-btn-ok.danger { background: #dc3545; }
    .confirm-btn-cancel { background: #e9e9e9; color: #555; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="page-wrapper">

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <a href="{{ url('home') }}">
            <img src="{{ asset('img/informe-de-datos.png') }}" alt="Dashboard" class="menu-icon">
            Dashboard
        </a>
        <a href="{{ url('crud') }}">
            <img src="{{ asset('img/red-mundial.png') }}" alt="Reportes públicos" class="menu-icon">
            Reportes públicos
        </a>
        <a href="{{ url('reportes') }}">
            <img src="{{ asset('img/tus reportes.png') }}" alt="Reportes" class="menu-icon">
            Reportes
        </a>
        <a href="{{ url('moderadores') }}">
            <img src="{{ asset('img/proteger.png') }}" alt="Moderadores" class="menu-icon">
            Moderadores
        </a>
        <a href="{{ url('leer-usuarios') }}" class="active">
            <img src="{{ asset('img/admin.png') }}" alt="Administradores" class="menu-icon">
            Administradores
        </a>
        <a href="{{ url('leer-contactos') }}">
            <img src="{{ asset('img/contacts.png') }}" alt="Contactos" class="menu-icon">
            Contactos
        </a>
        <a href="{{ url('cuentasbloqueadas') }}">
            <img src="{{ asset('img/cuenta-privada.png') }}" alt="Cuentas bloqueadas" class="menu-icon">
            Cuentas bloqueadas
        </a>
        <a href="{{ url('solicitudes') }}">
            <img src="{{ asset('img/soporte y contacto.png') }}" alt="Soporte" class="menu-icon">
            Soporte
        </a>
    </div>

    {{-- CONTENIDO --}}
    <div class="main-content">
        <div class="container-fluid">
            <div class="custom-card">
                <div class="custom-card-header">
                    <strong>Gestión de Administradores</strong>
                    <a href="{{ route('users.create') }}" class="btn-add-admin">
                        <i class="fa-solid fa-plus me-2"></i> Agregar Administrador
                    </a>
                </div>

                <div class="p-4 border-bottom">
                    <p class="text-muted mb-0">
                        Visualiza, edita o elimina los administradores.
                    </p>
                </div>

                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="120">UID</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Último Acceso</th>
                                <th class="text-center" width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $user)
                            <tr>
                                <td class="text-muted"><small>...{{ substr($user->uid, -8) }}</small></td>
                                <td class="fw-bold">{{ $user->displayName ?? 'Usuario sin nombre' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    {{ $user->metadata->lastLoginAt ? $user->metadata->lastLoginAt->format('d/m/Y H:i') : 'Nunca' }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('users.edit', $user->uid) }}"
                                           class="action-btn btn-edit" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        {{-- El form se envía desde JS tras confirmar en el modal --}}
                                        <form id="form-delete-{{ $user->uid }}"
                                              action="{{ route('users.destroy', $user->uid) }}"
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="action-btn btn-delete btn-trigger-delete"
                                                    data-uid="{{ $user->uid }}"
                                                    data-name="{{ $user->displayName ?? 'este usuario' }}"
                                                    title="Eliminar">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No se encontraron usuarios.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE CONFIRMACIÓN -->
<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon" id="confirm-icon">🗑️</div>
        <p id="confirm-message">¿Estás seguro?</p>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="confirm-cancel">Cancelar</button>
            <button class="confirm-btn confirm-btn-ok danger" id="confirm-ok">Sí, eliminar</button>
        </div>
    </div>
</div>

<script>
    // ─── Modal de confirmación (Lógica Promisificada) ─────────────────────────
    let confirmIsOpen = false;

    function showConfirm({ message = "¿Estás seguro?", icon = "⚠️" } = {}) {
        return new Promise((resolve) => {
            if (confirmIsOpen) { resolve(false); return; }
            confirmIsOpen = true;

            const overlay   = document.getElementById("confirm-overlay");
            const msgEl     = document.getElementById("confirm-message");
            const iconEl    = document.getElementById("confirm-icon");
            const btnOk     = document.getElementById("confirm-ok");
            const btnCancel = document.getElementById("confirm-cancel");

            // Configurar contenido
            msgEl.textContent  = message;
            iconEl.textContent = icon;
            overlay.classList.add("active");

            const cleanup = (result) => {
                overlay.classList.remove("active");
                confirmIsOpen = false;
                // Importante: remover listeners para evitar ejecuciones múltiples en el siguiente clic
                btnOk.removeEventListener("click", onOk);
                btnCancel.removeEventListener("click", onCancel);
                resolve(result);
            };

            const onOk     = () => cleanup(true);
            const onCancel = () => cleanup(false);

            btnOk.addEventListener("click", onOk);
            btnCancel.addEventListener("click", onCancel);
        });
    }

    // ─── Interceptar clics en botones de eliminar ───────────────────────────
    document.addEventListener("click", async (e) => {
        // Buscamos si el clic fue en un botón con la clase .btn-trigger-delete
        const btn = e.target.closest(".btn-trigger-delete");
        
        if (btn) {
            e.preventDefault(); // Detenemos cualquier acción por defecto
            
            const uid  = btn.dataset.uid;
            const name = btn.dataset.name;

            // Esperamos la respuesta del modal personalizado
            const confirmed = await showConfirm({
                message: `¿Eliminar permanentemente a "${name}"? Esta acción no se puede deshacer una vez realizada.`,
                icon: "🗑️"
            });

            if (confirmed) {
                // Buscamos el formulario específico mediante el ID único generado en el Blade
                const form = document.getElementById(`form-delete-${uid}`);
                if (form) {
                    form.submit();
                } else {
                    console.error("No se encontró el formulario para el UID:", uid);
                }
            }
        }
    });
</script>
@endsection
@extends('layouts.app')

@section('styles')
<style>
    body { background: #f2f4f7; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
    .page-wrapper { display: flex; flex-grow: 1; }
    
    /* SIDEBAR STYLES */
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
        display: flex; align-items: center; padding: 14px 20px; color: white;
        font-size: 16px; text-decoration: none; transition: 0.2s;
    }
    .sidebar a:hover { background: #086b6a; }
    .sidebar a.active { background: #f2f4f7; color: #333; border-radius: 4px; margin: 0 10px; }
    .menu-icon { width: 35px; height: 35px; margin-right: 15px; filter: invert(); object-fit: contain; }
    .active img { filter: invert(0); }

    /* CONTENT STYLES */
    .main-content { flex-grow: 1; padding: 30px; }
    .custom-card {
        border: none; border-radius: 15px; background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); overflow: hidden;
    }
    .custom-card-header {
        background-color: #0c8e8a; 
        color: white; padding: 15px 25px; font-size: 1.1rem;
        display: flex; justify-content: space-between; align-items: center;
    }

    /* BUTTONS */
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
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    .btn-add-admin:hover {
        background-color: #e8f6f6;
        color: #086b6a;
        transform: translateY(-1px);
    }
    
    .table thead { background-color: #e8f6f6; color: #0c8e8a; }
    .action-btn { background: none; border: none; font-size: 1.2rem; transition: 0.2s; padding: 5px 10px; cursor: pointer; }
    
    /* CAMBIO: Estilo para botón de reactivar */
    .btn-activate { color: #198754; } 
    .btn-activate:hover { color: #146c43; transform: scale(1.1); }

    /* MODAL DE CONFIRMACIÓN */
    .confirm-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.45);
        z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(3px);
    }
    .confirm-overlay.active { display: flex; }
    .confirm-box {
        background: #fff; border-radius: 12px; padding: 28px 30px;
        max-width: 420px; width: 90%; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
        text-align: center; animation: popIn 0.2s ease;
    }
    @keyframes popIn { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .confirm-icon { font-size: 2.5em; margin-bottom: 12px; }
    .confirm-actions { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
    .confirm-btn { padding: 9px 22px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
    .confirm-btn-ok { background: #198754; color: white; }
    .confirm-btn-cancel { background: #e9e9e9; color: #555; }

    .form-group-custom { text-align: left; margin-bottom: 15px; }
    .form-group-custom label { display: block; margin-bottom: 5px; color: #555; font-weight: 600; }
    .form-control-custom { 
        width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; outline: none;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="page-wrapper">
    <div class="sidebar">
        <a href="{{ url('home') }}"><img src="{{ asset('img/informe-de-datos.png') }}" class="menu-icon"> Dashboard</a>
        <a href="{{ url('crud') }}"><img src="{{ asset('img/red-mundial.png') }}" class="menu-icon"> Reportes públicos</a>
        <a href="{{ url('reportes') }}"><img src="{{ asset('img/tus reportes.png') }}" class="menu-icon"> Reportes</a>
        <a href="{{ url('moderadores') }}"><img src="{{ asset('img/proteger.png') }}" class="menu-icon"> Moderadores</a>
        <a href="{{ url('leer-usuarios') }}"><img src="{{ asset('img/admin.png') }}" class="menu-icon"> Administradores</a>
        <a href="{{ url('leer-contactos') }}"><img src="{{ asset('img/contacts.png') }}" class="menu-icon"> Contactos</a>
        <a href="{{ url('cuentasbloqueadas') }}" class="active"><img src="{{ asset('img/cuenta-privada.png') }}" class="menu-icon"> Cuentas bloqueadas</a>
        <a href="{{ url('solicitudes') }}"><img src="{{ asset('img/soporte y contacto.png') }}" class="menu-icon"> Soporte</a>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="custom-card">
                <div class="custom-card-header">
                    <strong>Cuentas bloqueadas</strong>
                    <div>
                        <span class="badge bg-light text-danger me-3">{{ count($usuariosBloqueados ?? []) }} Restringidos</span>
                        <button type="button" class="btn-add-admin" onclick="openBlockModal()">
                            <i class="fa-solid fa-user-slash me-2"></i> Bloquear Usuario
                        </button>
                    </div>
                </div>

                <div class="p-4 border-bottom">
                    <p class="text-muted mb-0">Listado de usuarios con acceso restringido. Use la acción de "Activar" para restaurar el acceso.</p>
                </div>

                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="150">UID</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Estado Actual</th>
                                <th class="text-center" width="120">Reactivar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuariosBloqueados ?? [] as $user)
                            <tr>
                                <td class="text-muted"><small>{{ $user['uid'] }}</small></td>
                                <td class="fw-bold">{{ $user['nombre'] ?? 'Sin nombre' }}</td>
                                <td>{{ $user['correo'] ?? 'Sin correo' }}</td>
                                <td>
                                    <span class="badge {{ ($user['estado'] ?? '') == 'bloqueado' ? 'bg-danger' : 'bg-success' }}">
                                        {{ ucfirst($user['estado'] ?? 'Desconocido') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{-- BOTÓN MODIFICADO: Ahora es de Reactivación --}}
                                        <button type="button" class="action-btn btn-activate btn-trigger-activate" 
                                                data-uid="{{ $user['uid'] }}" 
                                                data-name="{{ $user['nombre'] ?? 'este usuario' }}">
                                            <i class="fa-solid fa-user-check"></i>
                                        </button>
                                        
                                        {{-- FORMULARIO: Apunta a la misma ruta pero el controlador la tratará como Update --}}
                                        <form id="form-activate-{{ $user['uid'] }}" 
                                              action="{{ url('eliminar-usuario-bloqueado/'.$user['uid']) }}" 
                                              method="POST" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No hay cuentas bloqueadas actualmente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA BLOQUEAR --}}
<div class="confirm-overlay" id="modal-agregar-cuenta">
    <div class="confirm-box">
        <div class="confirm-icon">🚫</div>
        <h4 class="mb-3">Bloquear Usuario</h4>
        <p class="small text-muted mb-4">Ingrese el UID para cambiar el estado a <b>bloqueado</b>.</p>
        <form action="{{ url('bloquear-usuario-id') }}" method="POST" id="form-bloquear">
            @csrf
            <div class="form-group-custom">
                <label>ID del Usuario (UID)</label>
                <input type="text" name="uid" class="form-control-custom" placeholder="Ingrese UID..." required>
            </div>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn confirm-btn-cancel" onclick="closeBlockModal()">Cancelar</button>
                <button type="submit" class="confirm-btn confirm-btn-ok" style="background: #0c8e8a;">Bloquear ahora</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN DE REACTIVACIÓN --}}
<div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon" id="confirm-icon">🔓</div>
        <h4 id="confirm-title" class="mb-2">Reactivar Cuenta</h4>
        <p id="confirm-message">¿Estás seguro?</p>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="confirm-cancel">Cancelar</button>
            <button class="confirm-btn confirm-btn-ok" id="confirm-ok">Sí, Activar Acceso</button>
        </div>
    </div>
</div>

<script>
    function openBlockModal() {
        document.getElementById("modal-agregar-cuenta").classList.add("active");
    }
    function closeBlockModal() {
        document.getElementById("modal-agregar-cuenta").classList.remove("active");
        document.getElementById("form-bloquear").reset();
    }

    let confirmIsOpen = false;
    function showConfirm({ message = "¿Estás seguro?", icon = "⚠️" } = {}) {
        return new Promise((resolve) => {
            if (confirmIsOpen) return;
            confirmIsOpen = true;
            const overlay = document.getElementById("confirm-overlay");
            document.getElementById("confirm-message").textContent = message;
            document.getElementById("confirm-icon").textContent = icon;
            overlay.classList.add("active");

            const cleanup = (result) => {
                overlay.classList.remove("active");
                confirmIsOpen = false;
                btnOk.removeEventListener("click", onOk);
                btnCancel.removeEventListener("click", onCancel);
                resolve(result);
            };
            const onOk = () => cleanup(true);
            const onCancel = () => cleanup(false);
            const btnOk = document.getElementById("confirm-ok");
            const btnCancel = document.getElementById("confirm-cancel");
            btnOk.addEventListener("click", onOk);
            btnCancel.addEventListener("click", onCancel);
        });
    }

    // Listener para el botón de reactivar
    document.addEventListener("click", async (e) => {
        const btn = e.target.closest(".btn-trigger-activate");
        if (btn) {
            const uid = btn.dataset.uid;
            const name = btn.dataset.name;
            const confirmed = await showConfirm({
                message: `¿Deseas restaurar el acceso para "${name}"? El estado cambiará a Activo.`,
                icon: "👤"
            });
            if (confirmed) {
                document.getElementById("form-activate-" + uid).submit();
            }
        }
    });

    window.onclick = function(event) {
        if (event.target.id == "modal-agregar-cuenta") closeBlockModal();
    }
</script>
@endsection
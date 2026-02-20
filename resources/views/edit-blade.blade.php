@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius: 15px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0A9A9E; color: white;">
                    <strong>Editar Usuario: {{ $user->displayName ?: 'Sin nombre' }}</strong>
                    {{-- Botón de Regresar --}}
                    <a href="{{ url('leer-usuarios') }}" class="btn btn-sm btn-light" style="color: #0A9A9E;">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->uid) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="display_name" class="form-control" value="{{ $user->displayName }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <div class="d-flex justify-content-start gap-2">
                            <button type="submit" class="btn text-white" style="background-color: #0A9A9E;">
                                Actualizar Datos
                            </button>
                            <a href="{{ url('leer-usuarios') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
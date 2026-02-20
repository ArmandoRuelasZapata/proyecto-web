<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'ubicacion',
        'tipo_incidencia', 
        'recomendaciones',
        'detalles_extra',
        'imagen',
        'estatus'
    ];
}
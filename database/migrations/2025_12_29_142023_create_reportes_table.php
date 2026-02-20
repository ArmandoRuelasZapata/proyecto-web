<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('ubicacion');
            $table->string('tipo_incidencia')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->text('detalles_extra')->nullable();
            $table->text('imagen')->nullable();
            $table->enum('estatus', ['atencion', 'revision', 'finalizado'])->default('atencion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImagenColumnTypeInReportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reportes', function (Blueprint $table) {
            // Cambiamos el tipo de 'text' a 'longText' para soportar Base64 pesado
            $table->longText('imagen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportes', function (Blueprint $table) {
            // En caso de volver atrás, regresamos a text (aunque podrías perder datos largos)
            $table->text('imagen')->nullable()->change();
        });
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservacionAdicionalHabitacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observacion_adicional_habitacions', function (Blueprint $table) {
            $table->id();

            /* $table->unsignedBigInteger('propiedad_id'); */
            $table->unsignedBigInteger('propiedad_id');

            $table->string('imagen_evidencia');
            $table->text('observaciones', 800);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('propiedad_id')->references('id')->on('propiedads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observacion_adicional_habitacions');
    }
}

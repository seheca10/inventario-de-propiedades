<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservacionAdicionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observacion_adicionals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inventario_id');

            $table->string('imagen_evidencia');
            $table->text('observaciones', 800);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inventario_id')->references('id')->on('inventarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observacion_adicionals');
    }
}

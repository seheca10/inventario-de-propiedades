<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitacions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('propiedad_id');

            // Puertas y ventanas
            $table->string('puerta');
            $table->string('cerradura');
            $table->string('llaves');
            $table->string('ventana');
            $table->string('vidrio');
            $table->string('rieles');
            $table->string('cortinas');
            $table->string('rejas');

            // Pisos y paredes
            $table->string('pisos');
            $table->string('paredes');
            $table->string('techos');
            //Nuevos campos
            $table->string('aires_acondicionados');
            $table->string('ventiladores');
            $table->string('anjeos');

            // Instalaciones eléctricas
            $table->string('tomacorrientes');
            $table->string('tomas_television');
            $table->string('interruptores');
            $table->string('rosetas');
            $table->string('lamparas');
            $table->string('bombillos');

            // Muebles y armarios
            $table->string('guarda_escobas');
            $table->string('closet');
            $table->string('entrepanos');
            $table->string('puertas');
            $table->string('cajones');

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
        Schema::dropIfExists('habitacions');
    }
}

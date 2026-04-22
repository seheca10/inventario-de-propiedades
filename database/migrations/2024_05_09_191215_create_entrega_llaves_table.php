<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntregaLlavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrega_llaves', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');

            $table->string('tipo_de_propiedad', 20);

            $table->string('fecha');
            $table->string('nombre_asesor');
            $table->string('numero_contrato');
            $table->string('arrendatario');
            $table->string('direccion');
            $table->string('condominio');
            $table->string('bloque');
            $table->string('garaje');
            $table->string('deposito');
            $table->string('patio');
            $table->string('jardin');
            $table->string('metros');
            $table->string('inmueble');
            $table->string('alcobas');
            $table->string('banos');
            $table->boolean('estado')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entrega_llaves');
    }
}

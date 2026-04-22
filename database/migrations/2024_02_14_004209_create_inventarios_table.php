<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');

            $table->string('tipo_de_propiedad', 20);

            $table->string('fecha');
            $table->string('tipo_de_contrato');
            $table->string('numero_contrato');
            $table->string('fmi');

            $table->string('arrendatario');
            $table->string('numero_identificacion_arrendatario');
            $table->string('corre_electronico_arrendatario');
            $table->string('nombre_asesor');

            $table->string('condominio');
            $table->string('direccion');
            
            $table->string('torre');
            $table->string('numero_apartamento');

            $table->string('garaje');
            $table->string('deposito');
            $table->string('patio');
            $table->string('jardin');
            $table->string('metros');
            $table->string('inmueble');
            $table->string('alcobas');
            $table->string('banos');

            $table->string('aires_acondicinados');
            $table->string('controles_aires_acondicinados');
            $table->string('ventiladores');
            $table->string('calentador_de_agua')->nullable();
            $table->string('numero_de_llaves');
            $table->string('numero_de_llaves_depositos');
            $table->string('numero_de_llaves_habitaciones');

            #Medidores
            $table->float('lectura_medidor_luz')->nullable()->default(0.0);
            $table->string('evidencia_lectura_medidor_luz')->nullable();
            $table->float('lectura_medidor_agua')->nullable()->default(0.0);
            $table->string('evidencia_lectura_medidor_agua')->nullable();
            $table->float('lectura_medidor_gas')->nullable()->default(0.0);
            $table->string('evidencia_lectura_medidor_gas')->nullable();

            /* Nuevos campos */
            $table->string('numero_inmueble');
            $table->string('alcoba_de_servicio');
            $table->string('bano_de_servicio');
            $table->string('sala_de_tv');
            $table->string('calentador_de_gas');

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
        Schema::dropIfExists('inventarios');
    }
}

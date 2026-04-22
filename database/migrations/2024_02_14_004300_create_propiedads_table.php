<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropiedadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propiedads', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inventario_id');

            //Entrada principal
            $table->string('puerta_principal', 10);
            $table->string('cerradura_puerta', 10);
            $table->string('otras_puertas', 10);
            $table->string('ventana', 10);
            $table->string('pisos', 10);
            $table->string('paredes', 10);
            $table->string('techos', 10);
            $table->string('tomas_electricas', 10);
            $table->string('interruptores', 10);
            $table->string('lamparas', 10);
            $table->string('timbre', 10);

            //informacion nueva de persianas
            $table->string('persianas', 10);
            $table->string('tipo_material_persianas');

            $table->string('escaleras', 10);
            $table->string('otros', 10);

            //Tomas electricos
            $table->string('citofonos', 10);
            /* $table->string('tomas', 10); */
            $table->string('rosetas', 10);
            /* $table->string('contador_luz', 10); */
            $table->string('caja_de_fusibles', 10);

            //SALA, COMEDOR Y HALL
            $table->string('puerta_hall', 10);
            /* $table->string('cerradura_hall', 10);
            $table->string('llaves_hall', 10); */
            $table->string('ventana_hall', 10);
            $table->string('cortinas_hall', 10);
            $table->string('pisos_hall', 10);
            $table->string('paredes_hall', 10);
            $table->string('techos_hall', 10);
            $table->string('tomas_electricas_hall', 10);
            $table->string('interruptores_hall', 10);
            $table->string('lamparas_hall', 10);
            $table->string('bombillos_hall', 10);
            $table->string('muebles_hall', 10);
            $table->string('ventiladores_de_techo_hall', 10);
            $table->string('anjeos_hall', 10);
            $table->string('aires_acondicionados_hall', 10);
            $table->string('otros_hall', 10);
            //NUEVOS CAMPOS SALA COMERODOR Y HALL
            $table->string('iluminacion_balcon', 10);
            $table->string('vidrio_balcon', 10);
            $table->string('baranda_balcon', 10);
            $table->string('piso_balcon', 10);
            $table->string('paredes_balcon', 10);
            $table->string('detecto_de_humo_hall', 10);

            //COCINA
            $table->string('puerta_cocina', 10);
            /* $table->string('cerradura_cocina', 10);
            $table->string('llaves_cocina', 10); */
            $table->string('ventana_cocina', 10);
            $table->string('cortinas_cocina', 10);
            $table->string('paredes_cocina', 10);
            $table->string('pisos_cocina', 10);
            $table->string('techos_cocina', 10);
            $table->string('tomas_electricas_cocina', 10);
            $table->string('lamparas_cocina', 10);
            $table->string('bombillos_cocina', 10);
            $table->string('interruptores_cocina', 10);
            $table->string('instalacion_gas_cocina', 10);
            $table->string('lavaplatos_cocina', 10);
            $table->string('grifeteria_cocina', 10);
            $table->string('estufa_cocina', 10);
            $table->string('horno_cocina', 10);
            $table->string('meson_cocina', 10);
            $table->string('muebles_cocina', 10);
            $table->string('puertas_muebles_cocina', 10);
            $table->string('cerraduras_muebles_cocina', 10);
            $table->string('llaves_muebles_cocina', 10);
            $table->string('cajones_muebles_cocina', 10);
            $table->string('entrepanos_muebles_cocina', 10);
            $table->string('calentador_cocina', 10);
            $table->string('campana_extractora_cocina', 10);
            $table->string('otros_cocina', 10);

            //ALCOBA PRINCIPAL
            $table->string('puerta_alcoba_principal', 10);
            $table->string('cerradura_alcoba_principal', 10);
            $table->string('llaves_alcoba_principal', 10);
            $table->string('ventana_alcoba_principal', 10);
            $table->string('cortinas_alcoba_principal', 10);
            $table->string('pisos_alcoba_principal', 10);
            $table->string('paredes_alcoba_principal', 10);
            $table->string('techos_alcoba_principal', 10);
            $table->string('tomas_electricas_alcoba_principal', 10);
            $table->string('interruptores_alcoba_principal', 10);
            $table->string('lamparas_alcoba_principal', 10);
            $table->string('bombillos_alcoba_principal', 10);
            $table->string('closet_alcoba_principal', 10);
            $table->string('puertas_alcoba_principal', 10);
            //NUEVOS CAMPOS
            $table->string('ventiladores_alcoba_principal', 10);

            //BAÑO ALCOBA PRINCIPAL
            $table->string('puerta_bano_principal', 10);
            /* $table->string('cerradura_bano_principal', 10);
            $table->string('llaves_bano_principal', 10); */
            $table->string('ventana_bano_principal', 10);
            $table->string('lavamanos_bano_principal', 10);
            $table->string('meson_bano_principal', 10);
            $table->string('mueble_bano_principal', 10);
           /*  $table->string('puertas_bano_principal', 10);
            $table->string('cerraduras_bano_principal', 10);
            $table->string('gabinete_bano_principal', 10);
            $table->string('entrepanos_bano_principal', 10); */
            $table->string('sanitario_bano_principal', 10);
            $table->string('toallero_bano_principal', 10);
            $table->string('jabonera_bano_principal', 10);
            $table->string('cepillero_bano_principal', 10);
            $table->string('espejos_bano_principal', 10);
            $table->string('paredes_bano_principal', 10);
            $table->string('techos_bano_principal', 10);
            $table->string('lamparas_bano_principal', 10);
            $table->string('interruptores_bano_principal', 10);
            $table->string('bombillos_bano_principal', 10);
            $table->string('soporte_papel_higienico_bano_principal', 10);
            /* $table->string('rejillas_desague_bano_principal', 10); */
            $table->string('portavasos_bano_principal', 10);
            $table->string('ducha_bano_principal', 10);
            $table->string('divisiones_bano_principal', 10);
            $table->string('otros_bano_principal', 10);

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
        Schema::dropIfExists('propiedads');
    }
}

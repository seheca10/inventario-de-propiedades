<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanoAdicionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bano_adicionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')->constrained()->cascadeOnDelete();
            $table->foreignId('habitacion_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('nombre')->nullable(); // Ej: "Baño social", "Baño de visitas"

            // Campos de estado
            $table->string('puerta')->nullable();
            $table->string('cerradura')->nullable();
            $table->string('llaves')->nullable();
            $table->string('ventana')->nullable();
            $table->string('lavamanos')->nullable();
            $table->string('meson')->nullable();
            $table->string('mueble')->nullable();
            $table->string('puertas')->nullable();
            $table->string('cerraduras')->nullable();
            $table->string('gabinete')->nullable();
            $table->string('entrepanos')->nullable();
            $table->string('sanitario')->nullable();
            $table->string('toallero')->nullable();
            $table->string('jabonera')->nullable();
            $table->string('cepillero')->nullable();
            $table->string('espejos')->nullable();
            $table->string('paredes')->nullable();
            $table->string('techos')->nullable();
            $table->string('lamparas')->nullable();
            $table->string('interruptores')->nullable();
            $table->string('bombillos')->nullable();
            $table->string('soporte_papel_higienico')->nullable();
            $table->string('rejillas_desague')->nullable();
            $table->string('portavasos')->nullable();
            $table->string('ducha')->nullable();
            $table->string('divisiones')->nullable();

            $table->text('otros')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bano_adicionals');
    }
}

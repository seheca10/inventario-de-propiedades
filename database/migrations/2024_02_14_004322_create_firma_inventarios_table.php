<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmaInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firma_inventarios', function (Blueprint $table) {
            $table->id();
            //Llaves foraneas
            $table->unsignedBigInteger('inventario_id');

            $table->text('firma_arrendatario');
            $table->text('firma_arrendador');

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
        Schema::dropIfExists('firma_inventarios');
    }
}

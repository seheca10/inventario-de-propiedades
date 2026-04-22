<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoPropiedadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_propiedads', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('propiedad_id');

            $table->string('url_video');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('propiedad_id')->references('id')->on('propiedads')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_propiedads');
    }
}

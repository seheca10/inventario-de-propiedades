<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvidenciasToPropiedadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propiedads', function (Blueprint $table) {
            // Guarda las evidencias como JSON:
            // { "entrada.puerta_principal": { "foto": "path/...", "observacion": "texto" } }
            $table->json('evidencias')->nullable()->after('otros_bano_principal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propiedads', function (Blueprint $table) {
            $table->dropColumn('evidencias');
        });
    }
}

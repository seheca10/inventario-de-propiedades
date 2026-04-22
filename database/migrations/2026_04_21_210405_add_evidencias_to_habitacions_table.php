<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvidenciasToHabitacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('habitacions', function (Blueprint $table) {
            // Guarda las evidencias como JSON por campo:
            // { "puerta": { "foto": "evidencias/habitaciones/xxx.jpg", "obs": "texto" } }
            $table->json('evidencias')->nullable()->after('cajones');
 
            // Campo "otros" libre si no existe aún
            if (!Schema::hasColumn('habitacions', 'otros')) {
                $table->string('otros')->nullable()->after('cajones');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('habitacions', function (Blueprint $table) {
            $table->dropColumn(['evidencias']);
            // Descomentar si también quieres revertir 'otros':
            // $table->dropColumn('otros');
        });
    }
}

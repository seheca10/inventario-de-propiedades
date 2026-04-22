<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvidenciasToBanoAdicionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bano_adicionals', function (Blueprint $table) {
            if (!Schema::hasColumn('bano_adicionals', 'evidencias')) {
                $table->json('evidencias')->nullable()->after('otros');
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
        Schema::table('bano_adicionals', function (Blueprint $table) {
            $table->dropColumn('evidencias');
        });
    }
}

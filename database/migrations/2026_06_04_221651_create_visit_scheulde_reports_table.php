<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitScheuldeReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_scheulde_reports', function (Blueprint $table) {
            $table->id();

            /* $table->foreignId('ticket_id')->constrained('pqrs_tickets')->cascadeOnDelete(); */
            $table->foreignId('schedule_id')->constrained('ticket_schedules')->cascadeOnDelete();

            $table->text('report')->nullable();
            $table->string('reported_by')->nullable();
            $table->string('signed_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_scheulde_reports');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqrsReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqrs_reports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pqrs_ticket_id')->constraint('pqrs_tickets')->onDelete('cascade');
            $table->text('contractor_notes')->nullable();
            $table->decimal('final_cost', 10, 2)->nullable();
            $table->text('tenant_feedback')->nullable();
            $table->boolean('tenant_satisfied')->default(false);
            $table->string('completion_file_path')->nullable();

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
        Schema::dropIfExists('pqrs_reports');
    }
}

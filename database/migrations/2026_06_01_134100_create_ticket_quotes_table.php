<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_quotes', function (Blueprint $table) {
            $table->id();

            $table->string('access_token', 64)->nullable()->unique();
            $table->foreignId('ticket_id')->constrained('pqrs_tickets')->onDelete('cascade');
            $table->foreignId('contractor_id')->constrained('contractors');
            
            $table->text('description'); // Detalle de qué se va a hacer
            $table->decimal('labor_cost', 15, 2)->default(0); // Costo mano de obra
            $table->decimal('material_cost', 15, 2)->default(0); // Costo materiales
            $table->decimal('total_amount', 15, 2); // Suma total
            
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable(); // Por si el dueño la rechaza
            
            $table->string('pdf_path')->nullable(); // Por si el contratista sube un documento formal

            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable();

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
        Schema::dropIfExists('ticket_quotes');
    }
}

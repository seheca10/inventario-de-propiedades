<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('pqrs_tickets')
                ->cascadeOnDelete();

            $table->enum('type', ['diagnostic', 'work']);
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            
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
        Schema::dropIfExists('ticket_schedules');
    }
}

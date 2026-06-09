<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqrsTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqrs_tickets', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_number')->unique();
            $table->string('contract_number')->nullable();
            $table->string('tenant_name');
            $table->string('tenant_email');
            $table->string('tenant_phone');

            $table->string('category');
            $table->string('issue_type');
            $table->text('description')->nullable();
            
            $table->enum('status', [
                'created',
                //nuevo
                'cancelled',
                'validated',
                'assigned_pending_accept',
                'assigned',
                'visit_scheduled',
                'visit_scheduled_confirmed',
                'diagnosed',
                'quoted_pending',
                'quoted',
                'approved',
                'rejected',
                'work_scheduled',
                'work_scheduled_confirmed',
                'in_progress',
                'work_reported',
                'finished',
                'closed'
            ])->default('created');

            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');

            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->foreignId('owner_id')->nullable()->constrained('owners');

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
        Schema::dropIfExists('pqrs_tickets');
    }
}

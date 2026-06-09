<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PqrsReport extends Model
{
    use HasFactory;

    protected $table = 'pqrs_reports';

    protected $fillable = [
        'pqrs_ticket_id',
        'contractor_notes',
        'final_cost',
        'tenant_feedback',
        'tenant_satisfied',
        'completion_file_path',
    ];

    /**
     * Convierte nativamente la columna de satisfacción en boolean al consultar.
     */
    protected $casts = [
        'tenant_satisfied' => 'boolean',
        'final_cost' => 'decimal:2',
    ];

    /**
     * Relación inversa con el Ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(PqrsTicket::class, 'pqrs_ticket_id');
    }
}

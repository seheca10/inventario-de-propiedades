<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $table = 'ticket_attachments';

    protected $fillable = [
        'pqrs_ticket_id',
        'original_name',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    /**
     * Relación inversa con el Ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(PqrsTicket::class, 'pqrs_ticket_id');
    }

    /**
     * Atributo accesorio (Accessor) para obtener la URL pública del archivo de forma limpia.
     * Uso en Blade: <img src="{{ $attachment->file_url }}">
     */
    /* public function getFileUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    } */
}

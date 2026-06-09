<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAssignment extends Model
{
    use HasFactory;

    protected $table = 'ticket_assignments';

    protected $fillable = [
        'ticket_id',
        'contractor_id',
        'assigned_at',
        'status',
        'notes',
    ];

    public function ticket()
    {
        return $this->belongsTo(PqrsTicket::class, 'ticket_id');
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    public function isAccepted()
    {
        return in_array($this->status, ['accepted', 'in_progress', 'quoted']);
    }
}

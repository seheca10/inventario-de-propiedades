<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;

    protected $table = 'contractors';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ticketAssignments()
    {
        return $this->hasMany(TicketAssignment::class, 'contractor_id', 'id');
    }

    public function assignedTickets()
    {
        return $this->hasManyThrough(
            PqrsTicket::class,
            TicketAssignment::class,
            'contractor_id', // Foreign key on TicketAssignment
            'id', // Foreign key on PqrsTicket
            'id', // Local key on Contractor
            'ticket_id' // Local key on TicketAssignment
        );
    }

    public function reports()
    {
        return $this->hasManyThrough(
            PqrsReport::class,
            TicketAssignment::class,
            'contractor_id', // Foreign key on TicketAssignment
            'pqrs_ticket_id', // Foreign key on PqrsReport
            'id', // Local key on Contractor
            'ticket_id' // Local key on TicketAssignment
        );
    }
}

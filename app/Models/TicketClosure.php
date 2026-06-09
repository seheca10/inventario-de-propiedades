<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketClosure extends Model
{
    use HasFactory;

    protected $table = 'ticket_closures';

    protected $fillable = [
        'ticket_id',
        'final_cost',
        'summary',
    ];

    protected $casts = [
        'final_cost' => 'decimal:2',
    ];

    public function ticket()
    {
        return $this->belongsTo(PqrsTicket::class, 'ticket_id', 'id');
    }
}

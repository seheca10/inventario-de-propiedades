<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketRating extends Model
{
    use HasFactory;

    protected $table = 'ticket_ratings';

    protected $fillable = [
        'ticket_id',
        'rating',
        'comment',
    ];

    public function ticket()
    {
        return $this->belongsTo(PqrsTicket::class, 'ticket_id', 'id');
    }

    public function getRatingLabelAttribute(): string
    {
        return match(true) {
            $this->rating >= 5 => 'Excelente',
            $this->rating >= 4 => 'Bueno',
            $this->rating >= 3 => 'Regular',
            $this->rating >= 2 => 'Malo',
            default            => 'Muy malo',
        };
    }
}

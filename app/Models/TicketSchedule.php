<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'type',
        'scheduled_at',
        'confirmed_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(PqrsTicket::class, 'ticket_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(VisitScheuldeReport::class, 'schedule_id', 'id');
    }

    protected $casts = [
        'scheduled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'type' => 'string',
    ];

    //Accesor para saber si la cita ha sido confirmada
    public function getIsConfirmedAttribute(): bool
    {
        return $this->confirmed_at !== null;
    }

    //Accesor para conocer los estados de la cita
    //Obtener el estado legible del ticket
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'diagnostic' => 'Diagnostico',
            'work' => 'Realización de trabajo',
            default => 'Desconocido',
        };
    }

    public function getCanRegisterVisitAttribute(): bool
    {
        // Hay fecha confirmada, es hoy, y aún no se ha registrado el reporte
        return $this->confirmed_at
            && $this->confirmed_at->isToday()
            && $this->reports->isEmpty();  // sin reporte = aún no se registró
    }
}

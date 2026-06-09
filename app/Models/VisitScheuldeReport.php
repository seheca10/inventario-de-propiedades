<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VisitScheuldeReport extends Model
{
    use HasFactory;

    protected $table = 'visit_scheulde_reports';
    
    protected $fillable = [
        'schedule_id',
        'report',
        'reported_by',
        'signed_by',
    ];

    public function schedule()
    {
        return $this->belongsTo(TicketSchedule::class, 'schedule_id', 'id');
    }

    // Accessor para obtener la URL pública de la firma
    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signed_by
            ? asset('storage/' . $this->signed_by)
            : null;
    }

    // 🔑 Accesor para saber si debe mostrar el botón
    public function getShowButtonAttribute(): bool
    {
        return $this->schedule
            && $this->schedule->confirmed_at
            && $this->schedule->confirmed_at->isToday()
            && empty($this->signed_by);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $table = 'owners';

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

    /**
     * Tickets asociados a este propietario.
     * Un propietario puede tener múltiples propiedades/contratos con tickets.
     */
    public function tickets()
    {
        return $this->hasMany(PqrsTicket::class, 'owner_id');
    }

    /**
     * Formato limpio del teléfono con indicativo colombiano.
     * Usado por WhatsAppService para construir el enlace wa.me.
     */
    public function getPhoneCleanAttribute(): string
    {
        $clean = preg_replace('/[^0-9]/', '', $this->phone ?? '');
        return str_starts_with($clean, '57') ? $clean : '57' . $clean;
    }
}

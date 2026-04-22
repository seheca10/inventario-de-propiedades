<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Habitacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'habitacions';

    protected $fillable = [
        'propiedad_id',
        'puerta',
        'cerradura',
        'llaves',
        'ventana',
        'vidrio',
        'rieles',
        'cortinas',
        'rejas',
        'pisos',
        'paredes',
        'techos',
        'aires_acondicionados',
        'ventiladores',
        'anjeos',
        'tomacorrientes',
        'tomas_television',
        'interruptores',
        'rosetas',
        'lamparas',
        'bombillos',
        'guarda_escobas',
        'closet',
        'entrepanos',
        'puertas',
        'cajones',        
        //Evidencias
        'evidencias',
    ];

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id', 'id');
    }

    public function observaciones()
    {
        return $this->hasMany(ObservacionAdicionalHabitacion::class);
    }

    protected $casts = [
        'evidencias' => 'array',
    ];
}

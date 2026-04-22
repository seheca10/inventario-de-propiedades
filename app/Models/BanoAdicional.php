<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BanoAdicional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bano_adicionals';

    protected $fillable = [
        'propiedad_id',
        'habitacion_id',
        'nombre',
        'puerta',
        'cerradura',
        'llaves',
        'ventana',
        'lavamanos',
        'meson',
        'mueble',
        'puertas',
        'cerraduras',
        'gabinete',
        'entrepanos',
        'sanitario',
        'toallero',
        'jabonera',
        'cepillero',
        'espejos',
        'paredes',
        'techos',
        'lamparas',
        'interruptores',
        'bombillos',
        'soporte_papel_higienico',
        'rejillas_desague',
        'portavasos',
        'ducha',
        'divisiones',
        'otros',
        //Evidencias
        'evidencias',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    protected $casts = [
        'evidencias' => 'array',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObservacionAdicionalHabitacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'observacion_adicional_habitacions';

    protected $fillable = [
        'propiedad_id',
        'imagen_evidencia',
        'observaciones'
    ];

    public function propiedad() {
        return $this->belongsTo(Propiedad::class);
    }
}

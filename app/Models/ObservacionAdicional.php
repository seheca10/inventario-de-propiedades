<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObservacionAdicional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'observacion_adicionals';

    protected $fillable = [
        'inventario_id',
        'imagen_evidencia',
        'observaciones'
    ];

    public function inventario() {
        return $this->belongsTo(Inventario::class, 'inventario_id', 'id');
    }
}

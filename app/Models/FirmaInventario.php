<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirmaInventario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "firma_inventarios";

    protected $fillable = [
        'inventario_id',
        'firma_arrendatario',
        'firma_arrendador'
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id', 'id');
    }
}

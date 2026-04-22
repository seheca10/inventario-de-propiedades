<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casa extends Model
{
    use HasFactory;

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id', 'id');
    }
}

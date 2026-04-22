<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntregaLlaves extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entrega_llaves';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        ''
    ];
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventarios';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'tipo_de_propiedad',
        'fecha',
        'condominio',
        'numero_contrato',
        'arrendatario',
        'nombre_asesor',
        'direccion',
        'garaje',
        'deposito',
        'inmueble',
        'alcobas',
        'banos',
        'patio',
        'jardin',
        'metros',
        'estado',
        'tipo_de_contrato',
        'fmi',
        'numero_identificacion_arrendatario',
        'corre_electronico_arrendatario',
        'torre',
        'numero_apartamento',
        'aires_acondicinados',
        'controles_aires_acondicinados',
        'ventiladores',
        'calentador_de_agua',
        'numero_de_llaves',
        'numero_de_llaves_depositos',
        'numero_de_llaves_habitaciones',
        'lectura_medidor_luz',
        'evidencia_lectura_medidor_luz',
        'lectura_medidor_agua',
        'evidencia_lectura_medidor_agua',
        'lectura_medidor_gas',
        'evidencia_lectura_medidor_gas',
        'numero_inmueble',
        'alcoba_de_servicio',
        'bano_de_servicio',
        'sala_de_tv',
        'calentador_de_gas',
    ];

    public function agenteInmobiliario()
    {
        return $this->belongsTo(AgenteInmobiliario::class);
    }

    public function firma()
    {
        return $this->hasOne(FirmaInventario::class);
    }

    public function propiedad()
    {
        return $this->hasOne(Propiedad::class, 'inventario_id', 'id');
    }

    public function observaciones()
    {
        return $this->hasMany(ObservacionAdicional::class);
    }

    public function getEstado()
    {
        if (!$this->propiedad) {
            return 'pendiente_propiedad';
        }
        if (!$this->propiedad->habitaciones) {
            return 'pendiente_habitaciones';
        }
        if (!$this->firma) {
            return 'pendiente_firma';
        }
        return 'firmado';
    }

    public function condominio() {
        return $this->belongsTo(Condominio::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPropiedad extends Model
{
    use HasFactory, SoftDeletes;
   
    protected $fillable = [
        'inventario_id',
        'tipo_de_propiedad',
        'puerta_principal',
        'cerradura_puerta',
        'otras_puertas',
        'ventana',
        'pisos',
        'paredes',
        'techos',
        'tomas_electricas',
        'interruptores',
        'lamparas',
        'cortinas',
        'escaleras',
        'otros',
        //Tomas electricos
        'citofonos',
        'tomas',
        'rosetas',
        'contador_luz',
        'caja_de_fusibles',
        //SALA, COMEDOR Y HALL
        'puerta_hall',
        'cerradura_hall',
        'llaves_hall',
        'ventana_hall',
        'cortinas_hall',
        'pisos_hall',
        'paredes_hall',
        'techos_hall',
        'tomas_electricas_hall',
        'interruptores_hall',
        'lamparas_hall',
        'bombillos_hall',
        'muebles_hall',
        'entrepanos_hall',
        'cajones_hall',
        'otros_hall',
        //ALCOBA PRINCIPAL
        'puerta_alcoba_principal',
        'cerradura_alcoba_principal',
        'llaves_alcoba_principal',
        'ventana_alcoba_principal',
        'cortinas_alcoba_principal',
        'pisos_alcoba_principal',
        'paredes_alcoba_principal',
        'techos_alcoba_principal',
        'tomas_electricas_alcoba_principal',
        'interruptores_alcoba_principal',
        'lamparas_alcoba_principal',
        'bombillos_alcoba_principal',
        'closet_alcoba_principal',
        'puertas_alcoba_principal',
        //BAÑO ALCOBA PRINCIPAL
        'puerta_bano_principal',
        'cerradura_bano_principal',
        'llaves_bano_principal',
        'ventana_bano_principal',
        'lavamanos_bano_principal',
        'meson_bano_principal',
        'mueble_bano_principal',
        'puertas_bano_principal',
        'cerraduras_bano_principal',
        'gabinete_bano_principal',
        'entrpanos_bano_principal',
        'sanitario_bano_principal',
        'toallero_bano_principal',
        'jabonera_bano_principal',
        'cepillero_bano_principal',
        'espejos_bano_principal',
        'paredes_bano_principal',
        'techos_bano_principal',
        'lamparas_bano_principal',
        'interruptores_bano_principal',
        'bombillos_bano_principal',
        'soporte_papel_higienico_bano_principal',
        'rejillas_desague_bano_principal',
        'portavasos_bano_principal',
        'ducha_bano_principal',
        'divisiones_bano_principal',
        'otros_bano_principal',
    ];

    public function inventario() 
    {
        return $this->belongsTo(Inventario::class);
    }
    
}

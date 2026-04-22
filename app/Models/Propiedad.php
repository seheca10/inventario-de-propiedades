<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propiedad extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $table = 'propiedads';
   
    protected $fillable = [
        'inventario_id',

        //Entrada principal
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
        'escaleras',
        'citofonos',
        'tomas',
        'rosetas',
        'contador_luz',
        'caja_de_fusibles',
        'otros',
        'persianas',
        'tipo_material_persianas',
        'timbre',
        'detecto_de_humo_hall',

        //SALA, COMEDOR Y HALL
        'puerta_hall',
        'cerradura_hall',
        'llaves_hall',
        'ventana_hall',
        'pisos_hall',
        'paredes_hall',
        'techos_hall',
        'tomas_electricas_hall',
        'interruptores_hall',
        'lamparas_hall',
        'bombillos_hall',
        'muebles_hall',
        'otros_hall',
        'ventiladores_de_techo_hall',
        'anjeos_hall',
        'aires_acondicionados_hall',
        'cortinas_hall',
        // Nuevos campos balcón
        'iluminacion_balcon',
        'vidrio_balcon',
        'baranda_balcon',
        'piso_balcon',
        'paredes_balcon',

        //COCINA
        'puerta_cocina',
        /* 'cerradura_cocina',
        'llaves_cocina', */
        'ventana_cocina',
        'cortinas_cocina',
        'paredes_cocina',
        'pisos_cocina',
        'techos_cocina',
        'tomas_electricas_cocina',
        'lamparas_cocina',
        'bombillos_cocina',
        'interruptores_cocina',
        'instalacion_gas_cocina',
        'lavaplatos_cocina',
        'grifeteria_cocina',
        'estufa_cocina',
        'horno_cocina',
        'meson_cocina',
        'muebles_cocina',
        'puertas_muebles_cocina',
        'cerraduras_muebles_cocina',
        'llaves_muebles_cocina',
        'cajones_muebles_cocina',
        'entrepanos_muebles_cocina',
        'calentador_cocina',
        'campana_extractora_cocina',
        'otros_cocina',

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
        'ventiladores_alcoba_principal',

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
        'entrepanos_bano_principal',
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
        //Evidencias
        'evidencias',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id', 'id');
    }

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class);
    }

    public function banosAdicionales()
    {
        return $this->hasMany(BanoAdicional::class);
    }

    public function observaciones()
    {
        return $this->hasMany(ObservacionAdicional::class, 'id');
    }

    public function observacionesHabitaciones()
    {
        return $this->hasMany(ObservacionAdicionalHabitacion::class);
    }

    public function getCamposMalos()
    {
        $camposMalos = [];

        foreach ($this->fillable as $campo) {
            if ($this->$campo === 'Malo' || $this->$campo === 'Regular') {
                $camposMalos[] = [
                    'campo' => $campo,
                    'valor' => $this->$campo,
                ];
            }
        }

        return $camposMalos;
    }

    protected $casts = [
        'inventario_id' => 'integer',

        // ENTRADA
        'puerta_principal' => 'string',
        'cerradura_puerta' => 'string',
        'otras_puertas' => 'string',
        'ventana' => 'string',
        'pisos' => 'string',
        'paredes' => 'string',
        'techos' => 'string',
        'tomas_electricas' => 'string',
        'interruptores' => 'string',
        'lamparas' => 'string',
        'persianas' => 'string',
        'tipo_material_persianas' => 'string',
        'escaleras' => 'string',
        'otros' => 'string',
        'citofonos' => 'string',
        'tomas' => 'string',
        'rosetas' => 'string',
        'contador_luz' => 'string',
        'caja_de_fusibles' => 'string',

        // HALL
        'puerta_hall' => 'string',
        'cerradura_hall' => 'string',
        'llaves_hall' => 'string',
        'ventana_hall' => 'string',
        'cortinas_hall' => 'string',
        'pisos_hall' => 'string',
        'paredes_hall' => 'string',
        'techos_hall' => 'string',
        'tomas_electricas_hall' => 'string',
        'interruptores_hall' => 'string',
        'lamparas_hall' => 'string',
        'bombillos_hall' => 'string',
        'muebles_hall' => 'string',
        'ventiladores_de_techo_hall' => 'string',
        'anjeos_hall' => 'string',
        'aires_acondicionados_hall' => 'string',
        'otros_hall' => 'string',

        // COCINA
        'puerta_cocina' => 'string',
        'cerradura_cocina' => 'string',
        'llaves_cocina' => 'string',
        'ventana_cocina' => 'string',
        'cortinas_cocina' => 'string',
        'paredes_cocina' => 'string',
        'pisos_cocina' => 'string',
        'techos_cocina' => 'string',
        'tomas_electricas_cocina' => 'string',
        'lamparas_cocina' => 'string',
        'bombillos_cocina' => 'string',
        'interruptores_cocina' => 'string',
        'instalacion_gas_cocina' => 'string',
        'lavaplatos_cocina' => 'string',
        'grifeteria_cocina' => 'string',
        'estufa_cocina' => 'string',
        'horno_cocina' => 'string',
        'meson_cocina' => 'string',
        'muebles_cocina' => 'string',
        'puertas_muebles_cocina' => 'string',
        'cerraduras_muebles_cocina' => 'string',
        'llaves_muebles_cocina' => 'string',
        'cajones_muebles_cocina' => 'string',
        'entrepanos_muebles_cocina' => 'string',
        'calentador_cocina' => 'string',
        'campana_extractora_cocina' => 'string',
        'otros_cocina' => 'string',

        // ALCOBA PRINCIPAL
        'puerta_alcoba_principal' => 'string',
        'cerradura_alcoba_principal' => 'string',
        'llaves_alcoba_principal' => 'string',
        'ventana_alcoba_principal' => 'string',
        'cortinas_alcoba_principal' => 'string',
        'pisos_alcoba_principal' => 'string',
        'paredes_alcoba_principal' => 'string',
        'techos_alcoba_principal' => 'string',
        'tomas_electricas_alcoba_principal' => 'string',
        'interruptores_alcoba_principal' => 'string',
        'lamparas_alcoba_principal' => 'string',
        'bombillos_alcoba_principal' => 'string',
        'closet_alcoba_principal' => 'string',
        'puertas_alcoba_principal' => 'string',

        // BAÑO PRINCIPAL
        'puerta_bano_principal' => 'string',
        'cerradura_bano_principal' => 'string',
        'llaves_bano_principal' => 'string',
        'ventana_bano_principal' => 'string',
        'lavamanos_bano_principal' => 'string',
        'meson_bano_principal' => 'string',
        'mueble_bano_principal' => 'string',
        'puertas_bano_principal' => 'string',
        'cerraduras_bano_principal' => 'string',
        'gabinete_bano_principal' => 'string',
        'entrepanos_bano_principal' => 'string',
        'sanitario_bano_principal' => 'string',
        'toallero_bano_principal' => 'string',
        'jabonera_bano_principal' => 'string',
        'cepillero_bano_principal' => 'string',
        'espejos_bano_principal' => 'string',
        'paredes_bano_principal' => 'string',
        'techos_bano_principal' => 'string',
        'lamparas_bano_principal' => 'string',
        'interruptores_bano_principal' => 'string',
        'bombillos_bano_principal' => 'string',
        'soporte_papel_higienico_bano_principal' => 'string',
        'rejillas_desague_bano_principal' => 'string',
        'portavasos_bano_principal' => 'string',
        'ducha_bano_principal' => 'string',
        'divisiones_bano_principal' => 'string',
        'otros_bano_principal' => 'string',
        'evidencias' => 'array',
    ];

}
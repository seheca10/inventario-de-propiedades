<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Http\Livewire\HabitacionForm;
use App\Models\Inventario;
use App\Models\Condominio;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class MultiStepForm extends Component
{
    use WithFileUploads;

    //Campos inventario
    public $user_id;
    public $fecha;
    public $numero_contrato;
    public $tipo_de_contrato;
    public $fmi;
    public $tipo_de_propiedad;

    public $arrendatario;
    public $numero_identificacion_arrendatario;
    public $corre_electronico_arrendatario;
    public $nombre_asesor;

    public $condominio;
    public $direccion;
    public $bloque;
    public $torre;
    public $numero_apartamento;

    public $garaje;
    public $deposito;
    public $patio;  
    public $jardin;  
    public $metros;
    public $inmueble;
    public $alcobas;
    public $banos;

    public $aires_acondicinados;
    public $controles_aires_acondicinados;
    public $ventiladores;
    public $calentador_de_agua;
    public $numero_de_llaves;
    public $numero_de_llaves_depositos;
    public $numero_de_llaves_habitaciones;
    public $lectura_medidor_luz;
    public $evidencia_lectura_medidor_luz;
    public $lectura_medidor_agua;
    public $evidencia_lectura_medidor_agua;
    public $lectura_medidor_gas;
    public $evidencia_lectura_medidor_gas;

    public $numero_inmueble;
    public $alcoba_de_servicio;
    public $bano_de_servicio;
    public $sala_de_tv;
    public $calentador_de_gas;

    public $arrendador;

    public $fotico;

    public function mount()
    {
        $this->fecha = now()->toDayDateTimeString();
        $this->user_id = auth()->user()->id;
        $this->nombre_asesor = auth()->user()->name;
    }

    protected $rules = [
        'fecha'=> 'required',         
        'condominio'=> 'required',
        'numero_contrato'=> 'required',  
        'arrendatario'=> 'required',
        'nombre_asesor'=> 'required',
        'direccion' => 'required',
        'bloque'=> 'required',
        'garaje' => 'required',
        'deposito' => 'required',
        'inmueble'=> 'required',
        'alcobas' => 'required|integer',
        'banos' => 'required|integer',
        'patio' => 'required',
        'jardin' => 'required',
        'metros' => 'required|integer', 
        //Nuevos campos
        'tipo_de_contrato' => 'required',
        'numero_identificacion_arrendatario' => 'required',
        'corre_electronico_arrendatario' => 'required|email',
        'fmi' => 'required',
        'torre' => 'required',
        'numero_apartamento' => 'required',
        'aires_acondicinados' => 'required',
        'controles_aires_acondicinados' => 'required',
        'ventiladores' => 'required',
        'calentador_de_agua' => 'required',
        'numero_de_llaves' => 'required|integer',
        'numero_de_llaves_depositos' => 'required|integer',
        'numero_de_llaves_habitaciones' => 'required|integer',
        'numero_inmueble' => 'required',
        'alcoba_de_servicio' => 'required',
        'bano_de_servicio' => 'required',
        'sala_de_tv' => 'required',
        'calentador_de_gas' => 'required',
        /* Campos no obligatorios */
        'lectura_medidor_luz' => 'integer',
        'evidencia_lectura_medidor_luz' => 'image|max:1024',
        'lectura_medidor_agua' => 'integer',
        'evidencia_lectura_medidor_agua' => 'image|max:1024',
        'lectura_medidor_gas' => 'integer',
        'evidencia_lectura_medidor_gas' => 'image|max:1024',
     ];

    public function resetFields(){
        $this->fecha = '';
        $this->condominio = '';
        $this->bloque = '';
        $this->numero_apartamento = '';
        $this->garaje = '';
        $this->deposito = '';
        $this->arrendatario = '';
        $this->arrendador = '';
        $this->alcobas = '';
        $this->banos = '';
        $this->patio = '';
        $this->jardin = '';
        $this->metros = '';
        $this->tipo_de_propiedad = '';
        //Nuevos campos
        $this->tipo_de_contrato = '';
        $this->numero_identificacion_arrendatario = '';
        $this->corre_electronico_arrendatario = '';
        $this->fmi = '';
        $this->torre = '';
        $this->numero_apartamento = '';
        $this->aires_acondicinados = '';
        $this->controles_aires_acondicinados = '';
        $this->ventiladores = '';
        $this->calentador_de_agua = '';
        $this->numero_de_llaves = '';
        $this->numero_de_llaves_depositos = '';
        $this->numero_de_llaves_habitaciones = '';
        $this->lectura_medidor_luz = '';
        $this->evidencia_lectura_medidor_luz = '';
        $this->lectura_medidor_agua = '';
        $this->evidencia_lectura_medidor_agua = '';
        $this->lectura_medidor_gas = '';
        $this->evidencia_lectura_medidor_gas = '';
    }

    public function render()
    {
        $fecha = "";   
        $nombre_asesor = "";   
        $condominios = Condominio::all();
        
        return view('livewire.multi-step-form', compact('condominios'));
    }
    
    public function submit() {

        dd($this->fotico);

        /* dd($this->validate());

        $inventario = Inventario::create([
            'user_id' => $this->user_id,
            'tipo_de_propiedad'=> $this->tipo_de_propiedad,
            'fecha' => $this->fecha,
            'nombre_asesor' => $this->nombre_asesor,
            'numero_contrato' => $this->numero_contrato,
            'arrendatario' => $this->arrendatario,
            'direccion' => $this->direccion,
            'condominio' => $this->condominio,
            'bloque' => $this->bloque,
            'inmueble' => $this->inmueble,
            'garaje' => $this->garaje,
            'deposito' => $this->deposito,
            'patio' => $this->patio,
            'jardin' => $this->jardin,
            'metros' => $this->metros,
            'alcobas' => $this->alcobas,
            'banos' => $this->banos,
            'tipo_de_contrato' => $this->tipo_de_contrato,
            'numero_identificacion_arrendatario' => $this->numero_identificacion_arrendatario,
            'corre_electronico_arrendatario' => $this->corre_electronico_arrendatario,
            'fmi' => $this->fmi,
            'torre' => $this->torre,
            'numero_apartamento' => $this->numero_apartamento,
            'aires_acondicinados' => $this->aires_acondicinados,
            'controles_aires_acondicinados' => $this->controles_aires_acondicinados,
            'ventiladores' => $this->ventiladores,
            'calentador_de_agua' => $this->calentador_de_agua,
            'numero_de_llaves' => $this->numero_de_llaves,
            'numero_de_llaves_depositos' => $this->numero_de_llaves_depositos,
            'numero_de_llaves_habitaciones' => $this->numero_de_llaves_habitaciones,
            'lectura_medidor_luz' => $this->lectura_medidor_luz,
            'evidencia_lectura_medidor_luz' => $this->evidencia_lectura_medidor_luz,
            'lectura_medidor_agua' => $this->lectura_medidor_agua,
            'evidencia_lectura_medidor_agua' => $this->evidencia_lectura_medidor_agua,
            'lectura_medidor_gas' => $this->lectura_medidor_gas,
            'evidencia_lectura_medidor_gas' => $this->evidencia_lectura_medidor_gas,
        ]); */

        /* if ($this->fotico) {
            // Realizar algún proceso con el archivo
            dd($this->fotico->temporaryUrl());
        } else {
            // Manejar el caso en que no hay archivo cargado
            dd('No file uploaded');
        } */

        $validatedData = $this->validate();

        // Manejar archivos
        if ($this->evidencia_lectura_medidor_luz) {
            $validatedData['evidencia_lectura_medidor_luz'] = $this->evidencia_lectura_medidor_luz->store('evidencias');
        }
        if ($this->evidencia_lectura_medidor_agua) {
            $validatedData['evidencia_lectura_medidor_agua'] = $this->evidencia_lectura_medidor_agua->store('evidencias');
        }
        if ($this->evidencia_lectura_medidor_gas) {
            $validatedData['evidencia_lectura_medidor_gas'] = $this->evidencia_lectura_medidor_gas->store('evidencias');
        }

        $inventario = Inventario::create($validatedData);

        return redirect()->route('informacionPropiedad', [$inventario]);
    }
}

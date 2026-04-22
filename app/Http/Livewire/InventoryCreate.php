<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Inventario;
use App\Models\Condominio;

class InventoryCreate extends Component
{
    use WithFileUploads;

    // ─── Información inicial ──────────────────────────────────────────────────
    public $user_id;
    public string $fecha           = '';
    public string $nombre_asesor   = '';
    public string $tipo_de_propiedad = 'Residencial';
    public string $numero_contrato = '';
    public string $tipo_de_contrato = '';
    public string $fmi             = '';

    // ─── Arrendatario ─────────────────────────────────────────────────────────
    public string $arrendatario                      = '';
    public string $numero_identificacion_arrendatario = '';
    public string $corre_electronico_arrendatario    = '';

    // ─── Ubicación ────────────────────────────────────────────────────────────
    public $condominio;
    public string $direccion          = 'Anillo Vial Km 12 / Anillo vial Km 8';
    public string $torre              = '';
    public string $numero_apartamento = '';
    public string $numero_inmueble    = '';
    public string $inmueble           = '';

    // ─── Equipos y amenities ──────────────────────────────────────────────────
    public string $garaje               = 'Si';
    public string $deposito             = '';
    public string $patio                = 'No';
    public string $jardin               = 'No';
    public string $metros               = '';
    public string $alcoba_de_servicio   = 'No';
    public string $bano_de_servicio     = 'No';
    public string $sala_de_tv           = 'No';
    public string $calentador_de_agua   = 'No';
    public string $calentador_de_gas    = 'No';
    public $alcobas;
    public $banos;
    public $aires_acondicinados;
    public $controles_aires_acondicinados;
    public $ventiladores;
    public $numero_de_llaves;
    public $numero_de_llaves_depositos;
    public $numero_de_llaves_habitaciones;

    // ─── Medidores ────────────────────────────────────────────────────────────
    public $lectura_medidor_luz;
    public $evidencia_lectura_medidor_luz;
    public $lectura_medidor_agua;
    public $evidencia_lectura_medidor_agua;
    public $lectura_medidor_gas;
    public $evidencia_lectura_medidor_gas;

    // ─── Lista condominios ────────────────────────────────────────────────────
    public $condominios = [];

    protected $listeners = ['condominioCreated' => 'updateCondominios'];

    // ─── Reglas ───────────────────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'fecha'                              => 'required',
            'tipo_de_propiedad'                  => 'required',
            'numero_contrato'                    => 'required',
            'tipo_de_contrato'                   => 'required',
            'fmi'                                => 'required',
            'arrendatario'                       => 'required',
            'numero_identificacion_arrendatario' => 'required',
            'corre_electronico_arrendatario'     => 'required|email',
            'condominio'                         => 'required',
            'direccion'                          => 'required',
            'numero_inmueble'                    => 'required',
            'torre'                              => 'required',
            'numero_apartamento'                 => 'required',
            'garaje'                             => 'required',
            'deposito'                           => 'nullable',
            'metros'                             => 'required|numeric',
            'inmueble'                           => 'required',
            'alcobas'                            => 'required|integer|min:0',
            'banos'                              => 'required|integer|min:0',
            'patio'                              => 'required',
            'jardin'                             => 'required',
            'aires_acondicinados'                => 'required|integer|min:0',
            'controles_aires_acondicinados'      => 'required|integer|min:0',
            'ventiladores'                       => 'required|integer|min:0',
            'calentador_de_agua'                 => 'required',
            'calentador_de_gas'                  => 'required',
            'numero_de_llaves'                   => 'required|integer|min:0',
            'numero_de_llaves_depositos'         => 'required|integer|min:0',
            'numero_de_llaves_habitaciones'      => 'required|integer|min:0',
            'alcoba_de_servicio'                 => 'required',
            'bano_de_servicio'                   => 'required',
            'sala_de_tv'                         => 'required',
            'lectura_medidor_luz'                => 'nullable|numeric',
            'evidencia_lectura_medidor_luz'      => 'nullable|image|max:2048',
            'lectura_medidor_agua'               => 'nullable|numeric',
            'evidencia_lectura_medidor_agua'     => 'nullable|image|max:2048',
            'lectura_medidor_gas'                => 'nullable|numeric',
            'evidencia_lectura_medidor_gas'      => 'nullable|image|max:2048',
        ];
    }

    // ─── Ciclo de vida ────────────────────────────────────────────────────────
    public function mount(): void
    {
        $this->user_id      = auth()->id();
        $this->nombre_asesor = auth()->user()->name;
        $this->fecha        = now()->format('Y-m-d H:i');
        $this->loadCondominios();
    }

    // Validación en tiempo real campo a campo
    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    // ─── Condominios ──────────────────────────────────────────────────────────
    public function loadCondominios(): void
    {
        $this->condominios = Condominio::orderBy('nombre')->get();
    }

    public function updateCondominios(int $id): void
    {
        $this->loadCondominios();
        $this->condominio = $id;
        $this->dispatchBrowserEvent('close-modal-condominio');
    }

    // ─── Helpers: almacenar evidencias de medidores ───────────────────────────
    private function storeEvidencias(array &$data): void
    {
        foreach (['luz', 'agua', 'gas'] as $tipo) {
            $key = "evidencia_lectura_medidor_{$tipo}";
            if ($this->$key) {
                $data[$key] = $this->$key->store('evidencias', 'public');
            }
        }
    }

    // ─── Submit ───────────────────────────────────────────────────────────────
    public function submit(): void
    {
        $data = $this->validate();

        $this->storeEvidencias($data);

        $inventario = Inventario::create(array_merge($data, [
            'user_id'          => auth()->id(),
            'nombre_asesor'    => auth()->user()->name,
            'tipo_de_propiedad'=> $this->tipo_de_propiedad,
            'estado'           => 1,
        ]));

        redirect()->route('informacionPropiedad', [$inventario]);
    }

    // ─── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.inventory-create');
    }
}
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Propiedad;
use Illuminate\Support\Arr;

class FormStepPropiedad extends Component
{
    use WithFileUploads;

    // ─── Estado del formulario ────────────────────────────────────────────────
    public int $currentStep = 1;
    public $inventario;
    public bool $showEvidenciaModal = false;

    // ─── Evidencias: campo → ['foto' => path, 'observacion' => texto] ─────────
    public array $evidencias = [];

    // Estado temporal del modal de evidencia
    public ?string $evidenciaCampoActual = null;
    public $evidenciaFotoTemp = null;
    public string $evidenciaObservacionTemp = '';

    // ─── Datos de cada sección ────────────────────────────────────────────────
    public array $entrada = [];
    public array $hall = [];
    public array $cocina = [];
    public array $alcoba_principal = [];
    public array $bano_principal = [];

    // ─── Configuración declarativa de los pasos ───────────────────────────────
    // Agrega o quita pasos aquí sin tocar nada más
    protected function stepsConfig(): array
    {
        return [
            1 => ['titulo' => 'Entrada Principal',         'seccion' => 'entrada',          'otros_key' => 'otros'],
            2 => ['titulo' => 'Sala, Comedor y Hall',      'seccion' => 'hall',             'otros_key' => 'otros_hall'],
            3 => ['titulo' => 'Cocina',                    'seccion' => 'cocina',           'otros_key' => 'otros_cocina'],
            4 => ['titulo' => 'Alcoba Principal',          'seccion' => 'alcoba_principal', 'otros_key' => null],
            5 => ['titulo' => 'Baño Alcoba Principal',     'seccion' => 'bano_principal',   'otros_key' => 'otros_bano_principal'],
        ];
    }

    public function getTotalStepsProperty(): int
    {
        return count($this->stepsConfig());
    }

    public function getCurrentStepConfigProperty(): array
    {
        return $this->stepsConfig()[$this->currentStep];
    }

    public function getProgressPercentageProperty(): int
    {
        return (int) round(($this->currentStep / $this->totalSteps) * 100);
    }

    // ─── Montaje ──────────────────────────────────────────────────────────────
    public function mount($inventario)
    {
        $this->inventario = $inventario->id;
        $this->initSecciones();
    }

    private function initSecciones(): void
    {
        $this->entrada = [
            'puerta_principal'        => 'Bueno',
            'cerradura_puerta'        => 'Bueno',
            'otras_puertas'           => 'N/A',
            'ventana'                 => 'Bueno',
            'pisos'                   => 'Bueno',
            'paredes'                 => 'Bueno',
            'techos'                  => 'Bueno',
            'tomas_electricas'        => 'Bueno',
            'interruptores'           => 'Bueno',
            'lamparas'                => 'Bueno',
            'timbre'                  => 'Bueno',
            'persianas'               => 'Bueno',
            'tipo_material_persianas' => 'Bueno',
            'escaleras'               => 'Bueno',
            'citofonos'               => 'Bueno',
            'rosetas'                 => 'Bueno',
            'caja_de_fusibles'        => 'Bueno',
            'otros'                   => '',
        ];

        $this->hall = [
            'puerta_hall'               => 'Bueno',
            'ventana_hall'              => 'Bueno',
            'cortinas_hall'             => 'Bueno',
            'pisos_hall'                => 'Bueno',
            'paredes_hall'              => 'Bueno',
            'techos_hall'               => 'Bueno',
            'tomas_electricas_hall'     => 'Bueno',
            'interruptores_hall'        => 'Bueno',
            'lamparas_hall'             => 'Bueno',
            'bombillos_hall'            => 'Bueno',
            'muebles_hall'              => 'Bueno',
            'ventiladores_de_techo_hall'=> 'N/A',
            'anjeos_hall'               => 'Bueno',
            'aires_acondicionados_hall' => 'Bueno',
            'iluminacion_balcon'        => 'Bueno',
            'vidrio_balcon'             => 'Bueno',
            'baranda_balcon'            => 'Bueno',
            'piso_balcon'               => 'Bueno',
            'paredes_balcon'            => 'Bueno',
            'detecto_de_humo_hall'      => 'Bueno',
            'otros_hall'                => '',
        ];

        $this->cocina = [
            'puerta_cocina'             => 'N/A',
            'ventana_cocina'            => 'Bueno',
            'cortinas_cocina'           => 'Bueno',
            'paredes_cocina'            => 'Bueno',
            'pisos_cocina'              => 'Bueno',
            'techos_cocina'             => 'Bueno',
            'tomas_electricas_cocina'   => 'Bueno',
            'lamparas_cocina'           => 'Bueno',
            'bombillos_cocina'          => 'Bueno',
            'interruptores_cocina'      => 'Bueno',
            'instalacion_gas_cocina'    => 'Bueno',
            'lavaplatos_cocina'         => 'Bueno',
            'grifeteria_cocina'         => 'Bueno',
            'estufa_cocina'             => 'Bueno',
            'horno_cocina'              => 'Bueno',
            'meson_cocina'              => 'Bueno',
            'muebles_cocina'            => 'Bueno',
            'puertas_muebles_cocina'    => 'Bueno',
            'cerraduras_muebles_cocina' => 'Bueno',
            'llaves_muebles_cocina'     => 'Bueno',
            'cajones_muebles_cocina'    => 'Bueno',
            'entrepanos_muebles_cocina' => 'Bueno',
            'calentador_cocina'         => 'Bueno',
            'campana_extractora_cocina' => 'Bueno',
            'otros_cocina'              => '',
        ];

        $this->alcoba_principal = [
            'puerta_alcoba_principal'        => 'Bueno',
            'cerradura_alcoba_principal'     => 'Bueno',
            'llaves_alcoba_principal'        => 'Bueno',
            'ventana_alcoba_principal'       => 'Bueno',
            'cortinas_alcoba_principal'      => 'Bueno',
            'pisos_alcoba_principal'         => 'Bueno',
            'paredes_alcoba_principal'       => 'Bueno',
            'techos_alcoba_principal'        => 'Bueno',
            'tomas_electricas_alcoba_principal' => 'Bueno',
            'interruptores_alcoba_principal' => 'Bueno',
            'lamparas_alcoba_principal'      => 'Bueno',
            'bombillos_alcoba_principal'     => 'Bueno',
            'closet_alcoba_principal'        => 'Bueno',
            'puertas_alcoba_principal'       => 'Bueno',
            'ventiladores_alcoba_principal'  => 'Bueno',
        ];

        $this->bano_principal = [
            'puerta_bano_principal'                  => 'Bueno',
            'ventana_bano_principal'                 => 'Bueno',
            'lavamanos_bano_principal'               => 'Bueno',
            'meson_bano_principal'                   => 'Bueno',
            'mueble_bano_principal'                  => 'Bueno',
            'sanitario_bano_principal'               => 'Bueno',
            'toallero_bano_principal'                => 'Bueno',
            'jabonera_bano_principal'                => 'Bueno',
            'cepillero_bano_principal'               => 'Bueno',
            'espejos_bano_principal'                 => 'Bueno',
            'paredes_bano_principal'                 => 'Bueno',
            'techos_bano_principal'                  => 'Bueno',
            'lamparas_bano_principal'                => 'Bueno',
            'interruptores_bano_principal'           => 'Bueno',
            'bombillos_bano_principal'               => 'Bueno',
            'soporte_papel_higienico_bano_principal' => 'Bueno',
            'portavasos_bano_principal'              => 'Bueno',
            'ducha_bano_principal'                   => 'Bueno',
            'divisiones_bano_principal'              => 'Bueno',
            'otros_bano_principal'                   => '',
        ];
    }

    // ─── Navegación entre pasos ───────────────────────────────────────────────
    public function incrementSteps(): void
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        } else {
            $this->submit();
        }
    }

    public function decrementSteps(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    private function validateCurrentStep(): void
    {
        $config  = $this->currentStepConfig;
        $seccion = $config['seccion'];
        $otrosKey = $config['otros_key'];

        $this->validate($this->rulesForSection($seccion, $otrosKey));
    }

    // ─── Modal de evidencias ──────────────────────────────────────────────────

    /**
     * Abre el modal de evidencia para un campo específico.
     * Se llama desde la vista: wire:click="abrirEvidencia('campo_key')"
     */
    public function abrirEvidencia(string $campo): void
    {
        $this->evidenciaCampoActual      = $campo;
        $this->evidenciaFotoTemp         = null;
        $this->evidenciaObservacionTemp  = $this->evidencias[$campo]['observacion'] ?? '';
        $this->showEvidenciaModal        = true;
    }

    /**
     * Guarda la evidencia (foto + observación) para el campo actual.
     */
    public function guardarEvidencia(): void
    {
        $this->validate([
            'evidenciaFotoTemp'        => 'nullable|image|max:4096',
            'evidenciaObservacionTemp' => 'nullable|string|max:1000',
        ]);

        $campo = $this->evidenciaCampoActual;

        $fotoPath = isset($this->evidencias[$campo]['foto'])
            ? $this->evidencias[$campo]['foto']
            : null;

        if ($this->evidenciaFotoTemp) {
            $fotoPath = $this->evidenciaFotoTemp->store('evidencias/propiedad', 'public');
        }

        $this->evidencias[$campo] = [
            'foto'        => $fotoPath,
            'observacion' => $this->evidenciaObservacionTemp,
        ];

        $this->cerrarEvidenciaModal();
    }

    public function cerrarEvidenciaModal(): void
    {
        $this->showEvidenciaModal       = false;
        $this->evidenciaCampoActual     = null;
        $this->evidenciaFotoTemp        = null;
        $this->evidenciaObservacionTemp = '';
        $this->resetErrorBag(['evidenciaFotoTemp', 'evidenciaObservacionTemp']);
    }

    // ─── Reglas de validación ─────────────────────────────────────────────────
    private function rulesForSection(string $section, ?string $otrosKey = null): array
    {
        $rules = collect(Arr::except($this->$section, array_filter([$otrosKey])))
            ->mapWithKeys(fn($_, $key) => ["$section.$key" => 'required|in:Bueno,Regular,Malo,N/A,Dobles,Electrica'])
            ->toArray();

        if ($otrosKey) {
            $rules["$section.$otrosKey"] = 'nullable|string|max:500';
        }

        return $rules;
    }

    // ─── Submit final ─────────────────────────────────────────────────────────
    public function submit(): void
    {
        $data = array_merge(
            ['inventario_id' => $this->inventario],
            $this->entrada,
            $this->hall,
            $this->cocina,
            $this->alcoba_principal,
            $this->bano_principal,
        );

        // Serializar evidencias como JSON (agregar columna `evidencias` json en la migración)
        $data['evidencias'] = json_encode($this->evidencias);

        $propiedad = Propiedad::create($data);

        redirect()->route('informacionHabitaciones', [$propiedad]);
    }

    // ─── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.form-step-propiedad', [
            'steps'              => $this->stepsConfig(),
            'totalSteps'         => $this->totalSteps,
            'progressPercentage' => $this->progressPercentage,
            'currentStepConfig'  => $this->currentStepConfig,
        ]);
    }
}
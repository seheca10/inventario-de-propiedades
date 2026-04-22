<?php

namespace App\Http\Livewire;

use App\Models\Habitacion;
use Livewire\Component;
use Livewire\WithFileUploads;

class HabitacionForm extends Component
{
    use WithFileUploads;

    // ─── Estado principal ─────────────────────────────────────────────────────
    public $propiedad;
    public array $habitaciones = [];

    // ─── Estado del modal de evidencia ───────────────────────────────────────
    public bool $showEvidenciaModal  = false;
    public ?int $evidenciaIndex      = null;   // índice de la habitación
    public ?string $evidenciaCampo   = null;   // key del campo
    public $evidenciaFotoTemp        = null;
    public string $evidenciaObsTemp  = '';

    // Estructura: $evidencias[index][campo] = ['foto' => path|null, 'obs' => string]
    public array $evidencias = [];

    // ─── Defaults de una habitación ──────────────────────────────────────────
    private function getDefaults(): array
    {
        return [
            'puerta'             => 'Bueno',
            'cerradura'          => 'Bueno',
            'llaves'             => 'Bueno',
            'ventana'            => 'Bueno',
            'vidrio'             => 'Bueno',
            'rieles'             => 'Bueno',
            'cortinas'           => 'Bueno',
            'rejas'              => 'N/A',
            'pisos'              => 'Bueno',
            'alfombras'          => 'N/A',
            'paredes'            => 'Bueno',
            'techos'             => 'Bueno',
            'aires_acondicionados' => 'N/A',
            'ventiladores'       => 'N/A',
            'anjeos'             => 'Bueno',
            'tomacorrientes'     => 'Bueno',
            'tomas_telefonicas'  => 'N/A',
            'tomas_television'   => 'Bueno',
            'interruptores'      => 'Bueno',
            'rosetas'            => 'Bueno',
            'lamparas'           => 'Bueno',
            'bombillos'          => 'Bueno',
            'guarda_escobas'     => 'Bueno',
            'closet'             => 'Bueno',
            'entrepanos'         => 'Bueno',
            'puertas'            => 'Bueno',
            'cajones'            => 'Bueno',
            'otros'              => '',        // campo libre
        ];
    }

    // ─── Reglas ───────────────────────────────────────────────────────────────
    protected function rules(): array
    {
        $rules = [];
        foreach ($this->habitaciones as $i => $_) {
            foreach (array_keys($this->getDefaults()) as $campo) {
                if ($campo === 'otros') {
                    $rules["habitaciones.$i.$campo"] = 'nullable|string|max:500';
                } else {
                    $rules["habitaciones.$i.$campo"] = 'required|in:Bueno,Regular,Malo,N/A';
                }
            }
        }
        return $rules;
    }

    // ─── Ciclo de vida ────────────────────────────────────────────────────────
    public function mount($propiedad): void
    {
        $this->propiedad      = $propiedad->id;
        $this->habitaciones[] = $this->getDefaults();
        $this->evidencias[]   = [];
    }

    // ─── CRUD de habitaciones ─────────────────────────────────────────────────
    public function addHabitacion(): void
    {
        $this->habitaciones[] = $this->getDefaults();
        $this->evidencias[]   = [];
    }

    public function removeHabitacion(int $index): void
    {
        unset($this->habitaciones[$index], $this->evidencias[$index]);
        $this->habitaciones = array_values($this->habitaciones);
        $this->evidencias   = array_values($this->evidencias);
    }

    // ─── Modal de evidencias ──────────────────────────────────────────────────
    public function abrirEvidencia(int $index, string $campo): void
    {
        $this->evidenciaIndex    = $index;
        $this->evidenciaCampo    = $campo;
        $this->evidenciaFotoTemp = null;
        $this->evidenciaObsTemp  = $this->evidencias[$index][$campo]['obs'] ?? '';
        $this->showEvidenciaModal = true;
    }

    public function guardarEvidencia(): void
    {
        $this->validate([
            'evidenciaFotoTemp' => 'nullable|image|max:4096',
            'evidenciaObsTemp'  => 'nullable|string|max:1000',
        ]);

        $i     = $this->evidenciaIndex;
        $campo = $this->evidenciaCampo;

        $fotoPath = $this->evidencias[$i][$campo]['foto'] ?? null;

        if ($this->evidenciaFotoTemp) {
            $fotoPath = $this->evidenciaFotoTemp->store('evidencias/habitaciones', 'public');
        }

        $this->evidencias[$i][$campo] = [
            'foto' => $fotoPath,
            'obs'  => $this->evidenciaObsTemp,
        ];

        $this->cerrarModal();
    }

    public function cerrarModal(): void
    {
        $this->showEvidenciaModal = false;
        $this->evidenciaIndex     = null;
        $this->evidenciaCampo     = null;
        $this->evidenciaFotoTemp  = null;
        $this->evidenciaObsTemp   = '';
        $this->resetErrorBag(['evidenciaFotoTemp', 'evidenciaObsTemp']);
    }

    // ─── Guardar ─────────────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        foreach ($this->habitaciones as $i => $hab) {
            Habitacion::create(array_merge(
                ['propiedad_id' => $this->propiedad],
                $hab,
                ['evidencias' => json_encode($this->evidencias[$i] ?? [])]
            ));
        }

        redirect()->route('informacionBanos', [$this->propiedad])
            ->with('success', 'Habitaciones guardadas correctamente.');
    }

    public function render()
    {
        return view('livewire.habitacion-form');
    }
}
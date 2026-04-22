<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\BanoAdicional;
use App\Models\Habitacion;

class BanoAdicionalForm extends Component
{
    use WithFileUploads;

    // ─── Estado principal ─────────────────────────────────────────────────────
    public $propiedadId;
    public array $banos       = [];
    public array $habitaciones = [];

    // ─── Modal de evidencia ───────────────────────────────────────────────────
    public bool $showEvidenciaModal = false;
    public ?int $evidenciaIndex     = null;
    public ?string $evidenciaCampo  = null;
    public $evidenciaFotoTemp       = null;
    public string $evidenciaObsTemp = '';

    // $evidencias[banoIndex][campo] = ['foto' => path|null, 'obs' => string]
    public array $evidencias = [];

    // ─── Campos que tienen select (excluye nombre y otros) ───────────────────
    private function getCamposSelect(): array
    {
        return [
            'puerta'                  => 'Puerta',
            'cerradura'               => 'Cerradura',
            'llaves'                  => 'Llaves',
            'ventana'                 => 'Ventana',
            'lavamanos'               => 'Lavamanos',
            'meson'                   => 'Mesón',
            'mueble'                  => 'Mueble',
            'puertas'                 => 'Puertas mueble',
            'cerraduras'              => 'Cerraduras mueble',
            'gabinete'                => 'Gabinete',
            'entrepanos'              => 'Entrepaños',
            'sanitario'               => 'Sanitario',
            'toallero'                => 'Toallero',
            'jabonera'                => 'Jabonera',
            'cepillero'               => 'Cepillero',
            'espejos'                 => 'Espejos',
            'paredes'                 => 'Paredes',
            'techos'                  => 'Techos',
            'lamparas'                => 'Lámparas',
            'interruptores'           => 'Interruptores',
            'bombillos'               => 'Bombillos',
            'soporte_papel_higienico' => 'Soporte papel higiénico',
            'rejillas_desague'        => 'Rejillas desagüe',
            'portavasos'              => 'Portavasos',
            'ducha'                   => 'Ducha',
            'divisiones'              => 'Divisiones',
        ];
    }

    private function getDefaults(): array
    {
        $base = [
            'habitacion_id' => null,
            'nombre'        => '',
        ];

        foreach (array_keys($this->getCamposSelect()) as $campo) {
            // Campos que normalmente no aplican en todos los baños
            $base[$campo] = in_array($campo, ['gabinete', 'entrepanos', 'puertas', 'cerraduras', 'rejillas_desague', 'portavasos'])
                ? 'N/A'
                : 'Bueno';
        }

        $base['otros'] = '';
        return $base;
    }

    // ─── Reglas dinámicas ────────────────────────────────────────────────────
    protected function rules(): array
    {
        $rules = [];
        foreach ($this->banos as $i => $_) {
            $rules["banos.$i.nombre"]        = 'nullable|string|max:100';
            $rules["banos.$i.habitacion_id"] = 'nullable|integer';
            foreach (array_keys($this->getCamposSelect()) as $campo) {
                $rules["banos.$i.$campo"] = 'required|in:Bueno,Regular,Malo,N/A';
            }
            $rules["banos.$i.otros"] = 'nullable|string|max:500';
        }
        return $rules;
    }

    // ─── Ciclo de vida ────────────────────────────────────────────────────────
    public function mount($propiedadId): void
    {
        $this->propiedadId  = $propiedadId;
        $this->habitaciones = Habitacion::where('propiedad_id', $propiedadId)->get()->toArray();
        $this->banos[]      = $this->getDefaults();
        $this->evidencias[] = [];
    }

    // ─── CRUD de baños ────────────────────────────────────────────────────────
    public function addBano(): void
    {
        $this->banos[]      = $this->getDefaults();
        $this->evidencias[] = [];
    }

    public function removeBano(int $index): void
    {
        unset($this->banos[$index], $this->evidencias[$index]);
        $this->banos      = array_values($this->banos);
        $this->evidencias = array_values($this->evidencias);
    }

    // ─── Modal de evidencia ───────────────────────────────────────────────────
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
            $fotoPath = $this->evidenciaFotoTemp->store('evidencias/banos', 'public');
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

        foreach ($this->banos as $i => $bano) {
            BanoAdicional::create(array_merge(
                ['propiedad_id' => $this->propiedadId],
                $bano,
                ['evidencias' => json_encode($this->evidencias[$i] ?? [])]
            ));
        }

        session()->flash('success', 'Baños guardados correctamente.');
        redirect()->route('inventarios.index', [$this->propiedadId]);
    }

    public function render()
    {
        return view('livewire.bano-adicional-form', [
            'habitaciones' => $this->habitaciones,
            'camposSelect' => $this->getCamposSelect(),
        ]);
    }
}
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ObservacionAdicional;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Models\Propiedad;

class ObservacionAdicionalForm extends Component
{
    use WithFileUploads;

    public $inventario;
    public $imagen_evidencia;
    public $observaciones;
    public $path;
    public $message;

    public function render()
    {
        return view('livewire.observacion-adicional-form');
    }

    protected $rules = [
        'imagen_evidencia' => 'image|max:1024',
        'observaciones' => 'required',
    ];

    public function mount($inventario)
    {
        $this->inventario = $inventario;
    }

    public function resetFields()
    {
        $this->imagen_evidencia = '';
        $this->observaciones = '';
    }

    public function guardarObservaciones()
    {
        $this->validate();

        $this->message = '';

        $path = $this->imagen_evidencia->store('inventarios/observaciones/evidencias', 'public');

        // Crea un nuevo registro de ObservacionAdicional
        ObservacionAdicional::create([
            'inventario_id' => $this->inventario,
            'imagen_evidencia' => $path,
            'observaciones' => $this->observaciones,
        ]);

        $this->message = 'La oservacion añadida correctamente puedes cerrar esta ventana';
        $this->emit('alert', $this->message);
        $this->resetFields();
    }

    public function showObservacionAdicional($campo)
    {
        if ($this->{$campo} === 'Malo') {
            return view('partials.observacion_adicional', [
                'campo' => $campo,
            ]);
        }
    }
}

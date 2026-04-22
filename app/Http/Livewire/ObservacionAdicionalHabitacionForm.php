<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ObservacionAdicionalHabitacion;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ObservacionAdicionalHabitacionForm extends Component
{
    use WithFileUploads;

    public $observaciones = [];
    public $propiedad;
    public $imagen_evidencia;
    public $path;
    public $message;

    public function addObservacion()
    {
        $this->observaciones[] = new ObservacionAdicionalHabitacion;
    }

    public function removeObservacion($index)
    {
        unset($this->observaciones[$index]);
    }

    /* public function mount($propiedad)
    {
        $this->propiedad = $propiedad->id;
    } */

    public function render()
    {
        return view('livewire.observacion-adicional-habitacion-form');
    }

    protected $rules = [
        // Define las reglas de validacion
        'observaciones.*.imagen_evidencia' => 'required|image|max:1024',
        'observaciones.*.observaciones' => 'required',
    ];

    public $messages = [
        'observaciones.*.imagen_evidencia' => 'Es obligatorio',
        'observaciones.*.observaciones' => 'Es obligatorio',
    ];

    public function resetFields()
    {
        $this->imagen_evidencia = '';
        $this->observaciones = '';
    }

    public function guardarObservaciones()
    {
        $this->validate($this->rules);

        foreach ($this->observaciones as $observacion) {

            $path = $observacion['imagen_evidencia']->store('inventarios/observaciones/evidencias/habitaciones', 'public');

            ObservacionAdicionalHabitacion::create([
                'propiedad_id' => $this->propiedad,
                'imagen_evidencia' => $path,
            ] + $observacion);
        }

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

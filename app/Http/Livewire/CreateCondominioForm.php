<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Condominio;

class CreateCondominioForm extends Component
{
    public $nombre;

    protected $rules = [
        'nombre' => 'required|string|max:255',
    ];

    public function render()
    {
        return view('livewire.create-condominio-form');
    }

    public function submitCondominio()
    {
        $this->validate();

        $condominio = Condominio::create([
            'nombre' => $this->nombre,
        ]);

        // Emitimos el ID recién creado al padre
        $this->emit('condominioCreated', $condominio->id);

        // Mensaje flash opcional
        session()->flash('message', 'Condominio creado correctamente.');

        // Reseteamos campo
        $this->reset('nombre');

        // Cerramos modal con JS
        $this->dispatchBrowserEvent('close-modal');
    }
}
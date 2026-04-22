<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DynamicPropertySelect extends Component
{
    public $tipoVivienda;

    public function render()
    {
        return view('livewire.dynamic-property-select');
    }

    public function updatedTipoVivienda()
    {
        $this->validateOnly('tipoVivienda');
    }

    public function cambiarTipoVivienda()
    {
        $this->validate();

        return redirect()->route('inventarios.create', ['tipo_de_propiedad' => $this->tipoVivienda]);
    }

    public function rules()
    {
        return [
            'tipoVivienda' => 'required|in:Casa,Apartamento',
        ];
    }

    public function messages()
    {
        return [
            'tipoVivienda.required' => 'Debes seleccionar un tipo de propiedad',
            'tipoVivienda.in' => 'El tipo de propiedad seleccionado no es válido',
        ];
    }
}

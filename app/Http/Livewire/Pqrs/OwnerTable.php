<?php

namespace App\Http\Livewire\Pqrs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Owner;

class OwnerTable extends Component
{
    use WithPagination;

    // Lista
    public string $search = '';

    // Formulario
    public ?int $editingId = null;
    public string $name    = '';
    public string $phone   = '';
    public string $email   = '';

    // UI
    public bool $showForm    = false;
    public bool $showConfirm = false;
    public ?int $deleteId    = null;

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:150',
            'phone'   => 'required|string|max:20',
            'email'   => 'required|email|max:150|unique:owners,email,' . ($this->editingId ?? 'NULL'),
        ];
    }

    protected array $messages = [
        'name.required'  => 'El nombre es obligatorio.',
        'phone.required' => 'El teléfono es obligatorio.',
        'email.required' => 'El email es obligatorio.',
        'email.email'    => 'El email no tiene un formato válido.',
        'email.unique'   => 'Ya existe un propietario con este email.',
    ];

    public function updatingSearch() { $this->resetPage(); }

    // Abrir formulario vacío para crear
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    // Cargar datos para editar
    public function edit(int $id): void
    {
        $owner = Owner::findOrFail($id);
        $this->editingId = $id;
        $this->name      = $owner->name;
        $this->phone     = $owner->phone;
        $this->email     = $owner->email;
        $this->showForm  = true;
    }

    // Guardar (crear o actualizar)
    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Owner::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Propietario actualizado correctamente.');
        } else {
            Owner::create($data);
            session()->flash('success', 'Propietario creado correctamente.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    // Confirmar eliminación
    public function confirmDelete(int $id): void
    {
        $this->deleteId     = $id;
        $this->showConfirm  = true;
    }

    public function cancelDelete(): void
    {
        $this->deleteId    = null;
        $this->showConfirm = false;
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            Owner::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Propietario eliminado.');
        }
        $this->cancelDelete();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name      = '';
        $this->phone     = '';
        $this->email     = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.pqrs.owner-table', [
            'owners' => Owner::query()
                ->when($this->search, fn($q) =>
                    $q->where('name',  'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%")
                )
                ->withCount('tickets')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
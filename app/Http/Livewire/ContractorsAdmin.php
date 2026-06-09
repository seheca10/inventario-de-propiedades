<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Contractor;
use App\Models\User;

class ContractorsAdmin extends Component
{
    public $contractorId;
    public $name;
    public $email;
    public $phone;
    public $password;

    public $openCreateModal = false;

    public $listeners = [
        'refreshContractors' => '$refresh',
        'openCreateModal' => 'create',
    ];

    public function closeModal()
    {
        $this->openCreateModal = false;
    }

    public function mount()
    {
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->contractorId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
    }

    public function create()
    {
        $this->openCreateModal = true;
        $this->resetInputFields();
    }
    
    public function edit(Contractor $contractor)
    {
        $this->contractorId = $contractor->id;
        $this->name = $contractor->name;
        $this->email = $contractor->email;
        $this->phone = $contractor->phone;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function save(){
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contractors,email,' . $this->contractorId,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        if ($this->contractorId) {
            $contractor = Contractor::find($this->contractorId);
            $contractor->update($validatedData);
            session()->flash('success', 'Contractor updated successfully.');
        } else {

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            $user->assignRole('Contratista');

            Contractor::create([
                'user_id' => $user->id
            ] + $validatedData);

            $this->openCreateModal = false;

            session()->flash('success', 'Contractor created successfully.');
        }

        $this->dispatchBrowserEvent('hide-modal');
    }

    public function render()
    {
        return view('livewire.contractors-admin', [
            'contractors' => Contractor::paginate(10),
        ]);
    }
}

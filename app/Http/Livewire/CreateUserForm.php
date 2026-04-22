<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\AgenteInmobiliario;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class CreateUserForm extends Component
{
    public $name;
    public $email;
    public $password;
    public $identificacion;
    public $telefono;
    public $rol;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'identificacion' => 'required',
        'telefono' => 'required',
        'rol' => 'required',
        'password' => 'required'
    ];

    public function resetFields() {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->identificacion = '';
        $this->telefono = '';
        $this->rol = '';
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.create-user-form', compact('roles'));
    }
    
    public function generatePassword()
    {
        return $this->password = Str::random(16);
    }

    public function submit()
    {
        $this->validate();
        
        $user = User::create([
            'name' => $this->name,  
            'email' => $this->email,  
            'password' => bcrypt($this->password),  // encriptar contraseña con Bcrypt
        ]);

        $user->save();

        $user->assignRole($this->rol);

        if($this->rol === "2"){
            AgenteInmobiliario::create([
                'user_id' => $user->id,
                'nombre' => $user->name,
                'identificacion' => $this->identificacion,
                'telefono' => $this->telefono
            ]);
        }

        $this->resetFields();
        session()->flash('message', 'Usuario Creado Correctamente.');
    }
}

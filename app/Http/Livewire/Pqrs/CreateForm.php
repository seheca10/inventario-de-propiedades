<?php

namespace App\Http\Livewire\Pqrs;

use App\Mail\PqrsTicketCreated;
use Livewire\Component;
use App\Models\PqrsTicket;
use App\Services\PqrsService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateForm extends Component
{
    public $tenant_name;
    public $tenant_email;
    public $tenant_phone;
    public $description;
    public $contract_number; // Agregada ya que está en tu HTML
    
    // Usaremos únicamente estas dos propiedades para los dropdowns
    public $category = '';
    public $issue_type = '';

    protected $categoryData = [
        'Plomeria' => ['Fuga de agua', 'Tubería rota', 'Lavamanos tapado', 'Sanitario tapado', 'Daño en ducha', 'Daño en grifería'],
        'Electrico' => ['Sin energía', 'Tomacorriente dañado', 'Interruptor dañado', 'Lámpara dañada', 'Cortocircuito'],
        'Carpinteria' => ['Puerta dañada', 'Ventana dañada', 'Closet dañado', 'Bisagras dañadas'],
        'Pintura' => ['Humedad en pared', 'Pintura deteriorada', 'Manchas de humedad'],
        'Electrodomesticos' => ['Aire acondicionado', 'Estufa', 'Campana extractora', 'Calentador', 'Otro electrodoméstico'],
        'Limpieza' => ['Limpieza general', 'Retiro de escombros', 'Control de plagas'],
        'Otro' => ['Otro']
    ];

    // Se dispara automáticamente al cambiar la categoría seleccionada
    public function updatedCategory()
    {
        $this->issue_type = ''; // Limpia el tipo de problema anterior
    }

    public function submit(PqrsService $pqrsService)
    {
        $validatedData = $this->validate([
            'tenant_name' => 'required|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'tenant_email' => 'required|email|max:255',
            'tenant_phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'issue_type' => 'required|string|max:255',
        ]);

        $ticket = $pqrsService->create($validatedData);

        $this->reset();

        $whatsAppLink = $ticket->getWhatsAppLink('default');
        Mail::to($ticket->tenant_email)->send(new PqrsTicketCreated($ticket));

        return redirect()->away($whatsAppLink);
    }

    public function render()
    {
        // Filtra usando la propiedad unificada $this->category
        $issue_types = $this->categoryData[$this->category] ?? [];

        return view('livewire.pqrs.create-form', [
            'categories' => array_keys($this->categoryData),
            'issue_types' => $issue_types,
        ]);
    }
}
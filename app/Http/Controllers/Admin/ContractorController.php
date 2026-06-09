<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GenerateTicketQuote;
use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Models\PqrsTicket;
use App\Models\PqrstLog;
use App\Models\TicketAssignment;
use App\Models\TicketQuote;
use App\Services\PqrsService;
use Illuminate\Support\Facades\Mail;

class ContractorController extends Controller
{
    public function index()
    {
        $contractors = Contractor::all();
        return view('admin.contractors.index', compact('contractors'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:contractors,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Contractor::create($validatedData);

        return redirect()->route('contractos.index')->with('success', 'Contractor created successfully.');
    }

    public function update(Request $request, Contractor $contractor)
    {
        $validatedData = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:contractors,email,' . $contractor->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $contractor->update($validatedData);

        return redirect()->route('contractos.index')->with('success', 'Contractor updated successfully.');
    }

    public function destroy(Contractor $contractor)
    {
        $contractor->delete();
        return redirect()->route('contractos.index')->with('success', 'Contractor deleted successfully.');
    }

    public function admin()
    {
        $contractor = Contractor::with([
            'ticketAssignments' => fn($q) => $q->orderBy('created_at', 'desc'),
            'ticketAssignments.ticket.assignments',
            'ticketAssignments.ticket.quotes',
            'ticketAssignments.ticket.owner',
        ])
        ->where('user_id', auth()->id())
        ->firstOrFail();

        return view('admin.contractors.home-contractor', compact('contractor'));
    }

    /**
     * PROBLEMA 3: acceptAssignment() recibe $contractor por route model binding,
     * pero la ruta solo tiene {ticket} como parámetro — $contractor siempre
     * será un modelo vacío o causará un error de binding.
     *
     * Además, PqrsService::acceptAssignment() ahora requiere $userId como
     * tercer parámetro (corrección aplicada en la sesión anterior).
     *
     * SOLUCIÓN: Obtener el $contractor desde el usuario autenticado,
     * igual que en admin(), y pasar auth()->id() explícitamente.
     */
    public function acceptAssignment(PqrsTicket $ticket, PqrsService $pqrsService)
    {
        $contractor = Contractor::where('user_id', auth()->id())->firstOrFail();

        $pqrsService->acceptAssignment($ticket, $contractor, auth()->id());

        return redirect()->route('contractors.admin')->with('success', 'Ha aceptado el ticket.');
    }

    /**
     * PROBLEMA 4: generateQuote() pasaba auth()->user() completo como tercer argumento,
     * pero PqrsService::generateQuote() espera (int $userId, int $contractorId)
     * según la corrección aplicada en la sesión anterior.
     */
    public function generateQuote(Request $request, $id, PqrsService $pqrsService)
    {
        $ticket     = PqrsTicket::findOrFail($id);
        $contractor = Contractor::where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'labor_cost'    => 'required|numeric|min:0',
            'material_cost' => 'required|numeric|min:0',
            'total_amount'  => 'required|numeric|min:0',
            'description'   => 'required|string',
            'pdf_path'      => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $pqrsService->generateQuote($ticket, $data, auth()->id(), $contractor->id);

        return redirect()->route('contractors.admin')->with('success', 'Cotización generada exitosamente.');
    }

    //Iniciar el trabajo de reparación
    public function startWork($id)
    {
        //Actualizar estado del ticket
        $ticket = PqrsTicket::findOrFail($id);

        //Actualizar estado del ticket
        $ticket->update([
            'status' => 'in_progress'
        ]);

        return redirect()->route('contractors.admin')->with('success', 'Cuando finalices el trabajo puedes cerrar el caso.');

    }

    public function finishTicket(PqrsTicket $ticket, Request $request, PqrsService $pqrsService)
    {
        $ticket->update(['status' => 'finished']);

        // Subir fotos si las hay
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $ticket->attachments()->create([
                    'file_path'     => $photo->store('tickets/evidence', 'public'),
                    'original_name' => $photo->getClientOriginalName(),
                    'file_type'     => $photo->getMimeType(),
                    'uploaded_by'   => auth()->user()->name,
                ]);
            }
        }

        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => auth()->id(),
            'event_type'  => 'ticket_finished',
            'description' => 'Contratista confirmó la finalización del trabajo.',
            'old_values'  => [],
            'new_values'  => ['status' => 'finished'],
            'metadata'    => [],
            'source'      => "user:" . auth()->id(),
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return redirect()->route('contractors.admin')
            ->with('success', 'Trabajo finalizado correctamente.');
    }
}
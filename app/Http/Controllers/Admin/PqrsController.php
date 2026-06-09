<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AssignmentPqrsTicket;
use Illuminate\Http\Request;
use App\Models\PqrsTicket;
use Illuminate\Support\Str;
use App\Models\TicketAttachment;
use App\Models\PqrsReport;
use App\Mail\PqrsTicketCreated;
use App\Models\Contractor;
use App\Models\Owner;
use App\Models\PqrstLog;
use App\Models\TicketAssignment;
use App\Models\TicketClosure;
use App\Models\TicketQuote;
use App\Models\TicketRating;
use App\Models\TicketSchedule;
use App\Models\VisitScheuldeReport;
use App\Services\PqrsService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PqrsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pqrs.index', [
            'tickets' => PqrsTicket::latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pqrs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PqrsService $pqrsService)
    {
        //Validar los campos del formulario y crear el ticket
        $validatedData = $request->validate([
            'contract_number' => 'required|string|max:255',
            'tenant_name' => 'required|string|max:255',
            'tenant_email' => 'required|email|max:255',
            'tenant_phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'issue_type' => 'required|string|max:255',
        ]);

        $ticket = $pqrsService->create($validatedData);
        
        $whatsAppLink = $ticket->getWhatsAppLink('default');

        return redirect()->away($whatsAppLink);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validateTicket(Request $request, PqrsTicket $ticket)
    {
        $request->validate([
            'contract_number' => 'required|string|max:100',
            'owner_id'        => 'required|exists:owners,id',
            'category'        => 'nullable|string|max:100',
            'issue_type'      => 'nullable|string|max:100',
            'priority'        => 'nullable|in:low,medium,high,critical',
            'description'     => 'nullable|string',
        ]);
    
        $ticket->update([
            'contract_number' => $request->contract_number,
            'owner_id'        => $request->owner_id,
            'category'        => $request->category   ?? $ticket->category,
            'issue_type'      => $request->issue_type ?? $ticket->issue_type,
            'priority'        => $request->priority   ?? $ticket->priority,
            'description'     => $request->description ?? $ticket->description,
            'status'          => 'validated',
        ]);
    
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => auth()->id(),
            'event_type'  => 'ticket_validated',
            'description' => 'El operador completó y validó la información del ticket.',
            'old_values'  => [],
            'new_values'  => $request->only(['contract_number', 'owner_id', 'priority']),
            'metadata'    => [],
            'source'      => 'admin',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
    
        return redirect()->back()->with('success', 'Ticket validado correctamente. Ya puedes asignar un contratista.');
    }

    public function showTicket($id)
    {
        $ticket = PqrsTicket::with([
            'attachments',
            'report',
            'assignments.contractor',
            'owner',
            'logs.user',
            'rating',
            'closure'
        ])->findOrFail($id);
    
        $contractors = Contractor::orderBy('name')->get();
        $owners      = Owner::orderBy('name')->get();   // <-- nuevo
    
        return view('admin.pqrs.show-admin', compact('ticket', 'contractors', 'owners'));
    }

    public function assignTicket(Request $request, $id)
    {
        $request->validate([
            'contractor_id' => 'required|exists:contractors,id',
        ]);

        $ticket = PqrsTicket::findOrFail($id);
        $contractorId = $request->input('contractor_id');

        $ticket->update([
            'status' => 'assigned_pending_accept',
        ]);

        //Asignar ticket al contratista
        $assignment = TicketAssignment::create([
            'ticket_id' => $ticket->id,
            'contractor_id' => $contractorId,
            'assigned_at' => now(),
            'status' => 'assigned',
            'notes' => 'Ticket asignado al contratista.',
        ]);

        //Crear log ticket
        PqrstLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'event_type' => 'ticket_assigned',
            'description' => 'Ticket asignado al contratista.',
            'old_values' => [],
            'new_values' => ['contractor_id' => $ticket->contractor_id],
            'metadata' => [],
            'source' => 'admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        //Enviar mail al usuario notificando que su ticket ha sido asignado a un contratista
        $assignment->load('contractor.user');

        $contractor = $assignment->contractor;

        Mail::to($ticket->tenant_email)->send(new AssignmentPqrsTicket($ticket, $contractor));

        return redirect()->route('pqrs.show-admin', $ticket->id)->with('successAssigned', 'Ticket assigned successfully.');
    }

    public function viewQuote($id)
    {
        $ticket = PqrsTicket::with('quotes')->findOrFail($id);

        $quote = $ticket->quotes()->latest()->first();

        return view('admin.pqrs.tenant.view-quote', compact('ticket', 'quote'));
    }

    public function approveQuote($quoteId)
    {
        $quote = TicketQuote::findOrFail($quoteId);
        $quote->update(['status' => 'approved']);

        $ticket = $quote->ticket;
        $ticket->update(['status' => 'approved']);

        //Crear log ticket
        PqrstLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'event_type' => 'quote_approved',
            'description' => 'El propietario acepto la cotización.',
            'old_values' => [],
            'new_values' => ['contractor_id' => $ticket->contractor_id],
            'metadata' => [],
            'source' => 'admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $whatsAppLink = $ticket->getWhatsAppLink('approved');

        return redirect()->away($whatsAppLink);

        /* return redirect()->back()->with('success', 'Cotización aprobada exitosamente.'); */
    }

    public function rejectQuote($quoteId)
    {
        $quote = TicketQuote::findOrFail($quoteId);
        $quote->update(['status' => 'rejected']);

        $ticket = $quote->ticket;

        //Crear log ticket
        PqrstLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'event_type' => 'quote_reject',
            'description' => 'El propietario rechazó la cotización.',
            'old_values' => [],
            'new_values' => ['contractor_id' => $ticket->contractor_id],
            'metadata' => [],
            'source' => 'admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Cotización rechazada exitosamente.');
    }

    public function scheduleVisit(Request $request, $id, PqrsService $pqrsService)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|in:diagnostic,work',
            'option_1' => 'required|date',
            'option_2' => 'required|date',
            'option_3' => 'required|date',
        ]);

        $ticket = PqrsTicket::findOrFail($id);

        $pqrsService->scheduleVisit($ticket, $validatedData);

        return redirect()->route('pqrs.show-admin', $ticket->id)->with('successScheduleVisit', 'Opciones de visita programadas exitosamente.');
    }

    public function showSchedule($id)
    {
        $ticket = PqrsTicket::findOrFail($id);

        $schedules = $ticket->schedules()->get();

        return view('admin.pqrs.tenant.view-schedule', compact('ticket', 'schedules'));
    }

    public function confirmSchedule(Request $request, $id)
    {
        $request->validate([
            'selected_option' => 'required|exists:ticket_schedules,id',
        ]);

        $schedule = TicketSchedule::findOrFail($request->selected_option);

        $ticket = $schedule->ticket;

        $schedule->update([
            'confirmed_at' => now(),
        ]);

        //Actualizar estado del ticket a "visit_scheduled" o "work:show_schedule" segun corresponda
        $newStatus = $schedule->type === 'work' ? 'work_scheduled_confirmed' : 'visit_scheduled_confirmed';

        $ticket->update([
            'status' => $newStatus,
        ]);

         //Crear log ticket
         PqrstLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'event_type' => 'schedule_confirmed',
            'description' => 'El propietario confirmó la fecha de visita.',
            'old_values' => [],
            'new_values' => ['selected_option' => $request->selectedOption],
            'metadata' => [],
            'source' => 'admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $schedule->update([
            'selected_option' => $request->selectedOption,
            'confirmed_at_tenant' => now(),
            'confirmed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Visita programada confirmada exitosamente.');
    }

    public function registerScheuldeView(PqrsTicket $ticket)
    {
        $contractor = auth()->user()->contractor;
        $schedule   = $ticket->schedules()->firstWhere('confirmed_at', '!=', null);
    
        return view('admin.contractors.register-schedule', compact('ticket', 'contractor', 'schedule'));
    }

    public function registerScheuldeContractor(Request $request, PqrsTicket $ticket)
    {
        $schedule = $ticket->schedules()
            ->where('type', 'work')
            ->whereNotNull('confirmed_at')
            ->first()
            ?? $ticket->schedules()
                ->whereNotNull('confirmed_at')
                ->first();
    
        if (!$schedule) {
            return redirect()->route('contractors.admin')
                ->with('error', 'No hay visita confirmada para este ticket.');
        }
    
        $request->validate([
            'report'           => 'required|string',
            'firma_arrendador' => 'required|string',
            'media'            => 'nullable|array',
            'media.*'          => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        ]);
    
        // ── Guardar firma ──────────────────────────────────────────────────────
        $signaturePath = null;
        $base64 = $request->firma_arrendador;
    
        if (str_contains($base64, ',')) {
            $base64 = explode(',', $base64)[1];
        }
    
        $imageData = base64_decode($base64);
    
        if ($imageData) {
            $filename     = 'signature_' . $schedule->id . '_' . time() . '.png';
            $relativePath = 'signatures/' . $filename;
            Storage::disk('public')->put($relativePath, $imageData);
            $signaturePath = $relativePath;
        }
    
        // ── Crear el reporte de visita ─────────────────────────────────────────
        VisitScheuldeReport::create([
            'schedule_id'  => $schedule->id,
            'report'       => $request->report,
            'reported_by'  => auth()->user()->name,
            'signed_by'    => $signaturePath,
        ]);
    
        // ── Guardar archivos multimedia en ticket_attachments ──────────────────
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('tickets/' . $ticket->id . '/evidence', 'public');
    
                TicketAttachment::create([
                    'pqrs_ticket_id' => $ticket->id,
                    'original_name'  => $file->getClientOriginalName(),
                    'file_path'      => $path,
                    'file_type'      => $file->getMimeType(),
                    'uploaded_by'    => auth()->user()->name,
                ]);
            }
        }
    
        /**
         * CORRECCIÓN: status correcto según el tipo de visita.
         * Antes: 'work_done' no existe en el sistema → ningún match() lo reconocía
         * Ahora:
         *   type = 'work'       → 'finished'   (trabajo ejecutado, operativo cierra)
         *   type = 'diagnostic' → 'diagnosed'  (diagnóstico hecho, contratista cotiza)
         */
        $newStatus = $schedule->type === 'work' ? 'finished' : 'diagnosed';
        $ticket->update(['status' => $newStatus]);
    
        // ── Log ────────────────────────────────────────────────────────────────
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => auth()->id(),
            'event_type'  => $schedule->type === 'work' ? 'ticket_finished' : 'visit_reported',
            'description' => $schedule->type === 'work'
                ? 'Contratista finalizó el trabajo y registró el reporte con evidencia.'
                : 'Contratista registró el reporte del diagnóstico.',
            'old_values'  => [],
            'new_values'  => [
                'status'    => $newStatus,
                'signed_by' => $signaturePath,
            ],
            'metadata'    => [],
            'source'      => auth()->user()->name,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
    
        $message = $schedule->type === 'work'
            ? 'Trabajo finalizado y registrado exitosamente.'
            : 'Visita de diagnóstico registrada exitosamente.';
    
        return redirect()->route('contractors.admin')->with('success', $message);
    }

    public function validateVisit(Request $request, PqrsTicket $ticket)
    {
        $schedule = $ticket->schedules()->firstWhere('confirmed_at', '!=', null);
    
        if (!$schedule) {
            return redirect()->back()->with('error', 'No se encontró una visita programada para este ticket.');
        }
    
        // Cambiar el estado dependiento si es una vistia de diagnóstico o de trabajo realizado
        $newStatus = $schedule->type === 'diagnostic' ? 'quoted' : 'in_progress';
        $ticket->update(['status' => $newStatus]);
    
        // Log
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => auth()->id(),
            'event_type'  => 'visit_validated',
            'description' => 'El operador validó la visita registrada.',
            'old_values'  => [],
            'new_values'  => [],
            'metadata'    => [],
            'source'      => 'admin',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
    
        return redirect()->back()->with('success', 'Visita validada exitosamente.');
    }

    public function closeTicket(Request $request, $ticket)
    {
        $ticket = PqrsTicket::findOrFail($ticket);
 
        $request->validate([
            'rating'            => 'required|integer|min:1|max:5',
            'final_cost'        => 'required|numeric|min:0',
            'comment'           => 'nullable|string|max:1000',
            'summary'           => 'nullable|string|max:1000',
            'payment_confirmed' => 'nullable|boolean',
        ]);
    
        // 1. Cambiar estado
        $ticket->update([
            'status'      => 'closed',
            'closed_at'   => now(),
        ]);
    
        // 2. Rating del arrendatario
        TicketRating::create([
            'ticket_id' => $ticket->id,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
        ]);
    
        // 3. Cierre financiero
        TicketClosure::create([
            'ticket_id' => $ticket->id,
            'final_cost' => $request->final_cost,
            'summary' => $request->summary,
        ]);
    
        // 4. Log
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => auth()->id(),
            'event_type'  => 'ticket_closed',
            'description' => 'El operador cerró el ticket definitivamente.',
            'old_values'  => [],
            'new_values'  => [
                'rating'            => $request->rating,
                'final_cost'        => $request->final_cost,
                'payment_confirmed' => $request->boolean('payment_confirmed'),
            ],
            'metadata'    => [],
            'source'      => 'admin',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
    
        return redirect()->back()->with('success', "Ticket {$ticket->ticket_number} cerrado correctamente.");
    }

    public function publicStatus(Request $request, PqrsTicket $ticket)
    {
        // 1. Validar el token de seguridad para evitar accesos aleatorios
        // Si el token en la URL no coincide con el de la base de datos, abortamos.
        /* if (!$request->has('token') || $request->token !== $ticket->token) {
            abort(403, 'El enlace de seguimiento ha expirado o es inválido.');
        } */

        // 2. Cargar relaciones necesarias para la vista
        // Cargamos contratista, agendas y el rating por si ya está cerrado
        $ticket->load([
            'currentAssignment.contractor',  // contratista actual
            'schedules',                     // todas las agendas para filtrar en vista
            'rating',                        // calificación si ya fue cerrado
            'closure',                       // datos del cierre si existe
            'owner',                         // propietario
            'quotes',                        // para mostrar cotización aprobada si existe
        ]);
    
        return view('admin.pqrs.tenant.show-pqrs-status', compact('ticket'));

        // 3. Retornar la vista (Asegúrate de que el nombre del archivo coincida)
        return view('admin.pqrs.tenant.show-pqrs-status', compact('ticket'));
    }
}
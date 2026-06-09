<?php

namespace App\Services;

use App\Mail\GenerateTicketQuote;
use App\Mail\PqrsTicketCreated;
use App\Models\Contractor;
use App\Models\PqrsTicket;
use App\Models\PqrstLog;
use App\Models\TicketAssignment;
use App\Models\TicketQuote;
use App\Models\TicketSchedule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PqrsService
{
    public function create($validatedData)
    {
        $ticket = PqrsTicket::create($validatedData + [
            'ticket_number' => 'TEMP',
            'contract_number' => $validatedData['contract_number'] ?? null,
            'description'     => $validatedData['description'] ?? '',
            'status'          => 'created',
            'priority'        => 'medium',
            'category'        => $validatedData['category'] ?? 'General',
        ]);

        // Enviar correo al usuario confirmando la creación del ticket
        Mail::to($ticket->tenant_email)->send(new PqrsTicketCreated($ticket));

        return $ticket;
    }

    public function canQuote(PqrsTicket $ticket): bool
    {
        return $ticket->is_accepted_by_contractor
            && !$ticket->approved_quote
            && !$ticket->pending_quote;
    }

    /**
     * CAMBIO: Se corrigió el doble update() que existía antes.
     * Antes había dos llamadas seguidas a $ticket->update():
     *   1. $ticket->update(['pending_quote' => true])   ← columna inexistente, era un accessor
     *   2. $ticket->update(['status' => 'quoted', ...]) ← pisaba al anterior
     * Ahora hay un único update() con todos los campos necesarios.
     *
     * CAMBIO: Se eliminó el acoplamiento con auth() para $user.
     * Antes el método usaba auth()->user() directamente mezclado con el $user recibido.
     * Ahora recibe $userId y $contractorId explícitamente desde el Controller.
     *
     * @param PqrsTicket $ticket
     * @param array      $data
     * @param int        $userId        ID del usuario autenticado (viene del Controller)
     * @param int        $contractorId  ID del contratista (viene del Controller)
     */
    public function generateQuote(PqrsTicket $ticket, array $data, int $userId, int $contractorId): TicketQuote
    {
        if (!$this->canQuote($ticket)) {
            throw new \Exception('No se puede generar una cotización para este ticket.');
        }

        // Lógica para crear la cotización
        $quote = TicketQuote::create([
            'ticket_id'     => $ticket->id,
            'contractor_id' => $contractorId,
            'labor_cost'    => $data['labor_cost'],
            'material_cost' => $data['material_cost'],
            'total_amount'  => $data['total_amount'],
            'description'   => $data['description'],
            'pdf_path'      => isset($data['pdf_path']) ? $data['pdf_path']->store('quotes') : null,
        ]);

        // Guardar el PDF en disco si existe (lógica original preservada)
        $path = null;
        if (isset($data['pdf_path'])) {
            $path = $data['pdf_path']->store('quotes', 'public');
        }

        /**
         * CAMBIO: Un único update() en vez de dos.
         * Se eliminó: $ticket->update(['pending_quote' => true])
         * porque 'pending_quote' es un accessor del modelo, no una columna de BD.
         * El estado 'quoted' ya comunica implícitamente que hay una cotización pendiente.
         */
        $ticket->update([
            'status'        => 'quoted',
            'quote_amount'  => $data['total_amount'],
            'quote_details' => $data['description'],
        ]);

        // Actualizar estado en TicketAssignment
        TicketAssignment::where('ticket_id', $ticket->id)
            ->where('contractor_id', $contractorId)
            ->update([
                'status' => 'quoted',
                'notes'  => 'Contratista generó una cotización.',
            ]);

        // Crear log del ticket
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => $userId,
            'event_type'  => 'ticket_quoted',
            'description' => 'Contratista generó una cotización para el ticket.',
            'old_values'  => [],
            'new_values'  => ['quote_amount' => $data['total_amount']],
            'metadata'    => [],
            'source'      => "user:{$userId}",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        // Enviar mail con la cotización al usuario
        Mail::to($ticket->tenant_email)->send(new GenerateTicketQuote($ticket, $quote));

        $ticket->loadMissing('owner');
 
        if ($ticket->owner) {
            // Email al propietario con botones Aprobar / Rechazar
            Mail::to($ticket->owner->email)
                ->send(new \App\Mail\QuoteReadyForOwner($ticket, $quote));
        }

        return $quote;
    }

    /**
     * CAMBIO: Se corrigió la relación inexistente $ticket->assignment (singular).
     * El modelo PqrsTicket define:
     *   - assignments()       → HasMany (historial completo)
     *   - currentAssignment() → HasOne  (la más reciente)
     * No existe ->assignment en singular. Usar ->currentAssignment causa un error en runtime.
     *
     * CAMBIO: Se eliminó auth() del service. El $contractor ya viene desde el Controller.
     * Ahora se recibe $userId explícitamente en vez de usar auth()->id() internamente.
     *
     * @param PqrsTicket $ticket
     * @param Contractor $contractor
     * @param int        $userId  ID del usuario autenticado (viene del Controller)
     */
    public function acceptAssignment(PqrsTicket $ticket, Contractor $contractor, int $userId): bool
    {
        $ticket->currentAssignment->update(['status' => 'in_progress']);

        $ticket->update(['status' => 'assigned']);

        // Actualizar estado en TicketAssignment
        TicketAssignment::where('ticket_id', $ticket->id)
            ->where('contractor_id', $contractor->id)
            ->update([
                'status' => 'in_progress',
                'notes'  => 'Contratista aceptó el ticket.',
            ]);

        // Crear log del ticket
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => $userId,
            'event_type'  => 'ticket_accepted',
            'description' => 'Ticket aceptado por el contratista.',
            'old_values'  => [],
            'new_values'  => ['contractor_id' => $contractor->id],
            'metadata'    => [],
            'source'      => "user:{$userId}",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return true;
    }

    public function scheduleVisit(PqrsTicket $ticket, array $validatedData)
    {
        $options = [
            'option_1' => $validatedData['option_1'],
            'option_2' => $validatedData['option_2'],
            'option_3' => $validatedData['option_3'],
        ];

        $schedule = TicketSchedule::where('ticket_id', $ticket->id);

        $newStatus = $schedule->type === 'work' ? 'work_scheduled' : 'visit';

        //Actualizar estado del ticket
        $ticket->update([
            'status' => $newStatus
        ]);

        foreach ($options as $option) {
            TicketSchedule::create([
                'ticket_id' => $ticket->id,
                'type' => $validatedData['type'],
                'scheduled_at' => $option,
            ]);
        }

        //Crear log ticket
         PqrstLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'event_type' => 'visit_scheduled',
            'description' => 'El operador envio propuesta de una visita técnica.',
            'old_values' => [],
            'new_values' => ['option_1' => $validatedData['option_1'], 'option_2' => $validatedData['option_2'], 'option_3' => $validatedData['option_3']],
            'metadata' => [],
            'source' => 'admin',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return true;
    }
}
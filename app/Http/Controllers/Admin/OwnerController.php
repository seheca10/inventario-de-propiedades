<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\QuoteReadyForOwner;
use App\Models\PqrsTicket;
use App\Models\PqrstLog;
use App\Models\TicketQuote;
use Illuminate\Support\Facades\Mail;

class OwnerController extends Controller
{
    public function index()
    {
        return view('admin.owners.index');
    }

    /**
     * Muestra la cotización al propietario para que la revise.
     * Ruta: GET /cotizacion/{ticket}/revisar?token=xxx
     */
    public function review(PqrsTicket $ticket, Request $request)
    {
        $token = $request->query('token');
        $quote = $this->resolveQuote($ticket, $token);
 
        return view('pqrs.owner.review', compact('ticket', 'quote'));
    }
 
    /**
     * El propietario aprueba la cotización.
     * Ruta: POST /cotizacion/{ticket}/aprobar
     */
    public function approve(PqrsTicket $ticket, Request $request)
    {
        $token = $request->input('token');
        $quote = $this->resolveQuote($ticket, $token);
 
        if (!$quote->is_pending) {
            return redirect()->route('owner.review-quote', [
                'ticket' => $ticket->id,
                'token'  => $token,
            ])->with('info', 'Esta cotización ya fue procesada anteriormente.');
        }
 
        // Aprobar la cotización
        $quote->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);
 
        // Actualizar el ticket
        $ticket->update(['status' => 'approved']);
 
        // Log del evento
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => null,
            'event_type'  => 'quote_approved',
            'description' => 'Propietario aprobó la cotización desde el enlace de email.',
            'old_values'  => [],
            'new_values'  => ['status' => 'approved'],
            'metadata'    => [],
            'source'      => 'owner:' . $ticket->owner->name,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
 
        // Notificar al operativo interno por email
        /* Mail::to(config('pqrs.agency_email'))
            ->send(new \App\Mail\QuoteApprovedNotifyAdmin($ticket, $quote)); */
 
        return redirect()->route('owner.review-quote', [
            'ticket' => $ticket->id,
            'token'  => $token,
        ])->with('success', '¡Cotización aprobada! Nos pondremos en contacto para coordinar la fecha del trabajo.');
    }
 
    /**
     * El propietario rechaza la cotización (con motivo opcional).
     * Ruta: POST /cotizacion/{ticket}/rechazar
     */
    public function reject(PqrsTicket $ticket, Request $request)
    {
        $token = $request->input('token');
        $quote = $this->resolveQuote($ticket, $token);
 
        if (!$quote->is_pending) {
            return redirect()->route('owner.review-quote', [
                'ticket' => $ticket->id,
                'token'  => $token,
            ])->with('info', 'Esta cotización ya fue procesada anteriormente.');
        }
 
        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);
 
        // Rechazar la cotización
        $quote->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->input('rejection_reason', 'Sin motivo especificado.'),
        ]);
 
        // Actualizar el ticket
        $ticket->update(['status' => 'rejected']);
 
        // Log del evento
        PqrstLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => null,
            'event_type'  => 'quote_reject',
            'description' => 'Propietario rechazó la cotización desde el enlace de email.',
            'old_values'  => [],
            'new_values'  => ['status' => 'rejected', 'rejection_reason' => $request->input('rejection_reason')],
            'metadata'    => [],
            'source'      => 'owner:' . $ticket->owner->name,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
 
        // Notificar al operativo
        /* Mail::to(config('pqrs.agency_email'))
            ->send(QuoteRejectedNotifyAdmin($ticket, $quote)); */
 
        return redirect()->route('owner.review-quote', [
            'ticket' => $ticket->id,
            'token'  => $token,
        ])->with('error', 'Cotización rechazada. El operativo se pondrá en contacto con usted.');
    }
 
    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------
 
    /**
     * Busca la cotización pendiente del ticket y valida el token.
     * Lanza 403 si el token no coincide, 404 si no hay cotización.
     */
    private function resolveQuote(PqrsTicket $ticket, ?string $token): TicketQuote
    {
        $quote = TicketQuote::where('ticket_id', $ticket->id)
            ->where('access_token', $token)
            ->firstOrFail();
 
        abort_if($quote->access_token !== $token, 403, 'Enlace inválido o expirado.');
 
        return $quote;
    }
}

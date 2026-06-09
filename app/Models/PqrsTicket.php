<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class PqrsTicket extends Model
{
    use HasFactory;

    protected $table = 'pqrs_tickets';

    protected $fillable = [
        'ticket_number',
        'contract_number',
        'tenant_name',
        'tenant_email',
        'tenant_phone',
        'description',
        'category',
        'issue_type',
        'status',
        'priority',
        'assigned_at',
        'completed_at',
        'closed_at',
        'owner_id',
    ];

    /**
     * Boot del modelo para lógicas automáticas.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($ticket) {
            $ticket->update([
                'ticket_number' => 'PQRS-' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6)) . '-' . date('Y')
            ]);
        });
    }

    /**
     * Relación con las evidencias / archivos adjuntos del ticket.
     */
    public function attachments() : HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'pqrs_ticket_id');
    }

    /**
     * Relación con el reporte final de cierre.
     */
    public function report() : HasOne
    {
        return $this->hasOne(PqrsReport::class, 'pqrs_ticket_id');
    }

    public function owner() : BelongsTo
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function rating() : HasOne
    {
        return $this->hasOne(TicketRating::class, 'id');
    }

    public function closure() : HasOne
    {
        return $this->hasOne(TicketClosure::class, 'id');
    }

    /**
     * Genera la URL dinámica de WhatsApp (wa.me) con texto predefinido 
     * para que el Admin o el Contratista la usen desde el panel a costo cero.
     * * @param string $actionType ('assigned', 'quoted', 'completed')
     * @return string
     */

    //Token para las urls que se usaran en los enlaces de WhatsApp y que solo seran validos por un tiempo limitado para seguridad
    public function getTokenAttribute()
    {
        return '?token=' . Str::random(40);
    }

    /**
     * Accesor para obtener el contratista actual de forma fácil
     * Uso: $ticket->current_contractor
     */
    public function getCurrentContractorAttribute()
    {
        return $this->currentAssignment?->contractor;
    }

    // En PqrsTicket — accessors para fechas de agenda
    public function getConfirmedDiagnosticScheduleAttribute()
    {
        return $this->schedules
            ->where('type', 'diagnostic')
            ->whereNotNull('confirmed_at')
            ->sortByDesc('confirmed_at')
            ->first();
    }

    public function getConfirmedWorkScheduleAttribute()
    {
        return $this->schedules
            ->where('type', 'work')
            ->whereNotNull('confirmed_at')
            ->sortByDesc('confirmed_at')
            ->first();
    }

    /**
     * Genera el enlace de WhatsApp con mensajes dinámicos según el estado del ticket y el flujo de PQRS.
     * Cubre fases de validación, diagnóstico, cotización, ejecución de trabajo y cierre.
    */
    public function getWhatsAppLink(string $actionType): string
    {
        $agencyPhone = config('pqrs.agency_phone', '573137915029');
        $agencyName  = config('pqrs.agency_name', 'Cartagena Norte Inmobiliaria');

        // 1. RESOLVER TELÉFONO DESTINO
        $phoneClean = match(true) {
            in_array($actionType, [
                'default', 'scheduled', 'work_scheduled_chosen', 'diagnosed', 
                'quoted_notify_agency', 'approved', 'rejected', 'work_reported', 'finished'
            ]) => $agencyPhone,

            in_array($actionType, [
                'quoted_owner', 'quoted_owner_reminder'
            ]) => $this->formatPhone($this->owner?->phone ?? ''),

            in_array($actionType, [
                'assigned_pending_accept', 'scheduledContractor', 
                'work_scheduled_tenant_choice', 'approvedContractor', 'quoted_pending_reminder'
            ]) => $this->formatPhone($this->current_contractor?->phone ?? ''),

            default => $this->formatPhone($this->tenant_phone),
        };

        // 2. DATOS DINÁMICOS
        $token          = $this->token;
        $urlAgenda      = route('pqrs.show-schedule',  ['ticket' => $this->id]) . '?token=' . $token;
        $urlContratista = route('contractors.admin')                             . '?token=' . $token;
        $urlCotizacion  = route('tenant.view-quote',   ['ticket' => $this->id]) . '?token=' . $token;
        $urlPublica     = route('pqrs.public-status',  ['ticket' => $this->id]) . '?token=' . $token;

        $fechaDiag = $this->confirmed_diagnostic_schedule
            ? $this->confirmed_diagnostic_schedule->confirmed_at->format('d/m/Y h:i A')
            : 'la fecha seleccionada en plataforma';

        $fechaTrabajo = $this->confirmed_work_schedule
            ? $this->confirmed_work_schedule->confirmed_at->format('d/m/Y h:i A')
            : 'la fecha seleccionada en plataforma';

        $motivo = $this->issue_type ?? $this->category ?? 'Requerimiento técnico';

        // 3. MENSAJES
        $message = match($actionType) {
            'default' => "Hola *{$agencyName}*, acabo de registrar el radicado *{$this->ticket_number}* para el inmueble del contrato *{$this->contract_number}*.",
            'validated' => "Hola *{$this->tenant_name}*, tu reporte *{$this->ticket_number}* por *{$motivo}* fue recibido y validado por *{$agencyName}*.",
            'assigned_pending_accept' => "Hola *{$this->current_contractor?->name}*, tienes un nuevo caso disponible (#{$this->ticket_number}). Por favor acéptalo aquí: \n{$urlContratista}",
            'visitScheduled' => "Hola *{$this->tenant_name}*, por favor selecciona el horario para la *visita de diagnóstico* del reporte *{$this->ticket_number}*: \n{$urlAgenda}",
            'scheduled' => "Hola *{$agencyName}*, el arrendatario del radicado *{$this->ticket_number}* ya eligió fecha de diagnóstico: *{$fechaDiag}*.",
            'scheduledContractor' => "Hola *{$this->current_contractor?->name}*, el arrendatario eligió fecha de *Diagnóstico* para el ticket *{$this->ticket_number}*.\n*Motivo:* {$motivo}\n*Fecha:* {$fechaDiag}\nConfirma aquí: \n{$urlContratista}",
            'scheduledSuccessContractor' => "Hola *{$this->tenant_name}*, el técnico confirmó la visita de diagnóstico para el *{$fechaDiag}*.",
            'diagnosed' => "Hola *{$agencyName}*, realicé el diagnóstico del radicado *{$this->ticket_number}* y procedo a cotizar.",
            'quoted_pending_reminder' => "Hola *{$this->current_contractor?->name}*, tienes pendiente la cotización del radicado *{$this->ticket_number}*: \n{$urlContratista}",
            
            // AGREGADO CASO FALTANTE:
            'quoted_notify_agency' => "Hola *{$agencyName}*, he cargado la cotización para el radicado *{$this->ticket_number}*.",
            
            'quoted_owner' => "Hola *{$this->owner?->name}*, revise la cotización del radicado *{$this->ticket_number}* ({$motivo}) aquí: \n{$urlCotizacion}",
            'quoted_owner_reminder' => "Hola *{$this->owner?->name}*, recordatorio de cotización pendiente del ticket *{$this->ticket_number}*: \n{$urlCotizacion}",
            'approved' => "Hola *{$agencyName}*, he aprobado la cotización del radicado *{$this->ticket_number}*.",
            'rejected' => "Hola *{$agencyName}*, he rechazado el presupuesto del radicado *{$this->ticket_number}*.",
            'approvedContractor' => "Hola *{$this->current_contractor?->name}*, el presupuesto de *{$this->ticket_number}* fue *aprobado*. Se habilitará la agenda para el trabajo.",
            'workScheduled' => "¡Buenas noticias *{$this->tenant_name}*! El presupuesto fue aprobado. Elige la fecha para el trabajo final: \n{$urlAgenda}",
            'work_scheduled_chosen' => "Hola *{$agencyName}*, el arrendatario eligió fecha de *trabajo*: *{$fechaTrabajo}*.",
            'work_scheduled_tenant_choice' => "Hola *{$this->current_contractor?->name}*, el arrendatario eligió fecha de *Trabajo* (#{$this->ticket_number}).\n*Fecha:* {$fechaTrabajo}\nConfirma aquí: \n{$urlContratista}",
            'work_scheduled_confirmed' => "Hola *{$this->current_contractor?->name}*, el arrendatario confirmó trabajo con N° *{$this->ticket_number} para la fecha del *{$fechaTrabajo}*. Revisa más información acá {$urlContratista}.",
            'work_reported' => "Hola *{$agencyName}*, el técnico reportó terminación de obra del radicado *{$this->ticket_number}*.",
            'finished' => "Hola *{$agencyName}*, he finalizado el trabajo del radicado *{$this->ticket_number}*.",
            'completed' => "Hola *{$this->tenant_name}*, el técnico terminó el trabajo. ¿Estás conforme? Responde este mensaje.",
            'closed' => "Hola *{$this->tenant_name}*, el radicado *{$this->ticket_number}* ha sido cerrado satisfactoriamente: \n{$urlPublica}",
            default => "Hola, información sobre el radicado *{$this->ticket_number}* — *{$agencyName}*.",
        };

        return "https://wa.me/{$phoneClean}?text=" . urlencode($message);
    }
    
    /**
     * Formatea un número de teléfono colombiano para wa.me.
     * Elimina caracteres no numéricos y agrega indicativo +57 si falta.
     */
    private function formatPhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        return str_starts_with($clean, '57') ? $clean : '57' . $clean;
    }

    //Obtener el estado legible del ticket
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'created' => 'Creado',
            'cancelled' => 'Cancelado',
            'validated' => 'Validado',
            'assigned_pending_accept' => 'Pendiente de aceptación.',
            'assigned' => 'Asignado',
            'visit_scheduled' => 'Visita pendinte de confirmar',
            'visit_scheduled_confirmed' => 'Visita de diagnostico confirmada',
            'diagnosed' => 'Diagnosticado',
            'quoted_pending' => 'Cotizacion pendiente',
            'quoted' => 'Cotizado',
            'approved' => 'Cotización aprobada',
            'rejected' => 'Cotización rechazada',
            'work_scheduled' => 'Trabajo programado',
            'work_scheduled_confirmed' => 'Visita de trabajo confirmada',
            'in_progress' => 'En ejecución',
            'work_reported' => 'Trabajo reportado como finalizado',
            'finished' => 'Finalizado',
            'closed' => 'Cerrado',
            default => 'Desconocido',
        };
    }

    public function logs()
    {
        return $this->hasMany(PqrstLog::class, 'ticket_id');
    }

    //Obtener la prioridad legible del ticket
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'critical' => 'Critica',
            default => 'Desconocida',
        };
    }

    // Relación con todas las asignaciones (historial)
    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class, 'ticket_id');
    }

    // Relación con la asignación más reciente (el contratista actual)
    public function currentAssignment()
    {
        return $this->hasOne(TicketAssignment::class, 'ticket_id')->latestOfMany();
    }

    //Acceso a la cotizacion o cotizaciones relacionadas al ticket
    public function quotes()
    {
        return $this->hasMany(TicketQuote::class, 'ticket_id');
    }

    // Accesor para obtener la cotización aprobada (si existe)
    public function getApprovedQuoteAttribute()
    {
        return $this->quotes()->where('status', 'approved')->first();
    }

    // Accesor para obtener la cotización rechazada (si existe)
    public function getRejectedQuoteAttribute()
    {
        return $this->quotes()->where('status', 'rejected')->first();
    }

    //Accesor para obtener la cotización pendiente (si existe)
    public function getPendingQuoteAttribute()
    {
        return $this->quotes()
            ->where('status', 'pending')
            ->latest()
            ->first();
    }

    //Accessor para saber si el ticket puede generar cotización (solo si fue aceptado por el contratista y no tiene cotización aprobada o pendiente)
    public function getCanQuoteAttribute()
    {
        return $this->status === 'quoted_pending'
            && $this->is_accepted_by_contractor
            && !$this->approved_quote
            && !$this->pending_quote;
    }

    // Accesor para obtener si aún no esta asignado el ticket
    public function getIsAssignedAttribute()
    {
        return !is_null($this->currentAssignment);
    }

    //Relacion con la agenda de visitas
    public function schedules()
    {
        return $this->hasMany(TicketSchedule::class, 'ticket_id');
    }

    //Accesor para saber si el ticket fue aceptado por el contratista
    public function getIsAcceptedByContractorAttribute()
    {
        /* return $this->assignments()->where('status', 'in_progress')->exists(); */
        return $this->assignments->contains(fn($a) => $a->isAccepted());
    }

    //Helper para obtener la asignación actual del ticket
    public function currentSchedule()
    {
        return $this->hasOne(TicketSchedule::class)->latestOfMany();
    }

    //Accesor para saber si la visita programada ya fue confirmada por el inquilino y el contratista
    public function getIsScheduleConfirmedAttribute()
    {
        $schedule = $this->currentSchedule;

        return $schedule
            && $schedule->confirmed_at_tenant
            && $schedule->confirmed_at_contractor;
    }

    public function getCanAssignAttribute()
    {
        return !$this->currentAssignment()->exists();
    }

    public function getCanScheduleAttribute()
    {
        return $this->approved_quote && $this->status !== 'scheduled';
    }

    public function getCanFinishAttribute()
    {
        return $this->status === 'in_progress';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            // Fase Inicial
            'created'                   => 'badge-info',          // Azul claro
            'cancelled'                 => 'badge-dark',          // Gris muy oscuro/Negro
            'validated'                 => 'badge-primary',       // Azul estándar
            
            // Fase Asignación
            'assigned_pending_accept'   => 'bg-warning',          // Amarillo/Naranja (Alerta)
            'assigned'                  => 'bg-orange text-white',// Naranja fuerte
            
            // Fase Diagnóstico
            'visit_scheduled'           => 'bg-indigo',           // Índigo (Programación)
            'visit_scheduled_confirmed' => 'bg-purple',           // Púrpura (Confirmación)
            'diagnosed'                 => 'bg-teal',             // Turquesa (Resultado técnico)
            
            // Fase Cotización
            'quoted_pending'            => 'bg-olive',            // Verde oliva (Esperando presupuesto)
            'quoted'                    => 'badge-warning',       // Amarillo (Revisión de dueño)
            'approved'                  => 'badge-success',       // Verde (Aceptado)
            'rejected'                  => 'badge-danger',        // Rojo (Rechazado)
            
            // Fase Ejecución
            'work_scheduled'            => 'bg-lightblue',        // Azul cielo
            'work_scheduled_confirmed'  => 'bg-navy',             // Azul naval
            'in_progress'               => 'bg-maroon text-white',// Bordó/Marron (Acción)
            'work_reported'             => 'bg-lime',             // Verde lima (Pre-finalizado)
            
            // Fase Cierre
            'finished'                  => 'badge-success',       // Verde estándar
            'closed'                    => 'bg-gray',             // Gris (Archivado)
            
            default                     => 'badge-secondary',     // Gris estándar para desconocidos
        };
    }
}
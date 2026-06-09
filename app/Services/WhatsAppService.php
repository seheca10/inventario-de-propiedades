<?php

namespace App\Services;

use App\Models\PqrsTicket;

/**
 * WhatsAppService
 *
 * NUEVO: Extraído de PqrsTicket::getWhatsAppLink() para cumplir el Single Responsibility Principle.
 * El modelo no debería construir URLs, formatear teléfonos ni redactar mensajes.
 *
 * Uso:
 *   app(WhatsAppService::class)->buildLink($ticket, 'assigned')
 *   // O desde el modelo (compatibilidad): $ticket->getWhatsAppLink('assigned')
 */
class WhatsAppService
{
    /**
     * Número de la agencia inmobiliaria.
     * Centralizado en config/pqrs.php para no estar hardcodeado en el modelo.
     */
    private string $agencyPhone;

    public function __construct()
    {
        $this->agencyPhone = config('pqrs.agency_phone');
    }

    /**
     * Construye la URL completa de WhatsApp para el tipo de acción indicado.
     *
     * @param PqrsTicket $ticket
     * @param string     $actionType
     * @return string    URL: https://wa.me/{phone}?text={encoded_message}
     */
    public function buildLink(PqrsTicket $ticket, string $actionType): string
    {
        $phone   = $this->resolvePhone($ticket, $actionType);
        $message = $this->resolveMessage($ticket, $actionType);

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    /**
     * Determina el número de teléfono destino según el tipo de acción.
     * Agrega el indicativo colombiano (+57) si no lo tiene.
     *
     * Lógica original preservada, solo reorganizada con match() para mayor claridad.
     */
    private function resolvePhone(PqrsTicket $ticket, string $actionType): string
    {
        $raw = match(true) {
            // Arrendatario → agencia
            in_array($actionType, ['default', 'approved'])
                => $this->agencyPhone,

            // Agencia/sistema → contratista
            in_array($actionType, ['scheduledContractor', 'assignedContractorNotify'])
                => $ticket->current_contractor->phone,

            // Agencia/contratista → arrendatario (assigned, quoted, completed, scheduled, scheduledSuccessContractor)
            default
                => $ticket->tenant_phone,
        };

        $clean = preg_replace('/[^0-9]/', '', $raw);

        return str_starts_with($clean, '57') ? $clean : '57' . $clean;
    }

    /**
     * Construye la URL de plataforma según el tipo de acción.
     * Lógica original preservada — solo extraída del método monolítico.
     */
    private function resolvePlatformUrl(PqrsTicket $ticket, string $actionType): string
    {
        $token = $ticket->token; // Usa el accessor del modelo (persistente desde BD)

        return match($actionType) {
            'scheduled', 'scheduledContractor', 'scheduledSuccessContractor'
                => route('pqrs.show-schedule', ['ticket' => $ticket->id]) . $token,

            'assignedContractorNotify'
                => route('contractors.admin') . $token,

            // quoted, approved y cualquier otro que necesite la URL de cotización
            default
                => route('tenant.view-quote', ['ticket' => $ticket->id]) . $token,
        };
    }

    /**
     * Redacta el mensaje de WhatsApp según el tipo de acción.
     * Contenido original 100% preservado.
     */
    private function resolveMessage(PqrsTicket $ticket, string $actionType): string
    {
        $urlPlataforma = $this->resolvePlatformUrl($ticket, $actionType);

        return match($actionType) {
            'assigned' =>
                "Hola *{$ticket->tenant_name}*, te informamos de Cartagena Norte Inmobiliaria que tu reporte " .
                "*{$ticket->ticket_number}* ya fue asignado al contratista. Pronto te contactará.",

            'quoted' =>
                "Hola *{$ticket->tenant_name}*, te habla el contratista de *Cartagena Norte* asignado a tu " .
                "requerimiento *{$ticket->ticket_number}*. He generado una cotización para la reparación. " .
                "\n\n*Puedes revisarla y aprobarla aquí:* \n{$urlPlataforma}",

            'approved' =>
                "Hola *Cartagena Norte Inmobiliaria*, acabo de aprobar la cotización para el ticket " .
                "*{$ticket->ticket_number}* a través de la plataforma.",

            'scheduled' =>
                "Hola *{$ticket->tenant_name}*, hemos programado una visita para tu requerimiento " .
                "*{$ticket->ticket_number}*. Por favor revisa las opciones de fecha y confirma la que mejor " .
                "te convenga en la plataforma: \n{$urlPlataforma}",

            'scheduledContractor' =>
                "Hola *{$ticket->current_contractor->name}*, el arrendatario ha aceptado propuesto una agenda " .
                "de visita para el requerimiento *{$ticket->ticket_number}*. " .
                "Acá tienes la información revisa y confirma por favor: \n{$urlPlataforma}",

            'scheduledSuccessContractor' =>
                "Hola *{$ticket->tenant_name}*, el contratista ha aceptado tu agenda de visita para tu " .
                "requerimiento *{$ticket->ticket_number}*. Acá tienes la información: \n{$urlPlataforma}",

            'completed' =>
                "Hola *{$ticket->tenant_name}*, el trabajo *{$ticket->ticket_number}* ha sido ejecutado. " .
                "Confirma tu conformidad respondiendo este mensaje.",

            'assignedContractorNotify' =>
                "Hola *{$ticket->current_contractor->name}*, el trabajo *{$ticket->ticket_number}* se te ha " .
                "sido asignado. Revisa la información y confirma por favor. \n{$urlPlataforma}",

            // default: arrendatario notifica a la agencia al crear el ticket
            default =>
                "Hola *Cartagena Norte Inmobiliaria*, acabo de registrar el radicado " .
                "*{$ticket->ticket_number}* para el contrato *{$ticket->contract_number}*.",
        };
    }
}
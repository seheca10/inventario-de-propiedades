@component('mail::message')
# Hola, {{ $ticket->tenant_name }}

Te informamos que el contratista asignado ha registrado la cotización para el reporte técnico asociado a tu solicitud.

### Detalles del Ticket
*   **Radicado:** #{{ $ticket->ticket_number }}
*   **Concepto:** {{ $ticket->category }} | {{ $ticket->issue_type }}

### Resumen de la Cotización
A continuación, se detalla el presupuesto propuesto para la reparación:

*   **Descripción:** {{ $quote->description }}
*   **Total a pagar:** ${{ number_format($quote->total_amount, 2) }}

@component('mail::panel')
**Nota importante:** Para proceder con la ejecución de los trabajos, es necesaria tu revisión y aprobación formal a través de nuestra plataforma.
@endcomponent

@component('mail::button', ['url' => config('app.url') . '/pqrs/view-quote/' . $ticket->id])
Revisar y gestionar cotización
@endcomponent

Si tienes alguna duda adicional, puedes contactar directamente al contratista a través de los canales habilitados en el portal.

Este mensaje ha sido generado automáticamente, por favor no respondas a este correo.

Atentamente,
**Equipo de Mantenimiento**
Cartagena Norte Inmobiliaria
@endcomponent
@component('mail::message')
# Hola, {{ $ticket->tenant_name ?? 'Usuario' }}

Hemos recibido tu solicitud de **PQRS** con el número **{{ $ticket->ticket_number }}**.

Nuestro equipo está revisando tu caso. En un plazo no mayor a **48 horas hábiles**, nos pondremos en contacto contigo para brindarte una solución definitiva.
<br>
Este mensaje ha sido generado automáticamente por el sistema, por favor no respondas a este correo.

@component('mail::button', ['url' => config('app.url') . '/pqrs/' . $ticket->id])
Ver estado de mi solicitud
@endcomponent

Gracias,<br>
Cartagena Norte Inmobiliaria
@endcomponent
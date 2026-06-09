@component('mail::message')
# Hola, {{ $ticket->tenant_name ?? 'Usuario' }}

Tu solicitu de **PQRS** con el número **{{ $ticket->ticket_number }}** ha sido asignada a uno de nuestros contratistas. Su nombre es {{ $contractor->user->name }}.

En un plazo no mayor a **48 horas hábiles**, nos pondremos en contacto contigo para brindarte información sobre la cotizacion para llegar a una solución definitiva.
<br><br>
Este mensaje ha sido generado automáticamente por el sistema, por favor no respondas a este correo.

@component('mail::button', ['url' => config('app.url') . '/pqrs/' . $ticket->id])
Ver estado de mi solicitud
@endcomponent

Gracias,<br>
Cartagena Norte Inmobiliaria
@endcomponent
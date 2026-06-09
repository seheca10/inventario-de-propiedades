<?php

namespace App\Observers;

use App\Models\PqrsTicket;
use Illuminate\Support\Str;

/**
 * PqrsTicketObserver
 *
 * NUEVO: Centraliza las lógicas automáticas que antes estaban en boot() del modelo.
 *
 * Antes en PqrsTicket::boot() había:
 *   static::creating(fn($t) => $t->ticket_number = 'PQRS-' . Str::random(6) . '-' . date('Y'))
 *
 * Problemas con ese enfoque:
 *   1. El ticket_number se generaba en creating() → antes de tener un ID,
 *      por eso generateTicketNumber() (que usa el ID) no podía usarse.
 *   2. El formato era aleatorio (Str::random), no auditable ni predecible.
 *   3. Coexistían DOS formatos distintos para el ticket_number en el mismo modelo.
 *
 * Solución: Mover al evento created() (post-insert), donde ya existe el ID,
 * y generar también el access_token una única vez.
 *
 * Registro en AppServiceProvider::boot():
 *   PqrsTicket::observe(PqrsTicketObserver::class);
 */
class PqrsTicketObserver
{
    /**
     * Se ejecuta DESPUÉS de que el ticket es insertado en BD (ya tiene ID).
     * Genera el ticket_number y el access_token una única vez.
     */
    public function created(PqrsTicket $ticket): void
    {
        $ticket->updateQuietly([
            /**
             * Formato único: PQRS-{AÑO}-{ID_CON_PADDING}
             * Ej: PQRS-2026-000125
             *
             * Antes el boot() generaba: PQRS-ABCXYZ-2026 (aleatorio, sin relación con el ID)
             * Ahora es determinista, auditable y correlacionable con el ID de la BD.
             */
            'ticket_number' => $ticket->generateTicketNumber(),

            /**
             * NUEVO: Token persistente en BD.
             * Antes getTokenAttribute() generaba Str::random(40) en cada llamada,
             * produciendo tokens distintos en cada acceso → los enlaces de WhatsApp
             * eran inválidos después de la primera generación.
             * Ahora se genera una única vez y se guarda en la columna `access_token`.
             */
            'access_token' => Str::random(40),
        ]);
    }

    /**
     * Se puede agregar lógica antes de actualizar sin modificar el modelo.
     * Por ejemplo: registrar cambios de estado, limpiar caché, etc.
     */
    public function updating(PqrsTicket $ticket): void
    {
        // Espacio para lógica futura de pre-actualización
        // Ejemplo: if ($ticket->isDirty('status')) { ... }
    }

    /**
     * Se puede agregar lógica antes de eliminar sin modificar el modelo.
     */
    public function deleting(PqrsTicket $ticket): void
    {
        // Espacio para lógica futura antes de eliminación
        // Ejemplo: eliminar archivos adjuntos del storage
    }
}
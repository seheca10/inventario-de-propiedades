<?php

/**
 * config/pqrs.php
 *
 * NUEVO: Centraliza toda la configuración del módulo PQRS que antes estaba
 * hardcodeada dentro de los modelos y servicios.
 *
 * Antes en PqrsTicket::getWhatsAppLink():
 *   $agencyPhone = '573137915029';  // hardcodeado en el modelo
 *
 * Ahora:
 *   config('pqrs.agency_phone')  // desde aquí, configurable por entorno
 *
 * Para sobreescribir en .env:
 *   PQRS_AGENCY_PHONE=573XXXXXXXXX
 *   PQRS_AGENCY_NAME="Mi Inmobiliaria"
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Información de la agencia inmobiliaria
    |--------------------------------------------------------------------------
    */

    // Número de WhatsApp de la agencia con indicativo de Colombia.
    // Antes hardcodeado como '573137915029' en PqrsTicket::getWhatsAppLink()
    'agency_phone' => env('PQRS_AGENCY_PHONE', '573137915029'),

    // Nombre de la agencia usado en mensajes de WhatsApp
    'agency_name' => env('PQRS_AGENCY_NAME', 'Cartagena Norte Inmobiliaria'),

    /*
    |--------------------------------------------------------------------------
    | Tokens de acceso
    |--------------------------------------------------------------------------
    */

    // Longitud del token generado para enlaces de WhatsApp
    'token_length' => env('PQRS_TOKEN_LENGTH', 40),

    /*
    |--------------------------------------------------------------------------
    | Prioridades por defecto
    |--------------------------------------------------------------------------
    */

    'default_priority' => env('PQRS_DEFAULT_PRIORITY', 'medium'),
    'default_category' => env('PQRS_DEFAULT_CATEGORY', 'General'),

    /*
    |--------------------------------------------------------------------------
    | Estados del ticket
    |--------------------------------------------------------------------------
    | Listado de los estados válidos del sistema para referencia y validación.
    */

    'statuses' => [
        'created',
        'validated',
        'assigned',
        'visit_scheduled',
        'diagnosed',
        'quoted',
        'approved',
        'rejected',
        'work_scheduled',
        'in_progress',
        'finished',
        'closed',
    ],

];
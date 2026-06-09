@extends('adminlte::page')

@section('title', 'PQRS ' . $ticket->ticket_number)

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">
        <i class="fas fa-ticket-alt text-primary mr-2"></i>
        Radicado: <span class="font-weight-bold">{{ $ticket->ticket_number }}</span>
    </h1>
    <div class="d-flex align-items-center" style="gap:.5rem;">
        @php
            $priorityClass = match($ticket->priority) {
                'critical' => 'badge-danger',
                'high'     => 'badge-warning text-white',
                'medium'   => 'badge-info',
                default    => 'badge-secondary',
            };
            $priorityIcon = match($ticket->priority) {
                'critical' => 'fa-fire',
                'high'     => 'fa-arrow-up',
                'medium'   => 'fa-minus',
                default    => 'fa-arrow-down',
            };
        @endphp
        <span class="badge {{ $priorityClass }} px-3 py-2 shadow-sm">
            <i class="fas {{ $priorityIcon }} mr-1"></i> {{ $ticket->priority_label }}
        </span>
        @include('partials.pqrs.status-badge', ['ticket' => $ticket])
    </div>
</div>
@stop

@section('content')
<div class="row">

{{-- ══════════════════════════════════════════════════════════════════
     BANNERS CONTEXTUALES — uno activo por estado
══════════════════════════════════════════════════════════════════ --}}

{{-- ── 1. CREATED ────────────────────────────────────────────────── --}}
@if($ticket->status === 'created')
<div class="col-12 mb-3">
    <div class="action-banner banner-warning">
        <div class="ab-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="ab-body">
            <p class="ab-title">Ticket pendiente de validación</p>
            <p class="ab-desc">Completa el número de contrato y vincula al propietario antes de asignar un técnico.</p>
        </div>
        <div class="ab-actions">
            <button class="ab-btn ab-btn-primary" data-toggle="modal" data-target="#modalValidateTicket">
                <i class="fas fa-check-circle mr-1"></i> Completar y Validar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── 2. VALIDATED ──────────────────────────────────────────────── --}}
@if($ticket->status === 'validated')
<div class="col-12 mb-3">
    <div class="action-banner banner-info">
        <div class="ab-icon"><i class="fas fa-user-check"></i></div>
        <div class="ab-body">
            <p class="ab-title">Ticket validado — Asigna el técnico</p>
            <p class="ab-desc">Notifica al arrendatario que su reporte fue recibido y asigna el contratista responsable.</p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('validated') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Notificar arrendatario
            </a>
            <button class="ab-btn ab-btn-primary" data-toggle="modal" data-target="#assignmentTicket">
                <i class="fas fa-user-plus mr-1"></i> Asignar Técnico
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── 3. ASSIGNED_PENDING_ACCEPT ────────────────────────────────── --}}
@if($ticket->status === 'assigned_pending_accept')
<div class="col-12 mb-3">
    <div class="action-banner banner-warning">
        <div class="ab-icon"><i class="fas fa-user-clock"></i></div>
        <div class="ab-body">
            <p class="ab-title">Esperando aceptación del técnico</p>
            <p class="ab-desc">
                <strong>{{ $ticket->current_contractor?->name }}</strong> tiene el caso en su panel pero aún no lo ha aceptado.
            </p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('assigned_pending_accept') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Solicitar aceptación
            </a>
        </div>
    </div>
</div>
@endif

{{-- ── 4. ASSIGNED ───────────────────────────────────────────────── --}}
@if($ticket->status === 'assigned')
<div class="col-12 mb-3">
    <div class="action-banner banner-success">
        <div class="ab-icon"><i class="fas fa-user-tie"></i></div>
        <div class="ab-body">
            <p class="ab-title">Técnico aceptó el caso — Habilita la agenda de diagnóstico</p>
            <p class="ab-desc">Crea las opciones de visita para que el arrendatario elija la fecha más conveniente.</p>
        </div>
        <div class="ab-actions">
            <button class="ab-btn ab-btn-primary" data-toggle="modal" data-target="#modalSchedule-{{ $ticket->id }}">
                <i class="fas fa-calendar-plus mr-1"></i> Habilitar Agenda
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── 5. VISIT_SCHEDULED ────────────────────────────────────────── --}}
@if($ticket->status === 'visit_scheduled')
@php
    $diagSch       = $ticket->schedules()->where('type','diagnostic')->latest()->first();
    $tenantPicked  = $diagSch && $diagSch->confirmed_at;
@endphp
<div class="col-12 mb-3">
    <div class="action-banner {{ $tenantPicked ? 'banner-teal' : 'banner-info' }}">
        <div class="ab-icon">
            <i class="fas {{ $tenantPicked ? 'fa-user-clock' : 'fa-calendar-alt' }}"></i>
        </div>
        <div class="ab-body">
            @if(!$tenantPicked)
                <p class="ab-title">Agenda de diagnóstico enviada</p>
                <p class="ab-desc">Esperando que el arrendatario elija una de las fechas propuestas.</p>
            @else
                <p class="ab-title">¡Arrendatario eligió fecha de diagnóstico!</p>
                <p class="ab-desc">
                    Seleccionó:
                    <strong>{{ \Carbon\Carbon::parse($diagSch->confirmed_at)->translatedFormat('l d \d\e F \a \l\a\s h:i A') }}</strong>.
                    Notifica al técnico para que confirme su asistencia.
                </p>
            @endif
        </div>
        <div class="ab-actions">
            @if(!$tenantPicked)
                <a href="{{ $ticket->getWhatsAppLink('visitScheduled') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                    <i class="fab fa-whatsapp mr-1"></i> Recordar agenda al arrendatario
                </a>
            @else
                <a href="{{ $ticket->getWhatsAppLink('scheduledContractor') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                    <i class="fab fa-whatsapp mr-1"></i> Avisar fecha al técnico
                </a>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ── 6. VISIT_SCHEDULED_CONFIRMED ─────────────────────────────── --}}
@if($ticket->status === 'visit_scheduled_confirmed')
@php
    $diagSch = $ticket->schedules()->where('type','diagnostic')->whereNotNull('confirmed_at')->latest()->first();
@endphp
<div class="col-12 mb-3">
    <div class="action-banner banner-purple">
        <div class="ab-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="ab-body">
            <p class="ab-title">Visita de diagnóstico confirmada por el técnico</p>
            <p class="ab-desc">
                Fecha acordada:
                <strong>{{ $diagSch ? \Carbon\Carbon::parse($diagSch->confirmed_at)->translatedFormat('l d \d\e F \a \l\a\s h:i A') : '—' }}</strong>.
                Recuerda al arrendatario que esté disponible.
            </p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('scheduledSuccessContractor') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Recordar al arrendatario
            </a>
        </div>
    </div>
</div>
@endif

{{-- ── 7. DIAGNOSED ──────────────────────────────────────────────── --}}
@if($ticket->status === 'diagnosed')
@php
    $diagnosedSchedule = $ticket->schedules()->where('type','diagnostic')->whereNotNull('confirmed_at')->first();
@endphp
@if($diagnosedSchedule)
<div class="col-12 mb-3">
    <div class="action-banner banner-purple">
        <div class="ab-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="ab-body">
            <p class="ab-title">Diagnóstico registrado — Revisa antes de habilitar la cotización</p>
            <p class="ab-desc">Verifica el reporte técnico y la firma del arrendatario. Una vez validado, el técnico podrá cotizar.</p>
        </div>
        <div class="ab-actions">
            <a data-toggle="modal" data-target="#reviewSchedule-{{ $ticket->id }}" class="ab-btn ab-btn-primary">
                <i class="fas fa-eye mr-1"></i> Revisar y Validar Visita
            </a>
        </div>
    </div>
</div>
@endif
@endif

{{-- ── 8. QUOTED_PENDING ─────────────────────────────────────────── --}}
@if($ticket->status === 'quoted_pending')
<div class="col-12 mb-3">
    <div class="action-banner banner-warning">
        <div class="ab-icon"><i class="fas fa-calculator"></i></div>
        <div class="ab-body">
            <p class="ab-title">Técnico pendiente de generar la cotización</p>
            <p class="ab-desc">El técnico debe subir el presupuesto desde su panel. Puedes recordárselo.</p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('quoted_pending_reminder') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Recordar al técnico
            </a>
        </div>
    </div>
</div>
@endif

{{-- ── 9. QUOTED ─────────────────────────────────────────────────── --}}
@if($ticket->status === 'quoted')
<div class="col-12 mb-3">
    <div class="action-banner banner-warning">
        <div class="ab-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="ab-body">
            <p class="ab-title">Cotización generada — Enviar al propietario para aprobación</p>
            <p class="ab-desc">
                @if($ticket->owner)
                    Envía el presupuesto a <strong>{{ $ticket->owner->name }}</strong> para que lo apruebe o rechace.
                @else
                    <span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>Sin propietario vinculado — vincúlalo primero.</span>
                @endif
            </p>
        </div>
        <div class="ab-actions">
            @if($ticket->owner)
                <a href="{{ $ticket->getWhatsAppLink('quoted_owner') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                    <i class="fab fa-whatsapp mr-1"></i> Enviar al propietario
                </a>
                <a href="{{ $ticket->getWhatsAppLink('quoted_owner_reminder') }}" target="_blank" class="ab-btn ab-btn-secondary">
                    <i class="fas fa-redo mr-1"></i> Recordatorio
                </a>
            @else
                <button class="ab-btn ab-btn-warning" data-toggle="modal" data-target="#modalValidateTicket">
                    <i class="fas fa-user-plus mr-1"></i> Vincular propietario
                </button>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ── 10. APPROVED ──────────────────────────────────────────────── --}}
@if($ticket->status === 'approved')
<div class="col-12 mb-3">
    <div class="action-banner banner-success">
        <div class="ab-icon"><i class="fas fa-thumbs-up"></i></div>
        <div class="ab-body">
            <p class="ab-title">¡Presupuesto aprobado por el propietario!</p>
            <p class="ab-desc">Notifica al técnico la aprobación y habilita la agenda para el trabajo de reparación.</p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('approvedContractor') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Notificar al técnico
            </a>
            <button class="ab-btn ab-btn-primary" data-toggle="modal" data-target="#modalSchedule-{{ $ticket->id }}">
                <i class="fas fa-calendar-plus mr-1"></i> Habilitar Agenda de Trabajo
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── 11. REJECTED ──────────────────────────────────────────────── --}}
@if($ticket->status === 'rejected')
<div class="col-12 mb-3">
    <div class="action-banner banner-danger">
        <div class="ab-icon"><i class="fas fa-times-circle"></i></div>
        <div class="ab-body">
            <p class="ab-title">Cotización rechazada por el propietario</p>
            <p class="ab-desc">Motivo: <em>{{ $ticket->rejected_quote?->rejection_reason ?? 'No especificado' }}</em>. Contacta al técnico para revisar el presupuesto.</p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('quoted_pending_reminder') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Contactar técnico
            </a>
        </div>
    </div>
</div>
@endif

{{-- ── 12. WORK_SCHEDULED ────────────────────────────────────────── --}}
@if($ticket->status === 'work_scheduled')
@php
    $workSch          = $ticket->schedules()->where('type','work')->latest()->first();
    $workTenantPicked = $workSch && $workSch->confirmed_at;
@endphp
<div class="col-12 mb-3">
    <div class="action-banner {{ $workTenantPicked ? 'banner-teal' : 'banner-info' }}">
        <div class="ab-icon">
            <i class="fas {{ $workTenantPicked ? 'fa-check-circle' : 'fa-tools' }}"></i>
        </div>
        <div class="ab-body">
            @if(!$workTenantPicked)
                <p class="ab-title">Agenda de trabajo enviada — Esperando confirmación del arrendatario</p>
                <p class="ab-desc">El arrendatario debe elegir la fecha de entre las opciones enviadas para la ejecución del trabajo.</p>
            @else
                <p class="ab-title">¡Arrendatario confirmó la fecha de trabajo!</p>
                <p class="ab-desc">
                    Seleccionó:
                    <strong>{{ \Carbon\Carbon::parse($workSch->confirmed_at)->translatedFormat('l d \d\e F \a \l\a\s h:i A') }}</strong>.
                    Notifica al técnico para que esté listo.
                </p>
            @endif
        </div>
        <div class="ab-actions">
            @if(!$workTenantPicked)
                <a href="{{ $ticket->getWhatsAppLink('workScheduled') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                    <i class="fab fa-whatsapp mr-1"></i> Enviar agenda al arrendatario
                </a>
            @else
                <a href="{{ $ticket->getWhatsAppLink('work_scheduled_tenant_choice') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                    <i class="fab fa-whatsapp mr-1"></i> Avisar fecha al técnico
                </a>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ── 13. WORK_SCHEDULED_CONFIRMED ─────────────────────────────── --}}
@if($ticket->status === 'work_scheduled_confirmed')
@php
    $workSch = $ticket->schedules()->where('type','work')->whereNotNull('confirmed_at')->latest()->first();
@endphp
<div class="col-12 mb-3">
    <div class="action-banner banner-teal">
        <div class="ab-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="ab-body">
            <p class="ab-title">Visita de trabajo confirmada por el arrendatario</p>
            <p class="ab-desc">
                Fecha:
                <strong>{{ $workSch ? \Carbon\Carbon::parse($workSch->confirmed_at)->translatedFormat('l d \d\e F \a \l\a\s h:i A') : '—' }}</strong>.
                Recuerda al arrendatario que esté disponible.
            </p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('work_scheduled_confirmed') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Avisa al contratista
            </a>
        </div>
    </div>
</div>
@endif

{{-- ── 14. IN_PROGRESS ───────────────────────────────────────────── --}}
@if($ticket->status === 'in_progress')
<div class="col-12 mb-3">
    <div class="action-banner banner-orange">
        <div class="ab-icon"><i class="fas fa-hammer"></i></div>
        <div class="ab-body">
            <p class="ab-title">Trabajo en ejecución</p>
            <p class="ab-desc">El técnico está realizando el trabajo. Cuando finalice lo reportará desde su panel y el ticket pasará a <em>Trabajo reportado</em>.</p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('assigned_pending_accept') }}" target="_blank" class="ab-btn ab-btn-secondary">
                <i class="fab fa-whatsapp mr-1"></i> Contactar técnico
            </a>
        </div>
    </div>
</div>
@endif

{{-- ── 15. WORK_REPORTED ─────────────────────────────────────────── --}}
@if($ticket->status === 'work_reported')
<div class="col-12 mb-3">
    <div class="action-banner banner-success">
        <div class="ab-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="ab-body">
            <p class="ab-title">Trabajo reportado como finalizado — Verificar y cerrar</p>
            <p class="ab-desc">El técnico reportó que terminó. Confirma la conformidad del arrendatario y el pago antes de cerrar.</p>
        </div>
        <div class="ab-actions">
            <a href="{{ $ticket->getWhatsAppLink('completed') }}" target="_blank" class="ab-btn ab-btn-whatsapp">
                <i class="fab fa-whatsapp mr-1"></i> Confirmar conformidad
            </a>
            <button class="ab-btn ab-btn-success" data-toggle="modal" data-target="#modalCloseTicket-{{ $ticket->id }}">
                <i class="fas fa-archive mr-1"></i> Cerrar Ticket
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── 16. FINISHED ──────────────────────────────────────────────── --}}
@if($ticket->status === 'finished')
<div class="col-12 mb-3">
    <div class="action-banner banner-success">
        <div class="ab-icon"><i class="fas fa-check-double"></i></div>
        <div class="ab-body">
            <p class="ab-title">Proceso finalizado — Listo para cierre definitivo</p>
            <p class="ab-desc">Todo está verificado. Procede al cierre del ticket con el resumen de satisfacción y pago.</p>
        </div>
        <div class="ab-actions">
            <button class="ab-btn ab-btn-success" data-toggle="modal" data-target="#modalCloseTicket-{{ $ticket->id }}">
                <i class="fas fa-archive mr-1"></i> Cerrar Ticket
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── 17. CLOSED ────────────────────────────────────────────────── --}}
@if($ticket->status === 'closed')
<div class="col-12 mb-3">
    <div class="closed-banner">
        <div class="closed-left">
            <div class="closed-icon"><i class="fas fa-archive"></i></div>
            <div>
                <p class="closed-title">Ticket cerrado</p>
                <p class="closed-date">
                    {{ $ticket->closed_at
                        ? \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y \a \l\a\s H:i')
                        : $ticket->updated_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
        @if($ticket->rating)
        <div class="closed-stars-wrap">
            <p class="closed-stars-label">Satisfacción del arrendatario</p>
            <div class="closed-stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star" style="color:{{ $i <= $ticket->rating->rating ? '#f59e0b' : '#d1d5db' }};"></i>
                @endfor
            </div>
            <small class="font-weight-bold text-muted">{{ $ticket->rating->rating }}/5 — {{ $ticket->rating->rating_label }}</small>
        </div>
        @endif
        <div class="closed-actions">
            <a href="{{ $ticket->getWhatsAppLink('closed') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="fab fa-whatsapp mr-1 text-success"></i> Notificar cierre al arrendatario
            </a>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════════
     CONTENIDO PRINCIPAL
══════════════════════════════════════════════════════════════════ --}}
<div class="col-md-8">

    {{-- ── INFO GENERAL ────────────────────────────────────────────── --}}
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header border-0">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-info-circle mr-2 text-primary"></i>Información General
            </h3>
            @if(in_array($ticket->status, ['created','validated']))
            <div class="card-tools">
                <button class="btn btn-xs btn-outline-primary" data-toggle="modal" data-target="#modalValidateTicket">
                    <i class="fas fa-edit mr-1"></i> Editar
                </button>
            </div>
            @endif
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-md-5" style="border-right:1px solid #f0f0f0;">
                    <div class="info-item">
                        <label>Categoría</label>
                        <span class="text-white">{{ $ticket->category ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Motivo</label>
                        <span class="text-primary font-weight-bold">{{ $ticket->issue_type ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Propietario</label>
                        @if($ticket->owner)
                            <span class="text-white"><i class="fas fa-user-tie text-success mr-1"></i>{{ $ticket->owner->name }}</span>
                        @else
                            <span class="text-danger small"><i class="fas fa-exclamation-circle mr-1"></i>Sin vincular</span>
                        @endif
                    </div>
                    <div class="info-item">
                        <label>Contrato</label>
                        @if($ticket->contract_number)
                            <span class="badge badge-secondary px-2"># {{ $ticket->contract_number }}</span>
                        @else
                            <span class="text-danger small">Sin número</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-7 pl-4">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="info-label">Arrendatario</label>
                            <p class="mb-0 font-weight-bold"><i class="fas fa-user mr-1 text-muted"></i>{{ $ticket->tenant_name }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="info-label">Teléfono</label>
                            <p class="mb-0 font-weight-bold">{{ $ticket->tenant_phone }}</p>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="info-label">Email</label>
                            <p class="mb-0 small text-muted">{{ $ticket->tenant_email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 p-3 rounded" style="background:#f8f9fa; border-left:3px solid #dee2e6;">
                <label class="info-label">Descripción del reporte</label>
                <p class="mb-0 text-dark">{{ $ticket->description ?? 'Sin descripción proporcionada.' }}</p>
            </div>
        </div>
    </div>

    {{-- ── RESUMEN DE CIERRE (solo si está cerrado) ───────────────── --}}
    @if($ticket->status === 'closed' && ($ticket->rating || $ticket->closure))
    <div class="card shadow-sm" style="border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:1.25rem;">
        <div class="card-header" style="background:#1a3c5e; color:#fff; padding:.85rem 1.25rem;">
            <h3 class="card-title font-weight-bold mb-0">
                <i class="fas fa-clipboard-check mr-2 text-success"></i>Resumen del Cierre
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="d-flex" style="min-height:180px;">
                @if($ticket->rating)
                <div class="p-4" style="flex:1; border-right:1px solid #f1f5f9;">
                    <p class="info-label mb-3">Satisfacción del arrendatario</p>
                    <div style="font-size:2rem; letter-spacing:4px; line-height:1; margin-bottom:.6rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="color:{{ $i <= $ticket->rating->rating ? '#f59e0b' : '#e5e7eb' }};"></i>
                        @endfor
                    </div>
                    @php
                        $rc = match($ticket->rating->rating) {
                            5 => ['#d1fae5','#065f46'],
                            4 => ['#dbeafe','#1e40af'],
                            3 => ['#fef9c3','#854d0e'],
                            2 => ['#fee2e2','#991b1b'],
                            default => ['#f3f4f6','#374151'],
                        };
                    @endphp
                    <span class="badge px-3 py-2 mb-3" style="background:{{ $rc[0] }}; color:{{ $rc[1] }}; font-size:.82rem;">
                        {{ $ticket->rating->rating }}/5 — {{ $ticket->rating->rating_label }}
                    </span>
                    @if($ticket->rating->comment)
                    <div class="p-3 rounded" style="background:#fffbeb; border-left:3px solid #f59e0b;">
                        <small class="d-block info-label mb-1">Comentario</small>
                        <p class="mb-0 small font-italic">"{{ $ticket->rating->comment }}"</p>
                    </div>
                    @endif
                </div>
                @endif

                @if($ticket->closure)
                <div class="p-4" style="flex:1;">
                    <p class="info-label mb-3">Cierre financiero</p>
                    <div class="closure-row">
                        <span>Costo final</span>
                        <strong class="text-success" style="font-size:1.1rem;">$ {{ number_format($ticket->closure->final_cost, 0, ',', '.') }}</strong>
                    </div>
                    @if($ticket->approved_quote)
                    <div class="closure-row">
                        <span>Cotización aprobada</span>
                        <span>$ {{ number_format($ticket->approved_quote->total_amount, 0, ',', '.') }}</span>
                    </div>
                    @php $diff = $ticket->closure->final_cost - ($ticket->approved_quote->total_amount ?? 0); @endphp
                    @if($diff != 0)
                    <div class="closure-row">
                        <span>Diferencia</span>
                        <strong class="{{ $diff > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $diff > 0 ? '+' : '' }}$ {{ number_format(abs($diff), 0, ',', '.') }}
                        </strong>
                    </div>
                    @endif
                    @endif
                    <div class="closure-row">
                        <span>Pago</span>
                        @if($ticket->closure->payment_confirmed ?? false)
                            <span class="badge badge-success">Confirmado</span>
                        @else
                            <span class="badge badge-warning text-dark">Pendiente</span>
                        @endif
                    </div>
                    <div class="closure-row">
                        <span>Cerrado por</span>
                        <strong>{{ $ticket->closure->closed_by }}</strong>
                    </div>
                    @if($ticket->closure->summary)
                    <div class="mt-3 p-3 rounded" style="background:#f0fdf4; border-left:3px solid #28a745;">
                        <small class="d-block info-label mb-1">Notas del operador</small>
                        <p class="mb-0 small">{{ $ticket->closure->summary }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ── COTIZACIÓN ───────────────────────────────────────────────── --}}
    <div class="card card-outline {{ $ticket->approved_quote ? 'card-success' : 'card-warning' }} shadow-sm">
        <div class="card-header border-0">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-file-invoice-dollar mr-2"></i>Estado de la Cotización
            </h3>
            <div class="card-tools">
                @if($ticket->approved_quote)
                    <span class="badge badge-success px-3">Aprobada</span>
                @elseif($ticket->status === 'quoted')
                    <span class="badge badge-warning px-3 text-dark">En revisión</span>
                @elseif($ticket->rejected_quote)
                    <span class="badge badge-danger px-3">Rechazada</span>
                @elseif($ticket->status === 'quoted_pending')
                    <span class="badge badge-secondary px-3">Pendiente técnico</span>
                @else
                    <span class="badge badge-secondary px-3">Sin registrar</span>
                @endif
            </div>
        </div>
        <div class="card-body pt-0">
            @if($ticket->approved_quote)
                @include('partials.pqrs.quote-detail', ['quote' => $ticket->approved_quote, 'type' => 'approved'])
            @elseif($ticket->rejected_quote)
                @include('partials.pqrs.quote-detail', ['quote' => $ticket->rejected_quote, 'type' => 'rejected'])
            @else
                <div class="text-center py-4 border rounded bg-light">
                    <i class="fas fa-calculator fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0 small font-weight-bold">Sin cotización registrada todavía.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── HISTORIAL ─────────────────────────────────────────────────── --}}
    <div class="card card-outline card-dark shadow-sm">
        <div class="card-header border-0">
            <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-2"></i>Registro de Actividad</h3>
        </div>
        <div class="card-body p-0 overflow-auto" style="max-height:380px;">
            <table class="table table-sm table-hover mb-0" style="font-size:.82rem;">
                <thead style="background:#2d3748; color:#fff; position:sticky; top:0;">
                    <tr>
                        <th class="pl-3 py-2">Evento</th>
                        <th class="py-2">Por</th>
                        <th class="text-right pr-3 py-2">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ticket->logs as $log)
                    <tr>
                        <td class="pl-3 py-2 font-weight-bold">{{ $log->description }}</td>
                        <td class="py-2 text-muted">{{ $log->user->name ?? $log->source ?? 'Sistema' }}</td>
                        <td class="text-right pr-3 py-2 text-muted">
                            {{ $log->created_at->format('d/m/Y H:i') }}<br>
                            <small>{{ $log->created_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">Sin movimientos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>{{-- /col-md-8 --}}

{{-- ══════════════════════════════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════════════════════════════ --}}
<div class="col-md-4">

    {{-- Estado + asignados --}}
    <div class="card shadow-sm" style="border-radius:12px; overflow:hidden; border:1px solid #e2e8f0; margin-bottom:1rem;">
        <div style="background:#1a3c5e; padding:1rem 1.25rem;">
            <p class="text-white text-uppercase mb-1" style="font-size:.65rem; letter-spacing:.1em; opacity:.7;">Estado actual</p>
            <span class="badge {{ $ticket->status_color }} px-3 py-2" style="font-size:.9rem; border-radius:20px;">
                {{ $ticket->status_label }}
            </span>
        </div>
        <div class="card-body p-3">
            <div class="sidebar-info-row">
                <i class="fas fa-hard-hat text-warning mr-2"></i>
                <div>
                    <small class="info-label d-block">Técnico</small>
                    <span class="font-weight-bold">{{ $ticket->current_contractor?->name ?? 'Sin asignar' }}</span>
                </div>
            </div>
            <div class="sidebar-info-row">
                <i class="fas fa-user-tie text-success mr-2"></i>
                <div>
                    <small class="info-label d-block">Propietario</small>
                    <span class="font-weight-bold">{{ $ticket->owner?->name ?? 'Pendiente' }}</span>
                </div>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Creado</span>
                <span>{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="d-flex justify-content-between small text-muted">
                <span>Actualizado</span>
                <span>{{ $ticket->updated_at->diffForHumans() }}</span>
            </div>
            @if($ticket->closed_at)
            <div class="d-flex justify-content-between small text-muted mt-1">
                <span>Cerrado</span>
                <span>{{ \Carbon\Carbon::parse($ticket->closed_at)->format('d/m/Y H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Pipeline --}}
    <div class="card shadow-sm" style="border-radius:12px; border:1px solid #e2e8f0; margin-bottom:1rem;">
        <div class="card-header border-0" style="background:#f8fafc;">
            <h3 class="card-title font-weight-bold text-sm text-gray"><i class="fas fa-route mr-2 text-primary"></i>Progreso del Proceso</h3>
        </div>
        <div class="card-body p-3">
            @php
                $pipeline = [
                    'created'                   => ['icon'=>'fa-plus-circle',        'label'=>'Radicado',              'phase'=>'Inicio'],
                    'validated'                 => ['icon'=>'fa-check-circle',       'label'=>'Validado',              'phase'=>'Inicio'],
                    'assigned_pending_accept'   => ['icon'=>'fa-user-clock',         'label'=>'Aceptación técnica',    'phase'=>'Asignación'],
                    'assigned'                  => ['icon'=>'fa-user-check',         'label'=>'Técnico asignado',      'phase'=>'Asignación'],
                    'visit_scheduled'           => ['icon'=>'fa-calendar-alt',       'label'=>'Agenda diagnóstico',    'phase'=>'Diagnóstico'],
                    'visit_scheduled_confirmed' => ['icon'=>'fa-calendar-check',     'label'=>'Cita confirmada',       'phase'=>'Diagnóstico'],
                    'diagnosed'                 => ['icon'=>'fa-search',             'label'=>'Inspección realizada',  'phase'=>'Diagnóstico'],
                    'quoted_pending'            => ['icon'=>'fa-calculator',         'label'=>'En cotización',         'phase'=>'Presupuesto'],
                    'quoted'                    => ['icon'=>'fa-file-invoice-dollar','label'=>'Presupuesto enviado',   'phase'=>'Presupuesto'],
                    'approved'                  => ['icon'=>'fa-thumbs-up',          'label'=>'Aprobado',              'phase'=>'Presupuesto'],
                    'work_scheduled'            => ['icon'=>'fa-calendar-plus',      'label'=>'Agenda trabajo',        'phase'=>'Ejecución'],
                    'work_scheduled_confirmed'  => ['icon'=>'fa-calendar-check',     'label'=>'Trabajo confirmado',    'phase'=>'Ejecución'],
                    'in_progress'               => ['icon'=>'fa-hammer',             'label'=>'En ejecución',          'phase'=>'Ejecución'],
                    'work_reported'             => ['icon'=>'fa-clipboard-check',    'label'=>'Obra reportada',        'phase'=>'Cierre'],
                    'finished'                  => ['icon'=>'fa-check-double',       'label'=>'Finalizado',            'phase'=>'Cierre'],
                    'closed'                    => ['icon'=>'fa-archive',            'label'=>'Cerrado',               'phase'=>'Cierre'],
                ];
                $statuses   = array_keys($pipeline);
                $currentIdx = array_search($ticket->status, $statuses) ?: 0;
                $phases = [];
                foreach($pipeline as $st => $info) {
                    $phases[$info['phase']][] = $st;
                }
            @endphp
            @foreach($phases as $phaseName => $phaseStatuses)
            <div class="mb-3">
                <p class="info-label mb-1">{{ $phaseName }}</p>
                @foreach($phaseStatuses as $st)
                @php
                    $idx       = array_search($st, $statuses);
                    $isDone    = $idx < $currentIdx;
                    $isCurrent = $st === $ticket->status;
                    $step      = $pipeline[$st];
                @endphp
                <div class="d-flex align-items-center mb-1 pl-1">
                    <i class="fas {{ $step['icon'] }} mr-2
                       {{ $isCurrent ? 'text-primary' : ($isDone ? 'text-success' : 'text-muted') }}"
                       style="width:14px; font-size:.78rem;"></i>
                    <span class="small {{ $isCurrent ? 'font-weight-bold text-primary' : ($isDone ? 'text-success' : 'text-muted') }}">
                        {{ $step['label'] }}
                        @if($isCurrent)<span class="badge badge-primary ml-1" style="font-size:.6rem;">Actual</span>@endif
                        @if($isDone)<i class="fas fa-check ml-1" style="font-size:.62rem;"></i>@endif
                    </span>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <div class="card card-outline card-warning shadow-sm" style="border-radius:12px;">
        <div class="card-header border-0"><h3 class="card-title font-weight-bold text-sm"><i class="fas fa-bolt mr-2 text-warning"></i>Acciones Rápidas</h3></div>
        <div class="card-body p-2">
            <div class="d-flex flex-column" style="gap:.35rem;">
                @if($ticket->status === 'created')
                    <button class="btn btn-warning btn-block text-left btn-sm" data-toggle="modal" data-target="#modalValidateTicket">
                        <i class="fas fa-edit mr-2"></i> Completar y Validar
                    </button>
                @endif
                @if($ticket->status === 'validated' && !$ticket->is_assigned)
                    <button class="btn btn-info btn-block text-left btn-sm" data-toggle="modal" data-target="#assignmentTicket">
                        <i class="fas fa-user-plus mr-2"></i> Asignar Técnico
                    </button>
                @endif
                @if(in_array($ticket->status, ['assigned','approved']))
                    <button class="btn bg-indigo text-white btn-block text-left btn-sm" data-toggle="modal" data-target="#modalSchedule-{{ $ticket->id }}">
                        <i class="fas fa-calendar-plus mr-2"></i> Habilitar Agenda
                    </button>
                @endif
                @if(in_array($ticket->status, ['work_reported','finished']))
                    <button class="btn btn-success btn-block text-left btn-sm" data-toggle="modal" data-target="#modalCloseTicket-{{ $ticket->id }}">
                        <i class="fas fa-archive mr-2"></i> Cerrar Ticket
                    </button>
                @endif
                @if(in_array($ticket->status, ['created','validated']))
                    <button class="btn btn-outline-secondary btn-block text-left btn-sm" data-toggle="modal" data-target="#modalValidateTicket">
                        <i class="fas fa-edit mr-2"></i> Editar Información
                    </button>
                @endif
                @if($ticket->status !== 'closed')
                    <button class="btn btn-outline-danger btn-block text-left btn-sm">
                        <i class="fas fa-trash mr-2"></i> Eliminar Ticket
                    </button>
                @endif
            </div>
        </div>
    </div>

</div>{{-- /col-md-4 --}}

{{-- MODALES --}}
@include('partials.pqrs.ticket-validate')
@include('partials.pqrs.assignment')
@include('partials.pqrs.contractors.modal-schedule')
@include('partials.pqrs.modal-close-ticket')
@php
    $diagnosedSchedule = $ticket->schedules()->where('type','diagnostic')->whereNotNull('confirmed_at')->first();
@endphp
@include('partials.pqrs.contractors.modal-review-schedule', ['diagnosedSchedule' => $diagnosedSchedule])

</div>
@stop

@section('css')
<style>
/* ── Colores utilitarios ── */
.bg-olive  { background-color:#3d9970 !important; }
.bg-teal   { background-color:#20c997 !important; }
.bg-indigo { background-color:#6610f2 !important; }
.bg-purple { background-color:#6f42c1 !important; }
.bg-orange { background-color:#fd7e14 !important; }
.text-orange { color:#fd7e14 !important; }

/* ── Banners de acción ── */
.action-banner {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: .9rem 1.2rem;
    border-radius: 12px;
    border: 1px solid transparent;
    flex-wrap: wrap;
}
.ab-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 1.2rem;
    color: #fff;
}
.ab-body { flex: 1; min-width: 0; }
.ab-title { font-weight: 700; font-size: .92rem; margin: 0 0 2px; }
.ab-desc  { font-size: .78rem; color: #6b7280; margin: 0; }
.ab-actions { display: flex; gap: .4rem; flex-wrap: wrap; align-items: center; }

/* Variantes de banner */
.banner-warning  { background:#fffbeb; border-color:#fcd34d; }
.banner-warning  .ab-icon { background:#f59e0b; }
.banner-warning  .ab-title { color:#92400e; }

.banner-info     { background:#eff6ff; border-color:#93c5fd; }
.banner-info     .ab-icon { background:#3b82f6; }
.banner-info     .ab-title { color:#1e40af; }

.banner-success  { background:#f0fdf4; border-color:#86efac; }
.banner-success  .ab-icon { background:#22c55e; }
.banner-success  .ab-title { color:#166534; }

.banner-danger   { background:#fff1f2; border-color:#fca5a5; }
.banner-danger   .ab-icon { background:#ef4444; }
.banner-danger   .ab-title { color:#991b1b; }

.banner-purple   { background:#faf5ff; border-color:#c4b5fd; }
.banner-purple   .ab-icon { background:#7c3aed; }
.banner-purple   .ab-title { color:#4c1d95; }

.banner-teal     { background:#f0fdfa; border-color:#5eead4; }
.banner-teal     .ab-icon { background:#14b8a6; }
.banner-teal     .ab-title { color:#134e4a; }

.banner-orange   { background:#fff7ed; border-color:#fdba74; }
.banner-orange   .ab-icon { background:#f97316; }
.banner-orange   .ab-title { color:#7c2d12; }

/* Botones del banner */
.ab-btn {
    display: inline-flex; align-items: center;
    padding: 6px 14px; border-radius: 8px;
    font-size: .78rem; font-weight: 700;
    border: none; cursor: pointer; text-decoration: none;
    white-space: nowrap; transition: opacity .15s;
}
.ab-btn:hover { opacity: .85; text-decoration: none; }
.ab-btn-primary   { background:#1a3c5e; color:#fff; }
.ab-btn-success   { background:#22c55e; color:#fff; }
.ab-btn-warning   { background:#f59e0b; color:#fff; }
.ab-btn-secondary { background:#e5e7eb; color:#374151; }
.ab-btn-whatsapp  { background:#25d366; color:#fff; }

/* ── Banner cerrado ── */
.closed-banner {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
    background: #fff; border: 1px solid #e2e8f0;
    border-left: 5px solid #1a3c5e;
    border-radius: 12px; padding: 1rem 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.closed-left  { display: flex; align-items: center; gap: 12px; }
.closed-icon  { width:40px; height:40px; border-radius:10px; background:#1a3c5e; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1rem; }
.closed-title { font-weight:700; color:#1a3c5e; font-size:.95rem; margin:0; }
.closed-date  { color:#6b7280; font-size:.75rem; margin:0; }
.closed-stars-wrap { text-align:center; }
.closed-stars-label { font-size:.68rem; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; margin-bottom:4px; }
.closed-stars { font-size:1.3rem; letter-spacing:2px; }
.closed-actions { display:flex; align-items:center; }

/* ── Info items ── */
.info-item { margin-bottom: .85rem; }
.info-item label, .info-label {
    font-size: .68rem; text-transform: uppercase;
    letter-spacing: .07em; color: #9ca3af;
    display: block; margin-bottom: 2px; font-weight: 700;
}
.info-item span { font-size: .9rem; color: #1f2937; }

/* ── Sidebar ── */
.sidebar-info-row {
    display: flex; align-items: flex-start;
    gap: 10px; padding: .5rem 0;
    border-bottom: 1px solid #f8fafc;
}

/* ── Closure rows ── */
.closure-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .45rem 0; border-bottom: 1px solid #f8fafc;
    font-size: .86rem;
}
.closure-row span:first-child { color: #6b7280; }

/* ── WhatsApp pulse ── */
@@keyframes whatsappPulse {
    0%   { transform:scale(1);    box-shadow:0 0 0 0 rgba(37,211,102,.6); }
    50%  { transform:scale(1.05); }
    70%  { box-shadow:0 0 0 10px rgba(37,211,102,0); }
    100% { transform:scale(1); }
}
.btn-zoom-pulse { animation: whatsappPulse 1.8s infinite; }
</style>
@stop
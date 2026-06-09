@php
    $ticket = $work->ticket;

    // 1. Obtener la visita de DIAGNÓSTICO (Inspección inicial)
    $diagnosticSchedule = $ticket->schedules()
        ->where('type', 'diagnostic')
        ->whereNotNull('confirmed_at')
        ->first();

    // 2. Obtener la visita de TRABAJO (Reparación final)
    $workSchedule = $ticket->schedules()
        ->where('type', 'work')
        ->whereNotNull('confirmed_at')
        ->first();

    // Lógica de validación para HOY
    $isDiagnosticToday = $diagnosticSchedule && \Carbon\Carbon::parse($diagnosticSchedule->confirmed_at)->isToday();
    $isWorkToday = $workSchedule && \Carbon\Carbon::parse($workSchedule->confirmed_at)->isToday();

    // Verificar si ya se registró reporte para evitar duplicados
    $hasDiagnosticReport = $diagnosticSchedule && $diagnosticSchedule->reports()->exists();
    $hasWorkReport = $workSchedule && $workSchedule->reports()->exists();

    // Determinar qué botón de registro mostrar según el estado
    $showRegisterDiagnostic = ($ticket->status === 'visit_scheduled' && $isDiagnosticToday && !$hasDiagnosticReport);
    $showRegisterWork = ($ticket->status === 'work_scheduled' && $isWorkToday && !$hasWorkReport);
@endphp

<div class="card card-outline card-success shadow-sm work-card" data-status="{{ $ticket->status }}">

    {{-- HEADER: Número de radicado y fecha de asignación --}}
    <div class="card-header">
        <h3 class="card-title text-bold">
            <i class="fas fa-hashtag mr-1"></i>
            {{ $ticket->ticket_number }}
        </h3>
        <div class="card-tools">
            <span class="text-muted small">
                <i class="far fa-calendar-alt"></i>
                {{ $work->assigned_at ? \Carbon\Carbon::parse($work->assigned_at)->format('d/m/Y') : 'Sin fecha' }}
            </span>
        </div>
    </div>

    <div class="card-body">

        {{-- Arrendatario y Prioridad --}}
        <div class="row mb-2">
            <div class="col-8">
                <h5 class="text-success text-bold mb-1">{{ $ticket->tenant_name }}</h5>
                <p class="text-muted small mb-0">
                    <i class="fas fa-file-contract mr-1"></i> Contrato: {{ $ticket->contract_number ?? 'N/A' }}
                </p>
            </div>
            <div class="col-4 text-right">
                @php
                    $priorityBadge = match($ticket->priority) {
                        'high', 'critical' => 'badge-danger',
                        'medium' => 'badge-warning',
                        default => 'badge-primary',
                    };
                @endphp
                <span class="badge {{ $priorityBadge }}">
                    {{ $ticket->priority_label }}
                </span>
            </div>
        </div>

        <hr class="my-2">

        {{-- Teléfono de contacto --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="small font-weight-bold">
                <i class="fas fa-phone-alt text-muted mr-1"></i>
                {{ $ticket->tenant_phone }}
            </span>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ticket->tenant_phone) }}" target="_blank" class="btn btn-xs btn-outline-success">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>

        {{-- Categoría y descripción corta --}}
        <div class="bg-light p-2 rounded mb-3" style="border-left: 3px solid #28a745;">
            <p class="mb-0 small text-bold text-uppercase">
                {{ $ticket->category }} | {{ $ticket->issue_type }}
            </p>
            <p class="mb-0 small text-muted text-truncate">
                {{ $ticket->description }}
            </p>
        </div>

        {{-- INFO DE CITAS: Diagnóstico o Trabajo --}}
        @if($ticket->status === 'visit_scheduled' && $diagnosticSchedule)
            <div class="mb-3 p-2 rounded border" style="border-color: #6f42c1 !important; background: {{ $isDiagnosticToday ? '#f3ebff' : '#f8f9fa' }};">
                <small class="d-block font-weight-bold mb-1" style="color: #6f42c1;">
                    <i class="fas fa-search mr-1"></i> Visita de Diagnóstico:
                    @if($isDiagnosticToday) <span class="badge badge-purple text-white ml-1">¡HOY!</span> @endif
                </small>
                <span class="font-weight-bold" style="font-size:.9rem; color: #4b2c85;">
                    {{ \Carbon\Carbon::parse($diagnosticSchedule->confirmed_at)->translatedFormat('l d \d\e F') }}
                    — {{ \Carbon\Carbon::parse($diagnosticSchedule->confirmed_at)->format('h:i A') }}
                </span>
            </div>
        @elseif($ticket->status === 'work_scheduled_confirmed' && $workSchedule)
            <div class="mb-3 p-2 rounded border" style="border-color: #28a745 !important; background: {{ $isWorkToday ? '#f0fff4' : '#f8f9fa' }};">
                <small class="d-block font-weight-bold mb-1" style="color: #28a745;">
                    <i class="fas fa-tools mr-1"></i> Visita de Trabajo:
                    @if($isWorkToday) <span class="badge badge-success ml-1">¡HOY!</span> @endif
                </small>
                <span class="font-weight-bold" style="font-size:.9rem; color: #235e3d;">
                    {{ \Carbon\Carbon::parse($workSchedule->confirmed_at)->translatedFormat('l d \d\e F') }}
                    — {{ \Carbon\Carbon::parse($workSchedule->confirmed_at)->format('h:i A') }}
                </span>
            </div>
        @endif

        {{-- Estado del Ticket y Cotización --}}
        <div class="mb-3">
            <small class="text-muted d-block mb-1">Estado actual:</small>
            <span class="badge {{ $ticket->status_color }} mr-1">
                <i class="fas fa-sync-alt mr-1"></i>
                {{ $ticket->status_label }}
            </span>

            @if(!in_array($ticket->status, ['work_scheduled', 'in_progress', 'finished', 'closed']))
                @if($ticket->approved_quote)
                    <span class="badge badge-success quote-badge">
                        <i class="fas fa-check-square mr-1"></i> Cotización aprobada
                    </span>
                @elseif($ticket->pending_quote)
                    <span class="badge bg-orange text-white quote-badge">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Cotización pendiente
                    </span>
                @elseif($ticket->rejected_quote)
                    <span class="badge badge-danger quote-badge">
                        <i class="fas fa-times-circle mr-1"></i> Cotización rechazada
                    </span>
                @endif
            @endif
        </div>

        {{-- ACCIONES --}}
        <div class="d-flex justify-content-end">
            <div class="btn-group flex-wrap" style="gap:.3rem;">

                {{-- 1. ACEPTAR/INICIAR (Si el ticket solo está validado y no se ha aceptado) --}}
                @if($ticket->status === 'assigned_pending_accept')
                    <form action="{{ route('contractors.accept-ticket', $ticket->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn bg-teal text-white btn-sm font-weight-bold">
                            <i class="fas fa-play mr-1"></i> Aceptar Caso
                        </button>
                    </form>
                @endif

                {{-- 2. REGISTRAR VISITA DE DIAGNÓSTICO (Estado visit_scheduled) --}}
                @if($ticket->status === 'visit_scheduled_confirmed')
                    @if($isDiagnosticToday)
                        <a href="{{ route('contractors.register-schedule-view', $ticket->id) }}"
                           class="btn btn-sm bg-purple text-white font-weight-bold shadow-sm">
                            <i class="fas fa-file-signature mr-1"></i> Registrar Visita
                        </a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled title="Disponible el {{ \Carbon\Carbon::parse($diagnosticSchedule->confirmed_at)->format('d/m/Y') }}">
                            <i class="fas fa-lock mr-1"></i> Registrar Visita
                        </button>
                    @endif
                @endif

                {{-- 3. COTIZAR (Si el sistema permite cotizar en este estado) --}}
                @if($ticket->can_quote)
                    <button data-toggle="modal" data-target="#modalQuote-{{ $ticket->id }}" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Cotizar
                    </button>
                @endif

                {{-- 6. REGISTRAR FINALIZACIÓN DE TRABAJO (Estado work_scheduled) --}}
                @if($ticket->status === 'work_scheduled_confirmed')
                    @if($isWorkToday)
                        <form action="{{ route('start.work-schedule', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success font-weight-bold shadow-sm">
                                <i class="fas fa-check-double mr-1"></i> Iniciar Trabajo
                            </button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled title="Disponible el {{ \Carbon\Carbon::parse($workSchedule->confirmed_at)->format('d/m/Y') }}">
                            <i class="fas fa-lock mr-1"></i> Iniciar Trabajo
                        </button>
                    @endif
                @endif

                {{-- 5. REGISTRAR FINALIZACIÓN DE TRABAJO (Estado work_scheduled) --}}
                @if($ticket->status === 'in_progress')
                    @if($isWorkToday)
                        <a href="{{ route('contractors.register-schedule-view', $ticket->id) }}"
                           class="btn btn-sm btn-success font-weight-bold shadow-sm">
                            <i class="fas fa-check-double mr-1"></i> Finalizar Trabajo
                        </a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled title="Disponible el {{ \Carbon\Carbon::parse($workSchedule->confirmed_at)->format('d/m/Y') }}">
                            <i class="fas fa-lock mr-1"></i> Finalizar Trabajo
                        </button>
                    @endif
                @endif

                {{-- 5. VER DETALLE (Siempre disponible) --}}
                <button data-toggle="modal" data-target="#ticketDetail-{{ $ticket->id }}" class="btn bg-maroon text-white btn-sm font-weight-bold">
                    <i class="fas fa-eye mr-1"></i> Ver Detalle
                </button>

            </div>
        </div>

    </div>
</div>

{{-- Incluir Modales Necesarios --}}
@include('partials.pqrs.contractors.detail', ['work' => $work])
@include('partials.pqrs.contractors.modal-quote', ['work' => $work, 'contractor' => $contractor])

<style>
    .quote-badge { font-size:.75rem; padding: 4px 8px; }
    .bg-purple { background-color:#6f42c1 !important; }
    .bg-teal   { background-color:#20c997 !important; }
    .bg-orange { background-color:#fd7e14 !important; }
    .bg-maroon { background-color:#800000 !important; }
    .badge-purple { background-color: #6f42c1; }
    
    .work-card {
        transition: transform 0.2s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    .work-card:hover {
        transform: translateY(-2px);
    }
    .btn-group .btn {
        border-radius: 4px !important;
        margin-bottom: 2px;
    }
</style>
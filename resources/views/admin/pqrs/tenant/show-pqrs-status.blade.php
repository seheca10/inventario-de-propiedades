<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de tu Solicitud — {{ $ticket->ticket_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        .status-card {
            border-radius: 16px; border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        /* ── Stepper ─────────────────────────────────────────────── */
        .stepper-wrapper {
            display: flex; justify-content: space-between;
            margin: 32px 0; position: relative;
        }
        .stepper-item {
            position: relative; display: flex;
            flex-direction: column; align-items: center;
            flex: 1; z-index: 2;
        }
        .stepper-item::before {
            position: absolute; content: "";
            border-bottom: 2px solid #dee2e6;
            width: 100%; top: 20px; left: -50%; z-index: 1;
        }
        .stepper-item::after {
            position: absolute; content: "";
            border-bottom: 2px solid #dee2e6;
            width: 100%; top: 20px; left: 50%; z-index: 1;
        }
        .stepper-item:first-child::before,
        .stepper-item:last-child::after { content: none; }

        .stepper-number {
            width: 40px; height: 40px; border-radius: 50%;
            background: #fff; border: 2px solid #dee2e6;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 8px; font-weight: 700; font-size: .9rem;
            transition: all .3s;
        }
        .stepper-item.completed .stepper-number {
            background: #28a745; border-color: #28a745; color: #fff;
        }
        .stepper-item.completed::after,
        .stepper-item.completed::before { border-color: #28a745; }
        .stepper-item.active .stepper-number {
            background: #007bff; border-color: #007bff; color: #fff;
            box-shadow: 0 0 0 4px rgba(0,123,255,.2);
        }
        .stepper-item.active::before { border-color: #28a745; }
        .stepper-item.rejected-step .stepper-number {
            background: #dc3545; border-color: #dc3545; color: #fff;
        }
        .step-name {
            font-size: .7rem; font-weight: 600; color: #6c757d;
            text-transform: uppercase; text-align: center;
            line-height: 1.2;
        }
        .stepper-item.completed .step-name,
        .stepper-item.active .step-name { color: #343a40; }

        /* ── Info ───────────────────────────────────────────────── */
        .info-label { font-size: .78rem; color: #adb5bd; text-transform: uppercase; margin-bottom: 2px; }
        .info-value { font-size: .95rem; font-weight: 600; color: #343a40; }

        .timeline-box {
            border-left: 3px solid #007bff; background: #f8f9fa;
            padding: 14px 16px; border-radius: 0 10px 10px 0;
        }

        /* ── Rating stars ───────────────────────────────────────── */
        .star-filled { color: #f59e0b; }
        .star-empty  { color: #dee2e6; }

        /* ── Badge status override para que se vea bien en fondo blanco ── */
        .bg-orange     { background-color: #fd7e14 !important; }
        .bg-indigo     { background-color: #4b5eaa !important; }
        .bg-purple     { background-color: #6f42c1 !important; }
        .bg-teal       { background-color: #20c997 !important; }
        .bg-olive      { background-color: #3d9970 !important; }
        .bg-lightblue  { background-color: #74c0fc !important; }
        .bg-navy       { background-color: #1c3f6e !important; }
        .bg-maroon     { background-color: #800000 !important; }
        .bg-lime       { background-color: #a3e635 !important; color:#1e293b !important; }
        .bg-gray       { background-color: #6c757d !important; }
        .bg-warning    { background-color: #ffc107 !important; color:#212529 !important; }
    </style>
</head>
<body>

@php
    /*
     * STEPPER — agrupa los estados del modelo en 6 fases visuales.
     * Los estados coinciden exactamente con getStatusLabelAttribute() del modelo.
     */
    $steps = [
        [
            'label'    => 'Recibido',
            'icon'     => 'fa-inbox',
            'statuses' => ['created', 'validated'],
        ],
        [
            'label'    => 'Asignado',
            'icon'     => 'fa-user-check',
            'statuses' => ['assigned_pending_accept', 'assigned'],
        ],
        [
            'label'    => 'Visita',
            'icon'     => 'fa-calendar-check',
            'statuses' => ['visit_scheduled', 'visit_scheduled_confirmed', 'diagnosed'],
        ],
        [
            'label'    => 'Cotización',
            'icon'     => 'fa-file-invoice-dollar',
            'statuses' => ['quoted_pending', 'quoted', 'approved', 'rejected'],
        ],
        [
            'label'    => 'Trabajo',
            'icon'     => 'fa-tools',
            'statuses' => ['work_scheduled', 'work_scheduled_confirmed', 'in_progress', 'work_reported'],
        ],
        [
            'label'    => 'Cerrado',
            'icon'     => 'fa-archive',
            'statuses' => ['finished', 'closed'],
        ],
    ];

    $currentStepIndex = 0;
    foreach ($steps as $index => $step) {
        if (in_array($ticket->status, $step['statuses'])) {
            $currentStepIndex = $index;
            break;
        }
    }

    $isRejected = $ticket->status === 'rejected';

    // Última agenda confirmada
    $lastSchedule = $ticket->schedules
        ->whereNotNull('confirmed_at')
        ->sortByDesc('confirmed_at')
        ->first();

    // Agenda de trabajo confirmada
    $workSchedule = $ticket->schedules
        ->where('type', 'work')
        ->whereNotNull('confirmed_at')
        ->sortByDesc('confirmed_at')
        ->first();
@endphp

<div class="container py-5">

    {{-- Header --}}
    <div class="text-center mb-5">
        <img src="{{ asset('assets/images/LOGO-3D-PAGINA-CARTAGENA-NORTE-color.png') }}"
             alt="Logo" height="70" class="mb-3">
        <h2 class="font-weight-bold">Estado de tu Radicado</h2>
        <p class="text-muted small">Consulta el progreso de tu requerimiento en tiempo real</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card status-card">
                <div class="card-body p-4 p-md-5">

                    {{-- Estado actual destacado --}}
                    <div class="text-center mb-4">
                        <p class="info-label mb-2">Estado Actual</p>
                        <span class="badge px-4 py-2 {{ $ticket->status_color }}"
                              style="font-size:1rem; border-radius:30px;">
                            {{ strtoupper($ticket->status_label) }}
                        </span>
                        <h4 class="mt-3 font-weight-bold text-muted">#{{ $ticket->ticket_number }}</h4>
                    </div>

                    {{-- Stepper --}}
                    <div class="stepper-wrapper">
                        @foreach($steps as $index => $step)
                            @php
                                $isDone    = $index < $currentStepIndex;
                                $isCurrent = $index === $currentStepIndex;
                                $isRejStep = $isCurrent && $isRejected;
                                $cls = $isDone ? 'completed' : ($isCurrent ? ($isRejStep ? 'rejected-step' : 'active') : '');
                            @endphp
                            <div class="stepper-item {{ $cls }}">
                                <div class="stepper-number">
                                    @if($isDone)
                                        <i class="fas fa-check"></i>
                                    @elseif($isRejStep)
                                        <i class="fas fa-times"></i>
                                    @else
                                        <i class="fas {{ $step['icon'] }}" style="font-size:.8rem;"></i>
                                    @endif
                                </div>
                                <div class="step-name">{{ $step['label'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-4">

                    {{-- Info detallada --}}
                    <div class="row">

                        {{-- Columna izquierda: detalles del reporte --}}
                        <div class="col-md-6 border-right mb-4 mb-md-0">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-info-circle mr-2"></i>Detalles del Reporte
                            </h6>

                            <div class="mb-3">
                                <p class="info-label">Categoría / Motivo</p>
                                <p class="info-value">
                                    {{ $ticket->category ?? '—' }}
                                    @if($ticket->issue_type)
                                        / {{ $ticket->issue_type }}
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <p class="info-label">Contrato</p>
                                <p class="info-value">#{{ $ticket->contract_number ?? '—' }}</p>
                            </div>

                            @if($ticket->description)
                                <div class="mb-3">
                                    <p class="info-label">Descripción</p>
                                    <p class="text-secondary small">{{ $ticket->description }}</p>
                                </div>
                            @endif

                            {{-- Cotización aprobada si existe --}}
                            @if($ticket->approved_quote)
                                <div class="mt-3 p-3 rounded" style="background:#f0fff4; border:1px solid #bbf7d0;">
                                    <p class="info-label mb-1">Cotización aprobada</p>
                                    <p class="info-value text-success mb-0">
                                        $ {{ number_format($ticket->approved_quote->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Columna derecha: última actualización --}}
                        <div class="col-md-6 pl-md-4">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-clock mr-2"></i>Última Actualización
                            </h6>

                            {{-- CLOSED --}}
                            @if($ticket->status === 'closed')
                                <div class="alert alert-success border-0 shadow-sm mb-3">
                                    <h6 class="font-weight-bold mb-1">
                                        <i class="fas fa-archive mr-2"></i>Servicio Cerrado
                                    </h6>
                                    <p class="small mb-0">
                                        Tu requerimiento fue resuelto exitosamente.
                                        Gracias por confiar en {{ config('pqrs.agency_name') }}.
                                    </p>
                                </div>

                                {{-- Mostrar calificación si existe --}}
                                @if($ticket->rating)
                                    <div class="p-3 rounded" style="background:#fffde7; border:1px solid #fde68a;">
                                        <p class="info-label mb-1">Tu calificación</p>
                                        <div class="mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $ticket->rating->rating ? 'star-filled' : 'star-empty' }}"></i>
                                            @endfor
                                            <span class="small font-weight-bold ml-1">
                                                {{ $ticket->rating->rating_label }}
                                            </span>
                                        </div>
                                        @if($ticket->rating->comment)
                                            <p class="small text-muted mb-0">
                                                "{{ $ticket->rating->comment }}"
                                            </p>
                                        @endif
                                    </div>
                                @endif

                            {{-- REJECTED --}}
                            @elseif($ticket->status === 'rejected')
                                <div class="alert alert-danger border-0 shadow-sm">
                                    <h6 class="font-weight-bold mb-1">
                                        <i class="fas fa-times-circle mr-2"></i>Cotización Rechazada
                                    </h6>
                                    @if($ticket->rejected_quote?->rejection_reason)
                                        <p class="small mb-0">
                                            Motivo: {{ $ticket->rejected_quote->rejection_reason }}
                                        </p>
                                    @else
                                        <p class="small mb-0">
                                            El propietario rechazó el presupuesto. El operativo se pondrá en contacto.
                                        </p>
                                    @endif
                                </div>

                            {{-- WORK SCHEDULED — mostrar fecha del trabajo --}}
                            @elseif($workSchedule && in_array($ticket->status, ['work_scheduled', 'work_scheduled_confirmed', 'in_progress', 'work_reported']))
                                <div class="timeline-box shadow-sm mb-3">
                                    <h6 class="font-weight-bold mb-2">
                                        <i class="fas fa-tools text-primary mr-2"></i>Visita de Trabajo
                                    </h6>
                                    <p class="mb-1">
                                        <i class="far fa-calendar-alt mr-2 text-primary"></i>
                                        {{ $workSchedule->confirmed_at->translatedFormat('l d \d\e F Y') }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="far fa-clock mr-2 text-primary"></i>
                                        {{ $workSchedule->confirmed_at->format('h:i A') }}
                                    </p>
                                    @if($ticket->current_contractor)
                                        <p class="small text-muted mt-2 mb-0">
                                            <i class="fas fa-hard-hat mr-1"></i>
                                            Técnico: {{ $ticket->current_contractor->name }}
                                        </p>
                                    @endif
                                </div>

                            {{-- AGENDAS DE DIAGNÓSTICO --}}
                            @elseif($lastSchedule)
                                <div class="timeline-box shadow-sm mb-3">
                                    <h6 class="font-weight-bold mb-2">
                                        <i class="fas fa-calendar-check text-primary mr-2"></i>
                                        Visita {{ $lastSchedule->type_label }} Confirmada
                                    </h6>
                                    <p class="mb-1">
                                        <i class="far fa-calendar-alt mr-2 text-primary"></i>
                                        {{ $lastSchedule->confirmed_at->translatedFormat('l d \d\e F Y') }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="far fa-clock mr-2 text-primary"></i>
                                        {{ $lastSchedule->confirmed_at->format('h:i A') }}
                                    </p>
                                    @if($ticket->current_contractor)
                                        <p class="small text-muted mt-2 mb-0">
                                            <i class="fas fa-hard-hat mr-1"></i>
                                            Técnico: {{ $ticket->current_contractor->name }}
                                        </p>
                                    @endif
                                </div>

                            {{-- SIN AGENDA AÚN --}}
                            @else
                                <div class="p-3 border rounded text-center" style="background:#f8f9fa;">
                                    <i class="fas fa-hourglass-half fa-2x text-muted mb-2 d-block"></i>
                                    <p class="small text-muted mb-0">
                                        Estamos procesando tu solicitud. Pronto recibirás una fecha de visita.
                                    </p>
                                </div>
                            @endif

                            {{-- Contratista asignado (si aplica y no se mostró arriba) --}}
                            @if($ticket->current_contractor && !$lastSchedule && !$workSchedule && $ticket->status !== 'closed')
                                <div class="mt-3 p-3 rounded" style="background:#f0f9ff; border:1px solid #bae6fd;">
                                    <p class="info-label mb-1">Técnico asignado</p>
                                    <p class="info-value mb-0">
                                        <i class="fas fa-hard-hat text-warning mr-1"></i>
                                        {{ $ticket->current_contractor->name }}
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Botón de soporte --}}
                    <div class="mt-5 text-center">
                        <p class="text-muted small mb-3">¿Tienes alguna duda sobre este proceso?</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('pqrs.agency_phone')) }}"
                           target="_blank"
                           class="btn btn-outline-success px-4 shadow-sm"
                           style="border-radius:30px;">
                            <i class="fab fa-whatsapp mr-2"></i> Contactar Soporte
                        </a>
                    </div>

                </div>
            </div>

            <p class="text-center mt-4 text-muted small">
                Canal de consulta automática de
                <strong>{{ config('pqrs.agency_name') }}</strong>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
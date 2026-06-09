<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agenda de Visita Técnica - {{ $ticket->ticket_number }}</title>
    
    <!-- Bootstrap 4.6 & Google Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Inter', sans-serif; 
        }
        .logo-container img {
            max-height: 80px; /* Controla la altura máxima real del logo */
            width: auto;      /* Mantiene la proporción perfecta */
            object-fit: contain;
        }
        .schedule-card { 
            border-radius: 16px; 
            border: none; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); 
        }
        /* Estilos interactivos para las opciones de radio */
        .date-option-wrapper input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .date-option {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
        }
        .date-option:hover {
            border-color: #cbd5e1;
            background-color: #f1f5f9;
        }
        .date-option-wrapper input[type="radio"]:checked + .date-option {
            border-color: #4f46e5;
            background-color: #f5f3ff;
            box-shadow: 0 0 0 1px #4f46e5;
        }
        .date-option-wrapper input[type="radio"]:focus + .date-option {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        .day-name { 
            font-size: 1.15rem; 
            font-weight: 700; 
            text-transform: capitalize; 
            color: #1e293b;
        }
        .date-option-wrapper input[type="radio"]:checked + .date-option .day-name {
            color: #4f46e5;
        }
        .date-detail { 
            color: #64748b; 
            font-size: 0.9rem;
            text-transform: capitalize;
        }
        .time-detail { 
            font-size: 1.2rem;
            font-weight: 700; 
            color: #334155;
        }
        .btn-confirm {
            background-color: #4f46e5;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: background-color 0.2s;
        }
        .btn-confirm:hover, .btn-confirm:focus {
            background-color: #4338ca;
            color: #ffffff;
        }
    </style>
</head>
<body>

@php
    $workSchedules = $schedules->where('type', 'work');
    $hasWorkSchedules = $workSchedules->isNotEmpty();

    // Si existen opciones de trabajo, el contexto es 'work'
    // Si no, el contexto es 'diagnostic' (las opciones que llegan)
    $activeSchedules = $hasWorkSchedules ? $workSchedules : $schedules;

    // Buscar confirmada dentro del contexto activo
    $confirmedSchedule = $activeSchedules->firstWhere('confirmed_at', '!=', null);
@endphp

<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-4 logo-container">
        <img src="{{ asset('assets/images/LOGO-3D-PAGINA-CARTAGENA-NORTE-color.png') }}" width="160" height="auto" alt="Logo" class="mb-3">
        <h2 class="font-weight-bold text-dark mt-2" style="font-size: 1.75rem;">Agenda de Visita Técnica</h2>
        <p class="text-muted small">Ticket #{{ $ticket->ticket_number }}</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">

            <div class="card schedule-card">
                <div class="card-body p-4 p-md-5">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Estado de la cabecera -->
                    <div class="text-center mb-4">
                        @if(!$confirmedSchedule)
                            <div class="text-primary mb-3">
                                <i class="fas fa-calendar-alt fa-2x text-indigo" style="color: #4f46e5;"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark">¿Cuándo podemos visitarte?</h5>
                            <p class="text-muted small">Selecciona uno de los horarios disponibles para tu revisión.</p>
                        @else
                            <div class="text-success mb-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark">Visita de {{ $confirmedSchedule->type_label }} Programada</h5>
                            <p class="text-muted small">Esta cita ya se encuentra confirmada en nuestro sistema.</p>
                        @endif
                    </div>

                    <hr class="my-4">

                    {{-- ======================================= --}}
                    {{-- FORMULARIO DE SELECCIÓN (SIN CONFIRMAR) --}}
                    {{-- ======================================= --}}
                    @if(!$confirmedSchedule)
                        <form action="{{ route('tenant.schedule.confirm', $ticket->id) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                @foreach ($activeSchedules->whereNull('confirmed_at') as $schedule)
                                    @php
                                        $date = \Carbon\Carbon::parse($schedule->scheduled_at);
                                    @endphp

                                    <div class="date-option-wrapper">
                                        <input type="radio"
                                               name="selected_option"
                                               id="schedule_{{ $schedule->id }}"
                                               value="{{ $schedule->id }}"
                                               required>
                                        
                                        <label for="schedule_{{ $schedule->id }}" class="date-option">
                                            <div>
                                                <div class="day-name">
                                                    {{ $date->translatedFormat('l d') }}
                                                </div>
                                                <div class="date-detail">
                                                    {{ $date->translatedFormat('F Y') }}
                                                </div>
                                            </div>
                                            <div class="time-detail">
                                                {{ $date->format('h:i A') }}
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-confirm btn-block shadow-sm mt-4">
                                <i class="fas fa-calendar-check mr-2"></i> Confirmar Cita
                            </button>
                        </form>

                    {{-- ======================================= --}}
                    {{-- VISTA DE CITA CONFIRMADA                --}}
                    {{-- ======================================= --}}
                    @else
                        @php
                            $date = \Carbon\Carbon::parse($confirmedSchedule->scheduled_at);
                        @endphp

                        <div class="text-center py-4 px-3 bg-light rounded-lg border">
                            <p class="text-uppercase text-muted font-weight-bold tracking-wider small mb-2">Cita Confirmada para:</p>
                            <h2 class="text-success font-weight-bold mb-1" style="font-size: 1.5rem;">
                                {{ $date->translatedFormat('l d \d\e F') }}
                            </h2>
                            <h3 class="text-dark font-weight-bold" style="font-size: 1.3rem;">
                                {{ $date->format('h:i A') }}
                            </h3>

                            <div class="mt-4">
                                <span class="badge badge-success px-4 py-2 font-weight-bold" style="font-size: 0.85rem; border-radius: 30px;">
                                    <i class="fas fa-user-shield mr-1"></i> Técnico Notificado
                                </span>
                            </div>
                        </div>

                        <div class="alert alert-warning d-flex align-items-center mt-4 p-3" role="alert">
                            <i class="fas fa-exclamation-triangle mr-3 fa-lg text-warning"></i>
                            <div class="small">
                                <strong>Importante:</strong> Asegúrate de que un adulto se encuentre en el inmueble durante la ventana de tiempo seleccionada.
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Footer con Soporte -->
            <div class="text-center mt-4">
                <a href="https://wa.me/573137915029" target="_blank" class="text-muted font-weight-bold small btn-link text-decoration-none">
                    <i class="fab fa-whatsapp text-success mr-1"></i> ¿Necesitas ayuda? Contactar soporte
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>

<script>
    $('input[type="radio"]').on('change', function() {
        $('.date-option').removeClass('active');
        $(this).closest('label').find('.date-option').addClass('active');
    });
</script>

</body>
</html>
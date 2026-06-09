<div>
    {{-- ══════════════════════════════════════════════════════════════
         SEMÁFORO / KPI CARDS
         ══════════════════════════════════════════════════════════════ --}}
    <div class="row mb-3">

        {{-- CRÍTICOS --}}
        <div class="col-6 col-md-2 mb-3">
            <div class="kpi-card kpi-critical {{ $counts['critical'] > 0 ? 'kpi-pulse' : '' }}"
                 wire:click="$set('priority', 'critical')" style="cursor:pointer;">
                <div class="kpi-icon"><i class="fas fa-fire"></i></div>
                <div class="kpi-number">{{ $counts['critical'] }}</div>
                <div class="kpi-label">Críticos</div>
            </div>
        </div>

        {{-- ALTA PRIORIDAD --}}
        <div class="col-6 col-md-2 mb-3">
            <div class="kpi-card kpi-high {{ $counts['high'] > 0 ? 'kpi-pulse-soft' : '' }}"
                 wire:click="$set('priority', 'high')" style="cursor:pointer;">
                <div class="kpi-icon"><i class="fas fa-arrow-up"></i></div>
                <div class="kpi-number">{{ $counts['high'] }}</div>
                <div class="kpi-label">Alta prioridad</div>
            </div>
        </div>

        {{-- PENDIENTES DE ACCIÓN --}}
        <div class="col-6 col-md-2 mb-3">
            <div class="kpi-card kpi-pending"
                 wire:click="$set('status', 'created')" style="cursor:pointer;">
                <div class="kpi-icon"><i class="fas fa-hourglass-half"></i></div>
                <div class="kpi-number">{{ $counts['pending_action'] }}</div>
                <div class="kpi-label">Sin gestionar</div>
            </div>
        </div>

        {{-- EN PROCESO --}}
        <div class="col-6 col-md-2 mb-3">
            <div class="kpi-card kpi-progress"
                 wire:click="$set('status', 'in_progress')" style="cursor:pointer;">
                <div class="kpi-icon"><i class="fas fa-spinner"></i></div>
                <div class="kpi-number">{{ $counts['in_progress'] }}</div>
                <div class="kpi-label">En proceso</div>
            </div>
        </div>

        {{-- CERRADOS / FINALIZADOS --}}
        <div class="col-6 col-md-2 mb-3">
            <div class="kpi-card kpi-done"
                 wire:click="$set('status', 'finished')" style="cursor:pointer;">
                <div class="kpi-icon"><i class="fas fa-check-double"></i></div>
                <div class="kpi-number">{{ $counts['finished'] }}</div>
                <div class="kpi-label">Finalizados</div>
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="col-6 col-md-2 mb-3">
            <div class="kpi-card kpi-total"
                 wire:click="clearFilters" style="cursor:pointer;">
                <div class="kpi-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="kpi-number">{{ $counts['total'] }}</div>
                <div class="kpi-label">Total</div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         BARRA POR ESTADO (mini semáforo horizontal)
         ══════════════════════════════════════════════════════════════ --}}
    @php
        $statusConfig = [
            'created'         => ['color' => '#6c757d', 'label' => 'Creado'],
            'validated'       => ['color' => '#17a2b8', 'label' => 'Validado'],
            'assigned'        => ['color' => '#007bff', 'label' => 'Asignado'],
            'visit_scheduled' => ['color' => '#a3e635', 'label' => 'Visita agendada'],
            'diagnosed'       => ['color' => '#6f42c1', 'label' => 'Diagnosticado'],
            'quoted'          => ['color' => '#ffc107', 'label' => 'Cotizado'],
            'approved'        => ['color' => '#28a745', 'label' => 'Aprobado'],
            'rejected'        => ['color' => '#dc3545', 'label' => 'Rechazado'],
            'work_scheduled'  => ['color' => '#20c997', 'label' => 'Trabajo agendado'],
            'in_progress'     => ['color' => '#fd7e14', 'label' => 'En ejecución'],
            'finished'        => ['color' => '#28a745', 'label' => 'Finalizado'],
            'closed'          => ['color' => '#343a40', 'label' => 'Cerrado'],
        ];
        $total = max($counts['total'], 1);
    @endphp
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex align-items-center mb-1">
                <small class="text-muted font-weight-bold mr-2">DISTRIBUCIÓN POR ESTADO</small>
                @if($status || $priority || $search)
                    <button wire:click="clearFilters"
                            class="btn btn-xs btn-outline-secondary ml-auto">
                        <i class="fas fa-times mr-1"></i> Limpiar filtros
                    </button>
                @endif
            </div>
            {{-- Barra de progreso segmentada --}}
            <div class="d-flex rounded overflow-hidden" style="height:10px;">
                @foreach($statusConfig as $st => $cfg)
                    @php $cnt = $byStatus[$st] ?? 0; @endphp
                    @if($cnt > 0)
                        <div title="{{ $cfg['label'] }}: {{ $cnt }}"
                             style="width:{{ round(($cnt/$total)*100,1) }}%; background:{{ $cfg['color'] }};"
                             wire:click="$set('status', '{{ $st }}')"
                             class="status-bar-seg"></div>
                    @endif
                @endforeach
            </div>
            {{-- Leyenda --}}
            <div class="d-flex flex-wrap mt-2" style="gap:6px 12px;">
                @foreach($statusConfig as $st => $cfg)
                    @php $cnt = $byStatus[$st] ?? 0; @endphp
                    @if($cnt > 0)
                        <span class="d-inline-flex align-items-center"
                              style="font-size:.7rem; cursor:pointer;"
                              wire:click="$set('status', '{{ $st }}')">
                            <span class="rounded-circle mr-1"
                                  style="width:8px;height:8px;background:{{ $cfg['color'] }};display:inline-block;"></span>
                            {{ $cfg['label'] }} ({{ $cnt }})
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         FILTROS + TABLA
         ══════════════════════════════════════════════════════════════ --}}
    <div class="card shadow-sm">
        <div class="card-body p-3">

            {{-- Flash --}}
            @foreach (['success' => 'alert-success', 'danger' => 'alert-danger', 'warning' => 'alert-warning'] as $key => $class)
                @if (session($key))
                    <div class="alert {{ $class }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
            @endforeach

            {{-- Filtros --}}
            <div class="row mb-3 align-items-end">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text"
                               class="form-control border-left-0"
                               wire:model.debounce.400ms="search"
                               placeholder="Buscar ticket, arrendatario, contrato...">
                    </div>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select class="form-control form-control-sm" wire:model="status">
                        <option value="">— Todos los estados —</option>
                        @foreach($statusConfig as $st => $cfg)
                            <option value="{{ $st }}">{{ $cfg['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <select class="form-control form-control-sm" wire:model="priority">
                        <option value="">— Prioridad —</option>
                        <option value="low">Baja</option>
                        <option value="medium">Media</option>
                        <option value="high">Alta</option>
                        <option value="critical">Crítica</option>
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <span class="text-muted small">
                        {{ $tickets->total() }} resultado(s)
                    </span>
                    @if($status || $priority || $search)
                        <button wire:click="clearFilters"
                                class="btn btn-xs btn-outline-danger ml-2">
                            <i class="fas fa-times"></i> Limpiar
                        </button>
                    @endif
                </div>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table table-hover text-center mb-0" id="tickets">
                    <thead style="background:#1a3c5e; color:#fff; font-size:.8rem;">
                        <tr>
                            <th wire:click="sortBy('created_at')" style="cursor:pointer; white-space:nowrap;">
                                Fecha
                                @if($sortField === 'created_at')
                                    <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('ticket_number')" style="cursor:pointer;">
                                N° Ticket
                                @if($sortField === 'ticket_number')
                                    <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </th>
                            <th>Contrato</th>
                            <th>Arrendatario</th>
                            <th>Teléfono</th>
                            <th wire:click="sortBy('priority')" style="cursor:pointer;">
                                Prioridad
                                @if($sortField === 'priority')
                                    <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:.85rem;">
                        @forelse($tickets as $ticket)
                        <tr class="{{ in_array($ticket->priority, ['critical','high']) && !in_array($ticket->status, ['closed','finished']) ? 'table-danger' : '' }}">

                            <td class="align-middle">
                                <small class="text-muted">{{ $ticket->created_at->format('d/m/Y') }}</small>
                                <br>
                                <small class="text-muted" style="font-size:.7rem;">{{ $ticket->created_at->diffForHumans() }}</small>
                            </td>

                            <td class="align-middle font-weight-bold">
                                {{ $ticket->ticket_number }}
                            </td>

                            <td class="align-middle">
                                {{ $ticket->contract_number ?? '—' }}
                            </td>

                            <td class="align-middle">
                                <span class="font-weight-bold">{{ $ticket->tenant_name }}</span>
                            </td>

                            <td class="align-middle">
                                <small>{{ $ticket->tenant_phone }}</small>
                            </td>

                            {{-- Prioridad --}}
                            <td class="align-middle">
                                @php
                                    $pClass = match($ticket->priority) {
                                        'critical' => 'badge-danger',
                                        'high'     => 'badge-warning',
                                        'medium'   => 'badge-info',
                                        'low'      => 'badge-secondary',
                                        default    => 'badge-secondary',
                                    };
                                    $pIcon = match($ticket->priority) {
                                        'critical' => 'fa-fire',
                                        'high'     => 'fa-arrow-up',
                                        'medium'   => 'fa-minus',
                                        'low'      => 'fa-arrow-down',
                                        default    => 'fa-question',
                                    };
                                @endphp
                                <span class="badge {{ $pClass }}">
                                    <i class="fas {{ $pIcon }} mr-1"></i>
                                    {{ $ticket->priority_label }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="align-middle">
                                @php
                                    $badge = [
                                        'created'         => 'badge-secondary',
                                        'validated'       => 'badge-info',
                                        'assigned'        => 'badge-primary',
                                        'visit_scheduled' => 'bg-purple text-white',
                                        'diagnosed'       => 'bg-indigo text-white',
                                        'quoted'          => 'badge-warning text-dark',
                                        'approved'        => 'badge-success',
                                        'rejected'        => 'badge-danger',
                                        'work_scheduled'  => 'bg-teal text-white',
                                        'in_progress'     => 'bg-orange text-white',
                                        'finished'        => 'bg-olive',
                                        'closed'          => 'badge-dark',
                                    ][$ticket->status] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $badge }}">
                                    {{ $ticket->status_label }}
                                </span>
                                {{-- Indicador si necesita acción --}}
                                @if(in_array($ticket->status, ['created', 'validated', 'diagnosed']))
                                    <br><small class="text-danger" style="font-size:.65rem;">
                                        <i class="fas fa-exclamation-circle"></i> Requiere acción
                                    </small>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="align-middle">
                                <a href="{{ route('pqrs.show-admin', $ticket) }}"
                                   class="btn btn-xs btn-primary">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No se encontraron tickets con los filtros aplicados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tickets->links() }}
            </div>

        </div>
    </div>

    {{-- CSS del componente --}}
    <style>
        /* KPI Cards */
        .kpi-card {
            border-radius: 12px;
            padding: 14px 10px;
            text-align: center;
            color: #fff;
            transition: transform .15s;
            user-select: none;
        }
        .kpi-card:hover { transform: translateY(-3px); }
        .kpi-icon { font-size: 1.4rem; margin-bottom: 4px; opacity: .85; }
        .kpi-number { font-size: 1.8rem; font-weight: 800; line-height: 1; }
        .kpi-label  { font-size: .7rem; text-transform: uppercase; letter-spacing: .05em; opacity: .85; margin-top: 4px; }

        .kpi-critical { background: linear-gradient(135deg, #dc3545, #b02a37); }
        .kpi-high     { background: linear-gradient(135deg, #fd7e14, #d96c0c); }
        .kpi-pending  { background: linear-gradient(135deg, #ffc107, #d4a007); color: #212529 !important; }
        .kpi-pending .kpi-label, .kpi-pending .kpi-number { color: #212529; }
        .kpi-progress { background: linear-gradient(135deg, #007bff, #0056b3); }
        .kpi-done     { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .kpi-total    { background: linear-gradient(135deg, #1a3c5e, #0d2035); }

        /* Animación para críticos */
        @keyframes kpiPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(220,53,69,.5); }
            50%       { box-shadow: 0 0 0 10px rgba(220,53,69,0); }
        }
        @keyframes kpiPulseSoft {
            0%, 100% { box-shadow: 0 0 0 0 rgba(253,126,20,.4); }
            50%       { box-shadow: 0 0 0 8px rgba(253,126,20,0); }
        }
        .kpi-pulse      { animation: kpiPulse 1.5s infinite; }
        .kpi-pulse-soft { animation: kpiPulseSoft 2s infinite; }

        /* Barra de estados */
        .status-bar-seg {
            cursor: pointer;
            transition: opacity .15s;
        }
        .status-bar-seg:hover { opacity: .75; }

        /* Colores extra */
        .bg-purple { background-color: #6f42c1 !important; }
        .bg-indigo { background-color: #4b5eaa !important; }
        .bg-teal   { background-color: #20c997 !important; }
        .bg-orange { background-color: #fd7e14 !important; }
        .bg-lime   { background-color: #a3e635 !important; }
        .badge-xs  { font-size: .65rem; }
    </style>
</div>
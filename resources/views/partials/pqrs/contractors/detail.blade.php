@php $ticket = $work->ticket; @endphp

<div class="modal fade" id="ticketDetail-{{ $ticket->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Ticket #{{ $ticket->ticket_number }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-tools"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Categoría</span>
                                <span class="info-box-number">{{ $ticket->category }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Incidencia</span>
                                <span class="info-box-number">{{ $ticket->issue_type }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-primary">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Inquilino</strong>
                                <p class="text-muted mb-3">{{ $ticket->tenant_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Teléfono</strong>
                                <p class="text-muted mb-3">{{ $ticket->tenant_phone }}</p>
                            </div>
                        </div>

                        <strong>Prioridad</strong>
                        <div class="mt-2 mb-3">
                            <span class="badge p-2 {{ $ticket->priority == 'high' || $ticket->priority == 'critical' ? 'badge-danger' : ($ticket->priority == 'medium' ? 'badge-warning' : 'badge-info') }}">
                                {{ $ticket->priority_label }}
                            </span>
                        </div>

                        @if($ticket->description)
                            <hr>
                            <strong>Descripción adicional</strong>
                            <div class="alert alert-light mt-2 mb-0">
                                {{ $ticket->description }}
                            </div>
                        @endif

                    </div>
                </div>

                <div class="text-right text-muted small">
                    Reportado el {{ $ticket->created_at->format('d/m/Y H:i') }}
                </div>

            </div>{{-- /modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cerrar
                </button>

                @if($ticket->status === 'assigned')
                    <form action="{{ route('contractors.accept-ticket', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-play mr-1"></i> Iniciar trabajo
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>
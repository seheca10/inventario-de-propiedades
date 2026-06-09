@php
    $diagnosedSchedule = $ticket->schedules()
        ->where('type', 'diagnostic')
        ->whereNotNull('confirmed_at')
        ->first();
@endphp

{{-- Solo renderizar el modal si existe la visita diagnosticada --}}
@if($diagnosedSchedule)
<div class="modal fade" id="reviewSchedule-{{ $ticket->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-indigo text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Revisión de visita de {{ $diagnosedSchedule->type_label }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @forelse($diagnosedSchedule->reports as $report)
                    <div class="form-group">
                        <label><i class="fas fa-file-alt text-primary mr-1"></i> Motivo de la visita</label>
                        <textarea class="form-control" readonly>{{ $report->report }}</textarea>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-signature text-primary mr-1"></i> Firma de aceptación</label>
                        @if($report->signature_url)
                            <div>
                                <img src="{{ $report->signature_url }}"
                                     alt="Firma del arrendador"
                                     class="img-fluid border rounded"
                                     style="max-width:350px; height:90px;">
                            </div>
                        @else
                            <p class="text-danger mb-0">
                                <i class="fas fa-exclamation-circle mr-1"></i> No hay firma registrada
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                        <p class="mb-0">No hay reportes registrados para esta visita.</p>
                    </div>
                @endforelse
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <form action="{{ route('pqrs.validate-visit', $ticket->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn bg-indigo text-white font-weight-bold">
                        <i class="fas fa-check mr-1"></i> Validar visita
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
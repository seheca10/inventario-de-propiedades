@php
    // Detectar si ya hay una visita de diagnóstico confirmada
    $hasConfirmedDiagnostic = $ticket->schedules()
        ->where('type', 'diagnostic')
        ->whereNotNull('confirmed_at')
        ->exists();
@endphp

<div class="modal fade" id="modalSchedule-{{ $ticket->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-indigo">
                <h5 class="modal-title font-weight-bold text-white">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ $hasConfirmedDiagnostic ? 'Programar visita de trabajo' : 'Proponer horarios de visita' }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('pqrs.schedule.store', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-body">

                    @if($hasConfirmedDiagnostic)
                        <div class="alert alert-success py-2 mb-3" style="font-size:.85rem;">
                            <i class="fas fa-check-circle mr-1"></i>
                            La visita de diagnóstico ya fue completada. Estas opciones son para la <strong>ejecución del trabajo</strong>.
                        </div>
                    @else
                        <p class="text-muted small mb-3">
                            Selecciona 3 fechas y horas tentativas para que el arrendatario elija la que mejor le convenga.
                        </p>
                    @endif

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-tools text-primary mr-1"></i> Motivo de la visita
                        </label>

                        @if($hasConfirmedDiagnostic)
                            {{-- Ya hubo diagnóstico: forzar 'work', no permitir cambio --}}
                            <input type="hidden" name="type" value="work">
                            <input type="text"
                                   class="form-control bg-light"
                                   value="Realización de trabajo"
                                   disabled>
                        @else
                            {{-- Aún no hay diagnóstico: permitir elegir --}}
                            <select name="type" class="form-control" required>
                                <option value="diagnostic" selected>Diagnóstico</option>
                                <option value="work">Realización de trabajo</option>
                            </select>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-clock text-primary mr-1"></i> Opción 1
                        </label>
                        <input type="datetime-local" name="option_1" class="form-control"
                               required min="{{ date('Y-m-d\TH:i') }}">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-clock text-primary mr-1"></i> Opción 2
                        </label>
                        <input type="datetime-local" name="option_2" class="form-control"
                               required min="{{ date('Y-m-d\TH:i') }}">
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold">
                            <i class="fas fa-clock text-primary mr-1"></i> Opción 3
                        </label>
                        <input type="datetime-local" name="option_3" class="form-control"
                               required min="{{ date('Y-m-d\TH:i') }}">
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn bg-indigo text-white font-weight-bold">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar propuestas
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
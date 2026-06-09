<div class="modal fade" id="modalCloseTicket-{{ $ticket->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="background:#1a3c5e;">
                <h5 class="modal-title font-weight-bold text-white">
                    <i class="fas fa-archive mr-2"></i>
                    Cerrar Ticket — {{ $ticket->ticket_number }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('pqrs.close', $ticket->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="modal-body">

                    {{-- Aviso --}}
                    <div class="alert alert-warning py-2 mb-4" style="font-size:.85rem;">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Atención:</strong> Esta acción es irreversible.
                        Verifica la satisfacción del arrendatario y el pago del propietario antes de continuar.
                    </div>

                    {{-- Resumen del trabajo --}}
                    @if($ticket->approved_quote)
                        <div class="p-3 rounded mb-4" style="background:#f0fff4; border:1px solid #bbf7d0;">
                            <small class="font-weight-bold text-success d-block mb-2">
                                <i class="fas fa-check-circle mr-1"></i> Cotización aprobada
                            </small>
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Mano de obra</small>
                                    <strong class="text-gray">$ {{ number_format($ticket->approved_quote->labor_cost, 0, ',', '.') }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Materiales</small>
                                    <strong class="text-gray">$ {{ number_format($ticket->approved_quote->material_cost, 0, ',', '.') }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Total aprobado</small>
                                    <strong class="text-success">$ {{ number_format($ticket->approved_quote->total_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">

                        {{-- COLUMNA IZQUIERDA: Satisfacción --}}
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-star text-warning mr-1"></i>
                                Satisfacción del arrendatario
                            </h6>

                            {{-- Estrellas interactivas --}}
                            <div class="form-group">
                                <label class="font-weight-bold small text-muted text-uppercase">
                                    Calificación <span class="text-danger">*</span>
                                </label>
                                <div class="star-rating d-flex" style="gap:.5rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label for="star_{{ $ticket->id }}_{{ $i }}"
                                               class="star-label"
                                               style="cursor:pointer; font-size:1.8rem; color:#d1d5db; transition:color .15s;"
                                               data-value="{{ $i }}">
                                            <i class="fas fa-star"></i>
                                        </label>
                                        <input type="radio"
                                               name="rating"
                                               id="star_{{ $ticket->id }}_{{ $i }}"
                                               value="{{ $i }}"
                                               style="display:none;"
                                               required>
                                    @endfor
                                </div>
                                <small id="rating_label_{{ $ticket->id }}" class="text-muted">
                                    Selecciona una calificación
                                </small>
                            </div>

                            {{-- Comentario del arrendatario --}}
                            <div class="form-group">
                                <label class="font-weight-bold small text-muted text-uppercase">
                                    Comentario del arrendatario
                                </label>
                                <textarea name="comment"
                                          class="form-control"
                                          rows="3"
                                          placeholder="¿Qué dijo el arrendatario sobre el trabajo realizado?"></textarea>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: Cierre financiero --}}
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-dollar-sign text-success mr-1"></i>
                                Cierre financiero
                            </h6>

                            {{-- Costo final --}}
                            <div class="form-group">
                                <label class="font-weight-bold small text-muted text-uppercase">
                                    Costo final del trabajo <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number"
                                           name="final_cost"
                                           class="form-control"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           value="{{ $ticket->approved_quote?->total_amount ?? '' }}"
                                           required>
                                </div>
                                <small class="text-muted">
                                    Precargado con el valor aprobado. Ajusta si hubo diferencias.
                                </small>
                            </div>

                            {{-- Confirmación de pago --}}
                            <div class="form-group">
                                <label class="font-weight-bold small text-muted text-uppercase">
                                    Estado del pago
                                </label>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="payment_confirmed_{{ $ticket->id }}"
                                           name="payment_confirmed"
                                           value="1">
                                    <label class="custom-control-label"
                                           for="payment_confirmed_{{ $ticket->id }}">
                                        Pago confirmado por transferencia
                                    </label>
                                </div>
                            </div>

                            {{-- Resumen del cierre --}}
                            <div class="form-group mb-0">
                                <label class="font-weight-bold small text-muted text-uppercase">
                                    Resumen del cierre
                                </label>
                                <textarea name="summary"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Observaciones finales del operativo sobre el caso..."></textarea>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn font-weight-bold text-white"
                            style="background:#1a3c5e;"
                            onclick="return confirm('¿Confirmas el cierre definitivo del ticket {{ $ticket->ticket_number }}?')">
                        <i class="fas fa-archive mr-1"></i> Cerrar ticket definitivamente
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .star-label:hover,
    .star-label:hover ~ .star-label { color: #f59e0b !important; }
    .star-label.active { color: #f59e0b !important; }
</style>

<script>
(function() {
    const labels = {
        1: 'Muy malo', 2: 'Malo', 3: 'Regular', 4: 'Bueno', 5: 'Excelente'
    };

    const stars  = document.querySelectorAll('#modalCloseTicket-{{ $ticket->id }} .star-label');
    const inputs = document.querySelectorAll('#modalCloseTicket-{{ $ticket->id }} input[name="rating"]');
    const labelEl = document.getElementById('rating_label_{{ $ticket->id }}');

    stars.forEach((star, idx) => {
        star.addEventListener('click', () => {
            const val = idx + 1;
            inputs[idx].checked = true;

            // Colorear estrellas
            stars.forEach((s, i) => {
                s.style.color = i < val ? '#f59e0b' : '#d1d5db';
            });

            if (labelEl) labelEl.textContent = labels[val] || '';
        });

        star.addEventListener('mouseover', () => {
            const val = idx + 1;
            stars.forEach((s, i) => {
                s.style.color = i < val ? '#fbbf24' : '#d1d5db';
            });
        });

        star.addEventListener('mouseout', () => {
            const checked = document.querySelector(
                '#modalCloseTicket-{{ $ticket->id }} input[name="rating"]:checked'
            );
            const activeVal = checked ? parseInt(checked.value) : 0;
            stars.forEach((s, i) => {
                s.style.color = i < activeVal ? '#f59e0b' : '#d1d5db';
            });
        });
    });
})();
</script>
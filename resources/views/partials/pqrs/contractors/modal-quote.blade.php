@php $ticket = $work->ticket; @endphp

<div class="modal fade" id="modalQuote-{{ $ticket->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                    Generar Cotización — Ticket #{{ $ticket->ticket_number }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('contractors.quote-ticket', $ticket->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_id"     value="{{ $ticket->id }}">
                <input type="hidden" name="contractor_id" value="{{ $contractor->id }}">

                <div class="modal-body">
                    <div class="row">

                        {{-- Descripción --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Descripción del trabajo y materiales</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Detalle aquí qué reparaciones realizará y qué materiales incluye..."
                                      required></textarea>
                            <small class="text-muted">Sea lo más específico posible para facilitar la aprobación.</small>
                        </div>

                        {{-- Mano de obra --}}
                        <div class="col-md-6 mb-3">
                            <label>Costo Mano de Obra ($)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </span>
                                </div>
                                <input type="number"
                                       name="labor_cost"
                                       id="labor-{{ $ticket->id }}"
                                       class="form-control cost-input-{{ $ticket->id }}"
                                       step="0.01" min="0"
                                       placeholder="0.00"
                                       required>
                            </div>
                        </div>

                        {{-- Materiales --}}
                        <div class="col-md-6 mb-3">
                            <label>Costo Materiales ($)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-tools"></i>
                                    </span>
                                </div>
                                <input type="number"
                                       name="material_cost"
                                       id="material-{{ $ticket->id }}"
                                       class="form-control cost-input-{{ $ticket->id }}"
                                       step="0.01" min="0"
                                       placeholder="0.00"
                                       required>
                            </div>
                        </div>

                        {{-- Total calculado --}}
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <strong>TOTAL ESTIMADO:</strong>
                                <h4 class="mb-0">
                                    $ <span id="total-{{ $ticket->id }}">0.00</span>
                                </h4>
                                <input type="hidden"
                                       name="total_amount"
                                       id="total-input-{{ $ticket->id }}"
                                       value="0">
                            </div>
                        </div>

                        {{-- Adjunto opcional --}}
                        <div class="col-md-12">
                            <label>Adjuntar Cotización Formal (Opcional — PDF/JPG)</label>
                            <div class="custom-file">
                                <input type="file"
                                       name="pdf_path"
                                       class="custom-file-input"
                                       id="customFile-{{ $ticket->id }}"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <label class="custom-file-label" for="customFile-{{ $ticket->id }}">
                                    Seleccionar archivo...
                                </label>
                            </div>
                        </div>

                    </div>
                </div>{{-- /modal-body --}}

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar al Arrendatario
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{--
    CAMBIO: El <script> se mantiene igual pero usa $ticket->id en vez de
    $work->ticket->id para mayor legibilidad. La lógica es idéntica al original.
--}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const laborInput    = document.getElementById('labor-{{ $ticket->id }}');
    const materialInput = document.getElementById('material-{{ $ticket->id }}');
    const totalDisplay  = document.getElementById('total-{{ $ticket->id }}');
    const totalInput    = document.getElementById('total-input-{{ $ticket->id }}');

    function calculateTotal() {
        const labor    = parseFloat(laborInput.value)    || 0;
        const material = parseFloat(materialInput.value) || 0;
        const total    = labor + material;

        totalDisplay.innerText = total.toLocaleString('es-CO', { minimumFractionDigits: 2 });
        totalInput.value       = total.toFixed(2);
    }

    laborInput.addEventListener('input', calculateTotal);
    materialInput.addEventListener('input', calculateTotal);

    document.getElementById('customFile-{{ $ticket->id }}')
        .addEventListener('change', function (e) {
            e.target.nextElementSibling.innerText = e.target.files[0]?.name ?? 'Seleccionar archivo...';
        });
});
</script>
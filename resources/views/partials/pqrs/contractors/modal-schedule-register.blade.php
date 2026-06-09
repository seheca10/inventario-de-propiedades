@php
    $ticket          = $work->ticket;
    $currentScheulde = $ticket->schedules()->whereNotNull('confirmed_at')->latest('confirmed_at')->first();
@endphp

{{-- Solo renderizar si existe una visita confirmada --}}
@if($currentScheulde)

<style>
    #schuldeRegister-{{ $ticket->id }} .firma-wrapper {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background: #fff;
        width: 100%;
        height: 200px;
    }
    #schuldeRegister-{{ $ticket->id }} .firma-wrapper canvas {
        width: 100%; height: 100%;
        border-radius: 6px; cursor: crosshair; display: block;
    }
    #schuldeRegister-{{ $ticket->id }} .btn-clear-firma {
        display: inline-flex; align-items: center; gap: .3rem;
        margin-top: .6rem; padding: .3rem .8rem;
        background: #fff3f3; border: 1px solid #dc3545;
        color: #dc3545; border-radius: 5px;
        font-size: .8rem; font-weight: 600; cursor: pointer;
    }
    #schuldeRegister-{{ $ticket->id }} .btn-clear-firma:hover { background: #fde8e8; }
    #schuldeRegister-{{ $ticket->id }} .firma-status {
        font-size: .78rem; margin-top: .4rem;
        padding: .3rem .7rem; border-radius: 5px;
    }
    #schuldeRegister-{{ $ticket->id }} .firma-status.ok    { background: #d4edda; color: #155724; }
    #schuldeRegister-{{ $ticket->id }} .firma-status.empty { background: #fff3cd; color: #856404; }
</style>

<div class="modal fade" id="schuldeRegister-{{ $ticket->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                    Registrar visita de {{ $currentScheulde->type_label }}
                    para el ticket #{{ $ticket->ticket_number }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('contractors.register-scheulde', $ticket->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="schuldeForm-{{ $ticket->id }}">
                @csrf
                <input type="hidden" name="ticket_id"     value="{{ $ticket->id }}">
                <input type="hidden" name="contractor_id" value="{{ $contractor->id }}">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Descripción de la visita</label>
                            <textarea name="report" class="form-control" rows="4"
                                      placeholder="Detalle aquí el diagnóstico sobre el daño."
                                      required></textarea>
                            <small class="text-muted">Sea lo más específico posible para facilitar la aprobación.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label font-weight-bold">
                                <i class="fas fa-signature mr-1"></i>
                                Firma de aceptación de visita (Arrendador)
                            </label>
                            <div class="firma-wrapper">
                                <canvas id="canvas_arrendador_{{ $ticket->id }}"></canvas>
                            </div>
                            <input type="hidden"
                                   id="firma_arrendador_b64_{{ $ticket->id }}"
                                   name="firma_arrendador">
                            <button type="button"
                                    class="btn-clear-firma"
                                    id="clear_arrendador_{{ $ticket->id }}">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                            <div id="status_arrendador_{{ $ticket->id }}" class="firma-status empty">
                                ⚠️ Aún no has firmado
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar al Arrendatario
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
(function () {
    var TICKET_ID    = {{ $ticket->id }};
    var signaturePad = null;

    function initPad() {
        var canvas = document.getElementById('canvas_arrendador_' + TICKET_ID);
        if (!canvas) return;
        var ratio    = window.devicePixelRatio || 1;
        canvas.width  = canvas.offsetWidth  * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255,255,255)',
            penColor: 'rgb(0,0,0)',
        });
        signaturePad.addEventListener('afterUpdateStroke', actualizarStatus);
    }

    function actualizarStatus() {
        var el = document.getElementById('status_arrendador_' + TICKET_ID);
        if (signaturePad && !signaturePad.isEmpty()) {
            el.className  = 'firma-status ok';
            el.textContent = '✅ Firma registrada';
        } else {
            el.className  = 'firma-status empty';
            el.textContent = '⚠️ Aún no has firmado';
        }
    }

    document.getElementById('schuldeRegister-' + TICKET_ID)
        .addEventListener('shown.bs.modal', function () {
            if (signaturePad) { signaturePad.clear(); } else { initPad(); }
            actualizarStatus();
        });

    document.getElementById('clear_arrendador_' + TICKET_ID)
        .addEventListener('click', function (e) {
            e.preventDefault();
            if (signaturePad) signaturePad.clear();
            document.getElementById('firma_arrendador_b64_' + TICKET_ID).value = '';
            actualizarStatus();
        });

    document.getElementById('schuldeForm-' + TICKET_ID)
        .addEventListener('submit', function (e) {
            if (!signaturePad || signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Por favor registra la firma de aceptación antes de enviar.');
                return;
            }
            document.getElementById('firma_arrendador_b64_' + TICKET_ID).value =
                signaturePad.toDataURL('image/png');
        });
})();
</script>

@endif
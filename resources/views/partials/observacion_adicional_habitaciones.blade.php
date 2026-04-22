<div class="modal fade" id="observacionAdicional" tabindex="-1" role="dialog" aria-labelledby="observacionAdicional" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Agregar observaciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @livewire('observacion-adicional-habitacion-form', ['propiedad' => $propiedad])
            </div>

        </div>
    </div>
</div>
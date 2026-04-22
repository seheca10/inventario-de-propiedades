<div class="modal fade" id="observacionAdicional" tabindex="-1" role="dialog" aria-labelledby="observacionAdicional" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Agregar observacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @livewire('observacion-adicional-form', ['inventario' => $inventario])
                {{-- <form action="{{ route('observaciones-inventarios.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <input type="hidden" name="inventario_id" value="{{$inventario}}">

                    <div class="form-group">
                        <label>Evidencia fotografica</label>
                        <div class="custom-file mb-3">
                            <input type="file" class="custom-file-input" id="imagen_evidencia" name="imagen_evidencia" required>
                            <label class="custom-file-label" for="validatedCustomFile">Adjuntar fotografía...</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="mb-3">
                            <label>Información adicional</label>
                            <textarea class="form-control" id="validationTextarea" placeholder="Escribe información relevante" name="observaciones" required></textarea>
                        </div>
                    </div>                    

                    <button type="submit" class="btn btn-sm btn-danger d-block m-auto">Subir evidencia</button>
                </form> --}}
            </div>

        </div>
    </div>
</div>
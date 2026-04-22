<div>

    @if ($message)
        <p>{{ $message }}</p>
    @else

    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Evidencia Fotografica</td>
                <td>Observaciones</td>
                <td>Eliminar</td>
            </tr>

            @foreach ($observaciones as $observacion)
                <input type="hidden" wire:model="propiedad">
                
                <tr>
                    <td>
                        <div class="col-md-12 form-group py-2">
                            {{-- <input type="file" wire:model.lazy="observaciones.{{ $loop->index }}.imagen_evidencia" class="form-control">
                            @error('habitaciones.' . $loop->index . '.imagen_evidencia')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror --}}

                            <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="inputGroupFileAddon01">Cargar</span>
                                </div>
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" wire:model.lazy="observaciones.{{ $loop->index }}.imagen_evidencia">
                                  <label class="custom-file-label" for="inputGroupFile01">Seleccionar</label>
                                </div>
                            </div>

                            @error('habitaciones.' . $loop->index . '.imagen_evidencia')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </td>

                    <td>
                        <div class="col-md-12 py-2">
                            <div class="form-group">
                                <input  type="text" placeholder="Ejm: cerradura mala" class="form-control" wire:model.lazy="observaciones.{{ $loop->index }}.observaciones" class="form-control">
                            </div>
                            @error('habitaciones.' . $loop->index . '.observaciones')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </td>

                    <td>
                        <div class="col-md-12">
                            <div class="form-group">        
                                <button wire:click="removeObservacion({{ $loop->index }})" class="btn btn-danger btn-sm d-block m-auto text-center"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </td>

                </tr>

            @endforeach
    </table>

    <div class="row">

        <div class="col-md-6">
            <button wire:click="addObservacion" class="btn btn-sm btn-info d-block m-auto"><i class="fas fa-camera-retro"></i> AÑADIR OBSERBACION</button>
        </div>

        <hr>
       
        <div class="col-md-6 py-2">
            <button wire:click="guardarObservaciones" class="btn btn-sm btn-success d-block m-auto"><i class="fas fa-save"></i> GUARDAR OBSERVACIONES</button>
        </div>
    </div>
    @endif

</div>

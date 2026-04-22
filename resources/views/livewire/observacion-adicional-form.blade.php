<div>  

    <div class="row p-2">

        <input type="hidden" wire:model="inventario">

        @if ($message)
            <p>{{ $message }}</p>

        @else
            <div class="col-md-12 form-group py-2" >
                <label>Evidencia fotografica</label>
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" wire:model="imagen_evidencia" required>
                    <label class="custom-file-label" for="validatedCustomFile">Adjuntar fotografía...</label>
                </div>
                @error('imagen_evidencia')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        
            <div class="col-md-12">
                <div class="form-group">
                    <label for="observaciones">Información adicional</label>
                    <textarea wire:model="observaciones" class="form-control" placeholder="Escribe información relevante"></textarea>
                </div>
                @error('observaciones')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        
            <div class="col-md-12">
                <button wire:click="guardarObservaciones" class="btn btn-sm btn-danger d-block m-auto">Subir evidencia</button>
            </div>
        @endif
    </div>
    
</div>

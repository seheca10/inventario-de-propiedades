<div>
    <div class="row">        
        <div class="col-md-12 form-group py-2">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" wire:model.lazy="nombre">
            @error('nombre')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

    </div>

    <a wire:click="submitCondominio" class="btn-primary btn-sm btn d-block m-auto">Crear condominio</a>   
</div>
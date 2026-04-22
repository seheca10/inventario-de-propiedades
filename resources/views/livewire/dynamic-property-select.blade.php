<div>
    <div class="form-group">
        <label for="tipoVivienda">Tipo de vivienda</label>
        <select class="form-control" wire:model="tipoVivienda">
            <option selected>Selecciona una opción</option>
            <option value="Casa">Casa</option>
            <option value="Apartamento">Apartamento</option>
        </select>
        @error('tipoVivienda') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <form wire:submit.prevent="cambiarTipoVivienda">
        <button type="submit" class="btn btn-sm btn-success d-block m-auto" v-if="tipoVivienda === 'casa' || tipoVivienda === 'apartamento'">Diligenciar formulario</button>
    </form>

</div>
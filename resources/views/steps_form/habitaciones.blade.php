<div class="col-md-4 form-group py-2">
    <label>Luz alcoba principal</label>
    <select wire:model.defer="luz_alcoba_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('luz_alcoba_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Aire acondicionado alcoba principal</label>
    <select wire:model.defer="aire_acondicionado_alcoba_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('aire_acondicionado_alcoba_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Puerta y Closet alcoba principal</label>
    <select wire:model.defer="puerta_y_closet_alcoba_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('puerta_y_closet_alcoba_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Puerta baño principal</label>
    <select wire:model.defer="puerta_bano_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('puerta_bano_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Llave lavamanos</label>
    <select wire:model.defer="llave_lavamanos" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('llave_lavamanos')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Ducha principal</label>
    <select wire:model.defer="ducha_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('ducha_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Sanitario principal</label>
    <select wire:model.defer="sanitario_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('sanitario_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Luz baño principal</label>
    <select wire:model.defer="luz_bano_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('luz_bano_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Persiana alcoba principal</label>
    <select wire:model.defer="persina_alcoba_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('persina_alcoba_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Puerta y Closet alcoba 1</label>
    <select wire:model.defer="puerta_y_closet_alcoba_uno" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('puerta_y_closet_alcoba_uno')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Luz alcoba 1</label>
    <select wire:model.defer="luz_alcoba_uno" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('luz_alcoba_uno')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Llaves principal</label>
    <select wire:model.defer="llave_principal" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('llave_principal')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Llaves habitaciones</label>
    <select wire:model.defer="llaves_habitaciones" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('llaves_habitaciones')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Persiana alcoba 1</label>
    <select wire:model.defer="persiana_alcoba_uno" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('persiana_alcoba_uno')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Aire acondicionado alcoba 1</label>
    <select wire:model.defer="aire_acondicionado_alcoba_uno" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('aire_acondicionado_alcoba_uno')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Puerta baño social</label>
    <select wire:model.defer="puerta_bano_social" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('puerta_bano_social')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Luz baño social</label>
    <select wire:model.defer="luz_bano_social" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('luz_bano_social')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Ducha social</label>
    <select wire:model.defer="ducha_social" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('ducha_social')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Sanitario social</label>
    <select wire:model.defer="sanitario_social" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('sanitario_social')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Lavamanos social</label>
    <select wire:model.defer="lavamanos_social" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('lavamanos_social')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Ventiladores</label>
    <select wire:model.defer="ventiladores_habitaciones" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('ventiladores_habitaciones')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Controles aire acondicionado</label>
    <select wire:model.defer="control_aire_acondicionado" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('control_aire_acondicionado')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Medidor Agua</label>
    <select wire:model.defer="medidor_de_agua" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('medidor_de_agua')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2">
    <label>Medidor Luz</label>
    <select wire:model.defer="medidor_de_luz" class="form-control">
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('medidor_de_luz')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4 form-group py-2 pb-4">
    <label>Medidor Gas</label>
    <select wire:model.defer="medidor_de_gas" class="form-control">
        <option>Selecciona una opción</option>
        <option value="Bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
    @error('medidor_de_gas')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-12 form-group py-2 pb-4">
    <label>Observaciones</label>
    <textarea wire:model="observaciones" cols="30" rows="10" class="form-control"></textarea>
    @error('observaciones')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
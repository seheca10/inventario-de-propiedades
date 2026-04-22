<div class="col-md-6">
    <label>ARRENDATARIO</label>
    <br/>
    <div id="sig"></div>
    <br/><br/>
    <button id="clear" class="btn btn-danger btn-sm">Limpiar <i class="fas fa-broom"></i></button>
    <textarea id="signature" wire:model="firma_arrendatario" style="display: none"></textarea>
    @error('firma_arrendatario')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-6">
    <label>ARRENDADOR</label>
    <br/>
    <div id="sig_arrendador"></div>
    <br/><br/>
    <button id="clear_arre" class="btn btn-danger btn-sm">Limpiar <i class="fas fa-broom"></i></button>
    <textarea id="signature_arre" wire:model="firma_arrendador" style="display: none"></textarea>
    @error('firma_arrendador')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
<!-- CARD 1: CONTRATO Y ASESOR -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white"><h5 class="mb-0">1. Información Inicial</h5></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <label>Fecha</label>
                <input type="text" class="form-control" wire:model="fecha" disabled>
            </div>
            <div class="col-md-3 form-group">
                <label>Tipo Propiedad</label>
                <input type="text" class="form-control @error('tipo_de_propiedad') is-invalid @enderror" wire:model.lazy="tipo_de_propiedad" readonly>
                @error('tipo_de_propiedad') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label>N° Contrato</label>
                <input type="text" class="form-control @error('numero_contrato') is-invalid @enderror" wire:model.lazy="numero_contrato">
                @error('numero_contrato') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label>Tipo Contrato</label>
                <input type="text" class="form-control @error('tipo_de_contrato') is-invalid @enderror" wire:model.lazy="tipo_de_contrato">
                @error('tipo_de_contrato') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label>FMI</label>
                <input type="text" class="form-control @error('fmi') is-invalid @enderror" wire:model.lazy="fmi">
                @error('fmi') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            
            <div class="col-md-12"><hr></div>

            <div class="col-md-4 form-group">
                <label>Nombre Arrendatario</label>
                <input type="text" class="form-control @error('arrendatario') is-invalid @enderror" wire:model.lazy="arrendatario">
                @error('arrendatario') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-4 form-group">
                <label>Identificación</label>
                <input type="text" class="form-control @error('numero_identificacion_arrendatario') is-invalid @enderror" wire:model.lazy="numero_identificacion_arrendatario">
                @error('numero_identificacion_arrendatario') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-4 form-group">
                <label>Correo</label>
                <input type="email" class="form-control @error('corre_electronico_arrendatario') is-invalid @enderror" wire:model.lazy="corre_electronico_arrendatario">
                @error('corre_electronico_arrendatario') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

<!-- CARD 2: UBICACIÓN Y ESTRUCTURA -->
<div class="card shadow-sm mb-4 border-left-secondary">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">2. Ubicación e Inmueble</h5>
        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#createCondominio">+ Condominio</button>

        <div class="modal fade" wire:ignore.self id="createCondominio" tabindex="-1" aria-labelledby="createCondominioLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="createCondominioLabel">Añadir un nuevo condominio</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('create-condominio-form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-md-2 form-group">
                <label class="font-weight-bold">Inmueble</label>
                <input type="text" 
                    class="form-control @error('inmueble') is-invalid @enderror" 
                    wire:model.lazy="inmueble" 
                    placeholder="Ej: Casa 5 o Local 1">
                @error('inmueble') 
                    <span class="text-danger small">{{ $message }}</span> 
                @enderror
            </div>

            <div class="col-md-4 form-group">
                <label>Condominio</label>
                <select wire:model.lazy="condominio" class="form-control @error('condominio') is-invalid @enderror">
                    <option value="">Selecciona...</option>
                    @foreach ($condominios as $cond) <option value="{{ $cond->id }}">{{ $cond->nombre }}</option> @endforeach
                </select>
                @error('condominio') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-5 form-group">
                <label>Dirección</label>
                <input type="text" class="form-control @error('direccion') is-invalid @enderror" wire:model.lazy="direccion">
                @error('direccion') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-3 form-group">
                <label>N° Inmueble</label>
                <input type="text" class="form-control @error('numero_inmueble') is-invalid @enderror" wire:model.lazy="numero_inmueble">
                @error('numero_inmueble') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group"><label>Torre</label><input type="text" class="form-control @error('torre') is-invalid @enderror" wire:model.lazy="torre">@error('torre') <span class="text-danger small">{{ $message }}</span> @enderror</div>
            <div class="col-md-2 form-group"><label>Apto/Casa</label><input type="text" class="form-control @error('numero_apartamento') is-invalid @enderror" wire:model.lazy="numero_apartamento">@error('numero_apartamento') <span class="text-danger small">{{ $message }}</span> @enderror</div>
            <div class="col-md-2 form-group">
                <label>Garaje</label>
                <select wire:model.lazy="garaje" class="form-control @error('garaje') is-invalid @enderror"><option value="Si">Si</option><option value="No">No</option></select>
                @error('garaje') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group"><label>Depósito</label><input type="text" class="form-control @error('deposito') is-invalid @enderror" wire:model.lazy="deposito">@error('deposito') <span class="text-danger small">{{ $message }}</span> @enderror</div>
            <div class="col-md-2 form-group"><label>Metros m²</label><input type="text" class="form-control @error('metros') is-invalid @enderror" wire:model.lazy="metros">@error('metros') <span class="text-danger small">{{ $message }}</span> @enderror</div>
            <div class="col-md-2 form-group"><label>Alcobas</label><input type="number" class="form-control @error('alcobas') is-invalid @enderror" wire:model.lazy="alcobas">@error('alcobas') <span class="text-danger small">{{ $message }}</span> @enderror</div>
            <div class="col-md-2 form-group"><label>Baños</label><input type="number" class="form-control @error('banos') is-invalid @enderror" wire:model.lazy="banos">@error('banos') <span class="text-danger small">{{ $message }}</span> @enderror</div>
        </div>
    </div>
</div>

<!-- CARD 3: AMENITIES E INVENTARIO -->
<div class="card shadow-sm mb-4 border-left-secondary">
    <div class="card-header bg-secondary text-white"><h5 class="mb-0">3. Inventario y Equipos</h5></div>
    <div class="card-body bg-light">
        <div class="row">
            @php $boolFields = [
                'patio' => 'Patio', 'jardin' => 'Jardín', 'sala_de_tv' => 'Sala TV', 
                'alcoba_de_servicio' => 'Alcoba Serv.', 'bano_de_servicio' => 'Baño Serv.',
                'calentador_de_agua' => 'Calentador Agua', 'calentador_de_gas' => 'Calentador Gas'
            ]; @endphp
            @foreach($boolFields as $key => $label)
            <div class="col-md-2 form-group">
                <label class="small font-weight-bold">{{ $label }}</label>
                <select wire:model.lazy="{{ $key }}" class="form-control form-control-sm @error($key) is-invalid @enderror">
                    <option value="No">No</option><option value="Si">Si</option>
                </select>
                @error($key) <span class="text-danger" style="font-size: 0.7rem;">{{ $message }}</span> @enderror
            </div>
            @endforeach

            <!-- Cantidades -->
            <div class="col-md-2 form-group">
                <label class="small font-weight-bold">Aires Acond.</label>
                <input type="number" class="form-control form-control-sm @error('aires_acondicinados') is-invalid @enderror" wire:model.lazy="aires_acondicinados">
                @error('aires_acondicinados') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label class="small font-weight-bold">Controles Aire</label>
                <input type="number" class="form-control form-control-sm @error('controles_aires_acondicinados') is-invalid @enderror" wire:model.lazy="controles_aires_acondicinados">
                @error('controles_aires_acondicinados') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label class="small font-weight-bold">Ventiladores</label>
                <input type="number" class="form-control form-control-sm @error('ventiladores') is-invalid @enderror" wire:model.lazy="ventiladores">
                @error('ventiladores') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label class="small font-weight-bold">Llaves Gral.</label>
                <input type="number" class="form-control form-control-sm @error('numero_de_llaves') is-invalid @enderror" wire:model.lazy="numero_de_llaves">
                @error('numero_de_llaves') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label class="small font-weight-bold">Llaves Depo.</label>
                <input type="number" class="form-control form-control-sm @error('numero_de_llaves_depositos') is-invalid @enderror" wire:model.lazy="numero_de_llaves_depositos">
                @error('numero_de_llaves_depositos') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 form-group">
                <label class="small font-weight-bold">Llaves Hab.</label>
                <input type="number" class="form-control form-control-sm @error('numero_de_llaves_habitaciones') is-invalid @enderror" wire:model.lazy="numero_de_llaves_habitaciones">
                @error('numero_de_llaves_habitaciones') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

<!-- CARD 4: MEDIDORES -->
<div class="card shadow-sm mb-4 border-left-secondary">
    <div class="card-header bg-secondary text-white font-weight-bold">
        <h5 class="mb-0"><i class="fas fa-camera mr-2"></i> 4. Medidores y Evidencias</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @php $medidores = ['luz' => 'Energía', 'agua' => 'Acueducto', 'gas' => 'Gas Natural']; @endphp
            @foreach($medidores as $key => $label)
            <div class="col-md-4 border-right">
                <h6 class="font-weight-bold text-secondary">{{ $label }}</h6>
                
                <!-- Lectura Numérica -->
                <div class="form-group">
                    <label>Lectura</label>
                    <input type="number" 
                           class="form-control @error('lectura_medidor_'.$key) is-invalid @enderror" 
                           wire:model.lazy="lectura_medidor_{{ $key }}">
                    @error('lectura_medidor_'.$key) 
                        <span class="text-danger small">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Foto Evidencia -->
                <div class="form-group">
                    <label>Foto Evidencia</label>
                    <div class="custom-file">
                        <input type="file" 
                               class="custom-file-input" 
                               id="f_{{ $key }}" 
                               wire:model="evidencia_lectura_medidor_{{ $key }}">
                        
                        <!-- Lógica corregida para mostrar si el archivo ya está en el sistema -->
                        <label class="custom-file-label border @error('evidencia_lectura_medidor_'.$key) border-danger @enderror" for="f_{{ $key }}">
                            @if($this->{'evidencia_lectura_medidor_'.$key})
                                Foto lista ✅
                            @else
                                Elegir archivo...
                            @endif
                        </label>
                    </div>

                    <!-- Indicador de carga (Indispensable para archivos grandes) -->
                    <div wire:loading wire:target="evidencia_lectura_medidor_{{ $key }}" class="text-primary small mt-1">
                        <i class="fas fa-spinner fa-spin"></i> Subiendo imagen...
                    </div>

                    <!-- Mensaje de Error de la Foto -->
                    @error('evidencia_lectura_medidor_'.$key) 
                        <span class="text-danger small d-block mt-1 font-weight-bold">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
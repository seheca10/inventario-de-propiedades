{{-- Modal: Validate --}}
<div class="modal fade" id="modalValidateTicket" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg shadow-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i>Completar Información</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('pqrs.validate', $ticket->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>N° Contrato <span class="text-danger">*</span></label>
                            <input type="text" name="contract_number" class="form-control" value="{{ old('contract_number', $ticket->contract_number) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Nivel de prioridad <span class="text-danger">*</span></label>
                            <select name="priority" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="{{ $ticket->priority }}" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>{{ $ticket->priority_label }}</option>
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Baja</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>Alta</option>
                                <option value="critial" {{ $ticket->priority == 'critial' ? 'selected' : '' }}>Crita</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Propietario <span class="text-danger">*</span></label>
                            <select name="owner_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ $ticket->owner_id == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Descripción del Problema</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $ticket->description) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-warning font-weight-bold">Guardar y Validar</button>
                </div>
            </form>
        </div>
    </div>
</div>
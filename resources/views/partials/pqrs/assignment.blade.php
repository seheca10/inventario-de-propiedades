<div class="modal fade" id="assignmentTicket" tabindex="-1" role="dialog" aria-labelledby="assignmentTicket" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asignar caso #{{ $ticket->ticket_number }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

         <form action="{{ route('pqrs.assignments', $ticket->id) }}" method="post">
            @csrf

            <div class="form-group">
                
                <select name="contractor_id"class="form-control">
                    <option selected disabled>Seleccione el contratista</option>
                    @foreach ($contractors as $contractor)
                        <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                    @endforeach
                </select>

                @error('contractor_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <small><i>El ticket sera asignado al contratista y el arrendatario será notificado de este proceso.</i></small>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success"
                wire:click="save">
            Asignar
        </button>

        </form>
      </div>
    </div>
  </div>
</div>
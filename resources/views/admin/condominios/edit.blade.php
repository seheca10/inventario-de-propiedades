<!-- Modal -->
<div class="modal fade" id="editCondominio-{{$condominio->id}}" tabindex="-1" aria-labelledby="crearCondominio" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar el condominio {{ $condominio->nombre }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {!! Form::model($condominio, ['route' => ['condominios.update', $condominio->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}

                @csrf

                <div class="form-group">
                    {!! Form::label('nombre', 'Nombre:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::text('nombre', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Actualizar <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>

        {!! Form::close() !!}

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>
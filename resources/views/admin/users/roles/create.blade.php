<div class="modal fade" id="createRole" tabindex="-1" role="dialog" aria-labelledby="createRole" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Añadir un nuevo rol de usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre del rol</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>

                <div class="form-group">

                    @foreach ($permisos as $permiso)
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="permisos[]" value="{{ $permiso->id }}">
                            <label class="form-check-label" for="{{ $permiso->id }}">{{ $permiso->name }}</label>
                        </div>
                    @endforeach
                </div>

                  <button type="submit" class="btn btn-sm btn-primary">Crear</button>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div>
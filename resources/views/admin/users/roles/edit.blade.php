<div class="modal fade" id="editRole-{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="editRole" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Añadir un nuevo rol de usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre del rol</label>
                    <input type="text" class="form-control" value="{{ $role->name }}" name="name">
                </div>

                <div class="form-group">

                    @foreach ($permisos as $permiso)
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="permisos[]" value="{{ $permiso->id }}" {{ in_array($permiso->id, $role->permissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $permiso->id }}">{{ $permiso->name }}</label>
                        </div>
                    @endforeach
                </div>

                  <button type="submit" class="btn btn-sm btn-primary d-block m-auto">ACTUALIZAR</button>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div>
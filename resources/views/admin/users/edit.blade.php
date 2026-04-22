<div class="modal fade" id="editUser-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="createRole" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Añadir un nuevo rol de usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 form-group py-2">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                    </div>
                    
                    <div class="col-md-4 form-group py-2">
                        <label>Correo</label>
                        <input type="text" class="form-control" name="email" value="{{ $user->email }}">
                    </div>
        
                    <div class="col-md-4 form-group py-2">
                        <label>Rol</label>
                        <select name="rol" class="form-control">
                            <option>Selecciona un rol</option>
                            @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}" 
                                {{ in_array($rol->name, $user->getRoleNames()->toArray()) ? 'selected' : '' }}>
                                {{ $rol->name }}
                            </option>
                            
                            @endforeach
                        </select>
                    </div>

                    @if ($user->agenteInmobiliario)
                        <div class="col-md-6 form-group py-2">
                            <label>Identificación</label>
                            <input type="text" class="form-control" name="identificacion" value="{{ $user->agenteInmobiliario->identificacion }}">
                            @error('indentificacion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
            
                        <div class="col-md-6 form-group py-2">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" name="telefono" value="{{ $user->agenteInmobiliario->telefono }}">
                            @error('telefono')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                </div>

                

                  <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div>
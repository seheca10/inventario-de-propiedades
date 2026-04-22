<div>
    <form wire:submit.prevent="submit">
        
        <div class="row">
            
            <div class="col-md-4 form-group py-2">
                <label>Nombre</label>
                <input type="text" class="form-control" wire:model.defer="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="col-md-4 form-group py-2">
                <label>Correo</label>
                <input type="text" class="form-control" wire:model.defer="email">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4 form-group py-2">
                <label>Rol</label>
                <select wire:model.defer="rol" class="form-control">
                    <option selected>Selecciona el rol que tendrá el usuario</option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                    @endforeach
                </select>
                @error('rol')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4 form-group py-2">
                <label>Identificación</label>
                <input type="text" class="form-control" wire:model.defer="identificacion">
                @error('indentificacion')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4 form-group py-2">
                <label>Teléfono</label>
                <input type="text" class="form-control" wire:model.defer="telefono">
                @error('telefono')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4 form-group py-2">
                <label>Contraseña</label>
                <input type="text" class="form-control" wire:model="password">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="pt-2">
                    <span class="badge badge-success" wire:click="generatePassword">Generar Contraseña <i class="fas fa-mouse-pointer"></i></span>
                </div>
            </div>

        </div>

        <button type="submit" class="btn-primary btn-sm btn d-block m-auto">Crear usuario</button>
    </form>    
</div>
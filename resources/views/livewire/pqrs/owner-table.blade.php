<div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         CONFIRMACIÓN DE ELIMINACIÓN
         ══════════════════════════════════════════════════════════════ --}}
    @if($showConfirm)
    <div class="alert alert-danger d-flex align-items-center justify-content-between shadow-sm">
        <span>
            <i class="fas fa-exclamation-triangle mr-2"></i>
            ¿Estás seguro de que deseas eliminar este propietario? Esta acción no se puede deshacer.
        </span>
        <div class="ml-3 d-flex gap-2">
            <button wire:click="destroy"      class="btn btn-danger btn-sm mr-1">
                <i class="fas fa-trash mr-1"></i> Sí, eliminar
            </button>
            <button wire:click="cancelDelete" class="btn btn-secondary btn-sm">
                Cancelar
            </button>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         FORMULARIO INLINE (crear / editar)
         ══════════════════════════════════════════════════════════════ --}}
    @if($showForm)
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-{{ $editingId ? 'edit' : 'user-plus' }} mr-2 text-primary"></i>
                {{ $editingId ? 'Editar propietario' : 'Nuevo propietario' }}
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="font-weight-bold">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text"
                           wire:model.defer="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Ej: Juan García">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Teléfono <span class="text-danger">*</span></label>
                    <input type="text"
                           wire:model.defer="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="Ej: 3001234567">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                    <input type="email"
                           wire:model.defer="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="correo@ejemplo.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-light d-flex justify-content-end gap-2">
            <button wire:click="cancel" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-times mr-1"></i> Cancelar
            </button>
            <button wire:click="save" class="btn btn-primary btn-sm">
                <div wire:loading wire:target="save">
                    <i class="fas fa-spinner fa-spin mr-1"></i>
                </div>
                <div wire:loading.remove wire:target="save">
                    <i class="fas fa-save mr-1"></i>
                </div>
                {{ $editingId ? 'Actualizar' : 'Guardar propietario' }}
            </button>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         HEADER + BÚSQUEDA
         ══════════════════════════════════════════════════════════════ --}}
    <div class="card shadow-sm">
        <div class="card-body p-3">

            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div class="input-group input-group-sm" style="max-width:320px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text"
                           class="form-control border-left-0"
                           wire:model.debounce.400ms="search"
                           placeholder="Buscar por nombre, email o teléfono...">
                    @if($search)
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-sm"
                                    wire:click="$set('search','')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>

                @if(!$showForm)
                <button wire:click="create" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus mr-1"></i> Nuevo Propietario
                </button>
                @endif
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:.85rem;">
                    <thead style="background:#1a3c5e; color:#fff;">
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th class="text-center">Tickets</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($owners as $owner)
                        <tr>
                            <td class="align-middle font-weight-bold">
                                <i class="fas fa-user-tie text-success mr-1"></i>
                                {{ $owner->name }}
                            </td>
                            <td class="align-middle">
                                <i class="fas fa-phone text-muted mr-1"></i>
                                {{ $owner->phone }}
                            </td>
                            <td class="align-middle">
                                <i class="fas fa-envelope text-muted mr-1"></i>
                                {{ $owner->email }}
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge {{ $owner->tickets_count > 0 ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $owner->tickets_count }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <button wire:click="edit({{ $owner->id }})"
                                        class="btn btn-xs btn-outline-primary mr-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $owner->id }})"
                                        class="btn btn-xs btn-outline-danger"
                                        {{ $owner->tickets_count > 0 ? 'disabled title=Tiene tickets asociados' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                No se encontraron propietarios.
                                @if(!$showForm)
                                    <br>
                                    <button wire:click="create" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-user-plus mr-1"></i> Crear el primero
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $owners->links() }}
            </div>

        </div>
    </div>

    <style>
        .gap-2 { gap: .5rem; }
        .btn-xs { padding: .2rem .45rem; font-size: .75rem; }
    </style>
</div>
<div>

    {{-- Flash messages --}}
    @foreach (['success' => 'alert-success', 'danger' => 'alert-danger', 'warning' => 'alert-warning'] as $key => $class)
        @if (session($key))
            <div class="alert {{ $class }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
    @endforeach


    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="mb-0" style="font-weight:700;">
            <i class="fas fa-clipboard-list mr-2"></i> Administración
        </h5>

        <a data-toggle="modal"
           data-target="#seleccionarPropiedad"
           class="btn btn-success btn-sm"
           wire:click='create'
           >
            <i class="fas fa-plus mr-1"></i>
            Añadir contratista
        </a>

    </div>


    {{-- TABLE CARD --}}
    <div class="card shadow-sm">

        <div class="card-body p-0">

            <table class="table table-hover text-center mb-0" id="contractors">

                <thead class="bg-dark text-white">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Tickets</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($contractors as $contractor)

                        <tr>

                            {{-- ID --}}
                            <td class="align-middle">
                                <small class="text-muted">
                                    {{ $contractor->id }}
                                </small>
                            </td>

                            {{-- Nombre --}}
                            <td class="align-middle font-weight-bold">
                                {{ $contractor->name }}
                            </td>

                            {{-- Email --}}
                            <td class="align-middle">
                                {{ $contractor->email }}
                            </td>

                            {{-- Teléfono (corregido: antes estaba como badge de phone mal ubicado) --}}
                            <td class="align-middle">
                                <span class="badge badge-info">
                                    {{ $contractor->phone ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Tickets --}}
                            <td class="align-middle">
                                <span class="badge badge-warning">
                                    {{ $contractor->TicketAssignments->count() }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="align-middle">

                                <a href="{{ route('pqrs.show-admin', $contractor) }}"
                                   class="btn btn-sm btn-primary"
                                   target="_blank">

                                    <i class="fas fa-eye mr-1"></i>
                                    Ver
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                No hay contratistas registrados
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="card-footer">
            {{ $contractors->links() }}
        </div>

    </div>

    @if ($openCreateModal)
        <div class="modal fade show d-block">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Contratista
                        </h5>
                        <button type="button"
                                class="close"
                                wire:click="closeModal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text"
                                class="form-control"
                                wire:model.defer="name"
                                placeholder="Nombre completo">

                            @error('name')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="email"
                                class="form-control"
                                wire:model.defer="email"
                                placeholder="correo@ejemplo.com">

                            @error('email')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- PHONE --}}
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text"
                                class="form-control"
                                wire:model.defer="phone"
                                placeholder="+57 300 000 0000">

                            @error('phone')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="password"
                                class="form-control"
                                wire:model.defer="password">

                            @error('phone')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-secondary"
                                wire:click="closeModal">
                            Cerrar
                        </button>

                        <button class="btn btn-success"
                                wire:click="save">
                            Crear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
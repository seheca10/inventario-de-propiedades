@extends('adminlte::page')

@section('title', 'Inventarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0" style="font-weight:700; color:#fff;">
            <i class="fas fa-clipboard-list mr-2"></i> Inventarios
        </h1>
        <a data-toggle="modal" data-target="#seleccionarPropiedad" class="btn btn-success btn-sm">
            <i class="fas fa-plus mr-1"></i> Diligenciar inventario
        </a>
    </div>
    @include('partials.seleccionar-propiedad')
@stop

@section('content')

    {{-- Flash messages --}}
    @foreach (['success' => 'alert-success', 'danger' => 'alert-danger', 'warning' => 'alert-warning'] as $key => $class)
        @if (session($key))
            <div class="alert {{ $class }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
    @endforeach

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <table class="table table-hover text-center mb-0" id="inventarios">
                <thead style="background:#1a3c5e; color:#fff;">
                    <tr>
                        <th>Fecha</th>
                        <th>N° Contrato</th>
                        <th>Arrendatario</th>
                        <th>Asesor</th>
                        <th>Condominio</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventarios as $inventario)
                    @php
                        $estado     = $inventario->getEstado();
                        $condominio = \App\Models\Condominio::find($inventario->condominio);
                    @endphp
                    <tr>
                        <td class="align-middle">
                            <small class="text-muted">{{ $inventario->created_at->format('d/m/Y') }}</small>
                        </td>

                        <td class="align-middle font-weight-bold">
                            {{ $inventario->numero_contrato }}
                        </td>

                        <td class="align-middle">{{ $inventario->arrendatario }}</td>

                        <td class="align-middle">
                            <span class="badge badge-warning">{{ $inventario->nombre_asesor }}</span>
                        </td>

                        <td class="align-middle">
                            {{ $condominio?->nombre ?? '—' }}
                        </td>

                        {{-- Estado --}}
                        <td class="align-middle">
                            @switch($estado)
                                @case('pendiente_propiedad')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-hourglass-half mr-1"></i> Pdte. propiedad
                                    </span>
                                    @break
                                @case('pendiente_habitaciones')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-hourglass-half mr-1"></i> Pdte. habitaciones
                                    </span>
                                    @break
                                @case('pendiente_firma')
                                    <span class="badge" style="background:#e67e22; color:#fff;">
                                        <i class="fas fa-pen-fancy mr-1"></i> Pendiente firma
                                    </span>
                                    @break
                                @default
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i> Firmado
                                    </span>
                            @endswitch
                        </td>

                        {{-- Observaciones --}}
                        <td class="align-middle">
                            @if (optional($inventario->propiedad)->observaciones?->count() > 0)
                                <a href="{{ route('ver-observacion', $inventario) }}" target="_blank">
                                    <span class="badge badge-info">
                                        <i class="fas fa-camera mr-1"></i> Ver ({{ $inventario->propiedad->observaciones->count() }})
                                    </span>
                                </a>
                            @else
                                <span class="badge badge-light text-muted">
                                    <i class="fas fa-thumbs-up mr-1"></i> Sin novedades
                                </span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="align-middle">
                            @switch($estado)
                                @case('pendiente_propiedad')
                                    <a href="{{ route('informacionPropiedad', $inventario) }}"
                                       class="badge badge-success">
                                        <i class="fas fa-arrow-right mr-1"></i> Continuar
                                    </a>
                                    @break
                                @case('pendiente_habitaciones')
                                    <a href="{{ route('informacionHabitaciones', $inventario) }}"
                                       class="badge badge-success">
                                        <i class="fas fa-arrow-right mr-1"></i> Continuar
                                    </a>
                                    @break
                                @case('pendiente_firma')
                                    <a href="{{ route('sign-document', $inventario) }}"
                                       class="badge badge-warning text-dark">
                                        <i class="fas fa-pen-fancy mr-1"></i> Firmar
                                    </a>
                                    @break
                                @default
                                    <a href="{{ route('view-document', $inventario) }}"
                                       class="badge badge-primary" target="_blank">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </a>
                                    <a href="{{ route('download-document', $inventario) }}"
                                       class="badge badge-success" target="_blank">
                                        <i class="fas fa-cloud-download-alt mr-1"></i> Descargar
                                    </a>
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('css')
    @livewireStyles
    <style>
        #inventarios thead th { font-size: .82rem; letter-spacing: .04em; font-weight: 700; padding: .85rem .75rem; }
        #inventarios tbody td { vertical-align: middle; font-size: .88rem; }
        #inventarios tbody tr:hover { background: #f0f4ff; }
        #inventarios tbody tr:hover td,
        #inventarios tbody tr:hover span,
        #inventarios tbody tr:hover small {
            color: #1a3c5e;
        }
    </style>
@stop

@section('js')
    @livewireScripts
    <script>
        $(document).ready(function () {
            $('#inventarios').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json' },
                responsive: true,
                order: [[0, 'desc']], // más reciente primero
                pageLength: 25,
            });
        });
    </script>
@stop
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Entrega de llaves</h1>
@stop

@section('content')

  @if ($message = Session::get('success'))
      <div class="alert alert-success">
          <p>{{ $message }}</p>
      </div>
  @elseif ($message = Session::get('danger'))
      <div class="alert alert-danger">
          <p>{{ $message }}</p>
      </div>
  @endif

  <div class="card">

    <div class="card-header">
      
      <div class="float-left"></div>

      <div class="float-right">
        <a data-toggle="modal" data-target="#seleccionarPropiedad" class="btn btn-sm btn-success">Diligenciar formato</a>
      </div>

    </div>

    <div class="card-body">

      <table class="table text-center" id="entregaLlaves">
        <thead>
          <tr>
            <th scope="col">Fecha</th>
            <th scope="col">N° contrato</th>
            <th scope="col">Arrendatario</th>
            <th scope="col">Asesor</th>
            <th scope="col">Condominio</th>
            <th scope="col">Estado</th>
            <th scope="col">Observaciones</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($entregaLlaves as $inventario)
            <tr>
              <th scope="row">{{ $inventario->created_at->format('d-m-y') }}</th>
              <td>{{ $inventario->numero_contrato }}</td>
              <td>{{ $inventario->arrendatario }}</td>
              <td>
                <span class="badge badge-warning">{{ $inventario->nombre_asesor }}</span>
              </td>
              <td>{{ $inventario->condominio }}</td>

              <td>          

                @switch($inventario->getEstado())
                  @case('pendiente_propiedad')
                    <span class="badge badge-warning"><i class="fas fa-hand-point-right"></i> Pdte. info propiedad</span>
                    @break
                  @case('pendiente_habitaciones')
                    <span class="badge badge-warning"><i class="fas fa-hand-point-right"></i> Pdte. info habitaciones</span>
                    @break
                  @case('pendiente_firma')
                    <span class="badge badge-warning">Pendiente de firma</span>
                    @break
                  @default
                    <span class="badge badge-success">Firmado</span>
                @endswitch

              </td>

              <td>
                {{-- @if ($inventario->estado === 1 && $inventario->propiedad->observaciones->count() > 0 ) --}}
                @if ($inventario->propiedad->observaciones->count() > 0 )
                    <a href="{{ route('ver-observacion', $inventario) }}" target="_blank">
                      <span class="badge badge-info"><i class="fas fa-camera"></i> Ver observaciones</span>
                    </a>
                @else
                  <span class="badge badge-primary"><i class="fas fa-thumbs-up"></i> Sin observaciones</span>
                @endif
              </td>

              <td>

                @switch($inventario->getEstado())
                  @case('pendiente_propiedad')
                    <a href="{{ route('informacionPropiedad', $inventario) }}" class="badge badge-success"><i class="fas fa-hand-point-right"></i> Continuar</a>
                    @break
                  @case('pendiente_habitaciones')
                    <a href="{{ route('informacionHabitaciones', $inventario) }}" class="badge badge-success"><i class="fas fa-hand-point-right"></i> Continuar</a>
                    @break
                  @case('pendiente_firma')
                    <a href="{{ route('sign-document', $inventario) }}" class="badge badge-success"><i class="fas fa-pen-fancy"></i> FIRMAR</a>
                    @break
                  @default
                    <a href="{{ route('view-document', $inventario) }}" class="badge badge-primary" target="_blank"><i class="fas fa-eye"></i> VER</a>
                    <a href="{{ route('download-document', $inventario) }}" class="badge badge-success" target="_blank"><i class="fas fa-cloud-download-alt"></i> DESCARGAR</a>
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
@stop

@section('js')
  @livewireScripts
  <script>        
    $(document).ready(function() {
        $('#entregaLlaves').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
            },
            responsive: true,
        });
    });
</script>
@stop
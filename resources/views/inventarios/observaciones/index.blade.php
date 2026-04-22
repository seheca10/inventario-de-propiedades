@extends('adminlte::page')

@section('title', 'Observaciones de inventarios')

@section('content_header')
    <h1>Observaciones de inventarios</h1>
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

    <div class="card-body">

      <table class="table text-center" id="inventarios">
        <thead>
          <tr>
            <th scope="col">Inventario</th>
            <th scope="col">N° Contrato</th>
            <th scope="col">Arrendatario</th>
            <th scope="col">Asesor</th>
            <th scope="col">Tipo de propieda</th>
            <th scope="col">Imagen</th>
            <th scope="col">Fecha</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($observaciones as $observacion)
            <tr>
              <th scope="row">{{ $observacion->inventario->id }}</th>
              <td>{{ $observacion->inventario->numero_contrato }}</td>
              <td>{{ $observacion->inventario->arrendatario }}</td>
              <td>
                <span class="badge badge-info">{{ $observacion->inventario->nombre_asesor }}</span>
              </td>
              <td>
                {{ $observacion->inventario->tipo_de_propiedad }}
              </td>
              <td>
                <img src="{{ str_replace('public/', '', asset('storage/' . $observacion->imagen_evidencia)) }}" alt="Evidencia" width="30" height="30">
              </td>
              <td>{{ $observacion->created_at->format('d/m/Y') }}</td>              
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
        $('#inventarios').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
            },
            responsive: true,
        });
    });
</script>
@stop
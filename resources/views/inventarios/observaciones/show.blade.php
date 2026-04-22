@extends('adminlte::page')

@section('title', 'Observaciones inventarios')

@section('content_header')
    <h1>Observaciones inventario con número de contrato: <u>{{ $inventario->numero_contrato }}</u></h1>
@stop

@section('content')

  <div class="row">
    @foreach ($inventario->observaciones as $observacion)
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h5>{{ $observacion->created_at->format('Y-m-d : h:m:s') }}
          </div>
    
          <div class="card-body">
            <div class="foto py-2">
              <img src="{{ str_replace('public/', '', asset('storage/' . $observacion->imagen_evidencia)) }}" alt="Evidencia" width="100%" height="auto">
            </div>
    
            <div class="descripcion py-2">
              <p>Comentario: {{ $observacion->observaciones }}</p>         
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  
@stop

@section('css')
  @livewireStyles
@stop

@section('js')
  @livewireScripts
@stop
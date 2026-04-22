@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Diligenciamiento de inventario</h1>
@stop

@section('content')

  <div class="card">

    <div class="card-body">
      @livewire('observacion-adicional-form', ['propiedad' => $propiedad])
    </div>

  </div>

@stop

@section('css')
  @livewireStyles
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css" />
@stop

@section('js')
  @livewireScripts
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
@stop
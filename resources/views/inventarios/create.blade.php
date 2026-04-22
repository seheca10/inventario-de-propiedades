@extends('adminlte::page')

@section('title', 'Diligenciamiento de Inventario')

@section('content_header')
    <h1>Diligenciamiento de inventario</h1>
@stop

@section('content')

  <div class="card">

    <div class="card-body">
      {{-- @livewire('multi-step-form', ['tipo_de_propiedad' => $tipo_de_propiedad]) --}}
      @livewire('inventory-create', ['tipo_de_propiedad' => $tipo_de_propiedad])
    </div>

  </div>

@stop

@section('css')
  @livewireStyles
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
  @livewireScripts
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
      $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
  </script>

  <script>
      window.addEventListener('close-modal', event => {
          $('#createCodnominio').modal('hide');
      });
  </script>
@stop
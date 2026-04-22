@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Diligenciamiento de inventario</h1>
@stop

@section('content')

  <div class="card">

    <div class="card-body">
      @livewire('habitacion-form', ['propiedad'=> $propiedad])
    </div>

  </div>

@stop

@section('css')
  @livewireStyles
@stop

@section('js')
  @livewireScripts
@stop
@extends('adminlte::page')

@section('title', 'Gestión de Propietarios')

@section('content_header')
    <h1>Gestión de Propietarios</h1>
@stop

@section('content')

  <div class="card">

    <div class="card-body">
        
        @livewire('pqrs.owner-table')

    </div>

  </div>

@stop

@section('css')
  @livewireStyles
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
  @livewireScripts
@stop
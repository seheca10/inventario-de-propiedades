@extends('adminlte::page')

@section('title', 'Gestión de Contratistas')

@section('content_header')
    <h1>Gestión de Contratistas</h1>
@stop

@section('content')

  <div class="card">

    <div class="card-body">
        
        @livewire('contractors-admin')

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
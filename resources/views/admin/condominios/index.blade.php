@extends('adminlte::page')

@section('title', 'Listadeo de Condominios')

@section('content_header')
    <h1>Condominios</h1>
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
        <a data-toggle="modal" data-target="#crearCondominio" class="btn btn-sm btn-success">Añadir inventario</a>
        @include('admin.condominios.create')
      </div>

    </div>

    <div class="card-body">

      <table class="table">
        <thead>
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Nombre</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($condominios as $condominio)
            <tr>
              <th scope="row">{{ $condominio->id }}</th>
              <td>{{ $condominio->nombre }}</td>
              <td>
                {{-- Boton editar --}}
                <a class="btn btn-sm btn-warning float-left mr-2" data-toggle="modal" data-target="#editCondominio-{{$condominio->id}}">Editar</a>
                @include('admin.condominios.edit')
                <form method="POST" action="{{ route('condominios.destroy', $condominio->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Desea eliminar este registro?')" class="btn btn-sm btn-outline btn-danger">Eliminar</button>
                </form>
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
@stop
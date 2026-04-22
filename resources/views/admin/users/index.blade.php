@extends('adminlte::page')

@section('title', 'Listado de Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
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
        <a data-toggle="modal" data-target="#createUser" class="btn btn-sm btn-success">Añadir usuario <i class="fas fa-user-plus"></i></a>
        @include('admin.users.create')
      </div>

    </div>

    <div class="card-body">

      <table class="table">
        <thead>
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Nombre</th>
            <th scope="col">Rol</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr>
              <th scope="row">{{ $user->id }}</th>
              <td>{{ $user->name }}</td>
              <td>
                @foreach ($user->getRoleNames() as $role)
                  @switch($role)
                    @case('Administrador')
                      <span class="badge badge-success">Administrador</span>
                      @break
                    @case('Agente Inmobiliario')
                      <span class="badge badge-primary">Agente Inmobiliario</span>
                      @break
                    @case('Asistente administrativa')
                      <span class="badge badge-warning">Asistente administrativa</span>
                      @break
                     @default
                      <span class="badge badge-secondary">Otro rol</span>
                      @break
                  @endswitch
                @endforeach
              </td>
              <td>
                {{-- Boton editar --}}
                <a class="btn btn-sm btn-warning float-left mr-2" data-toggle="modal" data-target="#editUser-{{$user->id}}">Editar</a>
                @include('admin.users.edit')
                <form method="POST">
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
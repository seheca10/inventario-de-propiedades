@extends('adminlte::page')

@section('title', 'Listado de Roles')

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
        <a data-toggle="modal" data-target="#createRole" class="btn btn-sm btn-success">Añadir rol <i class="fas fa-user-plus"></i></a>
        @include('admin.users.roles.create')
      </div>

    </div>

    <div class="card-body">

      <table class="table">
        <thead>
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Permisos</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($roles as $role)
            <tr>
              <td scope="row">{{ $role->name }}</td>
              <td>
                @foreach ($role->permissions as $permiso)
                    <span class="badge badge-primary mr-2">{{ $permiso->name }}</span>
                @endforeach
              </td>
              <td>
                {{-- Boton editar --}}
                <a class="btn btn-sm btn-warning float-left mr-2" data-toggle="modal" data-target="#editRole-{{$role->id}}"><i class="fas fa-edit"></i></a>
                @include('admin.users.roles.edit')
                <form method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Desea eliminar este registro?')" class="btn btn-sm btn-outline btn-danger"><i class="fas fa-trash-alt"></i></button>
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
  {{-- <script>
    $(document).ready(function() {
        $('#select-permission-names').select2();
    });
  </script> --}}
@stop
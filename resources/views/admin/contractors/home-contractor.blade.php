@extends('adminlte::page')

@section('title', 'Mi lista de trabajos')

@section('content_header')
    <h1>Mi Lista de Trabajos</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if($contractor->ticketAssignments->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No tienes trabajos asignados aún</h4>
                <p class="text-muted small">Cuando te asignen un ticket aparecerá aquí.</p>
            </div>
        @else
            <div class="row">
                @foreach ($contractor->ticketAssignments as $work)
                    <div class="col-md-6 mb-3">
                        @include('partials.pqrs.contractors.work-card', [
                            'work'       => $work,
                            'contractor' => $contractor,
                        ])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@stop

@section('css')
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .bg-orange { background-color: #fd7e14 !important; }
        .quote-badge { font-size: .7rem; }
        .work-card[data-status="assigned"]    { border-left: 4px solid #17a2b8 !important; }
        .work-card[data-status="in_progress"] { border-left: 4px solid #fd7e14 !important; }
        .work-card[data-status="quoted"]      { border-left: 4px solid #ffc107 !important; }
        .work-card[data-status="approved"]    { border-left: 4px solid #28a745 !important; }
        .work-card[data-status="rejected"]    { border-left: 4px solid #dc3545 !important; }
    </style>
@stop

@section('js')
    @livewireScripts
@stop
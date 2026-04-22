{{--
    Partial reutilizable: resources/views/steps_form/_campo.blade.php

    Uso en cada step:
        @include('steps_form._campo', [
            'seccion'    => 'entrada',          // nombre del array Livewire
            'campo'      => 'puerta_principal', // key dentro del array
            'label'      => 'Puerta Principal',
            'opciones'   => ['Bueno','Regular','Malo','N/A'],  // opcional, default abajo
            'evidencias' => $evidencias,         // pasar desde el componente
        ])
--}}

@php
    $opcionesDefault = ['Bueno', 'Regular', 'Malo', 'N/A'];
    $opcionesFinal   = $opciones ?? $opcionesDefault;
    $valorActual     = data_get($$seccion, $campo, '');
    $tieneEvidencia  = isset($evidencias["{$seccion}.{$campo}"]);
@endphp

<div class="col-md-4 mb-1">
    <div class="field-card">

        <label>{{ $label }}</label>

        <select wire:model.lazy="{{ $seccion }}.{{ $campo }}"
                class="form-control form-control-sm {{ $errors->has("{$seccion}.{$campo}") ? 'is-invalid' : '' }}">
            @foreach($opcionesFinal as $op)
                <option value="{{ $op }}">{{ $op }}</option>
            @endforeach
        </select>

        @error("{$seccion}.{$campo}")
            <span class="field-error">{{ $message }}</span>
        @enderror

        {{-- Botón de evidencia — solo cuando el valor es "Malo" --}}
        @if($valorActual === 'Malo')
            <button type="button"
                    wire:click="abrirEvidencia('{{ $seccion }}.{{ $campo }}')"
                    class="btn-evidencia {{ $tieneEvidencia ? 'has-evidencia' : '' }}">
                @if($tieneEvidencia)
                    <i class="fas fa-check-circle"></i> Evidencia guardada
                @else
                    <i class="fas fa-camera-retro"></i> Añadir evidencia
                @endif
            </button>
        @endif

    </div>
</div>
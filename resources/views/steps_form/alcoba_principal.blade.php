{{-- ═══════════════════════════════════════════════════════════════════════
     resources/views/steps_form/alcoba_principal.blade.php
══════════════════════════════════════════════════════════════════════════ --}}

@php
    $camposAlcoba = [
        'puerta_alcoba_principal'           => ['label' => 'Puerta',              'opciones' => null],
        'cerradura_alcoba_principal'        => ['label' => 'Cerradura',           'opciones' => null],
        'llaves_alcoba_principal'           => ['label' => 'Llaves',              'opciones' => ['Bueno','Regular','Malo','N/A']],
        'ventana_alcoba_principal'          => ['label' => 'Ventana',             'opciones' => null],
        'cortinas_alcoba_principal'         => ['label' => 'Cortinas',            'opciones' => ['Bueno','Regular','Malo','Dobles','N/A']],
        'pisos_alcoba_principal'            => ['label' => 'Pisos',               'opciones' => null],
        'paredes_alcoba_principal'          => ['label' => 'Paredes',             'opciones' => null],
        'techos_alcoba_principal'           => ['label' => 'Techos',              'opciones' => null],
        'tomas_electricas_alcoba_principal' => ['label' => 'Tomacorrientes',      'opciones' => null],
        'interruptores_alcoba_principal'    => ['label' => 'Interruptores',       'opciones' => null],
        'lamparas_alcoba_principal'         => ['label' => 'Lámparas',            'opciones' => null],
        'bombillos_alcoba_principal'        => ['label' => 'Bombillos',           'opciones' => null],
        'closet_alcoba_principal'           => ['label' => 'Closet',              'opciones' => ['Bueno','Regular','Malo','N/A']],
        'puertas_alcoba_principal'          => ['label' => 'Puertas internas',    'opciones' => null],
        'ventiladores_alcoba_principal'     => ['label' => 'Ventiladores techo',  'opciones' => ['Bueno','Regular','Malo','N/A']],
    ];
@endphp

@foreach($camposAlcoba as $campo => $config)
    @include('steps_form._campo', [
        'seccion'    => 'alcoba_principal',
        'campo'      => $campo,
        'label'      => $config['label'],
        'opciones'   => $config['opciones'],
        'evidencias' => $evidencias,
    ])
@endforeach

<div class="col-md-12 mt-2">
    <div class="field-card">
        <label>Otros / Observaciones</label>
        <textarea wire:model.lazy="alcoba_principal.otros_alcoba_principal"
                  class="form-control" rows="2"
                  placeholder="Describe aquí otros detalles..."></textarea>
        @error('alcoba_principal.otros_alcoba_principal')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>
</div>
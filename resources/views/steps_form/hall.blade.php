{{-- resources/views/steps_form/hall.blade.php --}}

@php
    $camposHall = [
        'puerta_hall'               => ['label' => 'Puerta',                  'opciones' => null],
        'ventana_hall'              => ['label' => 'Ventana',                 'opciones' => null],
        'cortinas_hall'             => ['label' => 'Persianas / Cortinas',    'opciones' => ['Bueno','Regular','Malo','Dobles','N/A']],
        'pisos_hall'                => ['label' => 'Pisos',                   'opciones' => null],
        'paredes_hall'              => ['label' => 'Paredes',                 'opciones' => null],
        'techos_hall'               => ['label' => 'Techos',                  'opciones' => null],
        'tomas_electricas_hall'     => ['label' => 'Tomacorrientes',          'opciones' => null],
        'interruptores_hall'        => ['label' => 'Interruptores',           'opciones' => null],
        'lamparas_hall'             => ['label' => 'Lámparas',                'opciones' => null],
        'bombillos_hall'            => ['label' => 'Bombillos',               'opciones' => null],
        'muebles_hall'              => ['label' => 'Muebles',                 'opciones' => null],
        'anjeos_hall'               => ['label' => 'Anjeos',                  'opciones' => ['Bueno','Regular','Malo','N/A']],
        'aires_acondicionados_hall' => ['label' => 'Aires acondicionados',    'opciones' => ['Bueno','Regular','Malo','N/A']],
        'iluminacion_balcon'        => ['label' => 'Iluminación balcón',      'opciones' => ['Bueno','Regular','Malo','N/A']],
        'vidrio_balcon'             => ['label' => 'Vidrio balcón',           'opciones' => ['Bueno','Regular','Malo','N/A']],
        'baranda_balcon'            => ['label' => 'Baranda balcón',          'opciones' => ['Bueno','Regular','Malo','N/A']],
        'piso_balcon'               => ['label' => 'Piso balcón',             'opciones' => ['Bueno','Regular','Malo','N/A']],
        'paredes_balcon'            => ['label' => 'Paredes balcón',          'opciones' => ['Bueno','Regular','Malo','N/A']],
        'detecto_de_humo_hall'      => ['label' => 'Detector de humo',        'opciones' => ['Bueno','Regular','Malo','N/A']],
    ];
@endphp

@foreach($camposHall as $campo => $config)
    @include('steps_form._campo', [
        'seccion'    => 'hall',
        'campo'      => $campo,
        'label'      => $config['label'],
        'opciones'   => $config['opciones'],
        'evidencias' => $evidencias,
    ])
@endforeach

{{-- Ventiladores de techo: campo libre --}}
<div class="col-md-4 mb-1">
    <div class="field-card">
        <label>Ventiladores de techo</label>
        <input type="text"
               wire:model.lazy="hall.ventiladores_de_techo_hall"
               class="form-control form-control-sm"
               placeholder="Cantidad o N/A">
        @error('hall.ventiladores_de_techo_hall')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Otros --}}
<div class="col-md-12 mt-2">
    <div class="field-card">
        <label>Otros / Observaciones</label>
        <textarea wire:model.lazy="hall.otros_hall"
                  class="form-control" rows="2"
                  placeholder="Describe aquí otros detalles..."></textarea>
        @error('hall.otros_hall')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>
</div>
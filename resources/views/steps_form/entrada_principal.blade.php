{{-- resources/views/steps_form/entrada_principal.blade.php --}}

@php
    $camposEntrada = [
        'puerta_principal'        => ['label' => 'Puerta Principal',          'opciones' => ['Bueno','Regular','Malo']],
        'cerradura_puerta'        => ['label' => 'Cerradura puerta',          'opciones' => ['Bueno','Regular','Malo','Electrica']],
        'otras_puertas'           => ['label' => 'Otras puertas',             'opciones' => ['Bueno','Regular','Malo','N/A']],
        'ventana'                 => ['label' => 'Ventanas',                  'opciones' => null],
        'pisos'                   => ['label' => 'Pisos',                     'opciones' => null],
        'paredes'                 => ['label' => 'Paredes',                   'opciones' => null],
        'techos'                  => ['label' => 'Techos',                    'opciones' => null],
        'tomas_electricas'        => ['label' => 'Tomacorrientes',            'opciones' => null],
        'interruptores'           => ['label' => 'Interruptores',             'opciones' => null],
        'lamparas'                => ['label' => 'Lámparas',                  'opciones' => null],
        'persianas'               => ['label' => 'Persianas',                 'opciones' => null],
        'tipo_material_persianas' => ['label' => 'Material de persianas',     'opciones' => null],
        'escaleras'               => ['label' => 'Escaleras',                 'opciones' => ['Bueno','Regular','Malo','N/A']],
        'citofonos'               => ['label' => 'Citofonos',                 'opciones' => null],
        'rosetas'                 => ['label' => 'Rosetas',                   'opciones' => null],
        'caja_de_fusibles'        => ['label' => 'Tablero eléctrico',         'opciones' => null],
        'timbre'                  => ['label' => 'Timbre',                    'opciones' => ['Bueno', 'Regular', 'Malo', 'N/A']],
    ];
@endphp

@foreach($camposEntrada as $campo => $config)
    @include('steps_form._campo', [
        'seccion'    => 'entrada',
        'campo'      => $campo,
        'label'      => $config['label'],
        'opciones'   => $config['opciones'],
        'evidencias' => $evidencias,
    ])
@endforeach

{{-- Campo libre: Otros --}}
<div class="col-md-12 mt-2">
    <div class="field-card">
        <label>Otros / Observaciones</label>
        <textarea wire:model.lazy="entrada.otros"
                  class="form-control"
                  rows="2"
                  placeholder="Especifique si hay algún otro elemento en la entrada principal..."></textarea>
        @error('entrada.otros')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>
</div>
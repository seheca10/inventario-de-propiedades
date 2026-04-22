{{-- ═══════════════════════════════════════════════════════════════════════
     resources/views/steps_form/cocina.blade.php
══════════════════════════════════════════════════════════════════════════ --}}

@php
    $camposCocina = [
        'puerta_cocina'             => ['label' => 'Puerta',                    'opciones' => ['Bueno','Regular','Malo','N/A']],
        'ventana_cocina'            => ['label' => 'Ventana',                   'opciones' => null],
        'cortinas_cocina'           => ['label' => 'Persianas',                 'opciones' => ['Bueno','Regular','Malo','Dobles','N/A']],
        'paredes_cocina'            => ['label' => 'Paredes',                   'opciones' => null],
        'pisos_cocina'              => ['label' => 'Pisos',                     'opciones' => null],
        'techos_cocina'             => ['label' => 'Techos',                    'opciones' => null],
        'tomas_electricas_cocina'   => ['label' => 'Tomacorrientes',            'opciones' => null],
        'lamparas_cocina'           => ['label' => 'Lámparas',                  'opciones' => null],
        'bombillos_cocina'          => ['label' => 'Bombillos',                 'opciones' => null],
        'interruptores_cocina'      => ['label' => 'Interruptores',             'opciones' => null],
        'instalacion_gas_cocina'    => ['label' => 'Instalación gas',           'opciones' => null],
        'lavaplatos_cocina'         => ['label' => 'Lavaplatos',                'opciones' => null],
        'grifeteria_cocina'         => ['label' => 'Grifetería',                'opciones' => null],
        'estufa_cocina'             => ['label' => 'Estufa',                    'opciones' => null],
        'horno_cocina'              => ['label' => 'Horno',                     'opciones' => ['Bueno','Regular','Malo','N/A']],
        'meson_cocina'              => ['label' => 'Mesón',                     'opciones' => null],
        'muebles_cocina'            => ['label' => 'Muebles',                   'opciones' => null],
        'puertas_muebles_cocina'    => ['label' => 'Puertas de muebles',        'opciones' => null],
        'cerraduras_muebles_cocina' => ['label' => 'Manijas de muebles',        'opciones' => null],
        'llaves_muebles_cocina'     => ['label' => 'Llaves de muebles',         'opciones' => ['Bueno','Regular','Malo','N/A']],
        'cajones_muebles_cocina'    => ['label' => 'Cajones de muebles',        'opciones' => null],
        'entrepanos_muebles_cocina' => ['label' => 'Entrepaños de muebles',     'opciones' => null],
        'calentador_cocina'         => ['label' => 'Calentador',                'opciones' => ['Bueno','Regular','Malo','N/A']],
        'campana_extractora_cocina' => ['label' => 'Campana extractora',        'opciones' => ['Bueno','Regular','Malo','N/A']],
    ];
@endphp

@foreach($camposCocina as $campo => $config)
    @include('steps_form._campo', [
        'seccion'    => 'cocina',
        'campo'      => $campo,
        'label'      => $config['label'],
        'opciones'   => $config['opciones'],
        'evidencias' => $evidencias,
    ])
@endforeach

<div class="col-md-12 mt-2">
    <div class="field-card">
        <label>Otros / Observaciones</label>
        <textarea wire:model.lazy="cocina.otros_cocina"
                  class="form-control" rows="2"
                  placeholder="Describe aquí otros detalles..."></textarea>
        @error('cocina.otros_cocina')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>
</div>
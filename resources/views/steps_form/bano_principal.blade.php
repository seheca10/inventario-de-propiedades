{{-- ═══════════════════════════════════════════════════════════════════════
     resources/views/steps_form/bano_principal.blade.php
══════════════════════════════════════════════════════════════════════════ --}}

@php
    $camposBano = [
        'puerta_bano_principal'                  => ['label' => 'Puerta',                    'opciones' => null],
        'ventana_bano_principal'                 => ['label' => 'Ventana',                   'opciones' => ['Bueno','Regular','Malo','N/A']],
        'lavamanos_bano_principal'               => ['label' => 'Lavamanos',                 'opciones' => null],
        'meson_bano_principal'                   => ['label' => 'Mesón',                     'opciones' => ['Bueno','Regular','Malo','N/A']],
        'mueble_bano_principal'                  => ['label' => 'Mueble',                    'opciones' => ['Bueno','Regular','Malo','N/A']],
        'sanitario_bano_principal'               => ['label' => 'Sanitario',                 'opciones' => null],
        'toallero_bano_principal'                => ['label' => 'Toallero',                  'opciones' => ['Bueno','Regular','Malo','N/A']],
        'jabonera_bano_principal'                => ['label' => 'Jabonera',                  'opciones' => ['Bueno','Regular','Malo','N/A']],
        'cepillero_bano_principal'               => ['label' => 'Cepillero',                 'opciones' => ['Bueno','Regular','Malo','N/A']],
        'espejos_bano_principal'                 => ['label' => 'Espejos',                   'opciones' => null],
        'paredes_bano_principal'                 => ['label' => 'Paredes',                   'opciones' => null],
        'techos_bano_principal'                  => ['label' => 'Techos',                    'opciones' => null],
        'lamparas_bano_principal'                => ['label' => 'Lámparas',                  'opciones' => null],
        'interruptores_bano_principal'           => ['label' => 'Interruptores',             'opciones' => null],
        'bombillos_bano_principal'               => ['label' => 'Bombillos',                 'opciones' => null],
        'soporte_papel_higienico_bano_principal' => ['label' => 'Soporte papel higiénico',   'opciones' => ['Bueno','Regular','Malo','N/A']],
        'portavasos_bano_principal'              => ['label' => 'Portavasos',                'opciones' => ['Bueno','Regular','Malo','N/A']],
        'ducha_bano_principal'                   => ['label' => 'Ducha',                     'opciones' => null],
        'divisiones_bano_principal'              => ['label' => 'Divisiones',                'opciones' => ['Bueno','Regular','Malo','N/A']],
    ];
@endphp

@foreach($camposBano as $campo => $config)
    @include('steps_form._campo', [
        'seccion'    => 'bano_principal',
        'campo'      => $campo,
        'label'      => $config['label'],
        'opciones'   => $config['opciones'],
        'evidencias' => $evidencias,
    ])
@endforeach

<div class="col-md-12 mt-2">
    <div class="field-card">
        <label>Otros / Observaciones</label>
        <textarea wire:model.lazy="bano_principal.otros_bano_principal"
                  class="form-control" rows="2"
                  placeholder="Describe aquí otros detalles..."></textarea>
        @error('bano_principal.otros_bano_principal')
            <span class="field-error">{{ $message }}</span>
        @enderror
    </div>
</div>
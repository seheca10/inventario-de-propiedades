@if ($inventario->propiedad->banosAdicionales->count() > 0)
<table class="table table-bordered table-striped">
    <thead>
        <tr><th colspan="12" class="text-center">BAÑOS ADICIONALES</th></tr>
    </thead>
 
    @php
        $camposBano = [
            'puerta'                  => 'Puerta',
            'cerradura'               => 'Cerradura',
            'llaves'                  => 'Llaves',
            'ventana'                 => 'Ventana',
            'lavamanos'               => 'Lavamanos',
            'meson'                   => 'Mesón',
            'mueble'                  => 'Mueble',
            'puertas'                 => 'Puertas mueble',
            'cerraduras'              => 'Cerraduras mueble',
            'gabinete'                => 'Gabinete',
            'entrepanos'              => 'Entrepaños',
            'sanitario'               => 'Sanitario',
            'toallero'                => 'Toallero',
            'jabonera'                => 'Jabonera',
            'cepillero'               => 'Cepillero',
            'espejos'                 => 'Espejos',
            'paredes'                 => 'Paredes',
            'techos'                  => 'Techos',
            'lamparas'                => 'Lámparas',
            'interruptores'           => 'Interruptores',
            'bombillos'               => 'Bombillos',
            'soporte_papel_higienico' => 'Soporte papel higiénico',
            'rejillas_desague'        => 'Rejillas desagüe',
            'portavasos'              => 'Portavasos',
            'ducha'                   => 'Ducha',
            'divisiones'              => 'Divisiones',
            'otros'                   => 'Otros',
        ];
    @endphp
 
    @foreach ($inventario->propiedad->banosAdicionales as $bano)
    <thead>
        <tr>
            <th colspan="12" class="text-center">
                @if ($bano->nombre)
                    {{ strtoupper($bano->nombre) }}
                @elseif ($bano->habitacion_id)
                    BAÑO HABITACIÓN N° {{ $loop->iteration }}
                @else
                    BAÑO SOCIAL N° {{ $loop->iteration }}
                @endif
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach (array_chunk($camposBano, 2, true) as $fila)
        <tr>
            @foreach ($fila as $campo => $label)
                <td colspan="3">{{ $label }}</td>
                <td colspan="3"><b><u>{{ $bano->$campo ?? '—' }}</u></b></td>
            @endforeach
            {{-- Si la fila tiene solo 1 campo (último impar), rellenar --}}
            @if (count($fila) === 1)
                <td colspan="3"></td><td colspan="3"></td>
            @endif
        </tr>
        @endforeach
    </tbody>
    @endforeach
</table>
@endif
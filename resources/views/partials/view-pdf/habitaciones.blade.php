@if ($inventario->propiedad->habitaciones->count() > 0)
<table class="table table-bordered table-striped">
    <thead>
        <tr><th colspan="12" class="text-center">OTRAS HABITACIONES</th></tr>
    </thead>
 
    @foreach ($inventario->propiedad->habitaciones as $i => $habitacion)
    <thead>
        <tr><th colspan="12" class="text-center">HABITACIÓN N° {{ $i + 1 }}</th></tr>
    </thead>
    <tbody>
        @php
            $filasHab = [
                ['Puerta',           'puerta',           'Cerradura',          'cerradura'],
                ['Llaves',           'llaves',            'Ventana',            'ventana'],
                ['Vidrio ventana',   'vidrio',            'Rieles',             'rieles'],
                ['Cortinas',         'cortinas',          'Rejas',              'rejas'],
                ['Pisos',            'pisos',             'Alfombras',          'alfombras'],
                ['Paredes',          'paredes',           'Techos',             'techos'],
                ['Tomas eléctricas', 'tomacorrientes',    'Tomas de telefonía', 'tomas_telefonicas'],
                ['Tomas de TV',      'tomas_television',  'Interruptores',      'interruptores'],
                ['Rosetas',          'rosetas',           'Lámparas',           'lamparas'],
                ['Bombillos',        'bombillos',         'Guarda escobas',     'guarda_escobas'],
                ['Aires acond.',     'aires_acondicionados', 'Ventiladores',    'ventiladores'],
                ['Anjeos',           'anjeos',            'Closet',             'closet'],
                ['Entrepaños',       'entrepanos',        'Puertas closet',     'puertas'],
                ['Cajones closet',   'cajones',           'Otros',              'otros'],
            ];
        @endphp
        @foreach ($filasHab as $fila)
        <tr>
            <td colspan="5">{{ $fila[0] }}</td>
            <td colspan="1"><b><u>{{ $habitacion->{$fila[1]} ?? '—' }}</u></b></td>
            <td colspan="5">{{ $fila[2] }}</td>
            <td colspan="1"><b><u>{{ $habitacion->{$fila[3]} ?? '—' }}</u></b></td>
        </tr>
        @endforeach
    </tbody>
    @endforeach
</table>
@endif
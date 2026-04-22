@foreach ($habitacion->observaciones as $observacion)
    <thead>
    <tr>
        <th colspan="12" class="text-center">HABITACIÓN N° {{ $incrementeTitle++ }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="" colspan="5">Puerta</td>
        <td class="" colspan="1">{{ $habitacion->puerta }}</td>
        <td class="" colspan="5">Cerradura</td>
        <td class="" colspan="1">{{ $habitacion->cerradura }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Llaves</td>
        <td class="" colspan="1">{{ $habitacion->llaves }}</td>
        <td class="" colspan="5">Ventana</td>
        <td class="" colspan="1">{{ $habitacion->ventana }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Vidrio</td>
        <td class="" colspan="1">{{ $habitacion->vidrio }}</td>
        <td class="" colspan="5">Rieles</td>
        <td class="" colspan="1">{{ $habitacion->rieles }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Cortinas</td>
        <td class="" colspan="1">{{ $habitacion->cortinas }}</td>
        <td class="" colspan="5">Rejas</td>
        <td class="" colspan="1">{{ $habitacion->rejas }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Alfombras</td>
        <td class="" colspan="1">{{ $habitacion->alfombras }}</td>
        <td class="" colspan="5">Pisos</td>
        <td class="" colspan="1">{{ $habitacion->pisos }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Paredes</td>
        <td class="" colspan="1">{{ $habitacion->paredes }}</td>
        <td class="" colspan="5">Techos</td>
        <td class="" colspan="1">{{ $habitacion->techos }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Tomas eléctricas</td>
        <td class="" colspan="1">{{ $habitacion->tomas_electricas }}</td>
        <td class="" colspan="5">Tomas de telefonia</td>
        <td class="" colspan="1">{{ $habitacion->tomas_telefonicas }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Tomas de televisión</td>
        <td class="" colspan="1">{{ $habitacion->tomas_television }}</td>
        <td class="" colspan="5">Interruptores</td>
        <td class="" colspan="1">{{ $habitacion->interruptores }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Rosetas</td>
        <td class="" colspan="1">{{ $habitacion->rosetas }}</td>
        <td class="" colspan="5">Lamparas</td>
        <td class="" colspan="1">{{ $habitacion->lamparas }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Bombillos</td>
        <td class="" colspan="1">{{ $habitacion->bombillos }}</td>
        <td class="" colspan="5">Guarda escobas</td>
        <td class="" colspan="1">{{ $habitacion->guarda_escobas }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Closet</td>
        <td class="" colspan="1">{{ $habitacion->closet }}</td>
        <td class="" colspan="5">Entrepaños</td>
        <td class="" colspan="1">{{ $habitacion->entrepanos }}</td>
    </tr>
    <tr>
        <td class="" colspan="5">Puertas closet</td>
        <td class="" colspan="1">{{ $habitacion->puertas }}</td>
        <td class="" colspan="5">Cajones closet</td>
        <td class="" colspan="1">{{ $habitacion->cajones }}</td>
    </tr>
    @endforeach
</tbody>
</table>
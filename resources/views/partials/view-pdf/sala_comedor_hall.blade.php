<table class="table table-bordered table-striped">
    <thead>
        <tr><th colspan="12" class="text-center">SALA, COMEDOR Y HALL</th></tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5">Puerta</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->puerta_hall }}</u></b></td>
            <td colspan="5">Ventana</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->ventana_hall }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Cortinas / Persianas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->cortinas_hall }}</u></b></td>
            <td colspan="5">Pisos</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->pisos_hall }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Paredes</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->paredes_hall }}</u></b></td>
            <td colspan="5">Techos</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->techos_hall }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Tomas eléctricas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->tomas_electricas_hall }}</u></b></td>
            <td colspan="5">Interruptores</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->interruptores_hall }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Lámparas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->lamparas_hall }}</u></b></td>
            <td colspan="5">Bombillos</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->bombillos_hall }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Muebles</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->muebles_hall }}</u></b></td>
            <td colspan="5">Ventiladores de techo</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->ventiladores_de_techo_hall }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Anjeos</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->anjeos_hall }}</u></b></td>
            <td colspan="5">Aires acondicionados</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->aires_acondicionados_hall }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Detector de humo</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->detecto_de_humo_hall }}</u></b></td>
            <td colspan="5">Otros</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->otros_hall }}</u></b></td>
        </tr>
        {{-- Balcón --}}
        <tr><td colspan="12" class="text-center font-weight-bold" style="background:#f8f9fa;">BALCÓN</td></tr>
        <tr>
            <td colspan="5">Iluminación balcón</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->iluminacion_balcon }}</u></b></td>
            <td colspan="5">Vidrio balcón</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->vidrio_balcon }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Baranda balcón</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->baranda_balcon }}</u></b></td>
            <td colspan="5">Piso balcón</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->piso_balcon }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Paredes balcón</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->paredes_balcon }}</u></b></td>
            <td colspan="5"></td>
            <td colspan="1"></td>
        </tr>
    </tbody>
</table>
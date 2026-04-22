<table class="table table-bordered table-striped">
    <thead>
        <tr><th colspan="12" class="text-center">ENTRADA PRINCIPAL</th></tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5">Puerta Principal</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->puerta_principal }}</u></b></td>
            <td colspan="5">Cerradura Puerta</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->cerradura_puerta }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Otras puertas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->otras_puertas }}</u></b></td>
            <td colspan="5">Ventana</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->ventana }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Pisos</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->pisos }}</u></b></td>
            <td colspan="5">Paredes</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->paredes }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Techos</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->techos }}</u></b></td>
            <td colspan="5">Tomas eléctricas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->tomas_electricas }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Interruptores</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->interruptores }}</u></b></td>
            <td colspan="5">Lámparas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->lamparas }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Persianas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->persianas }}</u></b></td>
            <td colspan="5">Material persianas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->tipo_material_persianas }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Escaleras</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->escaleras }}</u></b></td>
            <td colspan="5">Timbre</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->timbre }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Citofonos</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->citofonos }}</u></b></td>
            <td colspan="5">Rosetas</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->rosetas }}</u></b></td>
        </tr>
        <tr>
            <td colspan="5">Tablero eléctrico</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->caja_de_fusibles }}</u></b></td>
            <td colspan="5">Otros</td>
            <td colspan="1"><b><u>{{ $inventario->propiedad->otros }}</u></b></td>
        </tr>
    </tbody>
</table>
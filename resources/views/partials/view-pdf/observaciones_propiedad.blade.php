<table class="table table-bordered table-striped">
    <thead>
        <tr>
          <th colspan="12" class="text-center">OBSERVACIONES</th>
        </tr>
    </thead>
    @foreach ($inventario->propiedad->observaciones as $observacion)
    <tbody>
    <tr>
        <td colspan="6">
            <p>{{ $observacion->observaciones }}</p>
            <img src="{{ str_replace('public/', '', asset('storage/' . $observacion->imagen_evidencia)) }}" alt="Evidencia" width="200" height="200">
        </td>
    </tr>
    @endforeach
</tbody>
</table>
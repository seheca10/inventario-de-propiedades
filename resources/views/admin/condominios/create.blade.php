<!-- Modal -->
<div class="modal fade" id="crearCondominio" tabindex="-1" aria-labelledby="crearCondominio" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Añadir un nuevo condominio</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('condominios.store') }}" method="post">

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input required type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingresa el nombre del condominio">
                </div>

                <button type="submit" class="btn btn-sm btn-primary d-block m-auto">Crear</button>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>
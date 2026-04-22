<div>
    <form wire:submit.prevent="submit">
        <div class="row">

            <div class="col-md-12">
                <h5 class="text-center">ENTRADA PRINCIPAL</h3>
                <hr>
            </div>

            @include('steps_form.cocina')

            @include('steps_form.hall')
        </div>

        <button type="submit" class="btn btn-sm btn-primary">Siguiente etapa</button>
    </form>    
</div>

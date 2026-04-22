<div class="container-fluid py-4">
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
        
        <div class="row">
            @include('steps_form.informacion_inicial')
        </div>

        <button type="submit" class="btn-primary btn-sm btn">Siguiente etapa</button>
    </form>
</div>
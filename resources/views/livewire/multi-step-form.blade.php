<div>
    <form wire:submit.prevent="store">
        
        <div class="row">
            @include('steps_form.informacion_inicial')
        </div>

        <button type="submit" class="btn-primary btn-sm btn">Siguiente etapa</button>
    </form>  
</div>
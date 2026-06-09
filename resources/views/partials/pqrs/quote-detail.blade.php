{{--
    NUEVO: Partial extraído de show.blade.php para eliminar duplicación.

    Antes en show.blade.php había dos bloques casi idénticos:
      @if($ticket->approved_quote)  ... 100 líneas de HTML ...
      @elseif($ticket->rejected_quote) ... 95 líneas casi iguales ...

    Ahora ambos casos usan este partial con $quote y $type como variables.

    Uso:
      @include('partials.pqrs.quote-detail', ['quote' => $ticket->approved_quote, 'type' => 'approved'])
      @include('partials.pqrs.quote-detail', ['quote' => $ticket->rejected_quote, 'type' => 'rejected'])

    Variables requeridas:
      $quote  → instancia de TicketQuote
      $type   → 'approved' | 'rejected'
--}}

@php
    $isApproved   = $type === 'approved';
    $totalColor   = $isApproved ? 'text-success' : 'text-danger';
    $totalLabel   = $isApproved ? 'Total Presupuesto' : 'Total Presupuesto';
    $detailLabel  = $isApproved ? 'Detalle de la Cotización:' : 'Motivo del rechazo:';
    $detailText   = $isApproved
        ? ($quote->description ?? 'Sin especificaciones detalladas.')
        : ($quote->rejection_reason ?? 'Sin especificaciones detalladas.');
@endphp

<div class="row py-3 mb-2">

    <div class="col-md-4 text-center border-right">
        <span class="text-muted small uppercase font-weight-bold d-block">Mano de Obra</span>
        <h3 class="text-bold mt-1 mb-0 font-lg text-white-50">
            $ {{ number_format($quote->labor_cost, 0, ',', '.') }}
        </h3>
    </div>

    <div class="col-md-4 text-center border-right">
        <span class="text-muted small uppercase font-weight-bold d-block">Materiales</span>
        <h3 class="text-bold mt-1 mb-0 text-white-50">
            $ {{ number_format($quote->material_cost, 0, ',', '.') }}
        </h3>
    </div>

    <div class="col-md-4 text-center">
        <span class="{{ $totalColor }} small uppercase font-weight-bold d-block">{{ $totalLabel }}</span>
        <h2 class="text-bold mt-1 mb-0 {{ $totalColor }} font-xl">
            $ {{ number_format($quote->total_amount, 0, ',', '.') }}
        </h2>
    </div>

</div>

<div class="mx-0 p-3 bg-dark-50 rounded border text-center"
     style="background: rgba(255,255,255,0.05);">
    <label class="text-muted small uppercase font-weight-bold d-block mb-1">
        {{ $detailLabel }}
    </label>
    <p class="mb-0 text-white" style="font-size: 1.1rem;">
        {{ $detailText }}
    </p>
</div>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización de Reparación - {{ $ticket->ticket_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .quote-card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-approve { background-color: #28a745; color: white; border-radius: 30px; padding: 12px 30px; font-weight: bold; }
        .btn-reject { background-color: #dc3545; color: white; border-radius: 30px; padding: 12px 30px; font-weight: bold; }
        .price-tag { font-size: 2rem; color: #28a745; font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <img src="{{ asset('assets/images/LOGO-3D-PAGINA-CARTAGENA-NORTE-color.png') }}" alt="Cartagena Norte Inmobiliaria Logo" height="80" class="mb-3">
        <h2 class="font-weight-bold">Gestión de Cotización</h2>
        <p class="text-muted">Ticket #{{ $ticket->ticket_number }}</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card quote-card">
                <div class="card-body p-4">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <h4 class="mb-4"><i class="fas fa-info-circle text-primary mr-2"></i>Detalles del Trabajo</h4>
                    
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <p class="mb-0 text-muted">Contrato</p>
                            <p class="font-weight-bold">{{ $ticket->contract_number }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0 text-muted">Categoría</p>
                            <p class="font-weight-bold">{{ $ticket->category }} | {{ $ticket->issue_type }}</p>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded mb-4">
                        <h6><strong>Descripción del Contratista:</strong></h6>
                        <p class="text-secondary mb-0">{{ $quote->description }}</p>
                    </div>

                    <hr>

                    <h4 class="mb-4"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Presupuesto</h4>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Mano de Obra</span>
                        <span class="font-weight-bold">$ {{ number_format($quote->labor_cost,  0, ',', '.') }} COP</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Materiales</span>
                        <span class="font-weight-bold">$ {{ number_format($quote->material_cost,  0, ',', '.') }} COP</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <span class="h5">Total a Pagar</span>
                        <span class="price-tag">$ {{ number_format($quote->total_amount, 0, ',', '.') }} COP</span>
                    </div>

                    @if($quote->status == 'pending')
                        <div class="mt-5 row">
                            <div class="col-md-6 mb-2">
                                <form action="{{ route('pqrs.quote.approve', $quote->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-approve btn-block shadow">
                                        <i class="fas fa-check-circle mr-2"></i> Aprobar Cotización
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-reject btn-block shadow" data-toggle="modal" data-target="#modalReject">
                                    <i class="fas fa-times-circle mr-2"></i> Rechazar
                                </button>
                            </div>
                        </div>
                    @else
                        @if ($quote->status === 'approved')
                            <div class="alert alert-info mt-4 text-center">
                                Esta cotización ya se encuentra: <strong>{{ strtoupper($quote->status_label) }}</strong>
                            </div>
                        @elseif($quote->status === 'rejected')
                            <div class="alert alert-danger mt-4 text-center">
                                Esta cotización se encuentra: <strong>{{ strtoupper($quote->status_label) }}</strong>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            
            <p class="text-center mt-4 text-muted small">
                Al hacer clic en aprobar, autorizas la ejecución de los trabajos descritos anteriormente.
            </p>
        </div>
    </div>
</div>

<!-- Modal Rechazo -->
<div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pqrs.quote.reject', $quote->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Rechazar Cotización</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Por favor, indícanos el motivo del rechazo:</label>
                    <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Ej: El costo es muy elevado / No estoy de acuerdo con los materiales..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@extends('adminlte::page')

@section('title', 'Registrar Visita')

@section('content_header')
@stop

@section('content')
<style>
    .rv-page { max-width: 95%; margin: 0 auto; padding: 1.5rem 0 3rem; }

    .rv-hero {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .rv-hero-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: #1a3c5e;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .rv-hero-icon i { color: #fff; font-size: 22px; }
    .rv-hero-title { font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.2; }
    .rv-hero-sub   { font-size: .85rem; margin: 2px 0 0; }

    /* Cards */
    .rv-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .rv-section-head {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: .75rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .rv-section-head i { font-size: 16px; color: #1a3c5e; }
    .rv-section-head span { font-size: .82rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #1a3c5e; }
    .rv-section-body { padding: 1.25rem; }

    /* Info grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .75rem 1rem;
    }
    @media (max-width: 600px) { .info-grid { grid-template-columns: repeat(2, 1fr); } }
    .info-cell label {
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #94a3b8;
        margin: 0 0 3px;
        display: block;
    }
    .info-cell span {
        font-size: .9rem;
        font-weight: 600;
        color: #1e293b;
    }
    .badge-priority {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 700;
        background: #fef9c3;
        color: #854d0e;
    }

    /* Textarea */
    .rv-textarea {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: .75rem 1rem;
        font-size: .92rem;
        color: #1e293b;
        resize: vertical;
        transition: border-color .15s;
        background: #f8fafc;
    }
    .rv-textarea:focus { outline: none; border-color: #2d7dd2; background: #fff; }

    /* Firma */
    .firma-canvas-wrap {
        border: 1.5px dashed #cbd5e1;
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
        transition: border-color .2s;
        cursor: crosshair;
    }
    .firma-canvas-wrap:hover { border-color: #2d7dd2; }
    .kbw-signature { width: 100%; height: 200px; }
    #sig_arrendador canvas { width: 100% !important; height: auto; }

    .firma-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: .75rem;
        flex-wrap: wrap;
    }
    .btn-limpiar {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px;
        border: 1px solid #fca5a5;
        background: #fff;
        color: #dc2626;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-limpiar:hover { background: #fef2f2; }

    .firma-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: .78rem;
        font-weight: 600;
    }
    .firma-pill.empty { background: #fef9c3; color: #854d0e; }
    .firma-pill.ok    { background: #dcfce7; color: #166534; }

    /* Footer */
    .rv-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 1rem;
    }
    .btn-volver {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569;
        border-radius: 10px;
        font-size: .9rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-volver:hover { background: #f1f5f9; color: #1e293b; text-decoration: none; }
    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 28px;
        background: #16a34a;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-submit:hover { background: #15803d; }

    .help-text { font-size: .78rem; color: #94a3b8; margin-top: 6px; }
</style>

<div class="rv-page">

    {{-- Hero --}}
    <div class="rv-hero">
        <div class="rv-hero-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div>
            <p class="rv-hero-title">Registrar Visita</p>
            <p class="rv-hero-sub">{{ $schedule->type_label }} &nbsp;·&nbsp; Ticket #{{ $ticket->ticket_number }}</p>
        </div>
    </div>

    <form action="{{ route('contractors.register-scheulde', $ticket->id) }}"
          method="POST"
          id="formVisita">
        @csrf
        <input type="hidden" name="ticket_id"     value="{{ $ticket->id }}">
        <input type="hidden" name="contractor_id" value="{{ $contractor->id }}">

        {{-- Info del ticket --}}
        <div class="rv-section">
            <div class="rv-section-head">
                <i class="fas fa-ticket-alt"></i>
                <span>Información del ticket</span>
            </div>
            <div class="rv-section-body">
                <div class="info-grid">
                    <div class="info-cell">
                        <label>Número</label>
                        <span>#{{ $ticket->ticket_number }}</span>
                    </div>
                    <div class="info-cell">
                        <label>Tipo de visita</label>
                        <span>{{ $schedule->type_label }}</span>
                    </div>
                    <div class="info-cell">
                        <label>Categoría</label>
                        <span>{{ $ticket->category ?? '—' }}</span>
                    </div>
                    <div class="info-cell">
                        <label>Arrendatario</label>
                        <span>{{ $ticket->tenant_name ?? '—' }}</span>
                    </div>
                    <div class="info-cell">
                        <label>Fecha de visita</label>
                        <span>{{ \Carbon\Carbon::parse($schedule->confirmed_at)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-cell">
                        <label>Prioridad</label>
                        <span class="badge-priority">{{ ucfirst($ticket->priority_label ?? '—') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reporte --}}
        <div class="rv-section">
            <div class="rv-section-head">
                <i class="fas fa-file-alt"></i>
                <span>Reporte de la visita</span>
            </div>
            <div class="rv-section-body">
                <textarea name="report"
                          class="rv-textarea @error('report') is-invalid @enderror"
                          rows="5"
                          placeholder="Detalle el diagnóstico, materiales observados, trabajos sugeridos..."
                          required>{{ old('report') }}</textarea>
                @error('report')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <p class="help-text">Sea lo más específico posible para facilitar la aprobación.</p>
            </div>
        </div>

        {{-- Firma --}}
        <div class="rv-section">
            <div class="rv-section-head">
                <i class="fas fa-pen-nib"></i>
                <span>Firma de aceptación — Arrendador</span>
            </div>
            <div class="rv-section-body">
                <p class="help-text mb-3" style="margin-bottom:.75rem;">
                    El arrendador firma aquí para confirmar que autoriza el registro de esta visita.
                </p>
                <div class="firma-canvas-wrap">
                    <div id="sig_arrendador"></div>
                </div>
                <textarea id="firma_arrendador_field" name="firma_arrendador" style="display:none"></textarea>
                @error('firma_arrendador')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="firma-bar">
                    <button type="button" class="btn-limpiar" id="btn_limpiar">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                    <span id="firma_status" class="firma-pill empty">
                        <i class="fas fa-exclamation-circle"></i> Sin firmar
                    </span>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="rv-actions">
            <a href="{{ route('contractors.admin') }}" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn-submit" onclick="return validarFirma()">
                <i class="fas fa-paper-plane"></i> Finalizar visita
            </button>
        </div>

    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
          integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
          crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
    <script>
        var sig = $('#sig_arrendador').signature({
            syncField : '#firma_arrendador_field',
            syncFormat: 'PNG',
            change: function () {
                var val = $('#firma_arrendador_field').val();
                if (val && val.length > 100) {
                    $('#firma_status').removeClass('empty').addClass('ok')
                        .html('<i class="fas fa-check-circle"></i> Firma registrada');
                } else {
                    $('#firma_status').removeClass('ok').addClass('empty')
                        .html('<i class="fas fa-exclamation-circle"></i> Sin firmar');
                }
            }
        });

        $('#btn_limpiar').click(function (e) {
            e.preventDefault();
            sig.signature('clear');
            $('#firma_arrendador_field').val('');
            $('#firma_status').removeClass('ok').addClass('empty')
                .html('<i class="fas fa-exclamation-circle"></i> Sin firmar');
        });

        function validarFirma() {
            if (!$('#firma_arrendador_field').val() || $('#firma_arrendador_field').val().length < 100) {
                alert('Por favor registra la firma de aceptación antes de enviar.');
                return false;
            }
            return true;
        }
    </script>
@stop
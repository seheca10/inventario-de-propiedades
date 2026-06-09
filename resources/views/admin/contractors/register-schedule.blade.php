@extends('adminlte::page')

@section('title', ($ticket->status === 'visit_scheduled' ? 'Registrar Diagnóstico' : 'Finalizar Trabajo') . ' — ' . $ticket->ticket_number)

@section('content_header')
@stop

@section('content')

@php
    /**
     * Lógica para determinar el tipo de reporte
     * Buscamos primero si hay un trabajo programado, si no, tomamos la visita de diagnóstico
     */
    $schedule = $ticket->schedules()
        ->where('type', 'work')
        ->whereNotNull('confirmed_at')
        ->first() 
        ?? 
        $ticket->schedules()
        ->where('type', 'diagnostic')
        ->whereNotNull('confirmed_at')
        ->first();

    $isDiagnostic = $schedule && $schedule->type === 'diagnostic';
    
    // Variables dinámicas según el tipo de visita
    $pageTitle   = $isDiagnostic ? 'Registrar Diagnóstico' : 'Finalizar Trabajo';
    $heroIcon    = $isDiagnostic ? 'fa-search' : 'fa-check-double';
    $heroColor   = $isDiagnostic ? '#6f42c1' : '#16a34a';
    $sectionName = $isDiagnostic ? 'Reporte de Hallazgos' : 'Reporte de Trabajo Realizado';
    $placeholder = $isDiagnostic 
        ? 'Describe detalladamente el estado de la propiedad, daños encontrados y posibles soluciones para cotizar...' 
        : 'Describe detalladamente el trabajo realizado, materiales usados y observaciones finales del cierre...';
    $submitLabel = $isDiagnostic ? 'Registrar Diagnóstico' : 'Finalizar y Entregar Trabajo';
    $signLabel   = $isDiagnostic ? 'Firma de aceptación de visita/hallazgos' : 'Firma de conformidad — Arrendatario';
@endphp

<style>
    .rv-page { max-width: 95%; margin: 0 auto; padding: 1.5rem 0 3rem; }

    .rv-hero {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 2rem; padding-bottom: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .rv-hero-icon {
        width: 48px; height: 48px; border-radius: 12px;
        background: {{ $heroColor }};
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .rv-hero-icon i { color: #fff; font-size: 22px; }
    .rv-hero-title { font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.2; }
    .rv-hero-sub   { font-size: .85rem; margin: 2px 0 0; color: #64748b; }

    .rv-section {
        background: #fff; border: 1px solid #e2e8f0;
        border-radius: 14px; margin-bottom: 1.25rem; overflow: hidden;
    }
    .rv-section-head {
        display: flex; align-items: center; gap: 10px;
        padding: .75rem 1.25rem; background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .rv-section-head i  { font-size: 16px; color: {{ $heroColor }}; }
    .rv-section-head span {
        font-size: .82rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em; color: #1a3c5e;
    }
    .rv-section-body { padding: 1.25rem; }

    .info-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem 1rem;
    }
    @media (max-width: 600px) { .info-grid { grid-template-columns: repeat(2, 1fr); } }
    .info-cell label {
        font-size: .7rem; text-transform: uppercase; letter-spacing: .07em;
        color: #94a3b8; margin: 0 0 3px; display: block;
    }
    .info-cell span { font-size: .9rem; font-weight: 600; color: #1e293b; }

    .rv-textarea {
        width: 100%; border: 1px solid #e2e8f0; border-radius: 10px;
        padding: .75rem 1rem; font-size: .92rem; color: #1e293b;
        resize: vertical; background: #f8fafc; transition: border-color .15s;
    }
    .rv-textarea:focus { outline: none; border-color: {{ $heroColor }}; background: #fff; }

    /* Upload de archivos */
    .upload-zone {
        border: 2px dashed #cbd5e1; border-radius: 12px;
        padding: 2rem; text-align: center; cursor: pointer;
        background: #f8fafc; transition: all .2s;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: {{ $heroColor }}; background: #f0fff4;
    }
    .upload-zone i { font-size: 2rem; color: #94a3b8; margin-bottom: .5rem; }
    .upload-zone p { margin: 0; color: #64748b; font-size: .9rem; }
    .upload-zone small { color: #94a3b8; font-size: .78rem; }

    /* Preview de archivos */
    .preview-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: .5rem; margin-top: 1rem;
    }
    .preview-item {
        position: relative; border-radius: 8px; overflow: hidden;
        border: 1px solid #e2e8f0; aspect-ratio: 1;
        background: #f1f5f9;
    }
    .preview-item img, .preview-item video {
        width: 100%; height: 100%; object-fit: cover;
    }
    .preview-item .remove-btn {
        position: absolute; top: 4px; right: 4px;
        background: rgba(220,38,38,.85); color: #fff;
        border: none; border-radius: 50%; width: 20px; height: 20px;
        font-size: 10px; cursor: pointer; display: flex;
        align-items: center; justify-content: center;
    }

    /* Firma */
    .firma-canvas-wrap {
        border: 1.5px dashed #cbd5e1; border-radius: 10px;
        background: #fff; overflow: hidden; cursor: crosshair;
        transition: border-color .2s;
    }
    .firma-canvas-wrap:hover { border-color: {{ $heroColor }}; }
    .kbw-signature { width: 100%; height: 200px; }
    .firma-bar {
        display: flex; align-items: center; gap: 12px;
        margin-top: .75rem; flex-wrap: wrap;
    }
    .btn-limpiar {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border: 1px solid #fca5a5;
        background: #fff; color: #dc2626; border-radius: 8px;
        font-size: .8rem; font-weight: 600; cursor: pointer;
    }
    .btn-limpiar:hover { background: #fef2f2; }
    .firma-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 20px;
        font-size: .78rem; font-weight: 600;
    }
    .firma-pill.empty { background: #fef9c3; color: #854d0e; }
    .firma-pill.ok    { background: #dcfce7; color: #166534; }

    .rv-actions {
        display: flex; align-items: center; gap: 12px; padding-top: 1rem;
    }
    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 28px; background: {{ $heroColor }}; color: #fff;
        border: none; border-radius: 10px; font-size: .9rem;
        font-weight: 700; cursor: pointer;
    }
    .btn-submit:hover { opacity: 0.9; }
</style>

<div class="rv-page">

    {{-- Hero --}}
    <div class="rv-hero">
        <div class="rv-hero-icon">
            <i class="fas {{ $heroIcon }}"></i>
        </div>
        <div>
            <p class="rv-hero-title">{{ $pageTitle }}</p>
            <p class="rv-hero-sub text-white">
                Ticket #{{ $ticket->ticket_number }} &nbsp;·&nbsp;
                @if($schedule)
                    {{ \Carbon\Carbon::parse($schedule->confirmed_at)->translatedFormat('l d \d\e F Y') }}
                @endif
            </p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('contractors.register-scheulde', $ticket->id) }}"
          method="POST"
          enctype="multipart/form-data"
          id="formFinalizacion">
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
                        <label>Ticket</label>
                        <span>#{{ $ticket->ticket_number }}</span>
                    </div>
                    <div class="info-cell">
                        <label>Arrendatario</label>
                        <span>{{ $ticket->tenant_name }}</span>
                    </div>
                    <div class="info-cell">
                        <label>Tipo de Visita</label>
                        <span class="text-uppercase text-{{ $isDiagnostic ? 'purple' : 'success' }}">
                            {{ $isDiagnostic ? 'Diagnóstico' : 'Ejecución de Trabajo' }}
                        </span>
                    </div>
                    <div class="info-cell">
                        <label>Incidencia</label>
                        <span>{{ $ticket->issue_type ?? '—' }}</span>
                    </div>
                    @if($schedule)
                        <div class="info-cell">
                            <label>Fecha Programada</label>
                            <span>{{ \Carbon\Carbon::parse($schedule->confirmed_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    <div class="info-cell">
                        <label>Contrato</label>
                        <span>{{ $ticket->contract_number ?? '—' }}</span>
                    </div>
                </div>

                {{-- Resumen de cotización (Solo si ya hay una aprobada y estamos en fase de trabajo) --}}
                @if(!$isDiagnostic && $ticket->approved_quote)
                    <div class="mt-3 p-3 rounded" style="background:#f0fff4; border:1px solid #bbf7d0;">
                        <small class="font-weight-bold text-success d-block mb-1">
                            <i class="fas fa-check-circle mr-1"></i> Cotización aprobada para este trabajo
                        </small>
                        <div class="d-flex" style="gap:1rem; font-size:.85rem;">
                            <span>Total Presupuestado: <strong>$ {{ number_format($ticket->approved_quote->total_amount, 0, ',', '.') }}</strong></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Cuerpo del reporte --}}
        <div class="rv-section">
            <div class="rv-section-head">
                <i class="fas fa-file-alt"></i>
                <span>{{ $sectionName }}</span>
            </div>
            <div class="rv-section-body">
                <textarea name="report"
                          class="rv-textarea @error('report') is-invalid @enderror"
                          rows="5"
                          placeholder="{{ $placeholder }}"
                          required>{{ old('report') }}</textarea>
                @error('report')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <p class="help-text mt-2 text-muted small">
                    <i class="fas fa-info-circle mr-1"></i> 
                    @if($isDiagnostic)
                        Este reporte será utilizado para generar la cotización formal al propietario.
                    @else
                        Este reporte confirma el cierre técnico del incidente.
                    @endif
                </p>
            </div>
        </div>

        {{-- Evidencia --}}
        <div class="rv-section">
            <div class="rv-section-head">
                <i class="fas fa-camera"></i>
                <span>Evidencia Multimedia (Fotos/Video)</span>
            </div>
            <div class="rv-section-body">
                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('mediaInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p class="mt-2 mb-1 font-weight-bold">Toca para añadir evidencia</p>
                    <small>Captura el estado @if($isDiagnostic) actual del daño @else final del arreglo @endif</small>
                </div>
                <input type="file" id="mediaInput" name="media[]" multiple accept="image/*,video/*" style="display:none">
                <div class="preview-grid" id="previewGrid"></div>
            </div>
        </div>

        {{-- Firma --}}
        <div class="rv-section">
            <div class="rv-section-head">
                <i class="fas fa-pen-nib"></i>
                <span>{{ $signLabel }}</span>
            </div>
            <div class="rv-section-body">
                <div class="firma-canvas-wrap">
                    <div id="sig_arrendador"></div>
                </div>
                <textarea id="firma_arrendador_field" name="firma_arrendador" style="display:none"></textarea>
                @error('firma_arrendador')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="firma-bar">
                    <button type="button" class="btn-limpiar" id="btn_limpiar">
                        <i class="fas fa-eraser"></i> Limpiar Firma
                    </button>
                    <span id="firma_status" class="firma-pill empty">
                        <i class="fas fa-exclamation-circle"></i> Pendiente de firma
                    </span>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="rv-actions">
            <a href="{{ route('contractors.admin') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius:10px;">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
            <button type="submit" class="btn-submit" onclick="return validarFormulario()">
                <i class="fas fa-paper-plane mr-1"></i> {{ $submitLabel }}
            </button>
        </div>

    </form>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
    <style>
        .text-purple { color: #6f42c1 !important; }
        .help-text { font-size: .8rem; }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>

    <script>
    // Configuración Firma
    var sig = $('#sig_arrendador').signature({
        syncField : '#firma_arrendador_field',
        syncFormat: 'PNG',
        change: function () {
            var val = $('#firma_arrendador_field').val();
            if (val && val.length > 100) {
                $('#firma_status').removeClass('empty').addClass('ok')
                    .html('<i class="fas fa-check-circle"></i> Firma capturada');
            } else {
                $('#firma_status').removeClass('ok').addClass('empty')
                    .html('<i class="fas fa-exclamation-circle"></i> Pendiente');
            }
        }
    });

    $('#btn_limpiar').click(function(e) {
        e.preventDefault();
        sig.signature('clear');
        $('#firma_arrendador_field').val('');
    });

    // Preview de Archivos
    const mediaInput  = document.getElementById('mediaInput');
    const previewGrid = document.getElementById('previewGrid');
    let allFiles = new DataTransfer();

    mediaInput.addEventListener('change', () => {
        Array.from(mediaInput.files).forEach(file => {
            allFiles.items.add(file);
            renderPreview(file, allFiles.files.length - 1);
        });
        mediaInput.files = allFiles.files;
    });

    function renderPreview(file, index) {
        const item = document.createElement('div');
        item.className = 'preview-item';
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '✕';
        removeBtn.type = 'button';
        removeBtn.onclick = () => {
            item.remove();
            // Lógica para quitar del DataTransfer si es necesario
        };

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            item.appendChild(img);
        } else {
            item.innerHTML = `<div class="p-2 text-center small"><i class="fas fa-file-video fa-2x"></i><br>Video</div>`;
        }
        item.appendChild(removeBtn);
        previewGrid.appendChild(item);
    }

    function validarFormulario() {
        const firma = document.getElementById('firma_arrendador_field').value;
        if (!firma || firma.length < 100) {
            alert('La firma del arrendatario es obligatoria para validar este reporte.');
            return false;
        }
        return true;
    }
    </script>
@stop
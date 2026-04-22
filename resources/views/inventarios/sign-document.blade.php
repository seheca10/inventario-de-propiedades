@extends('adminlte::page')

@section('title', 'Firmar Inventario')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0" style="font-weight:700; color:#1a3c5e;">
            <i class="fas fa-pen-fancy mr-2"></i> Firma de Inventario
        </h1>
        <small class="text-muted ml-2">— {{ $inventario->arrendatario }}</small>
    </div>
@stop

@section('content')

<style>
    :root {
        --sf-primary: #1a3c5e;
        --sf-accent:  #2d7dd2;
        --sf-success: #28a745;
        --sf-danger:  #dc3545;
        --sf-border:  #dee2e6;
        --sf-radius:  10px;
    }

    .firma-card {
        background: #fff;
        border-radius: var(--sf-radius);
        box-shadow: 0 2px 12px rgba(0,0,0,.08);
        overflow: hidden;
    }
    .firma-card-header {
        background: var(--sf-primary);
        color: #fff;
        padding: .9rem 1.4rem;
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    /* Toggle tipo firma */
    .tipo-firma-toggle {
        display: flex;
        gap: .5rem;
        margin-bottom: 1rem;
    }
    .tipo-firma-btn {
        flex: 1;
        padding: .45rem 0;
        text-align: center;
        border: 2px solid var(--sf-border);
        border-radius: 6px;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        color: #6c757d;
        background: #f8f9fa;
        transition: all .2s;
        user-select: none;
    }
    .tipo-firma-btn.active {
        border-color: var(--sf-accent);
        background: #e8f0fe;
        color: var(--sf-accent);
    }
    /* Input radio oculto */
    .tipo-firma-btn input[type="radio"] { display: none; }

    /* Canvas de firma */
    .canvas-wrapper {
        border: 2px dashed var(--sf-border);
        border-radius: 8px;
        background: #fafafa;
        overflow: hidden;
        transition: border-color .2s;
    }
    .canvas-wrapper:hover { border-color: var(--sf-accent); }
    .canvas-wrapper canvas {
        width: 100% !important;
        height: auto;
        display: block;
        cursor: crosshair;
    }
    .kbw-signature { width: 100%; height: 200px; }

    /* Preview imagen */
    .img-preview {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 6px;
        border: 1px solid var(--sf-border);
        margin-top: .5rem;
        display: none;
    }

    /* Botón limpiar */
    .btn-clear {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        margin-top: .6rem;
        padding: .3rem .8rem;
        background: #fff3f3;
        border: 1px solid var(--sf-danger);
        color: var(--sf-danger);
        border-radius: 5px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-clear:hover { background: #fde8e8; }

    /* Botón submit */
    .btn-firmar {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .7rem 2.5rem;
        background: var(--sf-success);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s, transform .1s;
    }
    .btn-firmar:hover  { opacity: .9; }
    .btn-firmar:active { transform: scale(.98); }

    /* Indicador de estado firma */
    .firma-status {
        font-size: .78rem;
        margin-top: .4rem;
        padding: .3rem .7rem;
        border-radius: 5px;
        display: none;
    }
    .firma-status.ok    { background: #d4edda; color: #155724; display: block; }
    .firma-status.empty { background: #fff3cd; color: #856404; display: block; }
</style>

<form action="{{ route('signed-documents.store') }}"
      method="POST"
      enctype="multipart/form-data"
      id="firmaForm">
    @csrf
    <input type="hidden" name="inventario_id" value="{{ $inventario->id }}">

    <div class="row">

        {{-- ══════════════════════════ ARRENDATARIO ══════════════════════════ --}}
        <div class="col-md-6 mb-4">
            <div class="firma-card">
                <div class="firma-card-header">
                    <i class="fas fa-user"></i> Firma del Arrendatario
                </div>
                <div class="p-3">

                    {{-- Toggle tipo --}}
                    <div class="tipo-firma-toggle">
                        <label class="tipo-firma-btn active" id="btn_digital_arrendatario">
                            <input type="radio" name="tipo_firma_arrendatario"
                                   value="digital" checked
                                   onchange="toggleFirma('arrendatario','digital')">
                            <i class="fas fa-pen-nib mr-1"></i> Firma digital
                        </label>
                        <label class="tipo-firma-btn" id="btn_imagen_arrendatario">
                            <input type="radio" name="tipo_firma_arrendatario"
                                   value="imagen"
                                   onchange="toggleFirma('arrendatario','imagen')">
                            <i class="fas fa-image mr-1"></i> Subir imagen
                        </label>
                    </div>

                    {{-- Canvas --}}
                    <div id="panel_digital_arrendatario">
                        <div class="canvas-wrapper">
                            <div id="sig_arrendatario"></div>
                        </div>
                        <textarea id="firma_arrendatario_b64"
                                  name="firma_arrendatario"
                                  style="display:none"></textarea>
                        <button type="button" class="btn-clear"
                                onclick="limpiarFirma('arrendatario')">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <div id="status_arrendatario" class="firma-status empty">
                            ⚠️ Aún no has firmado
                        </div>
                    </div>

                    {{-- Imagen --}}
                    <div id="panel_imagen_arrendatario" style="display:none;">
                        <input type="file" name="imagen_firma_arrendatario"
                               class="form-control"
                               accept="image/*"
                               onchange="previewImagen(this, 'preview_arrendatario')">
                        <small class="text-muted">Formatos: JPG, PNG — máx. 2MB</small>
                        <img id="preview_arrendatario" class="img-preview" alt="Vista previa">
                    </div>

                    @error('firma_arrendatario')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('imagen_firma_arrendatario')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ══════════════════════════ ARRENDADOR ════════════════════════════ --}}
        <div class="col-md-6 mb-4">
            <div class="firma-card">
                <div class="firma-card-header">
                    <i class="fas fa-building"></i> Firma del Arrendador
                </div>
                <div class="p-3">

                    <div class="tipo-firma-toggle">
                        <label class="tipo-firma-btn active" id="btn_digital_arrendador">
                            <input type="radio" name="tipo_firma_arrendador"
                                   value="digital" checked
                                   onchange="toggleFirma('arrendador','digital')">
                            <i class="fas fa-pen-nib mr-1"></i> Firma digital
                        </label>
                        <label class="tipo-firma-btn" id="btn_imagen_arrendador">
                            <input type="radio" name="tipo_firma_arrendador"
                                   value="imagen"
                                   onchange="toggleFirma('arrendador','imagen')">
                            <i class="fas fa-image mr-1"></i> Subir imagen
                        </label>
                    </div>

                    <div id="panel_digital_arrendador">
                        <div class="canvas-wrapper">
                            <div id="sig_arrendador"></div>
                        </div>
                        <textarea id="firma_arrendador_b64"
                                  name="firma_arrendador"
                                  style="display:none"></textarea>
                        <button type="button" class="btn-clear"
                                onclick="limpiarFirma('arrendador')">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <div id="status_arrendador" class="firma-status empty">
                            ⚠️ Aún no has firmado
                        </div>
                    </div>

                    <div id="panel_imagen_arrendador" style="display:none;">
                        <input type="file" name="imagen_firma_arrendador"
                               class="form-control"
                               accept="image/*"
                               onchange="previewImagen(this, 'preview_arrendador')">
                        <small class="text-muted">Formatos: JPG, PNG — máx. 2MB</small>
                        <img id="preview_arrendador" class="img-preview" alt="Vista previa">
                    </div>

                    @error('firma_arrendador')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('imagen_firma_arrendador')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    {{-- Submit --}}
    <div class="text-center my-3">
        <button type="submit" class="btn-firmar" id="submitBtn">
            <i class="fas fa-check-circle"></i> Guardar firmas y finalizar
        </button>
    </div>

</form>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/jquery-signature@1.0.0/jquery.signature.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
<script>
    // ─── Inicializar canvas ────────────────────────────────────────────────────
    var sigArrendatario = $('#sig_arrendatario').signature({
        syncField: '#firma_arrendatario_b64',
        syncFormat: 'PNG',
        change: function() { actualizarStatus('arrendatario'); }
    });

    var sigArrendador = $('#sig_arrendador').signature({
        syncField: '#firma_arrendador_b64',
        syncFormat: 'PNG',
        change: function() { actualizarStatus('arrendador'); }
    });

    // ─── Limpiar canvas ───────────────────────────────────────────────────────
    function limpiarFirma(parte) {
        if (parte === 'arrendatario') {
            sigArrendatario.signature('clear');
            $('#firma_arrendatario_b64').val('');
        } else {
            sigArrendador.signature('clear');
            $('#firma_arrendador_b64').val('');
        }
        actualizarStatus(parte);
    }

    // ─── Actualizar indicador de estado ──────────────────────────────────────
    function actualizarStatus(parte) {
        var val  = $('#firma_' + parte + '_b64').val();
        var $st  = $('#status_' + parte);
        if (val && val.length > 100) {
            $st.removeClass('empty').addClass('ok').text('✅ Firma registrada');
        } else {
            $st.removeClass('ok').addClass('empty').text('⚠️ Aún no has firmado');
        }
    }

    // ─── Toggle entre digital e imagen ───────────────────────────────────────
    function toggleFirma(parte, tipo) {
        var $btnDigital = $('#btn_digital_' + parte);
        var $btnImagen  = $('#btn_imagen_'  + parte);
        var $panelDig   = $('#panel_digital_' + parte);
        var $panelImg   = $('#panel_imagen_'  + parte);

        if (tipo === 'digital') {
            $btnDigital.addClass('active');
            $btnImagen.removeClass('active');
            $panelDig.show();
            $panelImg.hide();
            $('input[name="imagen_firma_' + parte + '"]').val('');
            $('#preview_' + parte).hide();
        } else {
            $btnImagen.addClass('active');
            $btnDigital.removeClass('active');
            $panelImg.show();
            $panelDig.hide();
            limpiarFirma(parte);
            $('#status_' + parte).hide();
        }
    }

    // ─── Preview de imagen subida ─────────────────────────────────────────────
    function previewImagen(input, previewId) {
        var $preview = $('#' + previewId);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).show();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ─── Validar antes de enviar ──────────────────────────────────────────────
    $('#firmaForm').on('submit', function(e) {
        var errores = [];

        ['arrendatario', 'arrendador'].forEach(function(parte) {
            var tipo = $('input[name="tipo_firma_' + parte + '"]:checked').val();
            if (tipo === 'digital') {
                var val = $('#firma_' + parte + '_b64').val();
                if (!val || val.length < 100) {
                    errores.push('La firma de ' + parte + ' está vacía.');
                }
            } else {
                var file = $('input[name="imagen_firma_' + parte + '"]').val();
                if (!file) {
                    errores.push('Debes subir una imagen de firma para ' + parte + '.');
                }
            }
        });

        if (errores.length > 0) {
            e.preventDefault();
            alert('Por favor corrige lo siguiente:\n\n' + errores.join('\n'));
        }
    });
</script>
@stop
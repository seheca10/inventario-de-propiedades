{{-- livewire/form-step-propiedad.blade.php --}}
<div>

    {{-- ═══════════════════════════════════════════════════════════════
         ESTILOS INTERNOS — elimina esto si ya tienes un CSS compilado
    ══════════════════════════════════════════════════════════════════ --}}
    @once
    <style>
        /* ── Variables ── */
        :root {
            --c-primary:   #1a3c5e;
            --c-accent:    #2d7dd2;
            --c-success:   #28a745;
            --c-danger:    #dc3545;
            --c-muted:     #6c757d;
            --c-surface:   #ffffff;
            --c-bg:        #f4f6f9;
            --c-border:    #dee2e6;
            --radius:      10px;
            --shadow:      0 2px 12px rgba(0,0,0,.08);
        }

        /* ── Tarjeta contenedora ── */
        .step-wrapper {
            background: var(--c-bg);
            border-radius: var(--radius);
            padding: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        /* ── Header del paso ── */
        .step-header {
            background: var(--c-primary);
            color: #fff;
            border-radius: var(--radius) var(--radius) 0 0;
            padding: 1.25rem 1.75rem 1rem;
        }
        .step-header .step-number {
            font-size: .75rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            opacity: .7;
            margin-bottom: .2rem;
        }
        .step-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.15rem;
        }

        /* ── Barra de progreso ── */
        .progress-bar-track {
            height: 6px;
            background: rgba(255,255,255,.25);
            border-radius: 3px;
            margin-top: .75rem;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: #5bc8f5;
            border-radius: 3px;
            transition: width .4s ease;
        }

        /* ── Steps indicadores (puntos) ── */
        .steps-dots {
            display: flex;
            gap: .5rem;
            margin-top: .6rem;
        }
        .step-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,.3);
            transition: all .3s ease;
        }
        .step-dot.active  { background: #5bc8f5; transform: scale(1.4); }
        .step-dot.done    { background: rgba(255,255,255,.7); }

        /* ── Cuerpo ── */
        .step-body {
            padding: 1.5rem 1.75rem;
            background: var(--c-surface);
        }

        /* ── Footer de navegación ── */
        .step-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.75rem;
            background: var(--c-bg);
            border-top: 1px solid var(--c-border);
            border-radius: 0 0 var(--radius) var(--radius);
        }
        .step-footer .counter {
            font-size: .8rem;
            color: var(--c-muted);
        }

        /* ── Botones de navegación ── */
        .btn-nav {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1.2rem;
            border-radius: 6px;
            font-size: .875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
        }
        .btn-nav:active { transform: scale(.97); }
        .btn-nav:disabled { opacity: .5; cursor: not-allowed; }
        .btn-prev { background: #fff; border: 1px solid var(--c-border); color: var(--c-primary); }
        .btn-next { background: var(--c-accent); color: #fff; }
        .btn-submit { background: var(--c-success); color: #fff; }

        /* ── Campo de formulario dentro del step ── */
        .field-card {
            background: var(--c-bg);
            border: 1px solid var(--c-border);
            border-radius: 8px;
            padding: .85rem 1rem;
            margin-bottom: .6rem;
            transition: border-color .2s;
        }
        .field-card:hover { border-color: var(--c-accent); }
        .field-card label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--c-primary);
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .3rem;
            display: block;
        }
        .field-card select,
        .field-card input,
        .field-card textarea {
            border-radius: 6px;
            border: 1px solid var(--c-border);
            font-size: .9rem;
            width: 100%;
            padding: .4rem .65rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .field-card select:focus,
        .field-card input:focus,
        .field-card textarea:focus {
            outline: none;
            border-color: var(--c-accent);
            box-shadow: 0 0 0 3px rgba(45,125,210,.15);
        }

        /* Badge de estado en los selects */
        .badge-estado {
            display: inline-block;
            font-size: .7rem;
            font-weight: 700;
            padding: .2em .55em;
            border-radius: 4px;
            margin-left: .4rem;
            vertical-align: middle;
        }
        .badge-bueno   { background: #d4edda; color: #155724; }
        .badge-regular { background: #fff3cd; color: #856404; }
        .badge-malo    { background: #f8d7da; color: #721c24; }
        .badge-na      { background: #e2e3e5; color: #383d41; }

        /* ── Botón de evidencia ── */
        .btn-evidencia {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            margin-top: .5rem;
            padding: .3rem .75rem;
            background: #fff0f0;
            border: 1px solid var(--c-danger);
            color: var(--c-danger);
            border-radius: 5px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        .btn-evidencia:hover { background: #fde8e8; }
        .btn-evidencia.has-evidencia {
            background: #eaf7ea;
            border-color: var(--c-success);
            color: var(--c-success);
        }

        /* ── Modal de evidencia ── */
        .evidencia-modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn .2s ease;
        }
        .evidencia-modal {
            background: #fff;
            border-radius: 12px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            overflow: hidden;
        }
        .evidencia-modal-header {
            background: var(--c-danger);
            color: #fff;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .evidencia-modal-header h5 { margin: 0; font-size: 1rem; font-weight: 700; }
        .evidencia-modal-body { padding: 1.25rem; }
        .evidencia-modal-footer {
            padding: .85rem 1.25rem;
            display: flex;
            justify-content: flex-end;
            gap: .5rem;
            border-top: 1px solid var(--c-border);
        }
        .btn-close-modal {
            background: none; border: none;
            color: #fff; font-size: 1.2rem;
            cursor: pointer; line-height: 1;
        }

        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

        /* ── Errores ── */
        .field-error { font-size: .78rem; color: var(--c-danger); margin-top: .25rem; display: block; }
    </style>
    @endonce

    {{-- ════════════════════════ HEADER CON PROGRESO ════════════════════════ --}}
    <div class="step-wrapper shadow">

        <div class="step-header">
            <div class="step-number">Paso {{ $currentStep }} de {{ $totalSteps }}</div>
            <h4>{{ $currentStepConfig['titulo'] }}</h4>

            {{-- Barra de progreso --}}
            <div class="progress-bar-track">
                <div class="progress-bar-fill" style="width: {{ $progressPercentage }}%"></div>
            </div>

            {{-- Puntos de progreso --}}
            <div class="steps-dots">
                @foreach($steps as $n => $step)
                    <div class="step-dot {{ $n < $currentStep ? 'done' : '' }} {{ $n === $currentStep ? 'active' : '' }}"
                         title="{{ $step['titulo'] }}"></div>
                @endforeach
            </div>
        </div>

        {{-- ════════════════════════ CUERPO DEL PASO ════════════════════════ --}}
        <div class="step-body">
            <div class="row">

                @if ($currentStep === 1)
                    @include('steps_form.entrada_principal')
                @elseif ($currentStep === 2)
                    @include('steps_form.hall')
                @elseif ($currentStep === 3)
                    @include('steps_form.cocina')
                @elseif ($currentStep === 4)
                    @include('steps_form.alcoba_principal')
                @elseif ($currentStep === 5)
                    @include('steps_form.bano_principal')
                @endif

            </div>

            {{-- Errores globales --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3 py-2">
                    <strong>Corrija los siguientes errores antes de continuar:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li style="font-size:.85rem">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- ════════════════════════ FOOTER NAVEGACIÓN ════════════════════════ --}}
        <div class="step-footer">
            <div>
                @if ($currentStep > 1)
                    <button wire:click="decrementSteps" class="btn-nav btn-prev">
                        ← Anterior
                    </button>
                @endif
            </div>

            <span class="counter">{{ $progressPercentage }}% completado</span>

            <div>
                @if ($currentStep < $totalSteps)
                    <button wire:click="incrementSteps" class="btn-nav btn-next" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="incrementSteps">Siguiente →</span>
                        <span wire:loading wire:target="incrementSteps">Guardando...</span>
                    </button>
                @else
                    <button wire:click="incrementSteps" class="btn-nav btn-submit" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="incrementSteps">
                            Guardar y continuar <i class="fas fa-angle-double-right"></i>
                        </span>
                        <span wire:loading wire:target="incrementSteps">Guardando...</span>
                    </button>
                @endif
            </div>
        </div>

    </div>{{-- .step-wrapper --}}


    {{-- ════════════════════════ MODAL DE EVIDENCIA (Livewire nativo) ══════════ --}}
    @if ($showEvidenciaModal)
    <div class="evidencia-modal-backdrop" wire:click.self="cerrarEvidenciaModal">
        <div class="evidencia-modal">

            <div class="evidencia-modal-header">
                <h5><i class="fas fa-camera-retro mr-1"></i> Agregar evidencia</h5>
                <button class="btn-close-modal" wire:click="cerrarEvidenciaModal">&times;</button>
            </div>

            <div class="evidencia-modal-body">

                {{-- Campo que se está documentando --}}
                <p style="font-size:.82rem; color:#6c757d; margin-bottom:1rem;">
                    Campo: <strong style="color:#1a3c5e">{{ str_replace(['_', '.'], ' ', $evidenciaCampoActual) }}</strong>
                </p>

                {{-- Foto --}}
                <div class="mb-3">
                    <label style="font-weight:600; font-size:.85rem; color:#383d41;">Foto de evidencia</label>
                    <input type="file"
                           wire:model="evidenciaFotoTemp"
                           accept="image/*"
                           class="form-control-file mt-1"
                           style="font-size:.85rem;">

                    {{-- Preview --}}
                    @if ($evidenciaFotoTemp)
                        <div class="mt-2">
                            <img src="{{ $evidenciaFotoTemp->temporaryUrl() }}"
                                 alt="Preview"
                                 style="max-width:100%; max-height:180px; border-radius:6px; border:1px solid #dee2e6;">
                        </div>
                    @elseif (isset($evidencias[$evidenciaCampoActual]['foto']))
                        <div class="mt-2" style="font-size:.8rem; color:#28a745;">
                            ✅ Ya tiene una foto guardada (sube otra para reemplazarla)
                        </div>
                    @endif

                    <div wire:loading wire:target="evidenciaFotoTemp" class="text-primary small mt-1">
                        <i class="fas fa-spinner fa-spin"></i> Procesando imagen...
                    </div>
                    @error('evidenciaFotoTemp')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Observación --}}
                <div>
                    <label style="font-weight:600; font-size:.85rem; color:#383d41;">Observación</label>
                    <textarea wire:model.lazy="evidenciaObservacionTemp"
                              rows="3"
                              class="form-control mt-1"
                              style="font-size:.88rem; resize:none;"
                              placeholder="Describe el daño o novedad encontrada..."></textarea>
                    @error('evidenciaObservacionTemp')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="evidencia-modal-footer">
                <button class="btn btn-sm btn-secondary" wire:click="cerrarEvidenciaModal">
                    Cancelar
                </button>
                <button class="btn btn-sm btn-danger" wire:click="guardarEvidencia" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="guardarEvidencia">
                        <i class="fas fa-save mr-1"></i> Guardar evidencia
                    </span>
                    <span wire:loading wire:target="guardarEvidencia">Guardando...</span>
                </button>
            </div>

        </div>
    </div>
    @endif

</div>
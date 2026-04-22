{{-- livewire/habitacion-form.blade.php --}}
<div>
    <input type="hidden" wire:model="propiedad">

    @once
    <style>
        /* ── Variables (consistentes con el resto del sistema) ── */
        :root {
            --hf-primary: #1a3c5e;
            --hf-accent:  #2d7dd2;
            --hf-success: #28a745;
            --hf-danger:  #dc3545;
            --hf-warning: #ffc107;
            --hf-muted:   #6c757d;
            --hf-bg:      #f4f6f9;
            --hf-border:  #dee2e6;
            --hf-radius:  10px;
        }

        /* ── Tarjeta por habitación ── */
        .hab-card {
            background: #fff;
            border-radius: var(--hf-radius);
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .hab-card-header {
            background: var(--hf-primary);
            color: #fff;
            padding: .85rem 1.4rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .hab-card-header h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .hab-card-body {
            padding: 1.25rem 1.4rem;
            background: var(--hf-bg);
        }

        /* ── Campo individual ── */
        .hf-field {
            background: #fff;
            border: 1px solid var(--hf-border);
            border-radius: 8px;
            padding: .75rem .9rem;
            margin-bottom: .5rem;
            transition: border-color .2s;
        }
        .hf-field:hover { border-color: var(--hf-accent); }
        .hf-field label {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--hf-primary);
            display: block;
            margin-bottom: .3rem;
        }
        .hf-field select {
            width: 100%;
            border-radius: 6px;
            border: 1px solid var(--hf-border);
            padding: .35rem .6rem;
            font-size: .88rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .hf-field select:focus {
            outline: none;
            border-color: var(--hf-accent);
            box-shadow: 0 0 0 3px rgba(45,125,210,.12);
        }
        .hf-field select.is-invalid { border-color: var(--hf-danger); }

        /* ── Botón evidencia ── */
        .btn-evidencia {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            margin-top: .45rem;
            padding: .25rem .65rem;
            background: #fff0f0;
            border: 1px solid var(--hf-danger);
            color: var(--hf-danger);
            border-radius: 5px;
            font-size: .75rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        .btn-evidencia:hover { background: #fde8e8; }
        .btn-evidencia.has-ev {
            background: #eaf7ea;
            border-color: var(--hf-success);
            color: var(--hf-success);
        }

        /* ── Campo "Otros" ── */
        .hf-otros { padding: .75rem .9rem; }
        .hf-otros label {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--hf-primary);
            display: block;
            margin-bottom: .3rem;
        }
        .hf-otros textarea {
            width: 100%;
            border-radius: 6px;
            border: 1px solid var(--hf-border);
            font-size: .88rem;
            padding: .4rem .7rem;
            resize: none;
            transition: border-color .2s;
        }
        .hf-otros textarea:focus {
            outline: none;
            border-color: var(--hf-accent);
            box-shadow: 0 0 0 3px rgba(45,125,210,.12);
        }

        /* ── Botón eliminar habitación ── */
        .btn-remove-hab {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,.5);
            color: #fff;
            border-radius: 6px;
            padding: .3rem .85rem;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            display: flex;
            align-items: center;
            gap: .3rem;
        }
        .btn-remove-hab:hover {
            background: rgba(220,53,69,.7);
            border-color: transparent;
        }

        /* ── Botón agregar habitación ── */
        .btn-add-hab {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .6rem 1.4rem;
            background: var(--hf-accent);
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s;
        }
        .btn-add-hab:hover { opacity: .88; }

        /* ── Botón submit ── */
        .btn-hf-submit {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .65rem 2rem;
            background: var(--hf-success);
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
        }
        .btn-hf-submit:hover  { opacity: .9; }
        .btn-hf-submit:active { transform: scale(.98); }
        .btn-hf-submit:disabled { opacity: .6; cursor: not-allowed; }

        /* ── Modal de evidencia ── */
        .ev-modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1055;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeInEv .2s ease;
        }
        .ev-modal {
            background: #fff;
            border-radius: 12px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            overflow: hidden;
        }
        .ev-modal-header {
            background: var(--hf-danger);
            color: #fff;
            padding: .9rem 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ev-modal-header h5 { margin: 0; font-size: .95rem; font-weight: 700; }
        .ev-modal-body   { padding: 1.2rem; }
        .ev-modal-footer {
            padding: .8rem 1.2rem;
            display: flex;
            justify-content: flex-end;
            gap: .5rem;
            border-top: 1px solid var(--hf-border);
        }
        .btn-ev-close {
            background: none; border: none;
            color: #fff; font-size: 1.2rem;
            cursor: pointer; line-height: 1;
        }
        .field-error { font-size: .77rem; color: var(--hf-danger); margin-top: .2rem; display: block; }

        @keyframes fadeInEv { from { opacity: 0; } to { opacity: 1; } }
    </style>
    @endonce

    @php
        $campos = [
            'puerta'              => 'Puerta',
            'cerradura'           => 'Cerradura',
            'llaves'              => 'Llaves',
            'ventana'             => 'Ventana',
            'vidrio'              => 'Vidrio ventana',
            'rieles'              => 'Rieles ventana',
            'cortinas'            => 'Cortinas / Persianas',
            'rejas'               => 'Rejas',
            'pisos'               => 'Pisos',
            'alfombras'           => 'Alfombras',
            'paredes'             => 'Paredes',
            'techos'              => 'Techos',
            'aires_acondicionados'=> 'Aires acondicionados',
            'ventiladores'        => 'Ventiladores',
            'anjeos'              => 'Anjeos',
            'tomacorrientes'      => 'Tomas eléctricas',
            'tomas_telefonicas'   => 'Tomas telefónicas',
            'tomas_television'    => 'Tomas TV',
            'interruptores'       => 'Interruptores',
            'rosetas'             => 'Rosetas',
            'lamparas'            => 'Lámparas',
            'bombillos'           => 'Bombillos',
            'guarda_escobas'      => 'Guarda escobas',
            'closet'              => 'Closet',
            'entrepanos'          => 'Entrepaños closet',
            'puertas'             => 'Puertas closet',
            'cajones'             => 'Cajones closet',
        ];
        $opciones = ['Bueno', 'Regular', 'Malo', 'N/A'];
    @endphp

    {{-- ═══════════════════════════════════════════════════════════
         HABITACIONES
    ════════════════════════════════════════════════════════════════ --}}
    @foreach ($habitaciones as $index => $habitacion)
    <div class="hab-card">

        {{-- Header de la habitación --}}
        <div class="hab-card-header">
            <h5>
                <i class="fas fa-door-open"></i>
                Habitación {{ $index + 1 }}
            </h5>
            @if (count($habitaciones) > 1)
            <button type="button"
                    wire:click="removeHabitacion({{ $index }})"
                    wire:confirm="¿Eliminar la Habitación {{ $index + 1 }}? Se perderán sus datos."
                    class="btn-remove-hab">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
            @endif
        </div>

        {{-- Campos ─ grid de 3 columnas --}}
        <div class="hab-card-body">
            <div class="row g-2">

                @foreach ($campos as $campo => $label)
                <div class="col-md-4">
                    <div class="hf-field">
                        <label>{{ $label }}</label>
                        <select wire:model="habitaciones.{{ $index }}.{{ $campo }}"
                                class="{{ $errors->has("habitaciones.$index.$campo") ? 'is-invalid' : '' }}">
                            @foreach ($opciones as $op)
                                <option value="{{ $op }}">{{ $op }}</option>
                            @endforeach
                        </select>

                        @error("habitaciones.$index.$campo")
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                        {{-- Botón evidencia cuando el valor es 'Malo' --}}
                        @if (($habitacion[$campo] ?? '') === 'Malo')
                            @php $tieneEv = isset($evidencias[$index][$campo]); @endphp
                            <button type="button"
                                    wire:click="abrirEvidencia({{ $index }}, '{{ $campo }}')"
                                    class="btn-evidencia {{ $tieneEv ? 'has-ev' : '' }}">
                                @if ($tieneEv)
                                    <i class="fas fa-check-circle"></i> Evidencia guardada
                                @else
                                    <i class="fas fa-camera-retro"></i> Añadir evidencia
                                @endif
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach

                {{-- Campo libre: Otros --}}
                <div class="col-md-12">
                    <div class="hf-otros">
                        <label>Otros / Observaciones</label>
                        <textarea wire:model.lazy="habitaciones.{{ $index }}.otros"
                                  rows="2"
                                  placeholder="Describe aquí otros detalles de esta habitación..."></textarea>
                        @error("habitaciones.$index.otros")
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

    {{-- ═══════════════════════════════════════════════════════════
         ACCIONES
    ════════════════════════════════════════════════════════════════ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <button type="button" wire:click="addHabitacion" class="btn-add-hab">
            <i class="fas fa-plus-circle"></i> Agregar habitación
        </button>

        <button type="button"
                wire:click="save"
                wire:loading.attr="disabled"
                class="btn-hf-submit">
            <span wire:loading.remove wire:target="save">
                Información baños <i class="fas fa-angle-double-right"></i>
            </span>
            <span wire:loading wire:target="save">
                <i class="fas fa-spinner fa-spin"></i> Guardando...
            </span>
        </button>

    </div>

    {{-- ═══════════════════════════════════════════════════════════
         MODAL DE EVIDENCIA
    ════════════════════════════════════════════════════════════════ --}}
    @if ($showEvidenciaModal)
    <div class="ev-modal-backdrop" wire:click.self="cerrarModal">
        <div class="ev-modal">

            <div class="ev-modal-header">
                <h5>
                    <i class="fas fa-camera-retro mr-1"></i>
                    Evidencia — {{ $evidenciaCampo ? str_replace('_', ' ', $evidenciaCampo) : '' }}
                    @if ($evidenciaIndex !== null)
                        <span style="opacity:.75; font-weight:400"> (Hab. {{ $evidenciaIndex + 1 }})</span>
                    @endif
                </h5>
                <button class="btn-ev-close" wire:click="cerrarModal">&times;</button>
            </div>

            <div class="ev-modal-body">

                {{-- Foto --}}
                <div class="mb-3">
                    <label style="font-weight:600; font-size:.85rem; display:block; margin-bottom:.4rem;">
                        Foto de evidencia
                    </label>
                    <input type="file"
                           wire:model="evidenciaFotoTemp"
                           accept="image/*"
                           class="form-control-file"
                           style="font-size:.85rem;">

                    {{-- Preview --}}
                    @if ($evidenciaFotoTemp)
                        <div class="mt-2">
                            <img src="{{ $evidenciaFotoTemp->temporaryUrl() }}"
                                 alt="Preview"
                                 style="max-width:100%; max-height:180px; border-radius:6px; border:1px solid #dee2e6; display:block;">
                        </div>
                    @elseif ($evidenciaIndex !== null && isset($evidencias[$evidenciaIndex][$evidenciaCampo]['foto']))
                        <p style="font-size:.8rem; color:#28a745; margin-top:.4rem;">
                            ✅ Ya tiene una foto (sube otra para reemplazarla)
                        </p>
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
                    <label style="font-weight:600; font-size:.85rem; display:block; margin-bottom:.4rem;">
                        Observación
                    </label>
                    <textarea wire:model.lazy="evidenciaObsTemp"
                              rows="3"
                              style="width:100%; border-radius:6px; border:1px solid #dee2e6; font-size:.88rem; padding:.4rem .7rem; resize:none;"
                              placeholder="Describe el daño o novedad encontrada..."></textarea>
                    @error('evidenciaObsTemp')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="ev-modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" wire:click="cerrarModal">
                    Cancelar
                </button>
                <button type="button"
                        class="btn btn-sm btn-danger"
                        wire:click="guardarEvidencia"
                        wire:loading.attr="disabled">
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
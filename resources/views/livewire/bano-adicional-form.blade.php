{{-- livewire/bano-adicional-form.blade.php --}}
<div>
    <input type="hidden" wire:model="propiedadId">

    @once
    <style>
        :root {
            --bf-primary: #1a3c5e;
            --bf-accent:  #2d7dd2;
            --bf-success: #28a745;
            --bf-danger:  #dc3545;
            --bf-muted:   #6c757d;
            --bf-bg:      #f4f6f9;
            --bf-border:  #dee2e6;
            --bf-radius:  10px;
        }
        .bano-card {
            background: #fff;
            border-radius: var(--bf-radius);
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .bano-card-header {
            background: var(--bf-primary);
            color: #fff;
            padding: .85rem 1.4rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .bano-card-header h5 { margin: 0; font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: .5rem; }
        .bano-card-body { padding: 1.25rem 1.4rem; background: var(--bf-bg); }

        /* Campo selector habitación */
        .bano-asignacion {
            background: #e8f0fe;
            border: 1px solid #c5d5f7;
            border-radius: 8px;
            padding: .85rem 1rem;
            margin-bottom: 1rem;
        }
        .bano-asignacion label { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--bf-primary); display: block; margin-bottom: .3rem; }
        .bano-asignacion select { width: 100%; border-radius: 6px; border: 1px solid #c5d5f7; padding: .4rem .7rem; font-size: .9rem; background: #fff; }

        /* Campo individual */
        .bf-field {
            background: #fff;
            border: 1px solid var(--bf-border);
            border-radius: 8px;
            padding: .75rem .9rem;
            margin-bottom: .5rem;
            transition: border-color .2s;
        }
        .bf-field:hover { border-color: var(--bf-accent); }
        .bf-field label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--bf-primary); display: block; margin-bottom: .3rem; }
        .bf-field select, .bf-field input, .bf-field textarea {
            width: 100%; border-radius: 6px; border: 1px solid var(--bf-border);
            padding: .35rem .6rem; font-size: .88rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .bf-field select:focus, .bf-field input:focus, .bf-field textarea:focus {
            outline: none; border-color: var(--bf-accent);
            box-shadow: 0 0 0 3px rgba(45,125,210,.12);
        }

        /* Botón evidencia */
        .btn-evidencia { display: inline-flex; align-items: center; gap: .3rem; margin-top: .45rem; padding: .25rem .65rem; background: #fff0f0; border: 1px solid var(--bf-danger); color: var(--bf-danger); border-radius: 5px; font-size: .75rem; font-weight: 600; cursor: pointer; transition: background .2s; }
        .btn-evidencia:hover { background: #fde8e8; }
        .btn-evidencia.has-ev { background: #eaf7ea; border-color: var(--bf-success); color: var(--bf-success); }

        /* Botones de acción */
        .btn-remove-bano { background: transparent; border: 1.5px solid rgba(255,255,255,.5); color: #fff; border-radius: 6px; padding: .3rem .85rem; font-size: .8rem; font-weight: 600; cursor: pointer; transition: background .2s; display: flex; align-items: center; gap: .3rem; }
        .btn-remove-bano:hover { background: rgba(220,53,69,.7); border-color: transparent; }
        .btn-add-bano { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.4rem; background: var(--bf-accent); color: #fff; border: none; border-radius: 7px; font-size: .9rem; font-weight: 700; cursor: pointer; transition: opacity .2s; }
        .btn-add-bano:hover { opacity: .88; }
        .btn-bf-submit { display: inline-flex; align-items: center; gap: .45rem; padding: .65rem 2rem; background: var(--bf-success); color: #fff; border: none; border-radius: 7px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: opacity .2s, transform .1s; }
        .btn-bf-submit:hover { opacity: .9; }
        .btn-bf-submit:disabled { opacity: .6; cursor: not-allowed; }

        /* Modal evidencia */
        .ev-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1055; display: flex; align-items: center; justify-content: center; animation: fadeEv .2s ease; }
        .ev-modal { background: #fff; border-radius: 12px; width: 100%; max-width: 460px; box-shadow: 0 20px 60px rgba(0,0,0,.25); overflow: hidden; }
        .ev-modal-hdr { background: var(--bf-danger); color: #fff; padding: .9rem 1.2rem; display: flex; justify-content: space-between; align-items: center; }
        .ev-modal-hdr h5 { margin: 0; font-size: .95rem; font-weight: 700; }
        .ev-modal-body { padding: 1.2rem; }
        .ev-modal-ftr { padding: .8rem 1.2rem; display: flex; justify-content: flex-end; gap: .5rem; border-top: 1px solid var(--bf-border); }
        .btn-ev-x { background: none; border: none; color: #fff; font-size: 1.2rem; cursor: pointer; line-height: 1; }
        .field-error { font-size: .77rem; color: var(--bf-danger); margin-top: .2rem; display: block; }
        @keyframes fadeEv { from { opacity: 0; } to { opacity: 1; } }
    </style>
    @endonce

    {{-- ═══════════════════════════════════ BAÑOS ═══════════════════════════ --}}
    @foreach ($banos as $index => $bano)
    <div class="bano-card">

        <div class="bano-card-header">
            <h5><i class="fas fa-bath"></i> Baño {{ $index + 1 }}</h5>
            @if (count($banos) > 1)
            <button type="button"
                    wire:click="removeBano({{ $index }})"
                    wire:confirm="¿Eliminar el Baño {{ $index + 1 }}?"
                    class="btn-remove-bano">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
            @endif
        </div>

        <div class="bano-card-body">

            {{-- Asignación a habitación + nombre del baño --}}
            <div class="row g-2 mb-2">
                <div class="col-md-5">
                    <div class="bano-asignacion">
                        <label><i class="fas fa-link mr-1"></i>¿Pertenece a una habitación?</label>
                        <select wire:model="banos.{{ $index }}.habitacion_id" style="color: #6c757d;">
                            <option value="">— Baño social / independiente —</option>
                            @foreach ($habitaciones as $hab)
                                <option value="{{ $hab['id'] }}">Habitación #{{ $loop->iteration }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bf-field" style="height:100%; display:flex; flex-direction:column; justify-content:center;">
                        <label>Nombre del baño (opcional)</label>
                        <input type="text"
                               wire:model.lazy="banos.{{ $index }}.nombre"
                               placeholder="Ej: Baño social, Baño visitas...">
                    </div>
                </div>
            </div>

            {{-- Campos select en grid de 3 --}}
            <div class="row g-2">
                @foreach ($camposSelect as $campo => $label)
                <div class="col-md-4">
                    <div class="bf-field">
                        <label>{{ $label }}</label>
                        <select wire:model="banos.{{ $index }}.{{ $campo }}"
                                class="{{ $errors->has("banos.$index.$campo") ? 'is-invalid' : '' }}">
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                            <option value="N/A">N/A</option>
                        </select>
                        @error("banos.$index.$campo")
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                        @if (($bano[$campo] ?? '') === 'Malo')
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

                {{-- Otros --}}
                <div class="col-md-12">
                    <div class="bf-field">
                        <label>Otros / Observaciones</label>
                        <textarea wire:model.lazy="banos.{{ $index }}.otros"
                                  rows="2"
                                  placeholder="Describe aquí otros detalles de este baño..."></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

    {{-- ═══════════════════════════ ACCIONES ════════════════════════════════ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <button type="button" wire:click="addBano" class="btn-add-bano">
            <i class="fas fa-plus-circle"></i> Agregar baño
        </button>
        <button type="button" wire:click="save" wire:loading.attr="disabled" class="btn-bf-submit">
            <span wire:loading.remove wire:target="save">
                <i class="fas fa-check-circle"></i> Guardar y finalizar
            </span>
            <span wire:loading wire:target="save">
                <i class="fas fa-spinner fa-spin"></i> Guardando...
            </span>
        </button>
    </div>

    {{-- ═══════════════════════════ MODAL EVIDENCIA ═════════════════════════ --}}
    @if ($showEvidenciaModal)
    <div class="ev-backdrop" wire:click.self="cerrarModal">
        <div class="ev-modal">
            <div class="ev-modal-hdr">
                <h5>
                    <i class="fas fa-camera-retro mr-1"></i>
                    Evidencia — {{ $evidenciaCampo ? str_replace('_', ' ', $evidenciaCampo) : '' }}
                    @if ($evidenciaIndex !== null)
                        <span style="opacity:.75; font-weight:400">(Baño {{ $evidenciaIndex + 1 }})</span>
                    @endif
                </h5>
                <button class="btn-ev-x" wire:click="cerrarModal">&times;</button>
            </div>
            <div class="ev-modal-body">
                <div class="mb-3">
                    <label style="font-weight:600; font-size:.85rem; display:block; margin-bottom:.4rem;">Foto de evidencia</label>
                    <input type="file" wire:model="evidenciaFotoTemp" accept="image/*" class="form-control-file" style="font-size:.85rem;">
                    @if ($evidenciaFotoTemp)
                        <img src="{{ $evidenciaFotoTemp->temporaryUrl() }}" style="max-width:100%; max-height:180px; border-radius:6px; margin-top:.5rem; border:1px solid #dee2e6; display:block;">
                    @elseif ($evidenciaIndex !== null && isset($evidencias[$evidenciaIndex][$evidenciaCampo]['foto']))
                        <p style="font-size:.8rem; color:#28a745; margin-top:.4rem;">✅ Ya tiene foto (sube otra para reemplazarla)</p>
                    @endif
                    <div wire:loading wire:target="evidenciaFotoTemp" class="text-primary small mt-1">
                        <i class="fas fa-spinner fa-spin"></i> Procesando...
                    </div>
                    @error('evidenciaFotoTemp') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="font-weight:600; font-size:.85rem; display:block; margin-bottom:.4rem;">Observación</label>
                    <textarea wire:model.lazy="evidenciaObsTemp" rows="3"
                              style="width:100%; border-radius:6px; border:1px solid #dee2e6; font-size:.88rem; padding:.4rem .7rem; resize:none;"
                              placeholder="Describe el daño o novedad..."></textarea>
                    @error('evidenciaObsTemp') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="ev-modal-ftr">
                <button type="button" class="btn btn-sm btn-secondary" wire:click="cerrarModal">Cancelar</button>
                <button type="button" class="btn btn-sm btn-danger" wire:click="guardarEvidencia" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="guardarEvidencia"><i class="fas fa-save mr-1"></i> Guardar</span>
                    <span wire:loading wire:target="guardarEvidencia">Guardando...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="bg-white py-8 px-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-2xl border border-gray-100 sm:px-10 transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] duration-300">
            
            <form wire:submit.prevent='submit' class="space-y-6">
                @csrf

                <div>
                    <label for="tenant_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Tu nombre completo
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" wire:model="tenant_name" id="tenant_name" required
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition-all text-sm" 
                            placeholder="Ej. Juan Pérez">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <div>
                        <label for="tenant_phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Número de WhatsApp
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-sm font-semibold text-gray-400 select-none">+57</span>
                            </div>
                            <input type="tel" wire:model="tenant_phone" id="tenant_phone" required 
                                class="block w-full pl-14 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition-all text-sm" 
                                placeholder="300 123 4567">
                        </div>
                    </div>

                    <div>
                        <label for="contract_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Número de Contrato <small class="text-muted">(opcional)</small>
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <input type="text" wire:model="contract_number" id="contract_number" required 
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition-all text-sm" 
                                placeholder="Ej. CO-4820">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="tenant_email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Correo electrónico <span class="text-gray-400 font-normal lowercase">(para el envío de tu radicado)</span>
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input type="email" wire:model="tenant_email" id="tenant_email" required 
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition-all text-sm" 
                            placeholder="usuario@correo.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Selector de Categoría (Padre) -->
                    <div>
                        <label for="category" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Categoría de la falla
                        </label>
                        <select wire:model.live="category" id="category" required
                            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition-all text-sm">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selector de Tipo de Problema (Hijo Dependiente) -->
                    <div>
                        <label for="issue_type" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Tipo de problema específico
                        </label>
                        <select wire:model="issue_type" id="issue_type" required {{ empty($category) ? 'disabled' : '' }}
                            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition-all text-sm disabled:opacity-50 disabled:bg-gray-100">
                            <option value="">Selecciona el problema específico</option>
                            @foreach($issue_types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- Descripción adicional -->
                <div id="description-container" class="hidden">
                    <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Descripción adicional
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition-all text-sm"
                        placeholder="Describe el problema con más detalle..."></textarea>
                </div>

                <div class="flex items-start bg-slate-50/50 p-3.5 rounded-xl border border-gray-100/80">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    </div>
                    <div class="ml-3 text-xs leading-5">
                        <label for="terms" class="font-medium text-gray-500 cursor-pointer select-none">
                            Autorizo el tratamiento de mis datos personales de acuerdo con la <a href="#" class="text-blue-600 hover:underline font-semibold">política de privacidad</a>.
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center items-center space-x-3 py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#002855] hover:bg-[#001f42] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all transform active:scale-[0.99] cursor-pointer group">
                        <svg class="h-5 w-5 fill-current text-white transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-4.846c1.66.986 3.296 1.489 4.973 1.491 5.482 0 9.938-4.46 9.94-9.94.001-2.65-1.02-5.141-2.871-6.995C16.832 1.859 14.34 .839 11.7 1.839c-5.485 0-9.94 4.46-9.942 9.941-.001 1.814.501 3.493 1.493 5.148l-.994 3.633 3.73-.978zm11.567-5.32c-.29-.145-1.71-.846-1.976-.941-.266-.096-.46-.145-.652.146-.192.29-.741.941-.908 1.134-.167.192-.334.217-.624.073-.29-.145-1.223-.45-2.33-1.439-.86-.767-1.44-1.716-1.608-2.008-.168-.291-.018-.447.127-.591.13-.13.29-.34.436-.509.145-.17.193-.291.29-.485.097-.193.048-.364-.025-.509-.072-.145-.652-1.573-.893-2.152-.234-.564-.472-.488-.652-.497-.168-.008-.363-.01-.559-.01-.195 0-.513.073-.781.364-.268.292-1.023 1.001-1.023 2.441 0 1.44 1.047 2.839 1.192 3.033.145.193 2.062 3.149 4.994 4.415.698.301 1.244.48 1.67.615.702.224 1.342.193 1.848.118.563-.083 1.71-.699 1.951-1.374.24-.674.24-1.253.168-1.374-.072-.121-.266-.193-.556-.338z"/>
                        </svg>
                        <span>Enviar y continuar por WhatsApp</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
